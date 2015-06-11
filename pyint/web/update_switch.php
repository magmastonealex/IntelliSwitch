<?php
require 'Predis/Autoloader.php';

Predis\Autoloader::register();

$client = new Predis\Client();

$client->set($_POST["id"], $_POST["data"]);

?>