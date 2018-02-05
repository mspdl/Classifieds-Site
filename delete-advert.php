<?php 
require 'config.php';
if(empty($_SESSION['cLogin'])) {
	header("Location: login.php");
	exit;
}

require 'classes/adverts.class.php';
$a = new Adverts();

if(isset($_GET['id']) && !empty($_GET['id'])) {
	$a->deleteAdvert($_GET['id']);
}
header("Location: my-adverts.php");

?>