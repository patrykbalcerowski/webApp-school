<html lang="pl-PL">
<head>
    <?php
    session_start();

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
        header("location: index.php");
        exit();
    }
    require_once "functions.php";
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Złóż zamówienie</title>
</head>

<body class="dashboardbody" style="overflow-x: hidden;
        overflow-y: hidden;">
<div class="container">
    <div class="includedFileContainer" id="includedFileContainer">
        <?php include 'dashboardmenu.php'; ?>
    </div>
    <div class="content-order">
        <div class="delivery-side">
            <div class="delivery-title">
                <p>1. DOSTAWA</p>
            </div>
            <div class="dostawa-content">
                <div>
                    <label for="deliveryType">Wybierz typ wysyłki:</label>
                    <div class="delivery-type-options">
                        <input style="width: 20px;height: 20px" type="radio" id="toCompany" name="deliveryType" value="company"
                               onclick="toggleAddressSection(false)" checked>
                        <label for="toCompany">Wpisz nowy adres</label>
                        <input style="width: 20px;height: 20px" type="radio" id="toCustomer" name="deliveryType" value="customer"
                               onclick="toggleAddressSection(true)">
                        <label for="toCustomer">Wybierz z książki adresowej</label>
                    </div>
                </div>
                <div class="newAddressSection" id="newAddressSection">
                    <div>
                        <input type="text" id="companyName" name="companyName" placeholder="Nazwa firmy">
                    </div>
                    <div>
                        <input type="text" id="street" name="street" placeholder="Ulica i numer budynku">
                    </div>
                    <div>
                        <input type="text" id="postalCode" name="postalCode" placeholder="Kod pocztowy">
                    </div>
                    <div>
                        <input type="text" id="city" name="city" placeholder="Miasto">
                    </div>
                    <label>Czy chcesz zapisać adres w książce?</label>
                    <input type="checkbox" id="newAddressCheckbox" style="width: 20px;height: 20px">
                </div>
                <div id="addressBookSection" style="display: none;">
                    <label for="shippingAddress">Adres wysyłkowy:</label>
                    <select id="shippingAddress">
                        <?php
                        $queryAddress = pg_query($link, "SELECT sa.\"companyName\",sa.\"addressID\" from \"shippingAddresses\" as sa join \"CustomerToAddress\" as cta on cta.\"shippingAddressID\"=sa.\"addressID\" join \"Customers\" as c on c.\"companyID\"=cta.\"companyID\" where cta.\"companyID\"='$companyID';");
                        while ($row = pg_fetch_assoc($queryAddress)) {
                            echo "<option value='" . $row['addressID'] . "'>" . $row['companyName'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="courier">Wybierz kuriera:</label>
                    <div class="courier-options">
                        <button type="button" id="dhlCourier" onclick="selectCourier('DHL')"
                                onfocus="highlightButton(this.id)">DHL
                            12 zł
                        </button>
                        <button type="button" id="dpdCourier" onclick="selectCourier('DPD')"
                                onfocus="highlightButton(this.id)">DPD 15 zł
                        </button>
                        <button type="button" id="inpostCourier" onclick="selectCourier('Inpost')"
                                onfocus="highlightButton(this.id)">Inpost 9,90 zł
                        </button>
                    </div>
                </div>
            </div>

        </div>
        <div class="products-side">
            <div class="products-side-title">
                <p>2. SZCZEGÓŁY ZAMÓWIENIA</p>
            </div>
            <div class="products-side-content">
                <?php
                $shopCart = pg_query($link, "SELECT \"productName\", \"unitPrize\", \"img\", \"unitCount\", \"cartID\" FROM products as p JOIN \"shoppingCart\" as s on p.\"productCode\"=s.\"productCode\" JOIN \"users\" as u on s.\"userID\"=u.\"userID\"  WHERE s.\"userID\" = '$userID';");
                $i = 0;
                $allValue=0;
                while ($row = pg_fetch_assoc($shopCart)) {
                    echo "<div class='product-cart' style='width: 100%'>";
                    echo "<img class='produkty-cart' src='pictures/" . $row['img'] . "' alt='Product Image'>";
                    echo "<h2 style='display: inline-block;padding-left: 30px;float:right;margin: auto'>" . $row['productName'] . "</h2>";
                    echo "<p style='display: inline-block;padding-left: 30px;float:right;margin: auto'>Cena: " . $row['unitPrize'] . " zł</p>";
                    echo "<p style='display: inline-block;padding-left: 30px;float:right;margin: auto'>Ilość: " . $row['unitCount'] . "</p>";
                    echo "<p style='display: inline-block ;padding-left: 30px;float:right;margin: auto'>Wartość: " . ($row['unitCount'] * $row['unitPrize']) . "</p>";
                    $allValue += ($row['unitCount'] * $row['unitPrize']);

                    echo "</div>";
                    $i++;
                }
                ?>
            </div>

        </div>
        <div class="payment-section" style="padding-top: 10px">
            <div class="products-side-title">
                <p>3. METODA PŁATNOŚCI</p>
            </div>
            <div class="payment-content">
                <label for="payment">Wybierz metodę płatności:</label>
                <div class="payment-options">
                    <button type="button" id="przelew24Payment" onclick="selectPayment('przelew24')"
                            onfocus="highlightButton(this.id)">Przelew24
                    </button>
                    <button type="button" id="odroczonyPayment" onclick="selectPayment('odroczony')"
                            onfocus="highlightButton(this.id)">Odroczony
                    </button>
                    <button type="button" id="paypalPayment" onclick="selectPayment('PayPal')"
                            onfocus="highlightButton(this.id)">PayPal
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="suma">
        <p id="sumaValue"></p>
        <p style="display: none" id="sumaValue2">Suma: <?php echo"$allValue"; ?> zł</p>
    </div>
    <div class="button-zamowienie">
        <button onclick="submitOrder()">
            ZŁÓŻ ZAMÓWIENIE
        </button>
    </div>
</div>
<style>
    label{
        font-weight: bolder;
        font-size: 20px;
    }
    .courier-options button{
        padding: 20px;
        background-color: #4CAF50;
        font-size: large;
        font-weight: bolder;
        color: white;
    }
    .payment-options button{
        padding: 20px;
        background-color: #4CAF50;
        font-size: large;
        font-weight: bolder;
        color: white;
    }
    .products-side {
        width: 60%;
        float: right;
        max-height: 600px;
        height: auto;
        outline: #d5dcdc 2px solid;
        margin-bottom: 20px;
        margin-right: 20px;
        padding-top: 10px;
        margin-left: -20px;
        flex: 1;
    }
    .suma p{
        font-family: Arial, sans-serif;
        font-size: 20px;
    }
    .button-zamowienie button{
        padding: 20px;
        background-color: #4CAF50;
        font-size: large;
        font-weight: bolder;
        color: white;
        border-radius: 20px;
    }
    .products-side-content {
        overflow-x: hidden;
        overflow-y: auto;
        max-height: 520px;
        width: auto;
        flex: 1;
    }

    .products-side-title p {
        padding-top: 10px;
        font-weight: bolder;
        font-size: 25px;
    }

    .content-order {
        padding-top: 195px;
        padding-left: 10px;
        display: flex;
        flex-grow: 1;
        flex-direction: row;
    }

    .delivery-title {
        height: 50px;
        background-color: #cfcfcf;
    }

    .products-side-title {
        height: 50px;
        background-color: #cfcfcf;
    }

    .delivery-title p {
        padding-top: 10px;
        font-weight: bolder;
        font-size: 25px;
    }

    .delivery-side {
        width: 40%;
        height: auto;
        outline: #d5dcdc 2px solid;
        margin-bottom: 20px;
        margin-right: 20px;
        padding-top: 10px;
    }

    .dostawa-content div {
        margin-bottom: 10px;
    }

    .dostawa-content label {
        margin-right: 10px;
    }

    .dostawa-content select,
    .dostawa-content button {
        padding: 5px 10px;
        border-radius: 4px;
    }

    .delivery-type-options input[type="radio"],
    .delivery-type-options label {
        display: inline-block;
        vertical-align: middle;
        margin-right: 10px;
    }

    .courier-options button,
    .payment-options button {
        display: inline-block;
        margin-right: 10px;
    }

    .selected-button {
        border: 3px solid red;
    }

    .newAddressSection input {
        padding: 2px 50px 2px 50px;
        font-size: 24px;
        font-family: Arial, sans-serif;
        text-align: left;
    }

    .content-order button {
        padding: 20px 30px 20px 30px;
        border-radius: 10px;
    }

    .payment-section {
        flex: 1;
        margin-left: -20px;
    }
</style>
<script>
    function highlightButton(buttonId) {
        const buttons = document.getElementsByTagName('button');

        // Podświetl wybrany przycisk kuriera
        if (buttonId.includes('Courier')) {
            const courierButtons = document.getElementsByClassName('courier-options')[0].getElementsByTagName('button');
            for (let i = 0; i < courierButtons.length; i++) {
                courierButtons[i].classList.remove('selected-button');
            }
            const button = document.getElementById(buttonId);
            button.classList.add('selected-button');
        }

        // Podświetl wybrany przycisk metody płatności
        if (buttonId.includes('Payment')) {
            const paymentButtons = document.getElementsByClassName('payment-options')[0].getElementsByTagName('button');
            for (let i = 0; i < paymentButtons.length; i++) {
                paymentButtons[i].classList.remove('selected-button');
            }
            const button = document.getElementById(buttonId);
            button.classList.add('selected-button');
        }
    }


    function submitOrder() {
        var deliveryType = document.querySelector('input[name="deliveryType"]:checked').value;
        var companyName = '';
        var street = '';
        var postalCode = '';
        var city = '';
        var newAddressCheckbox = document.getElementById("newAddressCheckbox").checked;
        var shippingAddress = '';
        var courier = '';
        var payment = '';

        if (deliveryType="customer") {
            companyName = document.getElementById("companyName").value;
            street = document.getElementById("street").value;
            postalCode = document.getElementById("postalCode").value;
            city = document.getElementById("city").value;
        } else {
            shippingAddress = document.getElementById("shippingAddress").value;
        }

        var selectedCourierButton = document.querySelector('.courier-options button.selected-button');
        if (selectedCourierButton) {
            courier = selectedCourierButton.textContent;
        }

        var selectedPaymentButton = document.querySelector('.payment-options button.selected-button');
        if (selectedPaymentButton) {
            payment = selectedPaymentButton.textContent;
        }

        $.ajax({
            type: "POST",
            url: "submit_order.php",
            data: {
                deliveryType: deliveryType,
                companyName: companyName,
                street: street,
                postalCode: postalCode,
                city: city,
                newAddressCheckbox: newAddressCheckbox,
                shippingAddress: shippingAddress,
                courier: courier,
                payment: payment
            },
            success: function (response) {

                console.log(response);
                alert("Złożono zamówienie!");
                window.location.href = "dashboard.php";
            },
            error: function (xhr, status, error) {

                console.error(error);
            }
        });
    }

    function toggleAddressSection(showAddress) {
        const newAddressSection = document.getElementById('newAddressSection');
        const addressBookSection = document.getElementById('addressBookSection');

        if (showAddress) {
            newAddressSection.style.display = 'none';
            addressBookSection.style.display = 'block';
        } else {
            newAddressSection.style.display = 'block';
            addressBookSection.style.display = 'none';
        }
    }

    function selectCourier(courier) {


        var sumaValue = document.getElementById("sumaValue");
        var sumaValue2 = document.getElementById("sumaValue2");
        var currentSuma = parseInt(sumaValue2.innerHTML.replace("Suma: ", "").replace(" zł", ""));
        var courierPrice = 0;

        switch (courier) {
            case "DHL":
                courierPrice = 12;
                break;
            case "DPD":
                courierPrice = 15;
                break;
            case "Inpost":
                courierPrice = 9.9;
                break;
        }

        var newSuma = currentSuma + courierPrice;
        sumaValue.innerHTML = "Suma netto: " + newSuma + " zł<p>Suma brutto: " + ((newSuma*1.23).toFixed(2)) + " zł</p>";

        if (courier === 'DHL') {
            document.getElementById('dhlCourier').classList.add('selected-button');
            document.getElementById('dpdCourier').classList.remove('selected-button');
            document.getElementById('inpostCourier').classList.remove('selected-button');
        } else if (courier === 'DPD') {
            document.getElementById('dhlCourier').classList.remove('selected-button');
            document.getElementById('dpdCourier').classList.add('selected-button');
            document.getElementById('inpostCourier').classList.remove('selected-button');
        } else if (courier === 'Inpost') {
            document.getElementById('dhlCourier').classList.remove('selected-button');
            document.getElementById('dpdCourier').classList.remove('selected-button');
            document.getElementById('inpostCourier').classList.add('selected-button');
        }
    }

    function selectPayment(payment) {
        // Zaznacz wybraną metodę płatności
        if (payment === 'przelew24') {
            document.getElementById('przelew24Payment').classList.add('selected-button');
            document.getElementById('odroczonyPayment').classList.remove('selected-button');
            document.getElementById('paypalPayment').classList.remove('selected-button');
        } else if (payment === 'odroczony') {
            document.getElementById('przelew24Payment').classList.remove('selected-button');
            document.getElementById('odroczonyPayment').classList.add('selected-button');
            document.getElementById('paypalPayment').classList.remove('selected-button');
        } else if (payment === 'PayPal') {
            document.getElementById('przelew24Payment').classList.remove('selected-button');
            document.getElementById('odroczonyPayment').classList.remove('selected-button');
            document.getElementById('paypalPayment').classList.add('selected-button');
        }
    }
</script>
</body>
</html>
