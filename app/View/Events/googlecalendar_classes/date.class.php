<?php
require('date_Core.class.php');
/**
 * Description of My_date
 *
 */
class date extends date_Core {

    static public $tz = array (
        'acst' => 'America/Porto_Acre',
        'ast'  => 'America/Puerto_Rico',
        'bdst' => 'Europe/London',
        'bst'  => 'Europe/London',
        'edt'  => 'America/New_York',
        'est'  => 'America/New_York',
        'cdt'  => 'America/Chicago',
        'gmt'  => 'Greenwich',
        'mdt'  => 'America/Boise',
        'mst'  => 'America/Phoenix',
        'pdt'  => 'America/Los_Angeles',
        'pst'  => 'America/Los_Angeles',
        'akdt' => 'America/Juneau',
        'hst'  => 'Pacific/Honolulu',
        'chst' => 'Pacific/Guam',
        'sst'  => 'Pacific/Samoa',
        'utc'  => 'Universal',
        'wakt' => 'Pacific/Wake',
    );

    /**
     * A mapping of month numbers to days.
     * @var array
     */
    static private $month_to_days = array
    (
        1 => 31, 2 => 28, 3 => 31, 4  => 30, 5 => 31,  6  => 30,
        7 => 31, 8 => 31, 9 => 30, 10 => 31, 11 => 31, 12 => 31
    );

    /**
     * Check whether a date could be legitimate. Keep in mind that something
     *  that something 10,000 years into the future is legitimate too.
     * @param int $day
     * @param int $month
     * @param int $year
     * @return bool
     */
    static function is_valid($day, $month, $year)
    {
        $day    = intval($day);
        $month  = intval($month);
        $year   = intval($year);

        if($month == 2 && $day == 29 && self::is_leap_year($year))
            return true;

        if(   0    < $day
           && 0    < $month
           && 12   >= $month
           && $day <= self::$month_to_days[$month])
        {
            return true;
        }
        return false;
    }

    /**
     * Check for a leap year
     * @param int $year
     * @return bool
     */
    static function is_leap_year($year)
    {
        if($year % 400 == 0) return true;
        if($year % 100 == 0) return false;
        if($year % 4   == 0) return true;
        return false;
    }

    /**
     * Get a list of years given an offet and limit. Basically good for generating
     *  year ranges between the current year+offset and limit.
     * @param int $current_year_offset The point to start generating years from.
     *  Can be positive or negative, and must be relative to the current year.
     * @param int $limit The number of years to generate before stopping.
     *  Can be positive or negative (for generating dates backward). Relative to
     *  the starting point.
     * @return array A list of years
     */
    static function get_years_from_range($current_year_offset, $limit)
    {
        $years          = array();
        $current_year   = date('Y');
        $direction      = ($limit >= 0) ? 1 : -1;
        $limit          = abs($limit);

        for($i = 0; $i < $limit; $i++)
        {
            $years[] = ($current_year + $current_year_offset + $i*($direction));
        }

        return $years;
    }

    static function get_mysql_datetime($day = false, $month = false, $year = false)
    {
        $format = 'Y-m-d H:i:s';
        if($day && $month && $year)
            return date($format, strtotime("$year-$month-$day"));
        else
            return date($format);
    }

    /**
     *
     * @param integer unix $timestamp
     * @return string Y-m-d H:i:s
     */
    static function convert_to_mysql($timestamp)
    {
        return date('Y-m-d H:i:s', $timestamp);
    }

    static function get_timestamp($day, $month, $year)
    {
        return strtotime("$year-$month-$day");
    }

    static function getTimeZones()
    {
        return self::$tz;
    }

    static function get_timezone_offset($to_tz = false)
    {
        if($to_tz == false)
            $to_tz = 'America/New_York';

        # Check to see if we have an abbrevation like EDT, PST
        if(strlen($to_tz) <= 4)
            $to_tz = self::convert_timezone_abbreviation($to_tz);

        return timezone_offset_get(new DateTimeZone($to_tz), new DateTime("now"))/60/60;
    }

    /**
     *
     * @param integer $timestamp
     * @param string $to_tz
     * @param boolean $fixDST fix if day light savings time
     * @return string timestamp 
     */
    static function convert_to_timezone($timestamp, $to_tz, $fixDST=true)
    {
        $this_offset = self::get_timezone_offset();
        $to_offset   = self::get_timezone_offset($to_tz);
        if ( (boolean)date('I',$timestamp) )
        {
            $calcOffset =  ($to_offset - $this_offset) - 1;
        } else {
            $calcOffset =  ($to_offset - $this_offset);
        }
        $time = $timestamp + ($calcOffset)*60*60;
        return $time;
    }

    /**
     * The timestamp inputted is the time from that timezone and will convert it to UTC timezone (timezone of our servers)
     * @param integer $timestamp
     * @param string $from_tz
     * @return integer timestamp (unix time)
     */
    static function convert_from_timezone($timestamp, $from_tz)
    {
        $from_offset = self::get_timezone_offset($from_tz);
        $to_offset   = self::get_timezone_offset();
        
        $time = $timestamp + ($to_offset - $from_offset)*60*60;

        return $time;
    }

    static function convert_timezone_abbreviation($abbrev)
    {
        $abbrev = strtolower($abbrev);

        $tz = date::getTimeZones();

        if(key_exists($abbrev, $tz))
        {
            return $tz[$abbrev];
        } else {
            return false;
        }
    }

    /**
     * Validates a date, returnes true if it passes, false if it fails. Currently support two different date formats.
     * @param string $date in format 'US'='mm/dd/year'; 'ISO-8601'='yyyy-mm-dd'
     * @return boolean
     */
    public static function validateDate($date)
    {
        Kohana_Log::add('debug', "date::validateDate executing....");
        if (strpos($date, '/') === FALSE)
        {
            $format = 'ISO-8601';
        } else {
            $format = 'US';
        }
        Kohana_Log::add('debug', "Format for $date found to be $format");
        // init variables
        $dateParsed = array();
        switch ($format) {
            case 'US': // mm/dd/yyyy
                $dateXploded = explode('/',$date);
                if (count($dateXploded) == 3)
                {
                    //check to see if the year is larger then 4. if so then it probably has a timestamp so lets chop it off
                    $year = preg_split('/[\s]/',$dateXploded[2]);
                    $dateParsed = array('year'=>$year[0], 'month'=>$dateXploded[0], 'day'=>$dateXploded[1]);
                }
                break;
            case 'ISO-8601': // yyyy-mm-dd
                $dateXploded = explode('-',$date);
                if (count($dateXploded) == 3)
                {
                    //check to see if the year is larger then 4. if so then it probably has a timestamp so lets chop it off
                    $day = preg_split('/[\s]/',$dateXploded[2]);
                    $dateParsed = array('year'=>$dateXploded[0], 'month'=>$dateXploded[1], 'day'=>$day[0]);
                }
                Kohana_Log::add('debug', print_r($dateParsed, TRUE));
                break;
            default:
                //you shouldn't be here - return false
                Kohana_Log::add('debug', "date::validateDate couldn't figure out the format of the date; FAILED");
                return false;
                break;
        }

        if (self::is_valid($dateParsed['day'], $dateParsed['month'], $dateParsed['year']))
        {
            Kohana_Log::add('debug', "date::validateDate PASSED");
            return true;
        }
        Kohana_Log::add('debug', "date::validateDate FAILED");
        return false;
    }

}
?>
