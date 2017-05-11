<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('common_model');
    }

    public function index() {
        $data = $this->common_model->commonFunction();
        $data['user_session'] = $this->session->userdata('user_account');
        if (!empty($data['user_session'])) {
            redirect('profile');
        }
        $data['global'] = $this->common_model->getGlobalSettings();

        $data['arr_testimonials'] = $this->common_model->getTestimonial();
//        echo "<pre>";
//        print_r($data['arr_testimonials']);
//        die;
        $data['header'] = array("title" => "Home", "keywords" => "", "description" => "");
        if (isset($data['global']['site_title']) && $data['global']['site_title'] != '') {
            $data['site_title'] = $data['global']['site_title'];
        } else {
            $data['site_title'] = "Home";
        }
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/includes/top-nav');
        $this->load->view('front/home', $data);
        $this->load->view('front/includes/home-footer');
    }

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */