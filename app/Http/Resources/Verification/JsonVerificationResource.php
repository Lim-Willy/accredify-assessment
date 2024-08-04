<?php

namespace App\Http\Resources\Verification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class JsonVerificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array(
            'issuer'    => $this->issuer,
            'result'    => $this->result
        );
    }
}
