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

    // Obtener carpeta uploads
    $upload_dir = wp_upload_dir();

    // Crear subcarpeta logs
    $log_dir = $upload_dir['basedir'] . '/miTxt';

    if (!file_exists($log_dir)) {
        wp_mkdir_p($log_dir);
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
        'firstname' => sanitize_text_field($_POST['firstName']. PHP_EOL),
        'lastName' => sanitize_text_field($_POST['lastName']. PHP_EOL),
        'email' => sanitize_email($_POST['email']. PHP_EOL),
        'cedula' => sanitize_text_field($_POST['cedula']. PHP_EOL),
        'borth' => sanitize_text_field($_POST['borth']. PHP_EOL),

    ];

    if ($_POST['tipo_documento'] === 'ingreso') {

        // Ruta final del archivo
        $log_file = $log_dir . '/' . $_POST['tipo_documento'] . '.txt';

    } else if ($_POST['tipo_documento'] === 'despido') {
        $log_file = $log_dir . '/' . $_POST['tipo_documento'] . '.txt';

    } else {
        $log_file = $log_dir . '/' . 'cita_medica' . '.txt';

    }

    // Guardar en modo append (no sobreescribe)
    file_put_contents($log_file, $data, FILE_APPEND);

    $response = createPdf::doPdf($data);

    wp_send_json($response);


}
