<?php

namespace Theokbokki\LaravelFilterSearch;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FilterQueryBuilder
{
    protected Builder $query;

    /** @param Builder<Model> $query */
    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    /**
     * @param array<int,array<int,SearchCondition>> $conditions
     * @param array<int,string> $allowedFields
     * @param array<int,string> $defaultFields
     */
    public function applyConditions(array $conditions, array $allowedFields, array $defaultFields): Builder
    {
        foreach ($conditions as $conditionGroup) {
            $this->query->where(function ($query) use ($conditionGroup, $allowedFields, $defaultFields) {
                foreach ($conditionGroup as $condition) {
                    if ($condition->field === 'default') {
                        $this->applyDefaultCondition($query, $condition, $defaultFields);
                    } elseif ($condition->matchesAllowedFields($allowedFields)) {
                        $this->applyFieldCondition($query, $condition);
                    }
                }
            });
        }

        return $this->query;
    }

    /** @param Builder<Model> $query */
    protected function applyFieldCondition(Builder $query, SearchCondition $condition): void
    {
        if ($condition->isNegative) {
            $query->whereNot($condition->field, 'like', '%' . $condition->value . '%');

            return;
        }

        $query->orWhere($condition->field, 'like', '%' . $condition->value . '%');
    }

    /**
 * @param Builder<Model> $query @param array<int,string> $fields */
    protected function applyDefaultCondition(Builder $query, SearchCondition $condition, array $fields): void
    {
        foreach ($fields as $field) {
            if ($condition->isNegative) {
                $query->whereNot($field, 'like', '%' . $condition->value . '%');
            } else {
                $query->orWhere($field, 'like', '%' . $condition->value . '%');
            }
        }
    }
}
