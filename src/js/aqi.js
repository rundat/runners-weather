/* Breakpoints concentration values. */

/* CO:
   1 ppm = 1.145mg/m3
   1mg/m3 = 0.873 ppm */
var CO_ppm = [0, 4.5, 9.5, 12.5, 15.5, 30.5, 40.5, 50.4] ;
var CO_mg = [0, 5.1525, 10.8775, 14.3125, 17.7475, 34.9225, 46.3725, 57.708] ;
var CO_ug = [0, 5152.5, 10877.5, 14312.5, 17747.5, 34922.5, 46372.5, 57708] ;

/* NO2:
   1 ppm = 1,880µg/m3
   1 µg/m3 = 0.000532 ppm */
var NO2_ppm = [0, .054, .101, .361, .650, 1.250, 1.650, 2.049] ;
var NO2_ug = [0, .102, .19, .679, 1.223, 2.352, 3.105, 3.856] ;

var PM25_ug = [0, 12.1, 35.5, 55.5, 150.5, 250.5, 350.5, 500.5];
var PM10_ug = [0, 55, 155, 255, 355, 425, 505, 605] ;

/* O3:
   1 ppm = 2mg/m3 */
var O3_8h_ppm = [0, .060, .076, .096, .116, .375] ;
var O3_8h_mg = [0, .120, .152, .192, .232, .750] ;
var O3_8h_ug = [0, 120., 152., 192., 232., 750.] ;

/* @conc concentration in µg/m3.
 * @model breakpoints concentration values. See *_ug variables.
 */
function AQI (conc, model)
{

    var AQIStage = [0, 50, 100, 120, 200, 300, 400, 500] ;

    function linear (conc, model, i)
    {

        var AQIhigh = AQIStage [i] ;
        var AQIlow =  AQIStage [i - 1] ;
        var conclow = model [i - 1] ;
        var conchigh = model [i] ; //- 1 ;

        var AQI =
            ( (AQIhigh - AQIlow) / (conchigh - conclow))
            * (conc - conclow) + AQIlow;

        return Math.round (AQI);
    } ;

    if (conc < 0)
    {
        return "Out_of_range" ;
    }

    for (var i = 0, len = model.length; i < len; i++)
    {
        if (conc < model[i])
        {
            return linear (conc, model, i) ;
        }
    }

    return "Out_of_range" ;
}
