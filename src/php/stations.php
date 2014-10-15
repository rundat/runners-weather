<?php

require ('stations_data.php') ;

/* Returned value is in kilometers. x is lat, y is lon. */
function distance ($x1, $y1, $x2, $y2)
{
    $a = pow (sin (deg2rad ($x2 - $x1) / 2), 2)
    + ( pow (sin (deg2rad ($y2 - $y1) / 2), 2)
        * cos (deg2rad ($x1))
        * cos (deg2rad ($x2)) ) ;

    return atan2 (sqrt ($a), sqrt (1 - $a))
    * 2
    * 6371 ;
}

function stations ($lat, $lon, $radius)
{
    global $STATIONS ;
    $selected = [] ;

    foreach ($STATIONS as $station => $info)
    {
        if (distance ($info ['latitude'], $info ['longitude'], $lat, $lon))
        {
            $selected [] = $station ;
        }
    }

    return $selected ;
}

?>
