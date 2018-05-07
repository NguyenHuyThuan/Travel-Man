<?php
require_once 'TravelMan.php';
$obj = new TravelMan();
$listCity = $obj->readFile("cities.txt");
$route = array_splice($listCity, 0, 1);
$finalRoute = $obj->calculateRoute($listCity, array_values($route)[0], $route);

foreach ($finalRoute as $name => $point) {
	echo "$name\n";
}
?>