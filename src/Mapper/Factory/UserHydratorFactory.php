<?php

namespace LmcUserCli\Mapper\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use LmcUserCli\Mapper\UserHydrator;
use LmcUserCli\Options\ModuleOptions;

/**
 * Class UserHydrator
 */
class UserHydratorFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @see \Laminas\ServiceManager\Factory\FactoryInterface::__invoke()
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $hydrator = $container->get('lmcuser_base_hydrator');
        $userOptions = $container->get(ModuleOptions::class);

        return new UserHydrator($hydrator, $userOptions);
    }
}
