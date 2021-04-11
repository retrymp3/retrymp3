 if (document.readyState == 'loading') { /* checking wether the html body loads before the js. */ 
    document.addEventListener('DOMContentLoaded', ready) /* listening for DOMContentLoaded. Which is an event fired when the intial html doc has been completely loaded, so if it is present, runs the ready function.  */
} else {
    ready() /* if it's already loaded, runs ready() */
}

function ready() { /* function to setup all the buttons */
    var removeCartItemButtons = document.getElementsByClassName('btn-danger') /* stores the buttons with the class 'btn-danger' */
    for (var i = 0; i < removeCartItemButtons.length; i++) { /* looping through all the buttons with class 'btn-danger' */
        var button = removeCartItemButtons[i]
        button.addEventListener('click', removeCartItem) /* listening for the 'click' event and removing the cart-item of cart */
    }

    var quantityInputs = document.getElementsByClassName('cart-quantity-input') /* function to control the quantity and add update the total price */
    for (var i = 0; i < quantityInputs.length; i++) { /* looping through all the elements with class, 'cart-quantity-input' */
        var input = quantityInputs[i]
        input.addEventListener('change', quantityChanged) /* listens for the 'change' event which is fired when we change the quantity, and runs the quantityChanged function. */
    }

    var addToCartButtons = document.getElementsByClassName('shop-item-button') /* setting the add to cart button */
    for (var i = 0; i < addToCartButtons.length; i++) {
        var button = addToCartButtons[i]
        button.addEventListener('click', addToCartClicked)
    }

    document.getElementsByClassName('btn-purchase')[0].addEventListener('click', purchaseClicked)
}

function purchaseClicked() { /* function for removing all items from cart, and alert the user */
    alert('Thank you for your purchase')
    var cartItems = document.getElementsByClassName('cart-items')[0]
    while (cartItems.hasChildNodes()) { /* when a child is present insdie the cart-items, looping through all the children inside the cart */
        cartItems.removeChild(cartItems.firstChild) /* removes the first child inside the cart-item */
    }
    updateCartTotal()
}

function removeCartItem(event) { /* function for removing the cart contents */
    var buttonClicked = event.target /* event.target is a property on the event-object and it will be whatever the button we clicked on. */
    buttonClicked.parentElement.parentElement.remove() /* removing the parent of the parent div to remove full of the cart row */
    updateCartTotal()
}

function quantityChanged(event) { /* function to be executed when we change the quantity */
    var input = event.target /* sets the event.target into input variable which will be the input element */
    if (isNaN(input.value) || input.value <= 0) { /* checks to see if inp is a number and checks if inp is less than or equal to 0. */
        input.value = 1 /* seting the quantity value to 1. */
    }
    updateCartTotal()
}

function addToCartClicked(event) {
    var button = event.target /* sets the 'add to cart' button to button variable. */
    var shopItem = button.parentElement.parentElement /* sets the parent of the parent div to get the actual 'shop-item' */
    var title = shopItem.getElementsByClassName('shop-item-title')[0].innerText
    var price = shopItem.getElementsByClassName('shop-item-price')[0].innerText
    var imageSrc = shopItem.getElementsByClassName('shop-item-image')[0].src /* getting the source of the image */
    addItemToCart(title, price, imageSrc)
    updateCartTotal()
}

function addItemToCart(title, price, imageSrc) { /* function to add a row to the cart*/
    var cartRow = document.createElement('div') /* create a 'div' element */
    cartRow.classList.add('cart-row')
    var cartItems = document.getElementsByClassName('cart-items')[0]
	/* generating a cart row. using back ticks for html which will be representd as a string, but we can use it for multiple lines. */
    var cartRowContents = ` 
        <div class="cart-item cart-column">
            <img class="cart-item-image" src="${imageSrc}" width="100" height="100">
            <span class="cart-item-title">${title}</span>
        </div>
        <span class="cart-price cart-column">${price}</span>
        <div class="cart-quantity cart-column">
            <input class="cart-quantity-input" type="number" value="1">
            <button class="btn btn-danger" type="button">REMOVE</button>
        </div>`
    cartRow.innerHTML = cartRowContents /* (innerHTML)since we are using html, insted of strings */
    cartItems.append(cartRow) /* adding the cart-row to the end of the cart-items */
    cartRow.getElementsByClassName('btn-danger')[0].addEventListener('click', removeCartItem) /* adding the event listener for remove button after loading the page */
    cartRow.getElementsByClassName('cart-quantity-input')[0].addEventListener('change', quantityChanged) /* adding the event listener for quantity after loading the page */
}

function updateCartTotal() { /* function to update the 'total' field */
    var cartItemContainer = document.getElementsByClassName('cart-items')[0] /* returns an array of elements with class name 'cart-items', we want the first one so '[0]' */
    var cartRows = cartItemContainer.getElementsByClassName('cart-row') /* elements with the class 'cart-row' inside the 'cartItemContainer' will be returned */
    var total = 0 /* initialising var */
    for (var i = 0; i < cartRows.length; i++) { /* looping over all the 'cart-rows' */
        var cartRow = cartRows[i]
        var priceElement = cartRow.getElementsByClassName('cart-price')[0] /* gets the element that has the 'cart-price' class, we want the first one so '[0]' */
        var quantityElement = cartRow.getElementsByClassName('cart-quantity-input')[0]
        var price = parseFloat(priceElement.innerText.replace('Rs.', '')) /* gets the actual price by fetching the text inside the element, (replace())removes the 'Rs.', (parseFloat())turns the string into a float */
        var quantity = quantityElement.value /* since it is an input we need the value property insted of innerText */
        total = total + (price * quantity) /* adds the 'price * quantity' for all cart-rows and gives total at the end */
    }
    document.getElementsByClassName('cart-total-price')[0].innerText = 'Rs.' + total /* puts the total price as innertext into the element with class 'cart-total-price'. */
}
