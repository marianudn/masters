<?php

$dbhoast = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname =  "masters";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn)
{
	die("No hay conexion: ".mysqli_connect_error());
}

$nombre = $_POST["txtusuario"];
$pass = $_POST["txtpassword"];

$query = mysqli__query($conn,"SELECT * FROM usuario WHERE login " '".$nombre."' and clave " '".$pass."'");
$nr =mysqli_num_rows($query);


$nr(nr ==1)
{
	//header("Location: pagina.html")
	echo "Bienvenido:" .$nombre;
}

else if ($nr == 0)
{
		echo: "No  ingreso";
}

?>