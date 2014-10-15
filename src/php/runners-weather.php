<?php

require ('stations.php') ;
require ('pollution.php') ;
require ('aqi.php') ;

function filterData ($lat, $lon, $radius)
{
    global $POLLUTION ;
    $stations = stations ($lat, $lon, $radius) ;
    $pollution = $POLLUTION ;
    $selected = [] ;

    foreach ($stations as $i => $station)
    {
        foreach ($pollution [$station] as $pollutant => $conc)
        {
            $selected ['pollution'] [$pollutant] [] = $conc ;
        }
    }
    return $selected ;
}

function toAQI ($pollution)
{
    $aqi = [] ;
    foreach ($pollution as $pollutant => $conc)
    {
        $aqi [$pollutant] =
        [ AQI ($pollutant, max (array_column ($conc, 0))),
          AQI ($pollutant, max (array_column ($conc, 1))),
          AQI ($pollutant, max (array_column ($conc, 2))),
          AQI ($pollutant, max (array_column ($conc, 3))) ] ;
    }
    return $aqi ;
}

$lat = 48.8534100 ;
$lon = 2.3488000 ;
$rad = 10 ;

$data = filterData ($lat, $lon, $rad) ;

header('Content-type: application/json') ;

$runners_weather ['date'] = date ('Y-m-d', time ()) ;

print (json_encode (toAQI ($data ['pollution']))) ;
/*
$conc = array_map ('max', $daily ['pollution']) ;

$runners_weather ['weather'] = [] ;

print (json_encode ($runners_weather)) ;
*/
?>
