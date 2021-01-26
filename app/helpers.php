<?php

if (!function_exists('get_distance')) {

    function get_distance($from_latlong, $to_latlong)
    {
        $googleApi = env("GMAPS_API_KEY", null);
        $distance_data = file_get_contents(
            'https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=' . $from_latlong . '&destinations=' . $to_latlong . '&language=th' . '&key=' . $googleApi
        );

        $distance_arr = json_decode($distance_data);

        $distance = $distance_arr->rows[0]->elements[0]->distance->text;
        $duration = $distance_arr->rows[0]->elements[0]->duration->value;

        $distance = preg_replace("/[^0-9.]/", "",  $distance);


        $distance = $distance * 1.609344;
        $duration = get_hourduration($duration);
        $distance = number_format($distance, 2, '.', ',');
        $distdata = array('distance' => $distance);
        $distdata = array_add($distdata, 'duration', $duration);

        return $distdata;
    }
}
if (!function_exists('get_hourduration')) {
    function get_hourduration($seconds)
    {
        $t = round($seconds);
        return sprintf('%02d.%02d', ($t / 3600), ($t / 60 % 60));
    }
}

if (!function_exists('formatDateThai')) {

    function formatDateThai($strDate)
    {
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strHour = date("H", strtotime($strDate));
        $strMinute = date("i", strtotime($strDate));
        $strSeconds = date("s", strtotime($strDate));
        $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        $strMonthThai = $strMonthCut[$strMonth];

        return "$strDay $strMonthThai $strYear $strHour:$strMinute";
        //return "$strDay $strMonthThai $strYear";
    }
}
