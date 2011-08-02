<?php
/**
* @package   com_zoo Component
* @file      googlemaps.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

class GooglemapsHelper extends AppHelper{
	
	public function stripText($text) {
		$text = str_replace(array("\r\n", "\n", "\r", "\t"), "", $text);
		$text = addcslashes($text, "'");
		return $text;
	}

	public function locate($location, $cache = null) {
		// check if location are lng / lat values
		$location = trim($location);

		if (preg_match('/^([-]?(?:[0-9]+(?:\.[0-9]+)?|\.[0-9]+)),([-]?(?:[0-9]+(?:\.[0-9]+)?|\.[0-9]+))$/i', $location, $regs)) {
			if ($location == $regs[0]) {
				return array('lat' => $regs[1], 'lng' => $regs[2]);
			}
		}

		// use geocode to translate location
		return $this->geoCode($location, $cache);
	}

	public function geoCode($address, $cache = null) {
		// use cache result
		if ($cache !== null && $value = $cache->get($address)) {
			if (preg_match('/^([-]?(?:[0-9]+(?:\.[0-9]+)?|\.[0-9]+)),([-]?(?:[0-9]+(?:\.[0-9]+)?|\.[0-9]+))$/i', $value, $regs)) {
				return array('lat' => $regs[1], 'lng' => $regs[2]);
			}
		}

		// query google maps geocoder and parse result
		$result      = $this->queryGeoCoder($address);
		$coordinates = null;

		if (isset($result->results) && ($result = array_pop($result->results))) {
			if (isset($result->geometry->location->lat) && isset($result->geometry->location->lng)) {
				$coordinates['lat'] = $result->geometry->location->lat;
				$coordinates['lng'] = $result->geometry->location->lng;
			}
		}

		// cache geocoder result
		if ($cache !== null && $coordinates !== null) {
			$cache->set($address, $coordinates['lat'].",".$coordinates['lng']);
		}

		return $coordinates;
	}

	public function queryGeoCoder($address) {
	    $contents = '';

		// query use fsockopen
		$response = $this->app->http->get(sprintf('http://maps.google.com/maps/api/geocode/json?address=%s&sensor=false', urlencode($address)));

		if (isset($response['body'])) {
			return json_decode($response['body']);
		}
				
	    return null;
	}

}