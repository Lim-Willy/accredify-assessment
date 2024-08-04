<?php

namespace Tests\Unit;

use App\DataTransferObjects\Verification\FileVerificationResult;
use App\DataTransferObjects\VerificationFileContent;
use App\Enums\FileValidationResultEnum;
use App\Services\FileVerificationService;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FileVerificationTest extends TestCase
{
    protected FileVerificationService $fileVerificationService;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->fileVerificationService = new FileVerificationService();
    }

    public function test_validation_service_file_content() {
        
        $file = base_path('SampleFile/sample.json');
        $testResult = $this->testFileContentFormat($file);
        $this->assertEquals(FileValidationResultEnum::Verified->value, $testResult->value);
        
    }

    public function test_validation_service_file_content_invalid_recipient() {
        
        $file = base_path('SampleFile/sample-invalid-recipient.json');
        $testResult = $this->testFileContentFormat($file);
        $this->assertEquals(FileValidationResultEnum::InvalidRecipient, $testResult);
    }

    public function test_validation_service_file_content_invalid_issuer() {
        
        $file = base_path('SampleFile/sample-invalid-issuer.json');
        $testResult = $this->testFileContentFormat($file);
        $this->assertEquals(FileValidationResultEnum::InvalidIssuer, $testResult);
        
    }

    public function test_signature_validity() {
        $file = base_path('SampleFile/sample.json');
        $fileContent = file_get_contents($file);
        $fileJsonContent = json_decode($fileContent, true);
        $testResult = $this->fileVerificationService->verifySignature(new VerificationFileContent($fileJsonContent));
        $this->assertEquals(FileValidationResultEnum::Verified, $testResult);
    }

    public function test_signature_validity_invalid() {
        $file = base_path('SampleFile/sample - invalid signature.json');
        $fileContent = file_get_contents($file);
        $fileJsonContent = json_decode($fileContent, true);
        $testResult = $this->fileVerificationService->verifySignature(new VerificationFileContent($fileJsonContent));
        $this->assertEquals(FileValidationResultEnum::InvalidSignature, $testResult);
    }

    private function testFileContentFormat($file) : FileValidationResultEnum
    {
        $mockResponse = json_decode(file_get_contents(base_path('SampleFile/sampleResponse.json')), true);
        Http::fake([
            'https://dns.google/resolve?type=TXT&name=ropstore.accredify.io' => Http::response($mockResponse, 200),
        ]);
        $fileContent = file_get_contents($file);
        $fileJsonContent = json_decode($fileContent, true);
        
        $testResult = $this->fileVerificationService->validateFileContent(new VerificationFileContent($fileJsonContent));
        return $testResult;
    }
}
