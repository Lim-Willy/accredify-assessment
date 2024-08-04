<?php

namespace App\DataTransferObjects\Verification;

use App\Enums\FileValidationResultEnum;
use App\Traits\DtoTrait;

class FileVerificationResult 
{
  use DtoTrait;

  protected int $userId;
  protected string $fileType;
  protected FileValidationResultEnum $verificationResult;
  protected string $recipientName;
  protected string $recipientEmail;
  protected string $issuerName;
  protected int $timestamp;

  public function __construct($data) {
    $this->fillDto($data);
  }
}