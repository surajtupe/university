<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Countries_Model extends CI_Model {

    function getCountriesList() {
        $fields_to_pass = "A.country_id,A.mobile_number_length,A.trunk_code,B.country_name,A.iso,A.country_phone_code,A.flag";
        $this->db->select($fields_to_pass);
        $this->db->from('mst_countries as A');
        $this->db->join('trans_country_lang as B', 'A.country_id=B.country_id_fk', 'left');
        $this->db->group_by('B.country_name', 'ASC');
        $this->db->where('B.lang_id', '17');
        $query = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'countries_model',
                'model_method_name' => 'getCountriesList',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }
    function getCountriesDetailsISO($country_iso) {
        $fields_to_pass = "A.country_id,A.mobile_number_length,A.trunk_code,B.country_name,A.iso,A.country_phone_code,A.flag";
        $this->db->select($fields_to_pass);
        $this->db->from('mst_countries as A');
        $this->db->join('trans_country_lang as B', 'A.country_id=B.country_id_fk', 'left');
        $this->db->where('B.lang_id', '17');
        $this->db->where('iso', $country_iso);
        $this->db->group_by('B.country_name', 'ASC');
        $query = $this->db->get();
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'countries_model',
                'model_method_name' => 'getCountriesList',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    function getCountriesRegionList() {
        $fields_to_pass = "A.country_id,B.country_name,A.iso";
        $this->db->select($fields_to_pass);
        $this->db->from('mst_countries as A');
        $this->db->join('trans_country_lang as B', 'A.country_id=B.country_id_fk', 'left');
        $this->db->group_by('A.country_name', 'DESC');
        $query = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'countries_model',
                'model_method_name' => 'getCountriesRegionList',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    function getCountriesCityList() {
        $fields_to_pass = "A.country_id,B.country_name,A.iso";
        $this->db->select($fields_to_pass);
        $this->db->from('mst_countries as A');
        $this->db->join('trans_country_lang as B', 'A.country_id=B.country_id_fk', 'left');
        $this->db->group_by('A.country_id', 'DESC');
        $query = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'countries_model',
                'model_method_name' => 'getCountriesCityList',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    function getNamesList($country_name) {
        $condition_to_pass = array("country_name" => $country_name);
        $fields_to_pass = "A.country_id,B.country_name,A.iso";
        $this->db->select($fields_to_pass);
        $this->db->from('mst_countries as A');
        $this->db->join('trans_country_lang as B', 'A.country_id=B.country_id_fk', 'left');
        $this->db->where($condition_to_pass);
        $this->db->order_by('A.country_id', 'DESC');
        $query = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'countries_model',
                'model_method_name' => 'getNamesList',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    function checkCountryISO($country_name) {
        $condition_to_pass = array("iso" => $country_name);
        $fields_to_pass = "A.country_id,A.iso";
        $this->db->select($fields_to_pass);
        $this->db->from('mst_countries as A');
        $this->db->where($condition_to_pass);
        $query = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'countries_model',
                'model_method_name' => 'getNamesList',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    function getCountriesPageDetails($country_id) {
        $fields_to_pass = "A.country_id,B.country_name,A.iso,A.country_phone_code,A.flag";
        $condition_to_pass = array("A.country_id" => $country_id);
        $this->db->select($fields_to_pass);
        $this->db->from('mst_countries as A');
        $this->db->join('trans_country_lang as B', 'A.country_id=B.country_id_fk', 'left');
        $this->db->where($condition_to_pass);
        $this->db->order_by('A.country_id', 'ASC');
        $query = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'countries_model',
                'model_method_name' => 'getCountriesPageDetails',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    public function get_all_country($condition_to_pass) {

        $this->db->select('*');
        $this->db->from('trans_country_lang');

        if ($condition_to_pass != '') {
            $this->db->where($condition_to_pass);
        }

        $this->db->order_by('country_lang_id DESC');



        $query = $this->db->get();
        // $query = $this->db->get_where('mst_category', $arr);
        /*         * * this is to print error message ** */
        $error = $this->db->_error_message();

        /*         * * this is to print number ** */
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'countries_model',
                'method_name' => 'get_all_country',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
        return $query->result_array();
    }

    public function getAllCountriesLang($condition_to_pass, $lang_id = '17') {

        $this->db->select('*');
        $this->db->from('trans_country_lang');
        $this->db->where('country_lang_id', $condition_to_pass);
        $this->db->where('lang_id', $lang_id);
        $this->db->order_by('country_lang_id DESC');



        $query = $this->db->get();
        // $query = $this->db->get_where('mst_category', $arr);
        /*         * * this is to print error message ** */
        $error = $this->db->_error_message();

        /*         * * this is to print number ** */
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'countries_model',
                'method_name' => 'get_all_country',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
        return $query->result_array();
    }

    public function getAllCountriesByLangAndCountryId($condition_to_pass, $lang_id = '17') {

        $this->db->select('*');
        $this->db->from('trans_country_lang');
        $this->db->where('country_id_fk', $condition_to_pass);
        $this->db->where('lang_id', $lang_id);
        $this->db->order_by('country_lang_id DESC');



        $query = $this->db->get();
        // $query = $this->db->get_where('mst_category', $arr);
        /*         * * this is to print error message ** */
        $error = $this->db->_error_message();

        /*         * * this is to print number ** */
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'countries_model',
                'method_name' => 'get_all_country',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
        return $query->result_array();
    }

    public function getCountryInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0) {
        $this->db->select('*');
        $this->db->from('trans_country_lang');

        if ($condition_to_pass != '') {
            $this->db->where($condition_to_pass);
        }
        $query = $this->db->get();
        // $query = $this->db->get_where('mst_category', $arr);
        /*         * * this is to print error message ** */
        $error = $this->db->_error_message();

        /*         * * this is to print number ** */
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'countries_model',
                'method_name' => 'getCountryInformation',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
        return $query->result_array();
    }

    function getCountriesByISO($condition_to_pass) {
        $fields_to_pass = "A.country_id,B.country_name,A.iso,A.country_phone_code,A.flag";
        $this->db->select($fields_to_pass);
        $this->db->from('mst_countries as A');
        $this->db->join('trans_country_lang as B', 'A.country_id=B.country_id_fk', 'left');
        $this->db->where($condition_to_pass);
        $query = $this->db->get();
        return $query->result_array();
    }

}
