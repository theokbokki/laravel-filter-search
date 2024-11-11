<?php

namespace Theokbokki\LaravelFilterSearch;

class SearchParser
{
    /** @return array<int,SearchCondition> */
    public function parse(string $search): array
    {
        $conditions = [];
        $spaceSplit = preg_split('/\s+(?=(?:[^"]*"[^"]*")*[^"]*$)/', $search);

        foreach ($spaceSplit as $part) {
            $colonSplit = preg_split('/:(?=(?:[^"]*"[^"]*")*[^"]*$)/', $part);

            if (count($colonSplit) === 2) {
                $field = ltrim($colonSplit[0], '-');
                $isNegative = str_starts_with($colonSplit[0], '-') && $colonSplit[0][0] !== '"';
                $values = preg_split('/,(?=(?:[^"]*"[^"]*")*[^"]*$)/', $colonSplit[1]);

                foreach ($values as $value) {
                    $conditions[] = new SearchCondition($field, trim($value, '"'), $isNegative);
                }
            } else {
                $term = trim($part, '"');
                $isNegative = str_starts_with($term, '-') && $term[0] !== '"';
                $term = ltrim($term, '-');
                $conditions[] = new SearchCondition('default', $term, $isNegative);
            }
        }

        return $conditions;
    }
}
