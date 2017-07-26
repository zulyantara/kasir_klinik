<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Obat extends CI_Controller
{
    /*
     * @author Zulyantara <zulyantara@gmail.com>
     */

    var $modul = "obat";
    var $table = "barang";
    var $v_table = "v_barang";
    var $pk = "barang_id";

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
            0 => "barang_kode",
            1 => "barang_nama",
            2 => "jo_ket",
            3 => "barang_jumlah",
            4 => "barang_limit",
            5 => "barang_harga"
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
			$where = "(barang_kode LIKE '%".$requestData['search']['value']."%' OR ";
            $where .= "barang_nama LIKE '%".$requestData['search']['value']."%' OR ";
            $where .= "jo_ket LIKE '%".$requestData['search']['value']."%' OR ";
            $where .= "barang_jumlah LIKE '%".$requestData['search']['value']."%' OR ";
            $where .= "barang_limit LIKE '%".$requestData['search']['value']."%' OR ";
            $where .= "barang_harga LIKE '%".$requestData['search']['value']."%')";

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
    			$id = base64_encode($random."-".$row->barang_id);
    			$edit = base64_encode($random."-edit");
    			$delete = base64_encode($random."-delete");

                $str_stok = $row->barang_jumlah;
                $str_limit = $row->barang_limit;
                $str_jml = $str_stok;
                if(($str_stok - $str_limit <= 5) && ($str_stok - $str_limit > 0))
                {
                    $str_jml = "<span class=\"uk-text-warning uk-text-bold\">".$str_stok."</span>";
                }
                elseif ($str_stok <= $str_limit)
                {
                    $str_jml = "<span class=\"uk-text-danger uk-text-bold\">".$str_stok."</span>";
                }

    			$nestedData = array();
    			$nestedData[] = strtoupper(trim($row->barang_kode));
    			$nestedData[] = strtoupper(trim($row->barang_nama));
                $nestedData[] = strtoupper(trim($row->jo_ket));
                $nestedData[] = trim($str_jml);
                $nestedData[] = trim($row->barang_limit);
                $nestedData[] = "IDR<span class=\"uk-float-right\">".trim(number_format($row->barang_harga,0,',','.'))."</span>";
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
    			redirect($this->modul."/index?m=".$msg."&a=".$alert);
    		}
    		else
    		{
    			redirect($this->modul);
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

            $this->form_validation->set_rules('opt_barang_jo', 'Jenis Obat', 'required');
            $this->form_validation->set_rules('opt_barang_kb', 'Kelompok Obat', 'required');
    		$this->form_validation->set_rules('txt_barang_nama', 'Nama', 'required');
            $this->form_validation->set_rules('opt_barang_sb', 'Satuan', 'required');
            $this->form_validation->set_rules('txt_barang_jumlah', 'Jumlah', 'required');
            $this->form_validation->set_rules('txt_barang_limit', 'Limit', 'required');
            $this->form_validation->set_rules('txt_barang_harga', 'Harga', 'required');

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

    				$data["qry_barang"] = $this->mm->get_data_by_id($this->pk,$id_arr[1],$this->table);
    			}

                $data["qry_kb"] = $this->mm->get_all_data("kelompok_barang", 'kb_id','10000');
                $data["qry_jo"] = $this->mm->get_all_data("jenis_obat", 'jo_id','10000');
                $data["qry_sb"] = $this->mm->get_all_data("satuan_barang", 'sb_id','10000');
    			$data["content"] = $this->modul."/form";
    			$data["panel_title"] = $this->modul;
    			$this->load->view('template/template', $data);
    		}
    		else
    		{
                $user_id = $this->session->userdata("isLoggedIn") === TRUE ? $this->session->userdata("userId") : 0;
                $barang_kode = strtoupper($this->input->post("txt_barang_kode"));
                $barang_jo = $this->input->post("opt_barang_jo");
                $barang_kb = $this->input->post("opt_barang_kb");
                $barang_nama = strtoupper($this->input->post("txt_barang_nama"));
                $barang_ket = $this->input->post("txt_barang_ket");
                $barang_sb = $this->input->post("opt_barang_sb");
                $barang_jumlah = $this->input->post("txt_barang_jumlah");
                $barang_limit = $this->input->post("txt_barang_limit");
                $barang_harga = str_replace(",","",$this->input->post("txt_barang_harga"));
                $barang_harga_beli = str_replace(",","",$this->input->post("txt_barang_harga_beli"));

                // bikin kode otomatis
                $kode_jo = $this->mm->get_data_by_id('jo_id', $barang_jo, 'jenis_obat');
                $kode_kb = $this->mm->get_data_by_id('kb_id', $barang_kb, 'kelompok_barang');

                $kode_barang = $kode_jo->jo_kode."-".$kode_kb->kb_kode."-";

                $lkb = $this->mm->get_last_kode('barang', 'barang_kode', "barang_kode LIKE '".$kode_barang."%'", 'barang_kode');
                if ($lkb !== FALSE)
                {
                    $no_urut = substr($lkb->barang_kode,-4)+1;
                    if (strlen($no_urut) == 1)
                    {
                        $nu = '000'.$no_urut;
                    }
                    elseif (strlen($no_urut) == 2)
                    {
                        $nu = '00'.$no_urut;
                    }
                    elseif (strlen($no_urut) == 3)
                    {
                        $nu = '0'.$no_urut;
                    }
                    else
                    {
                        $nu = $no_urut;
                    }
                }
                else
                {
                    $nu = '0001';
                }

                $kode_barang .= $nu;
                // echo $kode_barang;exit;

    			if($this->input->post("btn_simpan") === "btn_simpan")
    			{
    				$field[] = "barang_kode,";
                    $field[] = "barang_jenis,";
                    $field[] = "barang_kelompok,";
                    $field[] = "barang_nama,";
                    $field[] = "barang_ket,";
                    $field[] = "barang_satuan,";
                    $field[] = "barang_jumlah,";
                    $field[] = "barang_limit,";
                    $field[] = "barang_harga,";
                    $field[] = "barang_harga_beli,";
    				$field[] = "barang_insert_date,";
    				$field[] = "barang_insert_user";

    				$data[] = "'".trim($kode_barang)."',";
                    $data[] = trim($barang_jo).",";
                    $data[] = trim($barang_kb).",";
                    $data[] = "'".trim($barang_nama)."',";
                    $data[] = "'".trim($barang_ket)."',";
                    $data[] = trim($barang_sb).",";
                    $data[] = trim($barang_jumlah).",";
                    $data[] = trim($barang_limit).",";
                    $data[] = trim($barang_harga).",";
                    $data[] = trim($barang_harga_beli).",";
                    $data[] = "'".date("Y-m-d H:i:s")."',";
    				$data[] = "".$user_id;

    				//cek jika ada data yang sama
    				$cekdata="barang_kode LIKE '".$barang_kode."'";
    				$cek_barang = $this->mm->check_duplicate($this->table ,$cekdata);

    				if($cek_barang->jml === "0")
    				{
    					// kirim data
    					$insert_data = $this->mm->insert_data($this->table, $field, $data);
    					if($insert_data === 1)
    					{
    						$rand = rand();
    						$msg = base64_encode($rand."-Data berhasil ditambah");
    						$alert = base64_encode($rand."-success");
    						redirect($this->modul."/index?m=".$msg."&a=".$alert);
    					}
    					else
    					{
    						$msg = base64_encode($rand."-Data tidak berhasil ditambah");
    						$alert = base64_encode($rand."-warning");
    						redirect($this->modul."/index?m=".$msg."&a=".$alert);
    					}
    				}
    				else
    				{
    					?>
    					<script type="text/javascript">
    					alert("Barang sudah ada");
    					window.history.back();
    					</script>
    					<?php
    				}
    			}
    			elseif ($this->input->post("btn_simpan") === "btn_ubah")
    			{
    				$barang_id = "'".$this->input->post("txt_barang_id")."'";
    				$data["barang_kode"] = "'".trim($kode_barang)."',";
                    $data["barang_jenis"] = trim($barang_jo).",";
                    $data["barang_kelompok"] = trim($barang_kb).",";
                    $data["barang_nama"] = "'".trim($barang_nama)."',";
                    $data["barang_ket"] = "'".trim($barang_ket)."',";
                    $data["barang_satuan"] = trim($barang_sb).",";
                    $data["barang_jumlah"] = trim($barang_jumlah).",";
                    $data["barang_limit"] = trim($barang_limit).",";
                    $data["barang_harga"] = trim($barang_harga).",";
                    $data["barang_harga_beli"] = trim($barang_harga_beli).",";
    				$data["barang_update_date"] = "'".date("Y-m-d H:i:s")."',";
                    $data["barang_update_user"] = $user_id;

    				// kirim data
    				$update_data = $this->mm->update_data($this->table, $this->pk, $barang_id, $data);
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
	}

    function print_stok()
    {
        $data["qry_barang"] = $this->mm->get_all_data($this->v_table,"jo_ket, barang_nama ASC",10000);
        $this->load->view($this->modul.'/print_stok', $data);
        $html = $this->output->get_output();
        $this->load->library('dompdf_gen');
        $this->dompdf->load_html($html);
        $this->dompdf->set_paper("A4");
        $this->dompdf->render();
        $this->dompdf->stream("Laporan_Stok.pdf");
    }

    private function _cek_login()
    {
        if( ! $this->session->userdata('isLoggedIn') OR $this->session->userdata('isLoggedIn') !== TRUE)
        {
            redirect("auth");
        }
    }
}
