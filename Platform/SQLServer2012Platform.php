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
     * @param PDODblibDriver $driver
     */
    public function setDriver(PDODblibDriver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param string $prefix
     * @return array
     */
    protected function getDriverExtraOptions($prefix = 'CREATE_')
    {
        if(!$this->driver){
            return array();
        }

        $options = array();

        foreach ($this->driver->getExtraOptions() as $optionName => $optionValue) {
            if(stripos($optionName, $prefix) !== 0){
                continue;
            }
            $optionName = substr($optionName, strlen($prefix));
            $options[$optionName] = $optionValue;
        }

        return $options;
    }

    /**
     * @param string $tableName
     * @param array $columns
     * @param array $options
     * @return array
     */
    protected function _getCreateTableSQL($tableName, array $columns, array $options = array())
    {
        $sqls = parent::_getCreateTableSQL($tableName, $columns, $options);
        foreach ($this->getDriverExtraOptions() as $optionName => $value) {
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
