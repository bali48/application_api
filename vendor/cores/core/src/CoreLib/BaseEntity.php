<?php

namespace CoreLib;

use Doctrine\DBAL\Types\Type;

class BaseEntity {

    public function __construct() {
        if (strtolower(PHP_OS) == 'linux') {
            Type::overrideType('datetime', 'Doctrine\DBAL\Types\VarDateTimeType');
            Type::overrideType('datetimetz', 'Doctrine\DBAL\Types\VarDateTimeType');
            Type::overrideType('time', 'Doctrine\DBAL\Types\VarDateTimeType');
        }
    }

}
