<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Register_Model extends CI_Model {

    public function getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0) {
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

        return $query->result_array();
    }

    public function insertUserInformation($table, $fields, $debug_to_pass = 0) {
        if ($debug_to_pass)
            echo $this->db->last_query();

        $this->db->insert($table, $fields);
        return $this->db->insert_id();
    }

    public function updateInactiveUserFile($absolute_path, $user_id) {
        /* checking file is exists or not */
        if (!file_exists($absolute_path . "media/front/user-status/inactive-user")) {
            /* if not update the first inactive user to file */
            $deleted_user = array();
            $deleted_user[0] = $user_id;
        } else {
            /* getting all inactive user from file */
            $inactive_user = $this->read_file($absolute_path . "media/front/user-status/inactive-user");
            if (!in_array($user_id, $inactive_user)) {
                /* Adding new deleted user to file */
                array_push($inactive_user, $user_id);
            }
        }
        $this->write_file($absolute_path . "media/front/user-status/inactive-user", $inactive_user);
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'admin_model',
                'model_method_name' => 'updateDeletedUserFile',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }
    }

    public function read_file($file_path) {
        $file_content = file_get_contents($file_path);
        return unserialize($file_content);
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'admin_model',
                'model_method_name' => 'read_file',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }
    }

    public function write_file($file_path, $file_data) {
        #Opening the file for writing.
//                $file_boolean=true;
        $file_path = fopen($file_path, "write");
        #wrinting into file
        fwrite($file_path, serialize($file_data));
        #closing the file for writing.
        fclose($file_path);
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'admin_model',
                'model_method_name' => 'write_file',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }
    }

    public function getCurrentAddressDetils($user_id) {
        $this->db->select('ua.country_id,ua.state_id,ua.city_id,ua.zip_code,ua.address_line1,ua.address_line2,ua.address_picture,ua.latitude,ua.longitude');
        $this->db->from('mst_user_addresses_name as uan');
        $this->db->join('mst_user_addresses as ua', 'ua.address_name_id_fk = uan.address_name_id', 'inner');
        $this->db->where('uan.user_id_fk',$user_id);
        $this->db->where('uan.address_type_id_fk','2');
        $query = $this->db->get();
        return $query->result_array();
    }

}
