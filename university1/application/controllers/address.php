<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Address extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("common_model");
        $this->load->model("user_model");
        $this->load->model("address_model");
        $this->load->model("state_model");
        $this->load->model("city_model");
    }

    public function viewAddress($address_id) {
        if (!$this->common_model->isLoggedIn()) {
            redirect('sign-in');
        }

        $address_id = base64_decode($address_id);

        $data = $this->common_model->commonFunction();
        $data['user_session'] = $this->session->userdata('user_account');
        $user_id = $data['user_session']['user_id'];

        $condition_to_pass = array('ua.user_id_fk' => $user_id, 'ua.address_id' => $address_id);
        $data['address_details'] = $this->address_model->getAddressDetails($condition_to_pass);

        $condition_to_pass2 = array('user_address_id_fk' => $address_id);
        $data['is_forwarding_address'] = $this->common_model->getRecords('mst_user_forwarded_address', 'forwarded_address_id', $condition_to_pass2);


        $arr_user_data = array();
        $table_to_pass = 'mst_users';
        $fields_to_pass = 'user_id,first_name,last_name,middle_name,title,user_email,user_name,profile_picture,gender,email_verified,phone_number,user_birth_date';
        $condition_to_pass = array("user_id" => $data['user_session']['user_id']);
        $arr_user_data = $this->user_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        $data['arr_user_data'] = $arr_user_data[0];
        $data['address_id'] = $address_id;
        $this->session->unset_userdata('security_code');
        $data['site_title'] = "Address Details";
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/includes/inner-top-nav');
        $this->load->view('front/address/address-details', $data);
        $this->load->view('front/includes/footer');
    }

    public function editAddress($address_id, $security_code) {
        if (!$this->common_model->isLoggedIn()) {
            redirect('sign-in');
        }

        $security_code = base64_decode($security_code);
        $sess_security_code = $this->session->userdata('security_code');
        if (isset($sess_security_code) && $sess_security_code != $security_code) {
            $this->session->set_userdata('msg_failed', 'Sorry you are not authorise person to edit this details.');
            redirect(base_url() . 'profile');
        }

        $address_id = base64_decode($address_id);
        $data = $this->common_model->commonFunction();
        $data['user_session'] = $this->session->userdata('user_account');
        $user_id = $data['user_session']['user_id'];

        $condition_to_pass = array('ua.user_id_fk' => $user_id, 'ua.address_id' => $address_id);
        $data['address_details'] = $this->address_model->getAddressDetails($condition_to_pass);
        $data['arr_country_details'] = $this->city_model->getCountriesPageNames();

        $condition_to_pass = array('country' => $data['address_details'][0]['country_id_fk']);
        $data['arr_state_details'] = $this->state_model->getStateDetailsByCountry($condition_to_pass);

        $condition_to_pass = array('state_id_fk' => $data['address_details'][0]['state_id_fk'], 'lang_id' => '17');
        $data['arr_city_details'] = $this->city_model->getCitiesByState($condition_to_pass);

        $ip = "182.72.79.154";
//            $ip = $_SERVER['REMOTE_ADDR'];
        $respGeo = file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip);
        $arrResponse = (json_decode($respGeo));
        $data['country_code_geo'] = $arrResponse->geoplugin_countryCode;
        $data['city'] = $arrResponse->geoplugin_city;
        $data['latitude'] = $arrResponse->geoplugin_latitude;
        $data['longitude'] = $arrResponse->geoplugin_longitude;

        $arr_user_data = array();
        $table_to_pass = 'mst_users';
        $fields_to_pass = 'user_id,first_name,last_name,middle_name,title,user_email,user_name,profile_picture,gender,email_verified,phone_number,user_birth_date';
        $condition_to_pass = array("user_id" => $data['user_session']['user_id']);
        $arr_user_data = $this->user_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        $data['arr_user_data'] = $arr_user_data[0];
        $data['address_id'] = $address_id;
        $data['site_title'] = "Address Details";
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/includes/inner-top-nav');
        $this->load->view('front/address/edit-address', $data);
        $this->load->view('front/includes/footer');
    }

    public function officeAddress() {
        if (!$this->common_model->isLoggedIn()) {
            redirect('sign-in');
        }

        $data = $this->common_model->commonFunction();
        $data['user_session'] = $this->session->userdata('user_account');
        $user_id = $data['user_session']['user_id'];

        $data['office_address_name'] = $this->randomName();

        if ($this->input->post('office_address_name') != '') {

            $file_name = '';
            if ($_FILES['address_building_pic']['name'] != '') {
                $config['file_name'] = time();
                $config['upload_path'] = './media/front/img/address-picture/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '5000000000';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('address_building_pic')) {
                    $error = array('error' => $this->upload->display_errors());
                    $response_arr = array('msg' => 'msg_failed', 'Response' => $error['error']);
                    echo json_encode($response_arr);
                } else {
                    $this->load->library('image_lib');
                    $data = array('upload_data' => $this->upload->data());
                    $image_data = $this->upload->data();
                    $file_name = $image_data['file_name'];
                    $absolute_path = $this->common_model->absolutePath();
                    $image_path = $absolute_path . "media/front/img/address-picture/";
                    $image_main = $image_path . "/" . $file_name;
                    $thumbs_image1 = $image_path . "/thumbs/" . $file_name;
                    $str_console1 = "convert " . $image_main . " -resize 300!X300! " . $thumbs_image1;
                    exec($str_console1);
                }
            }

            $office_add_address_name = array(
                'user_id_fk' => $user_id,
                'address_name' => $this->input->post('office_address_name'),
                'address_type_id_fk' => '5'
            );
            $office_add_last_inser_id = $this->common_model->insertRow($office_add_address_name, 'mst_user_addresses_name');
            if ($this->input->post('skip_parameter') != 'Yes') {
                $office_add_arr = array(
                    'user_id_fk' => $user_id,
                    'address_name_id_fk' => $office_add_last_inser_id,
                    'country_id' => $this->input->post('office_add_country'),
                    'state_id' => $this->input->post('office_add_state'),
                    'city_id' => $this->input->post('office_add_city'),
                    'zip_code' => $this->input->post('office_add_zipcode'),
                    'address_line1' => $this->input->post('office_add_first'),
                    'address_line2' => $this->input->post('office_add_second'),
                    'latitude' => $this->input->post('office_add_lat'),
                    'longitude' => $this->input->post('office_add_long'),
                    'address_picture' => $file_name,
                    'created_date' => date('Y-m-d H:i:s'),
                    'is_forwarded' => '0'
                );
                $last_insert_id = $this->common_model->insertRow($office_add_arr, 'mst_user_addresses');
                $this->session->set_userdata('msg_success', 'You have added your office address successfully.');
                redirect(base_url() . 'view-address/' . base64_encode($last_insert_id));
            }
        }
        $data['arr_country_details'] = $this->city_model->getCountriesPageNames();
        $data['arr_state_details'] = $this->state_model->get_all_states();
        $data['arr_city_details'] = $this->city_model->getCityInformation();


        $ip = "182.72.79.154";
