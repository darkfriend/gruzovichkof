<?php
require_once __DIR__.'/../../vendor/autoload.php';

$success = true;
$msg = '';
$data = [];

try {
	$q = htmlspecialchars($_REQUEST['q']);
	$data = \gruzovichkof\controllers\SearchController::getInstance()->query($q);
} catch (\Exception $e) {
	$msg = $e->getMessage();
	$success = false;
}

header('Content-Type: application/json; charset=UTF-8');
echo json_encode([
	'success' => $success,
	'msg' => $msg,
	'data' => $data,
]);