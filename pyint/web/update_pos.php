<?php
require 'Predis/Autoloader.php';

Predis\Autoloader::register();

$client = new Predis\Client();

$client->set($_POST["id"].":pos", $_POST["data"]);

?>