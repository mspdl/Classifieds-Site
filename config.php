<?php 
session_start();

global $pdo;

try {
	$pdo = new PDO("mysql:dbname=project_classified;host=localhost","root","root123");
} catch (PDOException $e) {
	echo "FAILED: ".$e->getMessage();
	exit;
}

?>