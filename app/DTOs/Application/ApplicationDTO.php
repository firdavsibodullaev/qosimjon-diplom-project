<?php

namespace App\DTOs\Application;

use App\Enums\Calibration\Result;
use App\Enums\Calibration\Status;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class ApplicationDTO
{
    public function __construct(
        public int           $factory_device_id,
        public int           $applicant_factory_id,
        public int           $applicant_id,
        public int           $checker_factory_id,
        public Status        $status,
        public Carbon        $deadline,
        public ?UploadedFile $document = null,
        public ?int          $checker_id = null,
        public ?Carbon       $checked_at = null,
        public ?Result       $result = null,
        public ?string       $comment = null,
    )
    {
    }
}
