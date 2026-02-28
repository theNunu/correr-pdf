<?php

class createPdf
{

    public static function doPdf($data)
    {
        wp_send_json([
            "status" => "ok",
            "1 mensaje" => "soy tilin",
            "data" => $data
        ]);

    }
}