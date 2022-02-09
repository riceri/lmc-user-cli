<?php
namespace LmcUserCli\Command;

use Laminas\Cli\Command\AbstractParamAwareCommand;
use Laminas\Crypt\Password\Bcrypt;
use LmcUser\Mapper\UserInterface;
use LmcUser\Options\RegistrationOptionsInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

final class PasswordCommand extends AbstractParamAwareCommand
{
    /** @var string */
    protected static $defaultName = 'user:password';
    /** @var string */
    protected static $defaultDescription = 'Change the password for a user';
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
        $this->addArgument('password', InputArgument::OPTIONAL, 'New password for the user');
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
            'Do you want to change the password for user <info>' . $user->getEmail() . '</info>? (y/N) ',
            false
        );

        if (! $helper->ask($input, $output, $question)) {
            $output->writeln('<error>Command aborted by user</error>');
            return Command::FAILURE;
        }

        $newPassword = $input->getArgument('password');
        if (! $newPassword) {
            $comb = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!#$%&=";
            $shfl = str_shuffle($comb);
            $newPassword = substr($shfl, 0, 12);
        }
        $bcrypt = new Bcrypt();
        $bcrypt->setCost($this->userOptions->getPasswordCost());

        $pass = $bcrypt->create($newPassword);
        $user->setPassword($pass);

        $this->userMapper->update($user);

        $output->writeln('The users password have been updated to: <info>' . $newPassword . '</info>');

        return Command::SUCCESS;
    }
}
