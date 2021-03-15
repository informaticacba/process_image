<?php  

namespace Drupal\ngt_process_images\Plugin\Form;  

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProcessImagesGeneralForm extends ConfigFormBase {
    /**  
     * {@inheritdoc}  
     */  
    protected function getEditableConfigNames() {  
        return [  
            'ngt_process_images_general_general.settings',  
        ];  
    }  

    /**  
     * {@inheritdoc}  
     */  
    public function getFormId() {  
        return 'ngt_process_images_general_form_settings';  
    } 

    /**  
     * {@inheritdoc}  
     */  
    public function buildForm(array $form, FormStateInterface $form_state) {  
        $config = $this->config('ngt_process_images_general_general.settings');  


        $form['#tree'] = true;

        // configuración general 

        $form['ngt_process_images_general'] = [  
            '#type' => 'details',
            '#title' => t('Configuraciones generales'),   
            '#open' => false,  
        ]; 

        $form['ngt_process_images_general']['upload_ctn'] = [  
            '#type' => 'textfield',
            '#title' => t('Cantidad de imáges permitidas'),   
            '#default_value' => isset($config->get('ngt_process_images_general')['upload_ctn']) ? $config->get('ngt_process_images_general')['upload_ctn'] : 1,
            '#required' => true,
            '#description' => t('Permite indicar la cantidad de imágenes que se pueden cargar')
        ]; 
        
        $format = ['jpg'];
        $form['ngt_process_images_general']['format'] = [  
            '#type' => 'textarea',
            '#title' => t('Formatos de imágenes permitidas'),   
            '#default_value' => isset($config->get('ngt_process_images_general')['format']) ? $config->get('ngt_process_images_general')['format'] : implode(PHP_EOL, $format),
            '#description' => 'Ingrese línea por línea los formatos permitidos',
            '#required' => true
        ];

        $size = ['796x1123|A4'];
        $form['ngt_process_images_general']['size'] = [  
            '#type' => 'textfield',
            '#title' => t('Tamaño de la hoja permitido'),   
            '#default_value' => isset($config->get('ngt_process_images_general')['size']) ? $config->get('ngt_process_images_general')['size'] : $size,
            '#description' => 'Ingrese en formato clave(px)|valor(Label) ej: 796x1123|A4, los tamaños de hoja permitidos co su respectivo label',
            '#required' => true
        ];
        return parent::buildForm($form, $form_state);
    } 

    /**  
     * {@inheritdoc}  
     */  
    public function submitForm(array &$form, FormStateInterface $form_state) {  
        parent::submitForm($form, $form_state);

        $this->config('ngt_process_images_general_general.settings')
            ->set('ngt_process_images_general', $form_state->getValue('ngt_process_images_general'))  
            ->save(); 

    }  

    /**  
     * {@inheritdoc}  
     */ 
    public function validateFormat(array &$form, FormStateInterface $form_state){
        parent::validateFormat($form, $form_state);
    }

}