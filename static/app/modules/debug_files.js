import { tpl, fnAlertMessage } from "./lib.js"

export class DebugFiles {
    static sURL = ``

    static _oSelected = null;
    static _oSelectedProject = null;
    
    static oURLs = {
        create: 'ajax.php?method=create_debug_file',
        update: tpl`ajax.php?method=update_debug_file&id=${0}`,
        delete: 'ajax.php?method=delete_debug_file',
        list: tpl`ajax.php?method=list_debug_files&project_id=${0}`,
        scan_files: tpl`ajax.php?method=scan_debug_files&project_id=${0}`,
        clear_files: tpl`ajax.php?method=clear_files_debug_files&project_id=${0}`,
        clear_table: tpl`ajax.php?method=clear_table_debug_files&project_id=${0}`,

        view_file: tpl`ajax.php?method=view_file&project_id=${0}`,
    }
    static oWindowTitles = {
        create: 'Новый файл',
        update: 'Редактировать файл'
    }
    static oEvents = {
        projects_select: "projects:select",
        debug_files_save: "debug_files:save",
        debug_files_select: "debug_files:select",
    }

    static get oDialog() {
        return $('#debug-log-files-dlg');
    }
    static get oDialogForm() {
        return $('#debug-log-files-dlg-fm');
    }
    static get oComponent() {
        return $("#debug-log-files-table");
    }
    static get oContextMenu() {
        return $("#debug-log-files-mm");
    }

    static get oGroupList() {
        return $("#debug-log-files-group_id");
    }    
    static get oProjectTreeList() {
        return $("#debug-log-files-debug_file_id");
    }

    static get oEditDialogdebug_fileCleanBtn() {
        return $('#debug-log-files-dlg-clean-btn');
    }
    static get oEditDialogSaveBtn() {
        return $('#debug-log-files-dlg-save-btn');
    }
    static get oEditDialogCancelBtn() {
        return $('#debug-log-files-dlg-cancel-btn');
    }

    static get oPanelAddButton() {
        return $('#debug-log-files-add-btn');
    }
    static get oPanelEditButton() {
        return $('#debug-log-files-edit-btn');
    }
    static get oPanelRemoveButton() {
        return $('#debug-log-files-remove-btn');
    }
    static get oPanelReloadButton() {
        return $('#debug-log-files-reload-btn');
    }
    static get oPanelScanButton() {
        return $('#debug-log-files-scan-btn');
    }
    static get oPanelClearTableButton() {
        return $('#debug-log-files-clear-table-btn');
    }
    static get oPanelClearFilesButton() {
        return $('#debug-log-files-clear-folder-btn');
    }
    static get oPanelViewFileButton() {
        return $('#debug-log-files-view-file-btn');
    }

    static get fnComponent() {
        return this.oComponent.datagrid.bind(this.oComponent);
    }

    static get oSelecteddebug_file() {
        return this._oSelected;
    }

    static fnShowDialog(sTitle) {
        this.oDialog.dialog('open').dialog('center').dialog('setTitle', sTitle);
    }
    static fnDialogFormLoad(oRows={}) {
        this.oDialogForm.form('clear');
        this.oDialogForm.form('load', oRows);
    }

    static fnShowCreateWindow() {
        this.sURL = this.oURLs.create;
        var oData = {
        }
        this.fnShowDialog(this.oWindowTitles.create);
        this.fnDialogFormLoad(oData);
    }

    static fnShowEditWindow(oRow) {
        if (oRow) {
            this.sURL = this.oURLs.update(oRow.id);
            this.fnShowDialog(this.oWindowTitles.update);
            this.fnDialogFormLoad(oRow);
        }
    }

    static fnReload() {
        this.fnComponent('reload', this.oURLs.list(this._oSelectedProject.id));
    }

    static fnSave() {
        this.oDialogForm.form('submit', {
            url: this.sURL,
            iframe: false,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: (function(result){
                this.oDialog.dialog('close');
                this.fnReload();
                this.fnReloadLists();

                this.fnFireEvent_Save();
            }).bind(this)
        });
    }

    static fnDelete(oRow) {
        if (oRow){
            $.messager.confirm(
                'Confirm',
                'Удалить?',
                (function(r) {
                    if (r) {
                        $.post(
                            this.oURLs.delete,
                            { id: oRow.id },
                            (function(result) {
                                this.fnReload();
                            }).bind(this),
                            'json'
                        );
                    }
                }).bind(this)
            );
        }
    }

    static fnScanForFiles() {
        this.fnComponent('loading');
        $.post(
            this.oURLs.scan_files(this._oSelectedProject.id),
            { },
            (function(result) {
                this.fnReload();
            }).bind(this),
            'json'
        );
    }

    static fnClearTable() {
        this.fnComponent('loading');
        $.post(
            this.oURLs.clear_table(this._oSelectedProject.id),
            { },
            (function(result) {
                this.fnReload();
            }).bind(this),
            'json'
        );
    }

    static fnClearFiles() {
        this.fnComponent('loading');
        $.post(
            this.oURLs.clear_files(this._oSelectedProject.id),
            { },
            (function(result) {
                this.fnReload();
            }).bind(this),
            'json'
        );
    }

    static fnGetSelected() {
        return this.fnComponent('getSelected');
    }

