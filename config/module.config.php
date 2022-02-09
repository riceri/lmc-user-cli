<?php
namespace LmcUserCli;

use LmcUserCli\Command\ListCommand;
use LmcUserCli\Command\PasswordCommand;
use LmcUserCli\Command\RegisterCommand;
use LmcUserCli\Command\RemoveCommand;
use LmcUserCli\Command\Factory\ListCommandFactory;
use LmcUserCli\Command\Factory\PasswordCommandFactory;
use LmcUserCli\Command\Factory\RegisterCommandFactory;
use LmcUserCli\Command\Factory\RemoveCommandFactory;
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
            'user:password' => PasswordCommand::class,
            'user:register' => RegisterCommand::class,
            'user:remove'   => RemoveCommand::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            ListCommand::class     => ListCommandFactory::class,
            PasswordCommand::class => PasswordCommandFactory::class,
            RegisterCommand::class => RegisterCommandFactory::class,
            RemoveCommand::class   => RemoveCommandFactory::class,
            ModuleOptions::class   => ModuleOptionsFactory::class,
            UserMapper::class      => UserMapperFactory::class,
            UserHydrator::class    => UserHydratorFactory::class,
        ],
    ],
];
