<?php
class Asset_Ruang_Model extends MY_Model{
	
	function __construct(){
		parent::__construct();
		$this->table = 'asset_ruang';
                $this->extTable = 'ext_asset_ruang';
                $this->viewTable = 'view_asset_ruang';
                
//                $this->selectColumn = "SELECT t.kd_lokasi, t.kd_brg, t.no_aset, t.kd_pemilik, t.kd_ruang, a.id, a.kode_unor, a.image_url, a.document_url,
//                                        b.ur_upb as nama_unker, c.nama_unor, d.ur_ruang as ruang, d.pj_ruang as pejabat_ruang, d.nip_pjrug, e.ur_sskel,
//                                        e.kd_gol,e.kd_bid,e.kd_kel as kd_kelompok,e.kd_skel, e.kd_sskel
//                                        ,f.nama as nama_klasifikasi_aset, a.kd_klasifikasi_aset,
//                                        f.kd_lvl1,f.kd_lvl2,f.kd_lvl3";
                
//                $this->selectColumn = "SELECT kd_lokasi, kd_brg, no_aset, kd_pemilik, kd_ruang, id, kode_unor, image_url, document_url,rph_aset,kuantitas,tgl_prl,tgl_buku
//                        nama_unker, nama_unor, ruang, pejabat_ruang, nip_pjrug, ur_sskel,
//                        kd_gol,kd_bid,kd_kelompok,kd_skel, kd_sskel, ur_sskel
//                        ,nama_klasifikasi_aset, kd_klasifikasi_aset,
//                        kd_lvl1,kd_lvl2,kd_lvl3";
                $this->selectColumn = "SELECT * ";
	}
	
