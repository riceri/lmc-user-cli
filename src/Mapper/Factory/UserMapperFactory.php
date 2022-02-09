<?php

namespace LmcUserCli\Mapper\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use LmcUserCli\Mapper\UserHydrator;
use LmcUserCli\Mapper\UserMapper;
use LmcUserCli\Options\ModuleOptions;

class UserMapperFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @see \Laminas\ServiceManager\Factory\FactoryInterface::__invoke()
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /**
         * @var ModuleOptions $options
         */
        $options = $container->get(ModuleOptions::class);
        $dbAdapter = $container->get('lmcuser_laminas_db_adapter');
        $hydrator = $container->get(UserHydrator::class);

        $entityClass = $options->getUserEntityClass();
        $tableName = $options->getTableName();
        $tableColumns = $options->getTableColumns();

        $mapper = new UserMapper();
        $mapper->setDbAdapter($dbAdapter);
        $mapper->setTableName($tableName);
        $mapper->setTableColumns($tableColumns);
        $mapper->setEntityPrototype(new $entityClass);
        $mapper->setHydrator($hydrator);

        return $mapper;
    }
}
