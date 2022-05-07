import { tpl, fnAlertMessage } from "./lib.js"

export class Projects {
    static sURL = ``

    static _oSelected = null;
    
    static oURLs = {
        create: 'ajax.php?method=create_project',
        update: tpl`ajax.php?method=update_project&id=${0}`,
        delete: 'ajax.php?method=delete_project',
        list: `ajax.php?method=list_projects`,
        scan_projects: `ajax.php?method=scan_projects`,

        project_clean_all: tpl`ajax.php?method=project_clean_all`,
    }
    static oWindowTitles = {
        create: 'Новый проект',
        update: 'Редактировать проект'
    }
    static oEvents = {
        projects_save: "projects:save",
        projects_select: "projects:select",
    }

    static get oDialog() {
        return $('#debug-projects-dlg');
    }
    static get oDialogForm() {
        return $('#debug-projects-dlg-fm');
    }
    static get oComponent() {
        return $("#debug-projects-table");
    }
    static get oContextMenu() {
        return $("#debug-projects-mm");
    }

    static get oEditDialogprojectCleanBtn() {
        return $('#debug-projects-dlg-clean-btn');
    }
    static get oEditDialogSaveBtn() {
        return $('#debug-projects-dlg-save-btn');
    }
    static get oEditDialogCancelBtn() {
        return $('#debug-projects-dlg-cancel-btn');
    }

    static get oPanelAddButton() {
        return $('#debug-projects-add-btn');
    }
    static get oPanelEditButton() {
        return $('#debug-projects-edit-btn');
    }
    static get oPanelRemoveButton() {
        return $('#debug-projects-remove-btn');
    }
    static get oPanelReloadButton() {
        return $('#debug-projects-reload-btn');
    }
    static get oPanelScanButton() {
        return $('#debug-projects-scan-btn');
    }
    static get oPanelClearFilesButton() {
        return $('#debug-projects-clear-files-btn');
    }

    static get fnComponent() {
        return this.oComponent.datagrid.bind(this.oComponent);
    }

    static get oSelectedproject() {
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
        this.fnComponent('reload');
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

    static fnScanForProjects() {
        $.post(
            this.oURLs.scan_projects,
            { },
            (function(result) {
                this.fnReload();
            }).bind(this),
            'json'
        );
    }

    static fnCleanProject() {
        $.post(
            this.oURLs.project_clean_all,
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
            this._oSelected = oNode;
            this.fnReloadLists();
        }).bind(this))

        this.oEditDialogprojectCleanBtn.click((() => {

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
        this.oPanelScanButton.click((() => {
            this.fnScanForProjects();
        }).bind(this))

        this.oPanelClearFilesButton.click((() => {
            this.fnCleanProject();
        }).bind(this))
    }

    static fnFireEvent_Save() {
        $(document).trigger(this.oEvents.projects_save);
    }

    static fnFireEvent_Select(oNode) {
        $(document).trigger(this.oEvents.projects_select, [oNode])
    }

    static fnInitComponent()
    {
        this.fnComponent({
            url: this.oURLs.list,
            method:'get',

            singleSelect: true,
            fit: true,
            border: false,

            toolbar: '#debug-projects-tt',

            clientPaging: false,

            idField:'id',
            treeField:'name',
            columns:[[
                {title:'Название',field:'name',width:190},
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