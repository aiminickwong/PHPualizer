<?php
namespace PHPualizer\Database;


use \r;

use PHPualizer\Util\Config;

class Rethink
{
    private $m_Connection;
    private $m_Table;
    private $m_Config;

    public function getTable(): string
    {
        return $this->m_Table;
    }

    public function setTable(string $table)
    {
        if($this->createTableIfNotExists($table))
            $this->m_Table = $table;
        else
            throw new \InvalidArgumentException;
    }

    public function __construct()
    {
        $this->m_Config = Config::getConfigData()['database'];

        if(!isset($this->m_Connection)) {
            $this->m_Connection = r\connect($this->m_Config);

            if(!$this->createDatabaseIfNotExists($this->m_Config['db']))
                throw new \InvalidArgumentException;
        }
    }

    public function getDocuments(array $filter, int $nth = null): array
    {
        if(is_null($nth)) {
            return r\table($this->m_Table)->filter($filter)->run($this->m_Connection)->toArray();
        } else {
            return (array)r\table($this->m_Table)->filter($filter)->nth($nth)->run($this->m_Connection);
        }
    }

    public function deleteDocuments(array $filter): bool
    {
        return (r\table($this->m_Table)->filter($filter)->delete()->run($this->m_Connection)->deleted >= 1) ? true : false;
    }

    public function insertDocuments(array $documents): bool
    {
        $insert = r\table($this->m_Table)->insert($documents)->run($this->m_Connection);
        return ($insert->inserted >= 1 || $insert->replaced >= 1) ? true : false;
    }

    public function updateDocuments(array $documents, array $filter): bool
    {
        $update = r\table($this->m_Table)->filter($filter)->update($documents)->run($this->m_Connection);
        return ($update->inserted >= 1 || $update->replaced >= 1) ? true : false;
    }

    private function createTableIfNotExists(string $tableName): bool
    {
        if(!r\tableList()->contains($tableName)->run($this->m_Connection)) {
            if(r\tableCreate($tableName)->run($this->m_Connection)['tables_created'] == 1)
                return true;
            else
                return false;
        } else {
            return true;
        }
    }

    private function createDatabaseIfNotExists(string $dbName): bool
    {
        if(!r\dbList()->contains($dbName)->run($this->m_Connection)) {
            if(r\dbCreate($dbName)->run($this->m_Connection)['dbs_created'] == 1)
                return true;
            else
                return false;
        } else {
            return true;
        }
    }

    public function __destruct()
    {
        unset($this->m_Connection);
    }
}