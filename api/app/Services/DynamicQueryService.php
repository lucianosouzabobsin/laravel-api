<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class DynamicQueryService
{
    /**
     * Consulta por campos
     *
     * Exemplo de uso do filter
     * $filters = [
     *   ['field' => 'idade', 'operator' => '>', 'value' => 25],
     *   ['field' => 'cidade', 'operator' => '=', 'value' => 'São Paulo'],
     *   ['field' => 'nome', 'operator' => 'LIKE', 'value' => 'São'],
     * ];
     * Exemplo de uso do options
     * $options = [
     *   'sortBy' => ['id', 'name'],
     *   'sortDirection' => ['desc', 'asc']
     * ];
     *
     * @return EloquentBuilder
    */
    public function buildQuery(EloquentBuilder $query, array $filters, array $options = [])
    {
        foreach ($filters as $filter) {
            $field = $filter['field'];
            $operator = $filter['operator'];
            $value = $filter['value'];

            if (strtoupper($operator) == 'LIKE') {
                $searchTerm = '%' . $value . '%';
                $query->where($field, $operator, $searchTerm);
            } else {
                $query->where($field, $operator, $value);
            }
        }

        if(isset($options['sortBy']) && isset($options['sortDirection'])) {
            foreach ($options['sortBy'] as $key => $sortBy) {
                $query->orderBy($sortBy, $options['sortDirection'][$key]);
            }
        }

        if(isset($options['perPage']) && isset($options['page'])) {
            $query->offset(($options['page']) * $options['perPage']);
            $query->limit($options['perPage']);
        }

        return $query;
    }
}
