<?php
namespace LmcUserCli\Mapper;

use Laminas\Hydrator\HydratorInterface;
use LmcUser\Entity\UserInterface as UserEntityInterface;
use LmcUser\Mapper\Exception\InvalidArgumentException;
use LmcUser\Options\RegistrationOptionsInterface;

/**
 * Class UserHydrator
 */
class UserHydrator implements HydratorInterface
{
    /** @var HydratorInterface */
    private $hydrator;

    /** @var LmcUserCli\Options\ModuleOptions */
    private $userOptions;

    /**
     * UserHydrator constructor.
     *
     * @param HydratorInterface $hydrator
     * @param RegistrationOptionsInterface $userOption
     */
    public function __construct(
        HydratorInterface $hydrator,
        RegistrationOptionsInterface $userOptions
    ) {
        $this->hydrator = $hydrator;
        $this->userOptions = $userOptions;
    }

    /**
     * Extract values from an object
     *
     * @param  UserEntityInterface $object
     * @return array
     * @throws Exception\InvalidArgumentException
     */
    public function extract($object): array
    {
        if (! $object instanceof UserEntityInterface) {
            throw new InvalidArgumentException('$object must be an instance of LmcUser\Entity\UserInterface');
        }

        $columns = $this->userOptions->getTableColumns();

        $data = $this->hydrator->extract($object);

        if ($data['id'] !== null) {
            $data = $this->mapField('id', $columns['user_id'], $data);
        } else {
            unset($data['id']);
        }

        $data = $this->mapField('email', $columns['email'], $data);
        $data = $this->mapField('password', $columns['password'], $data);
        if ($this->userOptions->getEnableUsername()) {
            $data = $this->mapField('username', $columns['username'], $data);
        } else {
            unset($data['username']);
        }

        if ($this->userOptions->getEnableDisplayName()) {
            $data = $this->mapField('display_name', $columns['display_name'], $data);
        } else {
            unset($data['display_name']);
        }

        if ($this->userOptions->getEnableUserState()) {
            $data = $this->mapField('state', $columns['state'], $data);
        } else {
            unset($data['state']);
        }

        return $data;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array               $data
     * @param  UserEntityInterface $object
     * @return UserInterface
     * @throws InvalidArgumentException
     */
    public function hydrate(array $data, $object)
    {
        if (! $object instanceof UserEntityInterface) {
            throw new InvalidArgumentException('$object must be an instance of LmcUser\Entity\UserInterface');
        }

        $data = $this->mapField('user_id', 'id', $data);

        return $this->hydrator->hydrate($data, $object);
    }

    /**
     * @param  string $keyFrom
     * @param  string $keyTo
     * @param  array  $array
     * @return array
     */
    protected function mapField($keyFrom, $keyTo, array $array)
    {
        if ($keyFrom === $keyTo) {
            return $array;
        }
        $array[$keyTo] = $array[$keyFrom];
        unset($array[$keyFrom]);

        return $array;
    }
}
