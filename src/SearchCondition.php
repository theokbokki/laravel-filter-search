<?php

namespace Theokbokki\LaravelFilterSearch;

class SearchCondition
{
    public function __construct(
        public string $field,
        public string $value,
        public bool $isNegative = false
    ) {}

    /** @param array<int,string> $allowedFields */
    public function matchesAllowedFields(array $allowedFields): bool
    {
        return in_array($this->field, $allowedFields);
    }
}
