<?php
namespace LmcUserCli;

use LmcUserCli\Command\ListCommand;
use LmcUserCli\Command\PasswordCommand;
use LmcUserCli\Command\RegisterCommand;
use LmcUserCli\Command\RemoveCommand;
use LmcUserCli\Command\ShowCommand;
use LmcUserCli\Command\Factory\CommandFactory;
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
            'user:show'     => ShowCommand::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            ListCommand::class     => CommandFactory::class,
            PasswordCommand::class => CommandFactory::class,
            RegisterCommand::class => CommandFactory::class,
            RemoveCommand::class   => CommandFactory::class,
            ShowCommand::class     => CommandFactory::class,
            ModuleOptions::class   => ModuleOptionsFactory::class,
            UserMapper::class      => UserMapperFactory::class,
            UserHydrator::class    => UserHydratorFactory::class,
        ],
    ],
];
