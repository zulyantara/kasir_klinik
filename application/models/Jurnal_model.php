<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_data_jurnal($data)
    {
        $tgl_1 = $this->db->escape($data["tgl_1"]);
        $tgl_2 = $this->db->escape($data["tgl_2"]);

        $sql = "SELECT DATE(th_insert_date) AS tgl, 'Penerimaan Jasa Pengobatan' AS transaksi, SUM(td_qty*td_harga) AS harga, ka_kode, ka_akun FROM v_transaksi WHERE DATE(th_insert_date) BETWEEN ".$tgl_1." AND ".$tgl_2." GROUP BY DATE(th_insert_date)";
        $sql .= " UNION ";
        $sql .= "SELECT DATE(pengeluaran_insert_date) as tgl, pengeluaran_ket as transaksi, pengeluaran_harga as harga, ka_kode, ka_akun FROM v_pengeluaran WHERE DATE(pengeluaran_insert_date) BETWEEN ".$tgl_1." AND ".$tgl_2." GROUP BY pengeluaran_insert_date";
        $sql .= " UNION ";
        $sql .= "SELECT DATE(payroll_insert_date) AS tgl, CONCAT(ka_akun,' ',staff_nama) as transaksi, staff_gaji as harga, ka_kode, ka_akun FROM v_payroll WHERE DATE(payroll_insert_date) BETWEEN ".$tgl_1." AND ".$tgl_2;
        $sql .= " UNION ";
        $sql .= "SELECT DATE(ph_insert_date) AS tgl, ka_akun as transaksi, SUM(pd_harga_beli) as harga, ka_kode, ka_akun FROM v_pembelian WHERE DATE(pd_insert_date) BETWEEN ".$tgl_1." AND ".$tgl_2;
        // echo $sql;

        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->result() : FALSE;
    }

    function get_data_transaksi($data)
    {
        $tgl_1 = $this->db->escape($data["tgl_1"]);
        $tgl_2 = $this->db->escape($data["tgl_2"]);

        $sql = "SELECT * FROM v_transaksi WHERE DATE(th_insert_date) BETWEEN ".$tgl_1." AND ".$tgl_2;
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->result() : FALSE;
    }

    function get_data_pengeluaran($data)
    {
        $tgl_1 = $this->db->escape($data["tgl_1"]);
        $tgl_2 = $this->db->escape($data["tgl_2"]);

        $sql = "SELECT * FROM v_pengeluaran WHERE DATE(pengeluaran_insert_date) BETWEEN ".$tgl_1." AND ".$tgl_2;
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->result() : FALSE;
    }

    function get_data_payroll($data)
    {
        $tgl_1 = $this->db->escape($data["tgl_1"]);
        $tgl_2 = $this->db->escape($data["tgl_2"]);

        $sql = "SELECT * FROM v_payroll WHERE DATE(payroll_insert_date) BETWEEN ".$tgl_1." AND ".$tgl_2;
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->result() : FALSE;
    }
}
