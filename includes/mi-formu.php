<?php 
add_shortcode('generador_documentos', 'mostrar_interfaz_pdf');

function mostrar_interfaz_pdf() {
    // Iniciamos el guardado en memoria para no romper el diseño del sitio
    ob_start(); 
    ?>
    <div class="contenedor-plugin">
        <h2>Generador de Documentos Oficiales</h2>
        
        <div class="botones-tipo">
            <button type="button" class="btn-tipo" data-tipo="ingreso">📝 Formulario Ingreso</button>
            <button type="button" class="btn-tipo" data-tipo="despido">⚠️ Formulario Despido</button>
            <button type="button" class="btn-tipo" data-tipo="cita_medica">🏥 Cita Médica</button>
        </div>

        <form id="formGeneradorPDF" style="display:none; margin-top: 20px; border: 1px solid #ddd; padding: 20px;">
            <h3 id="titulo-form"></h3>
            <input type="hidden" id="tipo_documento" name="tipo_documento">
            
            <input type="text" id="firstName" placeholder="Nombre" required><br>
            <input type="text" id="lastName" placeholder="Apellido" required><br>
            <input type="email" id="email" placeholder="Correo Electrónico" required><br>
            <input type="text" id="cedula" placeholder="Número de Cédula" required><br>
            <input type="date" id="borth" required><br><br>
            
            <button type="submit" id="btnEnviar">Generar y Ver PDF</button>
        </form>

        <div id="area-visor" style="display:none; margin-top: 30px;">
            <h4>Vista Previa del Documento:</h4>
            <iframe id="iframe-pdf" src="" width="100%" height="600px"></iframe>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Al hacer clic en los botones superiores
        $('.btn-tipo').on('click', function() {
            var tipo = $(this).data('tipo');
            $('#tipo_documento').val(tipo);
            $('#titulo-form').text('Completar datos para: ' + tipo.toUpperCase());
            $('#formGeneradorPDF').fadeIn();
            $('#area-visor').hide(); // Ocultar visor previo
        });

        // Al enviar el formulario
        $('#formGeneradorPDF').on('submit', function(e) {
            e.preventDefault();
            
            var btn = $('#btnEnviar');
            btn.prop('disabled', true).text('Procesando...');

            var datos = {
                action: 'make_pdf', // LLAMA A TU BACKEND
                firstName: $('#firstName').val(),
                lastName: $('#lastName').val(),
                email: $('#email').val(),
                cedula: $('#cedula').val(),
                borth: $('#borth').val(),
                tipo_documento: $('#tipo_documento').val()
            };

            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: datos,
                success: function(response) {
                    if (response.url_pdf) {
                        $('#iframe-pdf').attr('src', response.url_pdf);
                        $('#area-visor').slideDown();
                    } else {
                        alert("Error: " + response);
                    }
                    btn.prop('disabled', false).text('Generar y Ver PDF');
                },
                error: function() {
                    alert("Error en la conexión con el servidor");
                    btn.prop('disabled', false).text('Generar y Ver PDF');
                }
            });
        });
    });
    </script>
    <?php
    return ob_get_clean();
}