<?php

namespace OlmServer;

use Silex\Application;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;

/**
 * Functions copied from Symfony\Component\Security\Core\User\User
 * and extended and tweaked to implement:
 * - Symfony\Component\Security\Core\User\AdvancedUserInterface;
 * - Symfony\Component\Security\Core\User\EquatableInterface; 
 * - \Serializable
 *
 */
class User implements UserInterface, AdvancedUserInterface, EquatableInterface, \Serializable {

	protected $data;

	public function __construct($username, $password, array $roles = array(), $enabled = true, $userNonExpired = true, $credentialsNonExpired = true, $userNonLocked = true) {
		if ($username === '' || $username == null) {
			throw new InvalidArgumentException('The username cannot be empty.');
		}

		$this->data = array(
			'username' => $username,
			'password' => $password,
			'roles' => $roles,
			'salt' => null,
			'enabled' => $enabled,
			'account_non_expired' => $userNonExpired,
			'credentials_non_expired' => $credentialsNonExpired,
			'account_non_locked' => $userNonLocked
		);
	}

	public function initiate(array $data) {
		$this->data['id'] = $data['id'];
		$this->data['email'] = $data['email'];
	}

	public function __toString() {
		return $this->data['username']; 
	}

	public function getId() {
		return $this->data['id'];
	}
	
	public function getRoles() {
		return $this->data['roles'];
	}

	public function isAdmin() {
		return in_array('ROLE_ADMIN', $this->data['roles']);
	}

	public function getPassword() {
		return $this->data['password'];
	}

	public function getSalt() {
		return $this->data['salt'];
	}

	public function getEmail() {
		return $this->data['email'];
	}

	public function getUsername() {
		return $this->data['username'];
	}

	public function isAccountNonExpired() {
		return $this->data['account_non_expired'];
	}

	public function isAccountNonLocked() {
		return $this->data['account_non_locked'];
	}

	public function isCredentialsNonExpired() {
		return $this->data['credentials_non_expired'];
	}

	public function isEnabled() {
		return $this->data['enabled'];
	}

	public function eraseCredentials() {
	}

	public function isEqualTo(UserInterface $user) {
		if (! $user instanceof User) {
			return false;
		}

		if ($this->getPassword() !== $user->getPassword()) {
			return false;
		}

		if ($this->getSalt() !== $user->getSalt()) {
			return false;
		}

		if ($this->getUsername() !== $user->getUsername()) {
			return false;
		}

		return true;
	}

	public function serialize() {
		return serialize(
			array(
				$this->getUsername(),
				$this->getPassword(),
				$this->getSalt(),
			)
		);
	}

	public function unserialize($serialized) {
		list (
			$this->data['username'],
			$this->data['password'],
			$this->data['salt']
		) = unserialize($serialized);
	}

	public function getDataForSaving() {
		return $this->data;
	}

	public function getDetail($detail) {
		return $this->data[$detail];
	}
}
