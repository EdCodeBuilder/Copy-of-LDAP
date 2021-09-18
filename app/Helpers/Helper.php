<?php

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

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

if ( !function_exists('validateDate') ) {
    /**
     * The method to return upper string including spanish chars
     *
     * @param $date
     * @param string $format
     * @return bool
     */
    function valiateDate( $date, $format = 'Y-m-d' )
    {
        try {
            Carbon::parse($date)->format($format);
            return true;
        } catch (Exception $exception) {
            return false;
        }
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

if ( ! function_exists('isJson') ) {
    /**
     * @param $string
     * @return bool
     */
    function isJson($string) {
        try {
            if (is_null($string)) {
                return false;
            }
            // decode the JSON data
            $result = is_array($string)
                ? json_encode($string)
                : json_decode($string);

            // switch and check possible JSON errors
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    $error = ''; // JSON is valid // No error has occurred
                    break;
                case JSON_ERROR_DEPTH:
                    $error = 'The maximum stack depth has been exceeded.';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $error = 'Invalid or malformed JSON.';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $error = 'Control character error, possibly incorrectly encoded.';
                    break;
                case JSON_ERROR_SYNTAX:
                    $error = 'Syntax error, malformed JSON.';
                    break;
                // PHP >= 5.3.3
                case JSON_ERROR_UTF8:
                    $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                    break;
                // PHP >= 5.5.0
                case JSON_ERROR_RECURSION:
                    $error = 'One or more recursive references in the value to be encoded.';
                    break;
                // PHP >= 5.5.0
                case JSON_ERROR_INF_OR_NAN:
                    $error = 'One or more NAN or INF values in the value to be encoded.';
                    break;
                case JSON_ERROR_UNSUPPORTED_TYPE:
                    $error = 'A value of a type that cannot be encoded was given.';
                    break;
                default:
                    $error = 'Unknown JSON error occured.';
                    break;
            }
            return $error !== '';
        } catch (Exception $exception) {
            return false;
        }
    }
}

if ( ! function_exists('mask') ) {
    /**
     * @param $str
     * @param $first
     * @param $last
     * @return string
     */
    function mask($str, $first, $last) {
        $len = strlen($str);
        $toShow = $first + $last;
        return substr($str, 0, $len <= $toShow ? 0 : $first).str_repeat("*", $len - ($len <= $toShow ? 0 : $toShow)).substr($str, $len - $last, $len <= $toShow ? 0 : $last);
    }
}

if ( ! function_exists('mask_email') ) {
    /**
     * @param $email
     * @return string
     */
    function mask_email($email) {
        $mail_parts = explode("@", $email);
        $domain_parts = explode('.', $mail_parts[1]);

        $mail_parts[0] = mask($mail_parts[0], 2, 1); // show first 2 letters and last 1 letter
        $domain_parts[0] = mask($domain_parts[0], 2, 1); // same here
        $mail_parts[1] = implode('.', $domain_parts);

        return implode("@", $mail_parts);
    }
}

if ( ! function_exists('format_contract') ) {
    function format_contract($number, $year) {
        $contract_number = str_pad($number, 4, '0', STR_PAD_LEFT);
        return toUpper("IDRD-CTO-{$contract_number}-{$year}");
    }
}

if ( ! function_exists('random_img_name') ) {
    /**
     * @return string
     */
    function random_img_name() {
        $s = strtoupper(md5(uniqid(rand(),true)));
        return substr($s,0,8) . '-' .
                substr($s,8,4) . '-' .
                substr($s,12,4). '-' .
                substr($s,16,4). '-' .
                substr($s,20);
    }
}

if ( ! function_exists('template_exist') ) {
    /**
     * @param $file
     * @param string $disk
     * @param string $path
     * @return bool
     */
    function template_exist($file, string $disk = 'local', string $path = 'templates/') {
        return \Illuminate\Support\Facades\Storage::disk($disk)->exists("{$path}{$file}");
    }
}

if ( ! function_exists('get_template') ) {
    /**
     * @param $file
     * @param string $disk
     * @param string $path
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    function get_template($file, string $disk = 'local', string $path = 'templates/') {
        try {
            return \Illuminate\Support\Facades\Storage::disk($disk)->get("{$path}{$file}");
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $exception) {
            return false;
        }
    }
}

if ( ! function_exists('array_insert_in_position') ) {
    /**
     * @param array $array
     * @param array $insertedArray
     * @param int $position
     * @return array
     */
    function array_insert_in_position(array $array, array $insertedArray, int $position = 0) {
        $i = 0;
        $new_array = [];
        foreach ($array as $value) {
            if ($i == $position) {
                foreach ($insertedArray as $ivalue) {
                    $new_array[] = $ivalue;
                }
            }
            $new_array[] = $value;
            $i++;
        }
        return $new_array;
    }
}

if ( ! function_exists('str_starts_with') ) {
    function str_starts_with($haystack, $needle) {
        $length = strlen($needle);
        return substr($haystack, 0, $length) === $needle;
    }
}

if ( ! function_exists('str_ends_with') ) {
    function str_ends_with($haystack, $needle) {
        $length = strlen($needle);
        if (!$length) {
            return true;
        }
        return substr($haystack, -$length) === $needle;
    }
}

if ( ! function_exists('class_dash_name') ) {
    function class_dash_name($class) {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', class_basename($class)));
    }
}

if ( ! function_exists('verify_url') ) {
    /**
     * @param $url
     * @return bool
     */
    function verify_url($url) {
        try {
            if ($url == null) {
                return false;
            }
            $client = new Client();
            $data = $client->head( $url );
            $status = $data->getStatusCode();
            return $status >= 200 && $status < 300;
        } catch (ClientException $e) {
            return false;
        }
    }
}
