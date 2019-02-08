<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'user_entities' => array(
                'class' => 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    0 => 'E:\\wamp\\www\\myapp\\module\\user\\config../src/User',
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    'User' => 'user_entities',
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'user\\V1\\Rest\\User\\UserResource' => 'user\\V1\\Rest\\User\\UserResourceFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'user.rest.user' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/user/v1/user[/:user_id]',
                    'defaults' => array(
                        'controller' => 'user\\V1\\Rest\\User\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'user.rest.user',
        ),
    ),
    'zf-rest' => array(
        'user\\V1\\Rest\\User\\Controller' => array(
            'listener' => 'user\\V1\\Rest\\User\\UserResource',
            'route_name' => 'user.rest.user',
            'route_identifier_name' => 'user_id',
            'collection_name' => 'user',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
                4 => 'POST',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
                2 => 'PUT',
                3 => 'DELETE',
                4 => 'PATCH',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'user\\V1\\Rest\\User\\UserEntity',
            'collection_class' => 'user\\V1\\Rest\\User\\UserCollection',
            'service_name' => 'user',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'user\\V1\\Rest\\User\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'user\\V1\\Rest\\User\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'user\\V1\\Rest\\User\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'user\\V1\\Rest\\User\\UserEntity' => array(
                'entity_identifier_name' => 'UserID',
                'route_name' => 'user.rest.user',
                'route_identifier_name' => 'user_id',
                'hydrator' => 'DoctrineModule\\Stdlib\\Hydrator\\DoctrineObject',
            ),
            'user\\V1\\Rest\\User\\UserCollection' => array(
                'entity_identifier_name' => 'UserID',
                'route_name' => 'user.rest.user',
                'route_identifier_name' => 'user_id',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-mvc-auth' => array(
        'authorization' => array(
            'user\\V1\\Rest\\User\\Controller' => array(
                'collection' => array(
                    'GET' => true,
                    'POST' => true,
                    'PUT' => true,
                    'PATCH' => true,
                    'DELETE' => true,
                ),
                'entity' => array(
                    'GET' => true,
                    'POST' => true,
                    'PUT' => true,
                    'PATCH' => true,
                    'DELETE' => true,
                ),
            ),
        ),
        'authentication' => array(
            'map' => array(
                'user\\V1' => 'ZF\\MvcAuth\\Authentication\\OAuth2Adapter',
            ),
        ),
    ),
    'zf-content-validation' => array(
        'user\\V1\\Rest\\User\\Controller' => array(
            'input_filter' => 'user\\V1\\Rest\\User\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'user\\V1\\Rest\\User\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'UserName',
                'error_message' => 'User Name is Required',
            ),
            1 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'UserEmail',
                'error_message' => 'User Email is Required',
            ),
            2 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'PlainPassword',
                'error_message' => 'Plain Password is Required',
            ),
            3 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'isactive',
                'error_message' => 'is active is Required',
            ),
        ),
    ),
);
