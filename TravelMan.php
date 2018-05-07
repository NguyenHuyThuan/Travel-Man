<?php
class TravelMan {
	/**
	 * readfile
	 * @return array of list of city
	 */
	public function readFile($filePath) {
		$myfile = file($filePath) or die('Input file "' . $filePath . '" not found');
		if (count($myfile) < 2) {
			throw new \Exception('Input file "' . $filePath . '" is empty or at least two cities');
		}
		foreach ($myfile as $list) {
			$parts = explode(" ", $list);
			$length = count($parts);
			$arr = ['latitude' => (float) $parts[$length - 2], 'longitude' => (float) $parts[$length - 1]];
			unset($parts[$length - 1]);
			unset($parts[$length - 2]);
			$city[implode(" ", $parts)] = $arr;
		}

		return $city;
	}
	/**
	 * Calculate distance by the coordinates
	 * @param $pStartla
	 * @param $pStartLong
	 * @param $pointDesLat
	 * @param $pointDesLong
	 * @param $earthRadius
	 * @return float distance
	 */
	private function calculateDistance($pStartla, $pStartLong, $pointDesLat, $pointDesLong, $earthRadius = 6371000) {
		$latFrom = deg2rad($pStartla);
		$lonFrom = deg2rad($pStartLong);
		$latTo = deg2rad($pointDesLat);
		$lonTo = deg2rad($pointDesLong);

		$lonDelta = $lonTo - $lonFrom;
		$a = pow(cos($latTo) * sin($lonDelta), 2) +
		pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
		$b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

		$angle = atan2(sqrt($a), $b);
		return $angle * $earthRadius;
	}

	/**
	 * Find the list of citys base on distance
	 * @param $listCity
	 * @param $pStart
	 * @return array city name
	 */

	public function calculateRoute($listCity, $pStart, &$arrRoute) {
		$lowest = false;
		if (count($listCity) > 1) {
			foreach ($listCity as $cityName => $point) {
				$distance = $this->calculateDistance($pStart['latitude'], $pStart['longitude'], $point['latitude'], $point['longitude']);
				// var_dump($distance . "|" . $cityName);
				if ($lowest === false || $distance < $lowest) {
					$lowest = $distance;
					$name = $cityName;
				}
			}
			$arrRoute[$name] = $listCity[$name];
			unset($listCity[$name]);
			$this->calculateRoute($listCity, $arrRoute[$name], $arrRoute);
		} else {
			// the final route of array
			$arrRoute = $arrRoute + $listCity;
			$listCity = [];
		}
		return $arrRoute;
	}
}
?>