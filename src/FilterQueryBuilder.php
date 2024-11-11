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
     * @param array<int,SearchCondition> $conditions
     * @param array<int,string> $allowedFields
     * @param array<int,string> $defaultFields
     */
    public function applyConditions(array $conditions, array $allowedFields, array $defaultFields): Builder
    {
        foreach ($conditions as $condition) {
            if ($condition->field === 'default') {
                $this->applyDefaultCondition($condition, $defaultFields);
            } elseif ($condition->matchesAllowedFields($allowedFields)) {
                $this->applyFieldCondition($condition);
            }
        }

        return $this->query;
    }

    protected function applyFieldCondition(SearchCondition $condition): void
    {
        if ($condition->isNegative) {
            $this->query->orWhere(function ($query) use ($condition) {
                $query->where($condition->field, 'not like', '%' . $condition->value . '%');
            });
        } else {
            $this->query->orWhere($condition->field, 'like', '%' . $condition->value . '%');
        }
    }

    /** @param array<int,string> $fields */
    protected function applyDefaultCondition(SearchCondition $condition, array $fields): void
    {
        $this->query->where(function ($query) use ($condition, $fields) {
            foreach ($fields as $field) {
                if($condition->isNegative) {
                    $query->where($field, 'not like', '%' . $condition->value . '%');
                } else {
                    $query->orWhere($field, 'like', '%' . $condition->value . '%');
                }
            }
        });
    }
}
