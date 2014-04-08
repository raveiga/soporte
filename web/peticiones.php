<?php

// Inicializamos la carga de la aplicación.
require dirname(__DIR__) . '/lib/inicializar.php';
$mibase = BaseDatos::getInstancia();


switch ($_GET['op'])
{
	case 1:  // Comprobacion acceso usuario.
		$ldap = new ldap(LDAP_SERVIDOR, LDAP_DOMINIO);
		if ($ldap->autenticarUsuario($_POST['usuario'], $_POST['password']))
		{
// Almacenamos el JSON con la información del usuario.
			$datosldap = $ldap->obtenerInfoUsuario($_POST['usuario']);
			$objetodatos = json_decode($datosldap);

// Comprobamos si existe en la tabla
			$sql = sprintf("select * from %susuarios where idusuario='%s'", BD_PREFIJO_TABLAS, strtolower($mibase->depurarCampo($_POST['usuario'])));

// Ejecutamos la SQL devuelve un array con todos los registros.
			$resultado = json_decode($mibase->ejecutarSQL($sql));

			if (empty($resultado))
			{ // Usuario no existe en tabla usuarios.
// Devolvemos el JSON del LDAP con los datos para cubrir el formulario de edicion.
				echo $datosldap;

// Creamos una variable de sesión con el ID del usuario para utilizar luego en op=2.
				$_SESSION['usuariofaseregistro'] = strtolower($objetodatos->cn);
			} else
			{ // Existe en la tabla.
// Creamos variable de sesión de usuario conectado con los datos de la tabla.
				$_SESSION['idusuario'] = $resultado[0]->idusuario;
				$_SESSION['nombre'] = $resultado[0]->nombre;
				$_SESSION['apellidos'] = $resultado[0]->apellidos;
				$_SESSION['email'] = $resultado[0]->email;
				$_SESSION['mensajeria'] = $resultado[0]->mensajeria;
				$_SESSION['telefono'] = $resultado[0]->telefono;
				$_SESSION['preferenciacomunicacion'] = $resultado[0]->preferenciacomunicacion;
				$_SESSION['rangonomolestar'] = $resultado[0]->rangonomolestar;
				$_SESSION['tipousuario'] = $resultado[0]->tipousuario;

				echo 'ok';
			}
		}
		else
			echo "error";
		break;

	case 2: // Inserción de datos de usuario en confirmación de registro.
// Empleamos la variable de $_SESSION['usuariofaseregistro'] para insertar sus datos.
		// Comprobamos si es un admin para crear variable de sesión con nivel 1.
		$admins = explode(' ', ADMINS);

		if (in_array($_SESSION['usuariofaseregistro'], $admins)) // Es un usuario administrador, se crea una variable de sesión de admin nivel 1.
			$tipousuario = 'a';
		else
			$tipousuario = 'u';

		$sql = sprintf("insert into %susuarios(idusuario,nombre,apellidos,email,mensajeria,telefono,preferenciacomunicacion,rangonomolestar,tipousuario) values('%s','%s','%s','%s','%s','%s','%s','%s','%s')", BD_PREFIJO_TABLAS, $_SESSION['usuariofaseregistro'], $mibase->depurarCampo($_POST['nombre']), $mibase->depurarCampo($_POST['apellidos']), $mibase->depurarCampo($_POST['email']), $mibase->depurarCampo($_POST['mensajeria']), $mibase->depurarCampo($_POST['telefono']), $mibase->depurarCampo($_POST['preferenciacomunicacion']), $mibase->depurarCampo($_POST['rangonomolestar']), $tipousuario);

		if ($mibase->ejecutarSQL($sql) == 'ok')
		{
// Creamos variable de sesión de usuario conectado con los datos de la tabla.
			$_SESSION['idusuario'] = $_SESSION['usuariofaseregistro'];
			$_SESSION['nombre'] = $mibase->depurarCampo($_POST['nombre']);
			$_SESSION['apellidos'] = $mibase->depurarCampo($_POST['apellidos']);
			$_SESSION['email'] = $mibase->depurarCampo($_POST['email']);
			$_SESSION['mensajeria'] = $mibase->depurarCampo($_POST['mensajeria']);
			$_SESSION['telefono'] = $mibase->depurarCampo($_POST['telefono']);
			$_SESSION['preferenciacomunicacion'] = $mibase->depurarCampo($_POST['preferenciacomunicacion']);
			$_SESSION['rangonomolestar'] = $mibase->depurarCampo($_POST['rangonomolestar']);
			$_SESSION['tipousuario'] = $tipousuario;

			echo 'ok';
		}
		else
			echo 'error';

		break;


	case 3: // Edición de datos de usuario.
// Empleamos la variable de $_SESSION['usuariofaseregistro'] para insertar sus datos.
		$sql = sprintf("update %susuarios set nombre='%s',apellidos='%s',email='%s',mensajeria='%s',telefono='%s',preferenciacomunicacion='%s',rangonomolestar='%s' where idusuario='%s'", BD_PREFIJO_TABLAS, $mibase->depurarCampo($_POST['nombre']), $mibase->depurarCampo($_POST['apellidos']), $mibase->depurarCampo($_POST['email']), $mibase->depurarCampo($_POST['mensajeria']), $mibase->depurarCampo($_POST['telefono']), $mibase->depurarCampo($_POST['preferenciacomunicacion']), $mibase->depurarCampo($_POST['rangonomolestar']), $_SESSION['idusuario']);

		if ($mibase->ejecutarSQL($sql) == 'ok')
		{
			$_SESSION['nombre'] = $mibase->depurarCampo($_POST['nombre']);
			$_SESSION['apellidos'] = $mibase->depurarCampo($_POST['apellidos']);
			$_SESSION['email'] = $mibase->depurarCampo($_POST['email']);
			$_SESSION['mensajeria'] = $mibase->depurarCampo($_POST['mensajeria']);
			$_SESSION['telefono'] = $mibase->depurarCampo($_POST['telefono']);
			$_SESSION['preferenciacomunicacion'] = $mibase->depurarCampo($_POST['preferenciacomunicacion']);
			$_SESSION['rangonomolestar'] = $mibase->depurarCampo($_POST['rangonomolestar']);
			echo 'ok';
		}
		else
			echo 'error';

		break;

	
	case 4: // Carga de zonas de control.
		$sql=sprintf("select * from %szonas order by nombrezona",BD_PREFIJO_TABLAS);
		
		$resultado=json_decode($mibase->ejecutarSQL($sql));
		if (!empty($resultado))
		{
			for ($i=0;$i<count($resultado);$i++)
			{
				echo "<tr align='center' id='".$resultado[$i]->idzona."'><td>".$resultado[$i]->nombrezona."</td><td>".$resultado[$i]->descripcion."</td><td><img src='img/doc_edit_icon&16.png'/></td><td><img src='img/delete_icon&16.png'/></td></tr>";
			}
		}
		else
		{
			echo "";
		}
		
		break;

	case 5: // Insertar zona en base de datos.
		$sql=sprintf("insert into %szonas(nombrezona,descripcion) values('%s','%s')",BD_PREFIJO_TABLAS,$mibase->depurarCampo($_POST['nombrezona']),$mibase->depurarCampo($_POST['descripcion']));
		
		if ($mibase->ejecutarSQL($sql) =='ok')
		{
			$sql=sprintf("select max(idzona) as maximo from %szonas",BD_PREFIJO_TABLAS);
			$resultado=json_decode($mibase->ejecutarSQL($sql));
			echo $resultado[0]->maximo;
		}
		break;
		
	case 6: //Borrado de una zona
		$sql=sprintf("delete from %szonas where idzona='%s'",BD_PREFIJO_TABLAS,$mibase->depurarCampo($_POST['idzona']));
		echo $mibase->ejecutarSQL($sql);
		
		break;
}
?>