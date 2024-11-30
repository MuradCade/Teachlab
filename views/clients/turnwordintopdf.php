<?php
require '../../vendor/autoload.php';

use PhpOffice\PhpWord\IOFactory;
use Dompdf\Dompdf;

function convertDocxToPdf($docxPath, $pdfPath) {
    // Load Word document
    $phpWord = \PhpOffice\PhpWord\IOFactory::load($docxPath);

    // Save the document as HTML
    $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
    ob_start();
    $htmlWriter->save('php://output');
    $htmlContent = ob_get_clean();

    // Convert HTML to PDF using Dompdf
    $dompdf = new Dompdf();
    $dompdf->loadHtml($htmlContent);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Save the PDF to a file
    file_put_contents($pdfPath, $dompdf->output());
    
}