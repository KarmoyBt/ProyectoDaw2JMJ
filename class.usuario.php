<?php

	
	abstract class User {
		
		const TiempoSession = 600;	
		
		
		private static $_db;
		private static $_init;
		
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
					/* main fields, except password */
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
