<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Acme Widget Co Cart</title>
</head>
<body>
<style>
    .container {
        margin-top : 30px;
    }
    a.add {
        text-decoration: none;
    }
    span.add, span.sub, #empty {
        cursor: pointer;
    }
    #empty {
        color:green;
    }
    .cart-total{
        color:red;
    }
    #tot-cart {
        font-weight: bold;
    }
    .cart-col{
        background-color:aliceblue;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<div class="container">
    <div class="row">
        <div class="col-8">
            <h2>Products</h2>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Product</th>
                    <th scope="col">Code</th>
                    <th scope="col">Price</th>
                    <th scope="col">Add</th>
                    <th scope="col">Sub</th>
                </tr>
                </thead>
                <tbody>
                <tr class="table-primary">
                    <th scope="row">Blue Widget</th>
                    <td>B01</td>
                    <td>$7.95</td>
                    <td><span class="add" id="B01">+</span></td>
                    <td><span class="sub" data-prod="B01">-</span></td>
                </tr>
                <tr class="table-success">
                    <th scope="row">Green Widget</th>
                    <td>G01</td>
                    <td>$24.95</td>
                    <td><span class="add" id="G01">+</span></td>
                    <td><span class="sub" data-prod="G01">-</span></td>
                </tr>
                <tr class="table-danger">
                    <th scope="row">Red Widget</th>
                    <td>R01</td>
                    <td>$32.95</td>
                    <td><span class="add" id="R01">+</span></td>
                    <td><span class="sub" data-prod="R01">-</span></td>
                </tr>
                </tbody>
            </table>
            <table class="table">
                <h5>Delivery</h5>
                <thead>
                <tr>
                    <th scope="col">Cart total</th>
                    <th scope="col">Cost</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Lower than $50</td>
                    <td>$4.95</td>
                </tr>
                <tr>
                    <td>Between $50 and $90</td>
                    <td>$2.95</td>
                </tr>
                <tr>
                    <td>Greater than $90</td>
                    <td>free</td>
                </tr>
                </tbody>

            </table>
            <table class="table">
                <h5>Special Offers</h5>
                <thead>
                <tr>
                    <th scope="col">Product</th>
                    <th scope="col">Offer</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>RO1</td>
                    <td>Buy one, ge the 2nd at 50% of the base price!</td>
                </tr>
                </tbody>

            </table>

        </div>
        <div class="col cart-col">
            <h2>Cart</h2>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">P. Code</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Save</th>
                </tr>
                </thead>
                <tbody id="prod-table">
                <tr>
                    <th scope="row">R01</th>
                    <td>0</td>
                    <td>0</td>
                </tr>
                <tr>
                    <th scope="row">G01</th>
                    <td>0</td>
                    <td>0</td>
                </tr>
                <tr>
                    <th scope="row">B01</th>
                    <td>0</td>
                    <td>0</td>
                </tr>
                </tbody>
            </table>
            <table class="table">
                <tbody class="no-border" id="tot-table">
                <tr>
                    <th scope="row">Products total</th>
                    <td id="tot-cost">0</td>
                </tr>
                <tr>
                    <th scope="row">Delivery total</th>
                    <td id="tot-delivery">0</td>
                </tr>
                <tr class="table-active">
                    <th class="cart-total" scope="row">Cart total</th>
                    <td id="tot-cart">0</td>
                </tr>
                </tbody>

            </table>
            <span id="empty">empty cart</span>
        </div>
    </div>
</div>
</body>
<script>
    const base_uri = 'http://localhost';
    window.onload = function() {
        fetch(base_uri+'/cart/front.php?action=total')
            .then(response => response.json())
            .then(data => buildTableCart(data));
    };

    function buildTableCart(data) {

        let html_str = '';
        let total = '';
        let delivery = '';
        let totalCart = '';
        if(data.items == null){
            html_str = '<tr> <th scope="row">R01</th><td>0</td><td>0</td></tr><tr><th scope="row">G01</th><td>0</td><td>0</td></tr><tr><th scope="row">B01</th><td>0</td><td>0</td></tr>';
            total = '0';
            delivery = '0';
            totalCart = '0';
        }else{
            for (const [key, value] of Object.entries(data.items)) {
                let saved = (value.save !== 0) ? '-'+value.save+'$' : value.save;
                html_str += '<tr><th scope="row">'+key+'</th>';
                html_str += '<td>'+value.quantity+'</td>';
                html_str += '<td>'+saved+'</td>';
                html_str += '</tr>';
            }
            total = data.total;
            delivery = data.delivery;
            totalCart = data.totalCart;
        }

        const prodTable = document.getElementById("prod-table");
        prodTable.innerHTML = '';
        prodTable.innerHTML = html_str;

        const totCost = document.getElementById("tot-cost");
        totCost.innerHTML = '';
        totCost.innerHTML = '$'+total;

        const totDelivery = document.getElementById("tot-delivery");
        totDelivery.innerHTML = '';
        totDelivery.innerHTML = '$'+delivery;

        const totCart = document.getElementById("tot-cart");
        totCart.innerHTML = '';
        totCart.innerHTML = '$'+totalCart;

    }

    let addItem = function() {
        let itemId = this.getAttribute("id");
        fetch(base_uri+'/cart/front.php?action=add&itemId='+itemId)
            .then(response => response.json())
            .then(data => buildTableCart(data));
    };

    let subItem = function() {
        let itemId = this.getAttribute("data-prod");
        fetch(base_uri+'/cart/front.php?action=sub&itemId='+itemId)
            .then(response => response.json())
            .then(data => buildTableCart(data));
    };

    let emptyCart = function() {
        fetch(base_uri+'/cart/front.php?action=empty')
            .then(response => response.json())
            .then(data => buildTableCart(data));
    };

    let adders = document.getElementsByClassName("add");
    for (let i = 0; i < adders.length; i++) {
        adders[i].addEventListener('click', addItem, false);
    }

    let subtractors = document.getElementsByClassName("sub");
    for (let i = 0; i < subtractors.length; i++) {
        subtractors[i].addEventListener('click', subItem, false);
    }

    let buttonempty = document.getElementById("empty");
    buttonempty.addEventListener('click', emptyCart, false);
</script>
</html>
