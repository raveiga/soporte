<?php
// Bases de datos.
define('BD_SERVIDOR', $_SERVER['HTTP_HOST']);
define('BD_NOMBRE', 'c2base2');
define('BD_USUARIO', 'c2mysql');
define('BD_PASSWORD', 'abc123.');
define('BD_PREFIJO_TABLAS', 'soporte_');

// Datos LDAP.
define('LDAP_SERVIDOR','10.0.4.1');
define('LDAP_DOMINIO','sanclemente.local');

// Rutas del Sistema
define('RAIZ', dirname(__DIR__) . DIRECTORY_SEPARATOR );	//	/var/www/clients/client2/web2/web/soporte/
define('RUTA_LIB', RAIZ . 'lib'. DIRECTORY_SEPARATOR);		//....soporte/lib/
define('RUTA_CLASES', RUTA_LIB . 'clases'. DIRECTORY_SEPARATOR);	//....soporte/lib/clases/
define('RUTA_INC', RAIZ . 'inc'. DIRECTORY_SEPARATOR);		//....soporte/lib/clases/

// Constante de administrador del sistema (usuarios del LDAP).
define('ADMINS','veiga administrador admin');	// Separados por un espacio.
?>