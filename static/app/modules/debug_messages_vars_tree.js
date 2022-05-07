import { tpl, fnAlertMessage } from "./lib.js"

export class DebugMessages {
    static sURL = ``

    static _oSelected = null;
    static _oSelectedDebugFile = null;
    
    static oURLs = {
        create: 'ajax.php?method=create_debug_message',
        update: tpl`ajax.php?method=update_debug_message&id=${0}`,
        delete: 'ajax.php?method=delete_debug_message',
        list: tpl`ajax.php?method=list_debug_messages&debug_file_id=${0}`,
    }
    static oWindowTitles = {
        create: 'Новый проект',
        update: 'Редактировать проект'
    }
    static oEvents = {
        debug_files_select: "debug_files:select",
        debug_messages_save: "debug_messages:save",
        debug_messages_select: "debug_messages:select",
    }

    static get oDialog() {
        return $('#debug-log-messages-dlg');
    }
    static get oDialogForm() {
        return $('#debug-log-messages-dlg-fm');
    }
    static get oComponent() {
        return $("#debug-log-messages-table");
    }
    static get oContextMenu() {
        return $("#debug-log-messages-mm");
    }

    static get oGroupList() {
        return $("#debug-log-messages-group_id");
    }    
    static get oProjectTreeList() {
        return $("#debug-log-messages-debug_message_id");
    }

    static get oEditDialogdebug_messageCleanBtn() {
        return $('#debug-log-messages-dlg-clean-btn');
    }
    static get oEditDialogSaveBtn() {
        return $('#debug-log-messages-dlg-save-btn');
    }
    static get oEditDialogCancelBtn() {
        return $('#debug-log-messages-dlg-cancel-btn');
    }

    static get oPanelAddButton() {
        return $('#debug-log-messages-add-btn');
    }
    static get oPanelEditButton() {
        return $('#debug-log-messages-edit-btn');
    }
    static get oPanelRemoveButton() {
        return $('#debug-log-messages-remove-btn');
    }
    static get oPanelReloadButton() {
        return $('#debug-log-messages-reload-btn');
    }

    static get fnComponent() {
        return this.oComponent.datagrid.bind(this.oComponent);
    }

    static get oSelecteddebug_message() {
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
        if (!this._oSelectedDebugFile) {
            return;
        }
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
        this.fnComponent('reload', this.oURLs.list(this._oSelectedDebugFile.id));
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

    static fnGetSelected() {
        return this.fnComponent('getSelected');
    }

    static fnSelect(oTarget) {
        this.fnComponent('select', oTarget);
    }

    static fnReloadLists() {

    }

    static fnBindEvents()
    {
        $(document).on(this.oEvents.debug_files_select, ((oEvent, oNode) => {
            this._oSelectedDebugFile = oNode;
            this.fnReload();
        }).bind(this))

        $(document).on(this.oEvents.debug_messages_select, ((oEvent, oNode) => {
            this._oSelected = oNode;
            this.fnReloadLists();
        }).bind(this))

        this.oEditDialogdebug_messageCleanBtn.click((() => {
            this.oProjectTreeList.combotree('clear');
        }).bind(this))
        this.oEditDialogSaveBtn.click((() => {
            this.fnSave();
        }).bind(this))
        this.oEditDialogCancelBtn.click((() => {
            this.oDialog.dialog('close');
        }).bind(this))

        this.oPanelAddButton.click((() => {
            this.fnShowCreateWindow();
        }).bind(this))
        this.oPanelEditButton.click((() => {
            this.fnShowEditWindow(this.fnGetSelected());
        }).bind(this))
        this.oPanelRemoveButton.click((() => {
            this.fnDelete(this.fnGetSelected());
        }).bind(this))
        this.oPanelReloadButton.click((() => {
            this.fnReload();
        }).bind(this))
    }

    static fnFireEvent_Save() {
        $(document).trigger(this.oEvents.debug_messages_save);
    }

    static fnFireEvent_Select(oNode) {
        $(document).trigger(this.oEvents.debug_messages_select, [oNode])
    }

    static fnInitComponent()
    {
        this.fnComponent({
            url: '',
            method:'get',

            singleSelect: true,
            fit: true,
            border: false,

            toolbar: '#debug-log-messages-tt',

            pagination: true,
            pageSize: 20,
            pageList: [20, 30, 40, 50, 60, 70, 80, 90, 100],

            idField:'id',
            treeField:'name',
            columns:[[
                {
                    title:'Название',field:'message',width:390,
                    formatter: function(value,row,index) {
                        var s = `${row.message}<br><small>${row.file.file}:${row.file.function ? row.file.function+':' : ''}${row.file.line}</small>`;
                        // s += '&nbsp;<span style=\'color:blue\'>(' + row.count + ')</span>';
                        return s;
                    }
                },
            ]],

            onSelect: ((iIndex, oNode) => {
                this._oSelected = oNode;
                this.fnFireEvent_Select(oNode);
            }).bind(this),

            // onContextMenu: (function(e, node) {
            //     e.preventDefault();
            //     this.fnSelect(node.target);
            //     this.oContextMenu.menu('show', {
            //         left: e.pageX,
            //         top: e.pageY,
            //         onClick: ((item) => {
            //             if (item.id == 'add') {
            //                 this.fnShowCreateWindow();
            //             }
            //             if (item.id == 'add_link') {
            //                 this.fnFireEvent_LinksAdd();
            //             }
            //             if (item.id == 'edit') {
            //                 this.fnShowEditWindow(node);
            //             }
            //             if (item.id == 'delete') {
            //                 this.fnDelete(node);
            //             }
            //             if (item.id == 'move_to_root_debug_message') {
            //                 this.fnMoveToRoot(node);
            //             }
            //         }).bind(this)
            //     });
            // }).bind(this),
        })
    }

    static fnPrepare()
    {
        this.fnInitComponent()
        this.fnBindEvents();
    }
}