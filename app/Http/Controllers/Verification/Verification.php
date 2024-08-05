<?php

namespace App\Http\Controllers\Verification;

// use App\DataTransferObjects\JsonVerification;
use App\DataTransferObjects\VerificationFileContent;
use App\Enums\FileValidationResultEnum;
use App\Helpers\GeneralHelper;
use App\Http\Requests\VerifyJsonFileRequest;
use App\Http\Resources\Verification\JsonVerificationResource;
use App\Services\FileVerificationService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Response\SuccessResponseResource;
use App\Http\Resources\Response\ErrorResponseResource;
use App\Http\Controllers\Controller;

class Verification extends Controller
{
    protected FileVerificationService $verificationService;

    public function __construct() {
        $this->verificationService = new FileVerificationService();
    }

    public function __invoke(VerifyJsonFileRequest $request) : JsonResponse
    {
        $fileContent = file_get_contents($request->file('fileUpload'));
        $fileJsonContent = json_decode($fileContent, true);
        $verificationResult = $this->verificationService->validateFileContent(new VerificationFileContent($fileJsonContent));
        if($verificationResult === FileValidationResultEnum::Verified) {
            $verificationResult = $this->verificationService->verifySignature(new VerificationFileContent($fileJsonContent));
        }
        // dd($verificationResult);
        try{
            $this->verificationService->storeResult(new VerificationFileContent($fileJsonContent), $verificationResult, $request->file('fileUpload')->getMimeType());
        }catch(\Throwable $th) {
            return new JsonResponse(new ErrorResponseResource([
                'message'   => 'Error Saving Result '.$th->getMessage()
            ]));
        }
        $resultArray = [
            'issuer'    => Arr::get($fileJsonContent, 'data.issuer.name'),
            'result'    => $verificationResult
        ];
        return new JsonResponse(new SuccessResponseResource($resultArray), Response::HTTP_OK);
    }
}
