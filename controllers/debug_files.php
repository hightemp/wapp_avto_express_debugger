<?php

// Таблицы

// R::fancyDebug( TRUE );

if ($sMethod == 'scan_debug_files') {
    R::begin();

    $oProject = R::findOne(T_PROJECTS, "id = ?", [$aRequest['project_id']]);

    $oLastDebugFile = R::findOne(T_DEBUG_FILES, "ORDER BY id DESC LIMIT 1");

    $aFiles = glob($oProject->path_to_debug_log."/*");
    $aAdded = [];

    foreach ($aFiles as $sFile) {
        if (is_file($sFile)) {
            $sFileName = basename($sFile);

            if ($oLastDebugFile && $oLastDebugFile->file_name > $sFileName) {
                continue;
            }

            $oDebugFile = R::findOrCreate(T_DEBUG_FILES, ["file_name" => $sFileName]);

            if (!$oDebugFile->created_at) {
                $oDebugFile->created_at = date("Y-m-d H:i:s");
                $oDebugFile->updated_at = date("Y-m-d H:i:s");
                $oDebugFile->timestamp = time();
            }

            $oDebugFile->file_name = $sFileName;
            $oDebugFile->name = date("Y-m-d H:i:s", $sFileName/1000);

            $oDebugFile->tprojects_id = $aRequest['project_id'];

            R::store($oDebugFile);

            $resH = fopen($sFile, "r");
            $sJSON = fread($resH, 1000);
            fclose($resH);

            $aInfo = json_decode($sJSON, true);
            $oDebugFile->import($aInfo);
            $oDebugFile->type = "DIRECT";
            if ($aInfo["bIsCli"]) {
                $oDebugFile->type = "CLI";
            } else if ($aInfo["bIsAjax"]) {
                $oDebugFile->type = "AJAX";
            }
            $oDebugFile->size = filesize($sFile);
            $oDebugFile->size_human = fnToHumanFileSize($oDebugFile->size);

            $sS = file_get_contents($sFile);
            $iC = substr_count($sS, "\n");
            $oDebugFile->count = $iC - 1;
            $oDebugFile->count_errors = 0;

            R::store($oDebugFile);

            $aL = explode("\n", $sS);
            $sS = null;

            array_shift($aL);

            foreach ($aL as $iI => $sI) {
                $aD = json_decode($sI, true);

                if (!$aD["sType"]) {
                    continue;
                }

                $oDebugMessage = R::dispense(T_DEBUG_MESSAGES);
                $oDebugMessage->tdebugfiles_id = $oDebugFile->id;
                $oDebugMessage->tprojects_id = $aRequest['project_id'];

                // echo "<pre>";
                // var_export($aD);
                // echo "</pre>";

                // $oDebugMessage->import($aD);

                $oDebugMessage->s_type = (string) $aD["sType"];
                $oDebugMessage->s_message = (string) $aD["sMessage"];
                $oDebugMessage->i_timestamp = (string) $aD["iTimestamp"];
                $oDebugMessage->created_at = date("Y-m-d H:i:s", $aD["iTimestamp"]/1000);

                R::store($oDebugMessage);

                $oDebugMessage->o_back_trace = json_encode($aD["oBackTrace"]);
                $oDebugMessage->a_back_trace = json_encode($aD["aBackTrace"]);
                $oDebugMessage->o_vars = json_encode($aD["oVars"]);

                $oDebugMessage->size = strlen($oDebugMessage->o_vars);
                $oDebugMessage->size_human = fnToHumanFileSize($oDebugMessage->size);

                R::store($oDebugMessage);
            }

            $aAdded[] = $oDebugFile->id;
        }
    }

    R::commit();

    die(json_encode($aAdded));
}

if ($sMethod == 'clear_table_debug_files') {
    R::begin();

    $t1 = T_DEBUG_FILES;
    R::exec("DELETE FROM $t1 WHERE tprojects_id = ?", [$aRequest['project_id']]);
    $t1 = T_DEBUG_MESSAGES;
    R::exec("DELETE FROM $t1 WHERE tprojects_id = ?", [$aRequest['project_id']]);

    R::commit();

    die(json_encode([]));
}

