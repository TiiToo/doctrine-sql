<?php

namespace Ibrows\DoctrineDblibSqlDriver\Platform;

use Doctrine\DBAL\Platforms\SQLServerPlatform;
use Doctrine\DBAL\Types\Type;
use Ibrows\DoctrineDblibSqlDriver\Type\DateTimeType;

class SQLServer2012Platform extends SQLServerPlatform
{
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
}