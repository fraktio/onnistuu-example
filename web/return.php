<?php

require '../vendor/autoload.php';

$config = require '../config/config.php';

$client = new Onnistuu_External_Client($config['clientId'], $config['secret']);

$response = $client->decryptReturn($_GET['data'], $_GET['iv']);

?>

<pre><?php echo print_r($response, true) ?></pre>

