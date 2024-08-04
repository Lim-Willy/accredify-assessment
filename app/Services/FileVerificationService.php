<?php

namespace App\Services;

use App\DataTransferObjects\Verification\FileVerificationResult as FileVerificationResultDto;;
use App\Enums\FileValidationResultEnum;
use App\DataTransferObjects\VerificationFileContent;
use App\Helpers\GeneralHelper;
use App\Repositories\FileVerificationRepository;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;

class FileVerificationService {
  private const DOH_QUERY_TYPE = 'TXT';
  private const DOH_LOOKUP_API = 'https://dns.google/resolve';

  protected $repository;

  public function __construct() {
    $this->repository = new FileVerificationRepository();
  }
    
  public function validateFileContent(VerificationFileContent $fileData) : FileValidationResultEnum
  {
    if(!$this->validateRecipientValidity($fileData)) {
      return FileValidationResultEnum::InvalidRecipient;
    }
    if(!$this->validateIssuerValidity($fileData)) {
      return FileValidationResultEnum::InvalidIssuer;
    }

    return FileValidationResultEnum::Verified;
  }

  public function verifySignature(VerificationFileContent $fileData) : FileValidationResultEnum
  {
    $flattenArray = GeneralHelper::flattenArray(Arr::get($fileData->toArray(), 'data'));
    $hashedArray = array();
    foreach($flattenArray as $key => $value) {
        $originalString = json_encode((object) [$key => $value]);
        $hashedData = hash('sha256', $originalString);
        array_push($hashedArray, $hashedData);
    }
    sort($hashedArray);
    $finalHashedCode = hash('sha256', json_encode($hashedArray));
    if($finalHashedCode !== Arr::get($fileData->toArray(), 'signature.targetHash')) {
      return FileValidationResultEnum::InvalidSignature;
    }
    return FileValidationResultEnum::Verified;
  }

  public function storeResult(VerificationFileContent $fileData, FileValidationResultEnum $result, string $mimeType) : void
  {
    $dataToStore = [
      'userId'             => Auth::user()->id,
      'fileType'           => $mimeType,
      'verificationResult' => $result,
      'timestamp'           => strtotime(now()),
      'recipientName'       => Arr::get($fileData->toArray(), 'data.recipient.name'),
      'recipientEmail'      => Arr::get($fileData->toArray(), 'data.recipient.email'),
      'issuerName'          => Arr::get($fileData->toArray(), 'data.issuer.name')
    ];
    $storeResult =  $this->repository->store(new FileVerificationResultDto($dataToStore));
    if(!$storeResult) {
      throw new Exception("Error Saving Result", Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    return;
  }

  private function validateRecipientValidity(VerificationFileContent $fileData) : Bool
  {
    if(empty(Arr::get($fileData->toArray(), 'data.recipient.name')) || empty(Arr::get($fileData->toArray(), 'data.recipient.email'))) {
      return False;
    }
    return True;
  }

  private function validateIssuerValidity(VerificationFileContent $fileData) : Bool 
  {
    if(empty(Arr::get($fileData->toArray(), 'data.issuer.name')) || empty(Arr::get($fileData->toArray(), 'data.issuer.identityProof'))) {
      return False;
    }
    $dohLookup = Http::get(self::DOH_LOOKUP_API, [
      'type'  => self::DOH_QUERY_TYPE,
      'name'  => Arr::get($fileData->toArray(), 'data.issuer.identityProof.location')
    ]);
    if($dohLookup->failed()) {
      return False;
    }
    $dohResponse = $dohLookup->json();
    $dohLookupStatus = False;
    foreach(Arr::get($dohResponse, 'Answer') as $responseAnswer) {
      if(strpos(Arr::get($responseAnswer, 'data'), Arr::get($fileData, 'data.issuer.identityProof.key')) !== False) {
        $dohLookupStatus = True;
        break;
      }
    }
    return $dohLookupStatus;
  }
}