<?php

namespace App\Services;

use App\DTOs\Application\ApproveDTO;
use App\DTOs\Application\FilterDTO;
use App\DTOs\Application\RejectDTO;
use App\Enums\Calibration\MediaCollection;
use App\Enums\Calibration\Result;
use App\Enums\Calibration\Status;
use App\Models\Calibration;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ApplicationService
{
    public function __construct(
        protected FactoryDeviceService $factoryDeviceService
    )
    {
    }

    public function paginate(FilterDTO $filter): Collection|LengthAwarePaginator
    {
        return Calibration::filter($filter)
            ->with(['media', 'applicant', 'checker', 'applicantFactory', 'checkerFactory', 'device.device.attributes'])
            ->paginate(15);
    }

    public function hasAccessToAccept(Calibration $calibration, User $user): bool
    {
        return $user->factoryFloors->contains('factory_id', $calibration->checker_factory_id);
    }

    public function applicationHasBeenAccepted(Calibration $calibration): bool
    {
        return !$calibration->status->is(Status::NEW);
    }

    public function accept(Calibration $calibration, User $user): Calibration
    {
        $calibration->update([
            'checker_id' => $user->id,
            'status' => Status::PROCESS
        ]);

        return $calibration->load(['checker']);
    }

    public function hasPermissionToReact(Calibration $calibration, User $user): bool
    {
        return $calibration->checker_id === $user->id;
    }

    /**
     * @param Calibration $calibration
     * @param ApproveDTO $payload
     * @return Calibration
     */
    public function approve(Calibration $calibration, ApproveDTO $payload): Calibration
    {
        $calibration->loadMissing('device');
        return DB::transaction(function () use ($calibration, $payload) {
            $calibration->update([
                'comment' => $payload->comment,
                'status' => Status::REVIEWED,
                'result' => Result::APPROVED,
                'checked_at' => now()
            ]);

            $calibration->device->update([
                'last_checked_at' => now()
            ]);

            $calibration->addMedia($payload->document)->toMediaCollection(MediaCollection::REACT_DOCUMENT->value);

            return $calibration->load('media');
        });
    }

    /**
     * @param Calibration $calibration
     * @param RejectDTO $payload
     * @return Calibration
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function reject(Calibration $calibration, RejectDTO $payload): Calibration
    {
        $calibration->update([
            'comment' => $payload->comment,
            'status' => Status::REVIEWED,
            'result' => Result::REJECTED,
            'checked_at' => now()
        ]);

        $calibration->addMedia($payload->document)->toMediaCollection(MediaCollection::REACT_DOCUMENT->value);

        return $calibration->load('media');
    }
}
