<?php

$query = $_GET['query'];
'SELECT * FROM table WHERE a = ' . htmlentities($query);

function user_create() {
	'INSERT INTO users () ()';
	$user = [
		'id' => mysql_insert_id()
	];
}

$user = [
	'id' => '';
	'email' => '',
];

$item = [
	'id' => '',
	'name' => '',
	'image' => '',
	'description' => '',
];

function user_save($user) {
	'UPDATE users SET VALUE = WHERE id = ' $user['id'];
}

display_admin_table($user, 'users');
display_admin_table($item, 'items');

function display_admin_table($obj, $table) {
	echo '<form action="update.php" method="post">';
	echo '<input type="hidden" name="table" value="' . $table . '">';
	foreach ($obj as $k => $v) {
		echo '<tr><td>' . $k . '</td><td><input type="text" value="' . $v . '"></td></tr>';
	}
}

switch ($_POST['table']) {
	case 'users':
		mysqli_query('UPDATE users SET VALUE = WHERE id = ' $user['id']);
		break;
}
