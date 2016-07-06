<?php
namespace PHPualizer\Database;


use \PDO;
use \PDOException;

use PHPualizer\Util\Config;
use Psr\Log\InvalidArgumentException;

class SQL
{
    private $m_PDO;
    private $m_Table;
    private $m_Config;

    public function getTable(): string
    {
        return $this->m_Table;
    }

    public function setTable(string $table)
    {
        $this->m_Table = $table;
    }

    public function __construct()
    {
        $this->m_Config = Config::getConfigData()['database'];

        if(!isset($this->m_PDO)) {
            try {
                if(isset($this->m_Config['password']) && $this->m_Config['password'] != '') {
                    $this->m_PDO = new PDO('mysql:dbname=' . $this->m_Config["db"] . ';host=' . $this->m_Config["host"] . ';port=' . $this->m_Config["port"],
                        $this->m_Config['user'], $this->m_Config['password']);
                } else {
                    $this->m_PDO = new PDO('mysql:dbname=' . $this->m_Config["db"] . ';host=' . $this->m_Config["host"] . ';port=' . $this->m_Config["port"],
                        $this->m_Config['user']);
                }
            } catch(PDOException $e) {
                throw new \InvalidArgumentException($e->getMessage());
            }
        }
    }

    public function getDocuments(array $filter, int $nth = null): array
    {
        $q_length = count($filter);
        $index = 0;
        $qs = 'SELECT * FROM ' . $this->m_Table . ' WHERE ';

        foreach($filter as $key => $val) {
            if($index < $q_length && $index > 0)
                $qs .= ' AND ';

            $qs .= $key . '=:' . $key;
            
            $index++;
        }

        $qs .= ';';

        $query = $this->m_PDO->prepare($qs);

        foreach($filter as $key => $val) {
            $query->bindValue(":$key", $val);
        }
        
        $query->execute();

        return (array)$query->fetchObject();
    }

    public function deleteDocuments(array $filter): bool
    {
        $q_length = count($filter);
        $index = 0;
        $qs = 'DELETE FROM ' . $this->m_Table . ' WHERE ';

        foreach($filter as $key => $val) {
            if($index < $q_length && $index > 0)
                $qs .= ' AND ';

            $qs .= "`$key`" . '=:' . $key;

            $index++;
        }

        $qs .= ';';

        $query = $this->m_PDO->prepare($qs);

        foreach($filter as $key => $val) {
            $query->bindValue(":$key", $val);
        }

        $query->execute();
    }

    public function insertDocuments(array $documents): bool
    {
        $s_length = count($documents);
        $index = 0;
        $qs = "INSERT INTO $this->m_Table (";
        
        foreach($documents as $key => $val) {
            if($index < $s_length && $index > 0)
                $qs .= ', ';
            
            $qs .= $key;
            
            $index++;
        }
        
        $index = 0;
        $qs .= ') VALUES (';
        
        foreach($documents as $key => $val) {
            if($index < $s_length && $index > 0)
                $qs .= ', ';

            $qs .= ":$key";

            $index++;
        }

        $qs .= ');';

        $query = $this->m_PDO->prepare($qs);

        foreach($documents as $key => $val) {
            $query->bindValue(":$key", $val);
        }

        return $query->execute();
    }

    public function updateDocuments(array $documents, array $filter): bool
    {
        $f_length = count($filter);
        $d_length = count($documents);
        $index = 0;
        $qs = 'UPDATE ' . $this->m_Table . ' SET ';

        foreach($documents as $key => $val) {
            if($index < $d_length && $index > 0)
                $qs .= ', ';

            $qs .= $key . '=:' . $key;

            $index++;
        }

        $index = 0;
        $qs .= ' WHERE ';

        foreach($filter as $key => $val) {
            if($index < $f_length && $index > 0)
                $qs .= ' AND ';

            $qs .= $key . '=:' . $key . 'filter';

            $index++;
        }

        $qs .= ';';

        $query = $this->m_PDO->prepare($qs);

        foreach($documents as $key => $val) {
            $query->bindValue(":$key", $val);
        }

        foreach($filter as $key => $val) {
            $query->bindValue(':' . $key . 'filter', $val);
        }

        return $query->execute();
    }

    public function __destruct()
    {
        unset($this->m_PDO);
    }
}