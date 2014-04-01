<?php

class Basedatos
{

	/**
	 * @var Basedatos Contiene la instancia de Basedatos.
	 */
	private static $_instancia;

	/**
	 *
	 * @var boolean|mysqli Contiene el objeto mysqli después de que se haya
	 * establecido la conexión.
	 */
	protected static $_mysqli = false;

	//----------------------------------------------------------------------------------------------------
	/**
	 * Crea la conexión al servidor o devuelve error parando la ejecución.
	 *
	 * @return Basedatos Devuelve la referencia al objeto Basedatos.
	 */
	public static function getInstancia()
	{
		if (!self::$_instancia instanceof self)
		{
			// Creamos una nueva instancia de basedatos.
			self::$_instancia = new self;
		}

		// Si la instancia ya estaba creada, la devolvemos.
		return self::$_instancia;
	}

	private function __construct()
	{
		// Creamos el objeto mysqli y lo asignamos a $_mysqli
		self::$_mysqli = @new mysqli(BD_SERVIDOR, BD_USUARIO, BD_PASSWORD, BD_NOMBRE);

		// Para que los datos de las conexiones vayan en UTF-8.
		self::$_mysqli->query("set names 'UTF8'");

		if (self::$_mysqli->connect_error)
		{
			echo "Error conectando Base Datos: " . self::$_mysqli->connect_error;
			self::$_mysqli = false;
			die();
		}
	}

	//----------------------------------------------------------------------------------------------------
	/**
	 * Función close()
	 * Cierra una conexión activa con el servidor
	 *
	 * @access public
	 * @return boolean Siempre devolverá true.
	 */
	public function close()
	{
		if (self::$_mysqli)
		{
			self::$_mysqli->close();
			self::$_mysqli = false;
		}
		return true;
	}

	/**
	 * Función inyeccion (evitarInyeccion)
	 * 
	 * @param string $dato Se le pasa un campo y hace el real_escap_string.
	 * @return string
	 */
	public function depurarCampo($dato)
	{
		$filtro = new InputFilter();
		$dato = $filtro->process($dato);
		return self::$_mysqli->real_escape_string($dato);
	}

	/**
	 * Función ejecutar SQL.
	 * 
	 * 
	 * @param type $sql
	 * @return string ok|error Para insert/update/delete o un Array JSON con las filas de resulta o vacio si no hay.
	 */
	public function ejecutarSQL($sql)
	{
		if (!empty($sql))
		{
			$consultas_simples = array("insert", "update", "delete");
			$tipo = explode(' ', trim($sql));
			if (in_array(strtolower($tipo[0]), $consultas_simples))
			{ // Es una consulta simple de insert/update/delete. Devuelve ok/error si ha habido algún problema.
				if (self::$_mysqli->query($sql))
					return 'ok';
				else
					return 'error';
			} else
			{ // Es una consulta de SELECT, puede devolver valores. Devolverá un array con los registros en formato JSON.
				$resultados = self::$_mysqli->query($sql) or die(self::$_mysqli->error);

				// Creamos un array para recorrer los resultados.
				$json = array();
				
				if ($resultados->num_rows != 0)
				{
					// Recorremos el recordset y metemos los resultados en un array.
					while ($fila = $resultados->fetch_assoc())
					{
						$json[] = $fila;
					}
				}
				
				return json_encode($json);	// Devuelve el array $json con las filas del recordset o vacío.
			}
		}
		else
			return 'Error';
	}

}

?>