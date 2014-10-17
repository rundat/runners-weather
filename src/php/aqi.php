<?php
class AQI
{
    /* Breakpoints concentration values. */

    /* CO:
    1 ppm = 1.145mg/m3
    1mg/m3 = 0.873 ppm */
    private static $CO_ppm =
    [0, 4.5, 9.5, 12.5, 15.5, 30.5, 40.5, 50.4] ;
    private static $CO_mg =
    [0, 5.1525, 10.8775, 14.3125, 17.7475, 34.9225, 46.3725, 57.708] ;
    private static $CO_ug =
    [0, 5152.5, 10877.5, 14312.5, 17747.5, 34922.5, 46372.5, 57708] ;

    /* NO2:
    1 ppm = 1,880µg/m3
    1 µg/m3 = 0.000532 ppm
     */
    private static $NO2_ppm =
    [0, .054, .101, .361, .650, 1.250, 1.650, 2.049] ;
    private static $NO2_ug =
    [0, .102, .19, .679, 1.223, 2.352, 3.105, 3.856] ;

    private static $PM25_ug =
    [0, 12.1, 35.5, 55.5, 150.5, 250.5, 350.5, 500.5];
    private static $PM10_ug =
    [0, 55, 155, 255, 355, 425, 505, 605] ;

    /* O3:
    1 ppm = 2mg/m3 */
    private static $O3_8h_ppm =
    [0, .060, .076, .096, .116, .375] ;
    private static $O3_8h_mg =
    [0, .120, .152, .192, .232, .750] ;
    private static $O3_8h_ug =
    [0, 120., 152., 192., 232., 750.] ;

    private static function linear ($conc, $model, $i)
    {
        $AQIStage = [0, 50, 100, 120, 200, 300, 400, 500] ;

        $AQIhigh = $AQIStage [$i] ;
        $AQIlow =  $AQIStage [$i - 1] ;
        $conclow = $model [$i - 1] ;
        $conchigh = $model [$i] ;

        $AQI =
        ( ($AQIhigh - $AQIlow) / ($conchigh - $conclow))
        * ($conc - $conclow) + $AQIlow;

        return intval (ceil ($AQI)) ;
    }

    private static function AQI_aux ($conc, $model)
    {

        if ($conc < 0)
        {
            return "Out_of_range" ;
        }

        foreach ($model as $i => $c)
        {
            if ($conc < $c)
            {
                return self :: linear ($conc, $model, $i) ;
            }
        }

        return -1 ;

    }

    public static function computeAQI ($pollutant, $conc)
    {
        switch ($pollutant)
            {
            case "PM10":
                return self :: AQI_aux ($conc, self :: $PM10_ug) ;
            case "PM25":
                return self :: AQI_aux ($conc, self :: $PM25_ug) ;
            case "NO2":
                return self :: AQI_aux ($conc, self :: $NO2_ug) ;
            case "O3":
                return self :: AQI_aux ($conc, self :: $O3_8h_ug) ;
        } ;
    }
}
?>
