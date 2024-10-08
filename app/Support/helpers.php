<?php

use Illuminate\Support\Number;

/**
 * 根据两点间的经纬度计算距离
 * @param $lat1
 * @param $lng1
 * @param $lat2
 * @param $lng2
 *
 * @return float
 */
function get_distance($lat1, $lng1, $lat2, $lng2): float
{
    $radLat1 = deg2rad($lat1);
    $radLat2 = deg2rad($lat2);
    $radLng1 = deg2rad($lng1);
    $radLng2 = deg2rad($lng2);
    $a = $radLat1 - $radLat2;
    $b = $radLng1 - $radLng2;
    return 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.138 * 1000;
}

/**
 * 生成一个假手机号
 */
function fake_phone(): string
{
    $prefixes = ['130', '131', '132', '133', '134', '135', '136', '137', '138', '139', '150', '151', '152', '153', '155', '156', '157', '158', '159', '170', '171', '173', '175', '176', '177', '178', '180', '181', '182', '183', '184', '185', '186', '187', '188', '189', '198', '199'];
    $randomPrefix = $prefixes[array_rand($prefixes)];
    return $randomPrefix . str_pad(mt_rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
}

/**
 * 格式化成可读数字
 * @param $number
 * @return string
 */
function format_large_number($number): string
{
    if ($number < 10000) {
        return (string) Number::format((float)$number, 0);
    }

    if ($number < 100000000) {
        return Number::format((float)$number / 1000, 2) . '万';
    }

    return Number::format((float)$number / 100000000, 2) . '亿';
}
