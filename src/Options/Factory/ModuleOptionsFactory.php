<?php

namespace LmcUserCli\Options\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use LmcUserCli\Options\ModuleOptions;

class ModuleOptionsFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');

        return new ModuleOptions(isset($config['lmcuser']) ? $config['lmcuser'] : []);
    }
}
