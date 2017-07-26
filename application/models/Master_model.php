<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /*
     * @param string $table nama table di database
     * @param string $order_by nama field order di table
     */
	function get_all_data($table, $order_by=NULL, $limit = "10", $offset="0")
	{
		if($order_by === NULL)
		{
			$sql = "SELECT * FROM ".$table."  LIMIT ".$limit." OFFSET ".$offset;
		}
		else
		{
			$sql = "SELECT * FROM ".$table." ORDER BY ".$order_by ." LIMIT ".$limit." OFFSET ".$offset;
		}
		// echo $sql;exit;

        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->result() : FALSE;
	}

    /*
     * @param string $table nama table di database
     * @param string $is_delete nama field is_delete di table
     */

	function count_all_data($table, $where = NULL)
	{
		if($where === NULL)
		{
			$sql = "SELECT count(*) AS jml FROM ".$table;
		}
		else
		{
			$sql = "SELECT count(*) AS jml FROM ".$table." WHERE ".$where;
		}

		//echo $sql;exit;

        $qry = $this->db->query($sql);
        return $qry->row();
	}

    /*
     * @param string $table nama table di database
     * @param string $where kondisi field yang ingin dicari
	 * @param string $order_by nama field order di table
	 */
	function get_search_data($table, $where, $order_by = NULL, $limit = "10", $offset="0")
	{
		if($order_by === NULL)
		{
			$sql = "SELECT * FROM ".$table." WHERE ".$where." LIMIT ".$limit." OFFSET ".$offset;
		}
		else
		{
			$sql = "SELECT * FROM ".$table." WHERE ".$where." ORDER BY ".$order_by." LIMIT ".$limit." OFFSET ".$offset;
		}
		//echo $sql;exit;

        $qry = $this->db->query($sql);
        // echo "<pre>";var_dump($qry);echo "</pre>";exit;
        return $qry->num_rows() > 0 ? $qry->result() : FALSE;
	}

    /*
     * @param string $table nama table di database
     * @param array $field nama field di table
     * @param array $data value yang ingin diinsert
	 */
	function insert_data($table, $field=array(), $data=array())
	{
		$sql = "INSERT INTO ".$table." (";
		for($i=0;$i<count($field);$i++)
		{
			$sql .= $field[$i];
		}
		$sql .= ") VALUES (";

		for($i=0;$i<count($data);$i++)
		{
			$sql .= $data[$i];
		}
		$sql .= ")";

		//echo $sql;exit;

		$qry = $this->db->query($sql);
		return $this->db->affected_rows();
	}

    /*
     * @param string $table nama table di database
     * @param string $pk nama field PK di table
     * @param string $id value PK di table
     * @param array $data value yang ingin diinsert
	 */
	function update_data($table, $pk, $id, $data=array())
	{
		$sql = "UPDATE ".$table." SET ";
		foreach($data as $key=>$row)
		{
			$sql .= $key."=".$row;
		}
		$sql .= " WHERE ".$pk."=".$id;

		//echo $sql;exit;

		$qry = $this->db->query($sql);
		return $this->db->affected_rows();
	}

    /*
     * @param string $table nama table di database
     * @param string $field_name nama awalan field di table
     * @param string $field_id nama field id di table
     * @param string $value_id value from form
	 */
	function delete_data($table, $field_id, $value_id)
	{
		$id = is_int($value_id)===TRUE ? $value_id : "'".$value_id."'";
		$user = $this->session->userdata("userId") ? $this->session->userdata("userId") : 0;
		$date = date("Y-m-d H:i:s");

        $sql = "DELETE FROM ".$table." WHERE ".$field_id."=".$id;
		//echo $sql;exit;

		$qry = $this->db->query($sql);
		return $this->db->affected_rows();
	}

	/*
	 * @param string $pk nama field primary_key
	 * @param string $id id value
     * @param string $table nama table di database
     */
	function get_data_by_id($pk, $id, $table)
	{
        $sql = "SELECT * FROM ".$table." WHERE ".$pk."=".$id;

		//echo $sql;exit;

        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->row() : FALSE;
	}

    /*
	 * @param string $pk nama field primary_key
	 * @param string $id id value
     * @param string $table nama table di database
     */
	function get_data_join_by_id($pk, $id, $table, $type_join, $table_join, $on_join)
	{
        $sql = "SELECT * FROM ".$table." ".$type_join." ".$table_join." ON ".$on_join." WHERE ".$pk."=".$id;

		//echo $sql;exit;

        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->row() : FALSE;
	}

    function get_data_row($table, $where, $order, $limit=10, $offset=0)
    {
        $sql = "SELECT * FROM ".$table." WHERE ".$where." ORDER BY ".$order." LIMIT ".$limit." OFFSET ".$offset;
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->row() : FALSE;
    }

    function get_data_result($table, $where)
    {
        $sql = "SELECT * FROM ".$table." WHERE ".$where;
        // echo $sql;
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->result() : FALSE;
    }

	/*
     * @param string $table nama table di database
     * @param string $parameter nama field cek duplikat di table
	 * Mengecek duplikasi data
     */
	function check_duplicate($table, $parameter)
    {
		$sql = "SELECT COUNT(*) as jml FROM ".$table." WHERE ".$parameter;

		//echo $sql;exit;

        $qry = $this->db->query($sql);
        return $qry->row();
	}

    function insert_th($data)
    {
        $th_kode = $this->db->escape($data["transaksi_kode"]);
        $th_customer = $this->db->escape($data["transaksi_customer"]);
        $th_insert_date = $this->db->escape(date("Y-m-d H:i:s"));
        $th_insert_user = $this->session->userdata("isLoggedIn") === TRUE ? $this->session->userdata("userId") : 0;

        $sql = "INSERT INTO transaksi_head(th_kode,th_customer,th_insert_date,th_insert_user) VALUES(".$th_kode.",".$th_customer.",".$th_insert_date.",".$th_insert_user.")";
        $qry = $this->db->query($sql);

        return $this->db->affected_rows();
    }

    function insert_tdt($data)
    {
        $tdt_kode = $this->db->escape($data["kode_transaksi"]);
        $tdt_kode_akun = $data["kode_akun"];
        $tdt_pasien = $data["pasien"];
        $tdt_customer = $this->db->escape($data["customer"]);
        $tdt_dokter = $this->db->escape($data['dokter']);
        $tdt_jasa = $data["jasa"];
        $tdt_barang = $data["barang"];
        $tdt_qty = $data["qty"];
        $tdt_harga = $data["harga"];
        $tdt_insert_date = $this->db->escape(date("Y-m-d H:i:s"));
        $tdt_insert_user = $this->session->userdata("isLoggedIn") === TRUE ? $this->session->userdata("userId") : 0;

        if ($tdt_barang !== "0")
        {
            //cek stok barang
            $sql_cek_stok = "SELECT barang_jumlah FROM barang WHERE barang_id=".$tdt_barang;
            $qry_cek_stok = $this->db->query($sql_cek_stok);
            $row_cek_stok = $qry_cek_stok->row();
            if ($row_cek_stok->barang_jumlah <= "0")
            {
                ?>
                <script>
                alert("Stok tidak cukup");
                window.history.back();
                </script>
                <?php
                exit();
            }

            // cek jika qty melebihi stok maka exit
            $cek_qty = $row_cek_stok->barang_jumlah - $tdt_qty;
            if ($cek_qty < 0)
            {
                ?>
                <script>
                alert("Qty melebihi stok");
                window.history.back();
                </script>
                <?php
                exit;
            }
        }

        // cek barang yang sama
        if ($tdt_jasa === "0")
        {
            $sql_cek_kembar = "SELECT count(*) as jml FROM transaksi_detail_temp WHERE tdt_barang=".$tdt_barang;
        }
        elseif ($tdt_barang === "0")
        {
            $sql_cek_kembar = "SELECT count(*) as jml FROM transaksi_detail_temp WHERE tdt_jasa=".$tdt_jasa;
        }
        // echo $sql_cek_kembar;exit;
        $qry_cek_kembar = $this->db->query($sql_cek_kembar);
        $row_cek_kembar = $qry_cek_kembar->row();
        $jml_cek_kembar = $row_cek_kembar->jml;
        //echo $jml_cek_kembar;exit;

        if ($tdt_barang !== "0")
        {
            // ngambil quantity yang di database
            $sql_get_qty = "SELECT tdt_qty FROM transaksi_detail_temp WHERE tdt_barang=".$tdt_barang;
            $qry_get_qty = $this->db->query($sql_get_qty);
            $row_get_qty = $qry_get_qty->row() > 0 ? $qry_get_qty->row() : FALSE;
            $qty_belanja = $row_get_qty === FALSE ? 0 : $row_get_qty->tdt_qty;
        }

        if($jml_cek_kembar === "0")
        {
            $sql = "INSERT INTO transaksi_detail_temp(tdt_kode_transaksi,tdt_kode_akun, tdt_pasien,tdt_customer,tdt_dokter,tdt_jasa,tdt_barang,tdt_qty,tdt_harga,tdt_insert_date,tdt_insert_user) VALUES(".$tdt_kode.",".$tdt_kode_akun.",".$tdt_pasien.",".$tdt_customer.",".$tdt_dokter.",".$tdt_jasa.",".$tdt_barang.",".$tdt_qty.",".$tdt_harga.",".$tdt_insert_date.",".$tdt_insert_user.")";
        }
        else
        {
            $last_qty = $qty_belanja + $tdt_qty;
            $sql = "UPDATE transaksi_detail_temp SET tdt_qty=".$last_qty." WHERE tdt_barang=".$tdt_barang;
        }
        //echo $sql;exit;
        $qry = $this->db->query($sql);
        return $this->db->affected_rows();
    }

    function get_harga($table,$id)
    {
        if ($table === "jasa")
        {
            $sql = "SELECT jasa_harga FROM jasa WHERE jasa_id=".$id;
            $qry = $this->db->query($sql);
            $row_jasa = $qry->row();
            return $row_jasa->jasa_harga;
        }
        elseif ($table = "barang")
        {
            $sql = "SELECT barang_harga FROM barang WHERE barang_id=".$id;
            $qry = $this->db->query($sql);
            $row_barang = $qry->row();
            return $row_barang->barang_harga;
        }
    }

    function get_data_tdt($kode)
    {
        $sql = "SELECT * FROM transaksi_detail_temp LEFT JOIN barang ON tdt_barang=barang_id LEFT JOIN jasa ON tdt_jasa=jasa_id WHERE tdt_kode_transaksi='".$kode."'";
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->result() : FALSE;
    }

    function get_row_tdt($id)
    {
        $sql = "SELECT * FROM transaksi_detail_temp WHERE tdt_id=".$id;
        // echo $sql;exit;
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->row() : FALSE;
    }

    function simpan_transaksi_detail($kode_transaksi)
    {
        //nyimpen transaksi_head
        $sql_head = "INSERT INTO transaksi_head(th_kode, th_pasien, th_customer, th_dokter, th_kode_akuntansi, th_insert_date, th_insert_user) SELECT DISTINCT tdt_kode_transaksi, tdt_pasien, tdt_customer, tdt_dokter, tdt_kode_akun, '".date('Y-m-d H:i:s')."', ".$this->session->userdata('userId')." FROM transaksi_detail_temp WHERE tdt_kode_transaksi='".$kode_transaksi."'";
        //echo $sql_head;exit;
        $qry_head = $this->db->query($sql_head);

        //nyimpen transaksi_detail
        $sql_detail = "INSERT INTO transaksi_detail(td_head, td_jasa, td_barang, td_qty, td_harga, td_discount,td_insert_date, td_insert_user, td_update_user) SELECT tdt_kode_transaksi, tdt_jasa, tdt_barang, tdt_qty, tdt_harga, tdt_discount, '".date('Y-m-d H:i:s')."', tdt_insert_user, tdt_update_user FROM transaksi_detail_temp WHERE tdt_kode_transaksi='".$kode_transaksi."'";
        $qry_detail = $this->db->query($sql_detail);

        //nyimpen biaya administrasi
        // $sql_adm = "INSERT INTO transaksi_detail(td_head, td_jasa, td_qty, td_harga, td_insert_date, td_insert_user) VALUES('".$kode_transaksi."',0,1,10000,'".date('Y-m-d')."',".$this->session->userdata('userId').")";
        // $qry_adm = $this->db->query($sql_adm);

        //kurangin stok barang_harga
        $sql_barang = "UPDATE barang INNER JOIN transaksi_detail_temp ON barang_id=tdt_barang SET barang_jumlah=(barang_jumlah - tdt_qty)";
        $qry_barang = $this->db->query($sql_barang);

        $sql_truncate = "TRUNCATE transaksi_detail_temp";
        $qry_truncate = $this->db->query($sql_truncate);
    }

    function cek_tdt()
    {
        $sql = "SELECT tdt_kode_transaksi, tdt_pasien, tdt_customer, tdt_dokter, tdt_kode_akun FROM transaksi_detail_temp";
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->row() : FALSE;
    }

    function get_pasien($id)
    {
        $sql = "SELECT pasien_nama, tdt_customer FROM transaksi_detail_temp LEFT JOIN pasien ON tdt_pasien=pasien_id WHERE pasien_id=".$id;
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->row() : FALSE;
    }

    function update_tdt($data)
    {
        $id = $data["tdt_id"];
        $jasa = $data["tdt_jasa"];
        $barang = $data["tdt_barang"];
        $qty = $data["tdt_qty"];

        $sql = "UPDATE transaksi_detail_temp SET tdt_jasa=".$jasa.", tdt_barang=".$barang.", tdt_qty=".$qty.", tdt_update_user=".$this->session->userdata("userId")." WHERE tdt_id=".$id;
        // echo $sql;exit;
        $qry = $this->db->query($sql);
        return $this->db->affected_rows();
    }

    function delete_tdt($id)
    {
        $sql = "DELETE FROM transaksi_detail_temp WHERE tdt_id=".$id;
        $qry = $this->db->query($sql);
    }

    function update_stok_barang($barang, $qty)
    {
        $sql = "UPDATE barang SET barang_jumlah=barang_jumlah-".$qty." WHERE barang_id=".$barang;
        // echo $sql;exit;
        $qry = $this->db->query($sql);
        return $this->db->affected_rows();
    }

    function get_transaksi_head($kode)
    {
        $sql = "SELECT * FROM v_transaksi WHERE th_kode='".$kode."'";
        // echo $sql;exit;
        $qry = $this->db->query($sql);
        // echo "<pre>";var_dump($qry);echo "</pre>";exit;
        return $qry->num_rows() > 0 ? $qry->row() : FALSE;
    }

    function get_last_kode($table, $field, $where, $order)
    {
        $sql = "SELECT ".$field." FROM ".$table." WHERE ".$where." ORDER BY ".$order." DESC";
        // echo $sql;exit;
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->row() : FALSE;
    }

    function get_pd($id)
    {
        $sql = "SELECT * FROM pembelian_detail LEFT JOIN barang ON pd_barang=barang_id WHERE pd_head=".$id;
        // echo $sql;exit;
        $qry = $this->db->query($sql);
        return $qry->num_rows() > 0 ? $qry->result() : FALSE;
    }

    function delete_td($id)
    {
        // ngambil th_kode
        $row_th = $this->get_data_by_id('th_id',$id,'transaksi_head');
        $kode_th = $row_th->th_kode;

        // ngembaliin stok barang
        $sql_update_barang = 'UPDATE barang LEFT JOIN transaksi_detail ON barang_id=td_barang SET barang_jumlah=barang_jumlah+td_qty WHERE td_head='.$this->db->escape($kode_th);
        // echo $sql_update_barang;exit;
        $qry_update_barang = $this->db->query($sql_update_barang);

        // delete transaksi detail dan transaksi head
        $sql_td = "DELETE FROM transaksi_detail WHERE td_head='".$kode_th."'";
        $qry_td = $this->db->query($sql_td);
        $sql_th = "DELETE FROM transaksi_head WHERE th_id=".$id;
        $qry_th = $this->db->query($sql_th);
    }
}
