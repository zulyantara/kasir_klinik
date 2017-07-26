<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laba_rugi extends CI_Controller
{
    /*
     * @author Zulyantara <zulyantara@gmail.com>
     */

    var $modul = "laba_rugi";

    function __construct()
    {
        parent::__construct();
        $this->_cek_login();
        $this->output->enable_profiler(FALSE);
        $this->load->model("laba_rugi_model", "lrm");
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

        $data["qry_ja"] = $this->lrm->get_jenis_akun();
        // $data["qry_jurnal"] = $this->lrm->get_data_laba_rugi($data);

        // $data["qry_transaksi"] = $this->lrm->get_data_transaksi($data);
        // $data["qry_pengeluaran"] = $this->lrm->get_data_pengeluaran($data);
        // $data["qry_payroll"] = $this->lrm->get_data_payroll($data);

        if ($this->input->post("btn_cetak") === "cetak_pdf")
        {
            $bulan = date("F",strtotime($this->input->post("opt_bulan")));

            $this->load->view($this->modul.'/laba_rugi_pdf', $data);
            $html = $this->output->get_output();
            $this->load->library('dompdf_gen');
            $this->dompdf->load_html($html);
            $this->dompdf->set_paper("A4");
            $this->dompdf->render();
            $this->dompdf->stream("laba_rugi_".$bulan.".pdf");
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
