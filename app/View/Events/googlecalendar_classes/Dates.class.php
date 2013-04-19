<?php

Class Dates extends date {

    function validateAndConvertTimeRange($timezone, $start, $end)
    {
        if(!array_key_exists($timezone, $this->tz))
            $this->renderError("This is not a valid timezone [$timezone]", 400);

        if(!$start || !$end) return FALSE;

        $return['start'] = date::convert_to_mysql(date::convert_from_timezone($start, $timezone));
        $return['end']   = date::convert_to_mysql(date::convert_from_timezone($end, $timezone));
        
        return (object)$return;
    }
}
