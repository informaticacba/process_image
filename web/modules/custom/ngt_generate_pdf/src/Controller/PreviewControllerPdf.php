<?php 

namespace Drupal\ngt_generate_pdf\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\file\Entity\File;

class PreviewControllerPdf extends ControllerBase{
  
    public function preview(Request $request, $fid = NULL, $orientation = NULL){
        
        \Drupal::service('page_cache_kill_switch')->trigger();
        
        $image = File::load($fid);
        $image = isset($image) ? $image->getFileUri() : '';
        
        $confiGeneral = \Drupal::config('ngt_process_images_general_general.settings')->get('ngt_process_images_general');
        $size = explode('|',$confiGeneral['size']);
        $size = explode('x', $size[0]);

        if($orientation == 'Portrait'){
            $width = $size[0];
            $height = $size[1];
        }else{
            $width = $size[1];
            $height = $size[0];
        }
        
        return [
            '#theme' => 'preview',
            '#image' => $image,
            '#width' => $width,
            '#height' => $height,
        ];
    }

}