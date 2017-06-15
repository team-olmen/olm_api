<?php
namespace OlmServer;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Doctrine\DBAL\Connection;

class UserManager implements UserProviderInterface {
	/**
	 * Connection to the database.
	 *
	 * @var Doctrine\DBAL\Connection
	 */
	private $connection;

	/**
	 * Encoder used to encode passwords.
	 *
	 * @var Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder
	 */
	private $encoder;

	/**
	 * String to prefix database tablenames with.
	 *
	 * @var string
	 */
	private $prefix;

	/**
	 * Constructor.
	 *
	 * @param Doctrine\DBAL\Connection $connection connection to the database
	 * @param Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder $encoder encoder used for passwords
	 * @param string $prefix a string to prefix the tablenames in the database with
	 * @return void
	 */
	public function __construct(\Doctrine\DBAL\Connection $connection, \Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder $encoder, $prefix) {
		$this->connection = $connection;
		$this->encoder = $encoder;
		$this->prefix = $prefix;
	}

	public function loadUserByUsername($username) {
		$stmt = $this->connection->executeQuery('SELECT * FROM ' . $this->prefix . 'users WHERE username = ?', array($username));

		if (!$data = $stmt->fetch()) {
			return null;//throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
		}

		$user = new \OlmServer\User($data['username'], $data['password'], explode(',', $data['roles']), $data['enabled'], $data['account_non_expired'], $data['credentials_non_expired'], $data['account_non_locked']);
		$user->initiate($data);
		return $user;
	}

	public function refreshUser(UserInterface $user) {
		if (!$user instanceof User) {
			throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
		}

		return $this->loadUserByUsername($user->getUsername());
	}

	public function supportsClass($class) {
		return $class === 'OlmServer\User';
	}

	/**
	 * Encodes the password.
	 *
	 * @param string $password the string to encode
	 * @param string $salt the salt
	 * @return string
	 */
	public function encodePassword(string $password, string $salt = null) {
		return $this->encoder->encodePassword($password, $salt);
	}

	/**
	 * Checks if a password is valid.
	 *
	 * @param string $passwordRaw the raw password
	 * @param string $passwordEnc encrypted password
	 * @return bool
	 */
	public function isPasswordValid($passwordRaw, $passwordEnc) {
		return $this->encoder->isPasswordValid($passwordEnc, $passwordRaw, null);
	}

	/**
	 * Check if user credentials are valid.
	 *
	 * @param string $username the username / name
	 * @param string $password raw password
	 * @return bool
	 */
	public function checkUserCredentials($username, $password) {
		$user = $this->loadUserByUsername($username);
		return $this->encoder->isPasswordValid($user->getDetail('password'), $password, null);
	}
}
