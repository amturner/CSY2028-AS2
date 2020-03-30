<?php
require '../autoload.php';

$routes = new \JobSite\Routes();

$entryPoint = new \CSY2028\EntryPoint($routes);

$entryPoint->run();
?>