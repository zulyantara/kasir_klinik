<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Laba_rugi_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_jenis_akun()
    {
        $sql = "SELECT ja_id, ja_kode, ja_ket FROM jenis_akun WHERE SUBSTR(ja_kode,1,2)='IS' AND SUBSTR(ja_kode,-1)<>'0' OR ja_kode='IS-10'";
        //echo $sql;
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->result() : FALSE;
    }
}
