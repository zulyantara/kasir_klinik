<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll extends CI_Controller
{
    /*
     * @author Zulyantara <zulyantara@gmail.com>
     */

    var $modul = "payroll";
    var $table = "payroll";
    var $v_table = "v_payroll";
    var $pk = "payroll_id";

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
            0 => "staff_nama",
            1 => "payroll_insert_date",
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
			$where = "(staff_nama LIKE '%".$requestData['search']['value']."%' OR ";
            $where .= "payroll_insert_date LIKE '%".$requestData['search']['value']."%')";

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
    			$id = base64_encode($random."-".$row->payroll_id);
    			$edit = base64_encode($random."-edit");
    			$delete = base64_encode($random."-delete");

                $my_date = new DateTime($row->payroll_insert_date);

    			$nestedData = array();
    			$nestedData[] = strtoupper(trim($row->staff_nama));
    			$nestedData[] = $my_date->format('d-m-Y');

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

    function form()
	{
		$this->load->library('form_validation');

        $this->form_validation->set_rules('opt_payroll_staff', 'Staff', 'required');

		if ($this->form_validation->run() === FALSE)
		{
            $data["qry_staff"] = $this->mm->get_search_data("staff","staff_id NOT IN (SELECT payroll_staff FROM v_payroll WHERE YEAR(payroll_insert_date)=YEAR(CURDATE()) AND MONTH(payroll_insert_date)=MONTH(CURDATE()))", "staff_kode ASC", "100000");
			$data["content"] = $this->modul."/form";
			$data["panel_title"] = $this->modul;
			$this->load->view('template/template', $data);
		}
		else
		{
            $user_id = $this->session->userdata("isLoggedIn") === TRUE ? $this->session->userdata("userId") : 0;
            $payroll_staff = $this->input->post("opt_payroll_staff");

			if($this->input->post("btn_simpan") === "btn_simpan")
			{
				$field[] = "payroll_staff,";
				$field[] = "payroll_insert_date,";
				$field[] = "payroll_insert_user";

				$data[] = trim($payroll_staff).",";
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
