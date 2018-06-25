<?php 

require '../../functions.php';

if (empty($_GET['id'])) {
	exit();
}

$id = $_GET['id'];

$rows = xiu_execute("UPDATE comments SET STATUS='approved' WHERE id in (". $id .');');