    static fnSelect(iIndex) {
        this.fnComponent('selectRow', iIndex);
    }

    static fnReloadLists() {

    }

    static fnBindEvents()
    {
        $(document).on(this.oEvents.projects_select, ((oEvent, oNode) => {
            this._oSelectedProject = oNode;
            this.fnReload();
        }).bind(this))

        $(document).on(this.oEvents.debug_files_select, ((oEvent, oNode) => {
            this._oSelected = oNode;
            this.fnReloadLists();
        }).bind(this))

        this.oEditDialogdebug_fileCleanBtn.click((() => {
            this.oProjectTreeList.combotree('clear');
        }).bind(this))
        this.oEditDialogSaveBtn.click((() => {
            this.fnSave();
        }).bind(this))
        this.oEditDialogCancelBtn.click((() => {
            this.oDialog.dialog('close');
        }).bind(this))

        this.oPanelAddButton.click((() => {
            if (!this._oSelectedProject) return;
            this.fnShowCreateWindow();
        }).bind(this))
        this.oPanelEditButton.click((() => {
            if (!this._oSelectedProject) return;
            this.fnShowEditWindow(this.fnGetSelected());
        }).bind(this))
        this.oPanelRemoveButton.click((() => {
            if (!this._oSelectedProject) return;
            this.fnDelete(this.fnGetSelected());
        }).bind(this))
        this.oPanelReloadButton.click((() => {
            if (!this._oSelectedProject) return;
            this.fnReload();
        }).bind(this))
        this.oPanelScanButton.click((() => {
            if (!this._oSelectedProject) return;
            this.fnScanForFiles();
        }).bind(this))
        this.oPanelClearTableButton.click((() => {
            if (!this._oSelectedProject) return;
            this.fnClearTable();
        }).bind(this))
        this.oPanelClearFilesButton.click((() => {
            if (!this._oSelectedProject) return;
            this.fnClearFiles();
        }).bind(this))

        this.oPanelViewFileButton.click((() => {
            if (!this._oSelected) return;
            
        }).bind(this))
    }

    static fnFireEvent_Save() {
        $(document).trigger(this.oEvents.debug_files_save);
    }

    static fnFireEvent_Select(oNode) {
        $(document).trigger(this.oEvents.debug_files_select, [oNode])
    }

    static fnInitComponent()
    {
        this.fnComponent({
            url: '',
            method:'get',

            height: "100%",

            singleSelect: true,
            fit: true,
            border: false,

            toolbar: '#debug-log-files-tt',

            clientPaging: false,
            rownumbers: true,

            pagination: true,
            pageSize: 10,
            pageList: [10, 12, 15, 24, 30, 40, 50, 60, 70, 80, 90, 100],

            idField:'id',
            treeField:'name',
            columns:[[
                {
                    title:'Название',field:'s_url',width:300,
                    formatter: function(value, row, index) {
                        var sSCls = '';
                        if (/MB/.test(row.size_human)) {
                            sSCls = 'file-size-x';

                            var oM = row.size_human.match(/(\d+)MB/);
                            if (oM && oM[1]) {

                                if (oM > 10) {
                                    sSCls = 'file-size-xx';
                                }
                                if (oM > 100) {
                                    sSCls = 'file-size-xxx';
                                }
                            }
                        }
                        if (/GB/.test(row.size_human)) {
                            sSCls = 'file-size-l';
                        }

                        var s = `
                        <div class="debug-files-cell">
                            <div class="row-url">
                                ${row.s_url}
                            </div>
                            <div class="row-file-info">
                                <span class="row-name">${row.name}</span>
                                <span class="type">${row.type}</span>
                                <span class="errors">${row.count_errors}</span>
                                <span class="messages">${row.count}</span>
                                <span class="size ${sSCls}">${row.size_human}</span>
                            </div>
                            <div class="row-file-info-2">
                                <span class="lang">${row.s_lang}</span>
                                <span class="s_user">${row.s_user}</span>
                                <span class="sys_user">${row.s_sys_user}</span>
                            </div>
                        </div>
                        `;
                        return s;
                    }
                },
            ]],

            onSelect: ((iIndex, oNode) => {
                this._oSelected = oNode;
                this.fnFireEvent_Select(oNode);
            }).bind(this),

            onRowContextMenu: (function(e, index, node) {
                e.preventDefault();
                this.fnSelect(index);
                this.oContextMenu.menu('show', {
                    left: e.pageX,
                    top: e.pageY,
                    onClick: ((item) => {
                        if (item.id == 'add') {
                            this.fnShowCreateWindow();
                        }
                        if (item.id == 'edit') {
                            this.fnShowEditWindow(node);
                        }
                        if (item.id == 'delete') {
                            this.fnDelete(node);
                        }
                        if (item.id == 'move_to_root_debug_file') {
                            this.fnMoveToRoot(node);
                        }
                    }).bind(this)
                });
            }).bind(this),

            formatter: function(node) {
                var s = node.text;
                s += '&nbsp;<span style=\'color:blue\'>(' + node.count + ')</span>';
                return s;
            }
        })

        this.fnComponent('enableFilter', [

        ]);
    }

    static fnPrepare()
    {
        this.fnInitComponent()
        this.fnBindEvents();
    }
}