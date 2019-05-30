<?php


namespace App\Entity;


use Symfony\Component\HttpFoundation\Response;

abstract class RequestBuilder
{
    protected $first_where_condition = true;
    protected $query = "";
    protected $query_parameters = [];
    protected $valid_request = false;

    protected function execQuery()
    {
        $db = SModel::getInstance();
        $prep = $db->prepare($this->query);

        foreach ($this->query_parameters as $key => $value) {
            $prep->bindValue($key, $value);
        }

        $prep->execute();

        if ($prep->errorCode() != "00000") {
            return [
              "error_msg" =>  "Incorrect SQL request: " . $prep->errorInfo()[2],
                "error_status" => Response::HTTP_BAD_REQUEST
            ];
        }

        $res = $prep->fetchAll(\PDO::FETCH_ASSOC);

        return $res;
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

    protected function fetchIdData(array $response, array $tables, int $depth = 1)
    {
        if ($depth > 0) {
            foreach ($response as $idx => $data) {
                foreach ($data as $key => $val) {
                    if (substr($key, -3) == '_id') {
                        $key_name = substr($key, 0, strlen($key) - 3);
                        $table_name = '';
                        if (in_array($key_name, $tables)) {
                            $table_name = $key_name;
                        } else if (array_key_exists($key_name, $tables)) {
                            $table_name = $tables[$key_name];
                        } else {
                            continue;
                        }

                        $this->query_parameters = [];
                        $this->first_where_condition = true;
                        $this->query = "SELECT * FROM " . $table_name;
                        $this->addWhereCondition("id = :id");
                        $this->query_parameters["id"] = $val;
                        $response[$idx][$key_name] = $this->execQuery();
                        unset($response[$idx][$key]);

                        if ($depth > 0) {
                            $res = $this->fetchIdData($response[$idx][$key_name], $tables, $depth - 1);
                            if (count($res) == 1) {
                                $response[$idx][$key_name] = $res[0];
                            }
                        }
                    }
                }
            }
        }

        return $response;
    }
}