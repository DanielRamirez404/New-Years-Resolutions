<?php
	function get_connexion($username, $password) {
		$con = new mysqli("localhost", $username, $password, "Resolutions");

		if ($con->connect_error)
			throw new Exception("Sus credenciales no son válidas");
	
		return $con;
	}

	function create_connexion() {
		if (isset($_SESSION["username"]))
			return;

		if (!isset($_POST["username"]) || !isset($_POST["password"]))
			throw new Exception("No hay credenciales suficientes para el inicio de sesión");

		$username = depurar($_POST["username"]);	
		$password = depurar($_POST["password"]);

		$con = get_connexion($username, $password);

		$_SESSION["username"] = $username;
	       	$_SESSION["password"] = $password;	
	}

	function get_session_connexion() {
		if (!isset($_SESSION["username"]) || !isset($_SESSION["password"]))
			throw new Exception("No hay credenciales suficientes para conectarse a la base de datos");
		
		return get_connexion($_SESSION["username"], $_SESSION["password"]);	
	}
?>
