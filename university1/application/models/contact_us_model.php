<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contact_Us_Model extends CI_Model {

    // common function for absolute path
    public function absolutePath($path = '') {
        $abs_path = str_replace('system/', $path, BASEPATH);
        //Add a trailing slash if it doesn't exist.
        $abs_path = preg_replace("//([^/])/*$//", "\\1/", $abs_path);
       
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'contact_us_model',
                'model_method_name' => 'absolutePath',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }
        return $abs_path;
    }

    public function insertContactUs($arr_fields) {
        $this->db->insert("mst_contact_us", $arr_fields);
        
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'contact_us_model',
                'model_method_name' => 'insertContactUs',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }
        
        return $this->db->insert_id();
    }

    public function getContactUs($table, $fields = '', $condition = '', $order_by = '', $limit = '', $debug = 0) {
        $str_sql = '';
        if (is_array($fields)) {  
        //$fields passed as array
            $str_sql.=implode(",", $fields);
        } elseif ($fields != "") {  
            //$fields passed as string
            $str_sql .= $fields;
        } else {
            $str_sql .= '*'; 
            //$fields passed blank
        }

        $this->db->select($str_sql, FALSE);

        if (is_array($condition)) {  //$condition passed as array
            if (count($condition) > 0) {
                foreach ($condition as $field_name => $field_value) {
                    if ($field_name != '' && $field_value != '') {
                        $this->db->where($field_name, $field_value);
                    }
                }
            }
        } else if ($condition != "") { //$condition passed as string
            $this->db->where($condition);
        }

        if ($limit != "")
            $this->db->limit($limit);//limit is not blank

        if (is_array($order_by)) {
            $this->db->order_by($order_by[0], $order_by[1]);  //$order_by is not blank
        } else if ($order_by != "") {
            $this->db->order_by($order_by);  //$order_by is not blank
        }


        $this->db->from($table);  //getting record from table name passed
        $query = $this->db->get();

        if ($debug) {
            die($this->db->last_query());
        }
        
         $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'contact_us_model',
                'model_method_name' => 'getContactUs',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }
        
        return $query->result_array();
    }

}