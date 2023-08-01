<?php

namespace sxqibo\fastweather\weather;

use Exception;
use GuzzleHttp\Client;

final class FastWeather
{
    const HOST = 'https://ali-weather.showapi.com';

    /**
     * 未来7日中某天的天气预报
     */
    const AREA_TO_WEATHER_DATA = '/area-to-weather-date';
    /**
     * 地名查询天气预报
     */
    const AREA_TO_WEATHER = '/area-to-weather';
    /**
     * 地名查询code
     */
    const AREA_TO_ID = '/area-to-id';
    /**
     * 经纬度查询天气预报
     */
    const GPS_TO_WEATHER = '/gps-to-weather';
    /**
     * 查未来40天预报
     */
    const DAY_40 = '/day40';
    /**
     * 未来15天预报
     */
    const DAY_15 = '/day15';
    /**
     * 查询24小时预报
     */
    const HOUR_24 = '/hour24';
    /**
     * IP查询天气预报
     */
    const IP_TO_WEATHER = '/ip-to-weather';
    /**
     * 查询历史天气情况
     */
    const WEATHER_HISTORY = '/weatherhistory';
    /**
     * 区号邮编查询天气预报
     */
    const PHONE_POST_CODE_WEATHER = '/phone-post-code-weeather';
    /**
     * 景点名称天气预报
     */
    const SPOT_TO_WEATHER = '/spot-to-weather';

    private $appcode = '';

    public function __construct($config)
    {
        $this->appcode = $config['appcode'];
    }

    /**
     * 未来7日中某天的天气预报
     *
     * @param string $areaCode 要查询的地区code
     * @param string $area 要查询的地区名称。areaCode与area字段必须输入其中一个。当两者都输入时，系统只取areaCode
     * @param string $date 查询日期 格式为 年月日，比如 20230731
     * @param string $need3HourForcast 是否需要当天每3/6/8小时一次的天气预报列表。1为需要，0为不需要。注意f1是3小时间隔，但f2到f7的间隔可能是6或8小时
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAreaToWeatherDate(string $areaCode, string $area, string $date, string $need3HourForcast): string
    {
        if ((!isset($areaCode) || empty($areaCode))
            && (!isset($area) || empty($area))) {
            throw new Exception('查询的地区code 和 查询的地区名称 不能同时为空');
        }

        $query = [];

        if (isset($areaCode) && !empty($areaCode)) {
            $query['areaCode'] = $areaCode;
        }

        if (isset($area) && !empty($area)) {
            $query['area'] = $area;
        }

        if (isset($date) && !empty($date)) {
            $query['date'] = $date;
        }

        if (isset($need3HourForcast) && !empty($need3HourForcast)) {
            $query['need3HourForcast'] = $need3HourForcast;
        }

        return $this->request($query, self::AREA_TO_WEATHER_DATA);
    }

    private function request($query, string $apiUri)
    {
        $headers = [
            'Authorization' => 'APPCODE ' . $this->appcode
        ];

        $uri = http_build_query($query);

        $client   = new Client();
        $response = $client->request('GET', self::HOST . $apiUri . '?' . $uri, [
            'headers' => $headers
        ]);

        return (string)$response->getBody();
    }

    /**
     * 地名查询天气预报
     *
     * @param string $area 地区名称。名称和code必须输入一个。如果都输入，以areaCode为准
     * @param string $areaCode 地区code。名称和code必须输入一个。如果都输入，以areaCode为准
     * @param string $needMoreDay 是否需要返回7天数据中的后4天。1为返回，0为不返回
     * @param string $needIndex 是否需要返回指数数据，比如穿衣指数、紫外线指数等。1为返回，0为不返回。
     * @param string $need3HourForcast 是否需要每小时数据的累积数组。由于本系统是半小时刷一次实时状态，因此实时数组最大长度为48。每天0点长度初始化为0. 1为需要 0为不
     * @param string $needAlarm 是否需要天气预警。1为需要，0为不需要
     * @param string $needHourData 是否需要每小时数据的累积数组。由于本系统是半小时刷一次实时状态，因此实时数组最大长度为48。每天0点长度初始化为0
     * @return string
     * @throws Exception
     */
    public function getAreaToWeather(string $area, string $areaCode, string $needMoreDay, string $needIndex, string $need3HourForcast, string $needAlarm, string $needHourData): string
    {
        if ((!isset($areaCode) || empty($areaCode))
            && (!isset($area) || empty($area))) {
            throw new Exception('查询的地区code 和 查询的地区名称 不能同时为空');
        }

        $query = [];

        if (isset($areaCode) && !empty($areaCode)) {
            $query['areaCode'] = $areaCode;
        }

        if (isset($area) && !empty($area)) {
            $query['area'] = $area;
        }

        if (isset($needMoreDay) && !empty($needMoreDay)) {
            $query['needMoreDay'] = $needMoreDay;
        }

        if (isset($needIndex) && !empty($needIndex)) {
            $query['needIndex'] = $needIndex;
        }

        if (isset($need3HourForcast) && !empty($need3HourForcast)) {
            $query['need3HourForcast'] = $need3HourForcast;
        }

        if (isset($needAlarm) && !empty($needAlarm)) {
            $query['needAlarm'] = $needAlarm;
        }

        if (isset($needHourData) && !empty($needHourData)) {
            $query['needHourData'] = $needHourData;
        }

        return $this->request($query, self::AREA_TO_WEATHER);
    }

