<?php

namespace ClothesEcommerce\Validation;

class Validation 
{
  public static function email (string $email, bool $is_required = false): string|bool 
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

  public static function length (string $string, string $name, int $from, int $to, bool $isRequired = false): string|bool
  {
    if ($isRequired && $string === '') {
      return $name . ' is required!';
    }
    else if ($string !== '' && (strlen($string) < $from || strlen($string) > $to)) {
      return  $name . ' cannot be shorter than ' . $from . ' and longer than ' . $to . ' characters!';
    }
  
    return false;
  }
}