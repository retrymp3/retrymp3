#!/usr/bin/env python3
"""Regenerate the open-source contributions table in README.md.

Finds pull requests authored by GH_USERNAME in repositories owned by someone
else, then renders them as a markdown table ordered by the star count of the
repository they landed in. Each row links to the pull request itself.
"""

from __future__ import annotations

import json
import os
import re
import sys
import urllib.error
import urllib.parse
import urllib.request
from collections import defaultdict

API_ROOT = "https://api.github.com"
USERNAME = os.environ.get("GH_USERNAME", "retrymp3")
TOKEN = os.environ.get("GITHUB_TOKEN", "")
README_PATH = os.environ.get("README_PATH", "README.md")

# Unmerged PRs are excluded by default: a closed-without-merge PR is not a
# contribution the repository accepted.
INCLUDE_UNMERGED = os.environ.get("INCLUDE_UNMERGED", "false").strip().lower() in {
    "1",
    "true",
    "yes",
}

# Repos to never list, e.g. forks used only as scratch space. Comma separated
# "owner/name" values.
EXCLUDE_REPOS = {
    item.strip().lower()
    for item in os.environ.get("EXCLUDE_REPOS", "").split(",")
    if item.strip()
}

START_MARKER = "<!-- CONTRIBUTIONS:START -->"
END_MARKER = "<!-- CONTRIBUTIONS:END -->"


class GitHubError(RuntimeError):
    pass


def api_get(path, params=None):
    url = "{}/{}".format(API_ROOT, path.lstrip("/"))
    if params:
        url = "{}?{}".format(url, urllib.parse.urlencode(params))

    request = urllib.request.Request(url)
    request.add_header("Accept", "application/vnd.github+json")
    request.add_header("X-GitHub-Api-Version", "2022-11-28")
    request.add_header("User-Agent", "{}-profile-updater".format(USERNAME))
    if TOKEN:
        request.add_header("Authorization", "Bearer {}".format(TOKEN))

    try:
        with urllib.request.urlopen(request, timeout=30) as response:
            return json.load(response)
    except urllib.error.HTTPError as exc:
        body = exc.read().decode("utf-8", "replace")[:400]
        raise GitHubError("GET {} failed: {} {}".format(url, exc.code, body)) from exc
    except urllib.error.URLError as exc:
        raise GitHubError("GET {} failed: {}".format(url, exc.reason)) from exc


def search_pull_requests():
    """Return PRs authored by USERNAME in repositories USERNAME does not own."""
    query_parts = ["is:pr", "author:{}".format(USERNAME), "-user:{}".format(USERNAME)]
    if not INCLUDE_UNMERGED:
        query_parts.append("is:merged")
    query = " ".join(query_parts)

    items = []
    page = 1
    while True:
        payload = api_get(
            "search/issues",
            {
                "q": query,
                "per_page": 100,
                "page": page,
                "advanced_search": "true",
            },
        )
        batch = payload.get("items", [])
        items.extend(batch)

        total = payload.get("total_count", 0)
        # The search API caps out at 1000 results.
        if len(batch) < 100 or len(items) >= min(total, 1000):
            break
        page += 1

    return items


def repo_full_name(item):
    url = item.get("repository_url", "")
    return url.replace("{}/repos/".format(API_ROOT), "", 1)


def fetch_repo(full_name, cache):
    if full_name not in cache:
        try:
            cache[full_name] = api_get("repos/{}".format(full_name))
        except GitHubError as exc:
            # A repo can be deleted or made private after a PR merges. Keep the
            # PR listed rather than dropping the contribution silently.
            print("warning: {}".format(exc), file=sys.stderr)
            cache[full_name] = {}
    return cache[full_name]


def escape_cell(text):
    return (text or "").replace("|", "\\|").replace("\n", " ").strip()


def format_stars(count):
    if count >= 1000:
        return "{:,}".format(count)
    return str(count)


def build_rows(items):
    grouped = defaultdict(list)
    for item in items:
        full_name = repo_full_name(item)
        if not full_name or full_name.lower() in EXCLUDE_REPOS:
            continue
        grouped[full_name].append(item)

    repo_cache = {}
    repos = []
    for full_name, prs in grouped.items():
        repo = fetch_repo(full_name, repo_cache)
        prs.sort(key=lambda pr: pr.get("number", 0), reverse=True)
        repos.append(
            {
                "full_name": full_name,
                "stars": repo.get("stargazers_count", 0),
                "url": repo.get("html_url", "https://github.com/{}".format(full_name)),
                "prs": prs,
            }
        )

    repos.sort(key=lambda repo: (-repo["stars"], repo["full_name"].lower()))
    return repos


def render_table(repos):
    if not repos:
        return "_No external contributions found yet._"

    lines = [
        "| Repository | Stars | Contribution |",
        "| :--- | ---: | :--- |",
    ]

    for repo in repos:
        stars = format_stars(repo["stars"])
        for index, pr in enumerate(repo["prs"]):
            repo_cell = (
                "**[{}]({})**".format(escape_cell(repo["full_name"]), repo["url"])
                if index == 0
                else ""
            )
            star_cell = stars if index == 0 else ""
            title = escape_cell(pr.get("title", "Pull request"))
            merged = (pr.get("pull_request") or {}).get("merged_at")
            state = "" if merged or not INCLUDE_UNMERGED else " _(unmerged)_"
            lines.append(
                "| {} | {} | [#{} {}]({}){} |".format(
                    repo_cell,
                    star_cell,
                    pr.get("number", "?"),
                    title,
                    pr.get("html_url", ""),
                    state,
                )
            )

    return "\n".join(lines)


def splice_into_readme(table, path):
    with open(path, "r", encoding="utf-8") as handle:
        original = handle.read()

    if START_MARKER not in original or END_MARKER not in original:
        raise SystemExit(
            "{} is missing the {} / {} markers".format(path, START_MARKER, END_MARKER)
        )

    pattern = re.compile(
        "{}.*?{}".format(re.escape(START_MARKER), re.escape(END_MARKER)),
        re.DOTALL,
    )
    replacement = "{}\n\n{}\n\n{}".format(START_MARKER, table, END_MARKER)
    updated = pattern.sub(lambda _: replacement, original, count=1)

    if updated == original:
        print("README.md already up to date")
        return False

    with open(path, "w", encoding="utf-8") as handle:
        handle.write(updated)
    print("README.md updated")
    return True


def main():
    try:
        items = search_pull_requests()
    except GitHubError as exc:
        raise SystemExit("error: {}".format(exc))

    repos = build_rows(items)
    for repo in repos:
        print(
            "{:>9} stars  {}  ({} PR)".format(
                format_stars(repo["stars"]), repo["full_name"], len(repo["prs"])
            )
        )

    splice_into_readme(render_table(repos), README_PATH)
    return 0


if __name__ == "__main__":
    sys.exit(main())
