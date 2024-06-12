<?php

namespace App\DTOs\Application;

use Illuminate\Http\UploadedFile;

class RejectDTO
{
    public function __construct(
        public string $comment,
        public UploadedFile $document
    )
    {
    }
}
