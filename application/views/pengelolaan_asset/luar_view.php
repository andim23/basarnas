<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php header("Content-Type: application/x-javascript"); ?>

<?php if (isset($jsscript) && $jsscript == TRUE) { ?>
<script>
///////////
        var Params_M_Luar = null;

        Ext.namespace('Luar', 'Luar.reader', 'Luar.proxy',
                'Luar.Data', 'Luar.Grid', 'Luar.Window', 'Luar.Form', 'Luar.Action', 'Luar.URL');

        Luar.dataStorePemeliharaan = new Ext.create('Ext.data.Store', {
            model: MPemeliharaan, autoLoad: false, noCache: false,
            proxy: new Ext.data.AjaxProxy({
                url: BASE_URL + 'Pemeliharaan/getSpecificPemeliharaan', actionMethods: {read: 'POST'},
                reader: new Ext.data.JsonReader({
                    root: 'results', totalProperty: 'total', idProperty: 'id'})
            })
        });

        Luar.URL = {
            read: BASE_URL + 'asset_Luar/getAllData',
            createUpdate: BASE_URL + 'asset_Luar/modifyLuar',
            remove: BASE_URL + 'asset_Luar/deleteLuar',
            createUpdatePemeliharaan: BASE_URL + 'Pemeliharaan/modifyPemeliharaan',
            removePemeliharaan: BASE_URL + 'Pemeliharaan/deletePemeliharaan'

        };

        Luar.reader = new Ext.create('Ext.data.JsonReader', {
            id: 'Reader_Luar', root: 'results', totalProperty: 'total', idProperty: 'id'
        });

        Luar.proxy = new Ext.create('Ext.data.AjaxProxy', {
            id: 'Proxy_Luar',
            url: Luar.URL.read, actionMethods: {read: 'POST'}, extraParams: {id_open: '1'},
            reader: Luar.reader,
            afterRequest: function(request, success) {
                Params_M_Luar = request.operation.params;
                
                //USED FOR MAP SEARCH
                var paramsUnker = request.params.searchUnker;
                if(paramsUnker != null ||paramsUnker != undefined)
                {
                    Luar.Data.clearFilter();
                    Luar.Data.filter([{property: 'nama_unker', value: paramsUnker, anyMatch:true}]);
                }
            }
        });

        Luar.Data = new Ext.create('Ext.data.Store', {
            id: 'Data_Luar', storeId: 'DataLuar', model: 'MLuar', pageSize: 50, noCache: false, autoLoad: true,
            proxy: Luar.proxy, groupField: 'tipe'
        });

        Luar.Form.create = function(data, edit) {
            var form = Form.asset(Luar.URL.createUpdate, Luar.Data, edit);
            form.insert(0, Form.Component.unit(edit,form));
            form.insert(1, Form.Component.kode(edit));
            form.insert(2, Form.Component.klasifikasiAset(edit))
            form.insert(5, Form.Component.luar());
            form.insert(6, Form.Component.fileUpload());
            if (data !== null)
            {
                form.getForm().setValues(data);
            }

            return form;
        };

        Luar.Form.createPemeliharaan = function(data, dataForm, edit) {
            var setting = {
                url: Luar.URL.createUpdatePemeliharaan,
                data: data,
                isEditing: edit,
                isBangunan: false,
                addBtn: {
                    isHidden: true,
                    text: '',
                    fn: function() {
                    }
                },
                selectionAsset: {
                    noAsetHidden: false
                }
            };

            var form = Form.pemeliharaanInAsset(setting);

            if (dataForm !== null)
            {
                form.getForm().setValues(dataForm);
            }
            return form;
        };

        Luar.Window.actionSidePanels = function() {
            var actions = {
                details: function() {
                    var _tab = Modal.assetEdit.getComponent('asset-window-tab');
                    var tabpanels = _tab.getComponent('luar-details');
                    if (tabpanels === undefined)
                    {
                        Luar.Action.edit('luar-details');
                    }
                },
                pengadaan: function() {
                    var _tab = Modal.assetEdit.getComponent('asset-window-tab');
                    var tabpanels = _tab.getComponent('luar-pengadaan');
                    if (tabpanels === undefined)
                    {
                        Luar.Action.detail_pengadaan();
                    }
                },
                pemeliharaan: function() {
                    var _tab = Modal.assetEdit.getComponent('asset-window-tab');
                    var tabpanels = _tab.getComponent('luar-pemeliharaan');
                    if (tabpanels === undefined)
                    {
                        Luar.Action.pemeliharaanList();
                    }
                },
                perencanaan: function() {
                    var _tab = Modal.assetEdit.getComponent('asset-window-tab');
                    var tabpanels = _tab.getComponent('luar-perencanaan');
                    if (tabpanels === undefined)
                    {
                        Luar.Action.detail_perencanaan();
                    }
                }

            };

            return actions;
        };

        Luar.Action.detail_perencanaan = function() {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                Ext.Ajax.request({
                    url: BASE_URL + 'perencanaan/getByID/',
                    params: {
                        id_perencanaan: 1
                    },
                    success: function(resp)
                    {
                        var form = Form.pengadaan(BASE_URL + 'Perencanaan/modifyPerencanaan', resp.responseText);
                        Tab.addToForm(form, 'luar-perencanaan', 'Simak Perencanaan');
                        Modal.assetEdit.show();
                    }
                });
            }
        };


        Luar.Action.detail_pengadaan = function() {

            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                var params = {
                    kd_lokasi: data.kd_lokasi,
                    kd_unor: data.kd_unor,
                    kd_brg: data.kd_brg,
                    no_aset: data.no_aset
                };

                Ext.Ajax.request({
                    url: BASE_URL + 'pengadaan/getByKode/',
                    params: params,
                    success: function(resp)
                    {
                        var jsonData = params;
                        var response = Ext.decode(resp.responseText);

                        if (response.length > 0)
                        {
                            var jsonData = response[0];
                        }

                        console.log(jsonData);

                        var setting = {
                            url: BASE_URL + 'Pengadaan/modifyPengadaan',
                            data: jsonData,
                            isEditing: false,
                            addBtn: {
                                isHidden: true,
                                text: '',
                                fn: function() {
                                }
                            },
                            selectionAsset: {
                                noAsetHidden: false
                            }
                        };
                        var form = Form.pengadaanInAsset(setting);

                        if (jsonData !== null || jsonData !== undefined)
                        {
                            form.getForm().setValues(jsonData);
                        }
                        Tab.addToForm(form, 'tanah-pengadaan', 'Pengadaan');
                        Modal.assetEdit.show();
                    }
                });
            }
        };

        Luar.Action.pemeliharaanEdit = function() {
            var selected = Ext.getCmp('luar_grid_pemeliharaan').getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var dataForm = selected[0].data;
                var form = Luar.Form.createPemeliharaan(Luar.dataStorePemeliharaan, dataForm, true);
                Tab.addToForm(form, 'luar-edit-pemeliharaan', 'Edit Pemeliharaan');
                Modal.assetEdit.show();
            }
        };

        Luar.Action.pemeliharaanRemove = function() {
            var selected = Ext.getCmp('luar_grid_pemeliharaan').getSelectionModel().getSelection();
            if (selected.length > 0)
            {
                var arrayDeleted = [];
                _.each(selected, function(obj) {
                    var data = {
                        id: obj.data.id
                    };
                    arrayDeleted.push(data);
                });
                console.log(arrayDeleted);
                Modal.deleteAlert(arrayDeleted, Luar.URL.removePemeliharaan, Luar.dataStorePemeliharaan);
            }
        };


        Luar.Action.pemeliharaanAdd = function()
        {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            var data = selected[0].data;
            var dataForm = {
                kd_lokasi: data.kd_lokasi,
                kd_brg: data.kd_brg,
                no_aset: data.no_aset
            };

            var form = Luar.Form.createPemeliharaan(Luar.dataStorePemeliharaan, dataForm, false);
            Tab.addToForm(form, 'luar-add-pemeliharaan', 'Add Pemeliharaan');
        };

        Luar.Action.pemeliharaanList = function() {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                
                Luar.dataStorePemeliharaan.getProxy().extraParams.kd_lokasi = data.kd_lokasi;
                Luar.dataStorePemeliharaan.getProxy().extraParams.kd_brg = data.kd_brg;
                Luar.dataStorePemeliharaan.getProxy().extraParams.no_aset = data.no_aset;
                Luar.dataStorePemeliharaan.load();
                
                var toolbarIDs = {
                    idGrid : "luar_grid_pemeliharaan",
                    add : Luar.Action.pemeliharaanAdd,
                    remove : Luar.Action.pemeliharaanRemove,
                    edit : Luar.Action.pemeliharaanEdit
                };

                var setting = {
                    data: data,
                    dataStore: Luar.dataStorePemeliharaan,
                    toolbar: toolbarIDs,
                    isBangunan: false
                };
                
                var _luarPemeliharaanGrid = Grid.pemeliharaanGrid(setting);
                Tab.addToForm(_luarPemeliharaanGrid, 'luar-pemeliharaan', 'Pemeliharaan');
            }
        };

        Luar.Action.add = function() {
            var _form = Luar.Form.create(null, false);
            Modal.assetCreate.setTitle('Create Luar');
            Modal.assetCreate.add(_form);
            Modal.assetCreate.show();
        };

        Luar.Action.edit = function() {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                delete data.nama_unker;
                delete data.nama_unor;

                if (Modal.assetEdit.items.length === 0)
                {
                    Modal.assetEdit.setTitle('Edit Luar');
                    Modal.assetEdit.add(Region.createSidePanel(Luar.Window.actionSidePanels()));
                    Modal.assetEdit.add(Tab.create());
                }
                var _form = Luar.Form.create(data, true);
                Tab.addToForm(_form, 'luar-details', 'Simak Details');
                Modal.assetEdit.show();
            }
        };

        Luar.Action.remove = function() {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            if (selected.length > 0)
            {
                var arrayDeleted = [];
                _.each(selected, function(obj) {
                    var data = {
                        kd_lokasi: obj.data.kd_lokasi,
                        kd_brg: obj.data.kd_brg,
                        no_aset: obj.data.no_aset,
                        id: obj.data.id
                    };
                    arrayDeleted.push(data);
                });
                console.log(arrayDeleted);
                Modal.deleteAlert(arrayDeleted, Luar.URL.remove, Luar.Data);
            }
        };

        Luar.Action.print = function() {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            var selectedData = "";
            if (selected.length > 0)
            {
                for (var i = 0; i < selected.length; i++)
                {
                    selectedData += selected[i].data.kd_brg + "||" + selected[i].data.no_aset + "||" + selected[i].data.kd_lokasi + ",";
                }
            }
            var gridHeader = Luar.Grid.grid.getView().getHeaderCt().getVisibleGridColumns();
            var gridHeaderList = "";
            //index starts at 2 to exclude the No. column
            for (var i = 2; i < gridHeader.length; i++)
            {
                if (gridHeader[i].dataIndex === undefined || gridHeader[i].dataIndex === "") //filter the action columns in grid
                {
                    //do nothing
                }
                else
                {
                    gridHeaderList += gridHeader[i].text + "&&" + gridHeader[i].dataIndex + "^^";
                }
            }
            var serverSideModelName = "Asset_Luar_Model";
            var title = "Luar";
            var primaryKeys = "kd_lokasi,kd_brg,no_aset";

            var my_form = document.createElement('FORM');
            my_form.name = 'myForm';
            my_form.method = 'POST';
            my_form.action = BASE_URL + 'excel_management/exportToExcel/';

            var my_tb = document.createElement('INPUT');
            my_tb.type = 'HIDDEN';
            my_tb.name = 'serverSideModelName';
            my_tb.value = serverSideModelName;
            my_form.appendChild(my_tb);

            var my_tb = document.createElement('INPUT');
            my_tb.type = 'HIDDEN';
            my_tb.name = 'title';
            my_tb.value = title;
            my_form.appendChild(my_tb);
            document.body.appendChild(my_form);

            my_tb = document.createElement('INPUT');
            my_tb.type = 'HIDDEN';
            my_tb.name = 'primaryKeys';
            my_tb.value = primaryKeys;
            my_form.appendChild(my_tb);
            document.body.appendChild(my_form);

            my_tb = document.createElement('INPUT');
            my_tb.type = 'HIDDEN';
            my_tb.name = 'gridHeaderList';
            my_tb.value = gridHeaderList;
            my_form.appendChild(my_tb);
            document.body.appendChild(my_form);

            my_tb = document.createElement('INPUT');
            my_tb.type = 'HIDDEN';
            my_tb.name = 'selectedData';
            my_tb.value = selectedData;
            my_form.appendChild(my_tb);
            document.body.appendChild(my_form);

            my_form.submit();
        };

        var setting = {
            grid: {
                id: 'grid_Luar',
                title: 'DAFTAR ASSET LUAR',
                column: [
                    {header: 'No', xtype: 'rownumberer', width: 35, resizable: true, style: 'padding-top: .5px;'},
                    {header: 'Klasifikasi Aset', dataIndex: 'nama_klasifikasi_aset', width: 150, hidden: false, groupable: false, filter: {type: 'string'}},
                    {header: 'Kode Klasifikasi Aset Level 1', dataIndex: 'kd_lvl1', width: 150, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Kode Klasifikasi Aset Level 2', dataIndex: 'kd_lvl2', width: 150, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Kode Klasifikasi Aset Level 3', dataIndex: 'kd_lvl3', width: 150, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Kode Klasifikasi Aset', dataIndex: 'kd_klasifikasi_aset', width: 150, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Kode Lokasi', dataIndex: 'kd_lokasi', width: 150, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Kode Barang', dataIndex: 'kd_brg', width: 90, groupable: false, filter: {type: 'string'}},
                    {header: 'No Asset', dataIndex: 'no_aset', width: 60, groupable: false, filter: {type: 'numeric'}},
                    {header: 'Unit Kerja', dataIndex: 'nama_unker', width: 150, groupable: true, filter: {type: 'string'}},
                    {header: 'Unit Organisasi', dataIndex: 'nama_unor', width: 150, groupable: true, filter: {type: 'string'}},
                    {header: 'Lokasi Fisik', dataIndex: 'lok_fisik', width: 150, groupable: true, filter: {type: 'string'}},
                    {header: 'Image Url', dataIndex: 'image_url', width: 50, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Document Url', dataIndex: 'document_url', width: 50, hidden: true, groupable: false, filter: {type: 'string'}},
                ]
            },
            search: {
                id: 'search_Luar'
            },
            toolbar: {
                id: 'toolbar_luar',
                add: {
                    id: 'button_add_Luar',
                    action: Luar.Action.add
                },
                edit: {
                    id: 'button_edit_Luar',
                    action: Luar.Action.edit
                },
                remove: {
                    id: 'button_remove_Luar',
                    action: Luar.Action.remove
                },
                print: {
                    id: 'button_pring_Luar',
                    action: Luar.Action.print
                }
            }
        };

        Luar.Grid.grid = Grid.inventarisGrid(setting, Luar.Data);

        var new_tabpanel_Asset = {
            id: 'luar_panel', title: 'Luar', iconCls: 'icon-tanah_Luar', closable: true, border: false,layout:'border',
            items: [Region.filterPanelAset(Luar.Data),Luar.Grid.grid]
        };

<?php

} else {
    echo "var new_tabpanel_MD = 'GAGAL';";
}
?>