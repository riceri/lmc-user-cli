<?php
namespace LmcUserCli\Command;

use Laminas\Cli\Command\AbstractParamAwareCommand;
use LmcUser\Mapper\UserInterface;
use LmcUser\Options\RegistrationOptionsInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ListCommand extends AbstractParamAwareCommand
{
    /** @var string */
    protected static $defaultName = 'user:list';
    /** @var string */
    protected static $defaultDescription = 'List users';
    /** @var LmcUserCli\Mapper\UserMapper */
    private $userMapper;
    /** @var LmcUserCli\Options\ModuleOptions */
    private $userOptions;

    public function __construct(
        UserInterface $userMapper,
        RegistrationOptionsInterface $userOptions
    ) {
        parent::__construct();
        $this->userMapper = $userMapper;
        $this->userOptions = $userOptions;
    }

    protected function configure() : void
    {
        $this->setName(self::$defaultName);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $results = $this->userMapper->fetchAll();

        $table = new Table($output);
        $tableHeaders = ['Id', 'Email'];

        # If username is enabled we show it
        if ($this->userOptions->getEnableUsername()) {
            $tableHeaders[] = 'Username';
        }

        # If display name is enabled we show it
        if ($this->userOptions->getEnableDisplayName()) {
            $tableHeaders[] = 'Display name';
        }

        # If state is enabled we show it
        if ($this->userOptions->getEnableUserState()) {
            $tableHeaders[] = 'State';
        }

        $tableData = [];

        foreach ($results as $result) {
            $data = [
                $result->getId(),
                $result->getEmail(),
            ];
            # If username is enabled we show it
            if ($this->userOptions->getEnableUsername()) {
                $data[] = $result->getUsername();
            }

            # If display name is enabled we show it
            if ($this->userOptions->getEnableDisplayName()) {
                $data[] = $result->getDisplayName();
            }

            # If state is enabled we show it
            if ($this->userOptions->getEnableUserState()) {
                $data[] = $result->getState();
            }

            $tableData[] = $data;
        }

        $table->setHeaders($tableHeaders)->setRows($tableData);
        $table->render();

        return Command::SUCCESS;
    }
}
