<?php 

namespace Drupal\ngt_generate_pdf;

use Drupal\file\Entity\File;
use Drupal\ngt_generate_pdf\FPDF;
use Symfony\Component\HttpFoundation\RedirectResponse;

class methodGeneral{
      
    /**
     * create_pdf
     *
     * @param  string $orientation
     * @param  string $image_url
     * @return void
     */
    public function create_pdf($orientation, $image_url, $paperSize = 'A4', $unit = 'cm', $destination = 'S'){

        switch ($orientation) {
            case 'Portrait':
                    $orientation = 'P';
                break;

            case 'Landscape':
                    $orientation = 'L';
                break;
        }

        $namePdf = 'doc_pdf_'. date('h_i_s'). '.pdf';

        $pdf = new FPDF($orientation, $unit, $paperSize);
        $pdf->AddPage();
        $pdf->Image($image_url, 0, 0);
        $stringPdf = $pdf->Output($namePdf, $destination);
        $urlPdf = $this->save_pdf($stringPdf);
        return $urlPdf;
    }
    
    /**
     * save_pdf
     *
     * @param  string $stringPdf
     * @return string
     */
    public function save_pdf($stringPdf){
        $directory = \Drupal::service('stream_wrapper_manager')
            ->getViaUri('public://')
            ->realpath();
        
        $file = '/file_pdf_'. date('d_m_y_h_i'). '.pdf';
        $pdf_root = $directory . $file;
        file_put_contents($pdf_root, $stringPdf);
        $realpath = file_create_url('public://'. $file);;
        return $realpath;
    }
    
    /**
     * calc_size_page Convert to centimeter
     *
     * @return void
     */
    public function calc_size_page($width, $height, $dpi = 300){

        $h = $width * 2.54 / $dpi;
        $l = $height * 2.54 / $dpi;
    
        $h = number_format($h, 2, '.', ' ');
        $l = number_format($l, 2, '.', ' ');
    
        $px2cm[] = $h;
        $px2cm[] = $l;
        
        return $px2cm;
    }

}