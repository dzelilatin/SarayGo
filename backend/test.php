<?php

require_once __DIR__ . '/Dao/AuthDao.php';

$test = new AuthDao();
$res = $test->login('admin', 'admin123');

print_r($res);