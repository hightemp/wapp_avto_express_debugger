<?php

if ($sMethod == 'list_tags') {
    $aTags = R::findAll(T_TAGS);
    $aResult = [];

    foreach ($aTags as $oTag) {
        $aResult[] = [
            'id' => $oTag->id,
            'text' => $oTag->name,
            'name' => $oTag->name,
        ];
    }
    
    die(json_encode(array_values($aResult)));
}

if ($sMethod == 'list_objects_for_tags') {
    $aItems = R::findAll(T_TAGS_TO_OBJECTS, "ttags_id = ?", [$aRequest['id']]);
    $aResult = [];

    foreach ($aItems as $oItem) {
        $oObject = $oItem->poly('contentType')->content;
        $aResult[] = [
            'id' => $oItem->id,
            'content_id' => $oItem->content_id,
            'content_type' => $oItem->content_type,
            'text' => $oObject->name,
            'name' => $oObject->name,
        ];
    }

    die(json_encode(array_values($aResult)));
}

if ($sMethod == 'create_tag') {
    $oTag = R::dispense(T_TAGS);
    $oTag->name = $aRequest['name'];
    R::store($oTag);
    die(json_encode($oTag));
}

if ($sMethod == 'update_tag') {
    $oTag = R::findOne(T_TAGS, "id = ?", [$aRequest['id']]);
    $oTag->name = $aRequest['name'];
    R::store($oTag);
    die(json_encode($oTag));
}

if ($sMethod == 'delete_tag') {
    R::trashBatch(T_TAGS, [$aRequest['id']]);
    R::exec("DELETE ? WHERE ttags_id='?'", [T_TAGS_TO_OBJECTS, $aRequest['id']]);
    die(json_encode([]));
}


if ($sMethod == 'remove_tags') {
    $aTags = R::findLike(T_TAGS, [ "name" => $aRequest['tags']]);
    $aRelations = R::findLike(T_TAGS_TO_OBJECTS, [ "ttags_id" => $aTags ]);

    R::trashBatch(T_TAGS, [$aTags]);
    R::trashBatch(T_TAGS_TO_OBJECTS, [$aRelations]);
    
    die(json_encode([]));
}

if ($sMethod == 'add_tags') {
    $aTags = $aRequest['tags'];

    foreach ($aTags as $sTag) {
        $oTag = R::findOrCreate(T_TAGS, [
            'name' => $sTag, 
        ]);

        $oRelation = R::findOrCreate(T_TAGS_TO_OBJECTS, [
            'ttags_id' => $oTag->id,
            'content_id' => $aRequest['content_id'],
            'content_type' => $aRequest['content_type'],
        ]);

        R::store($oTag);
        R::store($oRelation);
    }
    die(json_encode([]));
}

if ($sMethod == 'set_tags') {    
    R::exec("DELETE ".T_TAGS_TO_OBJECTS." WHERE content_id='${$aRequest['content_id']}' AND content_type='${$aRequest['content_type']}'");
    $aTags = $aRequest['tags'];

    foreach ($aTags as $sTag) {
        $oTag = R::findOrCreate(T_TAGS, [
            'name' => $sTag, 
        ]);

        $oRelation = R::findOrCreate(T_TAGS_TO_OBJECTS, [
            'ttags_id' => $oTag->id,
            'content_id' => $aRequest['content_id'],
            'content_type' => $aRequest['content_type'],
        ]);

        R::store($oTag);
        R::store($oRelation);
    }
    die(json_encode([]));
}