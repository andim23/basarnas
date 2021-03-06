<?php
class inventory_perlengkapan extends MY_Controller {

	function __construct() {
		parent::__construct();
 		if ($this->my_usession->logged_in == FALSE){
 			echo "window.location = '".base_url()."user/index';";
 			exit;
                }
		$this->load->model('Inventory_Perlengkapan_Model','',TRUE);
		$this->model = $this->Inventory_Perlengkapan_Model;		
	}
	
//	function index(){
//		if($this->input->post("id_open")){
//			$data['jsscript'] = TRUE;
//			$this->load->view('inventory/pemeriksaan_view',$data);
//		}else{
//			$this->load->view('inventory/pemeriksaan_view');
//		}
//	}
        
        
        
        /*
         * PENGADAAN 
         */
        function createPengadaanPerlengkapan(){
            $data = json_decode($this->input->post('data'));
            if(count($data) > 1)
            {
                foreach($data as $row)
                {
                   
                    if($row->kd_brg == '')
                    {
                        $row->kd_brg = 0;
                    }
                    $no_aset = $this->noAssetGenerator($row->kd_brg,$row->kd_lokasi);
                    $data_part = $this->model->get_partNumberDetails($row->part_number);
                    $umur = $data_part->umur_maks;
                    $asset_perlengkapan_data = array(
                        'kd_brg'=>$row->kd_brg,
                        'kd_lokasi'=>$row->kd_lokasi,
                        'part_number'=>$row->part_number,
                        'umur'=>$umur,
                        'kondisi'=>$row->status_barang,
                        'dari'=>$row->asal_barang,
                        'serial_number'=>$row->serial_number,
                        'kuantitas'=>$row->qty,
                        'id_pengadaan'=>$row->id_source,
                        'no_aset'=>$no_aset
                    );
                    unset($row->kd_lokasi);
                    $this->db->insert('asset_perlengkapan',$asset_perlengkapan_data);
                    $id_asset_perlengkapan = $this->db->insert_id();
                    $row->id_asset_perlengkapan = $id_asset_perlengkapan;
                    $this->db->insert('pengadaan_data_perlengkapan',$row);
                    
                    $this->createLog('INSERT PENGADAAN PERLENGKAPAN [id_pengadaan='.$row->id_source.']','pengadaan_data_perlengkapan');
                    
                }
            }
            else
            {
                
                if($data->kd_brg == '')
                {
                    $data->kd_brg = 0;
                }
                $no_aset = $this->noAssetGenerator($data->kd_brg,$data->kd_lokasi);
                $data_part = $this->model->get_partNumberDetails($data->part_number);
                $umur = $data_part->umur_maks;
                $asset_perlengkapan_data = array(
                        'kd_brg'=>$data->kd_brg,
                        'kd_lokasi'=>$data->kd_lokasi,
                        'part_number'=>$data->part_number,
                        'umur'=>$umur,
                        'kondisi'=>$data->status_barang,
                        'dari'=>$data->asal_barang,
                        'serial_number'=>$data->serial_number,
                        'kuantitas'=>$data->qty,
                        'no_aset'=>$no_aset,
                        'id_pengadaan'=>$data->id_source,
                    );
                unset($data->kd_lokasi);
                $this->db->insert('asset_perlengkapan',$asset_perlengkapan_data);
                $id_asset_perlengkapan = $this->db->insert_id();
                $data->id_asset_perlengkapan = $id_asset_perlengkapan;
                $this->db->insert('pengadaan_data_perlengkapan',$data);
                $this->createLog('INSERT PENGADAAN PERLENGKAPAN [id_pengadaan='.$data->id_source.']','pengadaan_data_perlengkapan');
            }
            
            echo "{success:true}";
	}
        
       function updatePengadaanPerlengkapan(){
            $data = json_decode($this->input->post('data'));
            if(count($data) > 1)
            {
                foreach($data as $row)
                {
                    $this->db->set($row);
                    $this->db->replace('pengadaan_data_perlengkapan');
                    $this->createLog('UPDATE PENGADAAN PERLENGKAPAN [id_pengadaan='.$row->id_source.']','pengadaan_data_perlengkapan');
                }
            }
            else
            {
                    $this->db->set($data);
                    $this->db->replace('pengadaan_data_perlengkapan');
                    $this->createLog('UPDATE PENGADAAN PERLENGKAPAN [id_pengadaan='.$data->id_source.']','pengadaan_data_perlengkapan');
            }
            
           

            echo "{success:true}"; 
       }
	
