<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_penjualan_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_data_transaksi($data)
    {
        $bulan = $this->db->escape($data["bulan"]);

        $sql = "SELECT * FROM v_transaksi WHERE MONTH(th_insert_date) = ".$bulan;
        //echo $sql;

        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->result() : FALSE;
    }
}
