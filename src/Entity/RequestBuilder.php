<?php


namespace App\Entity;


abstract class RequestBuilder
{
    protected $first_where_condition = true;
    protected $query = "";
    protected $query_parameters = [];

    protected function addWhereCondition(string $condition)
    {
        if ($this->first_where_condition) {
            $this->first_where_condition = false;
            $this->query .= " WHERE ";
        } else {
            $this->query .= " AND ";
        }

        $this->query .= $condition;
    }
}