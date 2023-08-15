<?php

class Bkk_model extends CI_Model {
    function post($data) {
        $this->db->insert("bkk",$data);
    }

    function delete($id) {
        $this->db->delete("bkk",['id' => $id]);
        return $this->db->affected_rows();
    }

    function put($id,$data) {
        $this->db->where("id",$id);
        $this->db->update("bkk",$data);
    }
    
    function get($id) {
        if($id) {
            return $this->db->get_where("bkk",["id" => $id]);
        } else {
            return $this->db->get("bkk");
        }
    }

    function get_details($id) {
        return $this->db->get_where("bkk",["id" => $id]);
    }
}