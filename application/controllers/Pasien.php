<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasien extends CI_Controller
{
    /*
     * @author Zulyantara <zulyantara@gmail.com>
     */

    var $modul = "pasien";
    var $table = "pasien";
    var $pk = "pasien_id";

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

        // $data["qry_ka"] = $this->mm->get_data_result("kode_akun", "ka_kode  LIKE 'p-%'");
        $data["content"] = $this->modul."/home";
        $data["panel_title"] = $this->modul;
		$this->load->view('template/template', $data);
	}

    function ajax_grid()
    {
        $requestData = $this->input->post();

        $columns = array(
            0 => "pasien_kode",
            1 => "pasien_nama",
            2 => "pasien_telp",
            3 => "pasien_pekerjaan"
        );

		//count_data
		$res_tot = $this->mm->count_all_data($this->table, NULL);
		$tot_data = $res_tot->jml;

		$order_by = $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];
		$offset = $requestData['start'];
		$limit = $requestData['length'];

		//get_all_data
		if( !empty($requestData['search']['value']) )
		{
			// if there is a search parameter, $requestData['search']['value'] contains search parameter
			$where = "(pasien_kode LIKE '%".$requestData['search']['value']."%' OR ";
            $where .= "pasien_nama LIKE '%".$requestData['search']['value']."%' OR ";
            $where .= "pasien_telp LIKE '%".$requestData['search']['value']."%' OR ";
            $where .= "pasien_pekerjaan LIKE '%".$requestData['search']['value']."%')";

			$res = $this->mm->get_search_data($this->table, $where, $order_by, $limit, $offset);

			$res_filtered_tot = $this->mm->count_all_data($this->table, $where);
			$tot_filtered = $res_filtered_tot->jml;
		}
		else
		{
			$res = $this->mm->get_all_data($this->table, $order_by, $limit, $offset);
			$tot_filtered = $tot_data;
		}

		$data = array();
        if(!empty($res))
        {
    		foreach($res as $row)
    		{
    			$random = rand();
    			$id = base64_encode($random."-".$row->pasien_id);
    			$edit = base64_encode($random."-edit");
    			$delete = base64_encode($random."-delete");

    			$nestedData = array();
                $nestedData[] = strtoupper(trim($row->pasien_kode));
    			$nestedData[] = strtoupper(trim($row->pasien_nama));
                $nestedData[] = trim($row->pasien_telp);
    			$nestedData[] = $row->pasien_pekerjaan;
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

        $this->form_validation->set_rules('txt_pasien_nama', 'Nama', 'required');
		$this->form_validation->set_rules('txt_pasien_tgl_lahir', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('txt_pasien_alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('opt_pasien_sex', 'Jenis Kelamin', 'required');

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

				$data["qry_pasien"] = $this->mm->get_data_by_id($this->pk,$id_arr[1],$this->table);
			}

			$data["qry_af"] = $this->mm->get_all_data("asal_foc",NULL,10000);
            $data["content"] = $this->modul."/form";
			$data["panel_title"] = $this->modul;
			$this->load->view('template/template', $data);
		}
		else
		{
            $user_id = $this->session->userdata("isLoggedIn") === TRUE ? $this->session->userdata("userId") : 0;
            $pasien_nama = strtoupper($this->input->post("txt_pasien_nama"));
            $pasien_tgl_lahir = $this->input->post("txt_pasien_tgl_lahir");
            $pasien_alamat = $this->input->post("txt_pasien_alamat");
            $pasien_sex = $this->input->post("opt_pasien_sex");
            $pasien_telp = $this->input->post("txt_pasien_telp");
            $pasien_tipe = $this->input->post("opt_pasien_tipe") === NULL ? "23" : $this->input->post("opt_pasien_tipe");
            $pasien_af = $this->input->post("opt_pasien_af") === NULL ? "00" : ($this->input->post("opt_pasien_af") === "" ? "00" : $this->input->post("opt_pasien_af"));
            $pasien_pekerjaan = $this->input->post("txt_pasien_pekerjaan");
            // echo $pasien_tipe;exit;
            // echo "<pre>";var_dump($_POST);echo "</pre>";

            // bikin kode pasien
            $kode_pasien_tipe = $pasien_tipe === "23" ? "00" : "01";

            $pasien_kode = $kode_pasien_tipe."-".$pasien_af."-".$pasien_sex."-".strtoupper(substr($pasien_nama,0,1)."-");
            // echo $pasien_kode;exit;
            $lkp = $this->mm->get_last_kode("pasien","pasien_kode","pasien_kode LIKE '$pasien_kode%'", "CONVERT(SUBSTRING(pasien_kode,12,10),UNSIGNED)");
            // var_dump($lkp);exit;
            if ($lkp !== FALSE)
            {
                $explode_lkp = explode("-",$lkp->pasien_kode);
                $no_urut = $explode_lkp[4]+1;
                // echo $no_urut;exit;
            }
            else
            {
                $no_urut = 1;
            }
            $pasien_kode .= $no_urut;
            // echo $pasien_kode;exit;

			if($this->input->post("btn_simpan") === "btn_simpan")
			{
                $field[] = "pasien_kode,";
				$field[] = "pasien_nama,";
                $field[] = "pasien_tgl_lahir,";
                $field[] = "pasien_alamat,";
                $field[] = "pasien_sex,";
                $field[] = "pasien_telp,";
                $field[] = "pasien_tipe,";
                $field[] = "pasien_pekerjaan,";
				$field[] = "pasien_insert_date,";
				$field[] = "pasien_insert_user";

				$data[] = "'".trim($pasien_kode)."',";
                $data[] = "'".trim($pasien_nama)."',";
                $data[] = "'".trim($pasien_tgl_lahir)."',";
                $data[] = "'".trim($pasien_alamat)."',";
                $data[] = "'".trim($pasien_sex)."',";
                $data[] = "'".trim($pasien_telp)."',";
                $data[] = trim($pasien_tipe).",";
                $data[] = "'".trim($pasien_pekerjaan)."',";
                $data[] = "'".date("Y-m-d H:i:s")."',";
				$data[] = $user_id;

				//cek jika ada data yang sama
				$cekdata="pasien_nama LIKE '".$pasien_kode."'";
				$cek_pasien = $this->mm->check_duplicate($this->table ,$cekdata);

				if($cek_pasien->jml === "0")
				{
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
					?>
					<script type="text/javascript">
					alert("Jenis Akun sudah ada");
					window.history.back();
					</script>
					<?php
				}
			}
			elseif ($this->input->post("btn_simpan") === "btn_ubah")
			{
				$pasien_id = "'".$this->input->post("txt_pasien_id")."'";
                $data["pasien_kode"] = "'".$pasien_kode."',";
				$data["pasien_nama"] = "'".trim($pasien_nama)."',";
                $data["pasien_tgl_lahir"] = "'".trim($pasien_tgl_lahir)."',";
                $data["pasien_alamat"] = "'".trim($pasien_alamat)."',";
                $data["pasien_sex"] = "'".trim($pasien_sex)."',";
                $data["pasien_telp"] = "'".trim($pasien_telp)."',";
                $data["pasien_tipe"] = trim($pasien_tipe).",";
                $data["pasien_pekerjaan"] = "'".trim($pasien_pekerjaan)."',";
				$data["pasien_update_date"] = "'".date("Y-m-d H:i:s")."',";
                $data["pasien_update_user"] = $user_id;

				// kirim data
				$update_data = $this->mm->update_data($this->table, $this->pk, $pasien_id, $data);
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

    private function _cek_login()
    {
        if( ! $this->session->userdata('isLoggedIn') OR $this->session->userdata('isLoggedIn') !== TRUE)
        {
            redirect("auth");
        }
    }
}
