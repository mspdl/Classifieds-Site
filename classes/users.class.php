<?php 

class Users {

	public function getTotalUsers() {
		global $pdo;

		$sql = $pdo->query("SELECT COUNT(*) as c FROM users");
		$row = $sql->fetch();

		return $row['c'];

	}


	public function register($name, $email, $password, $phone) {

		global $pdo;

		$sql = $pdo->prepare("SELECT id FROM users WHERE email = ?");
		$sql->execute(array($email));

		if($sql->rowCount() == 0) {
			$sql = $pdo->prepare("INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)");
			$sql->execute(array($name, $email, md5($password), $phone));
			return true;
		} else {
			return false;
		}

	}


	public function login($email, $password) {

		global $pdo;

		$sql = $pdo->prepare("SELECT id FROM users WHERE email = ? AND password = ?");
		$sql->execute(array($email, md5($password)));

		if($sql->rowCount() > 0) {
			$data = $sql->fetch();
			$_SESSION['cLogin'] = $data['id'];
			return true;
		} else {
			return false;
		}

	}

	
}

?>