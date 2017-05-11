<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class City_Model extends CI_Model {

    // common function for absolute path
    public function absolutePath($path = '') {

        $abs_path = str_replace('system/', $path, BASEPATH);
        //Add a trailing slash if it doesn't exist.
        $abs_path = preg_replace("#([^/])/*$#", "\\1/", $abs_path);
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
                'model_name' => 'city_model',
                'method_name' => 'absolutePath',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
        return $abs_path;
    }

    public function get_all_city($condition_to_pass) {

        $this->db->select('city_lang.city_id_lang,city_lang.city_name,B.country_name,state_lang.state_name,city_id');
        $this->db->from('mst_cities as city');
        $this->db->join('trans_cities_lang as city_lang', 'city_lang.city_id_fk=city.city_id');
        $this->db->join('mst_countries as C', 'C.country_id=city.country_id_fk', 'inner');
        $this->db->join('trans_country_lang as B', 'C.country_id=B.country_id_fk', 'inner');
        $this->db->join('mst_states as state', 'state.id=city.state_id_fk', 'inner');
        $this->db->join('trans_states_lang as state_lang', 'state_lang.state_id_fk=state.id', 'inner');
        $this->db->where('city_lang.lang_id', '17');
        $this->db->group_by('city_id_lang');
        if ($condition_to_pass != '') {
            $this->db->where($condition_to_pass);
        }
        $this->db->order_by('city_id_lang DESC');
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
                'model_name' => 'city_model',
                'method_name' => 'get_all_city',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
        return $query->result_array();
    }

    public function checkforCityExists($condition_to_pass) {
        $this->db->select('city_name');
        $this->db->from('mst_cities as cities');
        $this->db->join('trans_cities_lang as city_lang', 'city_lang.city_id_fk=cities.city_id', 'inner');
        if ($condition_to_pass != '') {
            $this->db->where($condition_to_pass);
        }
        $query = $this->db->get();
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
                'model_name' => 'city_model',
                'method_name' => 'checkforCityExists',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
        return $query->result_array();
    }

    public function getStateDetailsByCountry($condition_to_pass) {
        $this->db->select('state_lang.state_name,state.id,state.id as state_id');
        $this->db->from('mst_states as state');
        $this->db->join('trans_states_lang as state_lang', 'state_lang.state_id_fk=state.id', 'left');
        if ($condition_to_pass != '') {
            $this->db->where($condition_to_pass);
        }
        $this->db->group_by('state_lang.state_name', 'ASC');
        $query = $this->db->get();
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
                'model_name' => 'city_model',
                'method_name' => 'get_all_city',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
        $this->db->order_by('state_lang.state_name', 'ASC');
        return $query->result_array();
    }

    public function getAllLanguages() {

        $this->db->select('*');
        $this->db->from('mst_languages');
        $this->db->where('lang_id <>', '17');
        $this->db->where('status', 'A');

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
                'model_name' => 'city_model',
                'method_name' => 'getAllLanguages',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
        return $query->result_array();
    }

    public function get_all_city_by($city_lang_id) {

        $this->db->select('*');
        $arr = array("city_id_lang" => $city_lang_id);
        $query = $this->db->get_where('trans_cities_lang', $arr);
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
                'model_name' => 'city_model',
                'method_name' => 'get_all_city_by',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
        return $query->result_array();
    }

    public function getCityInformation($table_to_pass='', $fields_to_pass='', $condition_to_pass='', $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0) {
        $this->db->select('*');
        $this->db->from('trans_cities_lang');

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
                'model_name' => 'city_model',
                'method_name' => 'getCityInformation',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
        return $query->result_array();
    }

    public function update_city($update_data, $city_lang_id) {

        $this->db->where("city_id_lang", $city_lang_id);
        $this->db->update("trans_cities_lang", $update_data);
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
                'model_name' => 'city_model',
                'method_name' => 'update_city',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
    }

    public function updateCityDetails($table, $update_data, $condition) {


        if (is_array($condition)) {
            if (count($condition) > 0) {
                foreach ($condition as $field_name => $field_value) {
                    if ($field_name != '' && $field_value != '' && $field_value != NULL) {
                        $this->db->where($field_name, $field_value);
                    }
                }
            }
        } else if ($condition != "" && $condition != NULL) {
            $this->db->where($condition);
        }
        $this->db->update($table, $update_data);

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
                'model_name' => 'city_model',
                'method_name' => 'update_city',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
    }

    public function insertCityId($arr_fields) {

        $this->db->insert("mst_cities", $arr_fields);
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
                'model_name' => 'city_model',
                'method_name' => 'insertCityId',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
        return $this->db->insert_id();
    }

    public function insertCity($arr_fields) {

        $this->db->insert("trans_cities_lang", $arr_fields);
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
                'model_name' => 'city_model',
                'method_name' => 'insertCity',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
        return $this->db->insert_id();
    }

    function getCountriesPageNames() {

        $fields_to_pass = "country_id,country_name,iso";
        $this->db->select($fields_to_pass);
        $this->db->from('mst_countries A');
        $this->db->join('trans_country_lang as B', 'A.country_id=B.country_id_fk', 'inner');
        $this->db->order_by('country_id', 'ASC');
        $this->db->group_by('country_name', 'ASC');
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

    public function getStateInfo($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0) {
        $this->db->select($fields_to_pass, FALSE);
        $this->db->from($table_to_pass);
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
                'model_name' => 'cities_model',
                'model_method_name' => 'getStateInfo',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    function getCitiesPageDetails($city_id) {
        $fields_to_pass = "A.city_id,B.city_name,A.country_id_fk,A.state_id_fk,B.city_id_lang";
        $condition_to_pass = array("A.city_id" => $city_id);
        $this->db->select($fields_to_pass);
        $this->db->from('mst_cities as A');
        $this->db->join('trans_cities_lang as B', 'A.city_id=B.city_id_fk', 'left');
        $this->db->where($condition_to_pass);
        $this->db->order_by('A.city_id', 'ASC');
        $query = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'cities_model',
                'model_method_name' => 'getCitiesPageDetails',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    function getCitiesByState($condition_to_pass) {
        $fields_to_pass = "A.city_id,B.city_name,A.country_id_fk,A.state_id_fk,B.city_id_lang";
        $this->db->select($fields_to_pass);
        $this->db->from('mst_cities as A');
        $this->db->join('trans_cities_lang as B', 'A.city_id=B.city_id_fk', 'left');
        $this->db->where($condition_to_pass);
        $this->db->order_by('A.city_id', 'ASC');
        $query = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'cities_model',
                'model_method_name' => 'getCitiesPageDetails',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    function getCitiesByStateWebService($condition_to_pass) {
        $fields_to_pass = "A.country_id_fk,A.state_id_fk,A.city_id,B.city_name";
        $this->db->select($fields_to_pass);
        $this->db->from('mst_cities as A');
        $this->db->join('trans_cities_lang as B', 'A.city_id=B.city_id_fk', 'left');
        $this->db->where($condition_to_pass);
        $this->db->order_by('B.city_name', 'ASC');
        $query = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'cities_model',
                'model_method_name' => 'getCitiesPageDetails',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

}
