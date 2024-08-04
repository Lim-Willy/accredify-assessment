<?php 

namespace App\Helpers;

class GeneralHelper {
  public static function flattenArray(?array $array, string $prefix = ''): array
  {
    if($array === null) {
      return [];
    }
    $result = [];

    foreach ($array as $key => $value) {
        $newKey = $prefix === '' ? $key : $prefix . '.' . $key;

        if (is_array($value)) {
            $result = array_merge($result, self::flattenArray($value, $newKey));
        } else {
            $result[$newKey] = $value;
        }
    }

    return $result;
  }
}