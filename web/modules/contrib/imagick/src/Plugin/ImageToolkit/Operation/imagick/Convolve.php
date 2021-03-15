<?php

namespace Drupal\imagick\Plugin\ImageToolkit\Operation\imagick;

use Imagick;
use ImagickKernel;

/**
 * Defines imagick sharpen operation.
 *
 * @ImageToolkitOperation(
 *   id = "imagick_convolve",
 *   toolkit = "imagick",
 *   operation = "convolve",
 *   label = @Translation("Convolve"),
 *   description = @Translation("Applies the convolve effect on an image")
 * )
 */
class Convolve extends ImagickOperationBase {

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    return [
      'matrix' => [
        'description' => 'The convolution matrix.',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function process(Imagick $resource, array $arguments) {
    $matrix = $arguments['matrix']['entries'];
    array_walk_recursive($matrix, function (&$value) { $value = (int) $value; });

    return $resource->convolveImage(ImagickKernel::fromMatrix($matrix));
  }

}
