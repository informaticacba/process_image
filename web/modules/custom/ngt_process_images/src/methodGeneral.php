<?php 

namespace Drupal\ngt_process_images;

use Drupal\file\Entity\File;
use Drupal\rest\ResourceResponse;
use Drupal\user\Entity\User;
use Imagick;

class methodGeneral{
  

    /**
     * autoRotateImage
     *
     * @param  string $path
     * @return void
     */
    public function autoRotateImage($pic_fid, $recommendedOrientation, $format = 'jpg') {
        $path = $this->real_path($pic_fid);
        header('Content-type: image/'.$format);
        
        $image = new \Imagick(realpath($path));
        $orientation = $image->getImageOrientation();

        switch($recommendedOrientation) {
            case 'Portrait':
                $image->rotateimage("#000", 90); // rotate 90 degrees CW
            break;

            case 'Landscape':
                $image->rotateimage("#000", -90); // rotate 90 degrees CCW
            break;
        }

        // Set exif data new orientation
        $image->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);

        // Write image rotate
        $image->writeImage($path);
    }

    /* autoRotateImage
     *
     * @param  string $path
     * @return void
     */
    public function scaleImage($pic_fid, $recommendedOrientation, $format = 'jpg', $scale = FALSE) {
        $path = \Drupal::service('ngt_general.methodGeneral')->real_path($pic_fid);
        header('Content-type: image/'.$format);
        
        if($scale) {
            $image = new \Imagick(realpath($path));
            switch($recommendedOrientation) {
                case 'Portrait':
                    $image->scaleImage('796', '1123', TRUE); 
                break;
    
                case 'Landscape':
                    $image->scaleImage('1123', '796', TRUE); 
                break;
            }
    
            // Write image rotate
            $image->writeImage($path);
        }

        $url = \Drupal::service('ngt_general.methodGeneral')->load_url_file($pic_fid);
        return $url;
    }
    
    
    /**
     * getOrientation
     *
     * @param  string $path
     * @return string
     */
    public function getOrientation($pic_fid){
        
        $path = \Drupal::service('ngt_general.methodGeneral')->real_path($pic_fid);
        
        list($width, $height) = getimagesize($path);
        $data = [
            'width' => $width,
            'height' => $height,
        ];
        if ($width > $height) {
            $data['orientation'] ='Landscape';
        } else {
            $data['orientation'] = 'Portrait';
        }
        return $data;
    }


}