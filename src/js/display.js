function mkDiv (date, weather, pollution)
{
    var h1_date = document.createElement ("h1") ;
    h1_date.innerHTML = date ;

    var div_weather = document.createElement ("div") ;
    div_weather.innerHTML = weather ;

    var div_pollution = document.createElement ("div") ;
    div_pollution.innerHTML = JSON.stringify (pollution) ;

    var div_main = document.createElement ("div") ;
    div_main.appendChild (h1_date) ;
    div_main.appendChild (div_weather) ;
    div_main.appendChild (div_pollution) ;

    return div_main ;
}


function display (i, weather, pollution)
{
    var div = mkDiv (i, weather, pollution) ;
    document . body . appendChild (div) ;
}
