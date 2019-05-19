<?php


namespace App\Entity;


abstract class RequestBuilder
{
    protected $first_where_condition = true;
    protected $query = "";
    protected $query_parameters = [];

    protected function execQuery()
    {
        $db = SModel::getInstance();
        $prep = $db->prepare($this->query);

        foreach ($this->query_parameters as $key => $value) {
            $prep->bindValue($key, $value);
        }

        $prep->execute();
        return $prep->fetchAll(\PDO::FETCH_ASSOC);
    }

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