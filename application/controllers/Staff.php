<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends CI_Controller
{
    /*
     * @author Zulyantara <zulyantara@gmail.com>
     */

    var $modul = "staff";
    var $table = "staff";
    var $v_table = "v_staff";
    var $pk = "staff_id";

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
            0 => "staff_kode",
            1 => "staff_nama",
            2 => "staff_no_telp"
        );

		//count_data
		$res_tot = $this->mm->count_all_data($this->table, NULL);
		$tot_data = $res_tot->jml;

		$order_by = $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];
		$offset = $requestData['start'];
		$limit = $requestData['length'];

		//get_all_data
		if( ! empty($requestData['search']['value']) )
		{
			// if there is a search parameter, $requestData['search']['value'] contains search parameter
			$where = "(staff_kode LIKE '%".$requestData['search']['value']."%' OR ";
            $where .= "staff_nama LIKE '%".$requestData['search']['value']."%' OR ";
            $where .= "staff_no_telp LIKE '%".$requestData['search']['value']."%')";

			$res = $this->mm->get_search_data($this->v_table, $where, $order_by, $limit, $offset);

			$res_filtered_tot = $this->mm->count_all_data($this->table, $where);
			$tot_filtered = $res_filtered_tot->jml;
		}
		else
		{
			$res = $this->mm->get_all_data($this->v_table, $order_by, $limit, $offset);
			$tot_filtered = $tot_data;
		}

		$data = array();
        if( ! empty($res))
        {
    		foreach($res as $row)
    		{
    			$random = rand();
    			$id = base64_encode($random."-".$row->staff_id);
    			$edit = base64_encode($random."-edit");
    			$delete = base64_encode($random."-delete");

    			$nestedData = array();
    			$nestedData[] = strtoupper(trim($row->staff_kode));
                $nestedData[] = strtoupper(trim($row->staff_nama));
                $nestedData[] = trim($row->staff_no_telp);
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

		$this->form_validation->set_rules('txt_staff_kode', 'Kode', 'required');
        $this->form_validation->set_rules('txt_staff_nama', 'Nama', 'required');
        $this->form_validation->set_rules('txt_staff_tgl_lahir', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('txt_staff_no_telp', 'No. Telp', 'required');
        $this->form_validation->set_rules('txt_staff_alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('txt_staff_gaji', 'Gaji', 'required');
        $this->form_validation->set_rules('txt_staff_jabatan', 'jabatan', 'required');
        $this->form_validation->set_rules('opt_staff_ka', 'Kode Akun', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$id = $this->input->get("id");
			$m = $this->input->get("m");

			$m_decrypt = base64_decode("$m");
			$method = explode("-",$m_decrypt);

            $kode_staff = "KRY-";

            // autonumber kode staff
            $qry_last_staff = $this->mm->get_data_row($this->table, "1=1","staff_id DESC", 1);
            if($qry_last_staff === FALSE)
            {
                $kode_staff .= "001";
            }
            else
            {
                $last_kode = $qry_last_staff->staff_kode;
                //echo "<pre>";var_dump($qry_last_staff);echo "</pre>";
                $nu_lk = substr($last_kode, -3); //nomor urut last kode 3 digit
                $no_urut = $nu_lk + 1;
                $jml_no_urut = strlen($no_urut);
                if($jml_no_urut == 1)
                {
                    $kode_staff .= "00".$no_urut;
                }
                elseif ($jml_no_urut == 2)
                {
                    $kode_staff .= "0".$no_urut;
                }
                else
                {
                    $kode_staff .= $no_urut;
                }
            }

			if($id && $method[1] === "edit")
			{
				$id_decrypt = base64_decode("$id");
				$id_arr = explode("-",$id_decrypt);

				$data["qry_staff"] = $this->mm->get_data_by_id($this->pk,$id_arr[1],$this->table);

			}

            $data["kode_staff"] = $kode_staff;
            $data["qry_ka"] = $this->mm->get_search_data("kode_akun","ka_jenis_akun=10 AND ka_akun LIKE '%gaji%'");
            $data["content"] = $this->modul."/form";
			$data["panel_title"] = $this->modul;
			$this->load->view('template/template', $data);
		}
		else
		{
            $user_id = $this->session->userdata("isLoggedIn") === TRUE ? $this->session->userdata("userId") : 0;
            $staff_id = $this->input->post("txt_staff_id");
            $staff_kode = strtoupper($this->input->post("txt_staff_kode"));
            $staff_nama = strtoupper($this->input->post("txt_staff_nama"));
            $staff_tgl_lahir = $this->input->post("txt_staff_tgl_lahir");
            $staff_no_telp = $this->input->post("txt_staff_no_telp");
            $staff_alamat = $this->input->post("txt_staff_alamat");
            $staff_gaji = str_replace(",","",$this->input->post("txt_staff_gaji"));
            $staff_jabatan = strtoupper($this->input->post("txt_staff_jabatan"));
            $staff_ka = $this->input->post("opt_staff_ka");

			if($this->input->post("btn_simpan") === "btn_simpan")
			{
                $field[] = "staff_kode,";
                $field[] = "staff_nama,";
                $field[] = "staff_tgl_lahir,";
                $field[] = "staff_no_telp,";
                $field[] = "staff_alamat,";
                $field[] = "staff_gaji,";
                $field[] = "staff_jabatan,";
                $field[] = "staff_kode_akun,";
				$field[] = "staff_insert_date,";
				$field[] = "staff_insert_user";

                $data[] = "'".trim($staff_kode)."',";
                $data[] = "'".trim($staff_nama)."',";
                $data[] = "'".trim($staff_tgl_lahir)."',";
                $data[] = "'".trim($staff_no_telp)."',";
                $data[] = "'".trim($staff_alamat)."',";
                $data[] = trim($staff_gaji).",";
                $data[] = "'".trim($staff_jabatan)."',";
                $data[] = trim($staff_ka).",";
                $data[] = "'".date("Y-m-d H:i:s")."',";
				$data[] = "".$user_id;

				//cek jika ada data yang sama
				$cekdata="staff_kode LIKE '".$staff_kode."'";
				$cek_sb = $this->mm->check_duplicate($this->table ,$cekdata);

				if($cek_sb->jml === "0")
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
					alert("Staff sudah ada");
					window.history.back();
					</script>
					<?php
				}
			}
			elseif ($this->input->post("btn_simpan") === "btn_ubah")
			{
				$user_id = $this->session->userdata("isLoggedIn") === TRUE ? $this->session->userdata("userId") : 0;

				$staff_id = "'".$this->input->post("txt_staff_id")."'";
                $data["staff_kode"] = "'".trim($staff_kode)."',";
                $data["staff_nama"] = "'".trim($staff_nama)."',";
                $data["staff_tgl_lahir"] = "'".trim($staff_tgl_lahir)."',";
                $data["staff_no_telp"] = "'".trim($staff_no_telp)."',";
                $data["staff_alamat"] = "'".trim($staff_alamat)."',";
                $data["staff_gaji"] = "'".trim($staff_gaji)."',";
                $data["staff_jabatan"] = "'".trim($staff_jabatan)."',";
                $data["staff_kode_akun"] = "'".trim($staff_ka)."',";
				$data["staff_update_date"] = "'".date("Y-m-d H:i:s")."',";
                $data["staff_update_user"] = $user_id;

				// kirim data
				$update_data = $this->mm->update_data($this->table, $this->pk, $staff_id, $data);
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
        if ($this->session->userdata('userLevel') === '2')
        {
            redirect('home');
        }
    }
}
