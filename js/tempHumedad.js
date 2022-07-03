$(document).ready(function () {

    //boton para probar la lectura del fichero y hacer el insert de la temp y humidity en la base datos
    $('#charge').click(function () {
        document.location.href = "getSetTH.php";
        alert("Gas Boiler ON");
    });
})

cargaDatos();
//script que genera los medidores de temperature y humidity proporcionados por google
google.charts.load('current', { 'packages': ['gauge'] });
google.charts.setOnLoadCallback(drawChart);

//funcion para cargar la base de datos cada minuto
function cargaDatos() {
    //llama al fichero (getSetTempHume.php) que lee el txt, sube los datos a la BD y borra el txt.
    setInterval(function () {
        $.ajax({
            url: "getSetTH.php",
            type: 'GET',
            dataType: 'json',
            async: true,
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                if (data.val == 1) {
                    alert("OK");
                } else {
                    alert("NO CHARGE");
                }

            },
            error: function (data) {
                alert('Failure in getSetTH.php! '+ data.val);
            }
        });
    }, /*960000*/60000);
}

//funcion para dibujar los medidores 
function drawChart() {
    //datos
    var data = google.visualization.arrayToDataTable([
        ['Label', 'Value'],
        ['humidity', 0],
        ['temperature', 0]

    ]);
    /* opciones  */
    var options = {
        width: 400, height: 400,
        redFrom: 90, redTo: 100,
        yellowFrom: 75, yellowTo: 90,
        minorTicks: 5
    };
    //creando el medidor
    //se actualiza cada 16(960 000ms) min porque el sensor lee cada 15(900 000ms) min
    var chart = new google.visualization.Gauge(document.getElementById('gauges'));

    chart.draw(data, options);

    setInterval(function () {
        var JSON = $.ajax({
            /*                 url:"http://localhost/Example/sensores/DatoSensores.php?q=1",*/
            url: "DatoSensores.php?q=1",
            dataType: 'json',
            async: false
        }).responseText;
        var Respuesta = jQuery.parseJSON(JSON);

        //posicion 0 de columna 1
        data.setValue(0, 1, Respuesta[0].humidity);
        data.setValue(1, 1, Respuesta[0].temperature);
        chart.draw(data, options);
    }, /*960000*/90000);

}