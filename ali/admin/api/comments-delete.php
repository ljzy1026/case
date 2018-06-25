<?php 

require '../../functions.php';

if (empty($_GET['id'])) {
	exit();
}

$id = $_GET['id'];

$rows = xiu_execute('DELETE	FROM comments WHERE id in ('. $id .');');
