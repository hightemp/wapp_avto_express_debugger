<div id="tt" class="easyui-tabs" style="width:100%;height:100%;">
    <div title="Отладчик" style="padding:0px;display:none;">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',split:true" title="" style="width:540px;">

                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'west',split:true" title="" style="width:200px;">

                        <div id="files-list" style="width:100%; height:100%">
                            <table id="debug-projects-table" class="easyui-datagrid" data-options="fit:true" style="width:100%;"></table>
                            <div id="debug-projects-tt">
                                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-table_add',plain:true" id="debug-projects-add-btn"></a>
                                <!-- <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-table_edit',plain:true" id="debug-projects-edit-btn"></a>
                                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-table_delete',plain:true" id="debug-projects-remove-btn"></a> -->
                                <!-- <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true" id="debug-projects-reload-btn"></a> -->
                                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" id="debug-projects-scan-btn"></a>
                                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-bin',plain:true" id="debug-projects-clear-files-btn"></a>
                            </div>
                        </div>

                    </div>
                    <div data-options="region:'center',title:''">

                        <table id="debug-log-files-table" class="easyui-datagrid" data-options="fit:true" style="width:100%;height:100%"></table>
                        <div id="debug-log-files-tt">
                            <!-- <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" id="debug-log-files-add-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true" id="debug-log-files-edit-btn"></a> -->
                            <!-- <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-table_refresh',plain:true" id="debug-log-files-clear-table-btn"></a> -->

                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-bin',plain:true" id="debug-log-files-clear-folder-btn"></a>
                            <!-- <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-table_delete',plain:true" id="debug-log-files-remove-btn"></a> -->
                            <!-- <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true" id="debug-log-files-reload-btn"></a> -->
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" id="debug-log-files-scan-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-book_open',plain:true" id="debug-log-files-view-file-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-asterisk_orange',plain:true" id="debug-projects-filter-btn"></a>

                            <div id="project-files-info"></div>
                        </div>

                    </div>
                </div>

            </div>
            <div data-options="region:'center',title:'',iconCls:'icon-ok'">

                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'west',split:true" title="" style="width:440px;">

                        <table id="debug-log-messages-table" class="easyui-datagrid" data-options="fit:true" style="width:100%;"></table>
                        <div id="debug-log-messages-tt">
                            <!-- <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" id=debug-log-messages-add-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true" id="debug-log-messages-edit-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" id="debug-log-messages-remove-btn"></a> -->
                            <!-- <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-table_delete',plain:true" id="debug-log-messages-remove-btn"></a> -->
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true" id="debug-log-messages-reload-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-asterisk_orange',plain:true" id="debug-log-messages-filter-btn"></a>
                        </div>

                    </div>
                    <div data-options="region:'center',title:'',iconCls:'icon-ok'">
                        <div id="right-panel">
                            <div>
                                <div id="debug-log-message-info-message-text">

                                </div>
                            </div>

                            <div id="debug-log-message-info-backtrace-table-wrapper">
                                <table id="debug-log-message-info-backtrace-table" class="easyui-datagrid" data-options="fit:true" style="width:100%;"></table>
                            </div>
                            <div id="debug-log-message-info-vars-tree-wrapper">
                                <!-- Проблема со стилями -->
                                <!-- <table id="debug-log-message-info-vars-tree" class="easyui-treegrid" data-options="fit:true" style="width:100%;"></table> -->
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <div title="Логи" style="padding:0px;display:none;">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',split:true" title="" style="width:520px;">

                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'west',split:true" title="" style="width:200px;">

                        <table id="logs-projects-table" class="easyui-datagrid" data-options="fit:true" style="width:100%;"></table>
                        <div id="logs-projects-tt">
                            <!-- <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" id="logs-projects-add-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true" id="logs-projects-edit-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" id="logs-projects-remove-btn"></a> -->
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true" id="logs-projects-reload-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" id="logs-projects-scan-btn"></a>
                        </div>

                    </div>
                    <div data-options="region:'center',title:''">

                        <table id="logs-log-files-table" class="easyui-datagrid" data-options="fit:true" style="width:100%;height:100%"></table>
                        <div id="logs-log-files-tt">
                            <!-- <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" id="logs-log-files-add-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true" id="logs-log-files-edit-btn"></a> -->
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" id="logs-log-files-remove-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true" id="logs-log-files-reload-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" id="logs-log-files-scan-btn"></a>
                        </div>

                    </div>
                </div>

            </div>
            <div data-options="region:'center',title:'',iconCls:'icon-ok'">

                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'west',split:true" title="" style="width:400px;">

                        <table id="logs-log-messages-table" class="easyui-datagrid" data-options="fit:true" style="width:100%;"></table>
                        <div id="logs-log-messages-tt">
                            <!-- <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" id=logs-log-messages-add-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true" id="logs-log-messages-edit-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" id="logs-log-messages-remove-btn"></a> -->
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true" id="logs-log-messages-reload-btn"></a>
                        </div>

                    </div>
                    <div data-options="region:'center',title:'',iconCls:'icon-ok'">

                        <table id="logs-log-message-info-table" class="easyui-datagrid" data-options="fit:true" style="width:100%;"></table>
                        <div id="logs-log-message-info-tt">
                            <!-- <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" id="logs-log-message-info-add-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true" id="logs-log-message-info-edit-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" id="logs-log-message-info-remove-btn"></a> -->
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true" id="logs-log-message-info-reload-btn"></a>
                        </div>

                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>


