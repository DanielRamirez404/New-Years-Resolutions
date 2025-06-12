<?php
	include("../include/depurar.php");	
	include("conexion.php");	

	session_start();

	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		header("location: ../error.php");
		exit();	
	}


	try {
		create_connexion();	
		header("location: ../menu.php");
	} catch(Exception $e) {
		header("location: ../error.php");
		echo $e->getMessage();	
		exit();
	}
?>
