<?php

declare(strict_types=1);

namespace Lightit\Doctor\Domain\Actions;

use Illuminate\Pagination\LengthAwarePaginator;
use Lightit\Doctor\Domain\Models\Doctor;
use Spatie\QueryBuilder\QueryBuilder;

class ListDoctorAction
{
    /**
     * @return LengthAwarePaginator<int, Doctor>
     */
    public function execute(): LengthAwarePaginator
    {
        return QueryBuilder::for(Doctor::class)
            ->with(['clinics' => fn ($q) => $q->withCount('doctors')])
            ->allowedFilters('name')
            ->allowedSorts('name')
            ->orderBy('id', 'desc')
            ->paginate();
    }
}
