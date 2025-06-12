<?php
	include("../include/depurar.php");

	function create_connexion() {
		session_start();

		if (isset($_SESSION["con"]))
			return;

		if (!isset($_POST["username"]) || !isset($_POST["password"]))
			throw new Exception("No hay credenciales suficientes para el inicio de sesión");

		$username = depurar($_POST["username"]);	
		$password = depurar($_POST["password"]);

		$_SESSION["con"] = new mysqli("localhost", $username, $password, "Resolutions");	
	}
?>
