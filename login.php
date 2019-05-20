<?php
 
 public function getByLogin($login) {
			$user = false;
			if( $this->$_init) {
				$res =  $this->$_db->prepare('SELECT * FROM `' .  $this->$_definition['table'] . '` WHERE `' .  $this->$_definition['login'] . '` = :login;');
				$res->setFetchMode(\PDO::FETCH_ASSOC);
				$res->execute(array('login' => $login));
				if($lot = $res->fetch()) {
					/* main fields, except password */
					$user = array(
						'id' => $lot[ $this->$_definition['id']],
						'login' => $lot[ $this->$_definition['login']],
						'key' =>  $lot[ $this->$_definition['key']],
					);
					/* other fields */
					if(!empty( $this->$_definition['fields'])) {
						foreach( $this->$_definition['fields'] as $field) {
							$user[$field] = !empty($lot[$field]) ? $lot[$field] : '';
						}
					}
				}
			}
			return $user;
		}

public function logout() {
			$_SESSION['user'] = null;
			session_regenerate_id();
			return true;
		}

?>