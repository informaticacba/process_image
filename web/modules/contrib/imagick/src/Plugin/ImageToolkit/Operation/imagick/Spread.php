<?php

namespace Drupal\imagick\Plugin\ImageToolkit\Operation\imagick;

use Imagick;

/**
 * Defines imagick spread operation.
 *
 * @ImageToolkitOperation(
 *   id = "imagick_spread",
 *   toolkit = "imagick",
 *   operation = "spread",
 *   label = @Translation("Spread"),
 *   description = @Translation("Randomly displaces each pixel in a block defined by the radius parameter.")
 * )
 */
class Spread extends ImagickOperationBase {

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    return [
      'radius' => [
        'description' => 'The spread radius, in pixels.',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function process(Imagick $resource, array $arguments) {
    return $resource->spreadImage($arguments['radius']);
  }

}
