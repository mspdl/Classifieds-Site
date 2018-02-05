<?php 
require 'config.php';
if(empty($_SESSION['cLogin'])) {
	header("Location: login.php");
	exit;
}

require 'classes/adverts.class.php';
$a = new Adverts();

if(isset($_GET['id']) && !empty($_GET['id'])) {
	$id_advert = $a->deletePhoto($_GET['id']);
}

if(isset($id_advert)) {
	header("Location: edit-advert.php?id=".$id_advert);
} else {
	header("Location: my-adverts.php");
}

?>