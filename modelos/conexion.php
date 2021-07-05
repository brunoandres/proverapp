<?php

class Conexion{

	static public function conectarMysql(){

		$servidor= "localhost";
    $usuario= "root";
    $password = "";
    $base= "soyem_";

    $con = mysqli_connect($servidor, $usuario, $password ,$base)
        or die("No se pudo conectar con la base de datos");
		mysqli_set_charset($con, "utf8");

    return $con;

	}

	static public function conectar(){

		$link = new PDO("mysql:host=localhost;dbname=proverapp",
			            "root",
			            "");

		$link->exec("set names utf8");

		return $link;

	}

}

class ConexionSoyem{

	static public function conectarSoyem(){

		$link = new PDO("mysql:host=localhost;dbname=soyem_",
			            "root",
			            "");

		$link->exec("set names utf8");

		return $link;

	}

}

define('METHOD','AES-256-CBC');
define('SECRET_KEY','$SOYEM@2020');
define('SECRET_IV','102030');

class SED {
	public static function encryption($string){
		$output=FALSE;
		$key=hash('sha256', SECRET_KEY);
		$iv=substr(hash('sha256', SECRET_IV), 0, 16);
		$output=openssl_encrypt($string, METHOD, $key, 0, $iv);
		$output=base64_encode($output);
		return $output;
	}
	public static function decryption($string){
		$key=hash('sha256', SECRET_KEY);
		$iv=substr(hash('sha256', SECRET_IV), 0, 16);
		$output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
		return $output;
	}
}
