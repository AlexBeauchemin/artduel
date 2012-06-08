<?php

class Categories extends CI_Model {

    function __construct(){
        $this->load->helper('cookie');
        parent::__construct();
    }

    function getAll(){
        $this->db->order_by('ord');
        $query = $this->db->get('categories');
        return $query->result();
    }

    function getName($id){
        $this->db->where('ID',$id);
        $query = $this->db->get('categories');
        return $query->row()->name;
    }

    function getCurrent($id = false){
        $categories = $this->getAll();
        if($this->input->cookie('artduel_category'))
            $id = $this->input->cookie('artduel_category');
        if($this->input->get('cat'))
            $id = $this->input->get('cat');
        if($id)
            return $this->getName($id);

        return false;

    }
}