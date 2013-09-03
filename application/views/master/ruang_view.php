<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php header("Content-Type: application/x-javascript"); ?>

<?php if (isset($jsscript) && $jsscript == TRUE) { ?>
    <script>
    /////////
        var Params_M_MasterRuang = null;

        Ext.namespace('MasterRuang', 'MasterRuang.reader', 'MasterRuang.proxy', 'MasterRuang.Data', 'MasterRuang.Grid', 'MasterRuang.Window', 'MasterRuang.Form', 'MasterRuang.Action', 'MasterRuang.URL');
        MasterRuang.URL = {
            read: BASE_URL + 'master_data/ruang_getAllData',
            createUpdate: BASE_URL + 'master_data/ruang_modifyRuang',
            remove: BASE_URL + 'master_data/ruang_deleteRuang'
        };

        MasterRuang.reader = new Ext.create('Ext.data.JsonReader', {
            id: 'Reader.MasterRuang', root: 'results', totalProperty: 'total', idProperty: 'id'
        });

        MasterRuang.writer = new Ext.create('Ext.data.JsonWriter', {type: 'json',
            allowSingle: false});

        MasterRuang.proxy = new Ext.create('Ext.data.AjaxProxy', {
            id: 'Proxy_MasterRuang',
            url: MasterRuang.URL.read, actionMethods: {read: 'POST'}, extraParams: {id_open: '1'},
            reader: MasterRuang.reader,
            writer: MasterRuang.writer,
            afterRequest: function(request, success) {
                Params_M_MasterRuang = request.operation.params;
            }
        });

        MasterRuang.Data = new Ext.create('Ext.data.Store', {
            id: 'Data_MasterRuang', storeId: 'DataMasterRuang', model: 'MMasterRuang', pageSize: 20, noCache: false, autoLoad: true,
            proxy: MasterRuang.proxy, groupField: 'tipe'
        });

        MasterRuang.Form.create = function(data, edit) {
            var setting = {
                url: MasterRuang.URL.createUpdate,
                data: MasterRuang.Data,
                isEditing: edit,
                addBtn: {
                    isHidden: edit,
                    text: 'Add Asset',
                    fn: function() {

                        if (Modal.assetSelection.items.length === 0)
                        {
                            Modal.assetSelection.add(Grid.selectionAsset());
                            Modal.assetSelection.show();
                        }
                        else
                        {
                            console.error('There is existing grid in the popup selection - pemeliharaan');
                        }
                    }
                },
                selectionAsset: {
                    noAsetHidden: false
                }
            };

            var form = Form.pemeliharaan(setting);

            if (data !== null)
            {
                form.getForm().setValues(data);
            }
            return form;
        };

        MasterRuang.Action.add = function() {
//            var _form = MasterRuang.Form.create(null, false);
//            Modal.processCreate.setTitle('Create MasterRuang');
//            Modal.processCreate.add(_form);
//            Modal.processCreate.show();
        };

        MasterRuang.Action.edit = function() {
//            var selected = MasterRuang.Grid.grid.getSelectionModel().getSelection();
//            if (selected.length === 1)
//            {
//                var data = selected[0].data;
//                delete data.nama_unker;
//
//                if (Modal.processEdit.items.length === 0)
//                {
//                    Modal.processEdit.setTitle('Edit MasterRuang');
//                }
//                var _form = MasterRuang.Form.create(data, true);
//                Modal.processEdit.add(_form);
//                Modal.processEdit.show();
//            }
        };

        MasterRuang.Action.remove = function() {
//            var selected = MasterRuang.Grid.grid.getSelectionModel().getSelection();
//            var arrayDeleted = [];
//            _.each(selected, function(obj) {
//                var data = {
//                    id: obj.data.id
//                };
//                arrayDeleted.push(data);
//            });
//            Modal.deleteAlert(arrayDeleted, MasterRuang.URL.remove, MasterRuang.Data);
        };

        MasterRuang.Action.print = function() {
//            var selected = MasterRuang.Grid.grid.getSelectionModel().getSelection();
//            var selectedData = "";
//            if (selected.length > 0)
//            {
//                for (var i = 0; i < selected.length; i++)
//                {
//                    selectedData += selected[i].data.id + ",";
//                }
//            }
//            var gridHeader = MasterRuang.Grid.grid.getView().getHeaderCt().getVisibleGridColumns();
//            var gridHeaderList = "";
//            //index starts at 2 to exclude the No. column
//            for (var i = 2; i < gridHeader.length; i++)
//            {
//                if (gridHeader[i].dataIndex == undefined || gridHeader[i].dataIndex == "") //filter the action columns in grid
//                {
//                    //do nothing
//                }
//                else
//                {
//                    gridHeaderList += gridHeader[i].text + "&&" + gridHeader[i].dataIndex + "^^";
//                }
//            }
//
//            var serverSideModelName = "MasterRuang_Model";
//            var title = "MasterRuang Umum";
//            var primaryKeys = "id";
//
//            my_form = document.createElement('FORM');
//            my_form.name = 'myForm';
//            my_form.method = 'POST';
//            my_form.action = BASE_URL + 'excel_management/exportToExcel/';
//
//            my_tb = document.createElement('INPUT');
//            my_tb.type = 'HIDDEN';
//            my_tb.name = 'serverSideModelName';
//            my_tb.value = serverSideModelName;
//            my_form.appendChild(my_tb);
//
//            my_tb = document.createElement('INPUT');
//            my_tb.type = 'HIDDEN';
//            my_tb.name = 'title';
//            my_tb.value = title;
//            my_form.appendChild(my_tb);
//            document.body.appendChild(my_form);
//
//            my_tb = document.createElement('INPUT');
//            my_tb.type = 'HIDDEN';
//            my_tb.name = 'primaryKeys';
//            my_tb.value = primaryKeys;
//            my_form.appendChild(my_tb);
//            document.body.appendChild(my_form);
//
//            my_tb = document.createElement('INPUT');
//            my_tb.type = 'HIDDEN';
//            my_tb.name = 'gridHeaderList';
//            my_tb.value = gridHeaderList;
//            my_form.appendChild(my_tb);
//            document.body.appendChild(my_form);
//
//            my_tb = document.createElement('INPUT');
//            my_tb.type = 'HIDDEN';
//            my_tb.name = 'selectedData';
//            my_tb.value = selectedData;
//            my_form.appendChild(my_tb);
//            document.body.appendChild(my_form);
//
//            my_form.submit();
        };

        var setting = {
            grid: {
                id: 'grid_MasterRuang',
                title: 'RUANG',
                column: [
                    {header: 'No', xtype: 'rownumberer', width: 35, resizable: true, style: 'padding-top: .5px;'},
                    {header: 'Id', dataIndex: 'id', width: 130, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Id Warehouse', dataIndex: 'warehouse_id', width: 130, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Nama Warehouse', dataIndex: 'nama_warehouse', width: 130, hidden: false, groupable: false, filter: {type: 'string'}},
                    {header: 'Nama', dataIndex: 'nama', width: 130, hidden: false, groupable: false, filter: {type: 'string'}},
             
                ]
            },
            search: {
                id: 'search_MasterRuang'
            },
            toolbar: {
                id: 'toolbar_MasterRuang',
                add: {
                    id: 'button_add_MasterRuang',
                    action: MasterRuang.Action.add
                },
                edit: {
                    id: 'button_edit_MasterRuang',
                    action: MasterRuang.Action.edit
                },
                remove: {
                    id: 'button_remove_MasterRuang',
                    action: MasterRuang.Action.remove
                },
                print: {
                    id: 'button_print_MasterRuang',
                    action: MasterRuang.Action.print
                }
            }
        };

        MasterRuang.Grid.grid = Grid.processGrid(setting, MasterRuang.Data);

        var new_tabpanel_MD = {
            xtype: 'panel',
            id: 'master_ruang', title: 'Ruang', iconCls: 'icon-menu_impasing', border: false, closable: true,
            layout: 'border', items: [MasterRuang.Grid.grid]
        };
     
<?php } else {
    echo "var new_tabpanel_MD = 'GAGAL';";
} ?>