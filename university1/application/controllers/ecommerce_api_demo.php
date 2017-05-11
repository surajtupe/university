
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ecommerce_Api_Demo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("common_model");
        $this->load->model("register_model");
        $this->load->model("user_model");
        $this->load->model("ecommerce_api_demo_model");
    }

    public function regitration() {
        $data = $this->common_model->commonFunction();
        if ($this->input->post('first_name') != '' && $this->input->post('last_name') != '' && $this->input->post('mobile_number') != '') {
            $salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
            $hash_password = crypt($this->input->post('password'), '$2y$12$' . $salt);
            $insert_data = array(
                'user_password' => $hash_password,
                'user_type' => '1',
                'user_status' => '1',
                'phone_number' => '91' . $this->input->post('mobile_number'),
                'country_id' => '1',
                'register_date' => date('Y-m-d H:i:s'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name')
            );
            $this->common_model->insertRow($insert_data, 'mst_users');
            $this->session->set_userdata('msg_success', "Congratulations, Your registration successfully done.");
            redirect(base_url() . 'api-demo-sign-in');
        }
        $data['site_title'] = "Sign Up";
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/api-demo/registration');
        $this->load->view('front/includes/footer');
    }

    public function signin() {

        $data = $this->common_model->commonFunction();

        $data['global'] = $this->common_model->getGlobalSettings();
        if ($this->input->post('mobile_number') != '') {
            $table_to_pass = 'mst_users';
            $fields_to_pass = array('user_id', 'first_name', 'last_name', 'user_name', 'user_email', 'user_type', 'email_verified', 'user_status', 'user_password', 'role_id', 'user_step');
            $condition_to_pass = "(user_type!=2) and (phone_number = '91" . $this->input->post('mobile_number') . "' or phone_number = '91" . $this->input->post('mobile_number') . "')";
            $arr_login_data = $this->register_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
            if (count($arr_login_data)) {

                $crypt = crypt($this->input->post('password'), $arr_login_data[0]['user_password']);
                if ($crypt != $arr_login_data[0]['user_password']) {
                    $this->session->set_userdata('msg_failed', "Please enter correct password.");
                    redirect(base_url() . 'api-demo-sign-in');
                    exit;
                } elseif ($arr_login_data[0]['user_status'] == 2) {
                    $this->session->set_userdata('msg_failed', "Your account has been blocked by administrator.");
                    redirect(base_url() . 'api-demo-sign-in');
                } else {
                    if ($arr_login_data[0]['user_step'] == '1' || $arr_login_data[0]['user_step'] == '2' || $arr_login_data[0]['user_step'] == '3') {
                        redirect(base_url() . 'complete-profile/' . base64_encode($arr_login_data[0]['user_id']));
                    } else {
                        $user_data['user_id'] = $arr_login_data[0]['user_id'];
                        $user_data['user_email'] = $arr_login_data[0]['user_email'];
                        $user_data['first_name'] = $arr_login_data[0]['first_name'];
                        $user_data['last_name'] = $arr_login_data[0]['last_name'];
                        $user_data['user_type'] = $arr_login_data[0]['user_type'];
                        $user_data['role_id'] = $arr_login_data[0]['role_id'];
                        $this->session->set_userdata('user_account', $user_data);
                        redirect(base_url() . 'api-demo-profile');
                    }
                }
            } else {
                $this->session->set_userdata('msg_failed', "Invalid mobile/password.");
                redirect(base_url() . 'api-demo-sign-in');
            }
        }
        $data['header'] = array("title" => "User Login", "keywords" => "", "description" => "");
        $data['site_title'] = "Sign In";
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/api-demo/login');
        $this->load->view('front/includes/footer');
    }

    public function profile() {
        if (!$this->common_model->isLoggedIn()) {
            redirect('sign-in');
        }
        $data = $this->common_model->commonFunction();

        $arr_user_data = array();
        $table_to_pass = 'mst_users';
        $fields_to_pass = 'user_id,first_name,last_name,middle_name,title,user_email,user_name,profile_picture,gender,email_verified,phone_number,user_birth_date';
        $condition_to_pass = array("user_id" => $data['user_account']['user_id']);
        $arr_user_data = $this->user_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        $data['arr_user_data'] = $arr_user_data[0];

        $data['site_title'] = "Profile";
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/includes/inner-top-nav');
        $this->load->view('front/api-demo/user-profile', $data);
        $this->load->view('front/includes/footer');
    }

    public function addressDetails() {
        if ($this->input->post('address_name') != '' && $this->input->post('mobile_number') != '') {
            $condition_to_pass = array('u.phone_number' => $this->input->post('mobile_number'), 'address_name' => $this->input->post('address_name'));
            $arr_get_address_details = $this->ecommerce_api_demo_model->getAddressDetails($condition_to_pass);
            if (count($arr_get_address_details) > 0) {
                $condition_to_pass2 = array('ua.user_id_fk' => $arr_get_address_details[0]['user_id_fk'], 'user_address_id_fk' => $arr_get_address_details[0]['address_id']);
                $arr_forward_address_details = $this->ecommerce_api_demo_model->getUserForwardedAddress($condition_to_pass2);
                echo json_encode(array('current_address' => $arr_get_address_details[0], 'forwarding_address' => $arr_forward_address_details[0]));
            } else {
                echo json_encode('false');
            }
        }
    }

    function logout() {
        $this->session->unset_userdata('user_account');
        redirect(base_url('api-demo-sign-in'));
    }

}
