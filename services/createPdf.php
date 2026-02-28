<?php

class createPdf
{

    public static function doPdf()
    {
        wp_send_json([
            "status" => "ok",
            "1 mensaje" => "soy tilin"
        ]);

    }
}