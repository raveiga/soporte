<header id="header">
	<h1 id="logo"><a href="#">ss</a></h1>

	<!-- search -->
	<div class="search">
		<?php
		echo '<br/>';
		if (!empty($_SESSION['idusuario']))
		{ // Usuario conectado.
			echo "Bienvenido/a: <strong>{$_SESSION['apellidos']}, {$_SESSION['nombre']}</strong>.";
		}
		?>
	</div>
	<!-- end of search -->

	<div class="cl">&nbsp;</div>
</header>


<nav id="navigation">
	<?php
	$opcionActivada = 'class="active"';
	$paginaActiva = 'index';

	if (!empty($_GET['cargar']))
	{
		$paginaActiva = strtolower($_GET['cargar']);
	}

	if (!empty($_SESSION['idusuario']) && !empty($_SESSION['tipousuario']))
	{
		// Si el usuario no ha cubierto su nombre o su email(un técnico de nuevo acceso).
		// Se le redirecciona a su perfil (tener en cuenta posible bucle).
		if ($paginaActiva!='perfil' && (empty($_SESSION['nombre']) || empty($_SESSION['email'])))
			header("location:perfil.html");
		
		switch ($_SESSION['tipousuario'])
		{
			case 'a':
				?>
	<ul>
				<li <?php if ($paginaActiva == 'index') echo $opcionActivada; ?>><a href="index.html">Inicio</a></li>
				<li <?php if ($paginaActiva == 'crearticket') echo $opcionActivada; ?>><a href="crearticket.html">Comunicar incidencia</a></li>
				<li <?php if ($paginaActiva == 'tickets') echo $opcionActivada; ?>><a href="tickets.html">Tickets</a></li>
				<li <?php if ($paginaActiva == 'zonas') echo $opcionActivada; ?>><a href="zonas.html">Zonas</a></li>
				<li <?php if ($paginaActiva == 'equipos') echo $opcionActivada; ?>><a href="equipos.html">Equipos</a></li>
				<li <?php if ($paginaActiva == 'tecnicos') echo $opcionActivada; ?>><a href="tecnicos.html">Técnicos</a></li>
				<li <?php if ($paginaActiva == 'perfil') echo $opcionActivada; ?>><a href="perfil.html">Editar Perfil</a></li>
				<li <?php if ($paginaActiva == 'desconectar') echo $opcionActivada; ?>><a href="desconectar.html">Desconectar</a></li>
			</ul>
		<?php
			break;

		case 't':
			?>
			<ul>
				<li <?php if ($paginaActiva == 'index') echo $opcionActivada; ?>><a href="index.html">Inicio</a></li>
				<li <?php if ($paginaActiva == 'crearticket') echo $opcionActivada; ?>><a href="crearticket.html">Comunicar incidencia</a></li>
				<li <?php if ($paginaActiva == 'tickets') echo $opcionActivada; ?>><a href="tickets.html">Tickets</a></li>
				<li <?php if ($paginaActiva == 'zonas') echo $opcionActivada; ?>><a href="zonas.html">Zonas</a></li>
				<li <?php if ($paginaActiva == 'equipos') echo $opcionActivada; ?>><a href="equipos.html">Equipos</a></li>
				<li <?php if ($paginaActiva == 'tecnicos') echo $opcionActivada; ?>><a href="tecnicos.html">Técnicos</a></li>
				<li <?php if ($paginaActiva == 'perfil') echo $opcionActivada; ?>><a href="perfil.html">Editar Perfil</a></li>
				<li <?php if ($paginaActiva == 'desconectar') echo $opcionActivada; ?>><a href="desconectar.html">Desconectar</a></li>
			</ul>
			<?php
			break;

		case 'u':
			?>
			<ul>
				<li <?php if ($paginaActiva == 'index') echo $opcionActivada; ?>><a href="index.html">Inicio</a></li>
				<li <?php if ($paginaActiva == 'crearticket') echo $opcionActivada; ?>><a href="crearticket.html">Comunicar incidencia</a></li>
				<li <?php if ($paginaActiva == 'estado') echo $opcionActivada; ?>><a href="estado.html">Estado incidencia</a></li>
				<li <?php if ($paginaActiva == 'acceso') echo $opcionActivada; ?>><a href="acceso.html">Acceso al sistema</a></li>
				<li <?php if ($paginaActiva == 'informacion') echo $opcionActivada; ?>><a href="informacion.html">Información</a></li>
				<li <?php if ($paginaActiva == 'contactar') echo $opcionActivada; ?>><a href="contactar.html">Contactar</a></li>
			</ul>
			<?php
			break;
	}
}
else
{
	?>
	<ul>
		<li <?php if ($paginaActiva == 'index') echo $opcionActivada; ?>><a href="index.html">Inicio</a></li>
		<li <?php if ($paginaActiva == 'crearticket') echo $opcionActivada; ?>><a href="crearticket.html">Comunicar incidencia</a></li>
		<li <?php if ($paginaActiva == 'estado') echo $opcionActivada; ?>><a href="estado.html">Estado incidencia</a></li>
		<li <?php if ($paginaActiva == 'acceso') echo $opcionActivada; ?>><a href="acceso.html">Acceso al sistema</a></li>
		<li <?php if ($paginaActiva == 'informacion') echo $opcionActivada; ?>><a href="informacion.html">Información</a></li>
		<li <?php if ($paginaActiva == 'contactar') echo $opcionActivada; ?>><a href="contactar.html">Contactar</a></li>
	</ul>
	<?php
}
?>
<img id="preloader" src="img/preloader.gif"/>
<div class="cl">&nbsp;</div>
</nav>