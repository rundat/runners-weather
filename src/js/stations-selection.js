function selectStations (lat, lon, radius)
{
    function getStationsJSON ()
    {
        var req = new XMLHttpRequest ();
        req.open ( 'GET', '/data/stations.json', false );
        req.setRequestHeader ('Content-type', 'application/json') ;
        var json ;
        req.onreadystatechange = function ()
        {
            if ( req.readyState == 4 && req.status == 200 )
            {
                json = JSON.parse ( req.responseText );
            }
        }
        req.send () ;
        return json ;
    } ;

    /* Returned value is in kilometers. x is lat, y is lon. */
    function distance (x1, y1, x2, y2)
    {
        function rad (degrees)
        {
            return degrees * (Math.PI / 180) ;
        }
        var a = Math.pow (Math.sin (rad (x2 - x1) / 2), 2)
            + ( Math.pow (Math.sin (rad (y2 - y1) / 2), 2)
                * Math.cos (rad (x1))
                * Math.cos (rad (x2)) ) ;

        return Math.atan2 (Math.sqrt (a), Math.sqrt (1 - a))
            * 2
            * 6371 ;
    }

    var stations = getStationsJSON () ;
    var selected = [] ;

    for (s in stations)
    {
        var station = stations [s] ;
        var d = distance (station.latitude, station.longitude, lat, lon) ;
        if (d < radius)
        {
            selected.push (station) ;
        }
    }

    return selected ;

}
