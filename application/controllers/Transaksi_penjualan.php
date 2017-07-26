<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_penjualan extends CI_Controller
{
    /*
     * @author Zulyantara <zulyantara@gmail.com>
     */

    var $modul = "transaksi_penjualan";

    function __construct()
    {
        parent::__construct();
        $this->_cek_login();
        $this->output->enable_profiler(FALSE);
        $this->load->model("transaksi_penjualan_model", "jm");
    }

    function index()
    {
        $data["content"] = $this->modul."/home";
        $data["panel_title"] = $this->modul;
        $this->load->view("template/template", $data);
    }

    function cetak()
    {
        $data["bulan"] = $this->input->post("opt_bulan");

        $data["qry_tp"] = $this->jm->get_data_transaksi($data);

        if ($this->input->post("btn_cetak") === "cetak_pdf")
        {
            $bulan = $this->input->post("opt_bulan");

            $this->load->view($this->modul.'/transaksi_penjualan_pdf', $data);
            $html = $this->output->get_output();
            $this->load->library('dompdf_gen');
            $this->dompdf->load_html($html);
            $this->dompdf->set_paper("A4");
            $this->dompdf->render();
            $this->dompdf->stream("transaksi_penjualan.pdf");
        }
        else
        {
            $data["content"] = $this->modul."/cetak";
            $data["panel_title"] = $this->modul;
            $this->load->view("template/template", $data);
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
