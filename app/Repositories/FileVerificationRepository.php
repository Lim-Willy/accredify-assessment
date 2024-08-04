<?php

namespace App\Repositories;

use App\DataTransferObjects\Verification\FileVerificationResult as FileVerificationResultDto;
use App\Models\FileVerificationResult;

class FileVerificationRepository
{
  public function store(FileVerificationResultDto $data) :Bool
  {
    $storeData = FileVerificationResult::create([
      'user_id'             => $data->get('userId'),
      'verification_result' => $data->get('verificationResult')->value,
      'recipient_name'      => $data->get('recipientName'),
      'issuer_name'         => $data->get('issuerName'),
      'timestamp'           => $data->get('timestamp'),
      'file_type'           => $data->get('fileType')
    ]);
    if(!$storeData) {
      return False;
    }
    return True;
  }
}