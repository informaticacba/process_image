<?php  

namespace Drupal\ngt_process_images\Plugin\Form;  

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class UploadImagesForm extends ConfigFormBase {
  /**  
   * {@inheritdoc}  
   */  
  protected function getEditableConfigNames() {  
      return [  
          'ngt_process_images_upload.settings',  
      ];  
  }  

  /**  
   * {@inheritdoc}  
   */  
  public function getFormId() {  
      return 'ngt_process_images_upload_form_settings';  
  } 

  /**  
   * {@inheritdoc}  
   */  
  public function buildForm(array $form, FormStateInterface $form_state) {  
    $config = $this->config('ngt_process_images_upload.settings');

    $form['ngt_process_images_upload']['image'] = [
      '#type' => 'managed_file',
      '#title' => t('Foto'),'#description' => t('Foto fondo, de extension jpg'),
      '#upload_location' => 'public://',
      '#upload_validators' => [
        'file_validate_extensions' => ['jpg'],
      ],
    ];
    return parent::buildForm($form, $form_state);
  } 

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $confiGeneral = \Drupal::config('ngt_process_images_general_general.settings')->get('ngt_process_images_general');
    $pic = $form_state->getValue('image');
    
    if ($pic) {
        \Drupal::service('ngt_general.methodGeneral')->setFileAsPermanent($pic);
        
        $pic_fid = reset($pic);
        $dataImage = \Drupal::service('ngt_process_images.methodGeneral')->getOrientation($pic_fid);
        
        if(is_array($dataImage)){
          
          $size = explode('|',$confiGeneral['size']);
          $size = explode('x', $size[0]);
          $pageSet = $dataImage['orientation'];
          $widthConfig = $size[0];
          $heightConfig = $size[1];
          $widthImage = $dataImage['width'];
          $heightImage = $dataImage['height'];
          $label = $size[1];
          $scale = FALSE;

          if($widthImage >= $widthConfig || $heightImage >= $heightConfig){
            $status = 'Foto procesada de forma correcta, se aprovecha el máximo de la hoja A4';
            $scale = TRUE;
          }else{
            $status = 'Foto procesada de forma correcta, no se puede aprovechar el máximo de la hoja A4 hay perdida de espacio en el pdf';
          }
            
          // service scale image 
          $urlImageScale = \Drupal::service('ngt_process_images.methodGeneral')->scaleImage($pic_fid, $pageSet, '', $scale);

          // service get info new image
          $dataImageNew = \Drupal::service('ngt_process_images.methodGeneral')->getOrientation($pic_fid);

          $paperSize =  \Drupal::service('ngt_generate_pdf.fpdf')->calc_size_page($widthConfig, $heightConfig);

          // service generate pdf
          $pdf = \Drupal::service('ngt_generate_pdf.fpdf')->create_pdf($pageSet, $urlImageScale);
          
          // \Drupal::service('ngt_process_images.methodGeneral')->autoRotateImage($pic_fid, $pageSet);
          $message = t($status.', su orientación es: @orientation, ancho original:@width px, alto original:@height px; ancho nuevo:@widthNew px, alto nuevo:@heightNew px;  <a target="_blank" href="@url">(ver foto)</a> <a target="_blank" href="@urlPdf">(ver pdf)</a> <a target="_blank" href="@preview">(ver preview)</a>', 
            [
              '@orientation' => $pageSet, 
              '@width' => $widthImage, 
              '@height' => $heightImage, 
              '@url' => $urlImageScale,
              '@widthNew' => $dataImageNew['width'], 
              '@heightNew' => $dataImageNew['height'], 
              '@urlPdf' => $pdf,
              '@preview' => '/ngt/general/process/images/preview/' . $pic_fid.'/'.$pageSet
            ]
          );
          
          \Drupal::messenger()->addMessage($message, 'status');
          
        }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // $sim_number = $form_state->getValue('simcard_number');
    
    // if($sim_number==""){
    //   $form_state->setErrorByName('simcard_number', t('El valor de número simcard está vacio'));
    // }
    
    // if(!is_numeric($sim_number)) {
    //   $form_state->setErrorByName('simcard_number', t('El valor de número simcard debe estar compuesto solo por numeros'));
    // }
  
    parent::validateForm($form, $form_state);
  }

}