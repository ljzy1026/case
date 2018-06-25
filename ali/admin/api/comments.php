<?php
require_once '../../functions.php';

$page = empty($_GET['page']) ? 1 : intval($_GET['page']);

$length = 30;
$offset = ($page - 1) * $length;

//获取列表各种数据，用于展示
$sql = sprintf('SELECT 
comments.*,posts.title AS post_title
FROM comments
INNER JOIN posts ON comments.post_id = posts.id
ORDER BY comments.created DESC
LIMIT %d,%d;',$offset,$length);
$comments = xiu_fetch_all($sql);

//获取总数据条数
$total_count = xiu_fetch_one('SELECT 
COUNT(1) AS total_count
FROM comments
INNER JOIN posts ON comments.post_id = posts.id;')['total_count'];
$total_pages = ceil($total_count / $length);

$json = json_encode(array(
	'comments' => $comments , 
	'total_pages' => $total_pages
));


header('Content-Type: application/json');

//返回的是一个对象
echo $json;

