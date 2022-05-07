import { tpl, fnAlertMessage } from "./lib.js"

export class DebugMessageInfo {
    static sURL = ``

    static _oSelectedMessage = null;
    
    static oURLs = {
        list: tpl`ajax.php?method=get_debug_message&message_id=${0}`,
    }
    static oEvents = {
        debug_messages_select: "debug_messages:select",
        debug_messages_unselect: "debug_messages:unselect",
    }

    static get oMessageText() { return $("#debug-log-message-info-message-text"); }
    static get oBacktraceTable() { return $("#debug-log-message-info-backtrace-table"); }
    static get oVarsTreeWrapper() { return $("#debug-log-message-info-vars-tree-wrapper"); }
    static get oVarsTree() { return $("#debug-log-message-info-vars-tree"); }
    
    static get fnBacktraceTable() { return this.oBacktraceTable.datagrid.bind(this.oBacktraceTable); }
    static get fnVarsTree() { return this.oVarsTree.treegrid.bind(this.oVarsTree); }

    static fnReload() {
        
    }

    static fnClean() {
        this.oMessageText.html('');
        this.fnBacktraceTable('load', []);
        this.fnVarsTree('load', []);
    }

    static fnBindEvents()
    {
        $(document).on(this.oEvents.debug_messages_unselect, ((oEvent, oNode) => {
            this.fnClean();
        }).bind(this))

        $(document).on(this.oEvents.debug_messages_select, ((oEvent, oNode) => {
            this._oSelectedMessage = oNode;

            var sFile = '';
            var sLine = '';
            var sFunction = '';

            if (oNode.o_back_trace) {
                sFile = oNode.o_back_trace.file ?? '';
                sLine = oNode.o_back_trace.line ?? '';
                sFunction = oNode.o_back_trace.function ?? '';
            }

            this.oMessageText.html(`
                <div class="debug-info-file">
                    <span class="type">${oNode.s_type}</span>
                    <span class="size">${oNode.size_human}</span>
                    <a href="${oNode.editor_url}">${sFile}:${sLine}</a>
                </div>
                <div class="debug-info-message">${oNode.s_message}</div>
            `);
            // this.fnBacktraceTable('load', oNode.a_back_trace);
            // this.fnVarsTree('load', oNode.o_vars);

            this.fnBacktraceTable({
                singleSelect: true,
                fit: true,
                border: false,

                rownumbers: true,

                pagination: true,
                pageSize: 5,
                pageList: [5, 10, 12, 15, 24, 30, 40, 50, 60, 70, 80, 90, 100],

                data: oNode.a_back_trace,
    
                columns:[[
                    {title:'file',field:'file',width:230},
                    {title:'function',field:'function',width:100},
                    {title:'line',field:'line',width:50},
                    {title:'class',field:'class',width:100},
                ]],
            });

            this.fnBacktraceTable('enableFilter', []);

            // this.fnVarsTree('url', `/ajax.php?method=get_vars_tree_grid&id=${oNode.id}`);
            // this.fnVarsTree('reload');

            this.oVarsTreeWrapper.html('<table id="debug-log-message-info-vars-tree" class="easyui-treegrid" data-options="fit:true" style="width:100%;"></table>');

            this.fnVarsTree({
                url: `/ajax.php?method=get_vars_tree_grid&id=${oNode.id}`,
    
                singleSelect: true,
                fit: true,
                border: false,
    
                rownumbers: true,
    
                pagination: true,
                pageSize: 5,
                pageList: [5, 10, 12, 15, 24, 30, 40, 50, 60, 70, 80, 90, 100],
    
                idField: 'id',
                treeField: 'name',
    
                columns:[[
                    {title:'name',field:'name',width:200},
                    {title:'value',field:'value',width:600},
                ]],
            });

            this.fnVarsTree('enableFilter', []);
        }).bind(this))
    }

    static fnInitComponent()
    {

    }

    static fnPrepare()
    {
        this.fnInitComponent()
        this.fnBindEvents();
    }
}