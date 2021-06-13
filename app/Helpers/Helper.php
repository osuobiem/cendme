<?php
if (!function_exists('is_array')) {
  function is_in_array($array, $key, $key_value)
  {
    $within_array = 'no';
    foreach ($array as $k => $v) {
      if (is_array($v)) {
        $within_array = is_in_array($v, $key, $key_value);
        if ($within_array == 'yes') {
          break;
        }
      } else {
        if ($v == $key_value && $k == $key) {
          $within_array = 'yes';
          break;
        }
      }
    }
    return $within_array;
  }
}
