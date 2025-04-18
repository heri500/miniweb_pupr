<?php

namespace Drupal\Component\Utility;

/**
 * Provides helpers to operate on images.
 *
 * @ingroup utility
 */
class Image {

  /**
   * Scales image dimensions while maintaining aspect ratio.
   *
   * The resulting dimensions can be smaller for one or both target dimensions.
   *
   * @param array $dimensions
   *   Dimensions to be modified - an array with components width and height, in
   *   pixels.
   * @param int $width
   *   (optional) The target width, in pixels. If this value is NULL then the
   *   scaling will be based only on the height value.
   * @param int $height
   *   (optional) The target height, in pixels. If this value is NULL then the
   *   scaling will be based only on the width value.
   * @param bool $upscale
   *   (optional) Boolean indicating that images smaller than the target
   *   dimensions will be scaled up. This generally results in a low quality
   *   image.
   *
   * @return bool
   *   TRUE if $dimensions was modified, FALSE otherwise.
   */
  public static function scaleDimensions(array &$dimensions, $width = NULL, $height = NULL, $upscale = FALSE) {
    $aspect = $dimensions['height'] / $dimensions['width'];

    // Calculate one of the dimensions from the other target dimension,
    // ensuring the same aspect ratio as the source dimensions. If one of the
    // target dimensions is missing, that is the one that is calculated. If both
    // are specified then the dimension calculated is the one that would not be
    // calculated to be bigger than its target.
    if (($width && !$height) || ($width && $height && $aspect < $height / $width)) {
      $height = (int) round($width * $aspect);
    }
    else {
      $width = (int) round($height / $aspect);
    }

    // Don't upscale if the option isn't enabled.
    if (!$upscale && ($width >= $dimensions['width'] || $height >= $dimensions['height'])) {
      return FALSE;
    }

    $dimensions['width'] = $width;
    $dimensions['height'] = $height;
    return TRUE;
  }

  /**
   * Returns the offset in pixels from the anchor.
   *
   * @param string $anchor
   *   The anchor ('top', 'left', 'bottom', 'right', 'center').
   * @param int $current_size
   *   The current size, in pixels.
   * @param int $new_size
   *   The new size, in pixels.
   *
   * @return int
   *   The offset from the anchor, in pixels.
   *
   * @throws \InvalidArgumentException
   *   When the $anchor argument is not valid.
   */
  public static function getKeywordOffset(string $anchor, int $current_size, int $new_size): int {
    return match ($anchor) {
      'bottom', 'right' => $current_size - $new_size,
      'center' => (int) round($current_size / 2 - $new_size / 2),
      'top', 'left' => 0,
      default => throw new \InvalidArgumentException("Invalid anchor '{$anchor}' provided to getKeywordOffset()"),
    };
  }

}
