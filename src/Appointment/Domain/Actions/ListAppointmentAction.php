<?php

declare(strict_types=1);

namespace Lightit\Appointment\Domain\Actions;

use Illuminate\Pagination\LengthAwarePaginator;
use Lightit\Appointment\Domain\Models\Appointment;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ListAppointmentAction
{
    /** @return LengthAwarePaginator<int, Appointment> */
    public function execute(): LengthAwarePaginator
    {
        return QueryBuilder::for(Appointment::class)
            ->allowedFilters([
                AllowedFilter::exact('doctor_id'),
                AllowedFilter::exact('clinic_id'),
                AllowedFilter::exact('id'),
            ])
            ->allowedSorts('date')
            ->orderBy('id', 'desc')
            ->paginate();
    }
}
