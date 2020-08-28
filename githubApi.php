<?php
set_time_limit(0);
header('Content-Type: application/json');
require_once('classes/api.php');

$api = new GithubApi();
// Call Github API and insert response in DB
$result = $api->insertToDB();

echo json_encode($result);
?>