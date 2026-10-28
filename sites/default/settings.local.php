<?php
$config['system.logging']['error_level'] = 'some';
if (PHP_SAPI === 'cli') {
  error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
  set_error_handler(function ($severity, $message, $file, $line) {
    if ($severity === E_DEPRECATED || $severity === E_USER_DEPRECATED) {
      return true;
    }
    return false;
  });
}