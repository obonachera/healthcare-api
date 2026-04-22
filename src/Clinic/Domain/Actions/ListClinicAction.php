<?php

declare(strict_types=1);

namespace Lightit\Clinic\Domain\Actions;

use Illuminate\Pagination\LengthAwarePaginator;
use Lightit\Clinic\Domain\Models\Clinic;
use Spatie\QueryBuilder\QueryBuilder;

class ListClinicAction
{
    /**
     * @return LengthAwarePaginator<int, Clinic>
     */
    public function execute(): LengthAwarePaginator
    {
        return QueryBuilder::for(Clinic::query())
            ->allowedFilters(['name'])
            ->allowedSorts(['name'])
            ->orderBy('id', 'desc')
            ->paginate();
    }
}
