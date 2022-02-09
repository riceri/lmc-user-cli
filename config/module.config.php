<?php
namespace LmcUserCli;

use LmcUserCli\Mapper\UserHydrator;
use LmcUserCli\Mapper\UserMapper;
use LmcUserCli\Mapper\Factory\UserHydratorFactory;
use LmcUserCli\Mapper\Factory\UserMapperFactory;
use LmcUserCli\Options\ModuleOptions;
use LmcUserCli\Options\Factory\ModuleOptionsFactory;

return [
    'service_manager' => [
        'factories' => [
            ModuleOptions::class   => ModuleOptionsFactory::class,
            UserMapper::class      => UserMapperFactory::class,
            UserHydrator::class    => UserHydratorFactory::class,
        ],
    ],
];