//            $ip = $_SERVER['REMOTE_ADDR'];
        $respGeo = file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip);
        $arrResponse = (json_decode($respGeo));
        $data['country_code_geo'] = $arrResponse->geoplugin_countryCode;
        $data['city'] = $arrResponse->geoplugin_city;
        $data['latitude'] = $arrResponse->geoplugin_latitude;
        $data['longitude'] = $arrResponse->geoplugin_longitude;

        $arr_user_data = array();
        $table_to_pass = 'mst_users';
        $fields_to_pass = 'user_id,first_name,last_name,middle_name,title,user_email,user_name,profile_picture,gender,email_verified,phone_number,user_birth_date';
        $condition_to_pass = array("user_id" => $data['user_session']['user_id']);
        $arr_user_data = $this->user_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        $data['arr_user_data'] = $arr_user_data[0];

        $data['site_title'] = "Address Details";
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/includes/inner-top-nav');
        $this->load->view('front/address/office-address', $data);
        $this->load->view('front/includes/footer');
    }

    public function updateAddress() {
        if ($this->input->post('current_add_country') != '' && $this->input->post('current_add_state') != '' && $this->input->post('current_add_zipcode') != '' && $this->input->post('current_add_first') != '') {

            $file_name = '';
            if ($_FILES['address_building_pic']['name'] != '') {
                $config['file_name'] = time();
                $config['upload_path'] = './media/front/img/address-picture/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '5000000000';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('address_building_pic')) {
                    $error = array('error' => $this->upload->display_errors());
                    $response_arr = array('msg' => 'msg_failed', 'Response' => $error['error']);
                    echo json_encode($response_arr);
                } else {
                    $this->load->library('image_lib');
                    $data = array('upload_data' => $this->upload->data());
                    $image_data = $this->upload->data();
                    $file_name = $image_data['file_name'];
                    $absolute_path = $this->common_model->absolutePath();
                    $image_path = $absolute_path . "media/front/img/address-picture/";
                    $image_main = $image_path . "/" . $file_name;
                    $thumbs_image1 = $image_path . "/thumbs/" . $file_name;
                    $str_console1 = "convert " . $image_main . " -resize 300!X300! " . $thumbs_image1;
                    exec($str_console1);
                }
            }

            if ($file_name == '') {
                $file_name = $this->input->post('old_address_building_pic');
            }

            $update_add_arr = array(
                'country_id' => $this->input->post('current_add_country'),
                'state_id' => $this->input->post('current_add_state'),
                'city_id' => $this->input->post('current_add_city'),
                'zip_code' => $this->input->post('current_add_zipcode'),
                'address_line1' => $this->input->post('current_add_first'),
                'address_line2' => $this->input->post('current_add_second'),
                'latitude' => $this->input->post('current_add_lat'),
                'address_picture' => $file_name,
                'longitude' => $this->input->post('current_add_long'),
                'updated_date' => date('Y-m-d H:i:s'),
            );
            $condition_to_pass = array('address_id' => $this->input->post('address_id'));
            $this->common_model->updateRow('mst_user_addresses', $update_add_arr, $condition_to_pass);
            $this->session->set_userdata('msg_success', 'Your address has been updated successfully.');
            $response_arr = array('msg' => 'msg_success', 'address_id' => base64_encode($this->input->post('address_id')));
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'msg_failed', 'Response' => 'Somthing went wrong.Please try again.');
            echo json_encode($response_arr);
        }
    }

    public function addForwardingAddress($address_id) {

        if (!$this->common_model->isLoggedIn()) {
            redirect('sign-in');
        }

        $address_id = base64_decode($address_id);
        $data = $this->common_model->commonFunction();
        $data['user_session'] = $this->session->userdata('user_account');
        $user_id = $data['user_session']['user_id'];
        $condition_to_pass = array('ua.address_id' => $address_id);
        $data['arr_get_address_details'] = $this->address_model->getAllAddress($condition_to_pass);

        if ($this->input->post('current_add_country') != '' && $this->input->post('current_add_state') != '' && $this->input->post('current_add_zipcode') != '' && $this->input->post('current_add_first') != '') {
            $file_name = '';
            if ($_FILES['curr_add_building_pic']['name'] != '') {
                $config['file_name'] = time();
                $config['upload_path'] = './media/front/img/address-picture/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '5000000000';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('curr_add_building_pic')) {
                    $error = array('error' => $this->upload->display_errors());
                    $response_arr = array('msg' => 'msg_failed', 'Response' => $error['error']);
                    echo json_encode($response_arr);
                } else {
                    $this->load->library('image_lib');
                    $data = array('upload_data' => $this->upload->data());
                    $image_data = $this->upload->data();
                    $file_name = $image_data['file_name'];
                    $absolute_path = $this->common_model->absolutePath();
                    $image_path = $absolute_path . "media/front/img/address-picture/";
                    $image_main = $image_path . "/" . $file_name;
                    $thumbs_image1 = $image_path . "/thumbs/" . $file_name;
                    $str_console1 = "convert " . $image_main . " -resize 300!X300! " . $thumbs_image1;
                    exec($str_console1);
                }
            }

            $current_location_flag = '';
            if ($this->input->post('current_location_same_as_above') == 'on') {
                $current_location_flag = '1';
            }

            $current_add_arr = array(
                'user_id_fk' => $user_id,
                'user_address_id_fk' => $address_id,
                'country_id' => $this->input->post('current_add_country'),
                'state_id' => $this->input->post('current_add_state'),
                'city_id' => $this->input->post('current_add_city'),
                'zip_code' => $this->input->post('current_add_zipcode'),
                'address_line1' => $this->input->post('current_add_first'),
                'address_line2' => $this->input->post('current_add_second'),
                'latitude' => $this->input->post('current_add_lat'),
                'longitude' => $this->input->post('current_add_long'),
                'address_picture' => $file_name,
                'created_date' => date('Y-m-d H:i:s'),
                'date_from' => $this->input->post('date_from'),
                'date_to' => $this->input->post('date_to'),
                'same_location_flag' => $current_location_flag
            );
            $last_insert_id = $this->common_model->insertRow($current_add_arr, 'mst_user_forwarded_address');
            $condition_to_pass = array('address_id' => $address_id);
            $update_add_arr = array('is_forwarded' => '1');
            $this->common_model->updateRow('mst_user_addresses', $update_add_arr, $condition_to_pass);
            redirect(base_url() . 'view-forwarding-address/' . base64_encode($last_insert_id));
        }

        $ip = "182.72.79.154";