	function destroyPengadaanPerlengkapan()
	{
            $data = json_decode($this->input->post('data'));
            if(count($data) > 1)
            {
                foreach($data as $row)
                {
                    $this->db->delete('pengadaan_data_perlengkapan', array('id' => $row->id));
                    $this->createLog('DELETE PENGADAAN PERLENGKAPAN [id_pengadaan='.$row->id_source.']','pengadaan_data_perlengkapan');
                }
            }
            else
            {
                    $this->db->delete('pengadaan_data_perlengkapan', array('id' => $data->id));
                    $this->createLog('DELETE PENGADAAN PERLENGKAPAN [id_pengadaan='.$data->id_source.']','pengadaan_data_perlengkapan');
            }
            
		 echo "{success:true}"; 
	}
        
        function getSpecificPengadaanPerlengkapan()
        {
            $data = array();
            if(isset($_POST['id_source']))
            {
                $id = $this->input->post('id_source');
                $data = $this->model->get_InventoryPerlengkapan($id,'pengadaan_data_perlengkapan','pengadaan');
                $datasend["results"] = $data['data'];
                $datasend["total"] = $data['count'];
                echo json_encode($datasend);
            }
            
            
        }
        
        /*
         * INVENTORY PENERIMAAN/PEMERIKSAAN
         */
	function createInventoryPenerimaanPemeriksaanPerlengkapan(){
            $data = json_decode($this->input->post('data'));
           
            if(count($data) > 1)
            {
                foreach($data as $row)
                {
                    $this->db->insert('inventory_penerimaan_pemeriksaan_data_perlengkapan',$row);
                    $this->createLog('INSERT INVENTORY PENERIMAAN PEMERIKSAAN PERLENGKAPAN [id_inventory_penerimaan_pemeriksaan='.$row->id_source.']','inventory_penerimaan_pemeriksaan_data_perlengkapan');
                }
            }
            else
            {
                $this->db->insert('inventory_penerimaan_pemeriksaan_data_perlengkapan',$data);
                $this->createLog('INSERT INVENTORY PENERIMAAN PEMERIKSAAN PERLENGKAPAN [id_inventory_penerimaan_pemeriksaan='.$data->id_source.']','inventory_penerimaan_pemeriksaan_data_perlengkapan');
            }
            
            echo "{success:true}";
	}
        
       function updateInventoryPenerimaanPemeriksaanPerlengkapan(){
            $data = json_decode($this->input->post('data'));
            if(count($data) > 1)
            {
                foreach($data as $row)
                {
                    $this->db->set($row);
                    $this->db->replace('inventory_penerimaan_pemeriksaan_data_perlengkapan');
                    $this->createLog('UPDATE INVENTORY PENERIMAAN PEMERIKSAAN PERLENGKAPAN [id_inventory_penerimaan_pemeriksaan='.$row->id_source.']','inventory_penerimaan_pemeriksaan_data_perlengkapan');
                }
            }
            else
            {
                    $this->db->set($data);
                    $this->db->replace('inventory_penerimaan_pemeriksaan_data_perlengkapan');
                    $this->createLog('UPDATE INVENTORY PENERIMAAN PEMERIKSAAN PERLENGKAPAN [id_inventory_penerimaan_pemeriksaan='.$data->id_source.']','inventory_penerimaan_pemeriksaan_data_perlengkapan');
            }
            
           

            echo "{success:true}"; 
       }
	
	function destroyInventoryPenerimaanPemeriksaanPerlengkapan()
	{
            $data = json_decode($this->input->post('data'));
            if(count($data) > 1)
            {
                foreach($data as $row)
                {
                    $this->db->delete('inventory_penerimaan_pemeriksaan_data_perlengkapan', array('id' => $row->id));
                    $this->createLog('DELETE INVENTORY PENERIMAAN PEMERIKSAAN PERLENGKAPAN [id_inventory_penerimaan_pemeriksaan='.$row->id_source.']','inventory_penerimaan_pemeriksaan_data_perlengkapan');
                }
            }
            else
            {
                    $this->db->delete('inventory_penerimaan_pemeriksaan_data_perlengkapan', array('id' => $data->id));
                    $this->createLog('DELETE INVENTORY PENERIMAAN PEMERIKSAAN PERLENGKAPAN [id_inventory_penerimaan_pemeriksaan='.$data->id_source.']','inventory_penerimaan_pemeriksaan_data_perlengkapan');
            }
            
		 echo "{success:true}"; 
	}
        
