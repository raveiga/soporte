<?php

function convertir_UTF8($dato)
{
	$codificacion = mb_detect_encoding($dato);
	if ($codificacion == 'ASCII')
		return utf8_encode($dato);
	else
		return $dato;
}

// Para que funcione LDAP en XAMPP.
// Habilitar extension=php_ldap.dll
// Copiar xampp/php/libsasl.dll  al directorio xampp/apache/bin
// Activar también extension=php_openssl.dll en xamp/php/php.ini
// Intro a LDAP : http://ldapman.org/articles/sp_intro.html
// http://www.php.net/manual/es/book.ldap.php
/*
  GLOSARIO:
  DN -> Distinguised Name: Todas las entradas almacenadas en un directorio LDAP tienen un único "Distinguished Name," o DN
  El DN para cada entrada está compuesto de dos partes: el Nombre Relativo Distinguido (RDN por sus siglas en ingles, Relative Distinguished Name) y la localización dentro del directorio LDAP donde el registro reside. l RDN es la porción de tu DN que no está relacionada con la estructura del árbol de directorio.

  DC -> Domain Component.
  OU -> Organizational Unit
  CN -> Common Name: la mayoría de los objetos que almacenarás en LDAP utilizarán su valor cn como base para su RDN

  EJEMPLO:
  El DN base de mi directorio es dc=foobar,dc=com
  Estoy almacenando todos los registros LDAP para mis recetas en ou=recipes
  El RDN de mi registro LDAP es cn=Oatmeal Deluxe

  Dado todo esto, ¿cuál es el DN completo del registro LDAP para esta receta de comida de avena ? Recuerda, se lee en órden inverso, hacia atrás - como los nombres de máquina en los DNS.

  cn=ComidaDeAvena Deluxe,ou=recipes,dc=foobar,dc=com

  Más información en:
  // http://www.centos.org/docs/5/html/CDS/ag/8.0/Finding_Directory_Entries-LDAP_Search_Filters.html
  // https://confluence.atlassian.com/display/DEV/How+to+write+LDAP+search+filters
  // http://grover.open2space.com/content/use-php-create-modify-active-directoryldap-entries
 */
class Ldap
{

	private $_servidor;
	private $_puerto;
	private $_dominio;
	private $_filtro = "(|(name=*)(displayname=*))";
	private $_camposMostrar = array("cn", "dn", "name", "displayname", "givenname", "description", "mail", "memberOf");
	private $_conexion = false;
	private $_baseDN;

