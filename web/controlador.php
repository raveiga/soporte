<?php
// Inicializamos la carga de la aplicación.
require dirname(__DIR__) . '/lib/inicializar.php';
?>
<!DOCTYPE html>
<html lang="es">
	<?php
	require RUTA_INC . 'head.php';
	// Fichero central por defecto.
	$central = 'index.php';
	// Programamos lo que queremos cargar en los diferentes contenedores.
	if (isset($_GET['cargar']) && !empty($_GET['cargar'])) {
		// La parte central se cargará con $_GET['cargar']
		$central = strtolower($_GET['cargar']) . '.php';
	}
	?>
	<body>
		<!-- wrapper -->
		<div id="wrapper">
			<!-- shell -->
			<div class="shell">
				<!-- container -->
				<div class="container">
					<?php require RUTA_INC . 'menu.php'; ?>
					
			
						<?php
						if (file_exists(RUTA_INC . $central))
							require RUTA_INC . $central;
						else
							require RUTA_INC . 'index.php';
						?>
			
					
						<?php require RUTA_INC . 'pie.php'; ?>
				</div>
				<!-- end of container -->
			</div>
			<!-- end of shell -->
		</div>
		<!-- end of wrapper -->
	</body>
</html>