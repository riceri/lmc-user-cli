<?php
namespace LmcUserCli\Command\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use LmcUserCli\Command\ListCommand;
use LmcUserCli\Mapper\UserMapper;
use LmcUserCli\Options\ModuleOptions;

class ListCommandFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userMapper  = $container->get(UserMapper::class);
        $userOptions = $container->get(ModuleOptions::class);

        return new ListCommand($userMapper, $userOptions);
    }
}
