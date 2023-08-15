<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bkk extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("Coa_model");
        $this->load->model("Bkk_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Bukti Kas Keluar",
            "dataAdmin" => $this->dataAdmin,
            "coa" => $this->Coa_model->get()->result() 
        ];

		$this->load->view('header',$push);
		$this->load->view('Bkk',$push);
		$this->load->view('footer',$push);
    }
    
    public function json() {
        $this->load->model("datatables");
        $this->datatables->setTable("bkk");
        $this->datatables->setColumn([
            '<index>',
            '<get-customer>',
            '<get-akunbkk>',
            '<get-uraian>',
            '[rupiah=<get-jumlah>]',
            '<get-date>',
            '<div class="text-center"><button type="button" class="btn btn-primary btn-sm btn-edit" data-id="<get-id>" data-customer="<get-customer>" data-akunbkk="<get-akunbkk>" data-uraian="<get-uraian>" data-jumlah="<get-jumlah>"><i class="fa fa-edit"></i></button>
            <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="<get-id>" data-uraian="<get-uraian>"><i class="fa fa-trash"></i></button>
            <a href="[base_url=Bkk/print/<get-id>]" class="btn btn-sm btn-primary"><i class="fa fa-print"></i></a>
            </div>'
        ]);
        $this->datatables->setOrdering(["id","customer","akunbkk","uraian","jumlah","date",NULL]);
        #$this->datatables->setWhere("type","sparepart");
        $this->datatables->setSearchField(["uraian","akunbkk"]);
        $this->datatables->generate();
    }

    function insert() {
        $this->process();
    }

    function update($id) {
        $this->process("edit",$id);
    }

    private function process($action = "add",$id = 0) {
        $customer = $this->input->post("customer");
        $akunbkk = $this->input->post("akunbkk");
        $uraian = $this->input->post("uraian");
        $jumlah = $this->input->post("jumlah");
        $date = $this->input->post("date");

        if(!$akunbkk) {
            $response['status'] = FALSE;
            $response['msg'] = "Periksa kembali data yang anda masukkan";
        } else {
            $insertData = [
                "id" => NULL,
                "customer"=> $customer,
                "akunbkk" => $akunbkk,
                "uraian" => $uraian,
                "jumlah" => $jumlah,
                "date" => $date
            ];

            $response['status'] = TRUE;

            if($action == "add") {
                $response['msg'] = "Data berhasil ditambahkan";
                $this->Bkk_model->post($insertData);
            } else {
                unset($insertData['id']);
                #unset($insertData['date']);

                $response['msg'] = "Data berhasil diedit";
                $this->Bkk_model->put($id,$insertData);
            }

        }

        echo json_encode($response);
    }

    function delete($id) {
        $response = [
            'status' => FALSE,
            'msg' => "Data gagal dihapus"
        ];

        if($this->Bkk_model->delete($id)) {
            $response = [
                'status' => TRUE,
                'msg' => "Data berhasil dihapus"
            ];
        }

        echo json_encode($response);
    }
    
    public function print($id = 0) {
        $query = $this->Bkk_model->get($id);
        if($query->num_rows() > 0) {
            $push["fetch"] = $query->row();
            $push["details"] = $this->Bkk_model->get_details($id)->result();

            $title = "Bukti Kas Masuk";

            $this->load->library("pdf");

            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->filename = $title;

            $this->pdf->load_view("Bkk_pdf",$push);
        }
    }
}