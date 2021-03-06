<?php
namespace bmtmgr;
require_once dirname(__DIR__) . '/src/common.php';

$u = user\check_current();
$u->require_perm('admin');

utils\require_get_params(['tournament_id']);
$tournament = Tournament::by_id($_GET['tournament_id']);
$season = $tournament->get_season();

render('entry_dialog_import', [
	'user' => $u,
	'breadcrumbs' => [
		['name' => 'Ligen', 'path' => 'season/'],
		['name' => $season->name, 'path' => 'season/' . $season->id . '/'],
		['name' => $tournament->name, 'path' => 't/' . $tournament->id . '/'],
		['name' => 'Importieren', 'path' => 't/' . $tournament->id . '/dialog_import'],
	],
	'season' => $season,
	'tournament' => $tournament,
]);