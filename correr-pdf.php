<?php
/*
Plugin Name: Mi Plugin PDF
Description: Plugin de prueba para generación de PDFs.
Version: 1.0
Author: Alexis
*/

require_once plugin_dir_path(__FILE__) . 'services/createPdf.php';

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_mi_hola_mundo', 'mi_hola_mundo');
add_action('wp_ajax_nopriv_mi_hola_mundo', 'mi_hola_mundo');

function mi_hola_mundo()
{
    wp_send_json_success([
        'mensaje' => 'Hola Mundo desde WordPress AJAX .........'
    ]);
}


//-------------------------------------

add_action('wp_ajax_make_pdf', 'make_pdf');
add_action('wp_ajax_nopriv_make_pdf', 'make_pdf');
function make_pdf()
{

    // Establecer la zona horaria (ejemplo para Ecuador)
    date_default_timezone_set('America/Guayaquil');

    $ruta_plugin = plugin_dir_path(__FILE__) . 'mis_archivosPDF';

    if (!file_exists($ruta_plugin)) {
        wp_mkdir_p($ruta_plugin);
    }

    $required = [
        'firstName',
        'lastName',
        'email',
        'cedula',
        'borth',
    ];

    foreach ($required as $r) {
        if (!isset($_POST[$r]) || empty($_POST[$r])) {
            wp_send_json([
                "status" => "error",
                "msg" => "El campo {$r} es obligatorio"
            ], 400);
        }
    }

    $data = [
        'firstname' => sanitize_text_field($_POST['firstName']),
        'lastName' => sanitize_text_field($_POST['lastName']),
        'email' => sanitize_email($_POST['email']),
        'cedula' => sanitize_text_field($_POST['cedula']),
        'borth' => sanitize_text_field($_POST['borth']),
    ];

    $fecha_nom = date('Y-m-d_H-i-s'); // Formato seguro para archivos
    // $contenido_texto = "FECHA: " . date('Y-m-d H:i:s') . PHP_EOL;

    if ($_POST['tipo_documento'] === 'ingreso') {
        $log_file = $ruta_plugin . '/' . $_POST['tipo_documento'] . '-' . $_POST['cedula'] . '-' . $fecha_nom . '.txt';

    } else if ($_POST['tipo_documento'] === 'despido') {
        $log_file = $ruta_plugin . '/' . $_POST['tipo_documento'] . '-' . $_POST['cedula'] . '-' . $fecha_nom . '.txt';

    } else {
        $log_file = $ruta_plugin . '/' . 'cita_medica' . '-' . $_POST['cedula'] . '-' . $fecha_nom . '.txt';
    }

    // Guardar en modo append (no sobreescribe)
    file_put_contents($log_file, $data, FILE_APPEND);

    $response = createPdf::doPdf($data);

    wp_send_json($response);


}

add_action('wp_ajax_guardar_txt_postman', 'atender_peticion_postman');
add_action('wp_ajax_nopriv_guardar_txt_postman', 'atender_peticion_postman');

function atender_peticion_postman()
{
    // 1. Validar que vengan los datos necesarios
    if (!isset($_POST['nombre_archivo']) || !isset($_POST['contenido'])) {
        wp_send_json_error('Faltan parámetros: nombre_archivo o contenido');
    }

    // 2. Limpiar los datos recibidos
    $nombre = sanitize_file_name($_POST['nombre_archivo']);
    $texto = $_POST['contenido']; // Aquí puedes usar sanitize_textarea_field si es solo texto plano

    // 3. Configurar la ruta de la carpeta
    // Usamos wp_upload_dir() para evitar problemas de permisos de escritura
    $upload_dir = wp_upload_dir();
    $carpeta_destino = $upload_dir['basedir'] . '/mis-archivos-custom';

    // 4. Crear la carpeta si no existe (con permisos correctos)
    if (!file_exists($carpeta_destino)) {
        wp_mkdir_p($carpeta_destino);
    }

    $ruta_final = $carpeta_destino . '/' . $nombre . '.txt';

    // 5. Escribir el archivo en el servidor
    // FILE_APPEND por si quieres escribir sobre el mismo archivo sin borrar lo anterior
    $resultado = file_put_contents($ruta_final, $texto . PHP_EOL, FILE_APPEND);

    if ($resultado !== false) {
        wp_send_json_success([
            'mensaje' => 'Archivo guardado correctamente',
            'ruta_servidor' => $ruta_final,
            'url_publica' => $upload_dir['baseurl'] . '/mis-archivos-custom/' . $nombre . '.txt'
        ]);
    } else {
        wp_send_json_error('No se pudo escribir el archivo. Verifica permisos de carpeta.');
    }

    // Siempre terminar con wp_die() en funciones AJAX de WP
    wp_die();
}

//ANALIZAR ESTOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
// Registro de hooks para que WordPress reconozca la petición de Postman
add_action('wp_ajax_guardar_en_plugin', 'guardar_archivo_en_mi_plugin');
add_action('wp_ajax_nopriv_guardar_en_plugin', 'guardar_archivo_en_mi_plugin');

function guardar_archivo_en_mi_plugin()
{
    // 1. Recoger datos de Postman
    $nombre_archivo = isset($_POST['nombre']) ? sanitize_file_name($_POST['nombre']) : 'archivo_default';
    $contenido = isset($_POST['texto']) ? $_POST['texto'] : 'Sin contenido';

    // 2. Definir la ruta HACIA ADENTRO de tu plugin
    // __FILE__ se refiere al archivo actual donde estás pegando este código
    $ruta_plugin = plugin_dir_path(__FILE__) . 'mis_archivos';

    // 3. Crear la carpeta si no existe dentro del plugin
    if (!file_exists($ruta_plugin)) {
        wp_mkdir_p($ruta_plugin);
    }

    $ruta_final = $ruta_plugin . '/' . $nombre_archivo . '.txt';

    // 4. Intentar guardar el archivo
    $resultado = file_put_contents($ruta_final, $contenido);

    // 5. Respuesta para Postman
    if ($resultado !== false) {
        wp_send_json_success([
            'mensaje' => 'Archivo guardado dentro de la carpeta del plugin',
            'ruta_absoluta' => $ruta_final
        ]);
    } else {
        // Si entra aquí, es 99% probable que sea por permisos de escritura del servidor
        wp_send_json_error('Error: El servidor no tiene permiso para escribir en la carpeta del plugin.');
    }

    wp_die();
}
