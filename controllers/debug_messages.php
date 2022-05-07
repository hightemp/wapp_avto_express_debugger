<?php

// Таблицы

// if ($sMethod == 'scan_debug_messages') {
//     $aFiles = glob(PROJECTS_PATH."/*");

//     foreach ($aFiles as $File) {
//         if (is_dir($sFile)) {
//             R::findOrCreate(T_DEBUG_MESSAGES, ["path" => $sFile]);
//         }
//     }

//     die(json_encode([]));
// }

if ($sMethod == 'list_debug_messages') {
    // $aResponse = R::findAll(T_DEBUG_MESSAGES);

    $sFilterRules = " 1 = 1";
    if (isset($aRequest['filterRules'])) {
        $aRequest['filterRules'] = json_decode($aRequest['filterRules']);
        if ($aRequest['filterRules']) {
            $sFilterRules = fnGenerateFilterRules($aRequest['filterRules']);
        }
    }

    $sOffset = fnPagination($aRequest['page'], $aRequest['rows']);
    $aResult = [];
    $sOrder = "ORDER BY id ASC";

    if (isset($aRequest['debug_file_id'])) {
        $aDebugMessages = R::findAll(T_DEBUG_MESSAGES, "{$sFilterRules} AND tdebugfiles_id = ? {$sOrder} {$sOffset}", [$aRequest['debug_file_id']]);
        $aResult['total'] = R::count(T_DEBUG_MESSAGES, "{$sFilterRules} AND tdebugfiles_id = ?", [$aRequest['debug_file_id']]);
    } else {
        $aDebugMessages = R::findAll(T_DEBUG_MESSAGES, "{$sFilterRules} {$sOrder} {$sOffset}", []);
        $aResult['total'] = R::count(T_DEBUG_MESSAGES, "{$sFilterRules}");
    }

    foreach ($aDebugMessages as $oDebugMessage) {
        $oDebugMessage->o_back_trace = json_decode($oDebugMessage->o_back_trace);
        $oDebugMessage->a_back_trace = json_decode($oDebugMessage->a_back_trace);
        $oDebugMessage->o_vars = json_decode($oDebugMessage->o_vars);
        $oDebugMessage->project_global_path = sprintf(
            "%s/%s:%s", 
            $oDebugMessage->tprojects->global_path,
            $oDebugMessage->o_back_trace->file, 
            $oDebugMessage->o_back_trace->line
        );
        $oDebugMessage->tprojects->link_type = $oDebugMessage->tprojects->link_type ?: "vscode";
        $oDebugMessage->editor_url = sprintf(
            "%s://file%s",
            $oDebugMessage->tprojects->link_type,
            $oDebugMessage->project_global_path
        );

        $sPath = preg_quote($oDebugMessage->tprojects->relative_path, "/");
        $oDebugMessage->s_message = preg_replace(
            '/('.$sPath.')([\w\/\\.]*?\.php(:\d+)?)/', 
            sprintf(
                "<a href=\"%s://file%s%s\">%s</a>",
                $oDebugMessage->tprojects->link_type,
                $oDebugMessage->tprojects->global_path,
                '$2',
                '$2'
            ),
            $oDebugMessage->s_message
        );
    }

    $aResult['rows'] = array_values((array) $aDebugMessages);

    die(json_encode($aResult));
}

if ($sMethod == 'get_vars_tree_grid') {
    $oDebugMessage = R::findOne(T_DEBUG_MESSAGES, "id = ?", [$aRequest['id']]);

    $oDebugMessage->o_vars = json_decode($oDebugMessage->o_vars, true);

    $aVarsTreeGrid = [];
    fnConvertVarsObjectToTreegrid($oDebugMessage->o_vars, $aVarsTreeGrid);
    $oDebugMessage->a_vars_treegrid = $aVarsTreeGrid;

    die(json_encode($aVarsTreeGrid));
}

if ($sMethod == 'get_debug_message') {
    $aResponse = R::findOne(T_DEBUG_MESSAGES, "id = ?", [$aRequest['id']]);
    die(json_encode($aResponse));
}

if ($sMethod == 'delete_debug_message') {
    $oDebugMessage = R::findOne(T_DEBUG_MESSAGES, "id = ?", [$aRequest['id']]);

    // fnBuildRecursiveCategoriesTreeDelete($oDebugMessage);

    die(json_encode([]));
}

if ($sMethod == 'update_debug_message') {
    $oDebugMessage = R::findOne(T_DEBUG_MESSAGES, "id = ?", [$aRequest['id']]);

    $oDebugMessage->name = $aRequest['name'];
    $oDebugMessage->description = $aRequest['description'];

    if (isset($aRequest['group_id']) && !empty($aRequest['group_id'])) {
        $oDebugMessage->tgroups = R::findOne(T_GROUPS, "id = ?", [$aRequest['group_id']]);
    } else {
        $oDebugMessage->tgroups_id = null;
    }

    if (isset($aRequest['debug_message_id']) && !empty($aRequest['debug_message_id'])) {
        $oDebugMessage->tdebug_messages = R::findOne(T_DEBUG_MESSAGES, "id = ?", [$aRequest['debug_message_id']]);
    } else {
        $oDebugMessage->tdebug_messages_id = null;
    }

    R::store($oDebugMessage);

    die(json_encode([
        "id" => $oDebugMessage->id, 
        "name" => $oDebugMessage->name
    ]));
}

if ($sMethod == 'create_debug_message') {
    $oDebugMessage = R::dispense(T_DEBUG_MESSAGES);

    $oDebugMessage->name = $aRequest['name'];
    $oDebugMessage->description = $aRequest['description'];

    if (isset($aRequest['group_id']) && !empty($aRequest['group_id'])) {
        $oDebugMessage->tgroups = R::findOne(T_GROUPS, "id = ?", [$aRequest['group_id']]);
    }

    if (isset($aRequest['debug_message_id']) && !empty($aRequest['debug_message_id'])) {
        $oDebugMessage->tdebug_messages = R::findOne(T_DEBUG_MESSAGES, "id = ?", [$aRequest['debug_message_id']]);
    }

    R::store($oDebugMessage);

    die(json_encode([
        "id" => $oDebugMessage->id, 
        "name" => $oDebugMessage->name
    ]));
}