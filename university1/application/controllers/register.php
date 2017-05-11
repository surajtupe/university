<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Register extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("common_model");
        $this->load->model("register_model");
        $this->load->model("city_model");
        $this->load->model("state_model");
        $this->load->model("countries_model");
    }

    public function registration() {
        $data = $this->common_model->commonFunction();
        $data['user_session'] = $this->session->userdata('user_account');
        if (!empty($data['user_session'])) {
            redirect('profile');
        }
        $data['global'] = $this->common_model->getGlobalSettings();
        $data['curr_address_name'] = $this->randomName();
        $data['perm_address_name'] = $this->randomName();
        $data['office_address_name'] = $this->randomName();
        $ip = "182.72.79.154";

//        $ip = $_SERVER['REMOTE_ADDR'];
        $respGeo = file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip);
        $arrResponse = (json_decode($respGeo));
//        echo '<pre>'; print_r($arrResponse);die;
        $data['country_code_geo'] = $arrResponse->geoplugin_countryCode;
        $data['city'] = $arrResponse->geoplugin_city;
        $data['latitude'] = $arrResponse->geoplugin_latitude;
        $data['longitude'] = $arrResponse->geoplugin_longitude;

        $condition_to_pass = array('iso' => $arrResponse->geoplugin_countryCode, 'lang_id' => '17');
        $data['get_country_code_details'] = $this->countries_model->getCountriesByISO($condition_to_pass);
        $data['arr_country_details'] = $this->city_model->getCountriesPageNames();
        $data['arr_state_details'] = $this->state_model->get_all_states();
        $data['arr_city_details'] = $this->city_model->getCityInformation();

        $data['site_title'] = "Sign Up";
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/registration/register');
        $this->load->view('front/includes/footer');
    }

    public function firstStepMobileNumber() {
        $mobile_number = $this->input->post('mobile_number');
        if ($mobile_number != '') {
            $condition_to_pass = array('mobile_number' => $mobile_number);
            $chk_mobile_is_exits = $this->common_model->getRecords('mst_mobile_number_and_otp_details', 'mobile_number', $condition_to_pass);
            $six_digit_otp_number = mt_rand(1000, 9999);
            if (COUNT($chk_mobile_is_exits) == 0) {
                $arr_insert = array(
                    'mobile_number' => $mobile_number,
                    'otp_code' => $six_digit_otp_number,
                    'type' => 'Register',
                    'status' => '0',
                    'date' => date('Y-m-d H:i:s')
                );
                $this->common_model->insertRow($arr_insert, 'mst_mobile_number_and_otp_details');
                $response_arr = array('msg' => 'true', 'otp_code' => $six_digit_otp_number);
            } else {
                $condition_to_pass2 = array('mobile_number' => $mobile_number);
                $update_data = array(
                    'otp_code' => $six_digit_otp_number,
                    'date' => date('Y-m-d H:i:s')
                );
                $this->common_model->updateRow('mst_mobile_number_and_otp_details', $update_data, $condition_to_pass2);
                $response_arr = array('msg' => 'true', 'otp_code' => $six_digit_otp_number);
            }
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'false');
            echo json_encode($response_arr);
        }
    }

    function secondStepMobileNumber() {
        $data = $this->common_model->commonFunction();
        $data['global'] = $this->common_model->getGlobalSettings();
        $otp_code = $this->input->post('otp_number');
        $mobile_number = $this->input->post('mobile_number');
        $password = $this->input->post('password');
        if ($otp_code != '' && $mobile_number != '' && $password) {
            $condition_to_pass = array('mobile_number' => $mobile_number, 'otp_code' => $otp_code);
            $verify_mobile_and_otp = $this->common_model->getRecords('mst_mobile_number_and_otp_details', '', $condition_to_pass);
            if (COUNT($verify_mobile_and_otp) > 0) {
                $otp_generation_time = date('Y-m-d H:i:s', strtotime($verify_mobile_and_otp[0]['date']));
                $current_time = date('Y-m-d H:i:s');
                $time1 = new DateTime($otp_generation_time);
                $time2 = new DateTime($current_time);
                $interval = $time1->diff($time2);
                $hours = $interval->format('%H');
                $minutes = $interval->format('%i');
                //check otp is expired or not
                $otp_expired_time = $data['global']['OTP_expired'];
                if ($hours == '00' && $minutes <= $otp_expired_time) {
                    $salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
                    $hash_password = crypt($password, '$2y$12$' . $salt);
                    $activation_code = rand();
                    $insert_data = array(
                        'user_password' => $hash_password,
                        'user_type' => '1',
                        'user_status' => '1',
                        'phone_number' => $mobile_number,
                        'country_id' => '1',
                        'activation_code' => $activation_code,
                        'register_date' => date('Y-m-d H:i:s'),
                        'user_step' => '1'
                    );
                    $last_insert_id = $this->common_model->insertRow($insert_data, 'mst_users');
                    // updating status after entering mobile number and otp to VERIFIRED
                    $update_data = array('status' => '1');
                    $condition = array('mobile_number' => $mobile_number);
                    $this->common_model->updateRow('mst_mobile_number_and_otp_details', $update_data, $condition);
                    $response_arr = array('msg' => 'msg_success', 'last_insert_id' => $last_insert_id);
                    echo json_encode($response_arr);
                } else {
                    $response_arr = array('msg' => 'msg_failed', 'Response' => 'Your OTP has been expired.Please re-generate your otp.');
                    echo json_encode($response_arr);
                }
            } else {
                $response_arr = array('msg' => 'msg_failed', 'Response' => 'Please enter valid OTP.');
                echo json_encode($response_arr);
            }
        }
    }

    public function thirdStepCompleteProfile() {
        if ($this->input->post('first_name') != '' && $this->input->post('last_name') != '' && $this->input->post('user_email') != '' && $this->input->post('phone_number') != '' && $this->input->post('last_inser_id') != '') {
            $date_of_birth = $this->input->post('day') . '/' . $this->input->post('month') . '/' . $this->input->post('year');
            $activation_code = time();
            $data = $this->common_model->commonFunction();
            $update_data = array(
                'first_name' => $this->input->post('first_name'),
                'middle_name' => $this->input->post('middle_name'),
                'last_name' => $this->input->post('last_name'),
                'title' => $this->input->post('title'),
                'user_email' => $this->input->post('user_email'),
                'phone_number' => $this->input->post('phone_number'),
                'user_birth_date' => $date_of_birth,
                'user_type' => '1',
                'user_status' => '1',
                'email_verified' => '0',
                'activation_code' => $activation_code,
                'register_date' => date("Y-m-d H:i:s"),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_step' => '2'
            );
            $condition = array('user_id' => $this->input->post('last_inser_id'));
            $this->common_model->updateRow('mst_users', $update_data, $condition);
            $lang_id = 17;
            $activation_link = '<a href="' . base_url() . 'user-activation/' . $activation_code . '">Verify Email</a>';
            $reserved_words = array
                ("||SITE_TITLE||" => $data['global']['site_title'],
                "||SITE_PATH||" => base_url(),
                "||USER_NAME||" => $this->input->post('first_name') . ' ' . $this->input->post('last_name'),
                "||ADMIN_EMAIL||" => $this->input->post('user_email'),
                "||ADMIN_ACTIVATION_LINK||" => $activation_link
            );
            $email_content = $this->common_model->getEmailTemplateInfo('email-verify', 17, $reserved_words);
            $mail = $this->common_model->sendEmail(array($this->input->post('user_email')), array("email" => $data['global']['site_email'], "name" => $data['global']['site_title']), $email_content['subject'], $email_content['content']);

            $response_arr = array('msg' => 'msg_success');
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'msg_failed', 'Response' => 'Somthing went wrong.Please try again.');
            echo json_encode($response_arr);
        }
    }

    public function forthStepCurrentAddress() {
        if ($this->input->post('current_add_country') != '' && $this->input->post('current_add_state') != '' && $this->input->post('current_add_zipcode') != '' && $this->input->post('current_add_first') != '') {
            $current_add_address_name = array(
                'user_id_fk' => $this->input->post('c_last_inser_id'),
                'address_name' => $this->input->post('current_add_address_name'),
                'address_type_id_fk' => '2'
            );
            $current_add_last_inser_id = $this->common_model->insertRow($current_add_address_name, 'mst_user_addresses_name');

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

            $condition_to_pass = array('address_id' => $this->input->post('current_address_id_bk'));
            $get_current_address_details = $this->common_model->getRecords('mst_user_addresses', '', $condition_to_pass);

            if ($this->input->post('current_address_id_bk') != '' && COUNT($get_current_address_details) > 0) {
                $update_current_add_arr = array(
                    'country_id' => $this->input->post('current_add_country'),
                    'state_id' => $this->input->post('current_add_state'),
                    'city_id' => $this->input->post('current_add_city'),
                    'zip_code' => $this->input->post('current_add_zipcode'),
                    'address_line1' => $this->input->post('current_add_first'),
                    'address_line2' => $this->input->post('current_add_second'),
                    'latitude' => $this->input->post('current_add_lat'),
                    'longitude' => $this->input->post('current_add_long'),
                    'address_picture' => $file_name,
                );
                $condition = array('address_id' => $this->input->post('current_address_id_bk'));
                $this->common_model->updateRow('mst_user_addresses', $update_current_add_arr, $condition);
                $last_insert_id = $this->input->post('current_address_id_bk');
            } else {
                $current_add_arr = array(
                    'user_id_fk' => $this->input->post('c_last_inser_id'),
                    'address_name_id_fk' => $current_add_last_inser_id,
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
                    'is_forwarded' => '0'
                );
                $last_insert_id = $this->common_model->insertRow($current_add_arr, 'mst_user_addresses');
            }
            $update_data = array('user_step' => '3');
            $condition = array('user_id' => $this->input->post('c_last_inser_id'));
            $this->common_model->updateRow('mst_users', $update_data, $condition);

            $response_arr = array('msg' => 'msg_success', 'current_address_id' => $last_insert_id, 'address_picture' => $file_name);
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'msg_failed', 'Response' => 'Somthing went wrong.Please try again.');
            echo json_encode($response_arr);
        }
    }

    public function fifthStepPermanentAddress() {
        if ($this->input->post('permanant_add_country') != '') {
            $permanant_add_country = $this->input->post('permanant_add_country');
        } else {
            $permanant_add_country = $this->input->post('permanant_add_country_id');
        }

        if ($this->input->post('permanant_add_state') != '') {
            $permanant_add_state = $this->input->post('permanant_add_state');
        } else {
            $permanant_add_state = $this->input->post('permanant_add_state_id');
        }

        if ($this->input->post('permanant_add_city') != '') {
            $permanant_add_city = $this->input->post('permanant_add_city');
        } else {
            $permanant_add_city = $this->input->post('permanant_add_city_id');
        }

        if ($permanant_add_country != '' && $permanant_add_state != '' && $permanant_add_city != '' && $this->input->post('permanant_add_zipcode') != '' && $this->input->post('permanant_add_first') != '' && $this->input->post('p_last_inser_id') != '') {
            $permanent_add_address_name = array(
                'user_id_fk' => $this->input->post('p_last_inser_id'),
                'address_name' => $this->input->post('permanant_address_name'),
                'address_type_id_fk' => '3'
            );
            $permanent_add_last_inser_id = $this->common_model->insertRow($permanent_add_address_name, 'mst_user_addresses_name');

            $current_add_same_as_per = '0';
            $file_name = '';
            if ($this->input->post('permanent_add_same_as_current') == 'on') {
                $current_add_same_as_per = '1';
                $update_data = array('is_current_add_same_as_permanant_add' => '1');
                $condition = array('address_id' => $this->input->post('current_address_id'));
                $this->common_model->updateRow('mst_user_addresses', $update_data, $condition);
                $file_name = $this->input->post('current_address_image');
            }

            if ($_FILES['per_add_building_pic']['name'] != '') {
                $config['file_name'] = time();
                $config['upload_path'] = './media/front/img/address-picture/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '5000000000';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('per_add_building_pic')) {
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

            $condition_to_pass = array('address_id' => $this->input->post('permanent_address_id_bk'));
            $get_permanent_address_details = $this->common_model->getRecords('mst_user_addresses', '', $condition_to_pass);

            if ($this->input->post('permanent_address_id_bk') != '' && COUNT($get_permanent_address_details) > 0) {
                $update_permanent_add_arr = array(
                    'country_id' => $permanant_add_country,
                    'state_id' => $permanant_add_state,
                    'city_id' => $permanant_add_city,
                    'zip_code' => $this->input->post('permanant_add_zipcode'),
                    'address_line1' => $this->input->post('permanant_add_first'),
                    'address_line2' => $this->input->post('permanant_add_second'),
                    'latitude' => $this->input->post('permanant_add_lat'),
                    'longitude' => $this->input->post('permanant_add_long'),
                    'address_picture' => $file_name,
                    'is_forwarded' => '0',
                    'is_current_add_same_as_permanant_add' => $current_add_same_as_per
                );
                $condition = array('address_id' => $this->input->post('permanent_address_id_bk'));
                $this->common_model->updateRow('mst_user_addresses', $update_permanent_add_arr, $condition);
                $last_insert_id = $this->input->post('permanent_address_id_bk');
            } else {
                $permanent_add_arr = array(
                    'user_id_fk' => $this->input->post('p_last_inser_id'),
                    'address_name_id_fk' => $permanent_add_last_inser_id,
                    'country_id' => $permanant_add_country,
                    'state_id' => $permanant_add_state,
                    'city_id' => $permanant_add_city,
                    'zip_code' => $this->input->post('permanant_add_zipcode'),
                    'address_line1' => $this->input->post('permanant_add_first'),
                    'address_line2' => $this->input->post('permanant_add_second'),
                    'latitude' => $this->input->post('permanant_add_lat'),
                    'longitude' => $this->input->post('permanant_add_long'),
                    'address_picture' => $file_name,
                    'created_date' => date('Y-m-d H:i:s'),
                    'is_forwarded' => '0',
                    'is_current_add_same_as_permanant_add' => $current_add_same_as_per
                );
                $last_insert_id = $this->common_model->insertRow($permanent_add_arr, 'mst_user_addresses');
            }

            $update_data = array('user_step' => '4');
            $condition = array('user_id' => $this->input->post('p_last_inser_id'));
            $this->common_model->updateRow('mst_users', $update_data, $condition);

            $response_arr = array('msg' => 'msg_success', 'permanent_address_id' => $last_insert_id);
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'msg_failed', 'Response' => 'Somthing went wrong.Please try again.');
            echo json_encode($response_arr);
        }
    }

    public function sixthStepOfficeAddress() {
        if ($this->input->post('office_address_name') != '' && $this->input->post('o_last_inser_id') != '') {
            $office_add_address_name = array(
                'user_id_fk' => $this->input->post('o_last_inser_id'),
                'address_name' => $this->input->post('office_address_name'),
                'address_type_id_fk' => '5'
            );
            $office_add_last_inser_id = $this->common_model->insertRow($office_add_address_name, 'mst_user_addresses_name');

            $file_name = '';
            if (!empty($_FILES) && $_FILES['office_add_building_pic']['name'] != '') {
                $config['file_name'] = time();
                $config['upload_path'] = './media/front/img/address-picture/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '5000000000';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('office_add_building_pic')) {
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

            if ($this->input->post('skip_parameter') != 'Yes') {
                $office_add_arr = array(
                    'user_id_fk' => $this->input->post('o_last_inser_id'),
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
                $this->common_model->insertRow($office_add_arr, 'mst_user_addresses');

                $update_data = array('user_step' => '5');
                $condition = array('user_id' => $this->input->post('o_last_inser_id'));
                $this->common_model->updateRow('mst_users', $update_data, $condition);
            }

            $condition_to_pass = array('user_id' => $this->input->post('o_last_inser_id'));
            $arr_login_data = $this->common_model->getRecords('mst_users', 'user_id,first_name,last_name,user_type,role_id,user_email', $condition_to_pass);

            $user_data['user_id'] = $arr_login_data[0]['user_id'];
//            $user_data['user_name'] = $arr_login_data[0]['user_name'];
            $user_data['user_email'] = $arr_login_data[0]['user_email'];
            $user_data['first_name'] = $arr_login_data[0]['first_name'];
            $user_data['last_name'] = $arr_login_data[0]['last_name'];
            $user_data['user_type'] = $arr_login_data[0]['user_type'];
            $user_data['role_id'] = $arr_login_data[0]['role_id'];
            $this->session->set_userdata('user_account', $user_data);

            $this->session->set_userdata('msg_success', "Congratulations, Your registration successfully done.Please check your email and verify email from <strong>" . $arr_login_data[0]['user_email'] . "</strong>");
            $response_arr = array('msg' => 'msg_success');
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'msg_failed', 'Response' => 'Somthing went wrong.Please try again.');
            echo json_encode($response_arr);
        }
    }

    public function chkNumberDuplicate() {
        $this->load->model('register_model');
        $user_number = $this->input->post('mobile_number');

//        $table_to_pass = 'mst_mobile_number_and_otp_details';
//        $fields_to_pass = array('mobile_number');
//        $condition_to_pass = array("mobile_number" => $user_number);
//        $arr_login_data = $this->register_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
//        if (count($arr_login_data)) {
//            echo 'false';
//        } else {
        $table_to_pass = 'mst_users';
        $fields_to_pass = array('user_id', 'phone_number');
        $condition_to_pass = array("phone_number" => $user_number);
        $arr_login_data = $this->register_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        if (count($arr_login_data)) {
            echo 'false';
        } else {
            echo 'true';
        }
//        }
    }

    public function chkVlidOTP() {
        $otp_number = $this->input->post('otp_number');
        $mobile_number = $this->input->post('mobile_number');
        if ($otp_number != '') {
            $condition_to_pass = array('mobile_number' => $mobile_number, 'otp_code' => $otp_number);
            $arr_otp_details = $this->common_model->getRecords('mst_mobile_number_and_otp_details', '', $condition_to_pass);
            if (COUNT($arr_otp_details) > 0) {
                echo 'true';
            } else {
                echo 'false';
            }
        }
    }

    public function chkNumberExists() {
        $this->load->model('register_model');
        $user_number = $this->input->post('mobile_number');
        $table_to_pass = 'mst_users';
        $fields_to_pass = array('user_id', 'phone_number');
        $condition_to_pass = array("phone_number" => $user_number);
        $arr_login_data = $this->register_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        if (count($arr_login_data)) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    /*
     * Send the reset password link
     */

    public function passwordRecovery() {
        $data = $this->common_model->commonFunction();
        $data['user_session'] = $this->session->userdata('user_account');
        if (!empty($data['user_session'])) {
            redirect('profile');
        }
        $data['global'] = $this->common_model->getGlobalSettings();
        $otp_number = $this->input->post('otp_number');
        $mobile_number = $this->input->post('mobile_number');
        if ($otp_number != '' && $mobile_number != '') {
            $condition_to_pass = array(
                'mobile_number' => $mobile_number,
                'otp_code' => $otp_number
            );
            $verify_mobile_and_otp = $this->common_model->getRecords('mst_mobile_number_and_otp_details', 'mobile_number,otp_code,date', $condition_to_pass);
            if (COUNT($verify_mobile_and_otp) > 0) {
                $otp_generation_time = date('Y-m-d H:i:s', strtotime($verify_mobile_and_otp[0]['date']));
                $current_time = date('Y-m-d H:i:s');
                $time1 = new DateTime($otp_generation_time);
                $time2 = new DateTime($current_time);
                $interval = $time1->diff($time2);
                $hours = $interval->format('%H');
                $minutes = $interval->format('%i');
                //check otp is expired or not
                $otp_expired_time = $data['global']['OTP_expired'];
                if ($hours == '00' && $minutes <= $otp_expired_time) {
                    $table_to_pass = 'mst_users';
                    $fields_to_pass = array('user_id', 'first_name', 'last_name', 'user_name', 'user_email', 'user_type', 'email_verified', 'user_status', 'user_password', 'role_id');
                    $condition_to_pass = "(user_type!=2) and (phone_number = '" . addslashes($this->input->post('mobile_number')) . "' or phone_number = '" . addslashes($this->input->post('mobile_number')) . "')";
                    $arr_login_data = $this->register_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
                    if (count($arr_login_data)) {
                        if ($arr_login_data[0]['email_verified'] == 1) {
                            if ($arr_login_data[0]['user_status'] == 2) {
                                $this->session->set_userdata('login_error', "Your account has been blocked by administrator.");
                                redirect(base_url() . 'sign-in');
                            } else {
                                $user_data['user_id'] = $arr_login_data[0]['user_id'];
//                                $user_data['user_name'] = $arr_login_data[0]['user_name'];
                                $user_data['user_email'] = $arr_login_data[0]['user_email'];
                                $user_data['first_name'] = $arr_login_data[0]['first_name'];
                                $user_data['last_name'] = $arr_login_data[0]['last_name'];
                                $user_data['user_type'] = $arr_login_data[0]['user_type'];
                                $user_data['role_id'] = $arr_login_data[0]['role_id'];

                                $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $arr_login_data[0]['role_id']));
                                /* serializing the user privilegse and setting into the session. While ussing user privileges use unserialize this session to get user privileges */
                                if (count($arr_privileges) > 0) {
                                    foreach ($arr_privileges as $privilege) {
                                        $user_privileges[] = $privilege['privilege_id'];
                                    }
                                } else {
                                    $user_privileges = array();
                                }
                                $user_data['user_privileges'] = serialize($user_privileges);
                                $this->session->set_userdata('user_account', $user_data);

                                $update_data = array('status' => '1');
                                $condition = array('mobile_number' => $mobile_number);
                                $this->common_model->updateRow('mst_mobile_number_and_otp_details', $update_data, $condition);

                                redirect(base_url() . 'profile');
                            }
                        } else {
                            $this->session->set_userdata('msg_failed', "Please activate your account.");
                            redirect(base_url() . 'sign-in');
                        }
                    } else {
                        $this->session->set_userdata('msg_failed', "Invalid mobile number. Please try again or contact to admin.");
                        redirect(base_url() . 'sign-in');
                    }
                } else {
                    $this->session->set_userdata('msg_failed', 'Sorry ! Your OTP has been expired. Please try again.');
                    redirect(base_url() . 'forgot-password');
                }
            }
        }
        $data['site_title'] = "Forgot Password";
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/login/forgot-password');
        $this->load->view('front/includes/footer');
    }

    public function forgotPasswordLink() {
        $data = $this->common_model->commonFunction();
        $mobile_no = $this->input->post('mobile_number');
//        $country_id = $this->input->post('country_id');
        $country_id = '1';
        if ($mobile_no != '') {
            $condition_to_country = array('phone_number' => $mobile_no, 'country_id' => $country_id);
            $arr_user_details = $this->common_model->getRecords('mst_users', 'user_id,first_name,last_name,phone_number,user_step,user_email', $condition_to_country);
            if (COUNT($arr_user_details) > 0) {
                if ($arr_user_details[0]['user_email'] != '') {
                    $activation_code = time();
                    $table_name = 'mst_users';
                    $update_data = array('reset_password_code' => $activation_code);
                    $condition_to_pass = array("user_email" => $arr_user_details[0]['user_email']);
                    $this->common_model->updateRow($table_name, $update_data, $condition_to_pass);
                    $reset_password_link = '<a href="' . base_url() . 'reset-password/' . base64_encode($activation_code) . '">Click here</a>';
                    $lang_id = 17; // Default is 17(English)
                    $reserved_words = array(
                        "||FIRST_NAME||" => $arr_user_details[0]['first_name'],
                        "||LAST_NAME||" => $arr_user_details[0]['last_name'],
                        "||USER_EMAIL||" => $arr_user_details[0]['user_email'],
                        "||RESET_PASSWORD_LINK||" => $reset_password_link,
                        "||SITE_TITLE||" => $data['global']['site_title'],
                        "||SITE_PATH||" => base_url()
                    );

                    $template_title = 'forgot-password';
                    $arr_emailtemplate_data = $this->common_model->getEmailTemplateInfo($template_title, $lang_id, $reserved_words);
                    $recipeinets = $arr_user_details[0]['user_email'];
                    $from = array("email" => $data['global']['site_email'], "name" => $data['global']['site_title']);
                    $subject = $arr_emailtemplate_data['subject'];
                    $message = $arr_emailtemplate_data['content'];
                    $mail = $this->common_model->sendEmail($recipeinets, $from, $subject, $message);
                    $this->session->set_userdata('msg_success', 'Reset password link has been sent to your email.');
                    echo 'sign-in';
                } else {
                    $six_digit_otp_number = mt_rand(1000, 9999);
                    $update_data = array(
                        'otp_code' => $six_digit_otp_number,
                        'type' => 'forgotPassword',
                        'status' => '0',
                        'date' => date('Y-m-d H:i:s')
                    );
                    $this->common_model->updateRow('mst_mobile_number_and_otp_details', $update_data, array('mobile_number' => $mobile_no));
                    echo 'otp_generation';
                }
            }
        }
    }

    /*
     * Change new user's password
     */

    public function resetPassword($activation_code) {
        $data = $this->common_model->commonFunction();
        $data['global'] = $this->common_model->getGlobalSettings();
        if ($activation_code != '') {
            $data['activation_code'] = $activation_code;
        }
        /* cheaking password link expirted or not using reset_password_code; */
        $user_detail = $this->common_model->getRecords("mst_users", "user_id", array("reset_password_code" => base64_decode($data['activation_code'])));

        if (count($user_detail) == 0) {
            $this->session->set_userdata('msg_failed', "Your reset password link has been expired.");
            redirect(base_url() . 'reset-password');
            exit;
        }

        if ($this->input->post('new_password') != '') {
            // generating the password by using hashing technique and applyng salt on it
            //crypt has method is used. 2y is crypt algorith selector
            //12 is workload factor on core processor.
            $salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
            $hash_password = crypt($this->input->post('new_password'), '$2y$12$' . $salt);

            $table_to_pass = 'mst_users';
            $fields_to_pass = array('user_id', 'first_name', 'last_name', 'user_name', 'user_email', 'user_password');
            $condition_to_pass = array("reset_password_code" => base64_decode($this->input->post('security_code')));
            $arr_password_data = $this->register_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
            if (count($arr_password_data) > 0) {
                $table_name = 'mst_users';
                $update_data = array('user_password' => $hash_password, "reset_password_code" => "");
                $condition_to_pass = array("reset_password_code" => base64_decode($this->input->post('security_code')));
                $this->common_model->updateRow($table_name, $update_data, $condition_to_pass);
                $this->session->set_userdata('msg_success', "Your password has been reset successfully. Please login.");
                redirect(base_url() . 'sign-in');
                exit;
            } else {
                $this->session->set_userdata('msg_failed', "Your reset password link has been expired.");
                redirect(base_url() . 'reset-password');
                exit;
            }
        }
        $data['header'] = array("title" => "Reset Your Password", "keywords" => "", "description" => "");
        $data['site_title'] = "Reset Password";
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/login/new-password', $data);
        $this->load->view('front/includes/footer');
    }

    /*
     * Login into website
     */

    public function signin() {
        /* for remeber me load cookie lib */
        $this->load->helper('cookie');
        $data = $this->common_model->commonFunction();
        $data['user_session'] = $this->session->userdata('user_account');
        if (!empty($data['user_session'])) {
            redirect('profile');
        }
        $data['global'] = $this->common_model->getGlobalSettings();
        if ($this->input->post('mobile_number') != '') {
            $table_to_pass = 'mst_users';
            $fields_to_pass = array('user_id', 'first_name', 'last_name', 'user_name', 'user_email', 'user_type', 'email_verified', 'user_status', 'user_password', 'role_id', 'user_step');
            $condition_to_pass = "(user_type!=2) and (phone_number = '" . addslashes($this->input->post('mobile_number')) . "' or phone_number = '" . addslashes($this->input->post('mobile_number')) . "')";
            $arr_login_data = $this->register_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);

            if (count($arr_login_data)) {

                $crypt = crypt($this->input->post('password'), $arr_login_data[0]['user_password']);
                if ($crypt != $arr_login_data[0]['user_password']) {
                    $this->session->set_userdata('msg_failed', "Please enter correct password.");
                    redirect(base_url() . 'sign-in');
                    exit;
                } elseif ($arr_login_data[0]['user_status'] == 2) {
                    $this->session->set_userdata('msg_failed', "Your account has been blocked by administrator.");
                    redirect(base_url() . 'sign-in');
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


                        if ($this->input->post('remember_me')) {
                            $remember_me = serialize(array("cookie_mobile_number" => $this->input->post('mobile_number'), "cookie_password" => $this->input->post('password')));
                            $remember_me_cookie = array(
                                "name" => "cookie_remember_me",
                                "value" => $remember_me,
                                "expire" => time() - 3600
                            );
                            $this->input->set_cookie($remember_me_cookie);
                        } else {
                            delete_cookie('cookie_remember_me');
                        }

                        $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $arr_login_data[0]['role_id']));
                        /* serializing the user privilegse and setting into the session. While ussing user privileges use unserialize this session to get user privileges */
                        if (count($arr_privileges) > 0) {
                            foreach ($arr_privileges as $privilege) {
                                $user_privileges[] = $privilege['privilege_id'];
                            }
                        } else {
                            $user_privileges = array();
                        }
                        $user_data['user_privileges'] = serialize($user_privileges);
                        /*
                         * Set the user's session
                         */
                        $this->session->set_userdata('user_account', $user_data);
                        redirect(base_url() . 'profile');
                    }
                }
            } else {
                $this->session->set_userdata('msg_failed', "Invalid mobile/password.");
                redirect(base_url() . 'sign-in');
            }
        }
        $data['remember_me_array'] = unserialize($this->input->cookie('cookie_remember_me'));
        $data['header'] = array("title" => "User Login", "keywords" => "", "description" => "");
        $data['site_title'] = "Sign In";
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/login/login');
        $this->load->view('front/includes/footer');
    }

    public function completeProfile($user_id) {
        $user_id = base64_decode($user_id);
        $data = $this->common_model->commonFunction();
        $data['global'] = $this->common_model->getGlobalSettings();

        $data['curr_address_name'] = $this->randomName();
        $data['perm_address_name'] = $this->randomName();
        $data['office_address_name'] = $this->randomName();


        $ip = "182.72.79.154";
//            $ip = $_SERVER['REMOTE_ADDR'];
        $respGeo = file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip);
        $arrResponse = (json_decode($respGeo));
        $data['country_code_geo'] = $arrResponse->geoplugin_countryCode;
        $data['city'] = $arrResponse->geoplugin_city;
        $data['latitude'] = $arrResponse->geoplugin_latitude;
        $data['longitude'] = $arrResponse->geoplugin_longitude;

        $data['arr_country_details'] = $this->city_model->getCountriesPageNames();
        $data['arr_state_details'] = $this->state_model->get_all_states();
        $data['arr_city_details'] = $this->city_model->getCityInformation();

        $condition_to_pass = array('user_id' => $user_id);
        $arr_user_details = $this->common_model->getRecords('mst_users', 'user_step,phone_number', $condition_to_pass);

        $arr_current_add_details = $this->register_model->getCurrentAddressDetils($user_id);
        if (COUNT($arr_current_add_details) > 0) {
            $data['arr_current_add_details'] = $arr_current_add_details[0];
        }


        $data['arr_user_details'] = $arr_user_details[0];
        $data['user_id'] = $user_id;

        $data['site_title'] = "Complete Profile";
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/includes/inner-top-nav');
        $this->load->view('front/registration/complete-profile');
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

    /*
     * User's account activation by email
     */

    public function userActivation($activation_code) {
        $this->load->model('register_model');
        $table_to_pass = 'mst_users';
        $fields_to_pass = array('user_id', 'first_name', 'last_name', 'user_name', 'user_email', 'user_type', 'email_verified', 'user_status');
        $condition_to_pass = array("activation_code" => $activation_code);
        /* get user details to verify the email address */
        $arr_login_data = $this->register_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        if (count($arr_login_data)) {
            if ($arr_login_data[0]['email_verified'] == 1) {
                $this->session->set_userdata('activation_error', "You have already activated your account. Please login.");
            } else {

                $user_detail = $this->common_model->getRecords("mst_users", "user_id", array("activation_code" => $activation_code));
                $this->load->model('admin_model');
                /* Removing the user if he is exists in inactiveated list */
                $this->register_model->updateInactiveUserFile($this->common_model->absolutePath(), 1, intval($user_detail[0]['user_id']));
                $table_name = 'mst_users';
                $update_data = array("user_status" => '1', 'email_verified' => '1');
                $condition_to_pass = array("activation_code" => $activation_code);
                $this->common_model->updateRow($table_name, $update_data, $condition_to_pass);
                $this->session->set_userdata('msg_success', "Your email address has been verified successfully.");
            }
        } else {
            $this->session->set_userdata('msg_failed', "Invalid activation link.");
        }
        redirect(base_url() . "sign-in");
    }

    /*
     * Check email duplication
     */

    public function chkEmailDuplicate() {
        $this->load->model('register_model');
        $user_email = $this->input->post('user_email');
        $user_email_back = $this->input->post('user_email_back');
        if ($user_email == $user_email_back) {
            echo 'true';
        } else {
            $table_to_pass = 'mst_users';
            $fields_to_pass = array('user_id', 'user_email');
            $condition_to_pass = array("user_email" => $user_email);
            $arr_login_data = $this->register_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
            if (count($arr_login_data)) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    }

    /*
     * Check email availability 
     */

    public function chkEmailExist() {
        ob_clean();
        $this->load->model('register_model');
        $user_email = $this->input->post('user_email');
        $table_to_pass = 'mst_users';
        $fields_to_pass = array('user_id', 'user_email');
        if ($this->input->post('action') != "") {
            $condition_to_pass = "(user_type!=2) and (`user_email` = '" . $user_email . "' or `user_name` = '" . $user_email . "')";
        } else {
            $condition_to_pass = array("user_email" => $user_email);
        }
        $arr_login_data = $this->register_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        if (count($arr_login_data)) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    /*
     * Chekck duplicate username
     */

    public function chkUserDuplicate() {
        $this->load->model('register_model');
        $user_name = $this->input->post('user_name');
        $table_to_pass = 'mst_users';
        $fields_to_pass = array('user_id', 'user_name');
        $condition_to_pass = array("user_name" => $user_name);
        $arr_login_data = $this->register_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        if (count($arr_login_data)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    /*
     * create captcha image
     */

    public function generateCaptcha($rand) {
        ob_clean();
        $data = $this->common_model->commonFunction();
        $arr1 = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
        $arr2 = array();
        foreach ($arr1 as $val)
            $arr2[] = strtoupper($val);
        $arr3 = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        $str = "";
        $arr_all_characters = array_merge($arr1, $arr2, $arr3);
        for ($i = 0; $i < 5; $i++) {
            $str.=$arr_all_characters[array_rand($arr_all_characters)] . "";
        }
        $this->session->set_userdata('security_answer', $str);
        putenv('GDFONTPATH=' . realpath('.'));
        //$font = '/var/www/ci-pipl-code-library/user-module/media/front/captcha/ariblk.ttf';
        $font = $data['absolute_path'] . 'media/front/captcha/ariblk.ttf';
        $IMGVER_IMAGE = imagecreatefromjpeg(base_url() . "media/front/captcha/bg1.jpg");
        $IMGVER_COLOR_WHITE = imagecolorallocate($IMGVER_IMAGE, 0, 0, 0);
        $text = $str;
        $IMGVER_COLOR_BLACK = imagecolorallocate($IMGVER_IMAGE, 255, 255, 255);
        imagefill($IMGVER_IMAGE, 0, 0, $IMGVER_COLOR_BLACK);
        imagettftext($IMGVER_IMAGE, 24, 0, 20, 28, $IMGVER_COLOR_WHITE, $font, $text);
        //header("Content-type: image/jpeg");
        imagejpeg($IMGVER_IMAGE);
    }

    /*
     * Check the captcha validation 
     */

    public function checkCaptcha() {
        if ($this->input->post('input_captcha_value') == $this->session->userdata('security_answer')) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    public function checkForValidCodeOnchangePassword() {
        $data = $this->common_model->commonFunction();
        $code = $this->input->post('security_code');
        $data['user_session'] = $this->session->userdata('user_account');
        if ($code != '') {
            $table_name = 'mst_users';
            $select_data = array('user_id,password_change_security_code');
            $condition = array('password_change_security_code' => $code);
            $data['user_info'] = $this->common_model->getRecords($table_name, $select_data, $condition);

            if (count($data['user_info']) > 0) {
                if ($data['user_info'][0]['password_change_security_code'] != '0' && $data['user_info'][0]['password_change_security_code'] != '') {
                    echo "true";
                    exit;
                } else {
                    echo "false";
                    exit;
                }
            } else {
                echo "false";
                exit;
            }
        }
        echo "false";
        exit;
    }

    public function checkCurrentAddZipcode() {
        $zip_code = $this->input->post('current_add_zipcode');
        $city_name = urlencode($this->input->post('current_add_city'));
        $state_name = urlencode($this->input->post('current_add_state'));
        $country_name = urlencode(strtoupper($this->input->post('current_add_country')));
        $data_zip = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=$city_name&components=country:$country_name|locality:$city_name|postal_code:$zip_code&sensor=false");
        $data_zip = json_decode($data_zip);
        if ($data_zip->status == "OK") {
            echo 'true';
        } else {
            echo 'false';
        }
    }
    public function checkPermanentAddZipcode() {
        $zip_code = $this->input->post('permanant_add_zipcode');
        $city_name = urlencode($this->input->post('permanant_add_city'));
        $state_name = urlencode($this->input->post('permanant_add_state'));
        $country_name = urlencode(strtoupper($this->input->post('permanant_add_country')));
        $data_zip = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=$city_name&components=country:$country_name|locality:$city_name|postal_code:$zip_code&sensor=false");
        $data_zip = json_decode($data_zip);
        if ($data_zip->status == "OK") {
            echo 'true';
        } else {
            echo 'false';
        }
    }
    public function checkOfficeAddZipcode() {
        $zip_code = $this->input->post('office_add_zipcode');
        $city_name = urlencode($this->input->post('office_add_city'));
        $state_name = urlencode($this->input->post('office_add_state'));
        $country_name = urlencode(strtoupper($this->input->post('office_add_country')));
        $data_zip = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=$city_name&components=country:$country_name|locality:$city_name|postal_code:$zip_code&sensor=false");
        $data_zip = json_decode($data_zip);
        if ($data_zip->status == "OK") {
            echo 'true';
        } else {
            echo 'false';
        }
    }

}

/* End of file register.php */
    /* Location: ./application/controllers/register.php */ 
