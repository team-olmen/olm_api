<?php
namespace OlmServer;

/**
 */
class OlmApi {
	/**
	 * The app.
	 *
	 * @var \Silex\Application
	 */
	private $app;

	/**
	 * Connection to the database.
	 *
	 * @var \Doctrine\DBAL\Connection
	 */
	private $connection;

	/**
	 * String to prefix database tablenames with.
	 *
	 * @var string
	 */
	private $prefix;

	/**
	 * The routes served by the API.
	 *
	 * @var array
	 */
	private $routes = array(
		//
		// api-related
		//
		'api.login' => array('route' => '/api/login', 'function' => 'controllerLogin', 'method' => 'post', 'userrole' => '', 'owneronly' => false),
		'api.password.reset' => array('route' => '/api/password/reset', 'function' => 'controllerResetPassword', 'method' => 'post', 'userrole' => '', 'owneronly' => false),
		'api.setup' => array('route' => '/api/setup', 'function' => 'controllerSetup', 'method' => 'get', 'userrole' => '', 'owneronly' => false),
		'api.migrate.mcqs' => array('route' => '/api/migrate/mcqs', 'function' => 'controllerMigrateMcqs', 'method' => 'get', 'userrole' => '', 'owneronly' => false),
		'api.migrate.users' => array('route' => '/api/migrate/users', 'function' => 'controllerMigrateUsers', 'method' => 'get', 'userrole' => '', 'owneronly' => false),
		'api.test' => array('route' => '/api/test', 'function' => 'controllerTest', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		'api.teapot' => array('route' => '/api/teapot', 'function' => 'controllerTeapot', 'method' => 'get', 'userrole' => '', 'owneronly' => false),
		//
		// default controllers
		//
		// users
		'api.users.delete' => array('route' => '/api/users/{id}', 'function' => 'controllerDefaultDelete', 'method' => 'delete', 'userrole' => 'ROLE_USER', 'owneronly' => true),
		// mcqs
		'api.mcqs.gethistory' => array('route' => '/api/mcqs/history/{id}', 'function' => 'controllerDefaultGetHistory', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		'api.mcqs.getdeleted' => array('route' => '/api/mcqs/deleted', 'function' => 'controllerDefaultGetDeleted', 'method' => 'get', 'userrole' => 'ROLE_ADMIN', 'owneronly' => false),
		// modules
		'api.modules.getdeleted' => array('route' => '/api/modules/deleted', 'function' => 'controllerDefaultGetDeleted', 'method' => 'get', 'userrole' => 'ROLE_ADMIN', 'owneronly' => false),
		'api.modules.get' => array('route' => '/api/modules/{id}', 'function' => 'controllerDefaultGet', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		'api.modules.getversion' => array('route' => '/api/modules/{id}/version/{version}', 'function' => 'controllerDefaultGet', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		'api.modules.post' => array('route' => '/api/modules', 'function' => 'controllerDefaultPost', 'method' => 'post', 'userrole' => 'ROLE_ADMIN', 'owneronly' => false),
		'api.modules.patch' => array('route' => '/api/modules/{id}', 'function' => 'controllerDefaultPatch', 'method' => 'patch', 'userrole' => 'ROLE_ADMIN', 'owneronly' => false),
		'api.modules.delete' => array('route' => '/api/modules/{id}', 'function' => 'controllerDefaultDelete', 'method' => 'delete', 'userrole' => 'ROLE_ADMIN', 'owneronly' => false),
		'api.modules.gethistory' => array('route' => '/api/modules/history/{id}', 'function' => 'controllerDefaultGetHistory', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		// sessions
		'api.sessions.delete' => array('route' => '/api/sessions/{id}', 'function' => 'controllerDefaultDelete', 'method' => 'delete', 'userrole' => 'ROLE_USER', 'owneronly' => true),
		// exams
		'api.exams.getdeleted' => array('route' => '/api/exams/deleted', 'function' => 'controllerDefaultGetDeleted', 'method' => 'get', 'userrole' => 'ROLE_ADMIN', 'owneronly' => false),
		'api.exams.get' => array('route' => '/api/exams/{id}', 'function' => 'controllerDefaultGet', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		'api.exams.getversion' => array('route' => '/api/exams/{id}/version/{version}', 'function' => 'controllerDefaultGet', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		'api.exams.post' => array('route' => '/api/exams', 'function' => 'controllerDefaultPost', 'method' => 'post', 'userrole' => 'ROLE_ADMIN', 'owneronly' => false),
		'api.exams.patch' => array('route' => '/api/exams/{id}', 'function' => 'controllerDefaultPatch', 'method' => 'patch', 'userrole' => 'ROLE_ADMIN', 'owneronly' => false),
		'api.exams.delete' => array('route' => '/api/exams/{id}', 'function' => 'controllerDefaultDelete', 'method' => 'delete', 'userrole' => 'ROLE_ADMIN', 'owneronly' => false),
		'api.exams.gethistory' => array('route' => '/api/exams/history/{id}', 'function' => 'controllerDefaultGetHistory', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		// protocolls
		'api.protocolls.getdeleted' => array('route' => '/api/protocolls/deleted', 'function' => 'controllerDefaultGetDeleted', 'method' => 'get', 'userrole' => 'ROLE_ADMIN', 'owneronly' => false),
		'api.protocolls.get' => array('route' => '/api/protocolls/{id}', 'function' => 'controllerDefaultGet', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		'api.protocolls.getversion' => array('route' => '/api/protocolls/{id}/version/{version}', 'function' => 'controllerDefaultGet', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		'api.protocolls.post' => array('route' => '/api/protocolls', 'function' => 'controllerDefaultPost', 'method' => 'post', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		'api.protocolls.patch' => array('route' => '/api/protocolls/{id}', 'function' => 'controllerDefaultPatch', 'method' => 'patch', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		'api.protocolls.delete' => array('route' => '/api/protocolls/{id}', 'function' => 'controllerDefaultDelete', 'method' => 'delete', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		'api.protocolls.gethistory' => array('route' => '/api/protocolls/history/{id}', 'function' => 'controllerDefaultGetHistory', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		// generations
		'api.generations.get' => array('route' => '/api/generations/{id}', 'function' => 'controllerDefaultGet', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		'api.generations.post' => array('route' => '/api/generations', 'function' => 'controllerDefaultPost', 'method' => 'post', 'userrole' => 'ROLE_ADMIN', 'owneronly' => false),
		'api.generations.patch' => array('route' => '/api/generations/{id}', 'function' => 'controllerDefaultPatch', 'method' => 'patch', 'userrole' => 'ROLE_ADMIN', 'owneronly' => false),
		'api.generations.delete' => array('route' => '/api/generations/{id}', 'function' => 'controllerDefaultDelete', 'method' => 'delete', 'userrole' => 'ROLE_ADMIN', 'owneronly' => false),
		// texts
		'api.texts.get' => array('route' => '/api/texts/{id}', 'function' => 'controllerDefaultGet', 'method' => 'get', 'userrole' => 'anonymous', 'owneronly' => false),
		'api.texts.post' => array('route' => '/api/texts', 'function' => 'controllerDefaultPost', 'method' => 'post', 'userrole' => 'ROLE_ADMIN', 'owneronly' => false),
		'api.texts.patch' => array('route' => '/api/texts/{id}', 'function' => 'controllerDefaultPatch', 'method' => 'patch', 'userrole' => 'ROLE_ADMIN', 'owneronly' => false),
		'api.texts.delete' => array('route' => '/api/texts/{id}', 'function' => 'controllerDefaultDelete', 'method' => 'delete', 'userrole' => 'ROLE_ADMIN', 'owneronly' => false),
		//
		// custom controllers
		//
		// users
		'api.users.get' => array('route' => '/api/users/{id}', 'function' => 'controllerUsersGet', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => true),
		'api.users.getmulti' => array('route' => '/api/users', 'function' => 'controllerUsersGetMulti', 'method' => 'get', 'userrole' => 'ROLE_ADMIN', 'owneronly' => false),
		'api.users.post' => array('route' => '/api/users', 'function' => 'controllerUsersPost', 'method' => 'post', 'userrole' => 'ROLE_USER', 'owneronly' => true),
		'api.users.patch' => array('route' => '/api/users/{id}', 'function' => 'controllerUsersPatch', 'method' => 'patch', 'userrole' => 'ROLE_USER', 'owneronly' => true),
		// mcqs
		'api.mcqs.get' => array('route' => '/api/mcqs/{id}', 'function' => 'controllerMcqsGet', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		'api.mcqs.getversion' => array('route' => '/api/mcqs/{id}/version/{version}', 'function' => 'controllerMcqsGet', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		'api.mcqs.getmulti' => array('route' => '/api/mcqs/modules/{modules}/rating/{rating}/generation/{generation}/original/{original}/number/{number}', 'function' => 'controllerMcqsGetMulti', 'method' => 'get', 'userrole' => 'anonymous', 'owneronly' => false),
		'api.mcqs.post' => array('route' => '/api/mcqs', 'function' => 'controllerMcqsPost', 'method' => 'post', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		'api.mcqs.patch' => array('route' => '/api/mcqs/{id}', 'function' => 'controllerMcqsPatch', 'method' => 'patch', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		'api.mcqs.delete' => array('route' => '/api/mcqs/{id}', 'function' => 'controllerMcqsDelete', 'method' => 'delete', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		// modules
		'api.modules.getmulti' => array('route' => '/api/modules', 'function' => 'controllerModulesGetMulti', 'method' => 'get', 'userrole' => 'anonymous', 'owneronly' => false),
		// sessions
		'api.sessions.get' => array('route' => '/api/sessions/{id}', 'function' => 'controllerSessionsGet', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => true),
		'api.sessions.get.multi' => array('route' => '/api/sessions', 'function' => 'controllerSessionsGetMulti', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => true),
		'api.sessions.post' => array('route' => '/api/sessions', 'function' => 'controllerSessionsPost', 'method' => 'post', 'userrole' => 'ROLE_USER', 'owneronly' => true),
		'api.sessions.patch' => array('route' => '/api/sessions/{id}', 'function' => 'controllerSessionsPatch', 'method' => 'patch', 'userrole' => 'ROLE_USER', 'owneronly' => true),
		// exams
		'api.exams.getmulti' => array('route' => '/api/exams', 'function' => 'controllerExamsGetMulti', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		// protocolls
		'api.protocolls.getmulti' => array('route' => '/api/protocolls/exam/{id}', 'function' => 'controllerProtocollsGetMulti', 'method' => 'get', 'userrole' => 'ROLE_USER', 'owneronly' => false),
		// generations
		'api.generations.getmulti' => array('route' => '/api/generations', 'function' => 'controllerGenerationsGetMulti', 'method' => 'get', 'userrole' => 'anonymous', 'owneronly' => false),
		// texts
		'api.texts.getmulti' => array('route' => '/api/texts', 'function' => 'controllerTextsGetMulti', 'method' => 'get', 'userrole' => 'ROLE_ADMIN', 'owneronly' => false),
		'api.texts.getbypath' => array('route' => '/api/texts/path/{path}', 'function' => 'controllerTextsGetByPath', 'method' => 'get', 'userrole' => 'anonymous', 'owneronly' => false),
	);

	private $models = array(
		'users' => array(
			'id' => array('pattern' => '/^[0-9]+$/', 'default' => null, 'type' => 'numeric'),
			'username' => array('pattern' => '/^[a-zA-Z0-9äöüßÄÖÜß_\.\-]+$/', 'default' => null, 'type' => 'string'),
			'email' => array('pattern' => '/^[a-zA-Z0-9_\.\-]+@charite\.de$/', 'default' => null, 'type' => 'string'),
			'password' => array('pattern' => '/^.*+$/', 'default' => null, 'type' => 'string'),
			'salt' => array('pattern' => '/^[a-zA-Z0-9äöüßÄÖÜß_\.\-:]*$/', 'default' => '', 'type' => 'string'),
			'enabled' => array('pattern' => '/^(0|1)$/', 'default' => 0, 'type' => 'numeric'),
			'account_non_expired' => array('pattern' => '/^(0|1)$/', 'default' => 1, 'type' => 'numeric'),
			'credentials_non_expired' => array('pattern' => '/^(0|1)$/', 'default' => 1, 'type' => 'numeric'),
			'account_non_locked' => array('pattern' => '/^(0|1)$/', 'default' => 1, 'type' => 'numeric'),
			'roles' => array('pattern' => '/^((ROLE_USER|ROLE_ADMIN),?)+$/', 'default' => 'ROLE_USER', 'type' => 'array'),
			'login' => array('pattern' => '/^[0-9 \-:\.]+', 'default' => 'UNSET', 'type' => 'string'),
		),
		'mcqs' => array(
			'id' => array('pattern' => '/^[0-9]+$/', 'default' => null, 'type' => 'numeric'),
			'module' => array('pattern' => '/^[0-9]+$/', 'default' => null, 'type' => 'numeric'),
			'raw' => array('pattern' => '', 'default' => null, 'type' => 'string'),
			'rating' => array('pattern' => '/^[0-9]+$/', 'default' => 0, 'type' => 'numeric'),
			'original' => array('pattern' => '/^(0|1)$/', 'default' => 0, 'type' => 'numeric'),
			'complete' => array('pattern' => '/^(0|1)$/', 'default' => 0, 'type' => 'numeric'),
			'generation' => array('pattern' => '/^[0-9]+$/', 'default' => null, 'type' => 'numeric'),
			'discussion' => array('pattern' => '', 'default' => '', 'type' => 'string'),
		),
		'modules' => array(
			'id' => array('pattern' => '/^[0-9]+$/', 'default' => null, 'type' => 'numeric'),
			'name' => array('pattern' => '/^[a-zA-Z0-9äöüßÄÖÜß _\-:,]+$/', 'default' => null, 'type' => 'string'),
			'code' => array('pattern' => '/^(M|S)[0-9]{1,9}$/', 'default' => null, 'type' => 'string'),
		),
		'sessions' => array(
			'id' => array('pattern' => '/^[0-9]+$/', 'default' => null, 'type' => 'numeric'),
			'user' => array('pattern' => '/^[0-9]+$/', 'default' => null, 'type' => 'string'),
			'name' => array('pattern' => '/^[a-zA-Z0-9äöüßÄÖÜß _\-:,]+$/', 'default' => null, 'type' => 'string'),
			'questions' => array('pattern' => '/^([0-9]+;)+[0-9]+$/', 'default' => null, 'type' => 'array'),
			'status' => array('pattern' => '/^([0-9]+;)+[0-9]+$/', 'default' => null, 'type' => 'array'),
			'answers' => array('pattern' => '/^([0-9]+;)+[0-9]+$/', 'default' => null, 'type' => 'array'),	
			'current' => array('pattern' => '/^[0-9]+$/', 'default' => null, 'type' => 'numeric'),
			'answered' => array('pattern' => '/^[0-9]+$/', 'default' => null, 'type' => 'numeric'),
			'correct' => array('pattern' => '/^[0-9]+$/', 'default' => null, 'type' => 'numeric'),
			'total' => array('pattern' => '/^[0-9]+$/', 'default' => null, 'type' => 'numeric'),
		),
		'exams' => array(
			'id' => array('pattern' => '/^[0-9]+$/', 'default' => null, 'type' => 'numeric'),
			'name' => array('pattern' => '/^[a-zA-Z0-9äöüßÄÖÜß _\-:,]+$/', 'default' => null, 'type' => 'string'),
		),
		'protocolls' => array(
			'id' => array('pattern' => '/^[0-9]+$/', 'default' => null, 'type' => 'numeric'),
			'exam' => array('pattern' => '/^[0-9]+$/', 'default' => null, 'type' => 'numeric'),
			'name' => array('pattern' => '/^[a-zA-Z0-9äöüßÄÖÜß _\-:,]+$/', 'default' => null, 'type' => 'string'),
			'text' => array('pattern' => '', 'default' => null, 'type' => 'string'),
		),
		'generations' => array(
			'id' => array('pattern' => '/^[0-9]+$/', 'default' => null, 'type' => 'numeric'),
			'name' => array('pattern' => '/^[a-zA-Z0-9äöüßÄÖÜß _\-:,]+$/', 'default' => null, 'type' => 'string'),
		),
		'texts' => array(
			'id' => array('pattern' => '/^[0-9]+$/', 'default' => null, 'type' => 'numeric'),
			'path' => array('pattern' => '/^[a-zA-Z\-_:]+$/', 'default' => null, 'type' => 'string'),
			'help' => array('pattern' => '', 'default' => '', 'type' => 'string'),
			'text' => array('pattern' => '', 'default' => null, 'type' => 'string'),
		)
	);

	private $versionedTables = array(
		'mcqs',
		'exams',
		'modules',
		'protocolls'
	);

	const RESPONSE_INVALID_REQUEST = array(400, 'Invalid request');
	const RESPONSE_STUPID_REQUEST = array(400, 'Stupid request');
	const RESPONSE_TOO_MANY_MODULES = array(400, 'Too many modules');
	const RESPONSE_NO_QUESTIONS = array(400, 'No questions');
	const RESPONSE_INSUFFICIENT_PERMISSIONS = array(403, 'Insufficient permissions');
	const RESPONSE_ITEM_EXISTS = array(409, 'Item exists');
	const RESPONSE_ITEM_NOT_CHANGED = array(404, 'Item not changed');
	const RESPONSE_PASSWORDS_DO_NOT_MATCH = array(400, 'Passwords do not match');
	const RESPONSE_PASSWORD_MISSING = array(400, 'Password missing');
	const RESPONSE_USERNAME_EXISTS = array(409, 'Username exists');
	const RESPONSE_EMAIL_EXISTS = array(409, 'Email exists');
	const RESPONSE_WRONG_PASSWORD = array(403, 'Wrong password');
	const RESPONSE_ITEM_NOT_FOUND = array(409, 'Item not found');
	const RESPONSE_MCQ_NO_QUESTION = array(400, 'Question missing');
	const RESPONSE_MCQ_NO_ANSWERS = array(400, 'Answers missing');
	const RESPONSE_MCQ_NO_SOLUTION = array(400, 'Solution missing');
	const RESPONSE_MISSING_USERNAME_OR_PASSWORD = array(400, 'Missing username or password');
	const RESPONSE_BAD_USERNAME_OR_PASSWORD = array(403, 'Bad username or password');

	/**
	 * Constructor.
	 *
	 * @param Doctrine\DBAL\Connection $connection connection to the database
	 * @param string $prefix a string to prefix the tablenames in the database with
	 * @return void
	 */
	public function __construct(\Doctrine\DBAL\Connection $connection, \Silex\Application $app, string $prefix) {
		$this->connection = $connection;
		$this->app = $app;
		$this->prefix = $prefix;
	}

	public function getRoutes() {
		return $this->routes;
	}

	private function sendError(array $return) {
		list($code, $message) = $return;
		throw new ApiException($message, $code);
	}

	public function getCurrentUserId() {
		$token = $this->app['security.token_storage']->getToken();
		if ($token === null) {
			return -1;
		}
		$user = $token->getUser();
		if ($user === 'anon.') {
			return -1;
		}
		return $user->getId();
	}

	public function getCurrentUser() {
		$token = $this->app['security.token_storage']->getToken();
		return ($token !== null) ? $token->getUser() : null;
	}

	private function entryIsOwn(array $data, string $table, int $currentUserId) {
		if ($this->getCurrentUser()->isAdmin()) {
			return true;
		}
		if (empty($data)) {
			return false;
		}
		if (isset($data['user'])) {
			$userId = $data['user'];
		} else if ($table === 'users') {
			$userId = $data['id'];
		} else {
			$entry = $this->entryFetchById($data['id'], $table);
			$userId = $data['user'];
		}

		return $userId == $currentUserId;
	}

	/**
	 * Fetch a given entry from the database.
	 *
	 * For GET.
	 * @param array $conditions like array('username' => 'SOMENAME')
	 * @param string $table the name of the table to operate on (without prefix)
	 * @return array the entry
	 */
	private function entryFetch(array $conditions, string $table) {
		$where = '';
		foreach ($conditions as $key => $condition) {
			if ($where !== '') {
				$where .= ' AND ';
			}
			$where .= $key . ' = ?';
		}

		$entry = $this->connection->fetchAssoc(
			'SELECT * FROM ' . $this->prefix . $table . ' WHERE ' . $where . ' LIMIT 1',
			array_values($conditions));

		return $entry ? : array();
	}

	/**
	 * Fetch a given entry from the database.
	 *
	 * For GET.
	 * @param string $id the id
	 * @param string $table the name of the table to operate on (without prefix)
	 * @return array the entry
	 */
	private function entryFetchById(string $id, string $table) {
		$entry = $this->connection->fetchAssoc(
			'SELECT * FROM ' . $this->prefix . $table . ' WHERE id = ? LIMIT 1',
			array($id));

		return $entry ? : array();
	}

	private function entrySaveHistory(int $id, string $table, string $action) {
		if (!in_array($table, $this->versionedTables)) {
			// do not version entries
			return true;
		}
		// get the entry
		$entry = $this->entryFetchById($id, $table);
		if (empty($entry)) {
			return false;
		}

		// add user and action
		$entry['history_user'] = $this->getCurrentUserId();
		$entry['history_status'] = $action;

		$affectedRows = $this->connection->insert(
			$this->prefix . $table . '_history',
			$entry
		);

		return $affectedRows === 1;
	}

	/**
	 * Insert a given entry in the database.
	 *
	 * For POST.
	 * @param array $data the data, needs to contain the id
	 * @param string $table the name of the table to operate on (without prefix)
	 * @param boolen $history create an entry in the _history table
	 * @return integer the new id, -1 on error
	 */
	private function entryCreate(array $data, string $table, bool $history = true) {
		$affectedRows = $this->connection->insert(
			$this->prefix . $table,
			$data
		);

		if ($affectedRows > 0) {
			$id = (int)$this->connection->lastInsertId();

			$history && $this->entrySaveHistory($id, $table, 'created');
			return $id;
		} else {
			return -1;
		}
	}

	/**
	 * Update a given entry in the database.
	 *
	 * For PUT or PATCH.
	 * @param array $data the data, needs to contain the id
	 * @param string $table the name of the table to operate on (without prefix)
	 * @param array $keys the keys for the update (field name => value)
	 * @param boolen $history create an entry in the _history table
	 * @return integer the affected rows, -1 on error
	 */
	private function entryUpdate(array $data, string $table, array $keys, bool $history = true) {
		if (! isset($data['id'])) {
			return -1;
		}

		$affectedRows = $this->connection->update(
			$this->prefix . $table,
			$data,
			$keys
		);

		$history && $this->entrySaveHistory($data['id'], $table, 'updated');
		return $affectedRows;
	}

	/**
	 * Delete a given entry from the database.
	 *
	 * For DELETE.
	 * @param string $id the id
	 * @param string $table the name of the table to operate on (without prefix)
	 * @param boolen $history create an entry in the _history table
	 * @return integer the affected rows, -1 on error
	 */
	private function entriesDelete(string $id, string $table, bool $history = true) {
		$history && $this->entrySaveHistory((int)$id, $table, 'deleted');

		$affectedRows = $this->connection->delete(
			$this->prefix . $table,
			array('id' => $id)
		);

		return $affectedRows;
	}

	private function entriesFetchDeleted(string $table) {
		if (!in_array($table, $this->versionedTables)) 
			return array();

		$entries = $this->connection->fetchAll('SELECT * FROM ' . $this->prefix . $table . '_history WHERE history_status = "deleted"');

		return $entries ? : array();
	}

	private function entryPurge(string $id, string $table){
		$affectedRows = $this->connection->delete(
			$this->prefix . $table .'_history',
			array('id' => $id)
		);

		return $affectedRows;
	}

	private function entryFetchWithStatus(string $id, string $status, string $table) {
		$entry = $this->connection->fetchAssoc(
			'SELECT * FROM ' . $this->prefix . $table . '_history WHERE id = ? AND history_status = ?',
			array($id, $status)
		);
		return $entry ? : array();
	}

	private function entryUndelete(string $id, array $data = array(), string $table) {
		if (!in_array($table, $this->versionedTables)) 
			return array();

		//$data = empty($data) ? $deleted : $data;

		$affectedRows = $this->connection->update(
			$this->prefix . $table . '_history',
			array('history_status' => 'deletioncancelled'),
			array('id' => $id, 'history_status' => 'deleted')
		);

		if (isset($data['history_timestamp']))
			unset($data['history_timestamp']);

		$data['history_status'] = 'revived';

		$affectedRows = $this->connection->insert(
			$this->prefix . $table . '_history',
			$data
		);

		if (isset($data['history_user']))
			unset($data['history_user']);
		if (isset($data['history_status']))
			unset($data['history_status']);

		$affectedRows = $this->connection->insert(
			$this->prefix . $table,
			$data
		);

		return $data;
	}

	private function entryFetchHistory(string $id, string $table) {
		if (!in_array($table, $this->versionedTables))
			return array();

		$entries = $this->connection->fetchAll(
			'SELECT * FROM ' . $this->prefix . $table . '_history WHERE id = ? ORDER BY history_timestamp DESC',
			array($id)
		);

		return $entries ? : array();
	}

	private function entryFetchVersionByDate(string $id, string $date, string $table) {
		if (!in_array($table, $this->versionedTables))
			return array();

		$entry = $this->connection->fetchAssoc(
			'SELECT * FROM ' . $this->prefix . $table . '_history WHERE id = ? AND history_timestamp = ?',
			array($id, $date)
		);

		return $entry ? : array();
	}

	private function entriesPrepareForClient(array $entries, string $table) {
		foreach ($entries as $i => $data) {
			foreach ($data as $key => $item) {
				if (!isset($this->models[$table][$key])) {
					continue;
				}

				switch ($this->models[$table][$key]) {
				case 'numeric':
					$entries[$i][$key] = intval($item);
					break;
				}
			}
		}
		return $entries;
	}

	private function getTableForRoute(string $route){
		$array = explode('.', $route);
		return $array[1];
	}

	private function checkData(array $data, string $table, bool $forInsertion) {
		foreach ($this->models[$table] as $key => $attributes) {
			// check if the data point matches the pattern
			if (isset($data[$key]) && $data[$key] !== null && $attributes['pattern'] !== '' && ! preg_match($attributes['pattern'], $data[$key])) {
				// if it does not we stop the check
				$this->sendError(self::RESPONSE_INVALID_REQUEST);
			}
			// if the data set needs to be complete for insertion (except for the id)
			if ($forInsertion && $key !== 'id') {
				// try to fill in the default value
				if (!isset($data[$key]) && $attributes['default'] !== null) {
					$data[$key] = $attributes['default'];
				}

				if ($attributs['default'] !== 'UNSET' && !isset($data[$key])) {
					// the value is still missing so we stop and return an empty array
					$this->sendError(self::RESPONSE_INVALID_REQUEST);
				}
			}
		}

		foreach ($data as $key => $attribute) {
			if (!isset($this->models[$table][$key])) {
					unset($data[$key]);
			}
		}

		if (!$forInsertion && !isset($data['id'])) {
			$this->sendError(self::RESPONSE_INVALID_REQUEST);
		}
		return $data;
	}

	private function checkPermissionsRole(string $route) {
		if (!$this->app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
			// if the user is not admin
			// maybe the controller allows anonymous access
			$user = $this->getCurrentUser();
			if ($this->routes[$route]['userrole'] == 'anonymous' && $user) {
				return true;
			}
			// check if the user is allowed to access the resource
			if (!$this->app['security.authorization_checker']->isGranted($this->routes[$route]['userrole'])) {
				$this->sendError(self::RESPONSE_INSUFFICIENT_PERMISSIONS);
			}
		}
		return true;
	}

	private function checkPermissionsOwner(string $route, array $entry, $table) {
		if ($this->routes[$route]['owneronly'] && !$this->entryIsOwn($entry, $table, $this->getCurrentUserId())) {
			$this->sendError(self::RESPONSE_INSUFFICIENT_PERMISSIONS);
		}
	}

	private function checkParams(array $params, array $types) {
		$ok = true;
		foreach ($params as $key => $param) {
			switch ($types[$key]) {
			case 'string':
				$ok = is_string($param);
				break;
			case 'numeric':
				$ok = is_numeric($param);
				break;
			case 'bool':
				$ok = is_numeric($param) && ($param === "0" || $param === "1");
				break;
			default:
				$ok = preg_match($types[$key], $param);
				break;
			}

			if (!$ok){
				$this->sendError(self::RESPONSE_INVALID_REQUEST);
			}
		}
	}

	public function controllerDefaultGet(\Symfony\Component\HttpFoundation\Request $request, string $id, string $version = 'current') {
		$route = $request->get('_route');
		$table = $this->getTableForRoute($route);
		$this->checkPermissionsRole($route);
		$this->checkParams(array($id), array('numeric'));

		if ($version === 'current') {
			$entry = $this->entryFetchById($id, $table);
		} else {
			$entry = $this->entryFetchVersionByDate($id, $version, $table);
		}

		$this->checkPermissionsOwner($route, $entry, $table);
		$entry = $this->entriesPrepareForClient(array($entry), $table)[0];

		return $this->app->json($entry, 200);
	}

	public function controllerDefaultPost(\Symfony\Component\HttpFoundation\Request $request, array $data = array()) {
		$route = $request->get('_route');
		$table = $this->getTableForRoute($route);
		empty($data) && $data = json_decode($request->getContent(), true);
		$data = $this->checkData($data, $table, true);
		//var_dump($data);
		$this->checkPermissionsRole($request->get("_route"));
		$this->checkPermissionsOwner($route, $data, $table);

		$id = $this->entryCreate($data, $table);

		if ($id === -1) {
			$this->sendError(self::RESPONSE_ITEM_EXISTS);
		}

		$entry = $this->entryFetchById($id, $table);
		$entry = $this->entriesPrepareForClient(array($entry), $table)[0];

		return $this->app->json($entry, 201);
	}

	public function controllerDefaultPatch(\Symfony\Component\HttpFoundation\Request $request, string $id, array $data = array()) {
		$route = $request->get('_route');
		$table = $this->getTableForRoute($route);
		empty($data) && $data = json_decode($request->getContent(), true);
		isset($data['id']) || $data['id'] = $id;
		$this->checkPermissionsRole($request->get("_route"));
		$this->checkParams(array($id), array('numeric'));

		// check if the item exists
		$entry = $this->entryFetchById($id, $table);
		if (empty($entry)) {
			// maybe it existed but has been marked deleted
			$deleted = array();
			// this may only interest admins
			if ($this->getCurrentUser()->isAdmin()) {
				$deleted = $this->entryFetchWithStatus($id, 'deleted', $table);
			}
	
			if (empty($deleted)) {
				// item does not exist
				$this->sendError(self::RESPONSE_ITEM_NOT_FOUND);
			}

			// restore the item
			if ($this->getCurrentUser()->isAdmin()) {
				// if the user has not sent any data to restore
				if (empty($data)) {
					// restores the latest version
					$data = $deleted;
				}
				$data = $this->checkData($data, $table, true);
				$data = $this->entryUndelete($id, $data, $table);
			}
		} else {
			// item exists so we update it
			// set the owner in case it has not been submitted
			(!isset($data['user']) && isset($entry['user'])) && $data['user'] = $entry['user'];
			$this->checkPermissionsOwner($route, $data, $table);
			$data = $this->checkData($data, $table, false);

			if ($this->entryUpdate($data, $table, array('id' => $data['id'])) < 1) {
				$this->sendError(self::RESPONSE_ITEM_NOT_CHANGED);
			}

			$entry = $this->entryFetchById($id, $table);
		}

		$entry = $this->entriesPrepareForClient(array($entry), $table)[0];
		return $this->app->json($entry, 200);
	}

	public function controllerDefaultDelete(\Symfony\Component\HttpFoundation\Request $request, string $id) {
		$route = $request->get('_route');
		$table = $this->getTableForRoute($route);
		$this->checkPermissionsRole($request->get("_route"));
		$this->checkParams(array($id), array('numeric'));

		$entry = $this->entryFetchById($id, $table);
		if (empty($entry)) {
			$n = 0;
			$this->getCurrentUser()->isAdmin() && $n = $this->entryPurge($id, $table);
			$n < 1 &&	$this->sendError(self::RESPONSE_ITEM_NOT_CHANGED);
		}

		$this->checkPermissionsOwner($route, $entry, $table);
		$this->entriesDelete($id, $table);

		return $this->app->json(array(), 200);
	}

	public function controllerDefaultGetHistory(\Symfony\Component\HttpFoundation\Request $request, string $id) {
		$route = $request->get('_route');
		$table = $this->getTableForRoute($route);
		$this->checkPermissionsRole($route);
		$this->checkParams(array($id), array('numeric'));

		$entries = $this->entryFetchHistory($id, $table);

		return $this->app->json($entries, 200);

		foreach ($entries as $entry) {
			$this->checkPermissionsOwner($route, $entry, $table);
		}

		$entries = $this->entriesPrepareForClient($entries, $table);
		return $this->app->json($entries, 200);
	}

	public function controllerDefaultGetDeleted(\Symfony\Component\HttpFoundation\Request $request) {
		$route = $request->get('_route');
		$table = $this->getTableForRoute($route);
		$this->checkPermissionsRole($route);

		$entries = $this->entriesFetchDeleted($table);

		foreach ($entries as $entry) {
			$this->checkPermissionsOwner($route, $entry, $table);
		}
		$entries = $this->entriesPrepareForClient($entries, $table);

		return $this->app->json($entries, 200);
	}

	/*
	 * Controllers for the users table.
	 */

	public function controllerUsersGet(\Symfony\Component\HttpFoundation\Request $request, string $id) {
		$route = $request->get('_route');
		$table = 'users';
		$this->checkPermissionsRole($route);
		$this->checkParams(array($id), array('numeric'));

		$entry = $this->entryFetchById($id, $table);

		$this->checkPermissionsOwner($route, $entry, $table);

		$entry = $this->entriesPrepareForClient(array($entry), $table)[0];
		return $this->app->json($entry, 200);
	}


	public function controllerUsersGetMulti(\Symfony\Component\HttpFoundation\Request $request) {
		$this->checkPermissionsRole($request->get("_route"));

		$entries = $this->connection->fetchAll(
			'SELECT * FROM ' . $this->prefix . 'users'
		);

		$entries = $this->entriesPrepareForClient($entries, 'users');
		return $this->app->json($entries, 200);
	}

	public function controllerUsersPost(\Symfony\Component\HttpFoundation\Request $request) {
		$data = json_decode($request->getContent(), true);

		if ($data['password'] !== $data['check']) {
			$this->sendError(self::RESPONSE_PASSWORDS_DO_NOT_MATCH);
		}

		unset($data['check']);

		if (isset($data['password']) && !empty($data['password'])) {
			$password = $data['password'];
			$data['password'] = $this->app['users']->encodePassword($data['password'], empty($data['salt']) ? null : $data['salt']);
		} else {
			unset($data['password']);
		}

		if (isset($data['username'])) {
			$entry = $this->entryFetch(array('username' => $data['username']), 'users');
			if ($entry) {
				$this->sendError(self::RESPONSE_USERNAME_EXISTS);
			}
		}

		if (isset($data['email'])) {
			$entry = $this->entryFetch(array('email' => $data['email']), 'users');
			if ($entry) {
				$this->sendError(self::RESPONSE_EMAIL_EXISTS);
			}
		}

		if (isset($data['roles'])) {
			if (!$this->getCurrentUser()->isAdmin()) {
				// users may only create users
				$data['roles'] = array('ROLE_USER');
			}
			$data['roles'] = implode(',', $data['roles']);
		}

		$data['enabled'] = 0; 
		$data = $this->entriesPrepareForClient(array($data), 'users')[0];

		mail($data['email'], '[Olm] PW', "Hallo " . $data['username'] . ",\nbitte melde Dich mit: $password an und ändere Dein Passwort!");
		return $this->controllerDefaultPost($request, $data);
	}

	public function controllerUsersPatch(\Symfony\Component\HttpFoundation\Request $request, string $id) {
		$data = json_decode($request->getContent(), true);

		$user = $this->getCurrentUser();

		if (!isset($data['check'])) {
			$this->sendError(self::RESPONSE_PASSWORD_MISSING);
		}

		if (!$this->app['users']->isPasswordValid($data['check'], $user->getPassword())) {
			$this->sendError(self::RESPONSE_WRONG_PASSWORD);
		}
		unset($data['check']);

		if (isset($data['password']) && !empty($data['password'])) {
			$data['password'] = $this->app['users']->encodePassword($data['password'], empty($data['salt']) ? null : $data['salt']);
		} else {
			unset($data['password']);
		}

		if (isset($data['username']) && $data['username'] !== $user->getUsername()) {
			$entry = $this->entryFetch(array('username' => $data['username']), 'users');
			if ($entry) {
				$this->sendError(self::RESPONSE_USERNAME_EXISTS);
			}
		}

		if (isset($data['email']) && $data['email'] !== $user->getEmail()) {
			$entry = $this->entryFetch(array('email' => $data['email']), 'users');
			if ($entry) {
				$this->sendError(self::RESPONSE_EMAIL_EXISTS);
			}
		}

		if (isset($data['roles'])) {
			if (!$this->getCurrentUser()->isAdmin()) {
				// users may only create users
				$data['roles'] = array('ROLE_USER');
			}
			$data['roles'] = implode(',', $data['roles']);
		}

		$data['enabled'] = 1;
		if (isset($data['login'])) {
			unset($data['login']);
		}
		$data = $this->entriesPrepareForClient(array($data), 'users')[0];
		return $this->controllerDefaultPatch($request, $id, $data);
	}

	/*
	 * Controllers for the mcqs table.
	 */

	private function mcqsPrepareForClient(array $mcqs) {
		foreach ($mcqs as $key => $mcq) {
			$array = $this->mcqStringParse($mcq['raw']);
			$mcqs[$key]['question'] = $array['question'];
			$mcqs[$key]['answers'] = $array['answers'];
			$mcqs[$key]['solution'] = $array['solution'];
			$mcqs[$key]['rated'] = $this->mcqRatedByUser($mcq['id'], $mcq);
		}
		return $mcqs;
	}

	private function mcqsPrepareForDb(array $mcqs) {
		foreach ($mcqs as $key => $mcq) {
			if (isset($mcq['rated']))
				unset($mcqs[$key]['rated']);
			if (isset($mcq['rating']))
				unset($mcqs[$key]['rating']);
			if (isset($mcq['question']))
				unset($mcqs[$key]['question']);
			if (isset($mcq['answers']))
				unset($mcqs[$key]['answers']);
			if (isset($mcq['solution']))
				unset($mcqs[$key]['solution']);
			if (!isset($mcq['raw'])) {
				$this->sendError(self::RESPONSE_INVALID_REQUEST);
			}
			$mcqs[$key]['raw'] = $this->mcqStringNormalise($mcq['raw']);
			// check if the question is complete
			list($question, $answers, $solution) = array_values($this->mcqStringParse($mcqs[$key]['raw']));
			empty($question) && $this->sendError(self::RESPONSE_MCQ_NO_QUESTION);
			empty($answers) && $this->sendError(self::RESPONSE_MCQ_NO_ANSWERS);
			$solution == -1 && $this->sendError(self::RESPONSE_MCQ_NO_SOLUTION);
		}
		return $mcqs;
	}

	private function mcqsFetch(string $modules, string $rating, string $generation, string $original, string $number) {
		$this->checkParams(array($modules, $rating, $generation, $original, $number), array('/[0-9,]+/', '/[0123]/', 'string', '/[012]/', 'numeric'));

		$queryString = '';
		$queryArray = array($this->getCurrentUserId());

		foreach (explode(',', $modules) as $module) {
			if ((int) $module > 0) {
				$queryString .= $queryString === '' ? ' (' : ' OR';
				$queryString .= ' module = ?';
				$queryArray[] = $module;
			}
		}

		$queryString .= $queryString === '' ? '' : ') AND';

		(int) $rating === 0 && $queryString .= ' rating < 0 AND';
		(int) $rating === 1 && $queryString .= ' rating > 0 AND';
		(int) $rating === 2 && $queryString .= ' rating = 0 AND';

		if ($generation !== 'all') {
			$queryString .= ' generation = ? AND';
			$queryArray[] = $generation;
		}

		(int) $original === 0 && $queryString .= ' original = 0 AND';
		(int) $original === 1 && $queryString .= ' original = 1 AND';

		if (substr($queryString, -3) === 'AND') {
			$queryString = substr($queryString, 0, -4);
		}

		if ($queryString === '' && $number < 1) {
			$this->sendError(self::RESPONSE_INVALID_REQUEST);
		}

		$entries = $this->connection->fetchAll(
			'SELECT ' .
			't1.id as id, ' .
			't1.raw as raw, ' .
			't1.rating as rating, ' .
			't1.original as original, ' .
			't1.complete as complete, ' .
			't1.generation as generation, ' .
			't1.discussion as discussion, ' .
			'IFNULL(t2.rated, 2) as rated '.
			'FROM ' . $this->prefix . 'mcqs AS t1 ' .
			'LEFT JOIN ' . $this->prefix . 'mcqs_rated AS t2 ON t1.id = t2.id AND t2.user = ? ' .
			'WHERE' . $queryString .
			($number > 0 ? ' ORDER BY RAND()' : '') . 
			($number > 0 ? ' LIMIT ' . $number : ''),
			$queryArray);

		$entries = $this->mcqsPrepareForClient($entries);

		return $entries;
	}

	private function mcqStringParse(string $string) {
		$array = explode("\n\n", $string);
		$mcq['question'] = '';
		$mcq['answers'] = array();
		$mcq['solution'] = -1;
		$currentAnswer = 0;

		foreach ($array as $chunk) {
			if (substr($chunk, 0, 1) === "-") {
				$mcq['answers'][$currentAnswer] = substr($chunk, 2);
				$currentAnswer++;
			} else if (substr($chunk, 0, 1) === "*") {
				$mcq['answers'][$currentAnswer] = substr($chunk, 2);
				$mcq['solution'] = $currentAnswer;
				$currentAnswer++;
			} else {
				$mcq['question'] .= $chunk . "\n";
			}
		}

		return $mcq;
	}

	private function mcqStringNormalise(string $string) {
		#$string = htmlentities($string, ENT_QUOTES | ENT_HTML5, "UTF-8", false);
		// replace DOS / MAC / broken combinations of line feeds and carriage returns
		$string = str_replace("\r\n", "\n", $string);
		$string = str_replace("\n\r", "\n", $string);
		$string = str_replace("\r", "\n", $string);
		// place exactly one blank line between paragraphs and answers
		$string = preg_replace("/[ \t]*\n+[ \t]*/", "\n\n", $string);
		$string = trim($string);
		// there may only be one blank after the bulletpoint of an answer
		$string = preg_replace("/\n(-|\*)[ \t]*/", "\n" . '${1} ', $string);
		return $string;
	}

	private function mcqStringFromParsed(array $mcq) {
		$array[] = $this->mcqStringNormalise($mcq['question']);
		foreach ($mcq['answers'] as $key => $answer) {
			$bullet = $key === $mcq['solution'] ? '*' : '-';
			$array[] = $bullet . ' ' . $answer;
		}
		return implode("\n\n", $array);
	}

	private function mcqRate(int $id, int $rated) {
		// get the current count
		$current = $this->entryFetchById($id, 'mcqs');

		if (empty($current)) {
			// since one can't rate on creation the question has to exist
			$this->sendError(ITEM_NOT_FOUND);
		} else if (!in_array($rated, array(0, 1, 2))) {
			$this->sendError(INVALID_REQUEST);
		}

		$rating = $current['rating'];
		// see if the user has rated this question before
		$oldRating = $this->mcqRatedByUser($id);

		if ($oldRating === 2) {
			// the user has not rated yet
			$rating += ($rated === 1) ? (1) : (-1);
			$this->entryCreate(array('id' => $id, 'user' => $this->getCurrentUserId(), 'rated' => $rated), 'mcqs_rated', false);
		} else {
			// the user has rated before
			if ($oldRating !== $rated) {
				// if the old rating and the new rating were the same nothing would change
				// if they were not the rating would change by two in the direction of the new rating
				$rating += ($rated === 1) ? (2) : (-2);
			}
			$this->entryUpdate(array('rated' => $rated, 'id' => $id), 'mcqs_rated', array('id' => $id, 'user' => $this->getCurrentUserId()), false);
		}

		// update the rating in table mcqs
		// do not create an entry in the _history table
		$this->entryUpdate(array('rating' => $rating, 'id' => $id), 'mcqs', array('id' => $id), false);
	}

	/**
	 * 
	 *
	 * @param string $id
	 * @param array $data
	 * @return integer 0: rated negative, 1: rated positive, 2: not rated
	 */
	private function mcqRatedByUser($id, $data = array()) {
		if (!empty($data) && isset($data['rated'])) {
			return $data['rated'];
		}

		$rated = $this->entryFetch(
			array('id' => $id, 'user' => $this->getCurrentUserId()),
			'mcqs_rated');
		if (!empty($rated)) {
			return $rated['rated'];
		} else {
			return 2;
		}
	}

	public function controllerMcqsGet(\Symfony\Component\HttpFoundation\Request $request, string $id, string $version = 'current') {
		$this->checkPermissionsRole($request->get("_route"));
		$this->checkParams(array($id), array('numeric'));

		if ($version === 'current') {
			$entry = $this->entryFetchById($id, 'mcqs');
		} else {
			$entry = $this->entryFetchVersionByDate($id, $version, 'mcqs');
		}

		if (empty($entry)) {
			$this->sendError(self::RESPONSE_ITEM_NOT_FOUND);
		}

		$entry = $this->mcqsPrepareForClient(array($entry))[0];

		$entry = $this->entriesPrepareForClient(array($entry), 'mcqs')[0];
		return $this->app->json($entry, 200);
	}

	public function controllerMcqsGetMulti(\Symfony\Component\HttpFoundation\Request $request, string $modules, string $rating, string $generation, string $original, string $number) {
		$this->checkPermissionsRole($request->get("_route"));
		$entries = $this->mcqsFetch($modules, $rating, $generation, $original, $number);

		$entries = $this->entriesPrepareForClient($entries, 'mcqs');
		return $this->app->json($entries, 200);
	}

	public function controllerMcqsPost(\Symfony\Component\HttpFoundation\Request $request) {
		$data = json_decode($request->getContent(), true);

		$data = $this->mcqsPrepareForDb(array($data))[0];

		return $this->controllerDefaultPost($request, $data);
	}

	public function controllerMcqsPatch(\Symfony\Component\HttpFoundation\Request $request, string $id) {
		$data = json_decode($request->getContent(), true);
		$this->checkParams(array($id), array('numeric'));

		// check if only the rating should be updated
		if (count($data) == 2 && isset($data['rated'])) {
			$this->mcqRate($id, $data['rated']);

			$entry = $this->entryFetchById($id, 'mcqs');
			$entry['rated'] = $data['rated'];
			$entry = $this->entriesPrepareForClient(array($entry), $table)[0];
			return $this->app->json($entry, 200);
		} elseif (count($data) == 2 && isset($data['discussion'])) {
			$this->entryUpdate(array('discussion' => $data['discussion'], 'id' => $id), 'mcqs', array('id' => $id), false);
			$entry = $this->entryFetchById($id, 'mcqs');
			$entry = $this->mcqsPrepareForClient(array($entry))[0];
			$entry = $this->entriesPrepareForClient(array($entry), 'mcqs')[0];
			return $this->app->json($entry, 200);
		} else {
			// on updating the question the ratings loose their validity
			$this->entriesDelete($id, 'mcqs_rated', false);
			$data = $this->mcqsPrepareForDb(array($data))[0];
			return $this->controllerDefaultPatch($request, $id, $data);
		}
	}

	public function controllerMcqsDelete(\Symfony\Component\HttpFoundation\Request $request, string $id) {
		$this->checkParams(array($id), array('numeric'));
		$this->entriesDelete($id, 'mcqs_rated', false);
		return $this->controllerDefaultDelete($request, $id);
	}

	/*
	 * Controllers for the modules table.
	 */

	public function controllerModulesGetMulti(\Symfony\Component\HttpFoundation\Request $request) {
		$this->checkPermissionsRole($request->get("_route"));

		$entries = $this->connection->fetchAll(
			'SELECT * FROM ' . $this->prefix . 'modules'
		);

		$entries = $this->entriesPrepareForClient($entries, 'modules');
		return $this->app->json($entries, 200);
	}

	/*
	 * Controllers for the sessions table.
	 */

	private function sessionsPrepareForClient(array $sessions) {
		$nonNumericAttributes = array('questions', 'answers', 'status', 'name');
		foreach ($sessions as $key => $session) {
			$sessions[$key]['questions'] = array_map('intval', explode(';', $session['questions']));
			$sessions[$key]['answers'] = array_map('intval', explode(';', $session['answers']));
			$sessions[$key]['status'] = array_map('intval', explode(';', $session['status']));
			foreach ($session as $i => $attr) {
				if (!in_array($i, $nonNumericAttributes)) {
					$sessions[$key][$i] = intval($attr);
				}
			}
		}
		return $sessions;
	}

	private function sessionsPrepareForDb(array $sessions) {
		foreach ($sessions as $key => $session) {
			isset($session['questions']) &&
				$sessions[$key]['questions'] = implode(';', $session['questions']);
			isset($session['answers']) &&
				$sessions[$key]['answers'] = implode(';', $session['answers']);
			isset($session['status']) &&
				$sessions[$key]['status'] = implode(';', $session['status']);
		}
		return $sessions;
	}

	public function controllerSessionsGet(\Symfony\Component\HttpFoundation\Request $request, string $id) {
		$this->checkPermissionsRole($request->get("_route"));
		$this->checkParams(array($id), array('numeric'));

		$entry = $this->entryFetchById($id, 'sessions');

		$entry = $this->sessionsPrepareForClient(array($entry))[0];

		$entry = $this->entriesPrepareForClient(array($entry), 'sessions')[0];
		return $this->app->json($entry, 200);
	}

	public function controllerSessionsGetMulti(\Symfony\Component\HttpFoundation\Request $request) {
		$this->checkPermissionsRole($request->get("_route"));

		$entries = $this->connection->fetchAll(
			'SELECT * FROM ' . $this->prefix . 'sessions WHERE user = ?',
			array($this->getCurrentUserId())
		);

		$entries = $this->sessionsPrepareForClient($entries);

		$entries = $this->entriesPrepareForClient($entries, 'sessions');
		return $this->app->json($entries, 200);
	}

	public function controllerSessionsPost(\Symfony\Component\HttpFoundation\Request $request) {
		$data = json_decode($request->getContent(), true);
		if (count($data['modules']) > 4) {
			$this->sendError(self::RESPONSE_TOO_MANY_MODULES);
		}
		
		$entries = $this->mcqsFetch(implode(',', $data['modules']), $data['rating'], $data['generation'], $data['original'], $data['number']);

		if (empty($entries)) {
			$this->sendError(self::RESPONSE_NO_QUESTIONS);
		}

		$session['questions'] = array();
		$session['answers'] = array();
		$session['status'] = array();

		foreach ($entries as $key => $entry) {
			$session['questions'][$key] = $entry['id'];
			$session['answers'][$key] = '0';
			$session['status'][$key] = '2';
		}

		$session['name'] = $data['name'];
		$session['user'] = $this->getCurrentUserId();
		$session['current'] = 0;
		$session['answered'] = 0;
		$session['correct'] = 0;
		$session['total'] = count($entries);

		$session = $this->sessionsPrepareForDb(array($session))[0];

		$session = $this->entriesPrepareForClient(array($session), 'sessions')[0];
		return $this->controllerDefaultPost($request, $session);
	}

	public function controllerSessionsPatch(\Symfony\Component\HttpFoundation\Request $request, string $id) {
		$route = $request->get('_route');
		$table = $this->getTableForRoute($route);
		isset($data['id']) || $data['id'] = $id;
		$this->checkPermissionsRole($route);
		$this->checkParams(array($id), array('numeric'));
		$data = json_decode($request->getContent(), true);

		$data = $this->sessionsPrepareForDb(array($data))[0];


		// check if the item exists
		$entry = $this->entryFetchById($id, $table);
		if (empty($entry)) {
			// item does not exist
			$this->sendError(self::RESPONSE_ITEM_NOT_FOUND);
		}
		// item exists so we update it
		// set the owner in case it has not been submitted
		(!isset($data['user']) && isset($entry['user'])) && $data['user'] = $entry['user'];
		$this->checkPermissionsOwner($route, $data, $table);
		$data = $this->checkData($data, $table, false);

		if ($this->entryUpdate($data, $table, array('id' => $data['id'])) < 1) {
			$this->sendError(self::RESPONSE_ITEM_NOT_CHANGED);
		}

		$entry = $this->entryFetchById($id, $table);
		$entry = $this->sessionsPrepareForClient(array($entry))[0];

		$entry = $this->entriesPrepareForClient(array($entry), $table)[0];
		return $this->app->json($entry, 200);
	}

	/*
	 * Controllers for the exams table.
	 */

	public function controllerExamsGetMulti(\Symfony\Component\HttpFoundation\Request $request) {
		$this->checkPermissionsRole($request->get("_route"));

		$entries = $this->connection->fetchAll(
			'SELECT * FROM ' . $this->prefix . 'exams'
		);

		$entries = $this->entriesPrepareForClient($entries, 'exams');
		return $this->app->json($entries, 200);
	}

	/*
	 * Controllers for the protocolls table.
	 */

	public function controllerProtocollsGetMulti(\Symfony\Component\HttpFoundation\Request $request, string $id) {
		$this->checkPermissionsRole($request->get("_route"));

		$entries = $this->connection->fetchAll(
			'SELECT * FROM ' . $this->prefix . 'protocolls WHERE exam = ?',
			array($id)
		);

		$entries = $this->entriesPrepareForClient($entries, 'exams');
		return $this->app->json($entries, 200);
	}

	/*
	 * Controllers for the generations table.
	 */

	public function controllerGenerationsGetMulti(\Symfony\Component\HttpFoundation\Request $request) {
		$this->checkPermissionsRole($request->get("_route"));

		$entries = $this->connection->fetchAll(
			'SELECT * FROM ' . $this->prefix . 'generations'
		);

		$entries = $this->entriesPrepareForClient($entries, 'generations');
		return $this->app->json($entries, 200);
	}

	/*
	 * Controllers for the texts table.
	 */

	public function controllerTextsGetMulti(\Symfony\Component\HttpFoundation\Request $request) {
		$this->checkPermissionsRole($request->get("_route"));

		$entries = $this->connection->fetchAll(
			'SELECT * FROM ' . $this->prefix . 'texts'
		);

		$entries = $this->entriesPrepareForClient($entries, 'texts');
		return $this->app->json($entries, 200);
	}

	public function controllerTextsGetByPath(\Symfony\Component\HttpFoundation\Request $request, string $path) {
		$this->checkPermissionsRole($request->get("_route"));

		$entry = $this->entryFetch(array('path' => $path), 'texts');

		$entry = $this->entriesPrepareForClient(array($entry), 'texts')[0];
		return $this->app->json($entry, 200);
	}

	/*
	 * Controllers for other api-related stuff.
	 */

	public function controllerLogin(\Symfony\Component\HttpFoundation\Request $request) {
		$vars = json_decode($request->getContent(), true);
		$username = $this->app->escape($vars['username']);
		$password = $this->app->escape($vars['password']);

		if (empty($username) || empty($password)) {
			$this->sendError(self::RESPONSE_MISSING_USERNAME_OR_PASSWORD);
		}

		$user = $this->app['users']->loadUserByUsername($username);

		if (empty($user)) {
			$this->sendError(self::RESPONSE_BAD_USERNAME_OR_PASSWORD);
		}

		// refresh login
		// hack way to trigger NOW but it is only one more query for each login
		$now = new \DateTime('NOW', new \DateTimeZone('UTC'));
		$data['id'] = $user->getId();
		//$data['login'] = $now->format('Y-m-d H:i:s');
		$data['enabled'] = $user->isEnabled() ? 0 : 1;
		$this->entryUpdate($data, 'users', array('id' => $data['id']), false);
		$data['enabled'] = $user->isEnabled() ? 1 : 0;
		$this->entryUpdate($data, 'users', array('id' => $data['id']), false);
		
		if (!$this->app['users']->isPasswordValid($password, $user->getPassword())) {
			$this->sendError(self::RESPONSE_BAD_USERNAME_OR_PASSWORD);
		} else {
			$response = [
				'id' => intval($user->getId()),
				'name' => $user->getUsername(),
				'admin' => intval($user->isAdmin()),
				'role' => $user->getRoles(),
				'enabled' => intval($user->isEnabled()),
				'token' => $this->app['security.jwt.encoder']->encode(['name' => $username]),
			];
		}
		return $this->app->json($response, 200);
	}

	/**
	 * Generate a random string, using a cryptographically secure 
	 * pseudorandom number generator (random_int)
	 * 
	 * from: https://stackoverflow.com/questions/4356289/php-random-string-generator/31107425#31107425
	 * 
	 * @param int $length      How many characters do we want?
	 * @param string $keyspace A string of all possible characters
	 *                         to select from
	 * @return string
	 */
	function generateRandomString(int $length, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
		$str = '';
		$max = mb_strlen($keyspace, '8bit') - 1;
		for ($i = 0; $i < $length; ++$i) {
			$str .= $keyspace[random_int(0, $max)];
		}
		return $str;
	}

	public function controllerResetPassword(\Symfony\Component\HttpFoundation\Request $request) {
		$vars = json_decode($request->getContent(), true);
		$email = $this->app->escape($vars['email']);

		if (empty($email)) {
			$this->sendError(self::RESPONSE_INVALID_REQUEST);
		}

		$entry = $this->entryFetch(array('email' => $email), 'users');
		if (!empty($entry)) {
			$password = $this->generateRandomString(10);
			$data['id'] = $entry['id'];
			$data['password'] = $this->app['users']->encodePassword($password, empty($entry['salt']) ? null : $entry['salt']);
			$data['enabled'] = 0;

			mail($email, '[Olm] PW', "Hallo " . $entry['username'] . ",\nbitte melde Dich mit: $password an und ändere Dein Passwort!");

			$this->entryUpdate($data, 'users', array('id' => $data['id']), false);
		}

		return $this->app->json(array('Email sent'), 200);
	}

	public function controllerTest(\Symfony\Component\HttpFoundation\Request $request) {
		return $this->app->json(array('data' => 'Nice to meet you! :)'), 200);
	}

	public function controllerTeapot(\Symfony\Component\HttpFoundation\Request $request) {
		return $this->app->json(array('message' => 'I can\'t answer your request - I\'m a happy teapot! :)'), 419);
	}

	private function checkTableExists($table) {
		// check if the tables exist
		$sql = "SHOW TABLES LIKE '" . $table . "'";
		$stmt = $this->connection->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		if(!empty($result)) {
			$this->sendError(self::RESPONSE_STUPID_REQUEST);
		}
	}

	private function runSqlScript($file) {
		$log = array();

		$fh = fopen($file,"r");
		$content = fread($fh, filesize($file));
		$queries = explode(';', $content);

		foreach ($queries as $query) {
			if (empty(trim($query))) {
				continue;
			}
			$stmt = $this->connection->prepare($query);
			$stmt->execute();
			$log[] = array(
				'query' => $query,
			);
		}

		return $log;
	}

	public function controllerSetup(\Symfony\Component\HttpFoundation\Request $request) {
		$this->checkTableExists('olm_users');
		$result = $this->runSqlScript('../src/db/setup.sql');
		return $this->app->json($result, 200);
	}

	public function controllerMigrateUsers(\Symfony\Component\HttpFoundation\Request $request) {
		$this->checkTableExists('tmp_users');
		$result = $this->runSqlScript('../src/db/migrate_users.sql');
		return $this->app->json($result, 200);
	}

	public function controllerMigrateMcqs(\Symfony\Component\HttpFoundation\Request $request) {
		$this->checkTableExists('tmp_module');
		$result = $this->runSqlScript('../src/db/migrate_mcqs.sql');
		
		$entries = $this->connection->fetchAll('SELECT * FROM tmp_mcq');
		$done = array();
		foreach ($entries as $entry) {
			$data = array();

			for ($i = 1; $i <= 10; $i++) {
				if (!empty(trim($entry['a' . $i]))) {
					$data['answers'][] = html_entity_decode($entry['a' . $i], ENT_COMPAT | ENT_HTML5, 'UTF-8');
				}
				unset($entry['a' . $i]);
			}

			$data['question'] = html_entity_decode($entry['q'], ENT_COMPAT | ENT_HTML5, 'UTF-8');

			$first = substr(trim($data['question']), 0, 1);
			if ($first == '-' || $first == '*') {
				$data['question'] = substr(trim($data['question']), 1);
			}

			$data['solution'] = $entry['s'] - 1;

			if ($data['solution'] > count($data['answers']) - 1) {
				$data['answers'][] = 'Es wurde noch keine Antwort als richtig markiert';
				$data['solution'] = count($data['answers']) - 1;
			}

			$entry['raw'] = $this->mcqStringFromParsed($data);

			unset($entry['q']);
			unset($entry['s']);
			unset($entry['version']);

			$entry = $this->mcqsPrepareForDb(array($entry))[0];

			$entry['rating'] = 0;
			$entry['discussion'] = '';

			$ok = ($this->entryCreate($entry, 'mcqs', true) > -1);

			$done[] = array('id' => $entry['id'], 'done' => $ok);
		}

		return $this->app->json($done, 200);
	}
}
