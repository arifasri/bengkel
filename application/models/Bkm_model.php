<?php

class Bkm_model extends CI_Model {
    function post($data) {
        $this->db->insert("bkm",$data);
    }

    function delete($id) {
        $this->db->delete("bkm",['id' => $id]);
        return $this->db->affected_rows();
    }

    function put($id,$data) {
        $this->db->where("id",$id);
        $this->db->update("bkm",$data);
    }
    
    function get($id) {
        if($id) {
            return $this->db->get_where("bkm",["id" => $id]);
        } else {
            return $this->db->get("bkm");
        }
    }

    function get_details($id) {
        return $this->db->get_where("bkm",["id" => $id]);
    }
}