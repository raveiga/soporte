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

		$("#situaciondefecto").load("peticiones.php?op=18", function()
		{
			$("#zonas").attr("id", "zonadefecto");
		});

		function cargarListado(filtro)
		{
			$("tbody").html('');
			
			if (typeof (filtro) !== 'undefined')
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

		// Cargamos el listado inicial de equipamiento sin filtros.
		cargarListado();

		// Al escribir en la casilla del filtro cubrimos la lista con las máquinas que contienen el texto del filtro.
		$("#filtro").keyup(function()
		{
			cargarListado($("#filtro").val());
		});

		// CLICK EN DIRECCION IP. Hace petición Ajax para averiguar la dirección IP.
		$("#milistado").on('focus', '#ip', function()
		{
			$.post("peticiones.php?op=19", {idmaquina: $("#milistado input:eq(0)").val().toLowerCase()}, function(resultado)
			{
				$("#milistado #ip").val(resultado);
			});
		});

		// CLICK en NUEVO EQUIPO.
		$("#nuevoequipo").click(function()
		{
			$("#nuevoequipo").hide();


			// Comprobamos si hay alguna otra fila en Edición.
			if ($("#milistado input#edicion").length != 0)
			{
				padre.html(contenidoOriginalFila);
			}

			// Creamos una fila nueva con campos de formulario.
			$("<tr align='center'><td><input id='altas' type='text' autofocus /></td><td><input id='ip' type='text'/></td><td><input type='text'/></td><td></td><td><img src='img/doc_edit_icon&16.png'/></td><td><img src='img/delete_icon&16.png'/></td></tr>").insertAfter("thead");

			// Cargamos en la cuarta celda la lista de zonas.
			$("tr:eq(1) td:eq(3)").load("peticiones.php?op=18");

			$("#milistado input").keyup(function(evento) {
				if (evento.which == 13)
				{
					// Hacemos la petición Ajax de actualización si el idmaquina!=''
					if ($("#milistado input:eq(0)").val() != '')
					{
						$("#preloader").show();

						$.post("peticiones.php?op=15", {idmaquina: $("#milistado input:eq(0)").val().toLowerCase(), ip: $("#milistado input:eq(1)").val(), descrip: $("#milistado input:eq(2)").val(), zona: $("#milistado select").val()}, function(respuesta) {
							{
								$("#nuevoquipo").show();
								// Recorremos las casillas y las convertimos en campos de texto.
								$("tr:eq(1) td").slice(0, 3).each(function()
								{
									$(this).html($("input", this).val());
								});

								$("tr:eq(1) td:eq(3)").html($("#zonas option:selected").text());


								$("tr:eq(1)").attr("id", respuesta);
							}

							$("#preloader").hide();
						});
					}
					else	// El campo clave está en blanco. Eliminamos la fila sin grabar.
					{
						$("#nuevoquipo").show();
						$("tr:eq(1)").remove();
					}
				}
			});

		});


		// CLICK EN BORRADO.
		$("#milistado").on("click", "img[src*='delete']", function()
		{
			padre = $(this).parent().parent();
			var codigoMaquina = padre.attr("id");

			if ($("#milistado input#edicion").length != 0)
			{
				padre.html(contenidoOriginalFila);
			}

			if (confirm("¿Desea borrar esta máquina?"))
			{
				$("#preloader").show();

				// Hacemos la petición Ajax de actualización.
				$.post("peticiones.php?op=16", {idmaquina: codigoMaquina}, function(respuesta) {
					if (respuesta == "ok") // Cando remate a solicitude con OK:
					{
						padre.css("background", "#029cdb").fadeOut("slow", function() {
							$(this).remove();
							$("#preloader").hide();
						});
					}
				});
			}
		});


		// CLICK EN EDICION
		$("#milistado").on("click", "img[src*='edit']", function()
		{
			// Comprobamos si hay alguna otra fila en Edición.
			if ($("#milistado input#edicion").length != 0)
			{
				padre.html(contenidoOriginalFila);
			}

			// Si está la fila de Altas activada, la eliminamos y activamos el botón de nueva.
			if ($("#milistado input#altas").length != 0)
			{
				// Eliminamos la fila 1 de edición.
				$("tr:eq(1)").remove();

				//Mostramos el botón oculto.
				$("#nuevoquipo").show();
			}


			// Almacenamos el id del padre y el codigo de maquina y el contenido original.
			padre = $(this).parent().parent();
			var codigoMaquina = padre.attr("id");
			contenidoOriginalFila = padre.html();

			// Recorremos las casillas y las convertimos en campos de texto.
			padre.find("td").slice(0, 3).each(function()
			{
				$(this).html("<input type='text' value='" + $(this).text() + "'/>");
			});

			// Ponemos a la primera celda la id='edicion'
			padre.find("input:eq(0)").attr("id", "edicion");

			// Ponemos a la segunda celda el atributo de id='ip'
			padre.find("input:eq(1)").attr("id", "ip");

			// Cargamos la lista de zonas.
			$.get("peticiones.php?op=18", function(resultado)
			{
				listazonas = resultado;

				// Vamos a colocar el desplegable en la celda situación.
				idzonacelda = padre.find("td:eq(3)").attr("id");
				padre.find("td:eq(3)").html(listazonas);
				padre.find("td:eq(3) option[value='" + idzonacelda + "']").attr("selected", "selected");
			});



			$("input").keyup(function(evento) {
				if (evento.which == 13)
				{
					if ($("#milistado input:eq(0)").val() != '')
					{
						$("#preloader").show();
						// Hacemos la petición Ajax de actualización.
						$.post("peticiones.php?op=17", {idmaquinaoriginal: codigoMaquina, idmaquina: $("#milistado input:eq(0)").val().toLowerCase(), ip: $("#milistado input:eq(1)").val(), descrip: $("#milistado input:eq(2)").val(), zona: $("#milistado select").val(), }, function(respuesta) {
							if (respuesta == 'ok')
							{
								// Ponemos el id de la fila al nuevo id si ha cambiado.
								padre.attr("id", $("#milistado input:eq(0)").val().toLowerCase());

								// Sacamos los campos de texto y lo dejamos como textos.
								// Recorremos las casillas y las convertimos en campos de texto.
								padre.find("td").slice(0, 3).each(function()
								{
									$(this).html($("input", this).val());
								});

								padre.find("td:eq(3)").attr("id", $("select#zonas option:selected").val());
								padre.find("td:eq(3)").html($("select#zonas option:selected").text());


								$("#preloader").hide();
							}
							else	// Error actualizando, clave duplicada.
							{
								padre.find("input:eq(0)").css("background", "red");
								$("#preloader").hide();
							}
						});
					}
				}
			});
		});


		$("#generarmaquinas").click(function()
		{
			if ($("#inicio").val() !== '' && $("#fin").val() !== '')
			{
				// Generamos peticiones desde la máquina inicial hasta la máquina final.
				patronEquipo = $("#inicio").val().substr(0, $("#inicio").val().length - 2).toLowerCase();
				numeroInicio = parseInt($("#inicio").val().substr($("#inicio").val().length - 2));
				numeroFin = parseInt($("#fin").val());

				$("#preloader").show();

				for (i = numeroInicio; i < numeroFin; i++)
				{
					if (i < 10)
						concatenar = '0';
					else
						concatenar = '';

					maquina = patronEquipo + concatenar + i;

					$.postq("nuevacola", "peticiones.php?op=20", {idmaquina: maquina, zona: $("#zonadefecto").val(), descrip: $("#descdefault").val()}, function(respuesta) {
					});
				}

				controlChequeo = setInterval(chequearAjaxPendientes, 500);
			}
		});


		function chequearAjaxPendientes()
		{
			if (!$.ajaxq.isRunning("nuevacola"))
			{
				$("#preloader").hide();
				document.location = 'equipos.html';
			}
		}

	});

</script>