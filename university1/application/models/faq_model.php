<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 *  Class will do all necessary action for blog functionalities
 */

class FAQ_Model extends CI_Model {

    //funcion to get all FAQ categories with condition
    public function getCategories($fields = '', $condition_to_pass = '', $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0) {
        if ($fields == '') {
            $fields = "*";
        }

        $this->db->select($fields, FALSE);

        $this->db->from("mst_faq_categories");

        if ($condition_to_pass != '')
            $this->db->where($condition_to_pass);


        if ($order_by_to_pass != '')
            $this->db->order_by($order_by_to_pass);


        if ($limit_to_pass != '')
            $this->db->limit($limit_to_pass);

        $query = $this->db->get();

        if ($debug_to_pass)
            echo $this->db->last_query();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'faq_model',
                'model_method_name' => 'getCategories',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }
        return $query->result_array();
    }

    //funcion to get all FAQs with condition
    public function getFAQS($fields = '', $condition_to_pass = '', $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0) {
        if ($fields == '')
            $fields = "f.*,lang.lang_name,(select title from " . $this->db->dbprefix('mst_faq_categories') . " fc where fc.category_id=f.category_id) as category_name";

        $this->db->select($fields, FALSE);

        $this->db->from("mst_faqs as f");
        $this->db->join("mst_languages as lang", 'lang.lang_id=f.lang_id', 'inner');
        if ($condition_to_pass != '')
            $this->db->where($condition_to_pass);

        if ($order_by_to_pass != '')
            $this->db->order_by($order_by_to_pass);


        if ($limit_to_pass != '')
            $this->db->limit($limit_to_pass, $debug_to_pass);

        $query = $this->db->get();


//        if ($debug_to_pass)
//            echo $this->db->last_query();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'faq_model',
                'model_method_name' => 'getFAQS',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    public function getFAQSearchTags($fields ,$condition) {
        if ($fields == '')
            $fields = "f.*,lang.lang_name,(select title from " . $this->db->dbprefix('mst_faq_categories') . " fc where fc.category_id=f.category_id) as category_name";
        $this->db->select($fields, FALSE);
        $this->db->from("mst_faqs as f");
        $this->db->join("mst_languages as lang", 'lang.lang_id=f.lang_id', 'inner');
        if ($condition != '') {
            $this->db->where($condition.' !=','0');
            $this->db->where('f.status','Active');
        }
         $this->db->order_by('f.faq_id desc');
        $query = $this->db->get();
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'faq_model',
                'model_method_name' => 'getFAQS',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }
        return $query->result_array();
    }

}
