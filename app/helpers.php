<?php

const BAHT_TEXT_NUMBERS = array('ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า');
const BAHT_TEXT_UNITS = array('', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน');
const BAHT_TEXT_ONE_IN_TENTH = 'เอ็ด';
const BAHT_TEXT_TWENTY = 'ยี่';
const BAHT_TEXT_INTEGER = 'ถ้วน';
const BAHT_TEXT_BAHT = 'บาท';
const BAHT_TEXT_SATANG = 'สตางค์';
const BAHT_TEXT_POINT = 'จุด';

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

        //return "$strDay $strMonthThai $strYear $strHour:$strMinute";
        return "$strDay $strMonthThai $strYear";
    }
}

if (!function_exists('baht_text')) {


    /**
     * Convert baht number to Thai text
     * @param double|int $number
     * @param bool $include_unit
     * @param bool $display_zero
     * @return string|null
     */
    function baht_text($number, $include_unit = true, $display_zero = true)
    {
        if (!is_numeric($number)) {
            return null;
        }

        $log = floor(log($number, 10));
        if ($log > 5) {
            $millions = floor($log / 6);
            $million_value = pow(1000000, $millions);
            $normalised_million = floor($number / $million_value);
            $rest = $number - ($normalised_million * $million_value);
            $millions_text = '';
            for ($i = 0; $i < $millions; $i++) {
                $millions_text .= BAHT_TEXT_UNITS[6];
            }
            return baht_text($normalised_million, false) . $millions_text . baht_text($rest, true, false);
        }

        $number_str = (string)floor($number);
        $text = '';
        $unit = 0;

        if ($display_zero && $number_str == '0') {
            $text = BAHT_TEXT_NUMBERS[0];
        } else for ($i = strlen($number_str) - 1; $i > -1; $i--) {
            $current_number = (int)$number_str[$i];

            $unit_text = '';
            if ($unit == 0 && $i > 0) {
                $previous_number = isset($number_str[$i - 1]) ? (int)$number_str[$i - 1] : 0;
                if ($current_number == 1 && $previous_number > 0) {
                    $unit_text .= BAHT_TEXT_ONE_IN_TENTH;
                } else if ($current_number > 0) {
                    $unit_text .= BAHT_TEXT_NUMBERS[$current_number];
                }
            } else if ($unit == 1 && $current_number == 2) {
                $unit_text .= BAHT_TEXT_TWENTY;
            } else if ($current_number > 0 && ($unit != 1 || $current_number != 1)) {
                $unit_text .= BAHT_TEXT_NUMBERS[$current_number];
            }

            if ($current_number > 0) {
                $unit_text .= BAHT_TEXT_UNITS[$unit];
            }

            $text = $unit_text . $text;
            $unit++;
        }

        if ($include_unit) {
            $text .= BAHT_TEXT_BAHT;

            $satang = explode('.', number_format($number, 2, '.', ''))[1];
            $text .= $satang == 0
                ? BAHT_TEXT_INTEGER
                : baht_text($satang, false) . BAHT_TEXT_SATANG;
        } else {
            $exploded = explode('.', $number);
            if (isset($exploded[1])) {
                $text .= BAHT_TEXT_POINT;
                $decimal = (string)$exploded[1];
                for ($i = 0; $i < strlen($decimal); $i++) {
                    $text .= BAHT_TEXT_NUMBERS[$decimal[$i]];
                }
            }
        }

        return $text;
    }
}
