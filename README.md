# Simple PHP Cart

This is a simple PHP cart script. 
Shop owner can set product prices, offers and delivery costs. 
The customer can add and remove products from cart, flush it and view the totals. 

## Requirements
PHP >=7

## Installation

Clone or download the script files to your web root. 
Replace the `base_uri`constant (index.php:165) with the uri your web root is pointed by the web server. 
Set up the `$cartConfig` variable, adding prices, delivery costs and eventual offers. 

### Architecture
Logic, computation and  cart data are serverside managed (PHP Session). 
The interface exists for data representation and user interaction only. 

### Note
This script is mere example to show how a basic PHP cart may be implemented using PHP builtin session functionality. 
That is means it is **not** to be used on a production server. 
Some important features like the following are missing and should be implemented : 
- data sanitization 
- session service
- product, order, offers entities definition

