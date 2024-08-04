<?php

namespace App\DataTransferObjects\Login;

use App\Traits\DtoTrait;

class LoginCredential {
  use DtoTrait;

  protected string $email;
  protected string $password;

  public function __construct($data) {
    $this->fillDto($data);
  }
}