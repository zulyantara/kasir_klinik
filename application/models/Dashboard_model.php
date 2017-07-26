<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_data_barang_habis()
    {
        $sql = "SELECT barang_kode, barang_nama, barang_jumlah, barang_limit FROM barang WHERE barang_jumlah=0";
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->result() : FALSE;
    }

    function get_data_barang_limit()
    {
        $sql = "SELECT barang_kode, barang_nama, barang_jumlah, barang_limit FROM barang WHERE barang_jumlah <= barang_limit";
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->result() : FALSE;
    }

    function get_sum_transaksi()
    {
        $sql = "SELECT SUM(td_harga*td_qty) as total_transaksi FROM v_transaksi WHERE DATE(th_insert_date)=CURDATE()";
        // echo $sql;
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->row() : 0;
    }
}
