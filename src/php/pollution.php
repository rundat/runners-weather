<?php

class Pollution
{
    private static $DATA_FILE = 'pollution.json' ;
    private static $ESMERALDA_URL =
    'http://sagotch.fr/esmeralda-web-unofficial/' ;

    private static function loadData ()
    {
        return unserialize (file_get_contents (self :: $DATA_FILE)) ;
    }

    private static function updateData ()
    {
        $ch = curl_init (self :: $ESMERALDA_URL) ;
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
        $json = curl_exec ($ch) ;
        curl_close ($ch) ;

        $pollution = json_decode ($json, true) ;

        return file_put_contents (self :: $DATA_FILE, serialize ($pollution))
        || (unlink (self :: $DATA_FILE) && false) ;

    }

    private static function isUpToDate ()
    {
        return file_exists (self :: $DATA_FILE)
        && (date ('Y-m-d', filemtime (self :: $DATA_FILE))
            == date ('Y-m-d', time ())) ;
    }

    /* Main */
    public static function getPollution ()
    {
        if (!self :: isUpToDate () && !self :: updateData ())
        {
            exit ('Failed updating pollution data') ;
        }
        return self :: loadData () ;
    }
}
?>
