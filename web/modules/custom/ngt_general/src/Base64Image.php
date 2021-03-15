<?php
namespace Drupal\ngt_general;

use Drupal\image\Entity\ImageStyle;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Base64Image {

    protected $base64Image;
    protected $fileData;
    protected $fileName;
    protected $directory;

    public function __construct($base64Image) {
        $this->base64Image = $base64Image;
        $this->decodeBase64Image();
    }

    protected function decodeBase64Image() {
        $this->fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $this->base64Image));
        if ($this->fileData === FALSE) {
            throw new BadRequestHttpException('Avatar image could not be processed.');
        }
        // Determine image type
        $f = finfo_open();
        $mimeType = finfo_buffer($f, $this->fileData, FILEINFO_MIME_TYPE);
        // Generate fileName
        $ext = $this->getMimeTypeExtension($mimeType);
        $this->fileName = uniqid(rand()) . $ext;
    }

    /**
     * @param string $mimeType
     *
     * @return string
     */
    protected function getMimeTypeExtension($mimeType) {
        $mimeTypes = [
            'image/png' => 'png',
            'image/jpeg' => 'jpg',
            'image/gif' => 'gif',
            'image/bmp' => 'bmp',
            'image/vnd.microsoft.icon' => 'ico',
            'image/tiff' => 'tiff',
            'image/svg+xml' => 'svg',
        ];

        if (isset($mimeTypes[$mimeType])) {
            return '.' . $mimeTypes[$mimeType];
        }else {
            $split = explode('/', $mimeType);
            return '.' . $split[1];
        }

    }

    /**
     * @return mixed
     */
    public function getFileData() {
        return $this->fileData;
    }

    /**
     * @return mixed
     */
    public function getFileName() {
        return $this->fileName;
    }

    /**
     * @param string $path
     */
    public function setFileDirectory($path, $upload_location) {
        $this->directory = \Drupal::service('file_system')->realpath(file_default_scheme() . $upload_location);
        $this->directory .= 's3://' . $path;
        \Drupal\Core\File\FileSystemInterface::prepareDirectory($this->directory, FILE_MODIFY_PERMISSIONS | FILE_CREATE_DIRECTORY);
        // file_prepare_directory($this->directory, FILE_MODIFY_PERMISSIONS | FILE_CREATE_DIRECTORY);
    }

    /**
     * @param $path
     */
    public function setImageStyleImages($path) {
        $styles = ['medium', '28x28', '98x98', '110x100', 'max_325x325'];
        foreach ($styles as $style) {
            $imageStyle = ImageStyle::load($style);
            $uri = $imageStyle->buildUri($path . '/' . $this->fileName);
            $imageStyle->createDerivative($this->directory . '/' . $this->fileName, $uri);
        }
    }
}