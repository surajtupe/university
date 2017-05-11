<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Email_Template_Model extends CI_Model {
    /*
     * Function to get all email templates from email template table 
     * 
     */

    public function getEmailTemplateDetails() {
        $this->db->select('email.*,lang.lang_name');
        $this->db->from('mst_email_templates as email');
        $this->db->join('mst_languages as lang', 'lang.lang_id= email.lang_id', 'inner');
        $result = $this->db->get();

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'email_template_model',
                'model_method_name' => 'getEmailTemplateDetails',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }

        return $result->result_array();
    }

    /*
     *  function to get  email templates from email template table by using id 
     */

    public function getEmailTemplateDetailsById($email_template_id = '') {
        $this->db->select('email.*,lang.lang_name');
        $this->db->from('mst_email_templates as email');
        $this->db->join('mst_languages as lang', 'lang.lang_id= email.lang_id', 'inner');
        $this->db->where('email.email_template_id', $email_template_id);
        $result = $this->db->get();
        
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'email_template_model',
                'model_method_name' => 'getEmailTemplateDetailsById',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }
        
        return $result->result_array();
    }

    /*
     * function to update  email templates  by using id 
     */

    public function updateEmailTemplateDetailsById($email_template_id = '', $data) {
        $this->db->where('email_template_id', $email_template_id);
        $this->db->update('mst_email_templates as email', $data);
        
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'email_template_model',
                'model_method_name' => 'updateEmailTemplateDetailsById',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
        }
    }

}