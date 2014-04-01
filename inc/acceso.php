<div class="main">
	<div class="featured">
		<form id='formulario1' class="acceso">
			<fieldset>
				<legend>Autenticación de Usuarios</legend>
				<label for="usuario">Usuario: *</label>
				<input type="text" id="usuario" placeholder="...en <?php echo LDAP_DOMINIO; ?>" required/>
				<label for="password">Contraseña: *</label>
				<input type="password" id="password" required/>
				<input type="submit" id="acceder" value="Acceder" />
			</fieldset>
		</form>
		<div id="mensajes"></div>
	</div>
</div>
<script>
	$(document).ready(function() {
		// Desactivación de la caché en peticiones Ajax.
		$.ajaxSetup({cache: false});
		$("#formulario1").submit(function(evento)
		{
			evento.preventDefault();

			if ($("#usuario").val() != '' && $("#password").val() != '')
			{
				$("#preloader").show();
				$.post("peticiones.php?op=1", {usuario: $("#usuario").val(), password: $("#password").val()}, function(resultados)
				{
					if (resultados == 'ok') // Acceso correcto: redireccionamos al index.html.
					{
						$("#preloader").hide(function() {
							document.location = "index.html";
						});
					}
					else if (resultados == 'error')
					{
						$("#mensajes").html("Datos de acceso incorrectos").css("background", "red").fadeIn(300).delay(400).fadeOut(700);
						$("#preloader").hide();
					}

					else // Recibimos el JSON con datos a actualizar. 
					{
						resultados = $.parseJSON(resultados);
						// Fadeout del formulario actual.
						$("#formulario1").fadeOut(1000, function() {
							$(this).remove();
							$("div.featured").append($("<form id='formulario2' class='acceso'><fieldset><legend>Confirmación de registro de Usuario</legend><label for='usuario'>Usuario:</label><input type='text' id='usuario' name='usuario' value='" + resultados.cn.toLowerCase() + "' disabled /><label for='nombre'>Nombre: *</label><input type='text' id='nombre' name='nombre' value='" + resultados.name + "' autofocus required /><label for='apellidos'>Apellidos: *</label><input type='text' id='apellidos' name='apellidos' value='" + resultados.displayname + "' required /><label for='email'>Email: *</label><input type='email' id='email' name='email' value='" + resultados.mail + "' required /><label for='mensajeria'>Mensajeria Jabber:</label><input type='text' id='mensajeria' name='mensajeria' value=''/><label for='telefono'>Teléfono:</label><input type='tel' id='telefono' name='telefono' value=''/><label for='preferenciacomunicacion'>Preferencias Avisos:</label><select name='preferenciacomunicacion' id='preferenciacomunicacion'><option value=''>Sin preferencias</option><option value='e'>por e-mail</option><option value='t'>por teléfono</option></select><label for='rangonomolestar'>Rango No Molestar:</label><input type='text' id='rangonomolestar' name='rangonomolestar' value=''/><input type='submit' id='botonregistro' value='Registrarse' /></fieldset></form>")).fadeIn();

							$("#preloader").hide();

							// Programamos el botón del formulario de confirmar registro.


							$("#formulario2").submit(function(evento)
							{
								evento.preventDefault();

								$("#preloader").show();

								$.post("peticiones.php?op=2", {nombre: $("#nombre").val(), apellidos: $("#apellidos").val(), email: $("#email").val(), mensajeria: $("#mensajeria").val(), telefono: $("#telefono").val(), preferenciacomunicacion: $("#preferenciacomunicacion").val(), rangonomolestar: $("#rangonomolestar").val()}, function(resultados)
								{
									if (resultados == 'ok')
									{
										$("#preloader").hide();
										// Si todo ha ido 'ok' redireccionamos al index.html
										$("fieldset").fadeOut(1000, function() {
											document.location = "index.html";
										});
									}
									else
									{
										$("#mensajes").html("Error: registro duplicado en tabla.").css("background", "red").fadeIn(300).delay(400).fadeOut(700);
										$("#preloader").hide();
									}
								});
							}); // Click en #botonregistros.

						});
					} // else
				}); //$.post
			}
		});
	});
</script>