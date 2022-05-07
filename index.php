<?php 

include_once("./database.php");

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>

    <link rel="shortcut icon" href="<?php echo $sBase ?>/static/app/favicon.png" type="image/png">

    <link rel="stylesheet" type="text/css" href="<?php echo $sB ?>/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $sB ?>/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $sBA ?>/styles_index.css">
    <script type="text/javascript" src="<?php echo $sB ?>/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $sB ?>/jquery.easyui.min.js"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo $sBA ?>/easymde.min.css">
    <script type="text/javascript" src="<?php echo $sBA ?>/easymde.min.js"></script>
    <script type="text/javascript" src="<?php echo $sBA ?>/all.js"></script>
    <script type="text/javascript" src="<?php echo $sBA ?>/speadsheet.js"></script>

    <script type="text/javascript" src="<?php echo $sBA ?>/datagrid-filter.js"></script>

    <script src="https://cdn.jsdelivr.net/highlight.js/latest/highlight.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/highlight.js/latest/styles/github.min.css">

    <link rel="stylesheet" href="/static/app/icons.css">
</head>
<body>
    <div id="main-panel">
        <?php include "includes/layout.php" ?>
    </div>
    <?php
    if (!is_writable("./db/dbfile.db")) {
        ?><div style="background: red; color: white; padding: 10px">./db/dbfile.db is not writable</div><?php
    }
    ?>
</body>
</html>

<script type="module">
import * as m from "./static/app/modules/__init__.js";

$(document).ready(() => {
    m.Projects.fnPrepare();
    m.DebugFiles.fnPrepare();
    m.DebugMessages.fnPrepare();
    m.DebugMessageInfo.fnPrepare();

    m.NotificationManager.fnPrepare();
})
</script>