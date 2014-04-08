<div class="main">
	<div class="featured">
		<form class="acceso">
			<fieldset><legend>Edición de datos del perfil</legend>
				<label for='usuario'>Usuario:</label>
				<input type='text' id='usuario' name='usuario' value='<?php echo $_SESSION['idusuario'] ?>' disabled />
				<label for='nombre'>Nombre: *</label>
				<input type='text' id='nombre' name='nombre' value='<?php echo $_SESSION['nombre'] ?>' autofocus required />
				<label for='apellidos'>Apellidos: *</label>
				<input type='text' id='apellidos' name='apellidos' value='<?php echo $_SESSION['apellidos'] ?>' required />
				<label for='email'>Email: *</label>
				<input type='email' id='email' name='email' value='<?php echo $_SESSION['email'] ?>' required />
				<label for='mensajeria'>Mensajeria Jabber:</label>
				<input type='text' id='mensajeria' name='mensajeria' value='<?php echo $_SESSION['mensajeria'] ?>'/>
				<label for='telefono'>Teléfono:</label>
				<input type='tel' id='telefono' name='telefono' value='<?php echo $_SESSION['telefono'] ?>'/>
				<label for='preferenciacomunicacion'>Preferencias Avisos:</label>
				<select name='preferenciacomunicacion' id='preferenciacomunicacion'><option value=''>Sin preferencias</option><option value='e'>por e-mail</option><option value='t'>por teléfono</option></select>
				<label for='rangonomolestar'>Rango no Molestar:</label>
				<input type='text' id='rangonomolestar' name='rangonomolestar' value='<?php echo $_SESSION['rangonomolestar'] ?>'/>
				<input type='submit' id='botonactualizar' value='Actualizar' /></fieldset>
		</form>
		<div id="mensajes"></div>
	</div>
</div>
<script>
	$(document).ready(function() {
		// Desactivación de la caché en peticiones Ajax.
		$.ajaxSetup({cache: false});

		// Seleccionamos en el campo preferenciascomunicacion y le ponemos su valor almacenado en la sesión.
		$("#preferenciacomunicacion option[value='<?php echo $_SESSION['preferenciacomunicacion'] ?>']").attr('selected', 'selected');

		$("form.acceso").submit(function(evento)
		{
			evento.preventDefault();
			if ($("#nombre").val() != '' && $("#apellidos").val() != '' && $("#email").val() != '')
			{
				$("#preloader").show();

				$.post("peticiones.php?op=3", {nombre: $("#nombre").val(), apellidos: $("#apellidos").val(), email: $("#email").val(), mensajeria: $("#mensajeria").val(), telefono: $("#telefono").val(), preferenciacomunicacion: $("#preferenciacomunicacion").val(), rangonomolestar: $("#rangonomolestar").val(), }, function(resultados)
				{
					if (resultados == 'ok') // Acceso correcto: redireccionamos al index.html.
					{
						$("#mensajes").html("Datos actualizados correctamente.").css("background", "green").fadeIn(300).delay(400).fadeOut(700, function() {
							$("#preloader").hide(function() {
								//document.location = "index.html";
							});
						});

					}
					else if (resultados == 'error')
					{
						$("#mensajes").html("Problema actualizando sus datos.").css("background", "red").fadeIn(300).delay(400).fadeOut(700);
						$("#preloader").hide();
					}
				}); //$.post
			}
		});
	});
</script>