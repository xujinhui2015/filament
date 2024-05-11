<?php

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
