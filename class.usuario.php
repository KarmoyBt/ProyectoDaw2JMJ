<?php

	namespace OZ;
	
	abstract class User {
		
		const TiempoSession = 600;	
		
		private static $_db;
		private static $_init;
		

        //Array info usuario 
		private static $tablaUsuario = array(
			'table' => 'users',														
			'id' => 'userID',															
			'login' => 'login',													
			'pass' => 'password',												
			'key' => 'session_key',												
			'fields' => array('group', 'name', 'mail')	
		);
		
		// Array de  $ArError es	
		private static $ArError = array();
		

		
		//Conectar A base
		public function init($db_drvr, $db_host, $db_name, $db_user, $db_pass) { 
			try {
			    self::$_db = new \PDO('mysql:host=' . $db_host . ';dbname=' . $db_name, $db_user, $db_pass);
				self::$_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); 
			}  
			catch(\PDOException $e) {  
				die($e->getMessage());
			}
			
			
			if(!empty(session_id())) {
				$session_check = true;
		    }
			if(!$session_check) {
				die('Error en la sesion.');
			}

			if(!empty(self::$tablaUsuario['table']) && !empty(self::$tablaUsuario['id']) && !empty(self::$tablaUsuario['login']) && !empty(self::$tablaUsuario['pass'])) {
				self::$_init = true;
			}
			return self::$_init;
		}
		
    
        //Sacar datos del Usuario a partir de la Id,  En forma de array 
	    public function getByID($UserID) {
			
			if(self::$_init) {
				$queryPDO = self::$_db->prepare('SELECT * FROM `' . self::$tablaUsuario['table'] . '` WHERE `' . self::$tablaUsuario['id'] . '` = :id;');
				$queryPDO->setFetchMode(\PDO::FETCH_ASSOC);
				$queryPDO->execute(array('id' => $UserID));
				if($lot = $queryPDO->fetch()) {
					$ArQuey = array(
						'id' => $lot[self::$tablaUsuario['id']],
						'login' => $lot[self::$tablaUsuario['login']],
						'key' =>  $lot[self::$tablaUsuario['key']],
					);
					/* other fields */
					if(!empty(self::$tablaUsuario['fields'])) {
						foreach(self::$tablaUsuario['fields'] as $field) {
							$ArQuey[$field] = !empty($lot[$field]) ? $lot[$field] : '';
						}
					}
				}
			}
			return $ArQuey;
		}
	
	    //lo mismo que la getByID pero sacando el login 
		public function getByLogin($login) {
		    
			if(self::$_init) {
				$queryPDO = self::$_db->prepare('SELECT * FROM `' . self::$tablaUsuario['table'] . '` WHERE `' . self::$tablaUsuario['login'] . '` = :login;');
				$queryPDO->setFetchMode(\PDO::FETCH_ASSOC);
				$queryPDO->execute(array('login' => $login));
				if($lot = $queryPDO->fetch()) {

					$ArQuey = array(
						'id' => $lot[self::$tablaUsuario['id']],
						'login' => $lot[self::$tablaUsuario['login']],
						'key' =>  $lot[self::$tablaUsuario['key']],
					);
					if(!empty(self::$tablaUsuario['fields'])) {
						foreach(self::$tablaUsuario['fields'] as $field) {
							$ArQuey[$field] = !empty($lot[$field]) ? $lot[$field] : '';
						}
					}
				}
			}
			return $ArQuey;
		}
		
		/**
		* Get users list.
		*
		* @return array Returns array of users data.
		*/
		public function getList() {
			$list = array();
			if(self::$_init) {
				$queryPDO = self::$_db->query('SELECT * FROM `' . self::$tablaUsuario['table'] . '` ORDER BY `' . self::$tablaUsuario['id'] . '`;');
				$queryPDO->setFetchMode(\PDO::FETCH_ASSOC);
				while($lot = $queryPDO->fetch()) {
					/* main fields, except password */
					$ArQuey = array(
						'id' => $lot[self::$tablaUsuario['id']],
						'login' => $lot[self::$tablaUsuario['login']]
					);
					/* other fields */
					if(!empty(self::$tablaUsuario['fields'])) {
						foreach(self::$tablaUsuario['fields'] as $field) {
							$ArQuey[$field] = !empty($lot[$field]) ? $lot[$field] : '';
						}
					}
					$list[] = $ArQuey;
				}
			}
			return $list;
		}

		/**
		* Add new user.
		*
		* @param array $data			User data array with keys according definition.
		*
		* @return int|false Returns new user ID or false if error occurred.
		*/
		
		//Crear nuevo usuario 
		//data tiene $login y $password 
		public function add($data) {
		    
			self::$ArError = array();
			if(self::$_init) {
				$ArQuey_data = array(
					'login' => !empty($data['login']) ? $data['login'] : '',
					'pass' => !empty($data['pass']) ? self::passwordHash($data['pass']) : ''
				);
				$sql_set = array(
					'`' . self::$tablaUsuario['login'] . '` = :login',
					'`' . self::$tablaUsuario['pass'] . '` = :pass'
				);

				if(!empty(self::$tablaUsuario['fields'])) {
					foreach(self::$tablaUsuario['fields'] as $field) {
						$ArQuey_data[$field] = !empty($data[$field]) ? $data[$field] : null;
						$sql_set[] = '`' . $field . '` = :' . $field;
					}
				}
				if(!empty($ArQuey_data['login']) && !empty($ArQuey_data['pass'])) {
					if(!self::loginExists($ArQuey_data['login'])) {
						$queryPDO = self::$_db->prepare('INSERT INTO `' . self::$tablaUsuario['table'] . '` SET ' . implode(', ', $sql_set) . ';');
						$queryPDO->setFetchMode(\PDO::FETCH_ASSOC);
						if($queryPDO->execute($ArQuey_data)) {
							$queryPDO = self::$_db->lastInsertId();
						}
						else {
							self::$ArError[] = 'DB error';
						}//Pasarlo AJAX
					}
					else {
						self::$ArError[] = 'Login alrady exists';
					}//Pasarlo AJAX
				}
				else {
					self::$ArError[] = 'Login and Password are required fields';
				}//Pasarlo AJAX
			}
			return $queryPDO;
		}
		
		/**
		* Update user data (excep password and login).
		*
		* @param int $ArQueyID			User ID.
		* @param array $data			User data array with keys according definition. Some data could be skipped.
		*
		* @return boolean Returns result of update.
		*/
		public function update($ArQueyID, $data) {
			$queryPDO = false;
			self::$ArError = array();
			if(self::$_init) {
				$ArQuey_data = array('id' => $ArQueyID);
				$sql_set = array();
				if(!empty($data)) {
					foreach($data as $key => $val) {
						if(in_array($key, self::$tablaUsuario['fields'])) {
							$ArQuey_data[$key] = !empty($val) ? $val : null;
							$sql_set[] = '`' . $key . '` = :' . $key;
						}
					}
					$queryPDO = self::$_db->prepare('UPDATE `' . self::$tablaUsuario['table'] . '` SET ' . implode(', ', $sql_set) . ' WHERE `' . self::$tablaUsuario['id'] . '` = :id;');
					$queryPDO->setFetchMode(\PDO::FETCH_ASSOC);
					if($queryPDO->execute($ArQuey_data)) {
						$queryPDO = true;
					}
					else {
						self::$ArError[] = 'DB error';
					}
				}
			}
			return $queryPDO;
		}
		
		
		
		//Eliminar Usuario 
		public function delete($ArQueyID) {
			$queryPDO = false;
			self::$ArError = array();
			if(self::$_init) {
				$queryPDO = self::$_db->prepare('DELETE FROM `' . self::$tablaUsuario['table'] . '` WHERE `' . self::$tablaUsuario['id'] . '` = :id;');
				$queryPDO->setFetchMode(\PDO::FETCH_ASSOC);
				if($queryPDO->execute(array('id' => $ArQueyID))) {
					$queryPDO = true;
				}
				else {
					self::$ArError[] = 'DB error';
				}
			}
			return $queryPDO;
		}
		


		//Comprobar si existe el usuario
		public function loginExists($login, $ArQueyID = 0) {
		    $queryPDO=false;

				$queryPDO = self::$_db->prepare('SELECT `' . self::$tablaUsuario['id'] . '` FROM `' . self::$tablaUsuario['table'] . '` WHERE `' . self::$tablaUsuario['login'] . '` = :login AND `' . self::$tablaUsuario['id'] . '` <> :id;');
				$queryPDO->setFetchMode(\PDO::FETCH_ASSOC);
				$queryPDO->execute(array('id' => $ArQueyID, 'login' => $login));
				if($queryPDO->rowCount() > 0) {
					$queryPDO = true;
				}
			
			return $queryPDO;
		}


	

	
	
		
	
		
		
		/**
		* Log in user in system. Start user session.
		*
		* @param string $login		User login.
		* @param string $pass			User password.
		*
		* @return boolean Returns result of log in.
		*/
		public function login($login, $pass) {
			$queryPDO = false;
			if(self::$_init) {
				$queryPDO = self::$_db->prepare('SELECT `' . self::$tablaUsuario['id'] . '`, `' . self::$tablaUsuario['pass'] . '` FROM `' . self::$tablaUsuario['table'] . '` WHERE `' . self::$tablaUsuario['login'] . '` = :login;');
				$queryPDO->setFetchMode(\PDO::FETCH_ASSOC);
				$queryPDO->execute(array('login' => $login));
				if($lot = $queryPDO->fetch()) {
					if(self::passwordCheck($pass, $lot[self::$tablaUsuario['pass']])) {
						session_regenerate_id();
						$queryPDO = true;
						$key = md5(microtime(true));
						$_SESSION['user']['id'] = $lot[self::$tablaUsuario['id']];
						$_SESSION['user']['key'] = $key;
						$_SESSION['user']['time'] = time() + self::TiempoSession;
						$queryPDO = self::$_db->prepare('UPDATE `' . self::$tablaUsuario['table'] . '` SET `' . self::$tablaUsuario['key'] . '` = :key WHERE `' . self::$tablaUsuario['id'] . '` = :id;');
						$queryPDO->setFetchMode(\PDO::FETCH_ASSOC);
						$queryPDO->execute(array('key' => $key, 'id' => $lot[self::$tablaUsuario['id']]));
					}
				}
			}
			return $queryPDO;
		}
		

		//funcion de logout 
		public function logout() {
			$_SESSION['user'] = null;
			session_regenerate_id();
			return true;
		}
		//Array de errores
		public function getError() {
			return self::$ArError;
		}
		
		//Comprobar Contrasena 
		public function passwordCheck($pass, $hash) {
			if($hash == md5($pass)) {
				$ok = true;
			}
			return $ok;
		}

		/**
		* Check user session.
		*
		* @return boolean Returns result od check. If false than log out user.
		*/
		public function check() {
			$queryPDO = false;
			if(self::$_init) {
				$now = time();
				if(!empty($_SESSION['user']['id']) && !empty($_SESSION['user']['key'])) {
					if($now < $_SESSION['user']['time']) {
						$now = time();
						$ArQuey = self::getByID($_SESSION['user']['id']);
						if($ArQuey['key'] == $_SESSION['user']['key']) {
							$_SESSION['user']['time'] = time() + self::TiempoSession;
							$queryPDO = true;
						}
					}
					
					if(!$queryPDO) {
						self::logout();
					}
				}
			}
			return $queryPDO;
		}


	}