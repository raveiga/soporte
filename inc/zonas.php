<div class="main">
	<div class="featured">
		<h2>Listado de zonas a gestionar <img id="nuevazona" src='img/round_plus_icon&16.png'/> </h2>
		<table border="1" id="milistado">
			<thead>
				<tr><th id="nombrezona">Nombre de zona</th><th id="descripcion">Descripción</th><th colspan=2>Edición</th></tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<script>
	$(document).ready(function()
	{
		// Desactivamos cache Ajax.
		$.ajaxSetup({cache: false});

		// Cargamos listado de peticiones.
		$("tbody").load("peticiones.php?op=4");

		// Click sobre el icono +
		$("#nuevazona").click(function()
		{
			// Ocultamos el botón de click.
			$("#nuevazona").hide();

			// Creamos una fila nueva para añadir al comienzo de la tabla.
			$("<tr align='center'><td><input type='text' autofocus/></td><td><input type='text' id='altas'/></td><td><img src='img/doc_edit_icon&16.png'/></td><td><img src='img/delete_icon&16.png'/></td></tr>").insertAfter("thead");

			// Programamos el ENTER en los campos INPUT
			$("input").keyup(function(evento)
			{
				if (evento.which == 13) // Pulsada la tecla ENTER.
				{
					// Hacemos petición ajax....
					// Si hay datos en nombrezona..
					if ($("input:eq(0)").val() != '')
					{
						// Activamos el spinner de Ajax.
						$("#preloader").show();

						// Hacemos la petición Ajax.
						$.post("peticiones.php?op=5", {nombrezona: $("input:eq(0)").val(), descripcion: $("input:eq(1)").val()}, function(respuesta)
						{
							// Recorremos la fila para sustituir los input por sus valores.
							$("tr:eq(1) td").slice(0, 2).each(function()
							{
								$(this).html($("input", this).val());
							});
							// Le asignamos a la fila el id q nos devolvió la petición Ajax
							$("tr:eq(1)").attr("id", respuesta);

							// Quitamos el spinner
							$("#preloader").hide();

							// MOstramos el icono de +
							$("#nuevazona").show();
						});

					}
					else	// El campo nombrezona está en blanco. No grabamos nada y cerramos.
					{
						$("#nuevazona").show();
						$("tr:eq(1)").remove();
					}
				}
			});  // Click en +
		});


		// Hacemos click en imagenes de borrado.
		$("#milistado").on("click", "img[src*='delete']", function()
		{

			// Vamos a recuperar el id de la fila a borrar.
			filapadre = $(this).parent().parent();
			idzona = filapadre.attr("id");

			// Preguntamos si quiere borrar la zona
			if (confirm("¿Desea borrar esta zona?"))
			{
				// Activamos spinner
				$("#preloader").show();

				// hacemos petición ajax
				$.post("peticiones.php?op=6", {idzona: idzona}, function(respuesta)
				{
					if (respuesta == 'ok')
					{
						filapadre.css("background", "#029cdb").fadeOut("slow", function()
						{
							$(this).remove();
							$("#preloader").hide();
						});
					}
				});
			}
		});


// Hacemos click en imagenes de edición.
		$("#milistado").on("click", "img[src*='edit']", function()
		{
			// Comprobamos si hay alguna fila en edición.
			if ($("input#edicion").length!=0)
				{
					filapadre.html(contenidoOriginalFila);
				}
		
			// Vamos a recuperar el id de la fila a editar.
			filapadre = $(this).parent().parent();
			idzona = filapadre.attr("id");
			contenidoOriginalFila=filapadre.html();

			//Recorremos las celdas y sustituimos por sus input.
			filapadre.find("td").slice(0, 2).each(function()
			{
				$(this).html("<input id='edicion' type='text' value='" + $(this).text() + "'/>");
			});

			// Al pulsar ENTER petición Ajax.
			$("input").keyup(function(evento)
			{
				if (evento.which == 13)
				{
					if ($("input:eq(0)").val() != '')
					{	// Ajax para grabar.
						$("#preloader").show();

						// Petición ajax de edición OP=7
						$.post("peticiones.php?op=7", {nombrezona: $("input:eq(0)").val(), descripcion: $("input:eq(1)").val(), idzona: idzona}, function(resultado) {
							if (resultado == 'ok')
							{
								// Sacamos los input..
								filapadre.find("td").slice(0, 2).each(function()
								{
									$(this).html($("input",this).val());
								});

								// Ocultamos el spinner
								$("#preloader").hide();
							}

						});


					}




				}
			});





		});



	});
</script>