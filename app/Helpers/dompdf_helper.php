<?php

use Dompdf\Dompdf;

if (!function_exists('create_pdf')) {
    function create_pdf($filepath, $html)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Simpan output ke file
        file_put_contents($filepath, $dompdf->output());
    }
}