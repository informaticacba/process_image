<?php 

namespace Drupal\ngt_process_images\Plugin\Config\Block;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\ngt_process_images\Plugin\Block\UploadImagesBlock;

/**
 * Manage config a 'UploadImagesBlockClass' block
 */
class UploadImagesBlockClass {
    protected $instance;
    protected $configuration;

    /**
     * @param \Drupal\ngt_process_images\Plugin\Block\UploadImagesBlock $instance
     * @param $config
     */
    public function setConfig(UploadImagesBlock &$instance, &$config){
        $this->instance = &$instance;
        $this->configuration = &$config;
    }

    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration() {
        return [];
    }

    /**
     * @param \Drupal\ngt_process_images\Plugin\Block\UploadImagesBlock $instance
     * @param $config
     */
    public function build(UploadImagesBlock &$instance, $configuration){
        $form = \Drupal::formBuilder()->getForm('Drupal\ngt_process_images\Plugin\Form\UploadImagesForm',$this->configuration);
        $this->configuration = $configuration;

        // Set data uuid, generate filters_fields, generate table_fields.
        $instance->setValue('config_name', 'UploadImagesBlock');
        $instance->setValue('class', 'block-upload-images');
        $instance->setValue('directive', 'data-ng-upload-images');
        $uuid = $instance->uuid('block-upload-images');
        $this->instance->setValue('dataAngular', 'upload-images-' . $uuid);

        // Set theme $vars.
        $parameters = [
            'theme' => 'ngt_process_images_block',
            'library' => 'ngt_process_images/upload-images',
        ];

        $others = [
            '#title' => [
                'label' => 'Carga de imágenes',
                'label_display' => '1',
            ],
            '#form' => $form,
            '#uuid' => $uuid,
            '#dataAngular' => $this->instance->getValue('dataAngular'),
        ];

       

        // Set JavaScript data.
        $other_params = [];

        $config_block = $instance->cardBuildConfigBlock('/ngt/rest/upload-images?_format=json', $other_params);
        $instance->cardBuildVarBuild($parameters, $others);
        $instance->cardBuildAddConfigDirective($config_block);

        return $instance->getValue('build');
    }

}