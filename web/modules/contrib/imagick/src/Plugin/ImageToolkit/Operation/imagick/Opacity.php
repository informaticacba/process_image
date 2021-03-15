<?php

namespace Drupal\imagick\Plugin\ImageToolkit\Operation\imagick;

use Imagick;

/**
 * Defines imagick opacity operation.
 *
 * @ImageToolkitOperation(
 *   id = "imagick_opacity",
 *   toolkit = "imagick",
 *   operation = "opacity",
 *   label = @Translation("Opacity"),
 *   description = @Translation("Applies an opacity effect on an image")
 * )
 */
class Opacity extends ImagickOperationBase {
  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    return [
      'alpha' => [
        'description' => 'The transparency of the overlay layer.',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function process(Imagick $resource, array $arguments) {
    $opacity = $arguments['alpha'] / 100;

    if (method_exists($resource, 'setImageOpacity')) {
      return $resource->setImageOpacity($opacity);
    } else {
      return $resource->setImageAlpha($opacity);
    }
  }
}
