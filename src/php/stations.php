<?php

include ('stations_data.php') ;

class Stations extends Stations_data
{

    /* Returned value is in kilometers. x is lat, y is lon. */
    private static function distance ($x1, $y1, $x2, $y2)
    {
        $a = pow (sin (deg2rad ($x2 - $x1) / 2), 2)
        + ( pow (sin (deg2rad ($y2 - $y1) / 2), 2)
            * cos (deg2rad ($x1))
            * cos (deg2rad ($x2)) ) ;

        return atan2 (sqrt ($a), sqrt (1 - $a))
        * 2
        * 6371 ;
    }

    public static function selectStations ($lat, $lon, $radius)
    {
        $selected = [] ;

        foreach (self :: $stations_data as $station => $info)
        {
            if (self :: distance
                ($info ['latitude'], $info ['longitude'], $lat, $lon)
                <= $radius)
            {
                $selected [] = $station ;
            }
        }

        return $selected ;
    }

}
?>
