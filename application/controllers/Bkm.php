<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bkm extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("Coa_model");
        $this->load->model("Bkm_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Bukti Kas Masuk",
            "dataAdmin" => $this->dataAdmin,
            "coa" => $this->Coa_model->get()->result()
        ];

		$this->load->view('header',$push);
		$this->load->view('Bkm',$push);
		$this->load->view('footer',$push);
    }
    
    public function json() {
        $this->load->model("datatables");
        $this->datatables->setTable("bkm");
        $this->datatables->setColumn([
            '<index>',
            '<get-customer>',
            '<get-akunbkm>',
            '<get-uraian>',
            '[rupiah=<get-jumlah>]',
            '<get-date>',
            '<div class="text-center"><button type="button" class="btn btn-primary btn-sm btn-edit" data-id="<get-id>" data-customer="<get-customer>" data-akunbkm="<get-akunbkm>" data-uraian="<get-uraian>" data-jumlah="<get-jumlah>"><i class="fa fa-edit"></i></button>
            <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="<get-id>" data-uraian="<get-uraian>"><i class="fa fa-trash"></i></button>
            <a href="[base_url=Bkm/print/<get-id>]" class="btn btn-sm btn-primary"><i class="fa fa-print"></i></a>
            </div>'
        ]);
        $this->datatables->setOrdering(["id","customer","akunbkm","uraian","jumlah","date",NULL]);
        #$this->datatables->setWhere("type","sparepart");
        $this->datatables->setSearchField(["uraian","akunbkm"]);
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
        $akunbkm = $this->input->post("akunbkm");
        $uraian = $this->input->post("uraian");
        $jumlah = $this->input->post("jumlah");
        $date = $this->input->post("date");

        if(!$akunbkm) {
            $response['status'] = FALSE;
            $response['msg'] = "Periksa kembali data yang anda masukkan";
        } else {
            $insertData = [
                "id" => NULL,
                "customer"=> $customer,
                "akunbkm" => $akunbkm,
                "uraian" => $uraian,
                "jumlah" => $jumlah,
                "date" => $date
            ];

            $response['status'] = TRUE;

            if($action == "add") {
                $response['msg'] = "Data berhasil ditambahkan";
                $this->Bkm_model->post($insertData);
            } else {
                unset($insertData['id']);
                #unset($insertData['date']);

                $response['msg'] = "Data berhasil diedit";
                $this->Bkm_model->put($id,$insertData);
            }

        }

        echo json_encode($response);
    }

    function delete($id) {
        $response = [
            'status' => FALSE,
            'msg' => "Data gagal dihapus"
        ];

        if($this->Bkm_model->delete($id)) {
            $response = [
                'status' => TRUE,
                'msg' => "Data berhasil dihapus"
            ];
        }

        echo json_encode($response);
    }
    
    public function print($id = 0) {
        $query = $this->Bkm_model->get($id);
        if($query->num_rows() > 0) {
            $push["fetch"] = $query->row();
            $push["details"] = $this->Bkm_model->get_details($id)->result();

            $title = "Bukti Kas Masuk";

            $this->load->library("pdf");

            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->filename = $title;

            $this->pdf->load_view("Bkm_pdf",$push);
        }
    }
}