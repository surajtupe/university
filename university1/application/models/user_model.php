<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_Model extends CI_Model {

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

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'user_model',
                'model_method_name' => 'getUserInformation',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    #function to get user list from the database

    public function getUserDetails() {
        $this->db->select('*');
        $this->db->from('mst_users');
        $this->db->where("user_type", 1);
        $this->db->order_by("user_id", 'DESC');
        $result = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'user_model',
                'model_method_name' => 'getUserDetails',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $result->result_array();
    }

    public function getUserDetailsByID($user_id = 0) {

        $this->db->select('*');
        $this->db->from('mst_users as u');
        $this->db->where("u.user_id", $user_id);
        $result = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'user_model',
                'model_method_name' => 'getUserDetailsByID',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $result->result_array();
    }

    public function updateRow($table_name, $update_data, $condition) {
        $this->common_model->updateRow('mst_users', $update_data, $condition);

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'user_model',
                'model_method_name' => 'updateRow',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return true;
    }

    public function getUserSubscription($condition) {

        $this->db->select('*');
        $this->db->from('trans_newsletter_subscription');
        $this->db->where($condition);
        $result = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'user_model',
                'model_method_name' => 'getUserSubscription',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $result->result_array();
    }

    public function getuserDesignDetails($fields = '', $condition = '', $order = '', $limit = '') {
        if ($fields == '')
            $fields = "*";

        $this->db->select($fields, FALSE);

        $this->db->from("mst_submit_design as b");
        $this->db->join("mst_users as u", "b.submitted_user_id_fk=u.user_id", "left");
        if ($condition != '')
            $this->db->where($condition);


        if ($order != '')
            $this->db->order_by($order);


        if ($limit != '')
            $this->db->limit($limit);

        $query = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'user_model',
                'model_method_name' => 'getUserSubscription',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    public function getuserVotesCount($cond) {
        $this->db->select('*');
        $this->db->from('trans_design_votes');
        $this->db->where($cond);
        $query = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'user_model',
                'model_method_name' => 'getuserVotesCount',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    public function getAllComments($fields = '', $condition = '', $order = '', $limit = '') {
        if ($fields == '')
            $fields = "*";

        $this->db->select($fields, FALSE);

        $this->db->from("trans_design_comments as b");

        if ($condition != '')
            $this->db->where($condition);


        if ($order != '')
            $this->db->order_by($order);


        if ($limit != '')
            $this->db->limit($limit);

        $query = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'user_model',
                'model_method_name' => 'getAllComments',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

    public function getAllContacts($user_id, $keyword) {
        $this->db->select('muc.*,mu.profile_picture');
        $this->db->from("mst_user_contacts as muc");
        $this->db->join("mst_users as mu", 'mu.user_id=muc.contact_user_id_fk', 'left');
        $this->db->where("muc.user_id_fk", $user_id);
        if ($keyword != '') {
            $this->db->like("muc.contact_name", $keyword);
//            $this->db->or_like("muc.contact_phone", $keyword);
        }
        $this->db->order_by("muc.contact_name", "asc");
        $query = $this->db->get();
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'user_model',
                'model_method_name' => 'getAllComments',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $query->result_array();
    }

}
