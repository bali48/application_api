<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

return array(
    'statuslib' => array(
        // 'array_mapper_path' => 'path/to/PHP/file/returning/array.php',
    ),
    'service_manager' => array(
        'aliases' => array(
            'CoreLib\Mapper' => 'CoreLib\ArrayMapper',
        ),
        'factories' => array(
            'CoreLib\ArrayMapper'        => 'CoreLib\BaseResourceFactory' 
        ),
    ),
);
