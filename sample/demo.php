<?php

use sxqibo\fastweather\weather\FastWeather;

require __DIR__ . '/../vendor/autoload.php';

$fastWeather = new FastWeather();
//$fastWeather = new FastWeather();
//$response = $fastWeather->getAreaToWeatherDate('', '太原', '20230731', 1);
//var_dump(json_decode($response));

//$response = $fastWeather->getAreaToWeather('太原', '' , '1', '1', '1', '1', '1');
//var_dump(json_decode($response));

//$response = $fastWeather->getAreaToId('太原');
//var_dump(json_decode($response));

//$response = $fastWeather->getGpsToWeather('5', '116.2278', '40.242266', '0', '0', '0', '0', '0');
//var_dump(json_decode($response));

//$response = $fastWeather->getDay40('', '太原');
//var_dump(json_decode($response));

//$response = $fastWeather->getDay15('', '太原');
//var_dump(json_decode($response));

//$response = $fastWeather->getHour24('', '太原');
//var_dump(json_decode($response));

//$response = $fastWeather->getIpToWeather('223.5.5.5');
//var_dump(json_decode($response));

//$response = $fastWeather->getWeatherHistory('太原', '', '202307');
//var_dump(json_decode($response));

//$response = $fastWeather->getPhonePostCodeWeather('030000', '');
//var_dump(json_decode($response));

//$response = $fastWeather->getPhonePostCodeWeather('', '0351');
//var_dump(json_decode($response));

$response = $fastWeather->getSpotToWeather('太原');
var_dump(json_decode($response));
