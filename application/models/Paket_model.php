<?php
class Paket_model extends CI_Model {
    function post($data) {
        $this->db->insert("paket",$data);
        return $this->db->insert_id();
    }

    function post_details($data) {
        $this->db->insert_batch("paket_details",$data);
    }

    function update_stock($data) {
        $this->db->update_batch("products",$data,"id");
    }

    function get($id = 0) {
        $this->db->select("paket.*,suppliers.name,suppliers.address,suppliers.telephone");
        $this->db->join("paket","suppliers.id = paket.supplier_id","left");
        if($id) {
            return $this->db->get_where("paket",["paket.id" => $id]);
        } else {
            return $this->db->get("paket");
        }
    }

    function get_details($id) {
        $this->db->select("paket_details.*,products.name");
        $this->db->join("products","products.id = paket_details.product_id","left");
        return $this->db->get_where("paket_details",["paket_id" => $id]);
    }

    function put($id,$data) {
        $this->db->where("id",$id);
        $this->db->update("paket",$data);
    }

    function delete($id) {
        $this->db->delete("paket",['id' => $id]);
        return $this->db->affected_rows();
    }
}
