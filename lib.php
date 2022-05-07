<?php

function fnIsInDocker()
{
    return is_file("/.dockerenv");
}

function fnGetProjectDebugLogPath($oProject)
{
    return fnIsInDocker() ? $oProject->path_to_debug_log : $oProject->global_debug_log_path;
}

function fnGetProjectPath($oProject)
{
    return fnIsInDocker() ? $oProject->path : $oProject->global_path;
}


function fnToHumanFileSize($size,$unit="") {
    if( (!$unit && $size >= 1<<30) || $unit == "GB")
        return number_format($size/(1<<30),2)."GB";
    if( (!$unit && $size >= 1<<20) || $unit == "MB")
        return number_format($size/(1<<20),2)."MB";
    if( (!$unit && $size >= 1<<10) || $unit == "KB")
        return number_format($size/(1<<10),2)."KB";
    return number_format($size)."B";
}

function fnConvertVarsObjectToTreegrid($aVars, &$aResult=[], $sGK="")
{
    foreach ($aVars as $sK => $aV) {
        $aChildren = [];
        if (is_array($aV)) {
            fnConvertVarsObjectToTreegrid($aV, $aChildren, $sGK.".".$sK);
            $aV = "";
        }
        $aResult[] = [
            "id" => $sGK.".".$sK,
            "name" => (string) $sK,
            "value" => (string) $aV,
            "children" => $aChildren
        ];
    }
}

function fnGenerateFilterRules($aFilterRules)
{
    $sSQL = "";

    foreach ($aFilterRules as $aRule) {
        $aRule = (array) $aRule;
        if ($aRule["op"] == "contains") {
            $sSQL .= " {$aRule["field"]} LIKE '%{$aRule["value"]}%' ";
        }
    }

    return $sSQL;
}

function fnPagination($iPage, $iRows)
{
    $iF = ($iPage-1)*$iRows;
    return " LIMIT {$iF}, {$iRows}";
}

function fnBuildRecursiveProjectsTree(&$aResult, $aCategories) 
{
    $aResult = [];

    foreach ($aCategories as $oCategory) {
        $aTreeChildren = [];

        $aChildren = R::findAll(T_CATEGORIES, " tcategories_id = {$oCategory->id}");
        fnBuildRecursiveCategoriesTree($aTreeChildren, $aChildren);

        $aResult[] = [
            'id' => $oCategory->id,
            'text' => $oCategory->name,
            'name' => $oCategory->name,
            'description' => $oCategory->description,
            'category_id' => $oCategory->tcategories_id,
            'group_id' => $oCategory->tgroups_id,
            'children' => $aTreeChildren,
            'count' => $oCategory->countOwn(T_LINKS)
        ];
    }
}

function fnBuildRecursiveLinksTree(&$aResult, $aLinks, $sSQL = "", $aBindings=[]) 
{
    $aResult = [];

    foreach ($aLinks as $oLink) {
        $aTreeChildren = [];

        $aChildren = R::findAll(T_LINKS, " tlinks_id = {$oLink->id} {$sSQL}", $aBindings);
        fnBuildRecursiveLinksTree($aTreeChildren, $aChildren, $sSQL, $aBindings);
        $iC = $oLink->countOwn(T_LINKS);

        $aResult[] = [
            'id' => $oLink->id,
            'text' => $oLink->name,
            'created_at' => $oLink->created_at,
            'is_ready' => $oLink->is_ready,
            'name' => $oLink->name,
            'description' => $oLink->description,
            'category_id' => $oLink->tcategories_id,
            'task_id' => $oLink->tlinks_id,
            'children' => $aTreeChildren,
            'notes_count' => $iC,
            'checked' => $oLink->is_ready == '1',
            // 'state' => $iC > 0 ? "closed" : "",
        ];
    }
}

function fnBuildRecursiveLinksTreeModify($oLink, $bIsReady) 
{
    $aChildren = R::findAll(T_LINKS, " tlinks_id = {$oLink->id}");

    foreach ($aChildren as $oChildLink) {
        $oChildLink->is_ready = $bIsReady;
        R::store($oChildLink);

        fnBuildRecursiveLinksTreeModify($oChildLink, $bIsReady);
    }
}

function fnBuildRecursiveLinksTreeDelete($oLink) 
{
    $aChildren = R::findAll(T_LINKS, " tlinks_id = {$oLink->id}");

    foreach ($aChildren as $oChildLink) {
        fnBuildRecursiveLinksTreeDelete($oChildLink);
        R::trashBatch(T_LINKS, [$oChildLink->id]);
    }

    R::trashBatch(T_LINKS, [$oLink->id]);
}

function fnBuildRecursiveProjectsTreeDelete($oCategory) 
{
    $aChildren = R::findAll(T_CATEGORIES, " tcategories_id = {$oCategory->id}");

    $aLinks = R::findAll(T_LINKS, " tcategories_id = {$oCategory->id}");

    foreach ($aLinks as $oChildLink) {
        fnBuildRecursiveLinksTreeDelete($oChildLink);
    }

    R::trashBatch(T_CATEGORIES, [$oCategory->id]);
}