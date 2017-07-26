<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_data_pdt($id, $res = TRUE)
    {
        $sql = "SELECT pembelian_detail_temp.*, barang_nama FROM pembelian_detail_temp LEFT JOIN barang ON pdt_barang=barang_id WHERE pdt_head='".$id."'";
        $qry = $this->db->query($sql);
        if ($res === TRUE)
        {
            return $qry->num_rows() > 0 ? $qry->result() : FALSE;
        }
        else
        {
            return $qry->num_rows() > 0 ? $qry->row() : FALSE;
        }
    }

    function insert_pdt($data)
    {
        // var_dump($data);exit;
        $pdt_head = $data["head"];
        $pdt_nama = $this->db->escape(strtoupper($data["nama"]));
        $pdt_barang = $data["barang"];
        $pdt_qty = $data["qty"];
        $pdt_harga = $data["harga"];
        $pdt_date = $this->db->escape(date("Y-m-d H:i:s"));
        $pdt_user = $this->session->userdata("userId");
        $harga_satuan = $pdt_harga/$pdt_qty;

        $sql = "INSERT INTO pembelian_detail_temp(pdt_head, pdt_nama, pdt_barang, pdt_harga_beli, pdt_qty, pdt_insert_date, pdt_insert_user) VALUES($pdt_head,$pdt_nama,$pdt_barang,$pdt_harga,$pdt_qty,$pdt_date,$pdt_user)";
        // echo $sql;exit;
        $qry = $this->db->query($sql);
        return $this->db->affected_rows();
    }

    function cek_pdt()
    {
        $sql = "SELECT pdt_head, pdt_nama FROM pembelian_detail_temp";
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->row() : FALSE;
    }

    function get_last_id_ph()
    {
        $sql = "SELECT MAX(ph_id) as ph_id FROM pembelian_head ORDER BY ph_id DESC";
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->row() : 1;
    }

    function simpan_pembelian_detail($id)
    {
        //nyimpen pembelian_head
        $sql_head = "INSERT INTO pembelian_head(ph_id, ph_nama, ph_kode_akun, ph_insert_date, ph_insert_user) SELECT DISTINCT pdt_head, pdt_nama, 42, DATE(pdt_insert_date), pdt_insert_user FROM pembelian_detail_temp WHERE pdt_head=".$id;
        // echo $sql_head;exit;
        $qry_head = $this->db->query($sql_head);

        //nyimpen pembelian_detail
        $sql_detail = "INSERT INTO pembelian_detail(pd_head, pd_barang, pd_qty, pd_harga_beli, pd_insert_date, pd_insert_user) SELECT pdt_head, pdt_barang, pdt_qty, pdt_harga_beli, pdt_insert_date, pdt_insert_user FROM pembelian_detail_temp WHERE pdt_head=".$id;
        $qry_detail = $this->db->query($sql_detail);

        //kurangin stok barang_harga
        $sql_barang = "UPDATE barang INNER JOIN pembelian_detail_temp ON barang_id=pdt_barang SET barang_jumlah=(barang_jumlah + pdt_qty), barang_harga_beli=pdt_harga_beli/pdt_qty";
        $qry_barang = $this->db->query($sql_barang);

        // nyimpen ke pengeluaran
        $sql_belanja = "INSERT INTO pengeluaran (pengeluaran_kode_akuntansi,pengeluaran_ket,pengeluaran_qty, pengeluaran_harga, pengeluaran_insert_date, pengeluaran_insert_user) SELECT 42, barang_nama, pdt_qty, pdt_harga_beli, pdt_insert_date, pdt_insert_user FROM pembelian_detail_temp LEFT JOIN barang ON pdt_barang=barang_id";

        $sql_truncate = "TRUNCATE pembelian_detail_temp";
        $qry_truncate = $this->db->query($sql_truncate);
    }

    function insert_pd($data)
    {
        $head = $data["head"];
        $barang = $data["barang"];
        $qty = $data["qty"];
        $harga = $data["harga"];
        $date = date('Y-m-d H:i:s');
        $user = $this->session->userdata('userId');

        $sql = "INSERT INTO pembelian_detail (pd_head,pd_barang,pd_qty,pd_harga_beli,pd_insert_date,pd_insert_user) VALUES($head,$barang,$qty,$harga,'$date',$user)";
        $qry = $this->db->query($sql);
    }

    function delete_pd($id)
    {
        $sql = "DELETE FROM pembelian_detail WHERE pd_id=".$id;
        $qry = $this->db->query($sql);
    }

    function delete_pembelian($id)
    {
        $sql_dph = "DELETE FROM pembelian_head WHERE ph_id=".$id;
        $sql_dpd = "DELETE FROM pembelian_detail WHERE pd_head=".$id;
        $qry_dph = $this->db->query($sql_dph);
        $qry_dpd = $this->db->query($sql_dpd);
    }
}
