<?php

error_reporting(E_ERROR | E_PARSE);

class Config {
    public static $aOptions = [];

    public static function fnLoad()
    {
        static::$aOptions = json_decode(file_get_contents("./config.json"), true) ?: [];
    }

    public static function fnSave()
    {
        file_put_contents("./config.json", json_encode(static::$aOptions));
    }
}

Config::fnLoad();

$sBase = Config::$aOptions["base"];

define("PROJECTS_PATH", __DIR__."/projects");

$sBA = $sBase."/static/app";
$sB = $sBase."/static/app/jquery-easyui-1.10.2";

$sNP = "/data/notes";
$sFNP = __DIR__."/data/notes";
$sTP = "/data/tables";
$sFTP = __DIR__."/data/tables";
$sFFP = __DIR__."/data/resources/files";
$sIP = "/data/resources/images";
$sFIP = __DIR__."/data/resources/images";
