<?php

namespace Ibrows\DoctrineDblibSqlDriver\Driver;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOConnection;
use Doctrine\DBAL\Schema\SQLServerSchemaManager;
use Doctrine\DBAL\Driver;
use Ibrows\DoctrineDblibSqlDriver\Platform\SQLServer2012Platform;
use Doctrine\DBAL\Platforms\SQLServerPlatform;
use Symfony\Component\Validator\Constraints\Null;

class PDODblibDriver implements Driver
{

    /**
     * @var array
     */
    protected $extraOptions = array();

    /**
     * Attempts to establish a connection with the underlying driver.
     *
     * @param  array         $params
     * @param  string        $username
     * @param  string        $password
     * @param  array         $driverOptions
     * @return PDOConnection
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = array())
    {
        $this->extraOptions = array();
        foreach($driverOptions as $key => $val){
            if(stripos($key,'EXTRA_') === 0){
                $this->extraOptions[substr($key,6)] = $val;
                unset($driverOptions[$key]);
            }
        }
        $dbPDO = new PDOConnection(
            $this->_constructPdoDsn($params),
            $username,
            $password,
            $driverOptions
        );
        foreach ($this->getExtraOptions('CONNECT_') as $optionName => $value) {
            $sql = "SET " . strtoupper($optionName);
            if ($value) {
                $sql .= " ON";
            } else {
                $sql .= " OFF";
            }
            $dbPDO->exec($sql);
        }
        return $dbPDO;
    }

    /**
     * @param null $prefix
     * @return array
     */
    public function getExtraOptions($prefix = null)
    {
        if($prefix == null){
            return $this->extraOptions;
        }
        $options = array();
        foreach ($this->extraOptions as $optionName => $optionValue) {
            if(stripos($optionName, $prefix) !== 0){
                continue;
            }
            $optionName = substr($optionName, strlen($prefix));
            $options[$optionName] = $optionValue;
        }

        return $options;
    }

    /**
     * Constructs the Dblib PDO DSN.
     *
     * @param  array  $params
     * @return string The DSN.
     */
    private function _constructPdoDsn(array $params)
    {
        $dsn = 'dblib:';
        if (isset($params['host'])) {
            $dsn .= 'host=' . $params['host'] . ';';
        }
        if (isset($params['port'])) {
            $dsn .= 'port=' . $params['port'] . ';';
        }
        if (isset($params['dbname'])) {
            $dsn .= 'dbname=' . $params['dbname'] . ';';
        }
        // Support charset config
        if (isset($params['charset'])) {
            $dsn .= 'charset=' . $params['charset'] .';';
        }

        return $dsn;
    }

    /**
     * @return SQLServerPlatform
     */
    public function getDatabasePlatform()
    {
        $platform =  new SQLServer2012Platform();
        $platform->setDriver($this);
        return $platform;
    }

    /**
     * @param  Connection             $conn
     * @return SQLServerSchemaManager
     */
    public function getSchemaManager(Connection $conn)
    {
        return new SQLServerSchemaManager($conn);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pdo_dblib';
    }

    /**
     * @param  Connection $conn
     * @return string
     */
    public function getDatabase(Connection $conn)
    {
        $params = $conn->getParams();
        return $params['dbname'];
    }
}
