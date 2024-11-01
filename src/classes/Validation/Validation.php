<?php

namespace ClothesEcommerce\Validation;

class Validation 
{
  public static function email (string $email, bool $is_required): string|bool 
  {
    if ($is_required && $email === '') {
      return 'E-mail address is required!';
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return 'E-mail address format is invalid!';
    }
    else if (strlen($email) > 255) {
      return 'E-mail address cannot be longer than 255 characters!';
    }

    return false;
  }
}