<?php

require ('stations.php') ;
require ('pollution.php') ;
require ('aqi.php') ;

if (!isset ($_GET['lat']) || !isset ($_GET['lon']) || !isset ($_GET['rad']))
{
    exit ('Please defined $_GET["lat"], $_GET["lon"] and $_GET["rad"].') ;
}

$lat = floatval ($_GET['lat']) ; // 48.8534100 ;
$lon = floatval ($_GET['lon']) ; // 2.3488000 ;
$rad = floatval ($_GET['rad']) ; // 10 ;

$runners_weather = [] ;

/* Record parameters used. */
$runners_weather ['lat'] = $lat ;
$runners_weather ['lon'] = $lon ;
$runners_weather ['rad'] = $rad ;

/* Set the date to today's date. */
$runners_weather ['date'] = date ('Y-m-d', time ()) ;

/* Get stations list in circle of center ([lat], [lon])
 * and of radius [rad]. */
$runners_weather ['stations'] = Stations :: selectStations ($lat, $lon, $rad) ;

/* Get raw pollution data. */
$pollution = Pollution :: getPollution () ;

/* Filter pollution data. */
$selected_pollution = [] ;
foreach ($runners_weather ['stations'] as $i => $station)
{
    foreach ($pollution [$station] as $pollutant => $conc)
    {
        $selected_pollution [$pollutant] [] = $conc ;
    }
}

/* Compute aqi for each pollutant.
 * $aqi [$pol] = [d -1, d, d + 1, d + 2]. */
$runners_weather ['aqi'] = [] ;
foreach ($selected_pollution as $pollutant => $conc)
{
    $runners_weather ['aqi'] [$pollutant] =
    [ AQI :: computeAQI ($pollutant, max (array_column ($conc, 0))),
      AQI :: computeAQI ($pollutant, max (array_column ($conc, 1))),
      AQI :: computeAQI ($pollutant, max (array_column ($conc, 2))),
      AQI :: computeAQI ($pollutant, max (array_column ($conc, 3))) ] ;
}

header('Content-type: application/json') ;
print (json_encode ($runners_weather)) ;

?>
