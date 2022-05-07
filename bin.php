<?php 

include_once("./database.php");

if ($argv[1] == "nuke") {
    R::nuke();
    die();
}

if ($argv[1] == "wipe_tables") {
    R::wipeAll();
}

if ($argv[1] == "list_tables") {
    $listOfTables = R::inspect();
    die(json_encode($listOfTables));
}

if ($argv[1] == "list_fields") {
    $fields = R::inspect($argv[2]);
    die(json_encode($fields));
}

if ($argv[1] == "create_demo_data") {
    $aProjects = [];
    $aDebugFiles = [];
    $aDebugMessages = [];

    for ($i=0; $i<10; $i++) {
        $oProject = R::dispense(T_PROJECTS);

        $oProject->created_at = date("Y-m-d H:i:s");
        $oProject->updated_at = date("Y-m-d H:i:s");
        $oProject->timestamp = time();
        $oProject->name = 'test_project_'.$i;
        $oProject->description = 'Найти бла бла бла бла бла бла бла бла бла бла бла бла';
        $oProject->path = 'path/to/file';
        $oProject->path_to_debug_log = 'path/to/file';

        R::store($oProject);

        $aProjects[] = $oProject;
    }

    for ($i=0; $i<100; $i++) {
        $oDebugFile = R::dispense(T_DEBUG_FILES);

        $oDebugFile->created_at = date("Y-m-d H:i:s");
        $oDebugFile->updated_at = date("Y-m-d H:i:s");
        $oDebugFile->timestamp = time();
        $oDebugFile->file_name = time();
        $oDebugFile->name = date("Y-m-d H:i:s")." ".$i;

        $oDebugFile->tprojects = $aProjects[random_int(0, count($aProjects)-1)];

        $oDebugFile->setMeta("buildcommand.unique" , array(array('file_name')));
        // $oDebugFile->setMeta("buildcommand.unique" , array(array('timestamp')));

        R::store($oDebugFile);

        $aDebugFiles[] = $oDebugFile;
    }

    $aD = json_decode(file_get_contents("./tmp/message.json"), true);

    for ($i=0; $i<5000; $i++) {
        $oDebugMessage = R::dispense(T_DEBUG_MESSAGES);

        $oDebugMessage->tdebugfiles_id = $oDebugFile->id;
        $oDebugMessage->tprojects_id = $aRequest['project_id'];
        R::store($oDebugMessage);
        
        $oDebugMessage->import($aD);
        R::store($oDebugMessage);

        $aDebugMessages[] = $oDebugMessage;
    }
}

/*
{"sType":"info","oBackTrace":{"file":"\/app\/core\/DB.class.php","line":152,"function":null},"aBackTrace":[{"file":"\/var\/www\/app\/Debugger.class.php","line":734,"function":"fnDebugLog","class":"Debugger","type":"->"},{"file":"\/var\/www\/app\/Debugger.class.php","line":836,"function":"fnStopTimer","class":"Debugger","type":"->"},{"file":"\/var\/www\/app\/core\/DB.class.php","line":152,"function":"__stop"},{"file":"\/var\/www\/app\/includes\/DbSession.class.php","line":48,"function":"query","class":"DB","type":"->"},{"file":"\/var\/www\/app\/includes\/DbSession.class.php","line":30,"function":"destroy","class":"DbSession","type":"->"},{"function":"write","class":"DbSession","type":"->"}],"sMessage":"#timediff #sql_query 9 \u043c\u0441","oVars":{},"iTimestamp":1646806614075}
*/

if ($argv[1] == "create_scheme") {
    R::nuke();
    exec('rm -f ./sql/*.sql');

    die(json_encode([]));
}

if ($argv[1] == "list_projects") {
    echo PROJECTS_PATH."/*";
    echo "\n\n";
    $aFiles = glob(PROJECTS_PATH."/*");

    die(json_encode($aFiles));
}

if ($argv[1] == "generate_icons_css") {
    $aFiles = glob(__DIR__."/static/app/icons/*");
    $sStyle = "";

    foreach ($aFiles as $sFilePath) {
        $sFileName = basename($sFilePath, ".png");
        $sPath = "/static/app/icons/{$sFileName}.png";

        $sStyle .= "
.icon-{$sFileName}{
    background:url('{$sPath}') no-repeat center center;
}
";
    }

    file_put_contents(__DIR__."/static/app/icons.css", $sStyle);

    die(json_encode([]));
}


