<?php

namespace App\Services;

use App\DTOs\Application\ApplicationDTO;
use App\DTOs\Application\FilterDTO;
use App\Enums\Calibration\MediaCollection;
use App\Enums\FactoryDevice\Position;
use App\Models\Calibration;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

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
            ->with(['media', 'applicant', 'checker', 'applicantFactory', 'checkerFactory', 'device.device'])
            ->paginate(15);
    }

    public function create(ApplicationDTO $payload): Calibration
    {
        return DB::transaction(function () use ($payload) {
            /** @var Calibration $application */
            $application = Calibration::query()->create(Arr::except((array)$payload, 'document'));

            $this->factoryDeviceService->updateStatus($payload->factory_device_id, Position::CHECKING);

            $application->addMedia($payload->document)->toMediaCollection(MediaCollection::DOCUMENT->value);

            return $application->load(['applicant', 'checker', 'applicantFactory', 'checkerFactory', 'device.device']);
        });
    }

    public function update(Calibration $application, ApplicationDTO $payload): Calibration
    {
        return DB::transaction(function () use ($application, $payload) {
            $application->update(Arr::except((array)$payload, 'document'));

            if ($payload->document) {
                $collection = MediaCollection::DOCUMENT->value;

                if ($file = $application->getFirstMedia($collection)) {
                    $file->delete();
                }

                $application->addMedia($payload->document)->toMediaCollection($collection);
            }

            return $application->load(['applicant', 'checker', 'applicantFactory', 'checkerFactory', 'device.device']);
        });
    }

    public function hasActiveDeviceStatus(int $factory_device_id): bool
    {
        return Calibration::query()
            ->where('factory_device_id', $factory_device_id)
            ->whereHas(
                relation: 'device',
                callback: fn(Builder $builder) => $builder->whereIn('position', [Position::ON_PLACE, Position::WAREHOUSE])
            )
            ->exists();
    }
}
