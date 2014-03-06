<?php
@session_start();

// Cargamos el fichero de constantes
require_once __DIR__.DIRECTORY_SEPARATOR.'config.php';
// Cargamos librería de funciones.
require_once RUTA_LIB.'funciones.php';

// Función para cargar clases de forma automática.
function cargarClases($nombreclase){
	require_once RUTA_CLASES . strtolower($nombreclase) . '.php';
}

// Automatización de carga de clases registrando la función en spl_autoload_register;
spl_autoload_register('cargarClases');
?>