<?php
$fp = fopen("temperature.txt", 'r');
$fh = fopen("humidity.txt", 'r');

//Inicialitzo les dues variables
$temperature = "";
$humidity = "";

/*recuperar datos del fichero temperature$temperature.txt */
if (!$fp) {
    echo '<p>No he pogut. Torna a introduir-la</p>';
} else {
    //En cas que hagi pogut obrir bloquejo
    flock($fp, LOCK_EX);

    //Vaig extraient les dades de temperature$temperature
    while (!feof($fp)) {
        $temperature = $temperature . fgets($fp, 1024);
    }
    //eliminar ultima linea en blanco
    $temperature = chop($temperature, " ");
    flock($fp, LOCK_UN);
    fclose($fp);
}

/*recuperar datos del fichero humidity$humidity.txt */
if (!$fh) {
    echo '<p>No he pogut. Torna a introduir-la</p>';
} else {
    //En cas que hagi pogut obrir bloquejo
    flock($fh, LOCK_EX);

    //Vaig extraient les dades de temperature$temperature
    while (!feof($fh)) {
        $humidity = $humidity .  fgets($fh, 1024);
    }
    //eliminar la ultima coma en caso de que el fichero humidity$humidity tenga la ultima linea en blanco
    $humidity = chop($humidity, " ");
    flock($fh, LOCK_UN);
    fclose($fh);
}

/*tratamiento de errores del sensor, se resta dos ya que es chino y lee regular. La tolerancia del proximo sensor sera de 0.1%  */
$temperature = (float)$temperature - 2;
$humidity = (float)$humidity - 2;

/* inyeccion en la base de datos */
$llave = 0;
inserta($llave, $temperature, $humidity);

//Un cop llegit la dada i guardada enm la BD borro el que hi ha dins al fitxer temperature$temperature.txt
$fq = fopen("temperature.txt", 'w+');
$temperature = "";
if (!$fq) {
    echo '<p>No he pogut emmagatzemar el contingut de la comanda. Torna a introduir-la</p>';
} else {
    //En cas que hagi pogut obrir bloquejo
    flock($fq, LOCK_EX);

    fwrite($fq, $temperature);

    flock($fq, LOCK_UN);
    fclose($fq);
}
//Un cop llegit la dada i guardada enm la BD borro el que hi ha dins al fitxer humidity$humidity.txt
$fq2 = fopen("humidity.txt", 'w+');
$humidity = "";
if (!$fq2) {
    echo '<p>No he pogut emmagatzemar el contingut de la comanda. Torna a introduir-la</p>';
} else {
    //En cas que hagi pogut obrir bloquejo
    flock($fq2, LOCK_EX);

    fwrite($fq2, $humidity);

    flock($fq2, LOCK_UN);
    fclose($fq2);
}

//funcion para insertar en la tabla tblsensores
function inserta($llave,  $temperature, $humidity)
{
    //base de datos
    $h = 'localhost';
    $u = 'USER';
    $p = 'password';
    $b = 'temperaturehumidity';
    $ok = 1;

    $conn = mysqli_connect($h, $u, $p, $b);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    //insert en la base de datos

    $sql = 'INSERT INTO tblsensores VALUES (' . $llave . ', ' . $temperature . ', ' . $humidity . ');';
    if (mysqli_query($conn, $sql)) {
        $res = array('val' => $ok);
        echo json_encode($res);
    } else {
        $ok = 0;
        $res = array('val' => $ok);
        echo json_encode($res);
        //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>