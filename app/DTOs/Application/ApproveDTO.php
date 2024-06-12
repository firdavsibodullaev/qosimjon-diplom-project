<?php

namespace App\DTOs\Application;

use Illuminate\Http\UploadedFile;

class ApproveDTO
{
    public function __construct(
        public UploadedFile $document,
        public ?string $comment = null
    )
    {
    }
}