        function getSpecificInventoryPenerimaanPemeriksaanPerlengkapan()
        {
            $data = array();
            if(isset($_POST['id_source']))
            {
                $id = $this->input->post('id_source');
                $data = $this->model->get_InventoryPerlengkapan($id,'inventory_penerimaan_pemeriksaan_data_perlengkapan','inventory_penerimaan_pemeriksaan');
                $datasend["results"] = $data['data'];
                $datasend["total"] = $data['count'];
                echo json_encode($datasend);
                
            }
            
            
        }
        
        
        /*
         * INVENTORY PENYIMPANAN
         */
	function createInventoryPenyimpananPerlengkapan(){
            $data = json_decode($this->input->post('data'));
//            var_dump($data);
//            die;
            
            
            if(count($data) > 1)
            {
                foreach($data as $row)
                {
                    unset($row->nama_warehouse,$row->nama_ruang,$row->nama_rak,$row->invalid_grid_field_count);
                    $this->db->insert('inventory_penyimpanan_data_perlengkapan',$row);
                    $last_insert_id_penyimpanan = $this->db->insert_id();
                    if($row->id_asset_perlengkapan != 0 && $row->id_asset_perlengkapan != null)
                    {
                        $query = "select b.id_pengadaan from inventory_penyimpanan as a
                        INNER JOIN inventory_penerimaan_pemeriksaan as b ON a.id_penerimaan_pemeriksaan = b.id
                        where a.id = $row->id_source";
                        $result = $this->db->query($query);
                        $id_pengadaan = $result->row()->id_pengadaan;
                        $data_warehouse = array(
                            "warehouse_id"=>$row->id_warehouse,
                            "ruang_id"=>$row->id_warehouse_ruang,
                            "rak_id"=>$row->id_warehouse_rak
                        );
                        $this->db->where('id_pengadaan',$id_pengadaan);
                        $this->db->where('id',$row->id_asset_perlengkapan);
                        $this->db->update('asset_perlengkapan',$data_warehouse);
                        
                    }
                    else
                    {
                        $data_part = $this->model->get_partNumberDetails($row->part_number);
                        $umur = $data_part->umur_maks;
                        $query = $this->db->query("select * from inventory_penyimpanan where id=$row->id_source");
                        $result = $query->row();
                         $perlengkapan_data = array(
                            "kd_lokasi"=>$result->kd_lokasi,
                            "kode_unor"=>$result->kode_unor,
                            "kd_brg"=>$row->kd_brg,
                            "umur"=>$umur,
                            "no_aset"=>$this->noAssetGenerator($row->kd_brg, $result->kd_lokasi),
                            "kuantitas"=>$row->qty,
                            "kondisi"=>$row->status_barang,
                            "dari"=>$row->asal_barang,
                            "part_number"=>$row->part_number,
                            "serial_number"=>$row->serial_number,
                            "warehouse_id"=>$row->id_warehouse,
                            "ruang_id"=>$row->id_warehouse_ruang,
                            "rak_id"=>$row->id_warehouse_rak
                        );
                         $this->db->insert("asset_perlengkapan",$perlengkapan_data);
                         $last_insert_id_perlengkapan = $this->db->insert_id();
                         $update_data = array(
                             "id_asset_perlengkapan"=>$last_insert_id_perlengkapan
                         );
                         $this->db->where("id",$last_insert_id_penyimpanan);
                         $this->db->update("inventory_penyimpanan_data_perlengkapan",$update_data);
                         
                    }
                    $this->createLog('INSERT INVENTORY PENYIMPANAN PERLENGKAPAN [id_inventory_penyimpanan='.$row->id_source.']','inventory_penyimpanan_data_perlengkapan');
                    
                }
                
            }
            else
            {
                unset($data->nama_warehouse,$data->nama_ruang,$data->nama_rak,$data->invalid_grid_field_count);
                $this->db->insert('inventory_penyimpanan_data_perlengkapan',$data);
                $last_insert_id_penyimpanan = $this->db->insert_id();
                if($data->id_asset_perlengkapan != 0 && $data->id_asset_perlengkapan != null)
                {
                    $query = "select b.id_pengadaan from inventory_penyimpanan as a
                    INNER JOIN inventory_penerimaan_pemeriksaan as b ON a.id_penerimaan_pemeriksaan = b.id
                    where a.id = $data->id_source";
                    $result = $this->db->query($query);
                    $id_pengadaan = $result->row()->id_pengadaan;
                    $data_warehouse = array(
                        "warehouse_id"=>$data->id_warehouse,
                        "ruang_id"=>$data->id_warehouse_ruang,
                        "rak_id"=>$data->id_warehouse_rak
                    );
                    $this->db->where('id_pengadaan',$id_pengadaan);
                    $this->db->where('id',$data->id_asset_perlengkapan);
                    $this->db->update('asset_perlengkapan',$data_warehouse);
                    
                }
                else
                {
                    $data_part = $this->model->get_partNumberDetails($data->part_number);
                    $umur = $data_part->umur_maks;
                    $query = $this->db->query("select * from inventory_penyimpanan where id=$data->id_source");
                    $result = $query->row();
                     $perlengkapan_data = array(
                        "kd_lokasi"=>$result->kd_lokasi,
                        "kode_unor"=>$result->kode_unor,
                        "kd_brg"=>$data->kd_brg,
                        "kuantitas"=>$data->qty,
                        "umur"=>$umur,
                        "no_aset"=>$this->noAssetGenerator($data->kd_brg, $result->kd_lokasi),
                        "kondisi"=>$data->status_barang,
                        "dari"=>$data->asal_barang,
                        "part_number"=>$data->part_number,
                        "serial_number"=>$data->serial_number,
                        "warehouse_id"=>$data->id_warehouse,
                        "ruang_id"=>$data->id_warehouse_ruang,
                        "rak_id"=>$data->id_warehouse_rak
                    );
                     $this->db->insert("asset_perlengkapan",$perlengkapan_data);
                     $last_insert_id_perlengkapan = $this->db->insert_id();
                     $update_data = array(
                         "id_asset_perlengkapan"=>$last_insert_id_perlengkapan
                     );
                     $this->db->where("id",$last_insert_id_penyimpanan);
                     $this->db->update("inventory_penyimpanan_data_perlengkapan",$update_data);

                }
                $this->createLog('INSERT INVENTORY PENYIMPANAN PERLENGKAPAN [id_inventory_penyimpanan='.$data->id_source.']','inventory_penyimpanan_data_perlengkapan');
            }
            
            echo "{success:true}";
	}
        
