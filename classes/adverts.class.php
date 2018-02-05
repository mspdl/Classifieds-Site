<?php 

class Adverts {


	public function getTotalAdverts($filters) {
		global $pdo;

		$filterstring = array('1=1');
		if(!empty($filters['category'])) {
			$filterstring[] = 'adverts.id_category = :id_category';
		}
		if(!empty($filters['value'])) {
			$filterstring[] = 'adverts.value BETWEEN :value1 AND :value2';
		}
		if(!empty($filters['status'])) {
			$filterstring[] = 'adverts.status = :status';
		}


		$sql = $pdo->prepare("SELECT COUNT(*) as c FROM adverts WHERE ".implode(' AND ', $filterstring));

		if(!empty($filters['category'])) {
			$sql->bindValue(':id_category', $filters['category']);
		}
		if(!empty($filters['value'])) {
			$value = explode('-', $filters['value']);
			$sql->bindValue(':value1', $value[0]);
			$sql->bindValue(':value2', $value[1]);
		}
		if(!empty($filters['status'])) {
			$sql->bindValue(':status', $filters['status']);
		}
		
		$sql->execute();
		$row = $sql->fetch();

		return $row['c'];
	}


	public function getLastAdverts($page, $perPage, $filters) {
		global $pdo;
		$offset = ($page - 1) * 2;

		$array = array();

		$filterstring = array('1=1');
		if(!empty($filters['category'])) {
			$filterstring[] = 'adverts.id_category = :id_category';
		}
		if(!empty($filters['value'])) {
			$filterstring[] = 'adverts.value BETWEEN :value1 AND :value2';
		}
		if(!empty($filters['status'])) {
			$filterstring[] = 'adverts.status = :status';
		}

		$sql = $pdo->prepare("SELECT
		*,
		(SELECT adverts_images.url FROM adverts_images WHERE adverts_images.id_advert = adverts.id LIMIT 1) as url,
		(SELECT categories.name FROM categories WHERE categories.id = adverts.id_category) as category
		FROM adverts WHERE ".implode(' AND ', $filterstring)." ORDER BY id DESC LIMIT $offset, $perPage");

		if(!empty($filters['category'])) {
			$sql->bindValue(':id_category', $filters['category']);
		}
		if(!empty($filters['value'])) {
			$value = explode('-', $filters['value']);
			$sql->bindValue(':value1', $value[0]);
			$sql->bindValue(':value2', $value[1]);
		}
		if(!empty($filters['status'])) {
			$sql->bindValue(':status', $filters['status']);
		}

		$sql->execute();

		if($sql->rowCount() > 0) {
			$array = $sql->fetchAll();
		}

		return $array;
	}


	public function getMyAdverts() {
		global $pdo;

		$array = array();
		$sql = $pdo->prepare("SELECT *, (SELECT adverts_images.url FROM adverts_images WHERE adverts_images.id_advert = adverts.id LIMIT 1) as url FROM adverts WHERE id_user = ?");
		$sql->execute(array($_SESSION['cLogin']));

		if($sql->rowCount() > 0) {
			$array = $sql->fetchAll();
		}

		return $array;
	}


	public function addAdvert($title, $category, $value, $description, $status) {
		global $pdo;

		$sql = $pdo->prepare("INSERT INTO adverts (title, id_category, id_user, description, value, status) VALUES (?, ?, ?, ?, ?, ?)");
		$sql->execute(array($title, $category, $_SESSION['cLogin'], $description, $value, $status));
	}


	public function deleteAdvert($id) {
		global $pdo;

		$sql = $pdo->prepare("DELETE FROM adverts_images WHERE id_advert = ?");
		$sql->execute(array($id));

		$sql = $pdo->prepare("DELETE FROM adverts WHERE id = ?");
		$sql->execute(array($id));
	}


	public function getAdvert($id) {
		global $pdo;

		$array = array();
		$sql = $pdo->prepare("SELECT
		*,
		(SELECT categories.name FROM categories WHERE categories.id = adverts.id_category) as category,
		(SELECT users.phone FROM users WHERE users.id = adverts.id_user) as phone
		FROM adverts WHERE id = ?");
		$sql->execute(array($id));

		if($sql->rowCount() > 0) {

			$array = $sql->fetch();
			$array['photos'] = array();

			$sql = $pdo->prepare("SELECT id,url FROM adverts_images WHERE id_advert = ?");
			$sql->execute(array($id));

			if($sql->rowCount() > 0) {
				$array['photos'] = $sql->fetchAll();
			} 

		}

		return $array;
	}


	public function editAdvert($title, $category, $value, $description, $status, $photos, $id) {
		global $pdo;

		$sql = $pdo->prepare("UPDATE adverts SET title = ?, id_category = ?, id_user = ?, description = ?, value = ?, status = ? WHERE id = ?");
		$sql->execute(array($title, $category, $_SESSION['cLogin'], $description, $value, $status, $id));

		if(count($photos) > 0) {
			for($i=0;$i<count($photos['tmp_name']);$i++) {
				$type = $photos['type'][$i];
				if(in_array($type, array('image/jpeg', 'image/png'))) {

					// Renaming the sent files
					$tmpname = md5(time().rand(0,9999)).'.jpg';
					move_uploaded_file($photos['tmp_name'][$i], 'assets/img/adverts/'.$tmpname);

					// readjusting the size of the images sent
					list($width_orig, $height_orig) = getimagesize('assets/img/adverts/'.$tmpname);
					$ratio = $width_orig/$height_orig;
					$width = 500;
					$height = 500;
					if ($width/$height > $ratio) {
						$width = $height*$ratio;
					} else {
						$height = $width/$ratio;
					}

					// creating the new image
					$img = imagecreatetruecolor($width, $height);
					if($type == 'image/jpeg') {
						$orig = imagecreatefromjpeg('assets/img/adverts/'.$tmpname);
					} elseif ($type == 'image/png') {
						$orig = imagecreatefrompng('assets/img/adverts/'.$tmpname);
					}
					imagecopyresampled($img, $orig, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
					imagejpeg($img, 'assets/img/adverts/'.$tmpname, 80);

					$sql = $pdo->prepare("INSERT INTO adverts_images (id_advert, url) VALUES (?, ?)");
					$sql->execute(array($id, $tmpname));

				}
			}
		}
	}

	public function deletePhoto($id) {
		global $pdo;
		$id_advert = 0;

		$sql = $pdo->prepare("SELECT id_advert FROM adverts_images WHERE id = ?");
		$sql->execute(array($id));

		if($sql->rowCount() > 0) {
			$row = $sql->fetch();
			$id_advert = $row['id_advert'];
		}

		$sql = $pdo->prepare("DELETE FROM adverts_images WHERE id = ?");
		$sql->execute(array($id));

		return $id_advert;
	}


}

?>