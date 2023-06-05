// przechwytywanie buttona
    document.getElementById('search-button').addEventListener('click', function() {
    var searchQuery = document.getElementById('search-input').value;
    searchProducts(searchQuery);
});
$(document).ready(function() {

    $('.search__input').keydown(function(event) {
        if (event.keyCode == 13) {
            var searchQuery = document.getElementById('search-input').value;
            searchProducts(searchQuery);
            return false;
        }
    });

});


// wyszukaj produkty
function searchProducts(query) {
    $.ajax({
        url: 'search_products.php', // Replace with the actual search endpoint URL
        type: 'GET',
        data: { query: query },
        success: function(response) {
            // Handle the search results
            var productsContainer = document.querySelector('.products');
            productsContainer.innerHTML = response;
        },
        error: function(xhr, status, error) {
            console.error('Error: ' + error);
        }
    });
}
//usuwanie z koszyka
function removeFromCart(cartID) {
    console.log(cartID);

    $.ajax({
        url: 'remove_from_cart.php',
        type: 'POST',
        data: { cartID: cartID },
        success: function(response) {
            console.log(response);
            refreshIncludedFile();
        },
        error: function(xhr, status, error) {
            console.error('Error: ' + error);

        }
    });
}
function refreshIncludedFile(callback) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            document.getElementById("includedFileContainer").innerHTML = this.responseText;
            callback(); // Call the callback function after updating the container
        }
    };
    xhttp.open("GET", "dashboardmenu.php", true);
    xhttp.send();
}

//dodaj do koszyka
function addToCart(productCode) {
    $.ajax({
        url: 'add_to_cart.php',
        type: 'POST',
        data: { productCode: productCode },
        success: function(response) {
            console.log(response);
            refreshIncludedFile(function() {
                showMessage("Produkt dodany do koszyka");
            });
        },
        error: function(xhr, status, error) {
            console.error('Error: ' + error);
        }
    });
}
function showMessage(message) {
    var messageContainer = document.getElementById("message-container");
    messageContainer.innerHTML = message;
    messageContainer.style.display = "block";
    messageContainer.style.width = "200px";
    messageContainer.style.backgroundColor = "green";
   messageContainer.style.color="white";

    messageContainer.style.borderRadius = "40px";
    messageContainer.style.textAlign = "center";
    messageContainer.style.padding = "7px";
    messageContainer.style.fontFamily="Lato, sans serif";
    messageContainer.style.fontWeight="bold";

    setTimeout(function() {
        messageContainer.innerHTML = "";
        messageContainer.style.display = "none";
    }, 3000); // Display for 3 seconds
}
