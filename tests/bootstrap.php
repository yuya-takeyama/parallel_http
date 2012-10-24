<?php
require_once dirname(__FILE__) . '/../vendor/SplClassLoader/SplClassLoader.php';

$loader = new SplClassLoader('Yuyat', dirname(__FILE__) . '/../src');
$loader->setNamespaceSeparator('_');
$loader->register();

$loader = new SplClassLoader('Edps', dirname(__FILE__) . '/../vendor/yuya-takeyama/edps/src');
$loader->setNamespaceSeparator('_');
$loader->register();
