<?php

namespace Ibrows\DoctrineDblibSqlDriver\Platform;

use Doctrine\DBAL\Platforms\SQLServerPlatform;
use Ibrows\DoctrineDblibSqlDriver\Driver\PDODblibDriver;

class SQLServer2012Platform extends SQLServerPlatform
{
    /**
     * @var PDODblibDriver
     */
    protected $driver;

    /**
     * @return string
     */
    public function getDateTimeFormatString()
    {
        return 'M d Y H:i:s:000A';
    }

    /**
     * @return string
     */
    public function getDateFormatString()
    {
        return 'M d Y H:i:s:000A';
    }

    /**
     * @return string
     */
    public function getTimeFormatString()
    {
        return 'M d Y H:i:s:000A';
    }

    /**
     * @return bool
     */
    public function supportsLimitOffset()
    {
        return true;
    }


    /**
     * @param PDODblibDriver $driverOptions
     */
    public function setDriver(PDODblibDriver $driver)
    {
        $this->driver = $driver;
    }

    protected function _getCreateTableSQL($tableName, array $columns, array $options = array())
    {

        $sqls = parent::_getCreateTableSQL($tableName, $columns, $options);
        foreach ($this->getDriverOptions() as $optionName => $value) {
            if(stripos($optionName,'CREATE_') !== 0){
                continue;
            }
            $optionName = substr($optionName,7);
            $sql = "SET " . strtoupper($optionName);
            if ($value) {
                $sql .= " ON";
            } else {
                $sql .= " OFF";
            }
            array_unshift($sqls, $sql);
        }
        return $sqls;
    }
}
