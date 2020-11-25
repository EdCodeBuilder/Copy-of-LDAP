<?php

use Carbon\Carbon;

if ( !function_exists('toUpper') ) {
    /**
     * The method to return upper string including spanish chars
     *
     * @param null $string
     * @return mixed|string
     */
    function toUpper( $string = null )
    {
        if ( is_string($string) || is_numeric( $string ) ) {
            return mb_convert_case( strtolower( trim( strip_tags( $string ) ) ), MB_CASE_UPPER, 'UTF-8');
        }

        return null;
    }
}

if ( !function_exists('isAValidDate') ) {
    /**
     * The method to return upper string including spanish chars
     *
     * @param $date
     * @param string $format
     * @return bool
     */
    function isAValidDate( $date, $format = 'Y-m-d' )
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}

if ( ! function_exists('toLower') ) {
    /**
     * The method to return lower string including spanish chars
     *
     * @param null $string
     * @return mixed|string
     */
    function toLower( $string = null )
    {
        if ( is_string($string) || is_numeric( $string ) ) {
            return mb_convert_case( strtolower( trim( strip_tags( $string ) ) ), MB_CASE_LOWER, 'UTF-8');
        }

        return null;
    }
}

if ( ! function_exists('toTitle') ) {
    /**
     * The method to return title string including spanish chars
     *
     * @param null $string
     * @return mixed|string
     */
    function toTitle( $string = null )
    {
        if ( is_string($string) || is_numeric( $string ) ) {
            return mb_convert_case( strtolower( trim( strip_tags( $string ) ) ), MB_CASE_TITLE, 'UTF-8');
        }

        return null;
    }
}

if ( ! function_exists('toFirstUpper') ) {
    /**
     * The method to return title string including spanish chars
     *
     * @param null $string
     * @return mixed|string
     */
    function toFirstUpper( $string = null )
    {
        if ( is_string($string) || is_numeric( $string ) ) {
            $str = ucfirst( mb_convert_case( strtolower( trim( strip_tags( $string ) ) ), MB_CASE_LOWER, 'UTF-8') );
            preg_match_all("/\.\s*\w/", $str, $matches);

            foreach($matches[0] as $match){
                $str = str_replace($match, strtoupper($match), $str);
            }
            return $str;
        }

        return null;
    }
}

if ( ! function_exists('ldapDateToCarbon') ) {
    /**
     * Convert LDAP dates to readable date
     *
     * @param $date
     * @return string
     */
    function ldapDateToCarbon($date) {
        if ( $date == "0" || $date == 0 ) {
            return now()->addYears(2)->format('Y-m-d H:i:s');
        } else {
            $winSecs       = (int)($date / 10000000); // divide by 10 000 000 to get seconds
            $unixTimestamp = ($winSecs - 11644473600); // 1.1.1600 -> 1.1.1970 difference in seconds
            $date = date(DateTime::RFC822, $unixTimestamp);
            return Carbon::parse( $date )->format('Y-m-d H:i:s');
        }
    }
}

if ( ! function_exists('ldapFormatDate') ) {
    /**
     * Convert LDAP dates to valid date
     *
     * @param $date
     * @return string|array
     */
    function ldapFormatDate($date) {
        if ( is_array($date) && count($date) > 1 ) {
            $dates = [];
            foreach ($date as $dt) {
                $new_date = explode('.', $dt);
                $new_date = isset($new_date[0]) ? $new_date[0] : '0000-00-00 00:00:00';
                $dates[] = Carbon::parse( $new_date )->format('Y-m-d H:i:s');
            }
            return $dates;
        } elseif (is_array($date) && count($date) == 1) {
            $new_date = explode('.', $date[0]);
            $new_date = isset($new_date[0]) ? $new_date[0] : '0000-00-00 00:00:00';
            return Carbon::parse( $new_date )->format('Y-m-d H:i:s');
        } else {
            $new_date = explode('.', $date);
            $new_date = isset($new_date[0]) ? $new_date[0] : '0000-00-00 00:00:00';
            return Carbon::parse( $new_date )->format('Y-m-d H:i:s');
        }
    }
}