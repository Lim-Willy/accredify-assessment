<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileVerificationResult extends Model
{
    use HasFactory;

    protected $table = 'file_verification_results';

    protected $fillable = [
        'file_type',
        'verification_result',
        'issuer_name',
        'recipient_name',
        'recipient_email',
        'timestamp',
        'user_id'
    ];


}
