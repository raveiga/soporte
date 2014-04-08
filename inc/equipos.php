<div class="main">
	<div class="featured">
		<h2>Listado de equipamiento <img id="nuevoequipo" src='img/round_plus_icon&16.png'/> filtrar <input type='text' name='filtro' id='filtro'/> </h2>
		<h5>Máquina Inicio (Ej. a21eq00): <input type='text' name='inicio' id='inicio'/> Número Máquinas: (Ej. 30) <input type='text' name='fin' id='fin'/> Situación: <span id='situaciondefecto'></span><br/>
			Descripción por defecto: <input type='text' name='descdefault' id='descdefault'/>
			<button id='generarmaquinas'>Generar máquinas</button></h5>
		<table border="1" id="milistado">
			<thead>
				<tr><th id="idmaquina">Id. Máquina</th><th id="ip">IP</th><th id="descripcion">Descripcion</th><th id="zona">Situación</th><th colspan=2>Edición</th></tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<script>
	$(document).ready(function()
	{
		// Desactivación de la caché en peticiones Ajax.
		$.ajaxSetup({cache: false});

		// Función para cargar el listado de equipos.
		function cargarListado(filtro)
		{
			$("#preloader").show();

			$("tbody").html('');

			if (typeof filtro !== 'undefined')
			{
				$("tbody").load("peticiones.php?op=14", {filtro: filtro}, function()
				{
					$("#preloader").hide();
				});
			}
			else
			{
				$("tbody").load("peticiones.php?op=14", function() {
					$("#preloader").hide();
				});
			}
		}

		// Cargamos el listado actual de equipos en la tabla.
		cargarListado();



		// Click en botón + para añadir equipos.
		$("#nuevoequipo").click(function()
		{
			$("#nuevoequipo").hide();

			// Creamos fila nueva con campos del formulario.
			$("<tr align='center'><td><input id='altas' type='text' autofocus /></td><td><input id='ip' type='text'/></td><td><input type='text'/></td><td></td><td><img src='img/doc_edit_icon&16.png'/></td><td><img src='img/delete_icon&16.png'/></td></tr>").insertAfter("thead");

			// Cargamos la cuarta celda.
			$("tr:eq(1) td:eq(3)").load("peticiones.php?op=18");

			// Programos el keyup sobre los input de la TABLA.
			$("#milistado input").keyup(function(evento)
			{
				if (evento.which == 13) // tecla ENTER
				{
					// Hacemos peticion ajax si hay id de máquina.
					if ($("#milistado input:eq(0)").val() != '')
					{	// petición ajax de inserción de máquina.
						$.post("peticiones.php?op=15", {idmaquina: $("#milistado input:eq(0)").val(), ip: $("#milistado input:eq(1)").val(), descrip: $("#milistado input:eq(2)").val(), zona: $("#milistado select").val()}, function(resultado)
						{
							if (resultado != 'error')
							{
								$("#nuevoequipo").show();

								// Recorremos los td y ponemos el value del input
								$("tr:eq(1) td").slice(0, 3).each(function()
								{
									$(this).html($("input", this).val());
								});

								// Sacamos el campo SELECT.
								$("tr:eq(1) td:eq(3)").html($("#zonas option:selected").text());
								// Asignamos el id de resultado a la fila.
								$("tr:eq(1)").attr("id", resultado);

							}
							$("#preloader").hide();
						});
					}
					else  // Si no le hemos puesto nombremaquina -> borrará la fila.
					{
						$("#nuevoequipo").show();
						$("tr:eq(1)").remove();
					}
				}	// if evento == 13
			}); // keyup..


		}); //#nuevoequipo.click










	});
</script>