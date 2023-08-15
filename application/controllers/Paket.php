<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paket extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("supplier_model");
        $this->load->model("Paket_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Pembelian Stock",
            "dataAdmin" => $this->dataAdmin
        ];

		$this->load->view('header',$push);
		$this->load->view('paket',$push);
		$this->load->view('footer',$push);
    }

    public function detail($id = 0) {
        $query = $this->Paket_model->get($id);
        if($query->num_rows() > 0) {
            $response = $query->row_array();
            $response["items"] = $this->Paket_model->get_details($id)->result_array();
            echo json_encode($response);
        }
    }

	public function new()
	{

        $push = [
            "pageTitle" => "Tambah Pembelian Stock",
            "dataAdmin" => $this->dataAdmin,
            "suppliers" => $this->supplier_model->get()->result()
        ];

		$this->load->view('header',$push);
		$this->load->view('paket_compose',$push);
		$this->load->view('footer',$push);
    }

    public function json() {
        $this->load->model("datatables");
        $this->datatables->setSelect("paket.*,suppliers.name");
        $this->datatables->setTable("paket");
        $this->datatables->setJoin("suppliers","suppliers.id = paket.supplier_id","left");
        $this->datatables->setColumn([
            '<index>',
            '<get-kdpaket>',
            '<get-nama>',
            '[reformat_date=<get-date>]',
            '<get-name>',
            '[rupiah=<get-total>]',
            '<div class="text-center">
                <button type="button" class="btn btn-sm btn-warning btn-view" data-id="<get-id>"><i class="fa fa-eye"></i></button>
            </div>'
        ]);
        $this->datatables->setOrdering(["id","kdpaket","nama","date","name","total",NULL]);
        $this->datatables->setSearchField(["name","kdpaket","nama"]);
        $this->datatables->generate();
    }
    
    public function json_product() {
        $this->load->model("datatables");
        $this->datatables->setTable("products");
        $this->datatables->setColumn([
            '<get-name>',
            '<get-kditem>',
            '<div class="text-center"><button type="button" class="btn btn-warning btn-sm btn-choose" data-id="<get-id>" data-name="<get-name>" data-stock="<get-stock>"><i class="fa fa-check"></i></button></div>'
        ]);
        $this->datatables->setOrdering(["name","kditem",NULL]);
        $this->datatables->setWhere("type","sparepart");
        $this->datatables->setSearchField(["name","kditem"]);
        $this->datatables->generate();
    }
    
    public function create() {
        $data = json_decode($this->input->raw_input_stream,TRUE);

        //OR !$data['total'] *ini ditambahkan di belakang supplier id supaya total tidak boleh 0
        if(!$data['supplier_id']) {
            $response = [
                "status" => FALSE,
                "msg" => "Harap periksa kembali data anda"
            ];
        } else {
            $response = [
                "status" => TRUE,
                "msg" => "Data pembelian telah ditambahkan"
            ];
            
            $insertData = [
                "id" => NULL,
                "kdpaket" => $kdpaket,
                "nama" => $nama,
                "date" => date("Y-m-d H:i:s"),
                "total" => $data["total"],
                "supplier_id" => $data["supplier_id"]
            ];

            $Paket_id = $this->Paket_model->post($insertData);

            $items_batch = [];
            #$stock_batch = [];

            foreach($data["details"] as $detail) {
                $temp = array();
                $temp["id"] = NULL;
                $temp["Paket_id"] = $Paket_id;
                $temp["product_id"] = $detail["product_id"];
                $temp["price"] = $detail["price"];
                $temp["qty"] = $detail["qty"];

                #$tempStock = array();
                #$tempStock["id"] = $detail["product_id"];
                #$tempStock["stock"] = $detail["product_stock"] + $detail["qty"];

                $items_batch[] = $temp;
                #$stock_batch[] = $tempStock;
            }

            $this->Paket_model->post_details($items_batch);
            #$this->Paket_model->update_stock($stock_batch);
        }

        echo json_encode($response);
    }   

}
