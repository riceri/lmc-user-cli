<?php
namespace LmcUserCli\Command;

use Laminas\Cli\Command\AbstractParamAwareCommand;
use LmcUser\Mapper\UserInterface;
use LmcUser\Options\RegistrationOptionsInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

final class ShowCommand extends AbstractParamAwareCommand
{
    /** @var string */
    protected static $defaultName = 'user:show';
    /** @var string */
    protected static $defaultDescription = 'Show user info';
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
        $this->addArgument('identifier', InputArgument::REQUIRED, 'Search for id, username or email');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $identifier = $input->getArgument('identifier');
        $result = null;
        $foundBy = null;

        if (is_numeric($identifier)) {
            $result = $this->userMapper->findById($identifier);
            $foundBy = 'id';
        }

        if ($result === null) {
            $result = $this->userMapper->findByEmail($identifier);
            $foundBy = 'email';
        }

        if ($result === null && $this->userOptions->getEnableUsername()) {
            $result = $this->userMapper->findByUsername($identifier);
            $foundBy = 'username';
        }

        if ($result === null) {
            $output->writeln('<error>No user found!</error>');
            return Command::FAILURE;
        }

        $output->writeln('<info>Found user based on ' . $foundBy . '</info>');
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

        $tableData = [
            $result->getId(),
            $result->getEmail(),
        ];
        # If username is enabled we show it
        if ($this->userOptions->getEnableUsername()) {
            $tableData[] = $result->getUsername();
        }

        # If display name is enabled we show it
        if ($this->userOptions->getEnableDisplayName()) {
            $tableData[] = $result->getDisplayName();
        }

        # If state is enabled we show it
        if ($this->userOptions->getEnableUserState()) {
            $tableData[] = $result->getState();
        }

        $table->setHeaders($tableHeaders)->setRows([$tableData]);
        $table->render();

        return Command::SUCCESS;
    }
}
