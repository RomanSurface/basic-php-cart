<?php

require('data/product_config.php');
require('autoload.php');
use AcmeWidget\Session;
use AcmeWidget\Product;
use AcmeWidget\Cart;

if (isset($_GET['action'])) {
    $cart = new Cart(new AcmeWidget\Session(), new AcmeWidget\Product($productConfig));
    $action = $_GET['action'];
    switch ($action) {
        case 'add':
            echo $cart->addItem($_GET['itemId']);
            break;
        case 'sub':
            echo $cart->subtractItem($_GET['itemId']);
            break;
        case 'total':
            echo $cart->getCartTotals();
            break;
        case 'empty':
            echo $cart->emptyCart();
            break;
        default:
            break;
    }
}
exit;
