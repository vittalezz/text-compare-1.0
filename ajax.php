<?php
include($_SERVER['DOCUMENT_ROOT'] . '/functions.php');

$action = isset($_POST['action']) ? $_POST['action'] : '';
$textA = isset($_POST['textA']) ? $_POST['textA'] : '';
$textB = isset($_POST['textB']) ? $_POST['textB'] : '';

if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) OR strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
	header('Content-type: application/json');
	echo json_encode(['result' => 'error', 'error' => 'Необходимо отправить запрос'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	die();
}


if ($action == 'compare' && !empty($textA) && !empty($textB)) {
   $compare = new Compare();
   $resultCompare = $compare->startCompare($textA, $textB);
   header('Content-type: application/json');
   echo json_encode(['result' => 'OK', 'result_compare'=>$resultCompare], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
   die();
}

header('Content-type: application/json');
echo json_encode(['result' => 'error', 'error' => 'Не хватает входных данных'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
die();