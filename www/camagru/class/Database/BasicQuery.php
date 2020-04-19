<?php
namespace Clas\Database;
class BasicQuery{
    private $db;
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }
    public function execute(string $query, $params)
    {
        $tmp = $this->db->prepare($query);
        if ($tmp->execute($params))
        {
            return($tmp);
        }
    }
}