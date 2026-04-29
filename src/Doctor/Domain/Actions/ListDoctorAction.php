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
            ->allowedFilters('name')
            ->allowedSorts('name')
            ->with(['clinics' => fn (\Illuminate\Database\Eloquent\Relations\Relation $q) => $q->withCount('doctors')])
            ->orderBy('id', 'desc')
            ->paginate();
    }
}
