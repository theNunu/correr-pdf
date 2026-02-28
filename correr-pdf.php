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

    $response = createPdf::doPdf($data);

    wp_send_json($response);


}
