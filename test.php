<?php
if (!empty($_POST['usuario']))
{
	// Autenticación contra el LDAP
	// Nos conectamos.
	$conexion=ldap_connect("10.0.4.1") or die("Error conectando al LDAP:".ldap_error());
	
	// Intentamos autenticarnos
	$resultado=@ldap_bind($conexion,"{$_POST['usuario']}@sanclemente.local",$_POST['password']);
	if ($resultado)
	{
		echo "Acceso correcto al sistema";
		
		// Hacemos una búsqueda en el LDAP
		$usuario='mar';
		$dondeBuscar='OU=Alumnos,OU=SC-Usuarios,DC=sanclemente,DC=local';
		$filtroBusqueda="(|(name=$usuario*)(displayname=$usuario*))";
		$camposMostrar=array("mail","displayName","cn","ou","lastLogon","whenCreated","description");
		
		$busqueda=ldap_search($conexion,$dondeBuscar,$filtroBusqueda,$camposMostrar);
		
		// Para ordenar los resultados:
		ldap_sort($conexion,$busqueda,"displayName");
		
		// Leemos la primera entrada de los resultados.
		$entrada=ldap_first_entry($conexion,$busqueda);
		
		$datos=ldap_get_values($conexion,$entrada,"displayName");
		
		echo "-------";
		print_r($datos);
		// Recorremos los resultados de la búsqueda.
		//for ($fila=ldap_first_entry($conexion,))
		
		//$datos=ldap_get_entries($conexion, $busqueda);
		
		echo "<pre>";
		//print_r($datos);
		echo "</pre>";
		
		// 
		
		
		
		// Si queremos mostrar los atributos que contiene esa entrada, haríamos ldap_get_attributes:
		//$atributos=ldap_get_attributes($conexion,$entrada);
		
		echo "Listado de atributos:<br/>";
		//print_r($atributos);
		
	}
	else
		echo "Datos de acceso incorrectos.";
	
	
}
else
{ // Mostramos el formulario.
?>
<form name="formulario" method="post" action="">
	Usuario: <input type="text" name="usuario" id="usuario"/>
	<br/>Password: <input type="password" name="password" id="password" /><br/>
	<input type="submit" value="Acceder" />
	<input type="reset" value="limpiar"/>	
</form>
<?php
}
?>