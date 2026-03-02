<?php

class createPdf
{
    public static function doPdf($data)
    {
        // Ruta base del plugin
        // $base_path = plugin_dir_path(__FILE__);
        $base_path = dirname(plugin_dir_path(__FILE__), 1);

        // Ahora la ruta debería ser: .../correr-pdf/services/templates/pdfs/
        // AJUSTE: Si 'templates' está en la raíz del plugin, usa dirname(..., 2)
        $ruta_templates = $base_path . '/templates/pdfs/';

        // La carpeta de salida está en la raíz del plugin
        $carpeta_pdfs = dirname(plugin_dir_path(__FILE__), 1) . '/mis_archivosPDF';

        // // Carpeta donde están los PHP (templates)
        // $ruta_templates = $base_path . 'templates/pdfs/';

        // // Carpeta donde se guardará el PDF (la misma que usaste en make_pdf)
        // $carpeta_pdfs = $base_path . 'mis_archivosPDF'; 

        if (!file_exists($carpeta_pdfs)) {
            wp_mkdir_p($carpeta_pdfs);
        }

        // 1. Determinar template
        $template_file = '';
        switch ($_POST['tipo_documento']) {
            case 'ingreso':
                $template_file = $ruta_templates . 'ingreso.php';
                break;
            case 'despido':
                $template_file = $ruta_templates . 'despido.php';
                break;
            case !isset( $_POST['tipo_documento']):
                $_POST['tipo_documento'] = 'cita_medica';
                $template_file = $ruta_templates . 'cita_medica.php';
                break;
            default:
                $template_file = $ruta_templates . 'ingreso.php';
                // break;
        }

        // 2. Capturar el HTML
        ob_start();
        if (file_exists($template_file)) {
            include $template_file;
        } else {
            return "Error: No existe el template en " . $template_file;
        }
    
        $html = ob_get_clean();
        $fecha_nom = date('Y-m-d_H-i-s'); 
        
        $nameFile = (isset($_POST['tipo_documento']) ) ? $_POST['tipo_documento'] :'cita-medica';
        $nombre_pdf = $nameFile . '-' . $data['cedula'] . '-' . $fecha_nom  . '.pdf';

        
//         // Comprobar si se ha enviado un nombre por POST, si no, asignar "Invitado"
// $nombre_usuario = ( isset($_POST['nombre']) && !empty($_POST['nombre']) ) ? sanitize_text_field($_POST['nombre']) : 'Invitado';


        $ruta_final_pdf = $carpeta_pdfs . '/' . $nombre_pdf;

        $resultado = file_put_contents($ruta_final_pdf, $html);

        if ($resultado !== false) {
            // Retornamos la URL pública
            return plugins_url('mis_archivosPDF/' . $nombre_pdf, __FILE__);
        } else {
            return "Error al guardar el archivo físico en: " . $ruta_final_pdf;
        }
    }
}