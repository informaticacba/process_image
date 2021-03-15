<?php

namespace Drupal\imagick\Plugin\ImageToolkit\Operation\imagick;

use Drupal\imagick\Plugin\ImageToolkit\ImagickToolkit;
use Drupal\system\Plugin\ImageToolkit\Operation\gd\Resize as GdResize;
use Imagick;

/**
 * Defines imagick resize operation.
 *
 * @ImageToolkitOperation(
 *   id = "imagick_resize",
 *   toolkit = "imagick",
 *   operation = "resize",
 *   label = @Translation("Resize"),
 *   description = @Translation("Resizes an image to the given dimensions (ignoring aspect ratio).")
 * )
 */
class Resize extends GdResize {

  use ImagickOperationTrait;

  /**
   * {@inheritdoc}
   */
  protected function execute(array $arguments = []) {
    return $this->processFrames($arguments);
  }

  /**
   * {@inheritdoc}
   */
  protected function process(Imagick $resource, array $arguments) {
    $filter = \Drupal::config(ImagickToolkit::CONFIG)
      ->get(ImagickToolkit::CONFIG_RESIZE_FILTER);

    if ($filter == -1) {
      return $resource->scaleImage($arguments['width'], $arguments['height']);
    }
    else {
      return $resource->resizeImage($arguments['width'], $arguments['height'], $filter, 1);
    }
  }

}