       function updateInventoryPenyimpananPerlengkapan(){
            $data = json_decode($this->input->post('data'));
            if(count($data) > 1)
            {
                foreach($data as $row)
                {
                    unset($row->nama_warehouse,$row->nama_ruang,$row->nama_rak,$row->invalid_grid_field_count);
                    $this->db->set($row);
                    $this->db->replace('inventory_penyimpanan_data_perlengkapan');
                    if($row->id_asset_perlengkapan != 0 && $row->id_asset_perlengkapan != null)
                    {
                        $query = "select b.id_pengadaan from inventory_penyimpanan as a
                        INNER JOIN inventory_penerimaan_pemeriksaan as b ON a.id_penerimaan_pemeriksaan = b.id
                        where a.id = $row->id_source";
                        $result = $this->db->query($query);
                        $id_pengadaan = $result->row()->id_pengadaan;
                        $data_warehouse = array(
                            "warehouse_id"=>$row->id_warehouse,
                            "ruang_id"=>$row->id_warehouse_ruang,
                            "rak_id"=>$row->id_warehouse_rak
                        );
                        $this->db->where('id_pengadaan',$id_pengadaan);
                        $this->db->where('id',$row->id_asset_perlengkapan);
                        $this->db->update('asset_perlengkapan',$data_warehouse);
                        
                    }
                    $this->createLog('UPDATE INVENTORY PENYIMPANAN PERLENGKAPAN [id_inventory_penyimpanan='.$row->id_source.']','inventory_penyimpanan_data_perlengkapan');
                }
            }
            else
            {
                    unset($data->nama_warehouse,$data->nama_ruang,$data->nama_rak,$data->invalid_grid_field_count);
                    $this->db->set($data);
                    $this->db->replace('inventory_penyimpanan_data_perlengkapan');
                    if($data->id_asset_perlengkapan != 0 && $data->id_asset_perlengkapan != null)
                    {
                        $query = "select b.id_pengadaan from inventory_penyimpanan as a
                        INNER JOIN inventory_penerimaan_pemeriksaan as b ON a.id_penerimaan_pemeriksaan = b.id
                        where a.id = $data->id_source";
                        $result = $this->db->query($query);
                        $id_pengadaan = $result->row()->id_pengadaan;
                        $data_warehouse = array(
                            "warehouse_id"=>$data->id_warehouse,
                            "ruang_id"=>$data->id_warehouse_ruang,
                            "rak_id"=>$data->id_warehouse_rak
                        );
                        $this->db->where('id_pengadaan',$id_pengadaan);
                        $this->db->where('id',$data->id_asset_perlengkapan);
                        $this->db->update('asset_perlengkapan',$data_warehouse);

                    }
                    $this->createLog('UPDATE INVENTORY PENYIMPANAN PERLENGKAPAN [id_inventory_penyimpanan='.$data->id_source.']','inventory_penyimpanan_data_perlengkapan');
            }
            
           

            echo "{success:true}"; 
       }
	
