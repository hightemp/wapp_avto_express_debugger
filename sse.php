<?php

include_once("./database.php");
include_once("./lib.php");

header('Cache-Control: no-cache');
header("Content-Type: text/event-stream\n\n");

$oProject = R::findOne(T_PROJECTS, "id = ?", [$_REQUEST['project_id']]);

$oLastDebugFile = R::findOne(T_DEBUG_FILES, "ORDER BY id DESC LIMIT 1");
$iDBFileCount = R::count(T_DEBUG_FILES, "tprojects_id = ?", [$_REQUEST['project_id']]);

$aFiles = glob($oProject->path_to_debug_log."/*");

echo "event: files_count\n";
echo 'data: '.json_encode([ 
    "db_files_count" => $iDBFileCount,
    "files_count" => count($aFiles),
]);
echo "\n\n";