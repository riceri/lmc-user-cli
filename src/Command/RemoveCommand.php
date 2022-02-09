<?php
namespace LmcUserCli\Command;

use Laminas\Cli\Command\AbstractParamAwareCommand;
use LmcUser\Mapper\UserInterface;
use LmcUser\Options\RegistrationOptionsInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

final class RemoveCommand extends AbstractParamAwareCommand
{
    /** @var string */
    protected static $defaultName = 'user:remove';
    /** @var string */
    protected static $defaultDescription = 'Remove user';
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
        $this->addArgument('identifier', InputArgument::REQUIRED, 'Id, email or username of the user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $identifier = $input->getArgument('identifier');

        $user = null;
        if (is_numeric($identifier)) {
            $user = $this->userMapper->findById($identifier);
        }

        if ($user === null) {
            $user = $this->userMapper->findByEmail($identifier);
        }

        if ($user === null && $this->userOptions->getEnableUsername()) {
            $user = $this->userMapper->findByUsername($identifier);
        }

        if ($user === null) {
            $output->writeln('<error>No user found!</error>');
            return Command::FAILURE;
        }

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            'Do you want to remove the user <info>' . $user->getEmail() . '</info>? (y/N) ',
            false
        );

        if (! $helper->ask($input, $output, $question)) {
            $output->writeln('<error>Command aborted by user</error>');
            return Command::FAILURE;
        }

        $this->userMapper->delete(
            [$this->userOptions->getTableColumns()['user_id'] => $user->getId()],
            $this->userOptions->getTableName()
        );

        $output->writeln('The users have been removed');

        return Command::SUCCESS;
    }
}
