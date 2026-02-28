<?php
/*
Plugin Name: Mi Plugin PDF
Description: Plugin de prueba para generación de PDFs.
Version: 1.0
Author: Alexis
*/

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_mi_hola_mundo', 'mi_hola_mundo');
add_action('wp_ajax_nopriv_mi_hola_mundo', 'mi_hola_mundo');

function mi_hola_mundo() {
    wp_send_json_success([
        'mensaje' => 'Hola Mundo desde WordPress AJAX .........'
    ]);
}