<?php

namespace App\DataTransferObjects;

use App\Traits\DtoTrait;

class VerificationFileContent {
  use DtoTrait;

  protected array $data;
  protected array $signature;

  public function __construct(array $data) {
    $this->fillDto($data);
  }
}