	function destroyInventoryPenyimpananPerlengkapan()
	{
            $data = json_decode($this->input->post('data'));
            if(count($data) > 1)
            {
                foreach($data as $row)
                {
                    $this->db->delete('inventory_penyimpanan_data_perlengkapan', array('id' => $row->id));
                    if($row->id_asset_perlengkapan != 0 && $row->id_asset_perlengkapan != null)
                    {
                        $query = "select b.id_pengadaan from inventory_penyimpanan as a
                        INNER JOIN inventory_penerimaan_pemeriksaan as b ON a.id_penerimaan_pemeriksaan = b.id
                        where a.id = $row->id_source";
                        $result = $this->db->query($query);
                        $id_pengadaan = $result->row()->id_pengadaan;
                        $data_warehouse = array(
                            "warehouse_id"=>0,
                            "ruang_id"=>0,
                            "rak_id"=>0
                        );
                        $this->db->where('id_pengadaan',$id_pengadaan);
                        $this->db->where('id',$row->id_asset_perlengkapan);
                        $this->db->update('asset_perlengkapan',$data_warehouse);
                        
                    }
                    $this->createLog('DELETE INVENTORY PENYIMPANAN PERLENGKAPAN [id_inventory_penyimpanan='.$row->id_source.']','inventory_penyimpanan_data_perlengkapan');
                }
            }
            else
            {
                    $this->db->delete('inventory_penyimpanan_data_perlengkapan', array('id' => $data->id));
                    if($data->id_asset_perlengkapan != 0 && $data->id_asset_perlengkapan != null)
                    {
                        $query = "select b.id_pengadaan from inventory_penyimpanan as a
                        INNER JOIN inventory_penerimaan_pemeriksaan as b ON a.id_penerimaan_pemeriksaan = b.id
                        where a.id = $data->id_source";
                        $result = $this->db->query($query);
                        $id_pengadaan = $result->row()->id_pengadaan;
                        $data_warehouse = array(
                            "warehouse_id"=>0,
                            "ruang_id"=>0,
                            "rak_id"=>0
                        );
                        $this->db->where('id_pengadaan',$id_pengadaan);
                        $this->db->where('id',$data->id_asset_perlengkapan);
                        $this->db->update('asset_perlengkapan',$data_warehouse);

                    }
                    $this->createLog('DELETE INVENTORY PENYIMPANAN PERLENGKAPAN [id_inventory_penyimpanan='.$data->id_source.']','inventory_penyimpanan_data_perlengkapan');
            }
            
		 echo "{success:true}"; 
	}
        
