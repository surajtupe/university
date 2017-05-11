<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Newsletter_Model extends CI_Model {
    //function to get newsletter list from the database

    public function getNewsletterDetails() {
        $this->db->select('*');
        $this->db->order_by('newsletter_id DESC');
        $query = $this->db->get('mst_newsletter');
        
            $error = $this->db->_error_message();
            $error_number = $this->db->_error_number();
            if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                 'error_number' => $error_number,
                 'model_name' => 'newsletter_model',
                 'model_method_name' => 'getNewsletterDetails',
                 'controller_name' => $controller,
                 'controller_method_name' => $method
                );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            }
        return $query->result_array();
    }

    public function getNewsletterDetailById($newsletter_id) {
        $this->db->select('*');
        $this->db->where('newsletter_id', $newsletter_id);
        $query = $this->db->get('mst_newsletter');
        
        $error = $this->db->_error_message();
            $error_number = $this->db->_error_number();
            if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                 'error_number' => $error_number,
                 'model_name' => 'newsletter_model',
                 'model_method_name' => 'getNewsletterDetailById',
                 'controller_name' => $controller,
                 'controller_method_name' => $method
                );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            }
            
        return $query->result_array();
    }

    function addNewsletterDetails($data) {
        $this->db->insert('mst_newsletter', $data);
        
        $error = $this->db->_error_message();
            $error_number = $this->db->_error_number();
            if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                 'error_number' => $error_number,
                 'model_name' => 'newsletter_model',
                 'model_method_name' => 'addNewsletterDetails',
                 'controller_name' => $controller,
                 'controller_method_name' => $method
                );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            }
    }

    function updateNewsletterDetails($data, $condition) {
        $this->db->where($condition);
        $this->db->update('mst_newsletter', $data);
        
        $error = $this->db->_error_message();
            $error_number = $this->db->_error_number();
            if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                 'error_number' => $error_number,
                 'model_name' => 'newsletter_model',
                 'model_method_name' => 'updateNewsletterDetails',
                 'controller_name' => $controller,
                 'controller_method_name' => $method
                );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            }
            
    }

    function getNewsletterDetailsById($id) {
        $this->db->select('*');
        $this->db->where('newsletter_id', $id);
        $query = $this->db->get('mst_newsletter');
        
         $error = $this->db->_error_message();
            $error_number = $this->db->_error_number();
            if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                 'error_number' => $error_number,
                 'model_name' => 'newsletter_model',
                 'model_method_name' => 'getNewsletterDetailsById',
                 'controller_name' => $controller,
                 'controller_method_name' => $method
                );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            }
            
        return $query->result_array();
    }

    public function insertRow($insert_data, $table_name) {
        $this->db->insert($table_name, $insert_data);
        
        $error = $this->db->_error_message();
            $error_number = $this->db->_error_number();
            if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                 'error_number' => $error_number,
                 'model_name' => 'newsletter_model',
                 'model_method_name' => 'insertRow',
                 'controller_name' => $controller,
                 'controller_method_name' => $method
                );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            }
            
        return $this->db->insert_id();
    }

    function getAllUsersByStatus($user_status) {
        $this->db->where('user_status', $user_status);
		$this->db->where_not_in('user_type', '2');
        $query = $this->db->get('mst_users');
        
        $error = $this->db->_error_message();
            $error_number = $this->db->_error_number();
            if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                 'error_number' => $error_number,
                 'model_name' => 'newsletter_model',
                 'model_method_name' => 'getAllUsersByStatus',
                 'controller_name' => $controller,
                 'controller_method_name' => $method
                );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            }
        
        return $query->result_array();
    }

    public function getAllSubscribersByStatus($subscribe_status) {
        $this->db->select('user_email');
        $this->db->from('trans_newsletter_subscription');
        $this->db->where('subscribe_status', $subscribe_status);
        $query = $this->db->get();
        
        $error = $this->db->_error_message();
            $error_number = $this->db->_error_number();
            if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                 'error_number' => $error_number,
                 'model_name' => 'newsletter_model',
                 'model_method_name' => 'getAllSubscribersByStatus',
                 'controller_name' => $controller,
                 'controller_method_name' => $method
                );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            }
            
        return $query->result_array();
    }

}