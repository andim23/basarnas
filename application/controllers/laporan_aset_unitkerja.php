<?php
class laporan_aset_unitkerja extends MY_Controller {

	function __construct() {
		parent::__construct();
 		if ($this->my_usession->logged_in == FALSE){
 			echo "window.location = '".base_url()."user/index';";
 			exit;
                }
//		$this->load->model('Asset_Alatbesar_Model','',TRUE);
//		$this->model = $this->Asset_Alatbesar_Model;		
	}
	
        public function index()
        {
            if($this->input->post("id_open")){
			$data['jsscript'] = TRUE;
			$this->load->view('laporan/laporan_aset_unitkerja_view',$data);
		}else{
			$this->load->view('laporan/laporan_aset_unitkerja_view');
		}
	}
        
        public function getLaporanChart()
        {
            $kd_lokasi = null;
            $kd_unor = null;
            $tahun = null;
            $golongan = "";
            $bidang = "";
            $kelompok = "";
            $sub_kelompok = "";
            
            if(isset($_POST['kd_lokasi']))
            {
                $kd_lokasi = $_POST['kd_lokasi'];
            }
            if(isset($_POST['kd_unor']))
            {
                $kd_unor = $_POST['kd_unor'];
            }
            if(isset($_POST['tahun']))
            {
                $tahun = $_POST['tahun'];
            }
             if(isset($_POST['golongan']))
            {
                $golongan = $_POST['golongan'];
            }
            
            if(isset($_POST['bidang']))
            {
                $bidang = $_POST['bidang'];
            }
            
            if(isset($_POST['kelompok']))
            {
                $kelompok = $_POST['kelompok'];
            }
            
            if(isset($_POST['sub_kelompok']))
            {
                $sub_kelompok = $_POST['sub_kelompok'];
            }
            
            
            $query_barang = $this->getQueryBarang($golongan, $bidang, $kelompok, $sub_kelompok);
            
            if($kd_unor !=null && $kd_unor != '')
            {
                if($kd_lokasi !=null && $tahun !=null)
                {
                    $query = $this->db->query( "select 
                    (select (sum(rph_aset)) from asset_alatbesar as t 
                    LEFT JOIN ext_asset_alatbesar AS b ON t.kd_lokasi = b.kd_lokasi AND t.kd_brg = b.kd_brg AND t.no_aset = b.no_aset
                    where t.kd_lokasi='".$kd_lokasi."' and YEAR(tgl_buku) ='".$tahun."' and kode_unor ='".$kd_unor."' $query_barang) 
                    as 'Peralatan', 
                    (select (sum(rph_aset)) from asset_angkutan as t LEFT JOIN ext_asset_angkutan AS b ON t.kd_lokasi = b.kd_lokasi AND t.kd_brg = b.kd_brg AND t.no_aset = b.no_aset
                    where t.kd_lokasi='".$kd_lokasi."' and YEAR(tgl_buku) ='".$tahun."' and kode_unor ='".$kd_unor."' $query_barang) 
                    as 'Angkutan',
                    (select (sum(rph_aset)) from asset_bangunan as t LEFT JOIN ext_asset_bangunan AS b ON t.kd_lokasi = b.kd_lokasi AND t.kd_brg = b.kd_brg AND t.no_aset = b.no_aset
                    where t.kd_lokasi='".$kd_lokasi."' and YEAR(tgl_buku) ='".$tahun."' and kode_unor ='".$kd_unor."' $query_barang) 
                    as 'Bangunan',
                    (select (sum(rph_aset)) from asset_perairan as t LEFT JOIN ext_asset_perairan AS b ON t.kd_lokasi = b.kd_lokasi AND t.kd_brg = b.kd_brg AND t.no_aset = b.no_aset
                    where t.kd_lokasi='".$kd_lokasi."' and YEAR(tgl_buku) ='".$tahun."' and kode_unor ='".$kd_unor."' $query_barang) 
                    as 'Perairan',
                    (select (sum(rph_aset)) from asset_senjata as t LEFT JOIN ext_asset_senjata AS b ON t.kd_lokasi = b.kd_lokasi AND t.kd_brg = b.kd_brg AND t.no_aset = b.no_aset
                    where t.kd_lokasi='".$kd_lokasi."' and YEAR(tgl_buku) ='".$tahun."' and kode_unor ='".$kd_unor."' $query_barang) 
                    as 'Senjata',
                    (select (sum(rph_aset)) from asset_tanah as t LEFT JOIN ext_asset_tanah AS b ON t.kd_lokasi = b.kd_lokasi AND t.kd_brg = b.kd_brg AND t.no_aset = b.no_aset
                    where t.kd_lokasi='".$kd_lokasi."' and YEAR(tgl_buku) ='".$tahun."' and kode_unor ='".$kd_unor."' $query_barang) 
                    as 'Tanah',
                    (select (sum(rph_aset)) from ext_asset_dil as t 
                    where t.kd_lokasi='".$kd_lokasi."' and YEAR(tgl_buku) ='".$tahun."' and kode_unor ='".$kd_unor."' $query_barang) 
                    as 'Luar',
                    (select (sum(rph_aset)) from ext_asset_ruang as t 
                    where t.kd_lokasi='".$kd_lokasi."' and YEAR(tgl_buku) ='".$tahun."' and kode_unor ='".$kd_unor."' $query_barang) 
                    as 'Ruang'");
                    $result = $query->row_array();
                    $result_array = array();
                    foreach($result as $key=>$value)
                    {
                        $temp_array["nama"] = $key;
                        $temp_array["totalAset"] = $value;
                        array_push($result_array,$temp_array);
                    }

                    $data = $result_array;
                    $dataSend['results'] = $data;
                    echo json_encode($dataSend);
                }
            }
            else
            {
                if($kd_lokasi !=null && $tahun !=null)
                {
                    $query = $this->db->query( "select  
                    (select (sum(rph_aset)) from asset_angkutan as t where kd_lokasi='".$kd_lokasi."' and YEAR(tgl_buku) ='".$tahun."' $query_barang) as 'Angkutan',
                    (select (sum(rph_aset)) from asset_bangunan as t where kd_lokasi='".$kd_lokasi."' and YEAR(tgl_buku) ='".$tahun."' $query_barang) as 'Bangunan',
                    (select (sum(rph_aset)) from ext_asset_dil as t where kd_lokasi='".$kd_lokasi."' and YEAR(tgl_buku) ='".$tahun."' $query_barang) as 'Luar',
                    (select (sum(rph_aset)) from asset_perairan as t where kd_lokasi='".$kd_lokasi."' and YEAR(tgl_buku) ='".$tahun."' $query_barang) as 'Perairan',
                    (select (sum(rph_aset)) from asset_alatbesar as t where kd_lokasi='".$kd_lokasi."' and YEAR(tgl_buku) ='".$tahun."' $query_barang) as 'Peralatan',
                    (select (sum(rph_aset)) from ext_asset_ruang as t where kd_lokasi='".$kd_lokasi."' and YEAR(tgl_buku) ='".$tahun."' $query_barang) as 'Ruang',
                    (select (sum(rph_aset)) from asset_senjata as t where kd_lokasi='".$kd_lokasi."' and YEAR(tgl_buku) ='".$tahun."' $query_barang) as 'Senjata',
                    (select (sum(rph_aset)) from asset_tanah as t where kd_lokasi='".$kd_lokasi."' and YEAR(tgl_buku) ='".$tahun."' $query_barang) as 'Tanah'");
                    $result = $query->row_array();
                    $result_array = array();
                    foreach($result as $key=>$value)
                    {
                        $temp_array["nama"] = $key;
                        $temp_array["totalAset"] = $value;
                        array_push($result_array,$temp_array);
                    }

                    $data = $result_array;
                    $dataSend['results'] = $data;
                    echo json_encode($dataSend);
                }
            }
        }
        
        public function getLaporanGrid()
        {
            $kd_lokasi = null;
            $kd_unor = null;
            $tahun = null;
            $golongan = "";
            $bidang = "";
            $kelompok = "";
            $sub_kelompok = "";
            $start = $_POST['start'];
            $limit = $_POST['limit'];
            
            if(isset($_POST['kd_lokasi']))
            {
                $kd_lokasi = $_POST['kd_lokasi'];
            }
            if(isset($_POST['kd_unor']))
            {
                $kd_unor = $_POST['kd_unor'];
            }
            if(isset($_POST['tahun']))
            {
                $tahun = $_POST['tahun'];
            }
            
            if(isset($_POST['golongan']))
            {
                $golongan = $_POST['golongan'];
            }
            
            if(isset($_POST['bidang']))
            {
                $bidang = $_POST['bidang'];
            }
            
            if(isset($_POST['kelompok']))
            {
                $kelompok = $_POST['kelompok'];
            }
            
            if(isset($_POST['sub_kelompok']))
            {
                $sub_kelompok = $_POST['sub_kelompok'];
            }
            
            $query_barang = $this->getQueryBarang($golongan, $bidang, $kelompok, $sub_kelompok);

            /*tidak ada tgl: dil, ruang
             * tidak ada kode_unor: perlengkapan */
            if($kd_unor !=null || $kd_unor != '')
            {
                if($kd_lokasi !=null && $tahun !=null)
                {
                    
//                                              select '-' as kode_unor,kd_brg,kd_lokasi,no_aset,'-','-',kondisi,'Perlengkapan' as kategori_aset from asset_perlengkapan
//                          where kd_lokasi = '".$kd_lokasi."' and YEAR(tanggal_perolehan) = '".$tahun."' 
//                          UNION
                        $query = "select kode_unor,kd_lokasi,no_aset,kd_brg,type,merk,kondisi,kategori_aset,rph_aset from
                          (
                          select b.kode_unor,t.kd_lokasi,t.no_aset,t.kd_brg,type,merk,kondisi, 'Peralatan' as kategori_aset,t.rph_aset from asset_alatbesar as t
                          LEFT JOIN ext_asset_alatbesar AS b ON t.kd_lokasi = b.kd_lokasi AND t.kd_brg = b.kd_brg AND t.no_aset = b.no_aset
                          where t.kd_lokasi = '".$kd_lokasi."' and YEAR(t.tgl_buku) = '".$tahun."' and b.kode_unor = '".$kd_unor."' $query_barang
                          UNION ALL
                          select b.kode_unor,t.kd_lokasi,t.no_aset,t.kd_brg,type,merk,kondisi, 'Angkutan' as kategori_aset,t.rph_aset from asset_angkutan as t
                          LEFT JOIN ext_asset_angkutan AS b ON t.kd_lokasi = b.kd_lokasi AND t.kd_brg = b.kd_brg AND t.no_aset = b.no_aset
                          where t.kd_lokasi = '".$kd_lokasi."' and YEAR(t.tgl_buku) = '".$tahun."' and b.kode_unor = '".$kd_unor."' $query_barang
                          UNION ALL
                          select b.kode_unor,t.kd_lokasi,t.no_aset,t.kd_brg,type,'-','-','Bangunan' as kategori_aset,t.rph_aset from asset_bangunan as t
                          LEFT JOIN ext_asset_angkutan AS b ON t.kd_lokasi = b.kd_lokasi AND t.kd_brg = b.kd_brg AND t.no_aset = b.no_aset
                          where t.kd_lokasi = '".$kd_lokasi."' and YEAR(t.tgl_buku) = '".$tahun."' and b.kode_unor = '".$kd_unor."' $query_barang
                          UNION ALL
                          select b.kode_unor,t.kd_lokasi,t.no_aset,t.kd_brg,type,merk,kondisi,'Senjata' as kategori_aset,t.rph_aset from asset_senjata as t
                          LEFT JOIN ext_asset_senjata AS b ON t.kd_lokasi = b.kd_lokasi AND t.kd_brg = b.kd_brg AND t.no_aset = b.no_aset
                          where t.kd_lokasi = '".$kd_lokasi."' and YEAR(t.tgl_buku) = '".$tahun."' and b.kode_unor = '".$kd_unor."' $query_barang
                          UNION ALL
                          select b.kode_unor,t.kd_lokasi,t.no_aset,t.kd_brg,'-','-','-','DIL' as kategori_aset,t.rph_aset from ext_asset_dil as t
                          LEFT JOIN ext_asset_dil AS b ON t.kd_lokasi = b.kd_lokasi AND t.kd_brg = b.kd_brg AND t.no_aset = b.no_aset
                          where t.kd_lokasi = '".$kd_lokasi."' and b.kode_unor = '".$kd_unor."' $query_barang
                          UNION ALL
                          select b.kode_unor,t.kd_lokasi,t.no_aset,t.kd_brg,'-','-','-','Perairan' as kategori_aset,t.rph_aset from asset_perairan as t
                          LEFT JOIN ext_asset_perairan AS b ON t.kd_lokasi = b.kd_lokasi AND t.kd_brg = b.kd_brg AND t.no_aset = b.no_aset
                          where t.kd_lokasi = '".$kd_lokasi."' and YEAR(t.tgl_buku) = '".$tahun."' and b.kode_unor = '".$kd_unor."' $query_barang
                          UNION ALL
                          select b.kode_unor,t.kd_lokasi,t.no_aset,t.kd_brg,'-','-','-','Ruang' as kategori_aset,t.rph_aset from ext_asset_ruang as t
                          LEFT JOIN ext_asset_ruang AS b ON t.kd_lokasi = b.kd_lokasi AND t.kd_brg = b.kd_brg AND t.no_aset = b.no_aset
                          where t.kd_lokasi = '".$kd_lokasi."' and b.kode_unor = '".$kd_unor."' $query_barang
                          UNION ALL
                          select b.kode_unor,t.kd_lokasi,t.no_aset,t.kd_brg,'-','-','-','Tanah' as kategori_aset,t.rph_aset from asset_tanah as t
                          LEFT JOIN ext_asset_tanah AS b ON t.kd_lokasi = b.kd_lokasi AND t.kd_brg = b.kd_brg AND t.no_aset = b.no_aset
                          where t.kd_lokasi = '".$kd_lokasi."' and YEAR(t.tgl_buku) = '".$tahun."' and b.kode_unor = '".$kd_unor."' $query_barang
                          ) as result
                          

                          ";

                    $r = $this->db->query($query);
                    $data = array();
                    $totalRows = $r->num_rows(); 
                    if ($totalRows > 0)
                    {
                        foreach ($r->result() as $obj)
                        {
                            $data[] = $obj;
                        }  
                    }

                    $dataSend['results'] = $data;
//                    $dataSend['total'] = $totalRows;
                    echo json_encode($dataSend);
                }
                
            }
            else
            {
                if($kd_lokasi !=null && $tahun !=null)
                {
//                    select kd_brg,kd_lokasi,no_aset,'-','-',kondisi,'Perlengkapan' as kategori_aset,rph_aset from asset_perlengkapan
//                          where kd_lokasi = '".$kd_lokasi."' and YEAR(tanggal_perolehan) = '".$tahun."'
//                          UNION
                    
                        $query = "select kd_lokasi,no_aset,kd_brg,type,merk,kondisi,kategori_aset,rph_aset from
                          (
                          select t.kd_lokasi,t.no_aset,t.kd_brg,type,merk,kondisi, 'Peralatan' as kategori_aset,rph_aset from asset_alatbesar as t
                          where t.kd_lokasi = '".$kd_lokasi."' and YEAR(t.tgl_buku) = '".$tahun."' $query_barang
                          UNION ALL
                          select t.kd_lokasi,t.no_aset,t.kd_brg,type,merk,kondisi, 'Angkutan' as kategori_aset,rph_aset from asset_angkutan as t
                          where t.kd_lokasi = '".$kd_lokasi."' and YEAR(t.tgl_buku) = '".$tahun."' $query_barang
                          UNION ALL
                          select t.kd_lokasi,t.no_aset,t.kd_brg,type,'-','-','Bangunan' as kategori_aset,rph_aset from asset_bangunan as t
                          where t.kd_lokasi = '".$kd_lokasi."' and YEAR(t.tgl_buku) = '".$tahun."' $query_barang
                          UNION ALL
                          select t.kd_lokasi,t.no_aset,t.kd_brg,type,merk,kondisi,'Senjata' as kategori_aset,rph_aset from asset_senjata as t
                          where t.kd_lokasi = '".$kd_lokasi."' and YEAR(t.tgl_buku) = '".$tahun."' $query_barang
                          UNION ALL
                          select t.kd_lokasi,t.no_aset,t.kd_brg,'-','-','-','DIL' as kategori_aset,rph_aset from ext_asset_dil as t
                          where t.kd_lokasi = '".$kd_lokasi."' and YEAR(t.tgl_buku) = '".$tahun."' $query_barang
                          UNION ALL
                          select t.kd_lokasi,t.no_aset,t.kd_brg,'-','-','-','Perairan' as kategori_aset,rph_aset from asset_perairan as t
                          where t.kd_lokasi = '".$kd_lokasi."' and YEAR(t.tgl_buku) = '".$tahun."' $query_barang
                          UNION ALL
                          select t.kd_lokasi,t.no_aset,t.kd_brg,'-','-','-','Ruang' as kategori_aset,rph_aset from ext_asset_ruang as t
                          where t.kd_lokasi = '".$kd_lokasi."' and YEAR(t.tgl_buku) = '".$tahun."' $query_barang
                          UNION ALL
                          select t.kd_lokasi,t.no_aset,t.kd_brg,'-','-','-','Tanah' as kategori_aset,rph_aset from asset_tanah as t
                          where t.kd_lokasi = '".$kd_lokasi."' and YEAR(t.tgl_buku) = '".$tahun."' $query_barang
                          ) as result
                          

                          ";

                    $r = $this->db->query($query);
                    $data = array();
                    $totalRows = $r->num_rows(); 
                    if ($totalRows > 0)
                    {
                        foreach ($r->result() as $obj)
                        {
                            $data[] = $obj;
                        }  
                    }

                    $dataSend['results'] = $data;
//                    $dataSend['total'] = $totalRows;
                    echo json_encode($dataSend);
                }
            }
        }
        
        
        private function getQueryBarang($golongan,$bidang,$kelompok,$sub_kelompok)
        {
            $query_barang = "";
            if($golongan !=null && $golongan!= "")
            {
                $combined_kode = $golongan.$bidang.$kelompok.$sub_kelompok;
                $query_barang = "AND t.kd_brg LIKE '$combined_kode%'";
            }
            
            return $query_barang;
        }
}
?>