<?php


require_once __DIR__ . '/Dao/OfferDao.php';
require_once __DIR__ . '/Dao/CartDao.php';


$test = new CartDao();


$res = $test->addOfferToCart(344, 4);



print_r($res);
