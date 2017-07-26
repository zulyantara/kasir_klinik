<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_paket extends CI_Controller
{
    /*
     * @author Zulyantara <zulyantara@gmail.com>
     */

    var $modul = "stok_paket";
    var $table = "stok_paket";
    var $v_table = "v_stok_paket";
    var $pk = "sp_id";

    function __construct()
    {
        parent::__construct();
        $this->_cek_login();
        $this->output->enable_profiler(FALSE);
        $this->load->model("master_model", "mm");
    }

	public function index()
	{
        if($this->input->get("m") && $this->input->get("a"))
		{
			$get_a = $this->input->get("a");
			$dec_a = base64_decode("$get_a");
			$a = explode("-",$dec_a);

			$get_m = $this->input->get("m");
			$dec_m = base64_decode("$get_m");
			$m = explode("-",$dec_m);

			$data["alert"] = $a[1];
			$data["message"] = $m[1];
		}

        $data["content"] = $this->modul."/home";
        $data["panel_title"] = $this->modul;
		$this->load->view('template/template', $data);
	}

    function ajax_grid()
    {
        $requestData = $this->input->post();

        $columns = array(
            0 => "jasa_ket",
            1 => "barang_nama",
            2 => "sp_qty"
        );

		//count_data
		$res_tot = $this->mm->count_all_data($this->v_table, NULL);
		$tot_data = $res_tot->jml;

		$order_by = $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];
		$offset = $requestData['start'];
		$limit = $requestData['length'];

		//get_all_data
		if( !empty($requestData['search']['value']) )
		{
			// if there is a search parameter, $requestData['search']['value'] contains search parameter
			$where = "(jasa_ket LIKE '%".$requestData['search']['value']."%' OR ";
            $where .= "barang_nama LIKE '%".$requestData['search']['value']."%' OR ";
            $where .= "sp_qty LIKE '%".$requestData['search']['value']."%')";

			$res = $this->mm->get_search_data($this->v_table, $where, $order_by, $limit, $offset);

			$res_filtered_tot = $this->mm->count_all_data($this->v_table, $where);
			$tot_filtered = $res_filtered_tot->jml;
		}
		else
		{
			$res = $this->mm->get_all_data($this->v_table, $order_by, $limit, $offset);
			$tot_filtered = $tot_data;
		}

		$data = array();
        if(!empty($res))
        {
    		foreach($res as $row)
    		{
    			$random = rand();
    			$id = base64_encode($random."-".$row->sp_id);
    			$edit = base64_encode($random."-edit");
    			$delete = base64_encode($random."-delete");

    			$nestedData = array();
                $nestedData[] = date("d-m-Y",strtotime($row->sp_insert_date));
    			$nestedData[] = strtoupper(trim($row->jasa_ket));
                $nestedData[] = strtoupper(trim($row->barang_nama));
                $nestedData[] = trim($row->sp_qty);
    			$nestedData[] = "
    			<button class=\"uk-button uk-button-primary\" onClick=\"edit_function('".$edit."','".$id."');\" type=\"button\" title=\"Edit\"><i class=\"uk-icon uk-icon-edit\"></i></button>
    			<button class=\"uk-button uk-button-danger\" onClick=\"del_function('".$delete."','".$id."');\" type=\"button\" title=\"Delete\"><i class=\"uk-icon uk-icon-trash\"></i></button>
    			";

    			$data[] = $nestedData;
            }
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),
			"recordsTotal"    => intval($tot_data), // total records
			"recordsFiltered" => intval($tot_filtered),
			"data"            => $data
		);
		echo json_encode($json_data);
    }

    function delete_data()
	{
		$id = $this->input->get("id");
		$id_decrypt = base64_decode("$id");
		$arr_id = explode("-",$id_decrypt);

		$m = $this->input->get("m");
		$m_decrypt = base64_decode("$m");
		$method = explode("-",$m_decrypt);

		if($id && $method[1] === "delete")
		{
			$this->mm->delete_data($this->table,$this->pk,$arr_id[1]);

			$rand = rand();
			$msg = base64_encode($rand."-Data berhasil dihapus");
			$alert = base64_encode($rand."-success");
			redirect(base_url($this->modul."/index?m=".$msg."&a=".$alert));
		}
		else
		{
			redirect(base_url($this->modul));
		}
	}

    function form()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('txt_id_jasa', 'Jasa', 'required');
        $this->form_validation->set_rules('txt_id_barang', 'Barang', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$id = $this->input->get("id");
			$m = $this->input->get("m");

			$m_decrypt = base64_decode("$m");
			$method = explode("-",$m_decrypt);

			if($id && $method[1] === "edit")
			{
				$id_decrypt = base64_decode("$id");
				$id_arr = explode("-",$id_decrypt);

				$data["qry_sp"] = $this->mm->get_data_by_id($this->pk,$id_arr[1],$this->v_table);
			}

			$data["content"] = $this->modul."/form";
			$data["panel_title"] = $this->modul;
			$this->load->view('template/template', $data);
		}
		else
		{
            $user_id = $this->session->userdata("isLoggedIn") === TRUE ? $this->session->userdata("userId") : 0;
            $jasa = strtoupper($this->input->post("txt_id_jasa"));
            $barang = $this->input->post("txt_id_barang");
            $qty = $this->input->post("txt_qty");

			if($this->input->post("btn_simpan") === "btn_simpan")
			{
                $field[] = "sp_jasa,";
                $field[] = "sp_barang,";
                $field[] = "sp_qty,";
				$field[] = "sp_insert_date,";
				$field[] = "sp_insert_user";

                $data[] = trim($jasa).",";
                $data[] = trim($barang).",";
                $data[] = trim($qty).",";
                $data[] = "'".date("Y-m-d H:i:s")."',";
				$data[] = "".$user_id;

				// kirim data
                $update_barang = $this->mm->update_stok_barang($barang, $qty); //nge-update jumlah barang di table barang
				$insert_data = $this->mm->insert_data($this->table, $field, $data);
				if($insert_data === 1)
				{
					$rand = rand();
					$msg = base64_encode($rand."-Data berhasil ditambah");
					$alert = base64_encode($rand."-success");
					redirect(base_url($this->modul."/index?m=".$msg."&a=".$alert));
				}
				else
				{
					$msg = base64_encode($rand."-Data tidak berhasil ditambah");
					$alert = base64_encode($rand."-warning");
					redirect(base_url($this->modul."/index?m=".$msg."&a=".$alert));
				}
			}
			elseif ($this->input->post("btn_simpan") === "btn_ubah")
			{
				$sp_id = $this->input->post("txt_sp_id");
                $data["sp_jasa"] = trim($jasa).",";
                $data["sp_barang"] = trim($barang).",";
                $data["sp_qty"] = trim($qty).",";
				$data["sp_update_date"] = "'".date("Y-m-d H:i:s")."',";
                $data["sp_update_user"] = $user_id;

				// kirim data
				$update_data = $this->mm->update_data($this->table, $this->pk, $sp_id, $data);
				if($update_data === 1)
				{
					$rand = rand();
					$msg = base64_encode($rand."-Data berhasil diubah");
					$alert = base64_encode($rand."-success");
					redirect(base_url($this->modul."/index?m=".$msg."&a=".$alert));
				}
				else
				{
					$msg = base64_encode($rand."-Data tidak berhasil diubah");
					$alert = base64_encode($rand."-warning");
					redirect(base_url($this->modul."/index?m=".$msg."&a=".$alert));
				}
			}
			else
			{
				redirect(base_url($this->modul));
			}
		}
	}

    function barang_json()
    {
        $qry_barang = $this->mm->get_all_data("v_barang", NULL, "10000");
        foreach ($qry_barang as $row_barang)
        {
            $data["barang_id"] = $row_barang->barang_id;
            $data["barang_nama"] = $row_barang->barang_nama;
            $data["barang_jumlah"] = $row_barang->barang_jumlah;
            $data["jo_ket"] = $row_barang->jo_ket;
            $j_data[] = $data;
        }
        echo json_encode($j_data);
    }

    function jasa_json()
    {
        $qry_jasa = $this->mm->get_all_data("jasa", NULL, "10000");
        foreach ($qry_jasa as $row_jasa)
        {
            $data["jasa_id"] = $row_jasa->jasa_id;
            $data["jasa_ket"] = $row_jasa->jasa_ket;
            $j_data[] = $data;
        }
        echo json_encode($j_data);
    }
    private function _cek_login()
    {
        if( ! $this->session->userdata('isLoggedIn') OR $this->session->userdata('isLoggedIn') !== TRUE)
        {
            redirect("auth");
        }
    }
}
