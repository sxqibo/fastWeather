<?php

use Sxqibo\FastWeather\WanweiWeather;

require __DIR__ . '/../vendor/autoload.php';

$config = [
    'appcode' => ''
];

$weather = new WanweiWeather($config);

$response = $weather->getAreaToWeatherDate('', '太原', '20230731', 1);
var_dump(json_decode($response));

$response = $weather->getAreaToWeather('太原', '' , '1', '1', '1', '1', '1');
var_dump(json_decode($response));

$response = $weather->getAreaToId('太原');
var_dump(json_decode($response));

$response = $weather->getGpsToWeather('5', '116.2278', '40.242266', '0', '0', '0', '0', '0');
var_dump(json_decode($response));

$response = $weather->getDay40('', '太原');
var_dump(json_decode($response));

$response = $weather->getDay15('', '太原');
var_dump(json_decode($response));

$response = $weather->getHour24('', '太原');
var_dump(json_decode($response));

$response = $weather->getIpToWeather('223.5.5.5');
var_dump(json_decode($response));

$response = $weather->getWeatherHistory('太原', '', '202307');
var_dump(json_decode($response));

$response = $weather->getPhonePostCodeWeather('030000', '');
var_dump(json_decode($response));

$response = $weather->getPhonePostCodeWeather('', '0351');
var_dump(json_decode($response));

$response = $weather->getSpotToWeather('太原');
var_dump(json_decode($response));
