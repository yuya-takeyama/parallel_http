<?php
require_once dirname(__FILE__) . '/../vendor/SplClassLoader/SplClassLoader.php';

$loader = new SplClassLoader('Yuyat', dirname(__FILE__) . '/../src');
$loader->setNamespaceSeparator('_');
$loader->register();

$loader = new SplClassLoader('Edps', dirname(__FILE__) . '/../vendor/yuya-takeyama/edps/src');
$loader->setNamespaceSeparator('_');
$loader->register();

$urls = array(
    'http://twitter.com/',
    'http://www.facebook.com/',
    'http://www.yahoo.co.jp/',
    'http://www.google.co.jp/',
);

$loop   = new Yuyat_ParallelHttp_EventLoop;
$client = new Yuyat_ParallelHttp_Client($loop);

foreach ($urls as $url) {
    $request = $client->request($url, function ($response) {
        echo "Status Code: ";
        var_dump($response->getStatusCode());
        echo "Headers:", PHP_EOL;
        var_dump($response->getHeaders());
        echo "Body:", PHP_EOL;
        var_dump($response->getBody());
        echo PHP_EOL;
        echo PHP_EOL;
    });

    $request->on('error', function ($error) {
        echo "Error:", PHP_EOL;
        var_dump($error);
        echo PHP_EOL;
    });
}

$loop->run();
