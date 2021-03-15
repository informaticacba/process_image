<?php

namespace Drupal\imagick\Plugin\ImageEffect;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Image\ImageInterface;
use Drupal\image\ConfigurableImageEffectBase;

/**
 * Applies an opacity effect on an image resource.
 *
 * @ImageEffect(
 *   id = "image_opacity",
 *   label = @Translation("Opacity"),
 *   description = @Translation("Applies an opacity effect on an image.")
 * )
 */
class OpacityImageEffect extends ConfigurableImageEffectBase {
  /**
   * {@inheritdoc}
   */
  public function applyEffect(ImageInterface $image) {
    if (!$image->apply('opacity', $this->configuration)) {
      $this->logger->error('Image coloroverlay failed using the %toolkit toolkit on %path (%mimetype)', [
        '%toolkit' => $image->getToolkitId(),
        '%path' => $image->getSource(),
        '%mimetype' => $image->getMimeType()
      ]);
      return FALSE;
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getSummary() {
    $summary = parent::getSummary();
    $summary['#markup'] = '- ' . $this->configuration['alpha'] . '%';

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'alpha' => 50,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['colorform'],
      ],
    ];

    $form['alpha'] = [
      '#type' => 'number',
      '#title' => $this->t('Opacity'),
      '#description' => $this->t('Opacity of the color overlay in percents.'),
      '#default_value' => $this->configuration['alpha'],
      '#min' => 0,
      '#max' => 100,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);

    $this->configuration['alpha'] = $form_state->getValue('alpha');
  }

}
