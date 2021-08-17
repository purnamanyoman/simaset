<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function pdf_create($html, $filename='', $stream=TRUE, $paper = 'a4', $orientation = "portrait")
{
    require_once("dompdf/dompdf_config.inc.php");

    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->set_paper($paper, $orientation);
    $dompdf->render();
    $canvas = $dompdf->get_canvas();
    $canvas->page_text(760, 560, "Hal : {PAGE_NUM} / {PAGE_COUNT}", '', 12, array(0,0,0));
    if ($stream) {
        $dompdf->stream($filename.".pdf", array("Attachment" => 0));
    } else {
        return $dompdf->output();
    }
}

function pdf_create_to_disk($html, $filename='', $stream=TRUE, $paper = 'a4', $orientation = "portrait")
{
    require_once("dompdf/dompdf_config.inc.php");

    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->set_paper($paper, $orientation);
    $dompdf->render();
    $canvas = $dompdf->get_canvas();
    $canvas->page_text(760, 560, "Hal : {PAGE_NUM} / {PAGE_COUNT}", '', 12, array(0,0,0));

    $output = $dompdf->output();
    file_put_contents($filename.".pdf",$output);

}




?>
