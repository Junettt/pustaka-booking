<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function laporan_buku()
    {
        $data['judul'] = 'Laporan Data Buku';
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['buku'] = $this->ModelBuku->getBuku()->result_array();
        $data['kategori'] = $this->ModelBuku->getKategori()->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('buku/laporan_buku', $data);
        $this->load->view('templates/footer');
    }
    public function cetak_laporan_buku()
    {
        $data['buku'] = $this->ModelBuku->getBuku()->result_array();
        $data['kategori'] = $this->ModelBuku->getKategori()->result_array();

        $this->load->view('buku/laporan_print_buku', $data);
    }
    public function laporan_buku_pdf()
    {
        // Load data from ModelBuku
        $data['buku'] = $this->ModelBuku->getBuku()->result_array();

        // Load Dompdf library
        $sroot = $_SERVER['DOCUMENT_ROOT'];
        include $sroot . "/pustaka-booking1/application/third_party/dompdf/autoload.inc.php";

        // Initialize Dompdf
        $dompdf = new Dompdf\Dompdf();

        // Load the view and pass the data
        $this->load->view('buku/laporan_pdf_buku', $data);

        // Get the output from the view and set paper size and orientation
        $html = $this->output->get_output();
        $paper_size = 'A4';
        $orientation = 'landscape';
        $dompdf->set_paper($paper_size, $orientation);

        // Convert HTML to PDF
        $dompdf->load_html($html);
        $dompdf->render();

        // Stream the generated PDF to the browser
        $dompdf->stream("laporan_data_buku.pdf", array('Attachment' => 0));
    }
    public function export_excel()
    {
        $data = array(
            'title' => 'Laporan Buku',
            'buku' => $this->ModelBuku->getBuku()->result_array()
        );
        $this->load->view('buku/export_excel_buku', $data);
    }

}