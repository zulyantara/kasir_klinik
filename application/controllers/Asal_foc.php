<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asal_foc extends CI_Controller
{
    /*
     * @author Zulyantara <zulyantara@gmail.com>
     */

    var $modul = "asal_foc";
    var $table = "asal_foc";
    var $pk = "af_id";

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
            0 => "af_kode",
            1 => "af_ket"
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
			$where = "(af_kode LIKE '%".$requestData['search']['value']."%' OR ";
            $where .= "af_ket LIKE '%".$requestData['search']['value']."%')";

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
    			$id = base64_encode($random."-".$row->af_id);
    			$edit = base64_encode($random."-edit");
    			$delete = base64_encode($random."-delete");

    			$nestedData = array();
    			$nestedData[] = strtoupper(trim($row->af_kode));
    			$nestedData[] = strtoupper(trim($row->af_ket));
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
        if ($this->session->userdata('userLevel') === '2')
        {
            redirect($this->modul);
        }
        else
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
	}

    function form()
	{
        if ($this->session->userdata('userLevel') === '2')
        {
            redirect($this->modul);
        }
        else
        {
    		$this->load->library('form_validation');

    		$this->form_validation->set_rules('txt_af_ket', 'Ket', 'required');
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

    				$data["qry_af"] = $this->mm->get_data_by_id($this->pk,$id_arr[1],$this->table);

    			}

    			$data["content"] = $this->modul."/form";
    			$data["panel_title"] = $this->modul;
    			$this->load->view('template/template', $data);
    		}
    		else
    		{
                $user_id = $this->session->userdata("isLoggedIn") === TRUE ? $this->session->userdata("userId") : 0;
                $af_ket = strtoupper($this->input->post("txt_af_ket"));

                // get last kode
                $lk = $this->mm->get_last_kode("asal_foc","af_kode",'1=1',"af_kode");
                if ($lk === FALSE)
                {
                    $kode_af = "01";
                }
                else
                {
                    $af_kode = $lk->af_kode+1;
                    if (strlen($af_kode) == 1)
                    {
                        $kode_af = "0".$af_kode;
                    }
                    else
                    {
                        $kode_af = $af_kode;
                    }
                }

    			if($this->input->post("btn_simpan") === "btn_simpan")
    			{
    				$field[] = "af_kode,";
                    $field[] = "af_ket,";
    				$field[] = "af_insert_date,";
    				$field[] = "af_insert_user";

    				$data[] = "'".trim($kode_af)."',";
                    $data[] = "'".trim($af_ket)."',";
                    $data[] = "'".date("Y-m-d H:i:s")."',";
    				$data[] = "".$user_id;

    				//cek jika ada data yang sama
    				$cekdata="af_kode LIKE '".$af_kode."'";
    				$cek_ja = $this->mm->check_duplicate($this->table ,$cekdata);

    				if($cek_ja->jml === "0")
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
    				$user_id = $this->session->userdata("isLoggedIn") === TRUE ? $this->session->userdata("userId") : 0;

    				$af_id = "'".$this->input->post("txt_af_id")."'";
                    $data["af_ket"] = "'".trim($af_ket)."',";
    				$data["af_update_date"] = "'".date("Y-m-d H:i:s")."',";
                    $data["af_update_user"] = $user_id;

    				// kirim data
    				$update_data = $this->mm->update_data($this->table, $this->pk, $af_id, $data);
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
	}

    private function _cek_login()
    {
        if( ! $this->session->userdata('isLoggedIn') OR $this->session->userdata('isLoggedIn') !== TRUE)
        {
            redirect("auth");
        }
    }
}
