<?php

namespace Theokbokki\LaravelFilterSearch;

use Illuminate\Database\Eloquent\Builder;

trait HasFilterSearch
{
    public static function handleFilterSearch(string $search): Builder
    {
        $query = self::query();
        $allowedFields = self::allowedSearchFields();
        $defaultFields = self::defaultSearchFields();

        $parser = new SearchParser();
        $conditions = $parser->parse($search);

        $filterQueryBuilder = new FilterQueryBuilder($query);
        return $filterQueryBuilder->applyConditions($conditions, $allowedFields, $defaultFields);
    }

    /** @return array<int,string> */
    public static abstract function defaultSearchFields(): array;

    /** @return array<int,string> */
    public static abstract function allowedSearchFields(): array;
}
