<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cms_Model extends CI_Model {

    function getCmsList() {
      $fields_to_pass = "A.cms_id,A.page_alias,B.page_title,B.page_content,A.status";        
        $this->db->select($fields_to_pass);
        $this->db->from('mst_cms as A');     
        $this->db->join('trans_cms as B','A.cms_id=B.cms_id','left');
        $this->db->order_by('A.cms_id', 'DESC');
        $this->db->group_by('A.cms_id');
        $query = $this->db->get();
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'cms_model',
                'model_method_name' => 'getCmsList',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }
        return $query->result_array();
    }

   function getCmsPageDetailsFront($condition) {
      $fields_to_pass = "A.cms_id,A.page_alias,B.page_title,B.page_content,A.status,B.page_seo_title_lang,B.page_meta_keyword,B.page_meta_description,B.page_title";        
        $this->db->select($fields_to_pass);
        $this->db->from('mst_cms as A');     
        $this->db->join('trans_cms as B','A.cms_id=B.cms_id','left');
		$this->db->where($condition);
       	$query = $this->db->get();
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'cms_model',
                'model_method_name' => 'getCmsList',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }
        return $query->result_array();
    }
 function getCmsPageDetails($cms_page_id) {

        $fields_to_pass = "A.cms_id,A.page_alias,T.page_title,T.page_content,A.status,T.page_meta_keyword,T.page_meta_description,T.page_seo_title_lang";
        $condition_to_pass = array("A.cms_id" => $cms_page_id);

        $this->db->select($fields_to_pass);
        $this->db->from('mst_cms as A');
        $this->db->join('trans_cms as T','T.cms_id = A.cms_id');
        $this->db->where($condition_to_pass);
        $this->db->order_by('A.cms_id', 'ASC');

        $query = $this->db->get();
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'cms_model',
                'model_method_name' => 'getCmsPageDetails',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    //function to get cms from the database if edit_id and lang_id black then it will return all reacords   

    public function getCMS($edit_id = '', $lang_id = '') {
        $this->db->select('mst_cms.*,cms.*');
        $this->db->from('mst_cms as mst_cms');
        $this->db->join('trans_cms as cms', 'mst_cms.cms_id = cms.cms_id', 'left');
        if ($edit_id != '') { 
        //if edit id not blank passed it will return all records
            $this->db->where("cms.cms_id", $edit_id);
        }

        if ($lang_id != '') {
            $this->db->where("cms.lang_id", $lang_id);
        } else {
            $this->db->where("cms.lang_id", 17);
        }
        $result = $this->db->get();
        
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'cms_model',
                'model_method_name' => 'getCMS',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }
        
        return $result->result_array();
    }

}