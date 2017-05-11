<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 *  Class will do all necessary action for blog functionalities
 */

class Testimonials_Model extends CI_Model {

   
    //funcion to get all FAQs with condition
    public function getTestimonials() {
     
      	$this->db->select("t.*,lang.lang_name");
        $this->db->from("mst_testimonial as t");
        $this->db->join("mst_languages as lang",'lang.lang_id=t.lang_id','inner');
        $this->db->order_by('t.testimonial_id DESC');
       
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