	function get_AllData($start=null,$limit=null, $searchByBarcode = null, $gridFilter=null, $searchByField = null){
            
//                if($start !=null && $limit != null)
//                {
//                    $query = "$this->selectColumn
//                        FROM $this->table as t 
//                        LEFT JOIN $this->extTable as a ON t.kd_lokasi = a.kd_lokasi AND t.kd_brg = a.kd_brg AND t.no_aset = a.no_aset
//                        LEFT JOIN ref_unker AS b ON t.kd_lokasi = b.kdlok
//                        LEFT JOIN ref_unor AS c ON a.kode_unor = c.kode_unor
//                        LEFT JOIN ref_ruang as d ON t.kd_lokasi = d.kd_lokasi AND t.kd_ruang = d.kd_ruang
//                        LEFT JOIN ref_subsubkel as e ON t.kd_brg = e.kd_brg
//                        LEFT JOIN ref_klasifikasiaset_lvl3 AS f ON a.kd_klasifikasi_aset = f.kd_klasifikasi_aset
//                        LIMIT $start,$limit";
//                    
//                    if($searchByBarcode != null)
//                    {
//                        $query = "$this->selectColumn
//                        FROM $this->table as t 
//                        LEFT JOIN $this->extTable as a ON t.kd_lokasi = a.kd_lokasi AND t.kd_brg = a.kd_brg AND t.no_aset = a.no_aset
//                        LEFT JOIN ref_unker AS b ON t.kd_lokasi = b.kdlok
//                        LEFT JOIN ref_unor AS c ON a.kode_unor = c.kode_unor
//                        LEFT JOIN ref_ruang as d ON t.kd_lokasi = d.kd_lokasi AND t.kd_ruang = d.kd_ruang
//                        LEFT JOIN ref_subsubkel as e ON t.kd_brg = e.kd_brg
//                        LEFT JOIN ref_klasifikasiaset_lvl3 AS f ON a.kd_klasifikasi_aset = f.kd_klasifikasi_aset
//                         where CONCAT(t.kd_brg,t.kd_lokasi,t.no_aset) = '$searchByBarcode'
//                        LIMIT $start,$limit";
//                    }
//                }
//                else
//                {
//                    $query = "$this->selectColumn
//                        FROM $this->table as t 
//                        LEFT JOIN $this->extTable as a ON t.kd_lokasi = a.kd_lokasi AND t.kd_brg = a.kd_brg AND t.no_aset = a.no_aset
//                        LEFT JOIN ref_unker AS b ON t.kd_lokasi = b.kdlok
//                        LEFT JOIN ref_unor AS c ON a.kode_unor = c.kode_unor
//                        LEFT JOIN ref_ruang as d ON t.kd_lokasi = d.kd_lokasi AND t.kd_ruang = d.kd_ruang
//                        LEFT JOIN ref_subsubkel as e ON t.kd_brg = e.kd_brg
//                        LEFT JOIN ref_klasifikasiaset_lvl3 AS f ON a.kd_klasifikasi_aset = f.kd_klasifikasi_aset
//                        ";
//                    
//                    if($searchByBarcode != null)
//                    {
//                        $query = "$this->selectColumn
//                        FROM $this->table as t 
//                        LEFT JOIN $this->extTable as a ON t.kd_lokasi = a.kd_lokasi AND t.kd_brg = a.kd_brg AND t.no_aset = a.no_aset
//                        LEFT JOIN ref_unker AS b ON t.kd_lokasi = b.kdlok
//                        LEFT JOIN ref_unor AS c ON a.kode_unor = c.kode_unor
//                        LEFT JOIN ref_ruang as d ON t.kd_lokasi = d.kd_lokasi AND t.kd_ruang = d.kd_ruang
//                        LEFT JOIN ref_subsubkel as e ON t.kd_brg = e.kd_brg
//                        LEFT JOIN ref_klasifikasiaset_lvl3 AS f ON a.kd_klasifikasi_aset = f.kd_klasifikasi_aset
//                         where CONCAT(t.kd_brg,t.kd_lokasi,t.no_aset) = '$searchByBarcode'
//                        ";
//                    }
//                }
//		
//                
//                return $this->Get_By_Query($query);	
            
            
            
            
            
            
//            $isGridFilter = false;
//            if($start != null && $limit != null)
//            {
//                $query = "$this->selectColumn
//                                FROM $this->viewTable
//                                LIMIT $start, $limit";
//                if($searchByBarcode != null)
//                {
//                    $query = "$this->selectColumn
//                                FROM $this->viewTable
//                                where CONCAT(kd_brg,kd_lokasi,no_aset) = '$searchByBarcode'
//                                LIMIT $start, $limit";
//                }
//                else if($searchByField != null)
//                {
//                    $query = "$this->selectColumn
//                                FROM $this->viewTable
//                                where
//                                kd_brg like '%$searchByField%' OR
//                                kd_lokasi like '%$searchByField%' OR
//                                nama_unker like '%$searchByField%' OR
//                                nama_unor like '%$searchByField%' OR
//                                nama_klasifikasi_aset like '%$searchByField%'
//                                LIMIT $start, $limit";
//                }
//                else if($gridFilter != null)
//                {
//                    $query = "$this->selectColumn
//                               FROM $this->viewTable
//                               where $gridFilter
//                               LIMIT $start, $limit
//                                ";
//                    $isGridFilter = true;
//                }
//            }
//            else
//            {
//                $query = "$this->selectColumn
//                                 FROM $this->viewTable
//                                ";
//
//                if($searchByBarcode != null)
//                {
//                    $query = "$this->selectColumn
//                                FROM $this->viewTable
//                               where CONCAT(kd_brg,kd_lokasi,no_aset) = '$searchByBarcode'
//                                ";
//                }
//                else if($searchByField != null)
//                {
//                    $query = "$this->selectColumn
//                                FROM $this->viewTable
//                                where
//                                kd_brg like '%$searchByField%' OR
//                                kd_lokasi like '%$searchByField%' OR
//                                nama_unker like '%$searchByField%' OR
//                                nama_unor like '%$searchByField%' OR
//                                nama_klasifikasi_aset like '%$searchByField%'
//                                ";
//                }
//                else if($gridFilter != null)
//                {
//                    $query = "$this->selectColumn
//                                FROM $this->viewTable
//                               where $gridFilter
//                                ";
//                    $isGridFilter = true;
//                }
//            }
//
//            if($isGridFilter == true)
//            {
//                return $this->Get_By_Query($query,true);	
//            }
//            else if($searchByField != null)
//            {
//                return $this->Get_By_Query($query,false,'view_asset_ruang');	
//            }
//            else
//            {
//                return $this->Get_By_Query($query);	
//            }
            
            $countQuery = "select count(*) as total
                                FROM $this->viewTable";
            $nilaiAssetQuery = "select sum(abs(rph_aset)) as nilai_asset
                              FROM $this->viewTable";
            if($start != null && $limit != null)
            {
                $query = "$this->selectColumn
                                FROM $this->viewTable
                                LIMIT $start, $limit";
                
                if($searchByBarcode != null)
                {
                    $query = "$this->selectColumn
                                FROM $this->viewTable
                                where CONCAT(kd_brg,kd_lokasi,no_aset) = '$searchByBarcode'
                                LIMIT $start, $limit";
                     $countQuery = "select count(*) as total
                                FROM $this->table
                                where CONCAT(kd_brg,kd_lokasi,no_aset) = '$searchByBarcode'";
                     $nilaiAssetQuery = "select sum(abs(rph_aset)) as nilai_asset
                                    FROM $this->extTable
                                    where CONCAT(kd_brg,kd_lokasi,no_aset) = '$searchByBarcode'";
                }
                else if($searchByField != null)
                {
                    $query = "$this->selectColumn
                                FROM $this->viewTable
                                where 
                                kd_brg like '%$searchByField%' OR
                                kd_lokasi like '%$searchByField%' OR
                                nama_unker like '%$searchByField%' OR
                                nama_unor like '%$searchByField%' OR
                                nama_klasifikasi_aset like '%$searchByField%'                             
                                LIMIT $start, $limit";
                     $countQuery = "select count(*) as total
                                FROM $this->viewTable
                                where 
                                kd_brg like '%$searchByField%' OR
                                kd_lokasi like '%$searchByField%' OR
                                nama_unker like '%$searchByField%' OR
                                nama_unor like '%$searchByField%' OR
                                nama_klasifikasi_aset like '%$searchByField%'
                             ";
                    $nilaiAssetQuery = "select sum(abs(rph_aset)) as nilai_asset
                                    FROM $this->viewTable
                                    where 
                                    kd_brg like '%$searchByField%' OR
                                    kd_lokasi like '%$searchByField%' OR
                                    nama_unker like '%$searchByField%' OR
                                    nama_unor like '%$searchByField%' OR
                                    nama_klasifikasi_aset like '%$searchByField%'
                            ";
                }
                else if($gridFilter != null)
                {
                    $query = "$this->selectColumn
                               FROM $this->viewTable
                               where $gridFilter
                               LIMIT $start, $limit
                                ";
                     $countQuery = "select count(*) as total
                                FROM $this->viewTable
                                where $gridFilter";
                     $nilaiAssetQuery = "select sum(abs(rph_aset)) as nilai_asset
                                    FROM $this->viewTable
                                    where $gridFilter";
                }
            }
            else
            {
                $query = "$this->selectColumn
                                FROM $this->viewTable
                                ";
          
                
                if($searchByBarcode != null)
                {
                    $query = "$this->selectColumn
                                FROM $this->viewTable
                                where CONCAT(kd_brg,kd_lokasi,no_aset) = '$searchByBarcode'
                                ";
                     $countQuery = "select count(*) as total
                                FROM $this->table
                                where CONCAT(kd_brg,kd_lokasi,no_aset) = '$searchByBarcode'";
                     $nilaiAssetQuery = "select sum(abs(rph_aset)) as nilai_asset
                                    FROM $this->extTable
                                    where CONCAT(kd_brg,kd_lokasi,no_aset) = '$searchByBarcode'";
                }
                else if($searchByField != null)
                {
                    $query = "$this->selectColumn
                                FROM $this->viewTable
                                where 
                                kd_brg like '%$searchByField%' OR
                                kd_lokasi like '%$searchByField%' OR
                                nama_unker like '%$searchByField%' OR
                                nama_unor like '%$searchByField%' OR
                                nama_klasifikasi_aset like '%$searchByField%'                               
                                ";
                     $countQuery = "select count(*) as total
                                FROM $this->viewTable
                                where 
                                kd_brg like '%$searchByField%' OR
                                kd_lokasi like '%$searchByField%' OR
                                nama_unker like '%$searchByField%' OR
                                nama_unor like '%$searchByField%' OR
                                nama_klasifikasi_aset like '%$searchByField%'
                             ";
                    $nilaiAssetQuery = "select sum(abs(rph_aset)) as nilai_asset
                                    FROM $this->viewTable
                                    where 
                                    kd_brg like '%$searchByField%' OR
                                    kd_lokasi like '%$searchByField%' OR
                                    nama_unker like '%$searchByField%' OR
                                    nama_unor like '%$searchByField%' OR
                                    nama_klasifikasi_aset like '%$searchByField%'
                            ";
                }
                else if($gridFilter != null)
                {
                    $query = "$this->selectColumn
                               FROM $this->viewTable
                               where $gridFilter
                               
                                ";
                     $countQuery = "select count(*) as total
                                FROM $this->viewTable
                                where $gridFilter";
                     $nilaiAssetQuery = "select sum(abs(rph_aset)) as nilai_asset
                                    FROM $this->viewTable
                                    where $gridFilter";
                }
            }
            
            $accessControl = array(
                'unker'=>true,
                'unor'=>true
            );
            return $this->Get_By_Query_New($query, $countQuery, $accessControl, $nilaiAssetQuery);
	}
        
        function get_ExtAllData($kd_lokasi,$kd_brg,$no_aset){

        }
	
	function get_byIDs($ids)
	{		
		
	}
        
        function get_Ruang($kd_lokasi,$kd_brg,$no_aset)
        {
            $query = "$this->selectColumn 
                      FROM
                      $this->viewTable
                      where kd_lokasi = '$kd_lokasi' AND kd_brg = '$kd_brg' AND no_aset = '$no_aset'";
            $result = $this->db->query($query);
            return $result->row();
        }
	
	function get_SelectedDataPrint($ids){
		$dataasset = array();
		$idx = array();
		$idx = explode("||", urldecode($ids));
		$q = "$this->selectColumn FROM $this->viewTable as t WHERE t.kd_lokasi = '".$idx[0]."' and t.kd_brg = '".$idx[1]."' and t.no_aset = '".$idx[2]."'";
		$query = $this->db->query($q);
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$dataasset[] = $row;
			}
		}
		return $dataasset;
	}
}
?>