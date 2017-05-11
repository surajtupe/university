<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class State_Model extends CI_Model {

    function getStatesList() {
        $fields_to_pass = "*";
        $this->db->select($fields_to_pass);
        $this->db->from('mst_states as A');
        $this->db->join('trans_states_lang as state_lang', 'state_lang.state_id_fk=A.id', 'inner');
        $this->db->join('mst_countries as C', 'C.country_id=A.country', 'inner');
        $this->db->join('trans_country_lang as B', 'C.country_id=B.country_id_fk', 'inner');

        $this->db->where('state_lang.lang_id', '17');
        $this->db->group_by('state_lang.state_lang_id');
        $query = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'state_model',
                'model_method_name' => 'getStatesList',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    function getNamesList($state_name) {
        $condition_to_pass = array("state_name" => $state_name);
        $fields_to_pass = "A.id,state_lang.state_name";
        $this->db->select($fields_to_pass);
        $this->db->from('mst_states as A');
        $this->db->join('trans_states_lang as state_lang', 'state_lang.state_id_fk=A.id', 'inner');
        $this->db->where($condition_to_pass);
        $this->db->order_by('A.id', 'DESC');
        $query = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'state_model',
                'model_method_name' => 'getNamesList',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }
    
    function getStateDetailsByCountry($condition_to_pass) {
        $fields_to_pass = "A.id,state_lang.state_name";
        $this->db->select($fields_to_pass);
        $this->db->from('mst_states as A');
        $this->db->join('trans_states_lang as state_lang', 'state_lang.state_id_fk=A.id', 'inner');
        $this->db->where($condition_to_pass);
        $this->db->order_by('A.id', 'DESC');
        $query = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'state_model',
                'model_method_name' => 'getNamesList',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    function getStatePageDetails($state_id) {

        $fields_to_pass = "id,state_name,country";
        $condition_to_pass = array("id" => $state_id);
        $this->db->select($fields_to_pass);
        $this->db->from('mst_states as A');
        $this->db->join('trans_states_lang as state_lang', 'state_lang.state_id_fk=A.id', 'inner');
        $this->db->where($condition_to_pass);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'state_model',
                'model_method_name' => 'getStatePageDetails',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    function getCountriesPageNames() {

        $fields_to_pass = "country_id,country_name,iso";
        $this->db->select($fields_to_pass);
        $this->db->from('mst_countries A');
        $this->db->join('trans_country_lang as B', 'A.country_id=B.country_id_fk', 'inner');
        $this->db->order_by('country_id', 'ASC');
        $this->db->group_by('country_name', 'ASC');
        $this->db->where('lang_id', '17');
        $query = $this->db->get();
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'state_model',
                'model_method_name' => 'getCountriesPageNames',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    public function get_all_states($condition_to_pass='') {

        $this->db->select('*');
        $this->db->from('trans_states_lang');

        if ($condition_to_pass != '') {
            $this->db->where($condition_to_pass);
        }

        $this->db->order_by('state_lang_id DESC');



        $query = $this->db->get();
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'state_model',
                'method_name' => 'get_all_states',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
        return $query->result_array();
    }

    public function getAllStatesLang($state_lang_id, $lang_id = 17) {

        $this->db->select('*');
        $this->db->from('trans_states_lang');
        $this->db->where('state_lang_id', $state_lang_id);
        $this->db->where('lang_id', $lang_id);
        $this->db->order_by('state_lang_id DESC');
        $query = $this->db->get();
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'state_model',
                'method_name' => 'get_all_states',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
        return $query->result_array();
    }

    public function getAllStatesByStateIdAndLang($state_lang_id, $lang_id = 17) {

        $this->db->select('*');
        $this->db->from('trans_states_lang');
        $this->db->where('state_id_fk', $state_lang_id);
        $this->db->where('lang_id', $lang_id);
        $this->db->order_by('state_lang_id DESC');
        $query = $this->db->get();
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'state_model',
                'method_name' => 'get_all_states',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
        return $query->result_array();
    }

    public function getStateInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0) {
        $this->db->select('*');
        $this->db->from('trans_states_lang');

        if ($condition_to_pass != '') {
            $this->db->where($condition_to_pass);
        }
        $query = $this->db->get();
        $error = $this->db->_error_message();

        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'state_model',
                'method_name' => 'getStateInformation',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
        return $query->result_array();
    }

}
