<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;

class PdfController extends Controller
{
    public function generatePDF()
    {
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'NotoSansDevanagari', // Change this to your font name
            'autoScriptToLang' => true,  // Automatically detects Hindi script
            'autoLangToFont' => true,  // Ensures correct font is applied
        ]);

        // Load Hindi content
        $html = View::make('pdf.hindi', ['data' => 'नमस्ते Laravel'])->render();
        $mpdf->WriteHTML($html);

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, 'hindi_document.pdf');
    }
}
