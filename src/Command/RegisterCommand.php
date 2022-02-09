<?php
namespace LmcUserCli\Command;

use Laminas\Cli\Command\AbstractParamAwareCommand;
use Laminas\Crypt\Password\Bcrypt;
use LmcUser\Entity\User;
use LmcUser\Mapper\UserInterface;
use LmcUser\Options\RegistrationOptionsInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

final class RegisterCommand extends AbstractParamAwareCommand
{
    /** @var string */
    protected static $defaultName = 'user:register';
    /** @var string */
    protected static $defaultDescription = 'Create a new user';
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
        $this->addArgument('email', InputArgument::REQUIRED, 'E-mail of the user');
        $this->addArgument('username', InputArgument::REQUIRED, 'New password for user');
        $this->addArgument('name', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'New password for user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = new User;

        $user->setEmail($input->getArgument('email'));

        if ($this->userOptions->getEnableUsername()) {
            $user->setUsername($input->getArgument('username'));
        }

        if ($this->userOptions->getEnableDisplayName()) {
            $user->setDisplayName(implode(' ', $input->getArgument('name')));
        }

        if ($this->userOptions->getEnableUserState()) {
            $user->setState($this->userOptions->getDefaultUserState());
        }

        $comb = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!#$%&=";
        $shfl = str_shuffle($comb);
        $newPassword = substr($shfl, 0, 12);

        $bcrypt = new Bcrypt();
        $bcrypt->setCost($this->userOptions->getPasswordCost());

        $pass = $bcrypt->create($newPassword);
        $user->setPassword($pass);

        $result = $this->userMapper->insert($user);

        $output->writeln('Added user with email ' . $user->getEmail() . ', and password ' . $newPassword);

        return Command::SUCCESS;
    }
}