//            $ip = $_SERVER['REMOTE_ADDR'];
        $respGeo = file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip);
        $arrResponse = (json_decode($respGeo));
        $data['country_code_geo'] = $arrResponse->geoplugin_countryCode;
        $data['city'] = $arrResponse->geoplugin_city;
        $data['latitude'] = $arrResponse->geoplugin_latitude;
        $data['longitude'] = $arrResponse->geoplugin_longitude;

        $arr_user_data = array();
        $table_to_pass = 'mst_users';
        $fields_to_pass = 'user_id,first_name,last_name,middle_name,title,user_email,user_name,profile_picture,gender,email_verified,phone_number,user_birth_date';
        $condition_to_pass = array("user_id" => $data['user_session']['user_id']);
        $arr_user_data = $this->user_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        $data['arr_user_data'] = $arr_user_data[0];

        $data['arr_country_details'] = $this->city_model->getCountriesPageNames();

        $data['site_title'] = "Add Forwaring Address";
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/includes/inner-top-nav');
        $this->load->view('front/address/add-forwarding-address', $data);
        $this->load->view('front/includes/footer');
    }

    public function randomName() {
        $length = 8;
        $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
        $name = "";
        $i = '';
        for ($i = 0; $i < $length; $i++) {
            $name .= substr($characters, mt_rand(0, strlen($characters) - 1), 1);
        }
        return $name;
    }

    public function viewForwardingAddress($forwarding_address_id) {
        if (!$this->common_model->isLoggedIn()) {
            redirect('sign-in');
        }
        $forwarding_address_id = base64_decode($forwarding_address_id);

        $data = $this->common_model->commonFunction();
        $data['user_session'] = $this->session->userdata('user_account');
        $user_id = $data['user_session']['user_id'];
        $condition_to_pass = array('ufa.user_id_fk' => $user_id, 'ufa.forwarded_address_id' => $forwarding_address_id);
        $data['forwarind_address_details'] = $this->address_model->getForwardingAddressDetails($condition_to_pass);

        $arr_user_data = array();
        $table_to_pass = 'mst_users';
        $fields_to_pass = 'user_id,first_name,last_name,middle_name,title,user_email,user_name,profile_picture,gender,email_verified,phone_number,user_birth_date';
        $condition_to_pass = array("user_id" => $data['user_session']['user_id']);
        $arr_user_data = $this->user_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        $data['arr_user_data'] = $arr_user_data[0];
        $data['forwarding_address_id'] = $forwarding_address_id;
        $this->session->unset_userdata('security_code');
        $data['site_title'] = "Address Details";
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/includes/inner-top-nav');
        $this->load->view('front/address/forwarding-address-details', $data);
        $this->load->view('front/includes/footer');
    }

    public function editForwardingAddress($forwarding_address_id, $security_code) {
        if (!$this->common_model->isLoggedIn()) {
            redirect('sign-in');
        }

        $security_code = base64_decode($security_code);
        $sess_security_code = $this->session->userdata('security_code');
        if (isset($sess_security_code) && $sess_security_code != $security_code) {
            $this->session->set_userdata('msg_failed', 'Sorry you are not authorise person to edit this details.');
            redirect(base_url() . 'profile');
        }

        $forwarding_address_id = base64_decode($forwarding_address_id);

        $data = $this->common_model->commonFunction();
        $data['user_session'] = $this->session->userdata('user_account');
        $user_id = $data['user_session']['user_id'];

        if ($this->input->post('current_add_country') != '' && $this->input->post('current_add_state') != '' && $this->input->post('current_add_zipcode') != '' && $this->input->post('current_add_first') != '') {

            $file_name = '';
            if ($_FILES['address_building_pic']['name'] != '') {
                $config['file_name'] = time();
                $config['upload_path'] = './media/front/img/address-picture/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '5000000000';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('address_building_pic')) {
                    $error = array('error' => $this->upload->display_errors());
                    $response_arr = array('msg' => 'msg_failed', 'Response' => $error['error']);
                    echo json_encode($response_arr);
                } else {
                    $this->load->library('image_lib');
                    $data = array('upload_data' => $this->upload->data());
                    $image_data = $this->upload->data();
                    $file_name = $image_data['file_name'];
                    $absolute_path = $this->common_model->absolutePath();
                    $image_path = $absolute_path . "media/front/img/address-picture/";
                    $image_main = $image_path . "/" . $file_name;
                    $thumbs_image1 = $image_path . "/thumbs/" . $file_name;
                    $str_console1 = "convert " . $image_main . " -resize 300!X300! " . $thumbs_image1;
                    exec($str_console1);
                }
            }

            if ($file_name == '') {
                $file_name = $this->input->post('old_address_building_pic');
            }

            $current_location_flag = '';
            if ($this->input->post('current_location_same_as_above') == 'on') {
                $current_location_flag = '1';
            }

            $update_add_arr = array(
                'country_id' => $this->input->post('current_add_country'),
                'state_id' => $this->input->post('current_add_state'),
                'city_id' => $this->input->post('current_add_city'),
                'zip_code' => $this->input->post('current_add_zipcode'),
                'address_line1' => $this->input->post('current_add_first'),
                'address_line2' => $this->input->post('current_add_second'),
                'latitude' => $this->input->post('current_add_lat'),
                'address_picture' => $file_name,
                'longitude' => $this->input->post('current_add_long'),
                'updated_date' => date('Y-m-d H:i:s'),
                'date_from' => $this->input->post('date_from'),
                'date_to' => $this->input->post('date_to'),
                'same_location_flag' => $current_location_flag
            );
            $condition_to_pass = array('forwarded_address_id' => $forwarding_address_id);
            $this->common_model->updateRow('mst_user_forwarded_address', $update_add_arr, $condition_to_pass);
            $this->session->set_userdata('msg_success', 'Your address has been updated successfully.');
            $this->session->unset_userdata('security_code');
            redirect(base_url() . 'view-forwarding-address/' . base64_encode($forwarding_address_id));
        }

        $condition_to_pass = array('ufa.user_id_fk' => $user_id, 'ufa.forwarded_address_id' => $forwarding_address_id);
        $data['forwarind_address_details'] = $this->address_model->getForwardingAddressDetails($condition_to_pass);

        $data['arr_country_details'] = $this->city_model->getCountriesPageNames();

        $condition_to_pass = array('country' => $data['forwarind_address_details'][0]['country_id_fk']);
        $data['arr_state_details'] = $this->state_model->getStateDetailsByCountry($condition_to_pass);

        $condition_to_pass = array('state_id_fk' => $data['forwarind_address_details'][0]['state_id_fk']);
        $data['arr_city_details'] = $this->city_model->getCitiesByState($condition_to_pass);

        $ip = "182.72.79.154";
//            $ip = $_SERVER['REMOTE_ADDR'];
        $respGeo = file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip);
        $arrResponse = (json_decode($respGeo));
//        echo '<pre>'; print_r($arrResponse);die;
        $data['country_code_geo'] = $arrResponse->geoplugin_countryCode;
        $data['region_geo'] = $arrResponse->geoplugin_region;
        $data['city'] = $arrResponse->geoplugin_city;
        $data['latitude'] = $arrResponse->geoplugin_latitude;
        $data['longitude'] = $arrResponse->geoplugin_longitude;

        $arr_user_data = array();
        $table_to_pass = 'mst_users';
        $fields_to_pass = 'user_id,first_name,last_name,middle_name,title,user_email,user_name,profile_picture,gender,email_verified,phone_number,user_birth_date';
        $condition_to_pass = array("user_id" => $data['user_session']['user_id']);
        $arr_user_data = $this->user_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        $data['arr_user_data'] = $arr_user_data[0];
        $data['forwarding_address_id'] = $forwarding_address_id;

        $data['site_title'] = "Edit Forwarding Address Details";
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/includes/inner-top-nav');
        $this->load->view('front/address/edit-forwarding-address', $data);
        $this->load->view('front/includes/footer');
    }

}
