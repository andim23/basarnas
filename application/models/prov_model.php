<?php
class Prov_Model extends MY_Model {
	function __construct(){
		parent::__construct();
            $this->table = 'tref_provinsi';    
            $this->selectColumn = "SELECT ID_Prov,kode_prov, nama_prov";
	}
	
        function get_AllData($start=null, $limit=null){
            if($start !=null && $limit !=null)
            {
                $query = "$this->selectColumn 
                        FROM $this->table 
                        LIMIT $start, $limit";
            }
            else
            {
                $query = "$this->selectColumn 
                        FROM $this->table
                        ";
            }
            

            return $this->Get_By_Query($query);	
	}
	
}
?>