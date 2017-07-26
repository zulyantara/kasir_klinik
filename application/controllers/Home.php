<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
    /*
     * @author Zulyantara <zulyantara@gmail.com>
     */

    function __construct()
    {
        parent::__construct();
        $this->_cek_login();
        $this->output->enable_profiler(FALSE);
    }

	public function index()
	{
        $this->load->model("dashboard_model", "dm");
        // $data["qry_barang_habis"] = $this->dm->get_data_barang_habis();
        $data["qry_barang_habis"] = $this->dm->get_data_barang_habis();
        $data["qry_barang_limit"] = $this->dm->get_data_barang_limit();
        $data["qry_sum_transaksi"] = $this->dm->get_sum_transaksi();
        $data["content"] = "home";
		$this->load->view('template/template', $data);
	}

    private function _cek_login()
    {
        if( ! $this->session->userdata('isLoggedIn') OR $this->session->userdata('isLoggedIn') !== TRUE)
        {
            redirect("auth");
        }
    }
}
