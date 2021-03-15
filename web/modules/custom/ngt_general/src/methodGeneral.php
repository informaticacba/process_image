<?php 

namespace Drupal\ngt_general;

use Drupal\file\Entity\File;
use Drupal\rest\ResourceResponse;
use Drupal\user\Entity\User;
use Drupal\media\Entity\Media;
use Drupal\Core\Url;
use Drupal\image\Entity\ImageStyle;

class methodGeneral{
   
   
    /**
     * @param string $fid
     *   File id.
     */
    public function setFileAsPermanent($fid) {
        \Drupal::service('page_cache_kill_switch')->trigger();
        if (is_array($fid)) {
            $fid = array_shift($fid);
        }

        $file = File::load($fid);
        if (!is_object($file)) {
            return;
        }

        $file->setPermanent();
        $file->save();
        \Drupal::service('file.usage')->add($file, 'ngt', 'ngt', $fid);
    }

    /**
     * change_size
     *
     * @param  int $media_field
     * @return url
     */
    public function change_size($media_field, $style = NULL){
        $file = File::load($media_field);
        $url = $file->getFileUri();
        if ($style != NULL){
            $url = ImageStyle::load($style)->buildUrl($url);
        }
        return $url;
    }

    /**
     * load_url_file
     *
     * @param  int $media_field
     * @return string url
     */
    public function load_url_file($media_field){
        $file = File::load($media_field);
        $url = file_create_url($file->getFileUri());
        return $url;
    }
    
    /**
     * real_path
     *
     * @param  mixed $file_id
     * @return string
     */
    public function real_path($file_id){
        $file = File::load($file_id);
        $uri = $file->getFileUri();
        $stream_wrapper_manager = \Drupal::service('stream_wrapper_manager')->getViaUri($uri);
        $file_path = $stream_wrapper_manager->realpath();
        return $file_path;
    }

}
