<?php
return [
    'service_manager'    => [
        'abstract_factories' => [
            'ModuleLog\Service\LogAbstractServiceFactory',
            'ModuleLog\Writer\StreamAbstractServiceFactory',
            'ModuleLog\Writer\PhpFireAbstractServiceFactory'
        ]
    ]
];