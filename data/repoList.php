<?php

require_once('../classes/api.php');

header('Content-Type: application/json');

$api = new GithubApi();

// Fetch records from DB to display in listing area
$data = $api->fetchDBRepoList();
echo json_encode(['aaData'=>$data]);

?>