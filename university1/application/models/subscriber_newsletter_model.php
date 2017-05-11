<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class subscriber_Newsletter_Model extends CI_Model {
    #function to get newsletter list from the database

    public function getSubscriberNewsletterDetails() {
        $this->db->select('*');
        $query = $this->db->get('trans_newsletter_subscription');
        
        $error = $this->db->_error_message();
            $error_number = $this->db->_error_number();
            if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                 'error_number' => $error_number,
                 'model_name' => 'subscriber_newsletter_model',
                 'model_method_name' => 'getSubscriberNewsletterDetails',
                 'controller_name' => $controller,
                 'controller_method_name' => $method
                );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            }
            
        return $query->result_array();
    }
    
    public function getSubscriberUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0) {
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

    function updateSubcriberNewsletterDetails($data, $condition) {
        $this->db->where($condition);
        $this->db->update('trans_newsletter_subscription', $data); 
        
        $error = $this->db->_error_message();
            $error_number = $this->db->_error_number();
            if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                 'error_number' => $error_number,
                 'model_name' => 'subscriber_newsletter_model',
                 'model_method_name' => 'updateSubcriberNewsletterDetails',
                 'controller_name' => $controller,
                 'controller_method_name' => $method
                );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            }
            
    }

    function getSubcriberNewsletterDetailsById($id) {
        $this->db->select('*');
        $this->db->where('newsletter_subscription_id', $id);
        $query = $this->db->get('trans_newsletter_subscription');
        
        $error = $this->db->_error_message();
            $error_number = $this->db->_error_number();
            if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                 'error_number' => $error_number,
                 'model_name' => 'subscriber_newsletter_model',
                 'model_method_name' => 'getSubcriberNewsletterDetailsById',
                 'controller_name' => $controller,
                 'controller_method_name' => $method
                );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            }
            
        return $query->result_array();
    }
    
    public function insertNewsletterSubscriber($arr_fields) {

        $this->db->insert("trans_newsletter_subscription", $arr_fields);
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
                'model_name' => 'subscriber_newsletter_model',
                'method_name' => 'insertNewsletterSubscriber',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
        }
        return $this->db->insert_id();
    }
       
    }
 ?>