<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller
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
    }

    function index()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('txt_customer', 'Customer', 'required');
        $this->form_validation->set_rules('opt_pasien', 'Pasien', 'required');
        $this->form_validation->set_rules('txt_dokter', 'Dokter', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $cek_tdt = $this->mm->cek_tdt();
            if ($cek_tdt === FALSE)
            {
                $kode_transaksi = "TR/".date("m/Y/");

                // autonumber kode transaksi
                $qry_last_transaksi = $this->mm->get_data_row("transaksi_head", "th_kode LIKE '".$kode_transaksi."%'","th_id DESC", 1);
                if($qry_last_transaksi === FALSE)
                {
                    $kode_transaksi .= "1";
                }
                else
                {
                    $last_kode = $qry_last_transaksi->th_kode;
                    //echo "<pre>";var_dump($qry_last_staff);echo "</pre>";
                    $nu_lk = substr($last_kode, 11); //nomor urut last kode 3 digit
                    $no_urut = $nu_lk + 1;
                    $kode_transaksi .= $no_urut;
                }

                $data["kode_transaksi"] = $kode_transaksi;
                $data["qry_ka"] = $this->mm->get_search_data("kode_akun","ka_kode LIKE 'p-%'", NULL, "10000000");
                // $data["qry_pasien"] = $this->mm->get_all_data("pasien", NULL, "10000000");
                $data["content"] = "transaksi/header";
                $data["panel_title"] = "transaksi";
                $this->load->view('template/template', $data);
            }
            else
            {
                // var_dump($cek_tdt);exit;
                $random = rand();
                $rand_tk = base64_encode($random."-".$cek_tdt->tdt_kode_transaksi."-".$cek_tdt->tdt_customer."-".$cek_tdt->tdt_pasien."-".$cek_tdt->tdt_dokter.'-'.$cek_tdt->tdt_kode_akun);
                redirect("transaksi/form_detail?k=".$rand_tk);
            }
        }
        else
        {
            $btn_simpan = $this->input->post("btn_simpan_head");
            if ($btn_simpan === "btn_simpan_head")
            {
                $random = rand();
                $rand_tk = base64_encode($random."-".$this->input->post("txt_kode_transaksi")."-".$this->input->post("txt_customer")."-".$this->input->post("opt_pasien")."-".$this->input->post('txt_dokter')."-".$this->input->post("opt_kode_akun"));

                redirect("transaksi/form_detail?k=".$rand_tk);
            }
            else
            {
                redirect("transaksi");
            }
        }
    }

    function form_detail()
    {
        $this->load->library('form_validation');

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
                $data["c"] = $k[2];
                $data["p"] = $k[3];
                $data["d"] = $k[4];
                $data["ka"] = $k[5];

                // ini buat ngecek kode transaksi head, klo udah ada di table maka balikin ke index
                $cek_kode_transaksi = $this->mm->check_duplicate("transaksi_head", "th_kode='".$k[1]."'");

                if ($cek_kode_transaksi->jml === "0")
                {
                    $data["qry_pasien"] = $this->mm->get_pasien($k[3]);
                    $data["qry_kb"] = $this->mm->get_all_data("kelompok_barang", NULL, "100000");
                    $data["qry_jasa"] = $this->mm->get_all_data("jasa", NULL, "100000");
                    $data["qry_tdt"] = $this->mm->get_data_tdt($k[1]);
                    $data["content"] = "transaksi/detail";
                    $data["panel_title"] = "transaksi";
                    $this->load->view('template/template', $data);
                }
                else
                {
                    redirect("transaksi");
                }
            }
            else
            {
                redirect("transaksi");
            }
        }
        else
        {
            $btn_simpan = $this->input->post("btn_simpan_detail");

            // baru nyimpen ke transaksi detail temp belum ke transaksi detail biar bisa edit sama dokter
            if ($btn_simpan === "btn_simpan_detail")
            {
                $data["kode_transaksi"] = $this->input->post("txt_kode_transaksi");
                $data["kode_akun"] = $this->input->post("txt_kode_akun");
                $data["pasien"] = $this->input->post("txt_pasien");
                $data["customer"] = $this->input->post("txt_customer");
                $data["dokter"] = $this->input->post("txt_dokter");
                $data["jasa"] = $this->input->post("opt_jasa");
                $data["barang"] = $this->input->post("opt_barang");
                $data["qty"] = $this->input->post("txt_qty");

                if ($data["jasa"] === '0' && $data['barang'] === '0')
                {
                    ?>
                    <script>
                    alert("Pilih salah satu, Jasa atau Obat");
                    window.history.back();
                    </script>
                    <?php
                }

                if ($data["jasa"] !== '0' && $data['barang'] !== '0')
                {
                    ?>
                    <script>
                    alert("Pilih salah satu, Jasa atau Obat");
                    window.history.back();
                    </script>
                    <?php
                }

                $data["harga"] = $this->input->post("opt_barang") === "0" ? $this->mm->get_harga("jasa",$this->input->post("opt_jasa")) : $this->mm->get_harga("barang",$this->input->post("opt_barang"));

                $insert_tdt = $this->mm->insert_tdt($data);

                $random = rand();
                $rand_tk = base64_encode($random."-".$this->input->post("txt_kode_transaksi")."-".$this->input->post("txt_customer")."-".$this->input->post("txt_pasien")."-".$this->input->post('txt_dokter')."-".$this->input->post("txt_kode_akun"));

                redirect("transaksi/form_detail?k=".$rand_tk);
            }
            else
            {
                redirect("transaksi");
            }
        }
    }

    function simpan_transaksi()
    {
        $btn_simpan = $this->input->post("btn_simpan_detail");
        if ($btn_simpan === "btn_simpan_transaksi_detail")
        {
            $this->mm->simpan_transaksi_detail($this->input->post("txt_kode_transaksi"));
            redirect("transaksi");
        }
        elseif ($btn_simpan === "btn_cetak_transaksi_detail")
        {
            $this->mm->simpan_transaksi_detail($this->input->post("txt_kode_transaksi"));

            $data["qry_transaksi"] = $this->mm->get_search_data("v_transaksi","th_kode='".$this->input->post("txt_kode_transaksi")."'",NULL,10000);
            $data["qry_thead"] = $this->mm->get_transaksi_head($this->input->post("txt_kode_transaksi"));
            $this->load->view('transaksi/struk', $data);
            $html = $this->output->get_output();
            $this->load->library('dompdf_gen');
            $this->dompdf->load_html($html);
            $this->dompdf->set_paper("A4");
            $this->dompdf->render();
            $this->dompdf->stream("struk_".$this->input->post("txt_kode_transaksi").".pdf", array("Attachment"=>0));
        }
        else
        {
            redirect(base_url("transaksi"));
        }
    }

    function form_edit()
    {
        $get_k = $this->input->get("k");
        $dec_k = base64_decode("$get_k");
        $k = explode("-",$dec_k);
        // var_dump($k);

        $data["k"] = $k[1];
        $data["qry_tdt"] = $this->mm->get_row_tdt($k[1]);
        $data["qry_barang"] = $this->mm->get_all_data("barang", NULL, "100000");
        $data["qry_jasa"] = $this->mm->get_all_data("jasa", NULL, "100000");
        $data["content"] = "transaksi/form";
        $data["panel_title"] = "transaksi";
        $this->load->view("template/template",$data);
    }

    function update_transaksi()
    {
        if ($this->input->post("btn_update") === "btn_update")
        {
            $data["tdt_id"] = $this->input->post("txt_id");
            $data["tdt_jasa"] = $this->input->post("opt_jasa");
            $data["tdt_barang"] = $this->input->post("opt_barang");
            $data["tdt_qty"] = $this->input->post("txt_qty");

            $update_tdt = $this->mm->update_tdt($data);

            $row_tdt = $this->mm->get_row_tdt($this->input->post("txt_id"));

            $random = rand();
            $rand_tk = base64_encode($random."-".$row_tdt->tdt_kode_transaksi."-".$row_tdt->tdt_customer."-".$row_tdt->tdt_pasien."-".$row_tdt->tdt_dokter."-".$row_tdt_tdt_kode_akun);
            redirect("transaksi/form_detail?k=".$rand_tk);
        }
        else
        {
            redirect("transaksi");
        }
    }

    function delete()
    {
        $get_k = $this->input->get("k");
        $dec_k = base64_decode("$get_k");
        $k = explode("-",$dec_k);

        $delete_tdt = $this->mm->delete_tdt($k[1]);

        // $row_tdt = $this->mm->cek_tdt();
        // echo "<pre>";var_dump($row_tdt);echo "</pre>";exit;

        // $random = rand();
        // $rand_tk = base64_encode($random."-".$row_tdt->tdt_kode_transaksi."-".$row_tdt->tdt_customer."-".$row_tdt->tdt_pasien);
        redirect("transaksi");
    }

    function delete_transaksi()
    {
        $get_k = $this->input->get("id");
        $dec_k = base64_decode("$get_k");
        $k = explode("-",$dec_k);

        $delete_td = $this->mm->delete_td($k[1]);

        redirect("transaksi/list_transaksi");
    }

    function pasien_json()
    {
        $id = $this->input->get("i");
        if ($id != NULL)
        {
            $qry_pasien = $this->mm->get_data_result("pasien", "pasien_tipe=".$id);
        }
        else
        {
            $qry_pasien = $this->mm->get_all_data("pasien", NULL, "10000");
        }
        foreach ($qry_pasien as $row_pasien)
        {
            $data["pasien_id"] = $row_pasien->pasien_id;
            $data["pasien_nama"] = $row_pasien->pasien_nama;
            $data["pasien_tgl_lahir"] = $row_pasien->pasien_tgl_lahir;
            $j_data[] = $data;
        }
        echo json_encode($j_data);
    }

    function barang_json()
    {
        $id = $this->input->get("i");
        if ($id != NULL)
        {
            $qry_barang = $this->mm->get_data_result("v_barang", "barang_kelompok=".$id);
        }
        else
        {
            $qry_barang = $this->mm->get_all_data("v_barang", NULL, "10000");
        }
        foreach ($qry_barang as $row_barang)
        {
            $data["barang_id"] = $row_barang->barang_id;
            $data["barang_nama"] = $row_barang->barang_nama;
            $data["barang_jumlah"] = $row_barang->barang_jumlah;
            $data['jo_ket'] = $row_barang->jo_ket;
            $j_data[] = $data;
        }
        echo json_encode($j_data);
    }

    function list_transaksi()
	{
        if ($this->session->userdata('userLevel') == 0)
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

            $data["content"] ="transaksi/list";
            $data["panel_title"] = 'transaksi';
    		$this->load->view('template/template', $data);
        }
        else
        {
            redirect('home');
        }
	}

    function ajax_grid()
    {
        if ($this->session->userdata('userLevel') == 0)
        {
            $requestData = $this->input->post();

            $columns = array(
                0 => "th_kode",
                1 => "th_insert_date",
                2 => "pasien_nama",
                3 => 'total'
            );

    		//count_data
    		$res_tot = $this->mm->count_all_data('v_th', NULL);
    		$tot_data = $res_tot->jml;

    		$order_by = $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];
    		$offset = $requestData['start'];
    		$limit = $requestData['length'];

    		//get_all_data
    		if( !empty($requestData['search']['value']) )
    		{
    			// if there is a search parameter, $requestData['search']['value'] contains search parameter
    			$where = "(th_kode LIKE '%".$requestData['search']['value']."%' OR ";
                $where .= "th_insert_date LIKE '%".$requestData['search']['value']."%' OR ";
                $where .= "pasien_nama LIKE '%".$requestData['search']['value']."%' OR ";
                $where .= "total LIKE '%".$requestData['search']['value']."%')";

    			$res = $this->mm->get_search_data('v_th', $where, $order_by, $limit, $offset);

    			$res_filtered_tot = $this->mm->count_all_data('v_th', $where);
    			$tot_filtered = $res_filtered_tot->jml;
    		}
    		else
    		{
    			$res = $this->mm->get_all_data('v_th', $order_by, $limit, $offset);
    			$tot_filtered = $tot_data;
    		}

    		$data = array();
            if(!empty($res))
            {
        		foreach($res as $row)
        		{
        			$random = rand();
        			$id = base64_encode($random."-".$row->th_id);
        			$edit = base64_encode($random."-edit");
        			$delete = base64_encode($random."-delete");

                    $date_insert = new DateTime($row->th_insert_date);

        			$nestedData = array();
        			$nestedData[] = strtoupper(trim($row->th_kode));
        			$nestedData[] = $date_insert->format('d-m-Y');
                    $nestedData[] = strtoupper(trim($row->pasien_nama));
                    $nestedData[] = number_format($row->total,0,',','.');
        			$nestedData[] = "
        			<button class=\"uk-button uk-button-danger\" onClick=\"delete_function('".$edit."','".$id."');\" type=\"button\" title=\"Delete\"><i class=\"uk-icon uk-icon-trash\"></i></button>";

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
            redirect('home');
        }
    }

    function edit_transaksi()
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

			if ($this->input->post("btn_simpan") === "btn_ubah")
			{
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
					redirect($this->modul."/index?m=".$msg."&a=".$alert);
				}
				else
				{
					$msg = base64_encode($rand."-Data tidak berhasil diubah");
					$alert = base64_encode($rand."-warning");
					redirect($this->modul."/index?m=".$msg."&a=".$alert);
				}
			}
			else
			{
				redirect($this->modul);
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