	public function __construct($servidor, $dominio, $puerto = 389)
	{
		$this->_servidor = $servidor;
		$this->_dominio = utf8_encode(strtolower($dominio));

		// Obtenemos el BASE DN del Dominio
		$dominio = preg_split("/\./", $dominio);
		$this->_baseDN = '';
		for ($i = 0; $i < count($dominio); $i++)
		{
			$this->_baseDN.='DC=' . strtolower($dominio[$i]);
			if ($i + 1 < count($dominio))
				$this->_baseDN.=',';
		}

		$this->_baseDN = utf8_encode($this->_baseDN);
		$this->_puerto = $puerto;


		if (strstr($servidor, 'ldaps://') === false)
		{ // No es conexion segura
			$this->_conexion = ldap_connect($this->_servidor, $this->_puerto);
			// Opciones para Active Directory.
			ldap_set_option($this->_conexion, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($this->_conexion, LDAP_OPT_REFERRALS, 0);
		} else
		{
			// Es una conexión segura.
			$this->_conexion = ldap_connect($this->_servidor);
		}
	}

	/**
	 * Destructor de la clase ldap.
	 */
	public function __destruct()
	{
		ldap_close($this->_conexion);
	}

	/**
	 * 
	 * @param string $usuario
	 * @param string $password
	 * @return boolean
	 */
	public function autenticarUsuario($usuario=false, $password=false)
	{
		// Intentamos autenticarnos
		// Para autenticación anónima:
		// Para ver si hay conexión se puede mirar si hay conexión anónima.
		/*
		  $pruebas = @ldap_bind($this->_conexion);

		  if (!$pruebas)
		  {
		  die("Error en conexion con servidor LDAP o no se permiten conexiones anonimas.");
		  }
		 */

		// Para autenticación anónima:
		// $autenticacion=ldap_bind($this->_conexion);

		if ($usuario!=false && $password!=false)
		{
			$autenticacion = @ldap_bind($this->_conexion, "$usuario@$this->_dominio", $password);

			if ($autenticacion)
			{
				return true;
			}
		}
		else	// Autenticación anónima.
			$autenticacion = @ldap_bind($this->_conexion);

		return false;
	}

	/**
	 * Se encarga de obtener todos los miembros que forman parte de una OU.
	 * 
	 * @param string $unidadOrganizativa Ejemplo: "OU=Informatica,OU=Profes,OU=SC-Usuarios" El baseDN se añade automáticamente.
	 * @param string $campoOrdenacion Ejemplo: "displayname"
	 * @param array $camposMostrar  Ejemplo: array("cn", "displayname", "mail") Si no se pasa coge los campos por defecto.
	 * @return string JSON o 0 si no hay ningún miembro en el grupo.
	 */
	public function obtenerMiembrosOU($unidadOrganizativa, $campoOrdenacion, $camposMostrar = false)
	{

		if (!$camposMostrar)
		{
			$camposMostrar = $this->_camposMostrar;
		}

		$unidadOrganizativa.=',' . $this->_baseDN;

		$busqueda = @ldap_search($this->_conexion, $unidadOrganizativa, $this->_filtro, $camposMostrar);
		if ($busqueda)
		{

			// Ordenamos el array de resultados.
			ldap_sort($this->_conexion, $busqueda, $campoOrdenacion);
			$datos = '';
			$mijson = array();

			for ($entrada = ldap_first_entry($this->_conexion, $busqueda); $entrada != false; $entrada = ldap_next_entry($this->_conexion, $entrada))
			{

				foreach ($camposMostrar as $campo)
				{
					$atributos = @ldap_get_values($this->_conexion, $entrada, $campo);
					if ($atributos['count'] != 0)
					{
						$datos[$campo] = $atributos[0];
					}
				}

				// Si hay datos añadimos el array asociativo al array final.
				if ($datos != '')
				{
					// array_ma Se usa para evitar problemas al convertir a JSON las tildes y Ñ y demás.
					$mijson[] = array_map('convertir_UTF8', $datos);
				}
			}
			// Devuelve el objeto JSON con los datos solicitados.
			return json_encode($mijson);
		} else
		{
			return 0;
		}
	}

	/**
	 * 
	 * @param string $usuario Se escribe el CN del usuario. Ejemplo "veiga"
	 * @param string $unidadOrganizativa Ejemplo: "OU=Informatica,OU=Profes,OU=SC-Usuarios" BASEDN se añade automáticamente.
	 * @return boolean
	 */
	public function chequearPertenenciaOU($usuario, $unidadOrganizativa)
	{
		$filtro = "(cn=$usuario)";
		$unidadOrganizativa.=',' . $this->_baseDN;
		
		$busqueda = @ldap_search($this->_conexion, $unidadOrganizativa, $filtro, $this->_camposMostrar);
		if ($busqueda)
		{
			$datos = ldap_get_entries($this->_conexion, $busqueda);
			if ($datos['count'] == 0)
			{
				return false;
			} else
			{
				return true;
			}
		} else
		{
			return false;
		}
	}

	/**
	 * 
	 * @param string $usuario Se escribirá el CN del usuario. Ejemplo "veiga"
	 * @param string $unidadOrganizativa Ejemplo: "OU=Informatica,OU=Profes,OU=SC-Usuarios,DC=sanclemente,DC=local"
	 * @param array $camposMostrar  Ejemplo: array("cn", "displayname", "mail") Si no se pasa coge los campos por defecto.
	 * @return JSON o 0 si no lo encuentra.
	 */
	public function obtenerInfoUsuario($usuario, $camposMostrar = false)
	{

		if (!$camposMostrar)
		{
			$camposMostrar = $this->_camposMostrar;
		}
		$filtro = "(cn=$usuario)";
		$busqueda = @ldap_search($this->_conexion, $this->_baseDN, $filtro, $camposMostrar);

		if ($busqueda)
		{
			$datos = ldap_get_entries($this->_conexion, $busqueda);

			if ($datos['count'] == 0)
			{
				return 0; // No se encuentra ese usuario.
			} else
			{
				$entrada = ldap_first_entry($this->_conexion, $busqueda);
				$datos = '';
				foreach ($camposMostrar as $campo)
				{
					$atributos = @ldap_get_values($this->_conexion, $entrada, $campo);
					if ($atributos['count'] != 0)
					{
						$datos[$campo] = $atributos[0];
					}
				}

				if ($datos != '')
				{
					// array_map se usa para evitar problemas al convertir a JSON las tildes y Ñ y demás.
					$datos = array_map('convertir_UTF8', $datos);
				}

				// Devuelve el objeto JSON con los datos solicitados.
				return json_encode($datos);
			}
		}
	}

	/**
	 * Actualización de correo en el LDAP.
	 * Es necesario tener un permiso de escritura en el LDAP con un usuario específico.
	 * 
	 * @param string $dn Distinguised Name. Ejemplo: "CN=veiga,OU=Informatica,OU=Profes,OU=SC-Usuarios,DC=sanclemente,DC=local"
	 * @param string $email Nueva direción de correo electrónico.
	 */
	public function actualizarMail($dn, $email)
	{
		/*
		  $entry["objectclass"][0] = "device";
		  $entry["objectclass"][1] = "ipNetwork"; // add a structural objectclass
		  $entry["ipNetworkNumber"][0] = "1.2.3.4";
		 * 
		 *  @ldap_bind($this->_conexion, "$usuario@$this->_dominio", $password);
		 */
		$nuevosdatos['mail'] = $email;

		ldap_modify($this->_conexion, $dn, $nuevosdatos); // Los nuevos datos van en un array.
	}

	/**
	 * Se encarga de buscar en todo el LDAP usuarios que contengan en name, displayname o givenname el $textoBuscar 
	 * 
	 * @param string $textoBuscar Ejemplo: "lopez"
	 * @param string $campoOrdenacion Ejemplo: "displayname"
	 * @param array $camposMostrar  Ejemplo: array("cn", "displayname", "mail") Si no se pasa coge los campos por defecto.
	 * @return string JSON o 0 si no hay ningún miembro en el grupo.
	 */
	public function buscarUsuariosEnLdap($textoBuscar, $campoOrdenacion, $camposMostrar = false)
	{
		if (!$camposMostrar)
		{
			$camposMostrar = $this->_camposMostrar;
		}

		$unidadOrganizativa=$this->_baseDN;
		
		$filtroBusqueda= "(|(name=*$textoBuscar*)(displayname=*$textoBuscar*)(givenname=*$textoBuscar*))";

		$busqueda = @ldap_search($this->_conexion, $unidadOrganizativa, $filtroBusqueda, $camposMostrar);
		if ($busqueda)
		{

			// Ordenamos el array de resultados.
			ldap_sort($this->_conexion, $busqueda, $campoOrdenacion);
			$datos = '';
			$mijson = array();

			for ($entrada = ldap_first_entry($this->_conexion, $busqueda); $entrada != false; $entrada = ldap_next_entry($this->_conexion, $entrada))
			{

				foreach ($camposMostrar as $campo)
				{
					$atributos = @ldap_get_values($this->_conexion, $entrada, $campo);
					if ($atributos['count'] != 0)
					{
						$datos[$campo] = $atributos[0];
					}
				}

				// Si hay datos añadimos el array asociativo al array final.
				if ($datos != '')
				{
					// array_ma Se usa para evitar problemas al convertir a JSON las tildes y Ñ y demás.
					$mijson[] = array_map('convertir_UTF8', $datos);
				}
			}
			// Devuelve el objeto JSON con los datos solicitados.
			return json_encode($mijson);
		} else
		{
			return 0;
		}
	}
	
	/* Algunos campos LDAP.
	  objectClass
	  cn
	  sn
	  description
	  userPassword
	  givenName
	  givenName
	  distinguishedName
	  instanceType
	  whenCreated
	  whenChanged
	  displayName
	  uSNCreated
	  memberOf
	  uSNChanged
	  name
	  objectGUID
	  userAccountControl
	  codePage
	  countryCode
	  homeDirectory
	  homeDrive
	  scriptPath
	  pwdLastSet
	  primaryGroupID
	  objectSid
	  accountExpires
	  sAMAccountName
	  sAMAccountType
	  userPrincipalName
	  objectCategory
	  dSCorePropagationData
	 */
// Fin programación clase LDAP.
}

//////////////////////////////
// EJEMPLOS DE USO.
//////////////////////////////

/**

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8" />
	</head>
	<body>
<?php
$ldap = new ldap("10.0.4.1", "sanclemente.local");
if ($ldap->autenticarUsuario("alumno", "abc123.."))
{

	// $resultado = $ldap->obtenerMiembrosOU("OU=dawMP,OU=Alumnos,OU=SC-Usuarios", "displayname", array("cn", "displayname", "mail"));
	// $resultado = json_decode($resultado);

	$resultado = $ldap->buscarUsuariosEnLdap("veiga", "displayname", array("cn", "displayname", "mail"));
	$resultado = json_decode($resultado);
	
	echo "<pre>";
	print_r($resultado);
	echo "</pre>";

	if ($ldap->chequearPertenenciaOU("veiga", "OU=SC-Usuarios"))
	{
		echo "Pertenece a SC-Usuarios.<br/>";
	} else
	{
		echo "No Pertenece.<br/>";
	}

	$datos = $ldap->obtenerInfoUsuario("veiga");
	$datos = json_decode($datos);
	echo $datos->description;
}
?>
	</body>
</html>
**/
?>