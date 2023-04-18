<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/*use Mpdf\Mpdf;
use Mpdf\Output\Destination;*/

class PDFController extends Controller
{
    public function createPDF(){

        //create new PDF
      /*  $mpdf = new Mpdf();
        $header = "<h1>MLEAGE CLAIM</h1>";
        $footer = "<h6>Printed By Prime Systems.</h6>";

        if (strlen($header)) {
            $mpdf->SetHTMLHeader($header);
        }

        if (strlen($footer)) {
            $mpdf->SetHTMLFooter($footer);
        }
        */
       return view('mileage.claimprintout');

    }
}