    /**
     * 地名查询code
     *
     * @param string $area 地区名称
     * @return string
     * @throws Exception
     */
    public function getAreaToId(string $area): string
    {
        if (!isset($area) || empty($area)) {
            throw new Exception('地区名称不能为空!');
        }

        $query = [
            'area' => $area
        ];

        return $this->request($query, self::AREA_TO_ID);
    }

    /**
     * 经纬度查询天气预报
     *
     * @param string $from 输入的坐标类型： 1：GPS设备获取的角度坐标; 2：GPS获取的米制坐标、sogou地图所用坐标; 3：google地图、soso地图、aliyun地图、mapabc地图和amap地图所用坐标 4：3中列表地图坐标对应的米制坐标 5：百度地图采用的经纬度坐标 6：百度地图采用的米制坐标 7：mapbar地图坐标; 8：51地图坐标
     * @param string $lng 经度值
     * @param string $lat 纬度值
     * @param string $needMoreDay 是否需要返回7天数据中的后4天。1为返回，0为不返回。
     * @param string $needIndex 是否需要返回指数数据，比如穿衣指数、紫外线指数等。1为返回，0为不返回。
     * @param string $needHourData 是否需要每小时数据的累积数组。由于本系统是半小时刷一次实时状态，因此实时数组最大长度为48。每天0点长度初始化为0. 1为需要 0为不
     * @param string $need3HourForcast 是否需要当天每3/6小时一次的天气预报列表。1为需要，0为不需要。
     * @param string $needAlarm 是否需要天气预警。1为需要，0为不需要
     * @return string
     * @throws Exception
     */
    public function getGpsToWeather(string $from, string $lng, string $lat, string $needMoreDay, string $needIndex,
                                    string $needHourData, string $need3HourForcast, string $needAlarm): string
    {
        if ((!isset($from) || empty($from))
            || (!isset($lng) || empty($lng))
            || (!isset($lat) || empty($lat))) {
            throw new Exception('输入类型、经度值、纬度值为必填参数');
        }

        $query = [
            'from' => $from,
            'lng'  => $lng,
            'lat'  => $lat
        ];

        if (isset($needMoreDay) && !empty($needMoreDay)) {
            $query['needMoreDay'] = $needMoreDay;
        }

        if (isset($needIndex) && !empty($needIndex)) {
            $query['needIndex'] = $needIndex;
        }

        if (isset($needHourData) && !empty($needHourData)) {
            $query['needHourData'] = $needHourData;
        }

        if (isset($need3HourForcast) && !empty($need3HourForcast)) {
            $query['need3HourForcast'] = $need3HourForcast;
        }

        if (isset($needAlarm) && !empty($needAlarm)) {
            $query['needAlarm'] = $needAlarm;
        }

        return $this->request($query, self::GPS_TO_WEATHER);
    }

