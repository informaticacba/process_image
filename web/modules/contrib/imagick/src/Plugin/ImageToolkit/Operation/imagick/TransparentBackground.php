<?php

namespace Drupal\imagick\Plugin\ImageToolkit\Operation\imagick;

use Imagick;

/**
 * Defines imagick transparent background operation.
 *
 * @ImageToolkitOperation(
 *   id = "imagick_transparent_background",
 *   toolkit = "imagick",
 *   operation = "transparent_background",
 *   label = @Translation("Transparent background"),
 *   description = @Translation("Make image background transparent")
 * )
 */
class TransparentBackground extends ImagickOperationBase {

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(array $arguments = []) {
    /* @var $resource Imagick */
    $resource = $this->getToolkit()->getResource();

    // Replace white background with fuchsia
    $floodSuccess = $resource->floodFillPaintImage("rgb(255, 0, 255)", 2500, "rgb(255,255,255)", 0 , 0, false);
    // Make fuchsia transparent
    $transparentSuccess = $resource->transparentPaintImage("rgb(255,0,255)", 0, 10, FALSE);

    // Format as PNG
    $formatSuccess = $resource->setImageFormat('PNG');

    return ($floodSuccess && $transparentSuccess);
  }
}
