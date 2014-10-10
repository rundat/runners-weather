/* Lazy pollution data loading. */
var loadPollution =
    (function ()
     {
         var pol ;
         return function ()
         {
             if (!pol)
             {
                 var xmlhttp = new XMLHttpRequest () ;
                 xmlhttp.onreadystatechange = function ()
                 {
                     if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
                     {
                         pol = JSON.parse (xmlhttp.responseText) ;
                     }
                 }
                 xmlhttp.open ("GET", "/php/pollution.php", false) ;
                 xmlhttp.send () ;
             }
             return pol ;
         } ;
     }) () ;

/**
 * Select station according to position and radius.
 * Return data with the following format:
 * data = { "weather": [d-1, d, d+1, d+2],
 *          "pollution": { "station": { "pol": [d-1, d, d+1, d+2],
 *                                      ... },
 *                         ... } }
 */
function filterData (lat, lon, radius)
{
    var stations = selectStations (lat, lon, radius) ;
    var pol = loadPollution () ;

    var data = {} ;
    data ['weather'] = {} ;
    data ['pollution'] = {} ;

    for (s in stations)
    {
        data ['pollution'] [stations[s]['id']] = pol [stations[s]['id']] ;
    }

    return data;
}

/**
 * Select a given day (between -1 and +2) and return weather and pollution
 * informations associated.
 * data = { "weather": [d-1, d, d+1, d+2],
 *          "pollution": { "pol": [s1, s2, ...], ... }, ... }
 */
function getDailyData (pollution, day)
{
    var data = {} ;
    data ['weather'] = {} ;
    data ['pollution'] = {} ;

    for (var s in pollution)
    {
        for (var p in pollution [s])
        {
            if (data ['pollution'] [p] != undefined)
            {
                data ['pollution'] [p] . push (pollution [s] [p] [1 + day]) ;
            }
            else
            {
                data ['pollution'] [p] = [pollution [s] [p] [1 + day]] ;
            }
        }
    }

    return data ;
}

function max (pollution)
{
    var max = {};

    for (var p in pollution)
    {
        max [p] =
            (pollution [p])
            . reduce (function (x,y) { return Math.max(x,y); }) ;
    }
    return max  ;
}


function processForm (form)
{
    var data = filterData (form.elements ["latitude"].value,
                           form.elements ["longitude"].value,
                           form.elements ["radius"].value) ;

    for (var i = 0; i <= 2; i++)
    {
        var weather = null ;
        var daily = getDailyData (data ['pollution'], i) ;
        var pollution = max (daily ['pollution']) ;
        display (i, weather, pollution) ;
    }
}
