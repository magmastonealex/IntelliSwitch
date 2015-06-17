<?php
require 'Predis/Autoloader.php';

Predis\Autoloader::register();

$client = new Predis\Client();

$client->set("descriptor", $_POST["descrip"]);

?>