<?php
namespace LmcUserCli;

use LmcUserCli\Command\ListCommand;
use LmcUserCli\Command\Factory\ListCommandFactory;
use LmcUserCli\Mapper\UserHydrator;
use LmcUserCli\Mapper\UserMapper;
use LmcUserCli\Mapper\Factory\UserHydratorFactory;
use LmcUserCli\Mapper\Factory\UserMapperFactory;
use LmcUserCli\Options\ModuleOptions;
use LmcUserCli\Options\Factory\ModuleOptionsFactory;

return [
    'laminas-cli' => [
        'commands' => [
            'user:list'     => ListCommand::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            ListCommand::class     => ListCommandFactory::class,
            ModuleOptions::class   => ModuleOptionsFactory::class,
            UserMapper::class      => UserMapperFactory::class,
            UserHydrator::class    => UserHydratorFactory::class,
        ],
    ],
];
