<?php

declare(strict_types=1);

namespace Lightit\Appointment\Domain\Actions;

use Illuminate\Pagination\LengthAwarePaginator;
use Lightit\Appointment\Domain\Models\Appointment;
use Spatie\QueryBuilder\QueryBuilder;

class ListAppointmentAction
{
    /** @return LengthAwarePaginator<int, Appointment> */
    public function execute(): LengthAwarePaginator
    {
        return QueryBuilder::for(Appointment::class)
            ->allowedFilters(['doctor_id', 'clinic_id', 'id'])
            ->allowedSorts('date')
            ->orderBy('id', 'desc')
            ->paginate();
    }
}
