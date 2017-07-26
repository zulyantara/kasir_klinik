<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller
{
    /*
     * @author zulyantara <zulyantara@gmail.com>
     * @copyright copyright 2016 zulyantara
     */

	function __construct()
	{
		parent::__construct();
		$this->output->enable_profiler(FALSE);
		$this->load->model("auth_model", "am");
		$this->load->model("master_model", "mm");
	}

    function index()
    {
        if($this->session->userdata('isLoggedIn') !== TRUE)
        {
            $this->load->view('login/home');
        }
        else
        {
        	redirect('home');
        }
    }

	function list_user()
	{
		if ($this->session->userdata("userLevel") === "0")
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

	        $data["content"] = "login/list_user";
	        $data["panel_title"] = "user";
			$this->load->view('template/template', $data);
		}
		else
		{
			redirect(base_url());
		}
	}

	function ajax_grid()
    {
		if ($this->session->userdata("userLevel") === "0")
		{
	        $requestData = $this->input->post();

	        $columns = array(
	            0 => "user_name",
	            1 => "user_email",
	            2 => "ul_ket"
	        );

			//count_data
			$res_tot = $this->mm->count_all_data("user", NULL);
			$tot_data = $res_tot->jml;

			$order_by = $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];
			$offset = $requestData['start'];
			$limit = $requestData['length'];

			//get_all_data
			if( !empty($requestData['search']['value']) )
			{
				// if there is a search parameter, $requestData['search']['value'] contains search parameter
				$where = "(user_name LIKE '%".$requestData['search']['value']."%' OR ";
	            $where .= "user_email LIKE '%".$requestData['search']['value']."%' OR ";
	            $where .= "ul_ket LIKE '%".$requestData['search']['value']."%')";

				$res = $this->mm->get_search_data("v_user", $where, $order_by, $limit, $offset);

				$res_filtered_tot = $this->mm->count_all_data("v_user", $where);
				$tot_filtered = $res_filtered_tot->jml;
			}
			else
			{
				$res = $this->mm->get_all_data("v_user", $order_by, $limit, $offset);
				$tot_filtered = $tot_data;
			}

			$data = array();
	        if(!empty($res))
	        {
	    		foreach($res as $row)
	    		{
	    			$random = rand();
	    			$id = base64_encode($random."-".$row->user_id);
	    			$edit = base64_encode($random."-edit");
	    			$delete = base64_encode($random."-delete");

	    			$nestedData = array();
	    			$nestedData[] = strtoupper(trim($row->user_name));
	    			$nestedData[] = trim($row->user_email);
	                $nestedData[] = strtoupper(trim($row->ul_ket));
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
		else
		{
			redirect(base_url());
		}
    }

	function form_input()
	{
		if ($this->session->userdata("userLevel") === "0")
		{
			$this->check_level();
			$this->load->library('form_validation');

			$this->form_validation->set_rules('txt_user_name', 'Username', 'required');
			$this->form_validation->set_rules('txt_user_password', 'Password', 'required');
			$this->form_validation->set_rules('opt_user_level', 'Level', 'required');

			if ($this->form_validation->run() === FALSE)
			{
	            $data["qry_ul"] = $this->mm->get_search_data("user_level", 'ul_id <> 0', NULL,'10000');
				$data["content"] = "login/form_input";
				$data["panel_title"] = "user";
				$this->load->view('template/template', $data);
			}
			else
			{
	            $user_name = strtoupper($this->input->post("txt_user_name"));
				$user_password = $this->input->post("txt_user_password");
	            $user_email = $this->input->post("txt_user_email");
				$user_level = $this->input->post("opt_user_level");

				if($this->input->post("btn_simpan") === "btn_simpan")
				{
					$data["user_name"] = trim($user_name);
	                $data["user_password"] = trim($user_password);
	                $data["user_email"] = trim($user_email);
	                $data["user_level"] = trim($user_level);

					//cek jika ada data yang sama
					$cekdata="user_name LIKE '".$user_name."'";
					$cek_user = $this->am->check_duplicate($cekdata);

					if($cek_user->jml === "0")
					{
						// kirim data
						$insert_data = $this->am->insert_user($data);
						if($insert_data === 1)
						{
							$rand = rand();
							$msg = base64_encode($rand."-Data berhasil ditambah");
							$alert = base64_encode($rand."-success");
							redirect(base_url("auth/list_user?m=".$msg."&a=".$alert));
						}
						else
						{
							$msg = base64_encode($rand."-Data tidak berhasil ditambah");
							$alert = base64_encode($rand."-warning");
							redirect(base_url("auth/list_user?m=".$msg."&a=".$alert));
						}
					}
					else
					{
						?>
						<script type="text/javascript">
						alert("User sudah ada");
						window.history.back();
						</script>
						<?php
					}
				}
				else
				{
					redirect(base_url("auth/list_user"));
				}
			}
		}
		else
		{
			redirect(base_url());
		}
	}

	function change_password()
	{
		$this->load->library('form_validation');

		$data["txt_user_id"] = $this->session->userdata("userId");
		$data["txt_old_password"] = $this->input->post("txt_old_password");
		$data["txt_new_password"] = $this->input->post("txt_new_password");
		$data["txt_confirm_password"] = $this->input->post("txt_confirm_password");

		$this->form_validation->set_rules('txt_old_password', 'Old Password', 'trim|required');
		$this->form_validation->set_rules('txt_new_password', 'New Password', 'trim|required|matches[txt_confirm_password]');
		$this->form_validation->set_rules('txt_confirm_password', 'Password Confirmation', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$data["panel_title"] = "change password";
			$data["content"] = "login/form";
			$this->load->view("template/template", $data);
		}
		else
		{
			//check password
			$cp = $this->am->check_password($data);

			if($cp->jml === "1")
			{
				$update = $this->am->update_password($data);
				if($update === 1)
				{
					$data["panel_title"] = "change password";
					$data["content"] = "login/form";
					$data["message"] = "Password berhasil diubah";
					$this->load->view("template/template", $data);
				}
				else
				{
					redirect("/auth/form_change_password","refresh");
				}
			}
			else
			{
				$data["panel_title"] = "change password";
				$data["content"] = "login/form";
				$data["message"] = "Old Password tidak sama";
				$this->load->view("template/template", $data);
			}
		}
	}

    function validate_credential()
    {
		if($this->input->post('btn_login') === 'btn_login')
		{
			$this->load->model('auth_model');

			$query = $this->auth_model->validate($this->input->post('txt_user_name'), $this->input->post('txt_user_password'));
			$qry_profil = $this->auth_model->get_profil();
			if($query != FALSE)
			{
				$data = array(
					'userId' => $query->user_id,
					'userName' => $query->user_name,
					'userLevel' => $query->user_level,
					'profilKlinik' => $qry_profil->profil_nama,
					'isLoggedIn' => TRUE
				);

				$this->session->set_userdata($data);
				redirect("home");
			}
			else
			{
				$data['error'] = "Username atau Password salah";
				$this->load->view('login/home', $data);
			}
		}
		else
		{
			$data['error'] = "Anda harus login terlebih dahulu";
			$this->load->view('login/home', $data);
		}
    }

    function logout()
	{
		$this->session->sess_destroy();
		$this->load->view('login/home');
	}

	function check_level()
	{
		if ($this->session->userdata("userLevel") !== "0")
		{
			redirect(base_url());
		}
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
			$this->am->delete_user($arr_id[1]);

			$rand = rand();
			$msg = base64_encode($rand."-Data berhasil dihapus");
			$alert = base64_encode($rand."-success");
			redirect(base_url("auth/list_user?m=".$msg."&a=".$alert));
		}
		else
		{
			redirect(base_url("auth/list_user"));
		}
	}
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
