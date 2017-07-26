<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal extends CI_Controller
{
    /*
     * @author Zulyantara <zulyantara@gmail.com>
     */

    var $modul = "jurnal";

    function __construct()
    {
        parent::__construct();
        $this->_cek_login();
        $this->output->enable_profiler(FALSE);
        $this->load->model("jurnal_model", "jm");
    }

    function index()
    {
        $data["content"] = $this->modul."/home";
        $data["panel_title"] = $this->modul;
        $this->load->view("template/template", $data);
    }

    function cetak()
    {
        $data["tgl_1"] = $this->input->post("txt_tgl_1");
        $data["tgl_2"] = $this->input->post("txt_tgl_2");

        $data["qry_jurnal"] = $this->jm->get_data_jurnal($data);
        // $data["qry_transaksi"] = $this->jm->get_data_transaksi($data);
        // $data["qry_pengeluaran"] = $this->jm->get_data_pengeluaran($data);
        // $data["qry_payroll"] = $this->jm->get_data_payroll($data);

        if ($this->input->post("btn_cetak") === "cetak_pdf")
        {
            $tgl_1 = $this->input->post("txt_tgl_1");
            $tgl_2 = $this->input->post("txt_tgl_2");

            $this->load->view('jurnal/jurnal_pdf', $data);
            $html = $this->output->get_output();
            $this->load->library('dompdf_gen');
            $this->dompdf->load_html($html);
            $this->dompdf->set_paper("A4");
            $this->dompdf->render();
            $this->dompdf->stream("jurnal_".$tgl_1."_".$tgl_2.".pdf");
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
