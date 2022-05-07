<?php

if ($sMethod == 'scan_projects') {
    $aFiles = glob(PROJECTS_PATH."/*");

    foreach ($aFiles as $sFile) {
        if (is_dir($sFile)) {
            $oProject = R::findOrCreate(T_PROJECTS, [
                "path" => $sFile,
            ]);
            $oProject->name = basename($sFile);
            $oProject->path = $sFile;
            $oProject->path_to_debug_log = $sFile."/tmpFiles/dws_debugger";
        
            if (!$oProject->created_at) {
                $oProject->created_at = date("Y-m-d H:i:s");
                $oProject->updated_at = date("Y-m-d H:i:s");
                $oProject->timestamp = time();
            }

            R::store($oProject);
        }
    }

    die(json_encode([
        PROJECTS_PATH,
        $aFiles
    ]));
}

if ($sMethod == 'project_clean_all') {
    
}

if ($sMethod == 'list_projects') {
    $aResponse = R::findAll(T_PROJECTS);
    die(json_encode(array_values($aResponse)));
}

if ($sMethod == 'get_project') {
    $aResponse = R::findOne(T_PROJECTS, "id = ?", [$aRequest['id']]);
    die(json_encode($aResponse));
}

if ($sMethod == 'delete_project') {
    $oProject = R::findOne(T_PROJECTS, "id = ?", [$aRequest['id']]);

    fnBuildRecursiveProjectsTreeDelete($oProject);

    die(json_encode([]));
}

if ($sMethod == 'update_project') {
    $oProject = R::findOne(T_PROJECTS, "id = ?", [$aRequest['id']]);

    $oProject->name = $aRequest['name'];
    $oProject->description = $aRequest['description'];

    $oProject->created_at = date("Y-m-d H:i:s");
    $oProject->updated_at = date("Y-m-d H:i:s");
    $oProject->timestamp = time();
    $oProject->path = $aRequest['path'];
    $oProject->path_to_debug_log = $aRequest['path_to_debug_log'];
    $oProject->relative_path = $aRequest['relative_path'];
    $oProject->global_path = $aRequest['global_path'];
    $oProject->link_type = $aRequest['link_type'] ?: "vscode";


    R::store($oProject);

    die(json_encode([
        "id" => $oProject->id, 
        "name" => $oProject->name
    ]));
}

if ($sMethod == 'create_project') {
    $oProject = R::dispense(T_PROJECTS);

    $oProject->name = $aRequest['name'];
    $oProject->description = $aRequest['description'];

    $oProject->path = $aRequest['path'];
    $oProject->path_to_debug_log = $aRequest['path_to_debug_log'];
    $oProject->relative_path = $aRequest['relative_path'];
    $oProject->global_path = $aRequest['global_path'];
    $oProject->link_type = $aRequest['link_type'] ?: "vscode";

    R::store($oProject);

    die(json_encode([
        "id" => $oProject->id, 
        "name" => $oProject->name
    ]));
}
