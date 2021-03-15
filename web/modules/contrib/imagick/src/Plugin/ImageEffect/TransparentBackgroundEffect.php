<?php

namespace Drupal\imagick\Plugin\ImageEffect;

use Drupal\Core\Image\ImageInterface;
use Drupal\image\ImageEffectBase;

/**
 * Replace image background with transparency.
 *
 * @ImageEffect(
 *   id = "image_transparent_background",
 *   label = @Translation("Transparent background"),
 *   description = @Translation("Make image background transparent")
 * )
 */
class TransparentBackgroundEffect extends ImageEffectBase {

  /**
   * {@inheritdoc}
   */
  public function applyEffect(ImageInterface $image) {
    if (!$image->apply('transparent_background', $this->configuration)) {
      $this->logger->error('Image transparent background failed using the %toolkit toolkit on %path (%mimetype)', [
        '%toolkit' => $image->getToolkitId(),
        '%path' => $image->getSource(),
        '%mimetype' => $image->getMimeType()
      ]);
      return FALSE;
    }
    return TRUE;
  }
}
