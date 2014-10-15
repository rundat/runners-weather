<?php

$DATA_FILE = 'pollution.json' ;

function loadData ()
{
    global $DATA_FILE ;
    return unserialize (file_get_contents ($DATA_FILE)) ;
}

function updateData ()
{
    global $DATA_FILE ;

    $ch = curl_init ('http://sagotch.fr/esmeralda-web-unofficial/') ;
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
    $json = curl_exec ($ch) ;
    curl_close ($ch) ;

    $pollution = json_decode ($json, true) ;

    return file_put_contents ($DATA_FILE, serialize ($pollution))
    || (unlink ($DATA_FILE) && false) ;

}

function isUpToDate ()
{
    global $DATA_FILE ;
    return file_exists ($DATA_FILE)
    && (date ('Y-m-d', filemtime ($DATA_FILE)) == date ('Y-m-d', time ())) ;
}

/* Main */
if (!isUpToDate ())
{
    updateData () ;
}
$POLLUTION = loadData () ;

?>
