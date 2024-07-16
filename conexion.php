<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "registrosacademicos";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

//datos del formulario
$nombre = $_POST['nombreAlumno'];
$apellido = $_POST['apellidoAlumno'];
$grado = $_POST['gradoAlumno'];
$actividad = $_POST['actividadExtracurricular'];
$horario = $_POST['horario'];
$instructor = $_POST['nombreInstructor'];
$correo = $_POST['correoInstructor'];
$observaciones = $_POST['observaciones'];

// ejecutar consultas por separado pero manteniendo la conexion
$primeraConsulta = $conn->prepare("INSERT INTO alumnos (nombre, apellido, grado) VALUES (?, ?, ?)");
$primeraConsulta->bind_param("sss", $nombre, $apellido, $grado);

if ($primeraConsulta->execute()) {
    $alumno_id=$primeraConsulta->insert_id;
    echo "Se guardo correctamente los datos en la tabla alumno.<br>";
} else {
    echo "Error al guardar los datos del alumno: " . $primeraConsulta->error . "<br>";
}



$segundaConsulta = $conn->prepare("INSERT INTO instructores (nombre, correo, observaciones) VALUES (?, ?, ?)");
$segundaConsulta->bind_param("sss", $instructor, $correo, $observaciones);

if ($segundaConsulta->execute()) {
    $instructor_id = $segundaConsulta->insert_id;
    echo "Se guardo correctamente los datos en la tabla instructores.<br>";
} else {
    echo "Error al guardar los datos del instructor: " . $segundaConsulta->error . "<br>";
}



$terceraConsulta = $conn->prepare("INSERT INTO actividades (nombre, horario, instructor_id) VALUES (?, ?, ?)");
$terceraConsulta->bind_param("ssi", $actividad, $horario, $instructor_id);

if ($terceraConsulta->execute()) {
    $actividad_id = $terceraConsulta->insert_id;
    echo "Se guardo correctamente los datos en la tabla actividades.<br>";
} else {
    echo "Error al guardar los datos de la actividad: " . $terceraConsulta->error . "<br>";
}


$cuartaConsulta = $conn->prepare("INSERT INTO alumnos_actividades (alumno_id, actividad_id) VALUES (?, ?)");
$cuartaConsulta->bind_param("ii", $alumno_id, $actividad_id);

if ($cuartaConsulta->execute()) {
    echo "Se guardo correctamente la relacion entre alumno y su actividad.";
} else {
    echo "Error al guardar la relación entre el alumno y laactividad: " . $cuartaConsulta->error;
}


// Cerrar las declaraciones y la conexión
$primeraConsulta->close();
$segundaConsulta->close();
$terceraConsulta->close();
$cuartaConsulta->close();
$conn->close();
?>
