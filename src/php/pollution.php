<?php

$DATA_FILE = 'pollution.json' ;

function serveData ()
{
    global $DATA_FILE ;
    header('Content-type: application/json') ;
    $data = file_get_contents ($DATA_FILE) ;
    return $data && print ($data) ;
}

function updateData ()
{
    global $DATA_FILE ;

    $ch = curl_init ('http://sagotch.fr/esmeralda-web-unofficial/') ;
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
    $json = curl_exec ($ch) ;
    curl_close ($ch) ;

    return file_put_contents ($DATA_FILE, $json)
    || (unlink ($DATA_FILE) && false) ;

}

function isUpToDate ()
{
    global $DATA_FILE ;
    return file_exists ($DATA_FILE)
    && (date ('Y-m-d', filemtime ($DATA_FILE)) == date ('Y-m-d', time ())) ;
}

/* Main */
return ((isUpToDate () || updateData ()) && serveData ())

?>