<div style="position:fixed">
    <!-- Отладчик - Проекты -->
    <div id="debug-projects-dlg" class="easyui-dialog" style="width:500px" data-options="closed:true,modal:true,border:'thin',buttons:'#debug-projects-dlg-buttons'">
        <form id="debug-projects-dlg-fm" method="post" novalidate style="margin:0;padding:5px">
            <div style="margin-bottom:10px">
                <label>Заголовок:</label>
                <input name="name" class="easyui-textbox" required="true" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <label>Описание:</label>
                <input name="description" class="easyui-textbox" style="width:100%;height:200px" multiline="true">
            </div>
            <div style="margin-bottom:10px">
                <label>Путь до проекта:</label>
                <input name="path" class="easyui-textbox" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <label>Путь до папки с логами отладчика:</label>
                <input name="path_to_debug_log" class="easyui-textbox" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <label>Относительный путь:</label>
                <input name="relative_path" class="easyui-textbox" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <label>Глобальный путь:</label>
                <input name="global_path" class="easyui-textbox" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <label>Тип ссылки:</label>
                <select class="easyui-combobox" name="link_type" style="width:100%;">
                    <option value="vscode">vscode</option>
                    <option value="phpstorm">phpstorm</option>
                </select>
            </div>
        </form>
    </div>
    <div id="debug-projects-dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" id="debug-projects-dlg-save-btn" style="width:auto">Сохранить</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" id="debug-projects-dlg-cancel-btn" style="width:auto">Отмена</a>
    </div>

    <!-- debug-projects
    debug-log-files
    debug-log-messages
    debug-log-message-info -->

    <div id="debug-projects-mm" class="easyui-menu" style="width:auto;">
        <div data-options="id:'edit'">Радактировать</div>
        <div data-options="id:'delete'">Удалить</div>
    </div>
    <div id="debug-log-files-mm" class="easyui-menu" style="width:auto;">
        <div data-options="id:'edit'">Радактировать</div>
        <div data-options="id:'delete'">Удалить</div>
    </div>
    <div id="debug-log-messages-mm" class="easyui-menu" style="width:auto;">
        <div data-options="id:'edit'">Радактировать</div>
        <div data-options="id:'delete'">Удалить</div>
    </div>
    <div id=" debug-log-message-info-mm" class="easyui-menu" style="width:auto;">
        <div data-options="id:'edit'">Радактировать</div>
        <div data-options="id:'delete'">Удалить</div>
    </div>

</div>