<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengeluaran extends CI_Controller
{
    /*
     * @author Zulyantara <zulyantara@gmail.com>
     */

    var $modul = "pengeluaran";
    var $table = "pengeluaran";
    var $v_table = "v_pengeluaran";
    var $pk = "pengeluaran_id";

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
            0 => "ka_kode",
            1 => "pengeluaran_ket",
            2 => "total"
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
			$where = "(MONTH(pengeluaran_insert_date) = MONTH(CURDATE) OR ";
            $where = "(ka_kode LIKE '%".$requestData['search']['value']."%' OR ";
            $where .= "pengeluaran_ket LIKE '%".$requestData['search']['value']."%' OR ";
            $where .= "total LIKE '%".$requestData['search']['value']."%')";

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
    			$id = base64_encode($random."-".$row->pengeluaran_id);
    			$edit = base64_encode($random."-edit");
    			$delete = base64_encode($random."-delete");

                $tgl_insert = new DateTime($row->pengeluaran_insert_date);
    			$nestedData = array();
    			$nestedData[] = strtoupper(trim($row->ka_kode));
    			$nestedData[] = strtoupper(trim($row->pengeluaran_ket));
                $nestedData[] = $tgl_insert->format("d-m-Y");
                $nestedData[] = "<span class=\"uk-text-right\">".number_format($row->total)."</span>";
    			$nestedData[] = "
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
        if ($this->session->userdata("userLevel") === "0" OR $this->session->userdata("userLevel") === "1")
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
        else
        {
            $rand = rand();
            $msg = base64_encode($rand."-Anda tidak memiliki hak akses untuk menghapus data");
            $alert = base64_encode($rand."-danger");

            redirect(base_url($this->modul."/?m=".$msg."&a=".$alert));
        }
	}

    function form()
	{
		$this->load->library('form_validation');

        $this->form_validation->set_rules('opt_pengeluaran_ka', 'Kode Akuntansi', 'required');
        $this->form_validation->set_rules('txt_pengeluaran_ket', 'Keterangan', 'required');
		$this->form_validation->set_rules('txt_pengeluaran_qty', 'Qty', 'required');
        $this->form_validation->set_rules('txt_pengeluaran_harga', 'Harga', 'required');

		if ($this->form_validation->run() === FALSE)
		{
            $data["qry_ka"] = $this->mm->get_search_data("kode_akun","ka_jenis_akun=10 AND ka_akun NOT LIKE '%gaji%'", "ka_kode ASC", "100000");
			$data["content"] = $this->modul."/form";
			$data["panel_title"] = $this->modul;
			$this->load->view('template/template', $data);
		}
		else
		{
            $user_id = $this->session->userdata("isLoggedIn") === TRUE ? $this->session->userdata("userId") : 0;
            $pengeluaran_ka = $this->input->post("opt_pengeluaran_ka");
            $pengeluaran_ket = strtoupper($this->input->post("txt_pengeluaran_ket"));
            $pengeluaran_qty = $this->input->post("txt_pengeluaran_qty");
            $pengeluaran_harga = $this->input->post("txt_pengeluaran_harga");

			if($this->input->post("btn_simpan") === "btn_simpan")
			{
				$field[] = "pengeluaran_kode_akun,";
                $field[] = "pengeluaran_ket,";
                $field[] = "pengeluaran_qty,";
                $field[] = "pengeluaran_harga,";
				$field[] = "pengeluaran_insert_date,";
				$field[] = "pengeluaran_insert_user";

				$data[] = trim($pengeluaran_ka).",";
                $data[] = "'".trim($pengeluaran_ket)."',";
                $data[] = trim($pengeluaran_qty).",";
                $data[] = trim($pengeluaran_harga).",";
                $data[] = "'".date("Y-m-d H:i:s")."',";
				$data[] = "".$user_id;

				// kirim data
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
			else
			{
				redirect(base_url($this->modul));
			}
		}
	}

    private function _cek_login()
    {
        if( ! $this->session->userdata('isLoggedIn') OR $this->session->userdata('isLoggedIn') !== TRUE)
        {
            redirect("auth");
        }
    }
}
