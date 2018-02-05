<?php 

class Categories {


	public function getList() {
		$array = array();
		global $pdo;

		$sql = $pdo->query("SELECT * FROM categories");
		if($sql->rowCount() > 0) {
			$array = $sql->fetchAll();
		}

		return $array;
	}


}

?>