if ($sMethod == 'clear_files_debug_files') {
    R::begin();

    $t1 = T_DEBUG_FILES;
    R::exec("DELETE FROM $t1 WHERE tprojects_id = ?", [$aRequest['project_id']]);
    $t1 = T_DEBUG_MESSAGES;
    R::exec("DELETE FROM $t1 WHERE tprojects_id = ?", [$aRequest['project_id']]);

    $oProject = R::findOne(T_PROJECTS, "id = ?", [$aRequest['project_id']]);
    if ($oProject->path_to_debug_log && is_dir($oProject->path_to_debug_log)) {
        exec("rm -rf {$oProject->path_to_debug_log}/*");
    }

    R::commit();

    die(json_encode([]));
}

if ($sMethod == 'list_debug_files') {
    $sFilterRules = " 1 = 1";
    if (isset($aRequest['filterRules'])) {
        $aRequest['filterRules'] = json_decode($aRequest['filterRules']);
        if ($aRequest['filterRules']) {
            $sFilterRules = fnGenerateFilterRules($aRequest['filterRules']);
        }
    }

    $sOffset = fnPagination($aRequest['page'], $aRequest['rows']);
    $aResult = [];

    if (isset($aRequest['project_id'])) {
        $aLinks = R::findAll(T_DEBUG_FILES, "{$sFilterRules} AND tprojects_id = ? ORDER BY file_name DESC {$sOffset}", [$aRequest['project_id']]);
        $aResult['total'] = R::count(T_DEBUG_FILES, "{$sFilterRules} AND tprojects_id = ?", [$aRequest['project_id']]);
    } else {
        $aLinks = R::findAll(T_DEBUG_FILES, "{$sFilterRules} ORDER BY id DESC {$sOffset}", []);
        $aResult['total'] = R::count(T_DEBUG_FILES, "{$sFilterRules}");
    }

    foreach ($aLinks as $oLink) {
        $oLink->project_id = $oLink->tprojects_id;
    }

    $aResult['rows'] = array_values((array) $aLinks);

    die(json_encode($aResult));
}

if ($sMethod == 'get_debug_file') {
    $aResponse = R::findOne(T_DEBUG_FILES, "id = ?", [$aRequest['id']]);
    die(json_encode($aResponse));
}

if ($sMethod == 'delete_debug_file') {
    R::begin();

    $oDebugFile = R::findOne(T_DEBUG_FILES, "id = ?", [$aRequest['id']]);
    $oProject = $oDebugFile->tprojects;

    if ($oProject->path_to_debug_log && is_dir($oProject->path_to_debug_log)) {
        exec("rm -rf {$oProject->path_to_debug_log}/{$oDebugFile->file_name}");
    }

    $t1 = T_DEBUG_FILES;
    R::exec("DELETE FROM $t1 WHERE id = ?", [$aRequest['id']]);
    $t1 = T_DEBUG_MESSAGES;
    R::exec("DELETE FROM $t1 WHERE tdebugfiles_id = ?", [$aRequest['id']]);

    R::commit();

    die(json_encode([]));
}

if ($sMethod == 'update_debug_file') {
    $oCategory = R::findOne(T_DEBUG_FILES, "id = ?", [$aRequest['id']]);

    $oCategory->name = $aRequest['name'];
    $oCategory->description = $aRequest['description'];

    if (isset($aRequest['group_id']) && !empty($aRequest['group_id'])) {
        $oCategory->tgroups = R::findOne(T_GROUPS, "id = ?", [$aRequest['group_id']]);
    } else {
        $oCategory->tgroups_id = null;
    }

    if (isset($aRequest['debug_file_id']) && !empty($aRequest['debug_file_id'])) {
        $oCategory->tdebug_files = R::findOne(T_DEBUG_FILES, "id = ?", [$aRequest['debug_file_id']]);
    } else {
        $oCategory->tdebug_files_id = null;
    }

    R::store($oCategory);

    die(json_encode([
        "id" => $oCategory->id, 
        "name" => $oCategory->name
    ]));
}

if ($sMethod == 'create_debug_file') {
    $oCategory = R::dispense(T_DEBUG_FILES);

    $oCategory->name = $aRequest['name'];
    $oCategory->description = $aRequest['description'];

    if (isset($aRequest['group_id']) && !empty($aRequest['group_id'])) {
        $oCategory->tgroups = R::findOne(T_GROUPS, "id = ?", [$aRequest['group_id']]);
    }

    if (isset($aRequest['debug_file_id']) && !empty($aRequest['debug_file_id'])) {
        $oCategory->tdebug_files = R::findOne(T_DEBUG_FILES, "id = ?", [$aRequest['debug_file_id']]);
    }

    R::store($oCategory);

    die(json_encode([
        "id" => $oCategory->id, 
        "name" => $oCategory->name
    ]));
}