    /**
     * 查未来40天预报
     *
     * @param string $areaCode 地区code. 此参数和area必须输入一个。
     * @param string $area 地区名称
     * @return string
     * @throws Exception
     */
    public function getDay40(string $areaCode, string $area): string
    {
        if ((!isset($areaCode) || empty($areaCode))
            && (!isset($area) || empty($area))) {
            throw new Exception('地区code 和 地区名称 不能同时为空');
        }

        $query = [];

        if (isset($areaCode) && !empty($areaCode)) {
            $query['areaCode'] = $areaCode;
        }

        if (isset($area) && !empty($area)) {
            $query['area'] = $area;
        }

        return $this->request($query, self::DAY_40);
    }

    /**
     * 未来15天预报
     *
     * @param string $areaCode 地区名称
     * @param string $area 地区code. 此参数和area必须输入一个
     * @return string
     * @throws Exception
     */
    public function getDay15(string $areaCode, string $area): string
    {
        if ((!isset($areaCode) || empty($areaCode))
            && (!isset($area) || empty($area))) {
            throw new Exception('地区code 和 地区名称 不能同时为空');
        }

        $query = [];

        if (isset($areaCode) && !empty($areaCode)) {
            $query['areaCode'] = $areaCode;
        }

        if (isset($area) && !empty($area)) {
            $query['area'] = $area;
        }

        return $this->request($query, self::DAY_15);
    }

    /**
     * 查询24小时预报
     *
     * @param string $areaCode 地区名称
     * @param string $area 地区code. 此参数和area必须输入一个
     * @return string
     * @throws Exception
     */
    public function getHour24(string $areaCode, string $area): string
    {
        if ((!isset($areaCode) || empty($areaCode))
            && (!isset($area) || empty($area))) {
            throw new Exception('地区code 和 地区名称 不能同时为空');
        }

        $query = [];

        if (isset($areaCode) && !empty($areaCode)) {
            $query['areaCode'] = $areaCode;
        }

        if (isset($area) && !empty($area)) {
            $query['area'] = $area;
        }

        return $this->request($query, self::HOUR_24);
    }

    /**
     * IP查询天气预报
     *
     * @param string $ip 用户ip
     * @param string $needMoreDay 是否需要返回7天数据中的后4天。1为返回，0为不返回
     * @param string $needIndex 是否需要返回指数数据，比如穿衣指数、紫外线指数等。1为返回，0为不返回
     * @param string $needHourData 是否需要每小时数据的累积数组。由于本系统是半小时刷一次实时状态，因此实时数组最大长度为48。每天0点长度初始化为0
     * @param string $need3HourForcast 是否需要每小时数据的累积数组。由于本系统是半小时刷一次实时状态，因此实时数组最大长度为48。每天0点长度初始化为0. 1为需要 0为不
     * @param string $needAlarm 是否需要天气预警。1为需要，0为不需要
     * @return string
     * @throws Exception
     */
    public function getIpToWeather(string $ip, string $needMoreDay = '0', string $needIndex = '0',
                                string $needHourData = '0', string $need3HourForcast = '0', string $needAlarm = '0'): string
    {
        if (!isset($ip) || empty($ip)) {
            throw new Exception('用户ip 不能为空');
        }

        $query = [
            'ip' => $ip,
            'needMoreDay' => $needMoreDay,
            'needIndex' => $needIndex,
            'needHourData' => $needHourData,
            'need3HourForcast' => $need3HourForcast,
            'needAlarm' => $needAlarm
        ];

        return $this->request($query, self::IP_TO_WEATHER);
    }

