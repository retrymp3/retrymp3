<?php
session_start();

	include("connection.php");
	include("functions.php");
	
	$user_data=check_login($con);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Shop!</title>
        <meta name="description" content="This is the description">
        <link rel="stylesheet" href="./css/shop.css" />
        <script src="./js/main.js" async></script>
    </head>
    <body>
	<header>
	<div class="main">
	 <ul>
	 	<li class="active"><a href="shop.php">Shop</a></li>
		<li><a href="index.html">Home</a></li>
	 	<li><a href="login.php">Login</a></li>
	 	<li><a href="sign-up.php">Signup</a></li>
	 	<li><a href="about.html">About</a></li>
	 </ul>
	</div>
            </nav>
            <h1 class="band-name band-name-large">SPORTS</h1>
        </header>
        <section class="container content-section">
            <h2 class="section-header">SHOES</h2>
            <div class="shop-items">
                <div class="shop-item">
                    <span class="shop-item-title">Adiddas</span>
                    <img class="shop-item-image" src="./img/addidas1.jpeg">
                    <div class="shop-item-details">
                        <span class="shop-item-price">Rs.1199</span>
                        <button class="btn btn-primary shop-item-button" type="button">ADD TO CART</button>
                    </div>
                </div>
                <div class="shop-item">
                    <span class="shop-item-title">Nike</span>
                    <img class="shop-item-image" src="./img/nike1.jpeg">
                    <div class="shop-item-details">
                        <span class="shop-item-price">Rs.1499</span>
                        <button class="btn btn-primary shop-item-button"type="button">ADD TO CART</button>
                    </div>
                </div>
                <div class="shop-item">
                    <span class="shop-item-title">UCB</span>
                    <img class="shop-item-image" src="./img/ucb1.jpeg">
                    <div class="shop-item-details">
                        <span class="shop-item-price">Rs.1999</span>
                        <button class="btn btn-primary shop-item-button" type="button">ADD TO CART</button>
                    </div>
                </div>
                <div class="shop-item">
                    <span class="shop-item-title">Air jordan</span>
                    <img class="shop-item-image" src="./img/air_jordan.jpeg">
                    <div class="shop-item-details">
                        <span class="shop-item-price">Rs.4999</span>
                        <button class="btn btn-primary shop-item-button" type="button">ADD TO CART</button>
                    </div>
                </div>
            </div>
        </section>
        <section class="container content-section">
            <h2 class="section-header">CLOTHING</h2>
            <div class="shop-items">
                <div class="shop-item">
                    <span class="shop-item-title">T-Shirt</span>
                    <img class="shop-item-image" src="./img/t-shirt1.jpeg">
                    <div class="shop-item-details">
                        <span class="shop-item-price">Rs.399</span>
                        <button class="btn btn-primary shop-item-button" type="button">ADD TO CART</button>
                    </div>
                </div>
                <div class="shop-item">
                    <span class="shop-item-title">Track Pants</span>
                    <img class="shop-item-image" src="./img/track-pants.jpeg">
                    <div class="shop-item-details">
                        <span class="shop-item-price">Rs.599</span>
                        <button class="btn btn-primary shop-item-button" type="button">ADD TO CART</button>
                    </div>
                </div>
            </div>
        </section>
        <section class="container content-section">
            <h2 class="section-header">CART</h2>
            <div class="cart-row">
                <span class="cart-item cart-header cart-column">ITEM</span>
                <span class="cart-price cart-header cart-column">PRICE</span>
                <span class="cart-quantity cart-header cart-column">QUANTITY</span>
            </div>
            <div class="cart-items">
            </div>
            <div class="cart-total">
                <strong class="cart-total-title">Total</strong>
                <span class="cart-total-price">Rs.0</span>
            </div>
            <button class="btn btn-primary btn-purchase" type="button">PURCHASE</button>
     
                    </li>
                </ul>
            </div>
        </footer>
    </body>
</html>
