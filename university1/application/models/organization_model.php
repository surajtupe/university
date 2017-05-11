<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Organization_Model extends CI_Model {

    public function getDocumentList($condition_to_pass) {
        $this->db->select('*');
        $this->db->from('mst_documents as d');
        $this->db->join('trans_document_lang as dl', 'd.document_id = dl.document_id_fk', 'inner');
        if ($condition_to_pass != '')
            $this->db->where($condition_to_pass);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getOrganizationDetials($condition_to_pass) {
        $this->db->select('*');
        $this->db->from('mst_orgnisational_details as od');
        $this->db->join('mst_service_categories as sc', 'sc.service_category_id = od.service_category_id_fk', 'inner');
        $this->db->join('trans_service_category_lang as scl', 'scl.category_id_fk = sc.service_category_id', 'inner');
        $this->db->join('trans_service_catgory_charges as scc', 'scc.organisation_id_fk = od.orgnisation_id', 'left');
        if ($condition_to_pass != '')
            $this->db->where($condition_to_pass);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getSeviceRequiredDocument($condition_to_pass){
        $this->db->select('*');
        $this->db->from('trans_service_required_documents as rd');
        $this->db->join('mst_documents as d','d.document_id = rd.document_id_fk','inner');
        $this->db->join('trans_document_lang as dl', 'd.document_id = dl.document_id_fk', 'inner');
        if ($condition_to_pass != '')
            $this->db->where($condition_to_pass);
        $query = $this->db->get();
        return $query->result_array();
    }
}
