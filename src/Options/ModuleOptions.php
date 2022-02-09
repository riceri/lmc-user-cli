<?php

namespace LmcUserCli\Options;

use LmcUser\Options\ModuleOptions as LmcModuleOptions;
use LmcUser\Options\UserControllerOptionsInterface;
use LmcUser\Options\UserServiceOptionsInterface;

class ModuleOptions extends LmcModuleOptions implements
    UserControllerOptionsInterface,
    UserServiceOptionsInterface
{
    /**
     * @var Array
     */
    protected $tableColumns = [
        'display_name' => 'display_name',
        'email'        => 'email',
        'user_id'      => 'user_id',
        'username'     => 'username',
        'password'     => 'password',
        'state'        => 'state',
    ];

    /**
     * get database columns
     *
     * @return Array
     */
    public function getTableColumns()
    {
        $columns = [];
        foreach ($this->tableColumns as $key => $value) {
            switch ($key) {
                case 'display_name':
                    if ($this->getEnableDisplayName()) {
                        $columns['display_name'] = $value;
                    }
                    break;
                case 'username':
                    if ($this->getEnableUsername()) {
                        $columns['username'] = $value;
                    }
                    break;
                case 'state':
                    if ($this->getEnableUserState()) {
                        $columns['state'] = $value;
                    }
                    break;
                default:
                    $columns[$key] = $value;
            }
        }
        return $columns;
    }

    /**
     * set user table columns
     *
     * @param Array $tableColumns
     */
    public function setTableColumns($tableColumns)
    {
        $this->tableColumns = $tableColumns;
        return $this;
    }
}
