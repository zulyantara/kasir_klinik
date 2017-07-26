<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian extends CI_Controller
{
    /*
     * @author Zulyantara <zulyantara@gmail.com>
     */

    function __construct()
    {
        parent::__construct();
        $this->_cek_login();
        $this->output->enable_profiler(FALSE);
        $this->load->model("master_model","mm");
        $this->load->model("pembelian_model","pm");
    }

    function index()
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

        $data["content"] = "pembelian/home";
        $data["panel_title"] = "pembelian";
        $this->load->view('template/template', $data);
    }

    function form_head()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('txt_nama', 'Nama Supplier', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $cek_pdt = $this->pm->cek_pdt();

            if ($cek_pdt === FALSE)
            {
                $data["content"] = "pembelian/header";
                $data["panel_title"] = "pembelian";
                $this->load->view('template/template', $data);
            }
            else
            {
                $random = rand();
                $rand_tk = base64_encode($random."-".$cek_pdt->pdt_head."-".$cek_pdt->pdt_nama);
                redirect("pembelian/form_detail?k=".$rand_tk);
            }
        }
        else
        {
            $btn_simpan = $this->input->post("btn_simpan_head");
            if ($btn_simpan === "simpan_head")
            {
                // get last ph id
                $ph_id = $this->pm->get_last_id_ph();
                if ($ph_id !== 1)
                {
                    $last_id = $ph_id->ph_id + 1;
                }
                else
                {
                    $last_id = $ph_id->ph_id;
                }
                // echo $last_id;exit;

                $random = rand();
                $rand_tk = base64_encode($random."-".$last_id."-".$this->input->post("txt_nama"));

                redirect("pembelian/form_detail?k=".$rand_tk);
            }
            else
            {
                redirect("pembelian");
            }
        }
    }

    function form_detail()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('txt_id_barang', 'Barang', 'required');
        $this->form_validation->set_rules('txt_harga', 'Harga', 'required');
        $this->form_validation->set_rules('txt_qty', 'Qty', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $get_k = $this->input->get("k");

            if ($get_k !== NULL)
            {
                $dec_k = base64_decode("$get_k");
    			$k = explode("-",$dec_k);
                // var_dump($k);

                $data["k"] = $k[1];
                $data["s"] = $k[2];

                $data["qry_kb"] = $this->mm->get_all_data("kelompok_barang", NULL, "100000");
                $data["qry_pdt"] = $this->pm->get_data_pdt($k[1]);
                $data["content"] = "pembelian/detail";
                $data["panel_title"] = "pembelian";
                $this->load->view('template/template', $data);
            }
            else
            {
                redirect("pembelian");
            }
        }
        else
        {
            $btn_simpan = $this->input->post("btn_simpan");

            // baru nyimpen ke pembelian detail temp belum ke pembelian detail biar bisa edit sama dokter
            if ($btn_simpan === "simpan")
            {
                // var_dump($this->input->post());exit;
                $data["head"] = $this->input->post("txt_head");
                $data["nama"] = $this->input->post("txt_supplier");
                $data["barang"] = $this->input->post("txt_id_barang");
                $data["qty"] = str_replace(",","",$this->input->post("txt_qty"));
                $data["harga"] = str_replace(",","",$this->input->post("txt_harga"));

                $insert_pdt = $this->pm->insert_pdt($data);

                $random = rand();
                $rand_tk = base64_encode($random."-".$this->input->post("txt_head")."-".$this->input->post("txt_supplier"));

                redirect("pembelian/form_detail?k=".$rand_tk);
            }
            else
            {
                redirect("pembelian");
            }
        }
    }

    function simpan_pembelian()
    {
        $btn_simpan = $this->input->post("btn_simpan");
        if ($btn_simpan === "simpan_detail")
        {
            $this->pm->simpan_pembelian_detail($this->input->post("txt_head"));
            redirect("pembelian");
        }
        else
        {
            redirect("pembelian");
        }
    }

    function form_edit()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('txt_id_barang', 'Barang', 'required');
        $this->form_validation->set_rules('txt_harga', 'Harga', 'required');
        $this->form_validation->set_rules('txt_qty', 'Qty', 'required');

		if ($this->form_validation->run() === FALSE)
		{
            $get_m = $this->input->get("m");
            $dec_m = base64_decode("$get_m");
            $m = explode("-",$dec_m);
            // var_dump($m);

            $get_id = $this->input->get("id");
            $dec_id = base64_decode("$get_id");
            $id = explode("-",$dec_id);
            // var_dump($m);

            $data["m"] = $m[1];
            $data["id"] = $id[1];
            $data["qry_pd"] = $this->mm->get_pd($id[1]);
            $data["qry_barang"] = $this->mm->get_all_data("barang", NULL, "100000");
            $data["content"] = "pembelian/form_edit";
            $data["panel_title"] = "pembelian";
            $this->load->view("template/template",$data);
        }
        else
        {
            $btn_simpan = $this->input->post("btn_simpan");

            // baru nyimpen ke pembelian detail temp belum ke pembelian detail biar bisa edit sama dokter
            if ($btn_simpan === "simpan")
            {
                // var_dump($this->input->post());exit;
                $data["head"] = $this->input->post("txt_head");
                $data["barang"] = $this->input->post("txt_id_barang");
                $data["qty"] = str_replace(",","",$this->input->post("txt_qty"));
                $data["harga"] = str_replace(",","",$this->input->post("txt_harga"));

                $insert_pdt = $this->pm->insert_pd($data);

                redirect("pembelian/form_detail?m=".$get_m.'&id='.$get_id);
            }
            else
            {
                redirect("pembelian");
            }
        }
    }

    function delete_data()
    {
        $get_m = $this->input->get("m");
        $get_k = $this->input->get("id");
        $dec_k = base64_decode("$get_k");
        $k = explode("-",$dec_k);

        $delete_pdt = $this->pm->delete_pembelian($k[1]);

        redirect("pembelian/index?m=".$get_m.'&id='.$get_k);
    }

    function ajax_grid()
    {
        $requestData = $this->input->post();

        $columns = array(
            0 => "ph_nama",
            4 => "ph_insert_date"
        );

		//count_data
		$res_tot = $this->mm->count_all_data("pembelian_head", NULL);
		$tot_data = $res_tot->jml;

		$order_by = $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];
		$offset = $requestData['start'];
		$limit = $requestData['length'];

		//get_all_data
		if( !empty($requestData['search']['value']) )
		{
			// if there is a search parameter, $requestData['search']['value'] contains search parameter
			$where = "(ph_nama LIKE '%".$requestData['search']['value']."%' OR ";
            $where .= "total LIKE '%".$requestData['search']['value']."%' OR ";
            $where .= "pd_insert_date LIKE '%".$requestData['search']['value']."%')";

			$res = $this->mm->get_search_data("v_ph", $where, $order_by, $limit, $offset);

			$res_filtered_tot = $this->mm->count_all_data("v_ph", $where);
			$tot_filtered = $res_filtered_tot->jml;
		}
		else
		{
			$res = $this->mm->get_all_data("v_ph", $order_by, $limit, $offset);
			$tot_filtered = $tot_data;
		}

		$data = array();
        if(!empty($res))
        {
    		foreach($res as $row)
    		{
    			$random = rand();
    			$id = base64_encode($random."-".$row->ph_id);
    			$edit = base64_encode($random."-edit");
    			$delete = base64_encode($random."-delete");

    			$nestedData = array();
    			$nestedData[] = strtoupper(trim($row->ph_nama));
                $nestedData[] = trim($row->ph_insert_date);
                $nestedData[] = number_format($row->total);
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

    function delete_pd()
    {
        $get_m = $this->input->get("m");
        $get_id = $this->input->get("id");
        $dec_id = base64_decode("$get_id");
        $id = explode("-",$dec_id);

        $delete_pd = $this->pm->delete_pd($id[1]);

        redirect("pembelian/form_detail?m=".$get_m.'&id='.$get_id);
    }

    function simpan_edit()
    {
        if ($this->input->post('btn_simpan') === 'simpan')
        {
            $data['head'] = $this->input->post('txt_head');
            $data['id_barang'] = $this->input->post('txt_id_barang');
            $data['qty'] = $this->input->post('txt_qty');
            $data['harga'] = $this->input->post('txt_harga');

            $this->pm->insert_pd($data);

            $random = rand();
            $id = base64_encode($random."-".$data['head']);
            $edit = base64_encode($random."-edit");
            redirect('pembelian/form_detail?m='.$edit.'&id='.$id);
        }
        else
        {
            redirect('pembelian');
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
