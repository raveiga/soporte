<header id="header">
	<h1 id="logo"><a href="#">ss</a></h1>

	<!-- search -->
	<div class="search">
		<form action="" method="post">
			<input type="text" class="field" value="Palabras Clave ..." title="palabras clave ..." />
			<input type="submit" class="search-btn" value="" />
			<div class="cl">&nbsp;</div>
		</form>
	</div>
	<!-- end of search -->

	<div class="cl">&nbsp;</div>
</header>


<nav id="navigation">
	<?php
	$activarOpcionMenu = 'class="active"';
	$paginaActiva = 'index';

	if (!empty($_GET['cargar'])) {
		$paginaActiva = strtolower($_GET['cargar']);
	}
	?>
	<ul>
		<li <?php if ($paginaActiva == 'index') echo $activarOpcionMenu; ?>><a href="index.html">Inicio</a></li>
		<li <?php if ($paginaActiva == 'ticket') echo $activarOpcionMenu; ?>><a href="ticket.html">Comunicar incidencia</a></li>
		<li <?php if ($paginaActiva == 'estado') echo $activarOpcionMenu; ?>><a href="estado.html">Estado incidencia</a></li>
		<li <?php if ($paginaActiva == 'tecnicos') echo $activarOpcionMenu; ?>><a href="tecnicos.html">Acceso a Técnicos</a></li>
		<li <?php if ($paginaActiva == 'informacion') echo $activarOpcionMenu; ?>><a href="informacion.html">Información</a></li>
		<li <?php if ($paginaActiva == 'contactar') echo $activarOpcionMenu; ?>><a href="contactar.html">Contactar</a></li>
	</ul>
	<div class="cl">&nbsp;</div>
</nav>