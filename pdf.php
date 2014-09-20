<?php
require_once 'vendor/autoload.php';
require('vendor/itbz/fpdf/src/fpdf/FPDF.php');
require('vendor/itbz/fpdi/src/fpdi/FPDI.php');

Connection::conecting();
$file_id    =   $_GET['file'];

$file_id    =   strip_tags( htmlspecialchars( $file_id ) );
$file_id    =   filter_var( $file_id, FILTER_SANITIZE_NUMBER_INT );
$file_id    =   filter_var( $file_id, FILTER_VALIDATE_INT );

if ( !$file_id ) {
    echo 'no es un id valido';
}else {
    
    $archivo    =   Archivo::find( $file_id );    

    if ( count( $archivo ) > 0 ) {


        $pdf = new \fpdi\FPDI();

        $pageCount = $pdf->setSourceFile('uploads/lecciones/' . $archivo->nombre );
       

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);

            if ($size['w'] > $size['h']) {
                $pdf->AddPage('L', array($size['w'], $size['h']));
            } else {
                $pdf->AddPage('P', array($size['w'], $size['h']));
            }

            $pdf->useTemplate($templateId);

            $pdf->SetFont('Helvetica');
            $pdf->SetXY(5, 5);
        }

        
        $pdf->Output('file.pdf', 'I');

    }else {
        echo 'no existe el archivo';
    }
}



?>