    /**
     * 查询历史天气情况
     *
     * @param string $area 地区名称。code和名称必须输入其中1个。如果都输入，以id为准
     * @param string $areaCode 地区code
     * @param string $month 查询的月份，格式yyyyMM。最早的数据是2015年1月。
     * @return string
     * @throws Exception
     */
    public function getWeatherHistory(string $area, string $areaCode, string $month): string
    {
        if ((!isset($areaCode) || empty($areaCode))
            && (!isset($area) || empty($area))) {
            throw new Exception('查询的地区code 和 查询的地区名称 不能同时为空');
        }

        if (!isset($month) || empty($month)) {
            throw new Exception('查询月份 不能为空');
        }

        $query['month'] = $month;

        if (isset($areaCode) && !empty($areaCode)) {
            $query['areaCode'] = $areaCode;
        }

        if (isset($area) && !empty($area)) {
            $query['area'] = $area;
        }

        return $this->request($query, self::WEATHER_HISTORY);
    }

    /**
     * 区号邮编查询天气预报
     *
     * @param string $postCode 邮编，比如上海200000
     * @param string $phoneCode 电话区号，比如上海021。 注意邮编和区号必须二选一输入。都输入时，以邮编为准。
     * @param string $needMoreDay 是否需要返回7天数据中的后4天。1为返回，0为不返回
     * @param string $needIndex 是否需要返回指数数据，比如穿衣指数、紫外线指数等。1为返回，0为不返回
     * @param string $needHourData 是否需要每小时数据的累积数组。由于本系统是半小时刷一次实时状态，因此实时数组最大长度为48。每天0点长度初始化为0
     * @param string $need3HourForcast 是否需要每小时数据的累积数组。由于本系统是半小时刷一次实时状态，因此实时数组最大长度为48。每天0点长度初始化为0. 1为需要 0为不
     * @param string $needAlarm 是否需要天气预警。1为需要，0为不需要
     * @return string
     * @throws Exception
     */
    public function getPhonePostCodeWeather(string $postCode, string $phoneCode,
                                            string $needMoreDay = '0', string $needIndex = '0', string $needHourData = '0',
                                            string $need3HourForcast = '0', string $needAlarm = '0'): string
    {
        if ((!isset($postCode) || empty($postCode))
            && (!isset($phoneCode) || empty($phoneCode))) {
            throw new Exception('查询的地区code 和 查询的地区名称 不能同时为空');
        }

        $query = [
            'needMoreDay' => $needMoreDay,
            'needIndex' => $needIndex,
            'needHourData' => $needHourData,
            'need3HourForcast' => $need3HourForcast,
            'needAlarm' => $needAlarm,
        ];

        if (isset($postCode) && !empty($postCode)) {
            $query['post_code'] = $postCode;
        }

        if (isset($phoneCode) && !empty($phoneCode)) {
            $query['phone_code'] = $phoneCode;
        }

        return $this->request($query, self::PHONE_POST_CODE_WEATHER);
    }

    /**
     * 景点名称天气预报
     *
     * @param string $area 景点名称
     * @param string $needMoreDay 是否需要返回7天数据中的后4天。1为返回，0为不返回
     * @param string $needIndex 是否需要返回指数数据，比如穿衣指数、紫外线指数等。1为返回，0为不返回
     * @param string $needHourData 是否需要每小时数据的累积数组。由于本系统是半小时刷一次实时状态，因此实时数组最大长度为48。每天0点长度初始化为0. 1为需要 0为不
     * @param string $need3HourForcast 是否需要当天每3/6小时一次的天气预报列表。1为需要，0为不需要
     * @param string $needAlarm 是否需要天气预警。1为需要，0为不需要
     * @return string
     * @throws Exception
     */
    public function getSpotToWeather(string $area, string $needMoreDay = '0', string $needIndex = '0',
                                     string $needHourData = '0', string $need3HourForcast = '0', string $needAlarm = '0'): string
    {
        if (!isset($area) || empty($area)) {
            throw new Exception('景点名称 不能为空');
        }

        $query = [
            'area' => $area,
            'needMoreDay' => $needMoreDay,
            'needIndex' => $needIndex,
            'needHourData' => $needHourData,
            'need3HourForcast' => $need3HourForcast,
            'needAlarm' => $needAlarm,
        ];

        return $this->request($query, self::SPOT_TO_WEATHER);
    }
}
