<?php
namespace bmtmgr;

require_once dirname(__DIR__) . '/src/common.php';

if (isset($_GET['autocomplete']) && $_GET['autocomplete'] == 'json') {
	render_json(array_map(function($c) {
		return [
			'id' => $c->id,
			'textid' => $c->textid,
			'name' => $c->name,
			'text' => '(' . $c->textid . ') ' . $c->name,
		];
	}, Club::get_all()));
	exit();
}

$u = user\check_current();
$u->require_perm('admin');

render('club_list', [
	'user' => $u,
	'breadcrumbs' => [
		['name' => 'Vereine', 'path' => 'club/']
	],
	'clubs' => Club::get_all('ORDER BY ID ASC'),
]);