        function getSpecificInventoryPenyimpananPerlengkapan()
        {
            $data = array();
            if(isset($_POST['id_source']))
            {
                $id = $this->input->post('id_source');
                $data = $this->model->get_InventoryPerlengkapanPenyimpanan($id);
                $datasend["results"] = $data['data'];
                $datasend["total"] = $data['count'];
                echo json_encode($datasend);
            }
            
            
        }
        
        
        /*
         * INVENTORY PENYIMPANAN
         */
	function createInventoryPengeluaranPerlengkapan(){
            $data = json_decode($this->input->post('data'));
//            var_dump($_POST);
//            die;
            if(count($data) > 1)
            {
                foreach($data as $row)
                {
                    $qty_akhir = ($row->qty) - ($row->qty_keluar);
                    unset($row->qty,$row->nomor_berita_acara,$row->part_number,$row->nama_warehouse);
                    $this->db->insert('inventory_pengeluaran_data_perlengkapan',$row);
                    $query = "update inventory_penyimpanan_data_perlengkapan set qty= $qty_akhir where id=$row->id_penyimpanan_data_perlengkapan";
                    $this->db->query($query);
                    $this->createLog('INSERT INVENTORY PENGELUARAN PERLENGKAPAN [id_inventory_pengeluaran='.$row->id_source.']','inventory_pengeluaran_data_perlengkapan');
                }
            }
            else
            {
                $qty_akhir = ($data->qty) - ($data->qty_keluar);
                unset($data->qty,$data->nomor_berita_acara,$data->part_number,$data->nama_warehouse);
                $this->db->insert('inventory_pengeluaran_data_perlengkapan',$data);
                $query = "update inventory_penyimpanan_data_perlengkapan set qty= $qty_akhir where id=$data->id_penyimpanan_data_perlengkapan";
                $this->db->query($query);
                $this->createLog('INSERT INVENTORY PENGELUARAN PERLENGKAPAN [id_inventory_pengeluaran='.$data->id_source.']','inventory_pengeluaran_data_perlengkapan');
            }
            
            echo "{success:true}";
	}
        
       function updateInventoryPengeluaranPerlengkapan(){
            $data = json_decode($this->input->post('data'));
            if(count($data) > 1)
            {
                foreach($data as $row)
                {
                    $qty_akhir = ($row->qty) - ($row->qty_keluar);
                    unset($row->qty,$row->nomor_berita_acara,$row->part_number,$row->nama_warehouse);
                    $this->db->set($row);
                    $this->db->replace('inventory_pengeluaran_data_perlengkapan');
                    $query = "update inventory_penyimpanan_data_perlengkapan set qty= $qty_akhir where id=$row->id_penyimpanan_data_perlengkapan";
                    $this->db->query($query);
                    $this->createLog('UPDATE INVENTORY PENGELUARAN PERLENGKAPAN [id_inventory_pengeluaran='.$row->id_source.']','inventory_pengeluaran_data_perlengkapan');
                }
            }
            else
            {
                    $qty_akhir = ($data->qty) - ($data->qty_keluar);
                    unset($data->qty,$data->nomor_berita_acara,$data->part_number,$data->nama_warehouse);
                    $this->db->set($data);
                    $this->db->replace('inventory_pengeluaran_data_perlengkapan');
                    $query = "update inventory_penyimpanan_data_perlengkapan set qty= $qty_akhir where id=$data->id_penyimpanan_data_perlengkapan";
                    $this->db->query($query);
                    $this->createLog('UPDATE INVENTORY PENGELUARAN PERLENGKAPAN [id_inventory_pengeluaran='.$data->id_source.']','inventory_pengeluaran_data_perlengkapan');
            }
            
           

            echo "{success:true}"; 
       }
	
	function destroyInventoryPengeluaranPerlengkapan()
	{
            $data = json_decode($this->input->post('data'));
            if(count($data) > 1)
            {
                foreach($data as $row)
                {
                    $this->db->delete('inventory_pengeluaran_data_perlengkapan', array('id' => $row->id));
                     $query = "update inventory_penyimpanan_data_perlengkapan set qty= (qty + $row->qty_keluar) where id=$row->id_penyimpanan_data_perlengkapan";
                    $this->db->query($query);
                    $this->createLog('DELETE INVENTORY PENGELUARAN PERLENGKAPAN [id_inventory_pengeluaran='.$row->id_source.']','inventory_pengeluaran_data_perlengkapan');
                }
            }
            else
            {
                    $this->db->delete('inventory_pengeluaran_data_perlengkapan', array('id' => $data->id));
                    $query = "update inventory_penyimpanan_data_perlengkapan set qty= (qty + $data->qty_keluar) where id=$data->id_penyimpanan_data_perlengkapan";
                    $this->db->query($query);
                    $this->createLog('DELETE INVENTORY PENGELUARAN PERLENGKAPAN [id_inventory_pengeluaran='.$data->id_source.']','inventory_pengeluaran_data_perlengkapan');
            }
            
		 echo "{success:true}"; 
	}
        
        function getSpecificInventoryPengeluaranPerlengkapan()
        {
            $data = array();
            if(isset($_POST['id_source']))
            {
                $id = $this->input->post('id_source');
                $data = $this->model->get_InventoryPerlengkapanPengeluaran($id);
                $datasend["results"] = $data['data'];
                $datasend["total"] = $data['count'];
                echo json_encode($datasend);
            }
            
            
        }
        
        
        
        
}
?>