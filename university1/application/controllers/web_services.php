<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once("application/libraries/thumbnail.php");

class Web_Services extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('countries_model');
        $this->load->model('city_model');
        $this->load->model('web_services_model');
    }

    function countriesListWebService() {
        $get_countries_list = $this->countries_model->getCountriesList();
        $flag_image_path = base_url() . "media/backend/img/country-flag/thumbs/";
        if (COUNT($get_countries_list) > 0) {
            for ($count = 0; $count < COUNT($get_countries_list); $count++) {
                $countries_data = $get_countries_list[$count];
                $arr_return[$count]['country_id'] = $countries_data["country_id"];
                $arr_return[$count]['country_name'] = $countries_data["country_name"];
                $arr_return[$count]['country_phone_code'] = $countries_data["country_phone_code"];
                $arr_return[$count]['mobile_number_length'] = $countries_data["mobile_number_length"];
                $arr_return[$count]['trunk_code'] = $countries_data["trunk_code"];
                $arr_return[$count]['iso'] = $countries_data["iso"];
                if ($countries_data["flag"] != '') {
                    $arr_return[$count]['flag'] = $flag_image_path . $countries_data["flag"];
                } else {
                    $arr_return[$count]['flag'] = '';
                }
                $response_arr = array('msg' => 'Success', 'Response' => $arr_return);
            }
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function getStateInfoWebService() {
        $county_id = $this->input->post('country_id');
        $condition_to_pass = array("country" => $county_id, "lang_id" => '17');
        $arr_state_data = $this->city_model->getStateDetailsByCountry($condition_to_pass);
        if (COUNT($arr_state_data) > 0) {
            $response_arr = array('msg' => 'Success', 'Response' => $arr_state_data);
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function getCityInfoWebService() {
        $country_id = $this->input->post('country_id');
        $state_id = $this->input->post('state_id');
        $condition_to_pass = array("A.country_id_fk" => $country_id, "A.state_id_fk" => $state_id, "lang_id" => '17');
        $arr_city_data = $this->city_model->getCitiesByStateWebService($condition_to_pass);
        if (COUNT($arr_city_data) > 0) {
            $response_arr = array('msg' => 'Success', 'Response' => $arr_city_data);
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function cmsPageWebservice() {
        $this->load->model('cms_model');
        $cms_title = $this->input->post('cms_title');
        $condition = array('A.page_alias' => $cms_title, 'A.status' => 'Published', "B.lang_id" => '17');
        $arr_cms = $this->cms_model->getCmsPageDetailsFront($condition);
        if ($cms_title != '' && COUNT($arr_cms) > 0) {
            $arr_cms_details = array(
                'cms_id' => $arr_cms[0]['cms_id'],
                'page_alias' => $arr_cms[0]['page_alias'],
                'page_title' => $arr_cms[0]['page_title'],
                'page_content' => stripcslashes($arr_cms[0]['page_content'])
            );
            $response_arr = array('msg' => 'Success', 'Response' => $arr_cms_details);
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function generateOTP() {
        $mobile_no = $this->input->post('mobile_no');
        $country_id = $this->input->post('country_id');
        $registration_id = $this->input->post('registration_id');

        if ($mobile_no != '' && $country_id != '') {
            $six_digit_otp_number = mt_rand(1000, 9999);
            //checking mobile number is already exists or not
            $condition_for_mobile = array('phone_number' => $mobile_no, 'country_id' => $country_id);
            $arr_chk_mobile_exists = $this->common_model->getRecords('mst_users', '', $condition_for_mobile);
            if (COUNT($arr_chk_mobile_exists) == 0) {
                $condition_to_pass = array('mobile_number' => $mobile_no);
                $chk_mobile_exists = $this->common_model->getRecords('mst_mobile_number_and_otp_details', '', $condition_to_pass);

                if (COUNT($chk_mobile_exists) == 0) {
                    $arr_insert = array(
                        'mobile_number' => $mobile_no,
                        'otp_code' => $six_digit_otp_number,
                        'type' => '0',
                        'status' => '0',
                        'date' => date('Y-m-d H:i:s'),
                        'registration_id' => $registration_id
                    );
                    $this->common_model->insertRow($arr_insert, 'mst_mobile_number_and_otp_details');
                    $response_arr = array('msg' => 'Success', 'otp_code' => $six_digit_otp_number);
                } else {
                    $condition_to_pass = array('mobile_number' => $mobile_no);
                    $update_data = array(
                        'otp_code' => $six_digit_otp_number,
                        'date' => date('Y-m-d H:i:s'),
                        'registration_id' => $registration_id
                    );
                    $this->common_model->updateRow('mst_mobile_number_and_otp_details', $update_data, $condition_to_pass);
                    $response_arr = array('msg' => 'Success', 'otp_code' => $six_digit_otp_number);
                }
                echo json_encode($response_arr);
            } else {
                $condition_to_pass = array('mobile_number' => $mobile_no);
                $update_data = array(
                    'otp_code' => $six_digit_otp_number,
                    'date' => date('Y-m-d H:i:s'),
                    'registration_id' => $registration_id,
                    'status' => '0'
                );
                $this->common_model->updateRow('mst_mobile_number_and_otp_details', $update_data, $condition_to_pass);
                $response_arr = array('msg' => 'Failed. Mobile number already exists', 'otp_code' => $six_digit_otp_number);
                echo json_encode($response_arr);
            }
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function verifyOTP() {
        $data = $this->common_model->commonFunction();
        $otp_code = $this->input->post('otp');
        $mobile_number = $this->input->post('mobile_number');
        $password = $this->input->post('password');
        $country_id = $this->input->post('country_id');
        if ($otp_code != '' && $mobile_number != '' && $country_id != '') {
            //checking mobile number and otp is exists or not with entered mobile number and otp code, if exits then acoount get verified
            $condition_to_pass = array('mobile_number' => $mobile_number, 'otp_code' => $otp_code);
            $verify_mobile_and_otp = $this->common_model->getRecords('mst_mobile_number_and_otp_details', '', $condition_to_pass);
            if (COUNT($verify_mobile_and_otp) > 0) {
                $condition_to_pass2 = array(
                    'phone_number' => $mobile_number
                );
                $arr_user_details = $this->common_model->getRecords('mst_users', 'user_id,title,first_name,last_name,middle_name,user_email,user_birth_date,profile_picture,user_step', $condition_to_pass2);
                if (COUNT($arr_user_details) > 0) {

                    // updating status after entering mobile number and otp to VERIFIRED
                    $update_data = array('status' => '1');
                    $condition = array('otp_id' => $verify_mobile_and_otp[0]['otp_id']);
                    $this->common_model->updateRow('mst_mobile_number_and_otp_details', $update_data, $condition);


                    //checking address name is already exists for this user id
                    $condition_to_pass = array('user_id_fk' => $arr_user_details[0]['user_id']);
                    $arr_check_adress_name = $this->common_model->getRecords('mst_user_addresses_name', '', $condition_to_pass);

                    if ($arr_user_details[0]['user_step'] != '' && $arr_user_details[0]['user_step'] == '1') {
                        $response_arr = array('msg' => 'Success', 'user_id' => $arr_user_details[0]['user_id']);
                    } else if ($arr_user_details[0]['user_step'] != '' && $arr_user_details[0]['user_step'] == '2') {
                        $response_arr = array('msg' => 'Success', 'profile_details' => $arr_user_details, 'address_name' => $arr_check_adress_name, 'user_step' => $arr_user_details[0]['user_step']);
                    } else if ($arr_user_details[0]['user_step'] != '' && $arr_user_details[0]['user_step'] == '3') {
                        $condition_to_pass = array(
                            'at.address_type_id' => '2',
                            'ua.user_id_fk' => $arr_user_details[0]['user_id']
                        );
                        $arr_current_address = $this->web_services_model->getUserAddressDetails($condition_to_pass);
                        $response_arr = array('msg' => 'Success', 'profile_details' => $arr_user_details, 'current_address' => $arr_current_address, 'address_name' => $arr_check_adress_name, 'user_step' => $arr_user_details[0]['user_step']);
                    } else if ($arr_user_details[0]['user_step'] != '' && $arr_user_details[0]['user_step'] == '4') {
                        $condition_to_pass = array(
                            'at.address_type_id' => '3',
                            'ua.user_id_fk' => $arr_user_details[0]['user_id']
                        );
                        $arr_permanent_address = $this->web_services_model->getUserAddressDetails($condition_to_pass);
                        $condition_to_pass = array(
                            'at.address_type_id' => '2',
                            'ua.user_id_fk' => $arr_user_details[0]['user_id']
                        );
                        $arr_current_address = $this->web_services_model->getUserAddressDetails($condition_to_pass);
                        $response_arr = array('msg' => 'Success', 'profile_details' => $arr_user_details, 'current_address' => $arr_current_address, 'permanent_address' => $arr_permanent_address, 'address_name' => $arr_check_adress_name, 'user_step' => $arr_user_details[0]['user_step']);
                    } else if ($arr_user_details[0]['user_step'] != '' && $arr_user_details[0]['user_step'] == '5') {
                        $condition_to_pass = array(
                            'at.address_type_id' => '5',
                            'ua.user_id_fk' => $arr_user_details[0]['user_id']
                        );
                        $arr_office_address = $this->web_services_model->getUserAddressDetails($condition_to_pass);

                        $condition_to_pass = array(
                            'at.address_type_id' => '3',
                            'ua.user_id_fk' => $arr_user_details[0]['user_id']
                        );
                        $arr_permanent_address = $this->web_services_model->getUserAddressDetails($condition_to_pass);
                        $condition_to_pass = array(
                            'at.address_type_id' => '2',
                            'ua.user_id_fk' => $arr_user_details[0]['user_id']
                        );
                        $arr_current_address = $this->web_services_model->getUserAddressDetails($condition_to_pass);

                        $response_arr = array('msg' => 'Success', 'profile_details' => $arr_user_details, 'current_address' => $arr_current_address, 'permanent_address' => $arr_permanent_address, 'office_address' => $arr_office_address, 'address_name' => $arr_check_adress_name, 'user_step' => $arr_user_details[0]['user_step']);
                    } else {
                        $response_arr = array('msg' => 'Failed');
                    }
                } else {

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
                        //checking mobile number is already exists or not
//                    $condition_for_mobile = array('phone_number' => $mobile_number);
//                    $chk_mobile_exists = $this->common_model->getRecords('mst_users', '', $condition_for_mobile);
//                    if (COUNT($chk_mobile_exists) == 0) {
                        $salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
                        $hash_password = crypt($password, '$2y$12$' . $salt);
                        $activation_code = rand();
                        $insert_data = array(
                            'user_password' => $hash_password,
                            'user_type' => '1',
                            'user_status' => '1',
                            'phone_number' => $mobile_number,
                            'country_id' => $country_id,
                            'activation_code' => $activation_code,
                            'register_date' => date('Y-m-d H:i:s'),
                            'user_step' => '1',
                        );
                        $last_insert_id = $this->common_model->insertRow($insert_data, 'mst_users');
                        // updating status after entering mobile number and otp to VERIFIRED
                        $update_data = array('status' => '1');
                        $condition = array('otp_id' => $verify_mobile_and_otp[0]['otp_id']);
                        $this->common_model->updateRow('mst_mobile_number_and_otp_details', $update_data, $condition);

//                    } else {
//                        $response_arr = array('msg' => 'Failed. Mobile number already exists');
//                    }
                        $response_arr = array('msg' => 'Success', 'user_id' => $last_insert_id);
                    } else {
                        $response_arr = array('msg' => 'Failed. OTP has been expired.');
                    }
                }
            } else {
                $response_arr = array('msg' => 'Failed. Please enter valid otp or mobile number.');
            }
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function completeProfile() {
        $data = $this->common_model->commonFunction();
        $user_id = $this->input->post('user_id');
        $title = $this->input->post('title');
        $first_name = $this->input->post('first_name');
        $middle_name = $this->input->post('middle_name');
        $last_name = $this->input->post('last_name');
        $email = $this->input->post('email');
        $gender = $this->input->post('gender');
        $date_of_birth = $this->input->post('date_of_birth');
        $device = $this->input->post('device');
//
//        $user_id = '406';
//        $title = 'title';
//        $first_name = 'mk';
//        $middle_name ='mk';
//        $last_name = 'mk';
//        $email = 'mangesh@panaceatek.com' ;
//        $gender = '1';
//        $date_of_birth = '1990-12-17';
//        $device = 'A';

        if ($user_id != '' && $title != '' && $first_name != '' && $last_name != '' && $last_name != '' && $email != '' && $date_of_birth != '') {
            //Check email is exists or not
            $condition_for_mobile = array('user_email' => $email);
            $chk_mobile_exists = $this->common_model->getRecords('mst_users', '', $condition_for_mobile);
            if (COUNT($chk_mobile_exists) == 0) {
                //Upload user profile picture. Its an optional.
                $file_name = '';
                if ($device == 'A') {
                    if (isset($_FILES['profile_image_name']['tmp_name'])) { //echo 'If second';
                        $file_name = time() . ".jpg";
                        move_uploaded_file($_FILES['profile_image_name']['tmp_name'], "media/front/img/user-profile-pictures/" . $file_name);
                        // now initialize thumbnail class and make the thumb
//                        $arr_config1 = array(
//                            'width' => 120,
//                            'height' => 120,
//                            'source' => "media/front/img/user-profile-pictures/" . $file_name,
//                            'destination_file' => "media/front/img/user-profile-pictures/" . $file_name,
//                            'destination_dir' => "media/front/img/user-profile-pictures/thumb/"
//                        );
//                        $obj_thumb1 = new Thumbnail($arr_config1);
//                        $obj_thumb1->generateThumb();
                    }

                    if ($this->input->post('profile_image_name') != '') {
                        $base = $this->input->post('profile_image_name');
                        $binary = base64_decode($base);
                        $file_name = uniqid() . time() . '.jpg';
                        $file = fopen("media/front/img/user-profile-pictures/" . $user_profile_file, 'wb');
                        fwrite($file, $binary);
                        fclose($file);
                    }
                } else {

                    $image_path = '';
                    if (!empty($_FILES['profile_image_name'])) {
                        if ($_FILES['profile_image_name']['tmp_name'] != "") {
                            $config['upload_path'] = './media/front/img/user-profile-pictures/';
                            $config['allowed_types'] = '*';
                            $config['max_size'] = '100000000000000';
                            $config['max_width'] = '12024';
                            $config['max_height'] = '7268';
                            $config['file_name'] = rand();
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);
                            //loading uploda library
                            if (!$this->upload->do_upload('profile_image_name')) {
                                $error = array('error' => $this->upload->display_errors());
                                $response_arr = array('msg' => 'Failed', 'Response' => $error['error']);
                                echo json_encode($response_arr);
                                exit;
                            } else {
                                $image_data = $this->upload->data();
                                $file_name = $image_data['file_name'];

                                $absolute_path = $this->common_model->absolutePath();
                                $image_path = $absolute_path . "media/front/img/user-profile-pictures/";
                                $image_main = $image_path . "/" . $file_name;

                                $thumbs_image2 = $image_path . "/thumb/" . $file_name;
                                $str_console2 = "convert " . $image_main . " -resize 120!X120! " . $thumbs_image2;
                                exec($str_console2);

                                $image_path = base_url() . 'media/front/img/user-profile-pictures/' . $file_name;
                            }
                        }
                    }
                }

                //Updating user information with the help of user id
                $update_data = array(
                    'title' => $title,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'middle_name' => $middle_name,
                    'user_email' => $email,
                    'user_birth_date' => $date_of_birth,
                    'profile_picture' => $file_name,
                    'user_step' => '2',
                    'gender' => $gender
                );
                $condition = array('user_id' => $user_id);
                $this->common_model->updateRow('mst_users', $update_data, $condition);



                //checking address name is already exists for this user id
                $condition_to_pass = array('user_id_fk' => $user_id);
                $arr_check_adress_name = $this->common_model->getRecords('mst_user_addresses_name', '', $condition_to_pass);

                //getting address type ids for address better understanding which address name is assigned for which type of address
                $condition_to_pass = "parent_id = '1' OR parent_id = '4'";
                $arr_address_type = $this->common_model->getRecords('mst_address_type', '', $condition_to_pass);

                $address_arr = array();
                if (COUNT($arr_check_adress_name) == 0) {
                    for ($count = 0; $count < 3; $count++) {
                        $address_name = $this->randomName();
                        $insert_data = array(
                            'user_id_fk' => $user_id,
                            'address_name' => $address_name,
                            'address_type_id_fk' => $arr_address_type[$count]['address_type_id']
                        );
                        $last_insert_id = $this->common_model->insertRow($insert_data, 'mst_user_addresses_name');
                        $address_arr[$count]['address_name'] = $insert_data['address_name'];
                        $address_arr[$count]['address_id'] = $last_insert_id;
                        $address_arr[$count]['address_type_id_fk'] = $arr_address_type[$count]['address_type_id'];
                    }
//sending response
                    $response_arr = array('msg' => 'Success', 'address_names' => $address_arr, 'profile_image_name' => $file_name);



//sending email after completing profile to user 
                    $arr_user_details = $this->common_model->getRecords('mst_users', 'activation_code', array('user_id' => $user_id));
                    $lang_id = $this->session->userdata('lang_id');
                    if (isset($lang_id) && $lang_id != '') {
                        $lang_id = $this->session->userdata('lang_id');
                    } else {
                        $lang_id = 17; // Default is 17(English)
                    }
                    $activation_link = '<a href="' . base_url() . 'user-activation/' . $arr_user_details[0]['activation_code'] . '">Click here</a>';
                    $macros_array_detail = array();
                    $macros_array_detail = $this->common_model->getRecords('mst_email_template_macros', 'macros,value', $condition_to_pass = '', $order_by = '', $limit = '', $debug = 0);
                    $macros_array = array();
                    foreach ($macros_array_detail as $row) {
                        $macros_array[$row['macros']] = $row['value'];
                    }
                    $reserved_words = array();

                    $reserved_arr = array
                        (
                        "||FIRST_NAME||" => $first_name,
                        "||LAST_NAME||" => $last_name,
                        "||USER_EMAIL||" => $email,
                        "||ACTIVATION_LINK||" => $activation_link,
                        "||SITE_URL||" => base_url(),
                        "||SITE_TITLE||" => $data['global']['site_title']
                    );

                    $reserved_words = array_replace_recursive($macros_array, $reserved_arr);
                    $template_title = 'registration-successful';
                    $arr_emailtemplate_data = $this->common_model->getEmailTemplateInfo($template_title, $lang_id, $reserved_words);
                    $recipeinets = $email;
                    $from = array("email" => $data['global']['contact_email'], "name" => $data['global']['site_title']);
                    $subject = $arr_emailtemplate_data['subject'];
                    $message = $arr_emailtemplate_data['content'];
//                    $mail = $this->common_model->sendEmail($recipeinets, $from, $subject, $message);
                } else {
                    for ($count = 0; $count < 3; $count++) {
                        $address_data = $arr_check_adress_name[$count];
                        $address_arr[$count]['address_name'] = $address_data['address_name'];
                        $address_arr[$count]['address_id'] = $address_data['address_id'];
                        $address_arr[$count]['address_type_id_fk'] = $address_data['address_type_id_fk'];
                    }
                    $response_arr = array('msg' => 'Success', 'address_names' => $address_arr);
                }
            } else {
                $response_arr = array('msg' => 'Failed.Email address already exists.');
            }
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function randomName() {
        $length = 8;
        $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
        $name = "";
        for ($i = 0; $i < $length; $i++) {
            $name .= $characters[mt_rand(0, strlen($characters))];
        }
        return $name;
    }

    public function addAddresses() {
        $user_id = $this->input->post('user_id');
        $country_id = $this->input->post('country_id');
        $state_id = $this->input->post('state_id');
        $city_id = $this->input->post('city_id');
        $zipcode = $this->input->post('zipcode');
        $address1 = $this->input->post('address1');
        $address2 = $this->input->post('address2');
        $lat = $this->input->post('lat');
        $long = $this->input->post('long');
        $address_type_id = $this->input->post('address_type_id');
        $address_name_id = $this->input->post('address_name_id');
        $permanant_address_name_id = $this->input->post('permanant_address_name_id');
        $is_current_add_same_as_permanant_add = $this->input->post('is_current_add_same_as_permanant_add');
        $current_location_same_as_above_flag = $this->input->post('current_location_same_as_above_flag');

        $device = $this->input->post('device');
        if ($user_id != '' && $country_id != '' && $state_id != '' && $zipcode != '' && $address1 != '' && $address_name_id != '') {
            $file_name = '';
            $image_path = '';
            if ($device == 'A') {
                if (isset($_FILES['building_photo']['tmp_name'])) { //echo 'If second';
                    $file_name = time() . ".jpg";
                    move_uploaded_file($_FILES['building_photo']['tmp_name'], "media/front/img/address-picture/" . $file_name);
                }

                if ($this->input->post('building_photo') != '') {
                    $base = $this->input->post('building_photo');
                    $binary = base64_decode($base);
                    $file_name = uniqid() . time() . '.jpg';
                    $file = fopen("media/front/img/address-picture/" . $user_profile_file, 'wb');
                    fwrite($file, $binary);
                    fclose($file);
                }
                $image_path = base_url() . 'media/front/img/address-picture/' . $file_name;
            } else {

                if (!empty($_FILES['building_photo'])) {
                    if ($_FILES['building_photo']['tmp_name'] != "") {
                        $config['upload_path'] = './media/front/img/address-picture/';
                        $config['allowed_types'] = '*';
                        $config['max_size'] = '100000000000000';
                        $config['max_width'] = '12024';
                        $config['max_height'] = '7268';
                        $config['file_name'] = rand();
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        //loading uploda library
                        if (!$this->upload->do_upload('building_photo')) {
                            $error = array('error' => $this->upload->display_errors());
                            $response_arr = array('msg' => 'Failed', 'Response' => $error['error']);
                            echo json_encode($response_arr);
                            exit;
                        } else {
                            $data = array('upload_data' => $this->upload->data());
                            $image_data = $this->upload->data();
                            $file_name = $image_data['file_name'];

                            $absolute_path = $this->common_model->absolutePath();
                            $image_path = $absolute_path . "media/front/img/address-picture";
                            $image_main = $image_path . "/" . $file_name;

                            $thumbs_image2 = $image_path . "/thumbs/" . $file_name;
                            $str_console2 = "convert " . $image_main . " -resize 120!X120! " . $thumbs_image2;
                            exec($str_console2);

                            $image_path = base_url() . 'media/front/img/address-picture/' . $file_name;
                        }
                    }
                }
            }

            if ($is_current_add_same_as_permanant_add != '' && $is_current_add_same_as_permanant_add == '1') {
                $condition_to_pass = "user_id_fk = '" . $user_id . "' AND address_name_id_fk IN ($address_name_id,$permanant_address_name_id)";
                $chk_address_is_exists = $this->common_model->getRecords('mst_user_addresses', '', $condition_to_pass);
                if (COUNT($chk_address_is_exists) > 0) {
                    foreach ($chk_address_is_exists as $existing_address) {
                        if ($existing_address['address_name_id_fk'] == $address_name_id) {
                            $alternate_id = $permanant_address_name_id;
                        } else {
                            $alternate_id = $address_name_id;
                        }
                        if (COUNT($chk_address_is_exists) == '1') {
                            $update_data = array(
                                'country_id' => $country_id,
                                'state_id' => $state_id,
                                'city_id' => $city_id,
                                'zip_code' => $zipcode,
                                'address_line1' => $address1,
                                'address_line2' => $address2,
                                'address_picture' => $file_name,
                                'latitude' => $lat,
                                'longitude' => $long,
                                'updated_date' => date('Y-m-d H:i:s'),
                                'is_current_add_same_as_permanant_add' => $is_current_add_same_as_permanant_add,
                                'current_location_same_as_above_flag' => $current_location_same_as_above_flag
                            );
                            $condition = array(
                                'address_id' => $existing_address['address_id'],
                            );
                            $this->common_model->updateRow('mst_user_addresses', $update_data, $condition);

                            $insert_data = array(
                                'user_id_fk' => $user_id,
                                'address_name_id_fk' => $alternate_id,
                                'country_id' => $country_id,
                                'state_id' => $state_id,
                                'city_id' => $city_id,
                                'zip_code' => $zipcode,
                                'address_line1' => $address1,
                                'address_line2' => $address2,
                                'address_picture' => $file_name,
                                'latitude' => $lat,
                                'longitude' => $long,
                                'created_date' => date('Y-m-d H:i:s'),
                                'type_flag' => '1',
                                'is_forwarded' => '0',
                                'is_current_add_same_as_permanant_add' => $is_current_add_same_as_permanant_add,
                                'current_location_same_as_above_flag' => $current_location_same_as_above_flag
                            );
                            $this->common_model->insertRow($insert_data, 'mst_user_addresses');
                            if ($address_type_id == '3') {
                                //$message1 = "You have not provided access to any of your contacts to view your address. To provide access, please go to your profile, click on each address name to provide access.";
                                $message1 = "You have not provided access to any of your contacts to view your address. To provide access, visit your Profile, click on an Address and provide suitable access using Access Lists.";
                                $subject1 = "Give access to contacts";
                                //$message2 = "We provide various services associated with each address. To avail so, please go to your profile, select each address and add related services.";
                                $message2 = "P C O offers various services associated with each address. To avail these services, please visit your profile, select an address and add appropriate services.";
                                $subject2 = " Add Services";
                                $table_name1 = "mst_notifications";
                                $insert_data1 = array(
                                    'notification_from' => $user_id,
                                    'notification_to' => $user_id,
                                    'subject' => $subject1,
                                    'message' => $message1,
                                    'notification_date' => date('Y-m-d H:i:s')
                                );
                                $this->common_model->insertRow($insert_data1, $table_name1);

                                $table_name2 = "mst_notifications";
                                $insert_data2 = array(
                                    'notification_from' => $user_id,
                                    'notification_to' => $user_id,
                                    'subject' => $subject2,
                                    'message' => $message2,
                                    'notification_date' => date('Y-m-d H:i:s')
                                );
                                $this->common_model->insertRow($insert_data2, $table_name2);
                                $this->getReciepentUser($user_id);
                            }
                        } else {
                            $update_data = array(
                                'country_id' => $country_id,
                                'state_id' => $state_id,
                                'city_id' => $city_id,
                                'zip_code' => $zipcode,
                                'address_line1' => $address1,
                                'address_line2' => $address2,
                                'address_picture' => $file_name,
                                'latitude' => $lat,
                                'longitude' => $long,
                                'updated_date' => date('Y-m-d H:i:s'),
                                'is_current_add_same_as_permanant_add' => $is_current_add_same_as_permanant_add,
                                'current_location_same_as_above_flag' => $current_location_same_as_above_flag
                            );
                            $condition = array(
                                'address_id' => $existing_address['address_id'],
                            );
                            $this->common_model->updateRow('mst_user_addresses', $update_data, $condition);


                            $arr_users_access_details = $this->common_model->getRecords('mst_user_contacts_access', '', array('user_id_fk' => $user_id, 'user_address_id_fk' => $existing_address['address_id']));
                            $arr_login_users_details = $this->web_services_model->getContactName($user_id);
                            if (COUNT($arr_users_access_details) > 0) {
                                if ($address_type_id == '2') {
                                    $message3 = $arr_login_users_details[0]['contact_name'] . ' with mobile number ' . $arr_login_users_details[0]['contact_phone'] . ' has changed his Current Home Address.';
                                } else if ($address_type_id == '3') {
                                    $message3 = $arr_login_users_details[0]['contact_name'] . ' with mobile number ' . $arr_login_users_details[0]['contact_phone'] . ' has changed his Permanent Home Address.';
                                } else if ($address_type_id == '5') {
                                    $message3 = $arr_login_users_details[0]['contact_name'] . ' with mobile number ' . $arr_login_users_details[0]['contact_phone'] . ' has changed his Office Address.';
                                }

                                foreach ($arr_users_access_details as $arr_users) {
                                    $table_name2 = "mst_notifications";
                                    $insert_data2 = array(
                                        'notification_from' => $user_id,
                                        'notification_to' => $arr_users['access_to_fk'],
                                        'subject' => "Address Updated",
                                        'message' => $message3,
                                        'notification_date' => date('Y-m-d H:i:s')
                                    );
                                    $this->common_model->insertRow($insert_data2, $table_name2);
                                }
                            }
                        }
                    }
                    $response_arr = array('msg' => 'Success', 'Response' => "Records updated successfully.", "building_image" => $file_name);
                } else {
                    $insert_data = array(
                        'user_id_fk' => $user_id,
                        'address_name_id_fk' => $address_name_id,
                        'country_id' => $country_id,
                        'state_id' => $state_id,
                        'city_id' => $city_id,
                        'zip_code' => $zipcode,
                        'address_line1' => $address1,
                        'address_line2' => $address2,
                        'address_picture' => $file_name,
                        'latitude' => $lat,
                        'longitude' => $long,
                        'created_date' => date('Y-m-d H:i:s'),
                        'type_flag' => '1',
                        'is_forwarded' => '0',
                        'is_current_add_same_as_permanant_add' => $is_current_add_same_as_permanant_add,
                        'current_location_same_as_above_flag' => $current_location_same_as_above_flag
                    );
                    $this->common_model->insertRow($insert_data, 'mst_user_addresses');

                    $insert_data = array(
                        'user_id_fk' => $user_id,
                        'address_name_id_fk' => $permanant_address_name_id,
                        'country_id' => $country_id,
                        'state_id' => $state_id,
                        'city_id' => $city_id,
                        'zip_code' => $zipcode,
                        'address_line1' => $address1,
                        'address_line2' => $address2,
                        'address_picture' => $file_name,
                        'latitude' => $lat,
                        'longitude' => $long,
                        'created_date' => date('Y-m-d H:i:s'),
                        'type_flag' => '1',
                        'is_forwarded' => '0',
                        'is_current_add_same_as_permanant_add' => $is_current_add_same_as_permanant_add,
                        'current_location_same_as_above_flag' => $current_location_same_as_above_flag
                    );
                    $this->common_model->insertRow($insert_data, 'mst_user_addresses');
                    $response_arr = array('msg' => 'Success', 'Response' => "Records inserted successfully.", "building_image" => $file_name);
                }

                if ($address_type_id == '5') {
                    $update_data = array(
                        'user_step' => '5'
                    );
                } else {
                    $update_data = array(
                        'user_step' => '4'
                    );
                }

                $condition = array('user_id' => $user_id);
                $this->common_model->updateRow('mst_users', $update_data, $condition);
            } else {
                $condition_to_pass = array(
                    'user_id_fk' => $user_id,
                    'address_name_id_fk' => $address_name_id
                );
                $chk_address_is_exists = $this->common_model->getRecords('mst_user_addresses', '', $condition_to_pass);
                if (COUNT($chk_address_is_exists) > 0) {
                    $update_data = array(
                        'country_id' => $country_id,
                        'state_id' => $state_id,
                        'city_id' => $city_id,
                        'zip_code' => $zipcode,
                        'address_line1' => $address1,
                        'address_line2' => $address2,
                        'address_picture' => $file_name,
                        'latitude' => $lat,
                        'longitude' => $long,
                        'updated_date' => date('Y-m-d H:i:s'),
                        'is_current_add_same_as_permanant_add' => $is_current_add_same_as_permanant_add,
                        'current_location_same_as_above_flag' => $current_location_same_as_above_flag
                    );
                    $condition = array(
                        'user_id_fk' => $user_id,
                        'address_name_id_fk' => $address_name_id
                    );
                    $this->common_model->updateRow('mst_user_addresses', $update_data, $condition);
                    $response_arr = array('msg' => 'Success', 'Response' => "Records updated successfully.", "building_image" => $file_name);
                } else {
                    $insert_data = array(
                        'user_id_fk' => $user_id,
                        'address_name_id_fk' => $address_name_id,
                        'country_id' => $country_id,
                        'state_id' => $state_id,
                        'city_id' => $city_id,
                        'zip_code' => $zipcode,
                        'address_line1' => $address1,
                        'address_line2' => $address2,
                        'address_picture' => $file_name,
                        'latitude' => $lat,
                        'longitude' => $long,
                        'created_date' => date('Y-m-d H:i:s'),
                        'type_flag' => '1',
                        'is_forwarded' => '0',
                        'is_current_add_same_as_permanant_add' => $is_current_add_same_as_permanant_add,
                        'current_location_same_as_above_flag' => $current_location_same_as_above_flag
                    );
                    $last_insert_id = $this->common_model->insertRow($insert_data, 'mst_user_addresses');

                    if ($address_type_id == '2') {
                        $update_data = array(
                            'user_step' => '3'
                        );
                    } else if ($address_type_id == '3') {
                        $update_data = array(
                            'user_step' => '4'
                        );

                        $message1 = "You have not provided access to any of your contacts to view your address. To provide access, visit your Profile, click on an Address and provide suitable access using Access Lists.";
                        $subject1 = "Give access to contacts";
                        $message2 = "P C O offers various services associated with each address. To avail these services, please visit your profile, select an address and add appropriate services.";
                        $subject2 = " Add Services";
                        $table_name1 = "mst_notifications";
                        $insert_data1 = array(
                            'notification_from' => $user_id,
                            'notification_to' => $user_id,
                            'subject' => $subject1,
                            'message' => $message1,
                            'notification_date' => date('Y-m-d H:i:s')
                        );
                        $this->common_model->insertRow($insert_data1, $table_name1);

                        $table_name2 = "mst_notifications";
                        $insert_data2 = array(
                            'notification_from' => $user_id,
                            'notification_to' => $user_id,
                            'subject' => $subject2,
                            'message' => $message2,
                            'notification_date' => date('Y-m-d H:i:s')
                        );
                        $this->common_model->insertRow($insert_data2, $table_name2);
                        $this->getReciepentUser($user_id);
                    } else {
                        $update_data = array(
                            'user_step' => '5'
                        );
                    }
                    $condition = array('user_id' => $user_id);
                    $this->common_model->updateRow('mst_users', $update_data, $condition);
                    $response_arr = array('msg' => 'Success', 'Response' => "Records inserted successfully.", "building_image" => $file_name);
                }
            }
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function updateDeviceToken() {
        $user_id = $this->input->post('user_id');
        $device_token = $this->input->post('device_token');
        if ($user_id != '' && $device_token != '') {
            $update_data = array(
                'push_notification_id' => $device_token
            );
            $condition = array('user_id' => $user_id);
            $this->common_model->updateRow('mst_users', $update_data, $condition);
            $response_arr = array('msg' => 'Success', 'Response' => "Records updated successfully.");
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function login() {
        $country_id = $this->input->post('country_id');
        $mobile_no = $this->input->post('mobile_no');
        $user_password = $this->input->post('password');

        if ($country_id != '' && $mobile_no != '' && $user_password != '') {
            //checking mobile number is exits or not
            $condition_to_pass = array('phone_number' => $mobile_no);
            $arr_user_details_from_mob = $this->common_model->getRecords('mst_users', 'user_id,title,first_name,last_name,middle_name,user_email,user_birth_date,profile_picture,user_step,user_password', $condition_to_pass);
            if (COUNT($arr_user_details_from_mob) > 0) {

                //checking mobile number is exits or not with country code
                $condition_to_pass = array('phone_number' => $mobile_no, 'country_id' => $country_id);
                $arr_user_details_from_mob_and_country = $this->common_model->getRecords('mst_users', 'user_id,title,first_name,last_name,middle_name,user_email,user_birth_date,profile_picture,user_step,user_password', $condition_to_pass);
                if (COUNT($arr_user_details_from_mob_and_country) > 0) {

                    $password = crypt($user_password, $arr_user_details_from_mob[0]['user_password']);
                    $condition_to_pass2 = array(
                        'phone_number' => $mobile_no,
                        'user_password' => $password
                    );
                    $arr_user_details = $this->common_model->getRecords('mst_users', 'user_id,title,first_name,last_name,middle_name,user_email,user_birth_date,profile_picture,user_step', $condition_to_pass2);
                    if (COUNT($arr_user_details) > 0) {

                        //checking address name is already exists for this user id
                        $condition_to_pass = array('user_id_fk' => $arr_user_details[0]['user_id']);
                        $arr_check_adress_name = $this->common_model->getRecords('mst_user_addresses_name', '', $condition_to_pass);

                        if ($arr_user_details[0]['user_step'] != '' && $arr_user_details[0]['user_step'] == '1') {
                            $response_arr = array('msg' => 'Success', 'user_id' => $arr_user_details[0]['user_id']);
                        } else if ($arr_user_details[0]['user_step'] != '' && $arr_user_details[0]['user_step'] == '2') {
                            $response_arr = array('msg' => 'Success', 'profile_details' => $arr_user_details, 'address_name' => $arr_check_adress_name, 'user_step' => $arr_user_details[0]['user_step']);
                        } else if ($arr_user_details[0]['user_step'] != '' && $arr_user_details[0]['user_step'] == '3') {
                            $condition_to_pass = array(
                                'at.address_type_id' => '2',
                                'ua.user_id_fk' => $arr_user_details[0]['user_id']
                            );
                            $arr_current_address = $this->web_services_model->getUserAddressDetails($condition_to_pass);
                            $response_arr = array('msg' => 'Success', 'profile_details' => $arr_user_details, 'current_address' => $arr_current_address, 'address_name' => $arr_check_adress_name, 'user_step' => $arr_user_details[0]['user_step']);
                        } else if ($arr_user_details[0]['user_step'] != '' && $arr_user_details[0]['user_step'] == '4') {
                            $condition_to_pass = array(
                                'at.address_type_id' => '3',
                                'ua.user_id_fk' => $arr_user_details[0]['user_id']
                            );
                            $arr_permanent_address = $this->web_services_model->getUserAddressDetails($condition_to_pass);
                            $condition_to_pass = array(
                                'at.address_type_id' => '2',
                                'ua.user_id_fk' => $arr_user_details[0]['user_id']
                            );
                            $arr_current_address = $this->web_services_model->getUserAddressDetails($condition_to_pass);
                            $response_arr = array('msg' => 'Success', 'profile_details' => $arr_user_details, 'current_address' => $arr_current_address, 'permanent_address' => $arr_permanent_address, 'address_name' => $arr_check_adress_name, 'user_step' => $arr_user_details[0]['user_step']);
                        } else if ($arr_user_details[0]['user_step'] != '' && $arr_user_details[0]['user_step'] == '5') {
                            $condition_to_pass = array(
                                'at.address_type_id' => '5',
                                'ua.user_id_fk' => $arr_user_details[0]['user_id']
                            );
                            $arr_office_address = $this->web_services_model->getUserAddressDetails($condition_to_pass);

                            $condition_to_pass = array(
                                'at.address_type_id' => '3',
                                'ua.user_id_fk' => $arr_user_details[0]['user_id']
                            );
                            $arr_permanent_address = $this->web_services_model->getUserAddressDetails($condition_to_pass);
                            $condition_to_pass = array(
                                'at.address_type_id' => '2',
                                'ua.user_id_fk' => $arr_user_details[0]['user_id']
                            );
                            $arr_current_address = $this->web_services_model->getUserAddressDetails($condition_to_pass);

                            $response_arr = array('msg' => 'Success', 'profile_details' => $arr_user_details, 'current_address' => $arr_current_address, 'permanent_address' => $arr_permanent_address, 'office_address' => $arr_office_address, 'address_name' => $arr_check_adress_name, 'user_step' => $arr_user_details[0]['user_step']);
                        } else {
                            $response_arr = array('msg' => 'Failed');
                        }
                        echo json_encode($response_arr);
                    } else {
                        $response_arr = array('msg' => 'Failed. Please check your mobile number or password ypu entered.');
                        echo json_encode($response_arr);
                    }
                } else {
                    $response_arr = array('msg' => 'Failed. Mobile number not exits with this country.');
                    echo json_encode($response_arr);
                }
            } else {
                $response_arr = array('msg' => 'Failed. Mobile number is not registered.');
                echo json_encode($response_arr);
            }
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function forgotPassword() {
        $data = $this->common_model->commonFunction();
        $mobile_no = $this->input->post('mobile_no');
        $country_id = $this->input->post('country_id');

//        $mobile_no = "3333333333";
//        $country_id = "598";
        if ($mobile_no != '') {
            $condition_to_mobile = array('phone_number' => $mobile_no);
            $chk_mobile_exists = $this->common_model->getRecords('mst_users', 'user_id,first_name,last_name,phone_number,user_step', $condition_to_mobile);
            if (COUNT($chk_mobile_exists) > 0) {
                $condition_to_country = array('phone_number' => $mobile_no, 'country_id' => $country_id);
                $arr_user_details = $this->common_model->getRecords('mst_users', 'user_id,user_email,first_name,last_name,phone_number,user_step', $condition_to_country);
                if (COUNT($arr_user_details) > 0) {
                    $response_arr = array('msg' => 'Success. Reset password link has been sent to your email.', 'user_step' => $arr_user_details[0]['user_step']);
                    echo json_encode($response_arr);

                    //sending mail  to user 
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
//                        $mail = $this->common_model->sendEmail($recipeinets, $from, $subject, $message);
                    } else {
                        $six_digit_otp_number = mt_rand(1000, 9999);
                        $update_data = array(
                            'otp_code' => $six_digit_otp_number,
                            'type' => 'forgotPassword',
                            'status' => '0'
                        );
                        $this->common_model->updateRow('mst_mobile_number_and_otp_details', $update_data, array('mobile_number' => $mobile_no));
                        $response_arr = array('msg' => 'Success', 'otp_code' => $six_digit_otp_number, 'user_step' => $arr_user_details[0]['user_step']);
                        echo json_encode($response_arr);
                    }
                } else {
                    $response_arr = array('msg' => 'Failed. Please check your country code.');
                    echo json_encode($response_arr);
                }
            } else {
                $response_arr = array('msg' => 'Failed. Please enter regitered mobile number.');
                echo json_encode($response_arr);
            }
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function profileDetails() {
        $user_id = $this->input->post('user_id');
        if ($user_id != '') {
            $condition_to_pass = array('user_id' => $user_id);
            $arr_user_details = $this->common_model->getRecords('mst_users', 'title,first_name,last_name,middle_name,profile_picture,user_birth_date,user_email,gender,user_step,email_verified,phone_number', $condition_to_pass);
            if (COUNT($arr_user_details) > 0) {
                if ($arr_user_details[0]['email_verified'] == '1') {
                    $arr_user_details[0]['email_verified'] = 'Verified';
                } else {
                    $arr_user_details[0]['email_verified'] = 'Not_Verified';
                }
                $response_arr = array('msg' => 'Success', 'profile_details' => $arr_user_details);
                echo json_encode($response_arr);
            } else {
                $response_arr = array('msg' => 'Failed');
                echo json_encode($response_arr);
            }
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function notificationDetails() {
        $user_id = $this->input->post('user_id');
        if ($user_id != '') {
            $condition_to_pass = array('notification_to' => $user_id);
            $arr_notification_details = $this->common_model->getRecords('mst_notifications', '', $condition_to_pass);
            if (COUNT($arr_notification_details) > 0) {
                $response_arr = array('msg' => 'Success', 'notification_details' => $arr_notification_details);
                echo json_encode($response_arr);
            } else {
                $response_arr = array('msg' => 'Failed');
                echo json_encode($response_arr);
            }
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function deleteNotification() {
        $notification_id = $this->input->post('notification_id');
        if ($notification_id != '') {
            $arr_delete_array = array('notification_id' => $notification_id);
            $delete_arr = $this->common_model->deleteRows($arr_delete_array, 'mst_notifications', 'notification_id');
            $response_arr = array('msg' => 'Success');
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function addContact() {
        $user_id = $this->input->post('user_id');
        $name = $this->input->post('name');
        $phone_no = $this->input->post('phone_no');
        $email = $this->input->post('email');
        if ($user_id != '' && $name != '' && $phone_no != '' && $email != '') {
            $insert_data = array(
                'user_id_fk' => $user_id,
                'contact_name' => $name,
                'contact_phone' => $phone_no,
                'contact_email' => $email,
                'added_date' => date('Y-m-d H:i:s')
            );
            $this->common_model->insertRow($insert_data, 'mst_user_contacts');
            $response_arr = array('msg' => 'Success.Contact added successfully.');
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function uploadProfilePicture() {
        $user_id = $this->input->post('user_id');
        $device = $this->input->post('device');
        $condition = array('user_id' => $user_id);
        $arr_user_details = $this->common_model->getRecords('mst_users', '', $condition);
        if ($user_id != '' && COUNT($arr_user_details) > 0) {
            if (!empty($_FILES['profile_image_name'])) {
                $file_name = '';
                if ($device == 'A') {
                    if (isset($_FILES['profile_image_name']['tmp_name'])) { //echo 'If second';
                        $file_name = time() . ".jpg";
                        move_uploaded_file($_FILES['profile_image_name']['tmp_name'], "media/front/img/user-profile-pictures/" . $file_name);
                    }

                    if ($this->input->post('profile_image_name') != '') {
                        $base = $this->input->post('profile_image_name');
                        $binary = base64_decode($base);
                        $file_name = uniqid() . time() . '.jpg';
                        $file = fopen("media/front/img/user-profile-pictures/" . $user_profile_file, 'wb');
                        fwrite($file, $binary);
                        fclose($file);
                    }
                } else {


                    $image_path = '';

                    if ($_FILES['profile_image_name']['tmp_name'] != "") {
                        $config['upload_path'] = './media/front/img/user-profile-pictures/';
                        $config['allowed_types'] = '*';
                        $config['max_size'] = '100000000000000';
                        $config['max_width'] = '12024';
                        $config['max_height'] = '7268';
                        $config['file_name'] = rand();
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        //loading uploda library
                        if (!$this->upload->do_upload('profile_image_name')) {
                            $error = array('error' => $this->upload->display_errors());
                            $response_arr = array('msg' => 'Failed', 'Response' => $error['error']);
                            echo json_encode($response_arr);
                            exit;
                        } else {
                            $data = array('upload_data' => $this->upload->data());
                            $image_data = $this->upload->data();
                            $file_name = $image_data['file_name'];

                            $absolute_path = $this->common_model->absolutePath();
                            $image_path = $absolute_path . "media/front/img/user-profile-pictures/";
                            $image_main = $image_path . "/" . $file_name;

                            $thumbs_image2 = $image_path . "/thumb/" . $file_name;
                            $str_console2 = "convert " . $image_main . " -resize 120!X120! " . $thumbs_image2;
                            exec($str_console2);

                            $image_path = base_url() . 'media/front/img/user-profile-pictures/thumb/' . $file_name;
                        }
                    }
                    $update_data = array(
                        'profile_picture' => $file_name,
                    );
                    $condition = array('user_id' => $user_id);
                    $this->common_model->updateRow('mst_users', $update_data, $condition);
                    $response_arr = array('msg' => 'Success', 'file_name' => $file_name, 'profile_image_path' => $image_path);
                    echo json_encode($response_arr);
                }
            } else {
                $response_arr = array('msg' => 'Failed');
                echo json_encode($response_arr);
            }
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function userAddress() {
        $user_id = $this->input->post('user_id');
        $address_name_id = $this->input->post('address_name_id');
        $address_id = $this->input->post('address_id');

        if ($user_id != '' && $address_name_id != '') {
            $flag_image_path = base_url() . "media/backend/img/country-flag/thumbs/";

            if ($address_id != '') {
                $condition_to_pass = array('ua.user_id_fk' => $user_id, 'ua.address_id' => $address_id);
            } else {
                $condition_to_pass = array('ua.user_id_fk' => $user_id, 'ua.address_name_id_fk' => $address_name_id);
            }
            $arr_user_address = $this->web_services_model->userAddressByAddressNameId($condition_to_pass);
            if (COUNT($arr_user_address) > 0) {
                $flag_path = $flag_image_path . $arr_user_address[0]['flag'];
                $response_arr = array('msg' => 'Success', 'user_address' => $arr_user_address[0], 'flag' => $flag_path);
                echo json_encode($response_arr);
            } else {
                $response_arr = array('msg' => 'Failed');
                echo json_encode($response_arr);
            }
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function sendAllContactDetails() {
        $data = $this->common_model->commonFunction();
        $json_encode_arr = $this->input->post('json_array');
        $user_id = $this->input->post('user_id');

        $existing_users_in_app_array = array();
        $json_decode_arr = json_decode($json_encode_arr, true);
        if ($json_decode_arr != '' && $user_id != '') {
            foreach ($json_decode_arr['contact_details'] as $synch_contacts) {
                if ($synch_contacts['email'] != '') {
                    $email_add = $synch_contacts['email'];
                } else {
                    $email_add = '';
                }
                $phone_number = $synch_contacts['Phone'];
                $phone_number_original = $synch_contacts['Phone_original'];
                //getting existing users detials in the app
                $condition_to_pass = array('phone_number' => $phone_number, 'user_id !=' => $user_id, 'user_type' => '1');
                $check_user_exists_on_app = $this->common_model->getRecords('mst_users', 'user_id,phone_number,user_email,profile_picture', $condition_to_pass);
                if (COUNT($check_user_exists_on_app) > 0) {
                    foreach ($check_user_exists_on_app as $key => $existing_users_in_app) {
                        //checking contact already exists in contact list with login user id
                        $condition_of_contact = array('user_id_fk' => $user_id, 'contact_user_id_fk' => $existing_users_in_app['user_id']);
                        $arr_get_contact_details = $this->common_model->getRecords('mst_user_contacts', '', $condition_of_contact);
                        $check_user_exists_on_app[$key]['contact_name'] = $arr_get_contact_details[0]['contact_name'];
                        if (COUNT($arr_get_contact_details) == 0) {
                            $insert_data = array(
                                'user_id_fk' => $user_id,
                                'contact_name' => $synch_contacts['name'],
                                'contact_phone' => $phone_number,
                                'contact_email' => $email_add,
                                'added_date' => date('Y-m-d H:i:s'),
                                'contact_user_id_fk' => $existing_users_in_app['user_id']
                            );
                            $this->common_model->insertRow($insert_data, 'mst_user_contacts');
                        }

                        $existing_users_in_app_array[] = array(
                            'name' => $synch_contacts['name'],
                            'email' => $email_add,
                            'phone' => $phone_number,
                            'Phone_original' => $phone_number_original,
                            'images' => $existing_users_in_app['profile_picture'],
                            'user_id' => $existing_users_in_app['user_id'],
                            'flag' => 'Yes',
                            'is_blocked' => $arr_get_contact_details[0]['is_blocked'],
                            'user_contact_id' => $arr_get_contact_details[0]['user_contact_id']
                        );
                    }
                } else {
                    $existing_users_in_app_array[] = array(
                        'name' => $synch_contacts['name'],
                        'email' => $email_add,
                        'phone' => $phone_number,
                        'Phone_original' => $phone_number_original,
                        'images' => '',
                        'user_id' => '',
                        'flag' => 'No',
                        'is_blocked' => '1',
                        'user_contact_id' => ''
                    );

                    //checking contact already exists in contact list with login user id
                    $condition_of_contact = array('user_id_fk' => $user_id, 'contact_phone' => $phone_number);
                    $arr_get_contact_details = $this->common_model->getRecords('mst_user_contacts', '', $condition_of_contact);
                    if (COUNT($arr_get_contact_details) == 0) {
                        $insert_data = array(
                            'user_id_fk' => $user_id,
                            'contact_name' => $synch_contacts['name'],
                            'contact_phone' => $phone_number,
                            'contact_email' => $email_add,
                            'added_date' => date('Y-m-d H:i:s')
                        );
                        $this->common_model->insertRow($insert_data, 'mst_user_contacts');
                    }
                }
            }
            $response_arr = array('msg' => 'Success, Your contacts has been synchronised successfully.', 'contact_users' => $existing_users_in_app_array);
            echo json_encode($response_arr);

            //sending mail to all the user 
            if (COUNT($check_user_exists_on_app) > 0) {
                foreach ($check_user_exists_on_app as $existing_users_in_app) {

                    //getting new regitered users details by user_id
                    $condition = array('user_id' => $user_id);
                    $arr_user_details = $this->common_model->getRecords('mst_users', 'user_id,first_name,last_name,phone_number', $condition);


                    $lang_id = '17';
                    $macros_array_detail = $this->common_model->getRecords('mst_email_template_macros', 'macros,value', $condition_to_pass = '', $order_by = '', $limit = '', $debug = 0);
                    $macros_array = array();
                    foreach ($macros_array_detail as $row) {
                        $macros_array[$row['macros']] = $row['value'];
                    }
                    $reserved_words = array();

                    if ($existing_users_in_app['contact_name'] != '') {
                        $user_name = $existing_users_in_app['contact_name'];
                    } else {
                        $user_name = $arr_user_details[0]['first_name'] . ' ' . $arr_user_details[0]['last_name'] . ' with mobile number ' . $arr_user_details[0]['phone_number'];
                    }


                    $reserved_arr = array(
                        "||USER_NAME||" => $synch_contacts['name'],
//                        "||APP_NEW_USER_NAME||" => $arr_user_details[0]['first_name'] . ' ' . $arr_user_details[0]['last_name'],
                        "||APP_NEW_USER_NAME||" => $user_name,
                        "||SITE_TITLE||" => $data['global']['site_title']
                    );
                    $reserved_words = array_replace_recursive($macros_array, $reserved_arr);
                    $template_title = 'new-user-registered-on-app';
                    $arr_emailtemplate_data = $this->common_model->getEmailTemplateInfo($template_title, $lang_id, $reserved_words);
                    $recipeinets = $existing_users_in_app['user_email'];
                    $from = array("email" => $data['global']['contact_email'], "name" => $data['global']['site_title']);
                    $subject = $arr_emailtemplate_data['subject'];
                    $message = $arr_emailtemplate_data['content'];
//                    $mail = $this->common_model->sendEmail($recipeinets, $from, $subject, $message);
                    $arr_notification_details = $this->common_model->getRecords('mst_notifications', '', array('notification_from' => $user_id, 'notification_to' => $existing_users_in_app['user_id']));
                    if (count($arr_notification_details) == 0) {
                        if ($user_id != $existing_users_in_app['user_id']) {
                            $table_name = "mst_notifications";
                            $insert_data = array(
                                'notification_from' => $user_id,
                                'notification_to' => $existing_users_in_app['user_id'],
                                'subject' => $subject,
                                'message' => $message,
                                'notification_date' => date('Y-m-d H:i:s')
                            );
                            $this->common_model->insertRow($insert_data, $table_name);
                        }
                    }
                }
            }
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function getAllContacts() {
        $user_id = $this->input->post('user_id');
        if ($user_id != '') {
            $condition = array
                ('user_id_fk' => $user_id);
            $arr_user_contact = $this->common_model->getRecords('mst_user_contacts', 'contact_name,contact_phone,contact_email,contact_user_id_fk', $condition);
            if (COUNT($arr_user_contact) > 0) {
                $response_arr = array('msg' => 'Success', 'user_contacts' => $arr_user_contact);
                echo json_encode($response_arr);
            } else {
                $response_arr = array('msg' => 'Failed, Contacts not found.');
                echo json_encode($response_arr);
            }
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function updateUserDetails() {
        $user_id = $this->input->post('user_id');
        $title = $this->input->post('title');
        $first_name = $this->input->post('first_name');
        $middle_name = $this->input->post('middle_name');
        $last_name = $this->input->post('last_name');
        $date_of_birth = $this->input->post('date_of_birth');
        $email = $this->input->post('email');
        $gender = $this->input->post('gender');
        $device = $this->input->post('device');
        if ($user_id != '' && $title != '' && $first_name != '' && $last_name != '' && $email != '') {

            //Check email is exists or not
            $condition_for_mobile = array
                ('user_id' => $user_id);
            $chk_mobile_exists = $this->common_model->getRecords('mst_users', '', $condition_for_mobile);
            $file_name = '';

            if ($device == 'A') {
                if (isset($_FILES['profile_image_name']['tmp_name'])) { //echo 'If second';
                    $file_name = time() . ".jpg";
                    move_uploaded_file($_FILES['profile_image_name']['tmp_name'], "media/front/img/user-profile-pictures/" . $file_name);
                }

                if ($this->input->post('profile_image_name') != '') {
                    $base = $this->input->post('profile_image_name');
                    $binary = base64_decode($base);
                    $file_name = uniqid() . time() . '.jpg';
                    $file = fopen("media/front/img/user-profile-pictures/" . $user_profile_file, 'wb');
                    fwrite($file, $binary);
                    fclose($file);
                }
            } else {

                $image_path = '';
                $profile_picture = '';
                if (!empty($_FILES['profile_image_name'])) {
                    if ($_FILES['profile_image_name']['tmp_name'] != "") {
                        $config['upload_path'] = './media/front/img/user-profile-pictures/';
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['max_size'] = '100000000000000';
                        $config['max_width'] = '12024';
                        $config['max_height'] = '7268';
                        $config['file_name'] = rand();
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        //loading uploda library
                        if (!$this->upload->do_upload('profile_image_name')) {
                            $error = array('error' => $this->upload->display_errors());
                            $response_arr = array('msg' => 'Failed', 'Response' => "Failed to upload image.");
                            echo json_encode($response_arr);
                            exit;
                        } else {
                            $data = array('upload_data' => $this->upload->data());
                            $image_data = $this->upload->data();
                            $file_name = $image_data['file_name'];

                            $absolute_path = $this->common_model->absolutePath();
                            $image_path = $absolute_path . "media/front/img/user-profile-pictures/";
                            $image_main = $image_path . "/" . $file_name;

                            $thumbs_image2 = $image_path . "/thumb/" . $file_name;
                            $str_console2 = "convert " . $image_main . " -resize 120!X120! " . $thumbs_image2;
                            exec($str_console2);

                            $image_path = base_url() . 'media/front/img/user-profile-pictures/' . $file_name;
                        }
                    }
                }
            }

            if ($file_name != '') {
                $profile_picture = $file_name;
            } else {
                $profile_picture = "";
            }

            if ($chk_mobile_exists[0]['user_email'] == $email) {
                //Updating user information with the help of user id
                $update_data = array(
                    'title' => $title,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'middle_name' => $middle_name,
                    'user_birth_date' => $date_of_birth,
                    'profile_picture' => $profile_picture,
                    'gender' => $gender
                );
                $condition = array('user_id' => $user_id);
                $this->common_model->updateRow('mst_users', $update_data, $condition);
                $response_arr = array('msg' => 'Success', 'profile_image_name' => $profile_picture);
            } else {
                $condition_for_mobile = array('user_email' => $email);
                $chk_email_exists = $this->common_model->getRecords('mst_users', '', $condition_for_mobile);
                if (COUNT($chk_email_exists) == 0) {
                    $update_data = array('title' => $title,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'middle_name' => $middle_name,
                        'user_birth_date' => $date_of_birth,
                        'profile_picture' => $profile_picture,
                        'user_email' => $email,
                        'gender' => $gender
                    );
                    $condition = array('user_id' => $user_id);
                    $this->common_model->updateRow('mst_users', $update_data, $condition);
                    $response_arr = array('msg' => 'Success', 'profile_image_name' => $profile_picture);
                } else {
                    $response_arr = array('msg' => 'Failed', 'result' => 'Email address already exists.');
                }
            }
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function validatePassword() {
        $user_id = $this->input->post('user_id');
        $password = $this->input->post('password');
        if ($user_id != '' && $password != '') {
            $condition_to_pass = array
                ('user_id' => $user_id);
            $arr_user_password = $this->common_model->getRecords('mst_users', 'user_password', $condition_to_pass);
            if (COUNT($arr_user_password) > 0) {
                $user_password = crypt($password, $arr_user_password[0] ['user_password']);
                $condition_to_pass = array('user_password' => $user_password);
                $arr_user_details = $this->common_model->getRecords('mst_users', '', $condition_to_pass);
                if (COUNT($arr_user_details) > 0) {
                    $response_arr = array('msg' => 'Success');
                } else {
                    $response_arr = array('msg' => 'Failed. Please enter valid password');
                }
                echo json_encode($response_arr);
            } else {
                $response_arr = array('msg' => 'Failed');
                echo json_encode($response_arr);
            }
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function getContactUserInformation() {
//        $login_user_id = "528";
//        $contact_user_id = "466";
        $login_user_id = $this->input->post('login_user_id');
        $contact_user_id = $this->input->post('contact_user_id');
        if ($login_user_id != '' && $contact_user_id != '') {
            $condition_to_pass = array(
                'uc.user_id_fk' => $login_user_id,
                'uc.contact_user_id_fk' => $contact_user_id
            );
            $arr_get_contact_user_info = $this->web_services_model->getContactUserInfo($condition_to_pass);

            $condition_to_pass1 = array(
                'uc.user_id_fk' => $contact_user_id,
                'uc.contact_user_id_fk' => $login_user_id
            );
            $arr_to_contact_user_info = $this->web_services_model->getContactUserInfo($condition_to_pass1);
            if (COUNT($arr_get_contact_user_info) > 0) {
                foreach ($arr_get_contact_user_info as $key => $value) {

                    $contact_info[] = $value;
                    $contact_info[$key]['block_from'] = $value['is_blocked'];
                    if (COUNT($arr_to_contact_user_info) > 0) {
                        $contact_info[$key]['block_to'] = $arr_to_contact_user_info[0]['is_blocked'];
                    } else {
                        $contact_info[$key]['block_to'] = '1';
                    }
                    $condition_to_pass2 = array('uc.user_id_fk' => $login_user_id, 'uc.contact_user_id_fk' => $contact_user_id);
                    $arr_get_access_details = $this->web_services_model->getUserAccessDetails($condition_to_pass2);

                    foreach ($arr_get_access_details as $access_details) {
                        $condition_to_pass = array('access_to_user_id_fk' => $login_user_id, 'access_from_user_id_fk' => $contact_user_id, 'user_address_id_fk' => $access_details['address_id']);
                        $arr_get_temprary_access_details = $this->common_model->getRecords('mst_user_temprary_access', '', $condition_to_pass);
                        $contact_info[$key][str_replace(' ', '', $access_details['address_type_text'])] = $access_details;

                        if (count($arr_get_temprary_access_details) > 0) {
                            $contact_info[$key][str_replace(' ', '', $access_details['address_type_text'])]['is_temprary_access'] = 'Yes';
                        } else {
                            $contact_info[$key][str_replace(' ', '', $access_details['address_type_text'])]['is_temprary_access'] = 'No';
                        }

                        if ($access_details['access_to_fk'] == $login_user_id) {
                            $contact_info[$key][str_replace(' ', '', $access_details['address_type_text'])]['is_access'] = 'Yes';
                        } else {
                            $contact_info[$key][str_replace(' ', '', $access_details['address_type_text'])]['is_access'] = 'No';
                        }
                    }
                }
//                echo '<pre>';
//                print_r($contact_info[0]);
//                die;
                $response_arr = array('msg' => 'Success', 'User_info' => $contact_info[0]);
            } else {
                $response_arr = array('msg' => 'Failed');
            }
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function giveAddressAccess() {
        $login_user_id = $this->input->post('login_user_id');
        $contact_user_id = $this->input->post('contact_user_id');
        $address_id = $this->input->post('address_id');
        $address_type_flag = $this->input->post('address_type_flag');
        if ($login_user_id != '' && $contact_user_id != '' && $address_id != '') {

            $condition_to_pass = array('user_id' => $login_user_id);
            $arr_login_user_details = $this->common_model->getRecords('mst_users', 'first_name,last_name,phone_number', $condition_to_pass);

            $condition_to_pass2 = array('user_id_fk' => $login_user_id, 'access_to_fk' => $contact_user_id, 'user_address_id_fk' => $address_id);
            $arr_details_exists = $this->common_model->getRecords('mst_user_contacts_access', '', $condition_to_pass2);

            $arr_user_details = $this->common_model->getRecords('mst_user_contacts', '', array('contact_phone' => $arr_login_user_details[0]['phone_number'], 'user_id_fk' => $contact_user_id));

            $arr_contact_details = $this->common_model->getRecords('mst_users', 'user_id,first_name,last_name,phone_number', array('phone_number' => $arr_login_user_details[0]['phone_number']));

            //if (COUNT($arr_details_exists) == 0) {
            $insert_data = array(
                'user_id_fk' => $login_user_id,
                'access_to_fk' => $contact_user_id,
                'user_address_id_fk' => $address_id
            );
            $this->common_model->insertRow($insert_data, 'mst_user_contacts_access');

            if ($address_type_flag == 'current') {
                $messge = " has requested you to provide access to your Current Home Address.";
            } else if ($address_type_flag == 'permanent') {
                $messge = " has requested you to provide access to your Permanent Home Address.";
            } else {
                $messge = " has requested you to provide access to your Office Address.";
            }

//            if (count($arr_user_details) > 0 && $arr_user_details[0]['contact_name'] != '') {
            if ($arr_user_details[0]['contact_name'] != '') {
                $message = $arr_user_details[0]['contact_name'] . " with Mobile Number " . $arr_user_details[0]['contact_phone'] . $messge;
//                $message = $arr_user_details[0]['contact_name'] . $messge;
            } else {
                $message = $arr_contact_details[0]['first_name'] . ' ' . $arr_contact_details[0]['last_name'] . " with Mobile Number " . $arr_contact_details[0]['phone_number'] . $messge;
            }

            $subject = 'Request For Access';
            $table_name = "mst_notifications";
            $insert_data = array(
                'notification_from' => $login_user_id,
                'notification_to' => $contact_user_id,
                'subject' => $subject,
                'message' => $message,
                'notification_date' => date('Y-m-d H:i:s')
            );
            $this->common_model->insertRow($insert_data, $table_name);
            $response_arr = array('msg' => 'Success');
            /* } else {
              $response_arr = array('msg' => 'You have already sent request for access.');
              } */
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function alerts() {
//        $login_user_id = '421';
        $login_user_id = $this->input->post('login_user_id');
        if ($login_user_id != '') {
            $condition_to_pass = array("n.notification_to" => $login_user_id);
            $arr_get_notification = $this->web_services_model->getUserNotification($condition_to_pass);
            if (COUNT($arr_get_notification) > 0) {
                for ($count = 0; $count < COUNT($arr_get_notification); $count++) {
                    $notification_data = $arr_get_notification[$count];
                    $arr_return[$count]['notification_id'] = $notification_data["notification_id"];
                    $arr_return[$count]['notification_to'] = $notification_data["notification_to"];
                    $arr_return[$count]['notification_from'] = $notification_data["notification_from"];
                    $arr_return[$count]['subject'] = strip_tags($notification_data["subject"]);
                    $arr_return[$count]['message'] = strip_tags($notification_data["message"]);
                    $arr_return[$count]['notification_status'] = $notification_data["notification_status"];
                    $arr_return[$count]['notification_date'] = $notification_data["notification_date"];
                    $arr_return[$count]['read_status'] = $notification_data["read_status"];
                    $arr_return[$count]['longitude'] = $notification_data["longitude"];
                    $arr_return[$count]['latitude'] = $notification_data["latitude"];
                }
                $response_arr = array('msg' => 'Success', 'all_notification' => $arr_return);
                echo json_encode($response_arr);
            } else {
                $response_arr = array('msg' => 'Notification not found.');
                echo json_encode($response_arr);
            }
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function addContactInAccessList() {
        $login_user_id = $this->input->post('login_user_id');
        $json_encode_arr = $this->input->post('json_array');
        $address_id = $this->input->post('address_id');
        $address_type_flag = $this->input->post('address_type_flag');

//        $login_user_id = '23';
//        $json_encode_arr = '{"contact_list":[{"contact_user_id":"919021246652"}]}';
//        $address_id = '45';
//        $address_type_flag = 'permanent';
        $json_decode_arr = json_decode($json_encode_arr, true);
        if ($json_encode_arr != '' && $address_id != '') {
            $condition_to_pass = array('user_id' => $login_user_id);

//            $this->web_services_model->deleteRows(array('user_id_fk' => $login_user_id), 'mst_user_contacts_access');
            $arr_access_user = $this->common_model->getRecords('mst_user_contacts_access', 'access_to_fk', array('user_id_fk' => $login_user_id));
            foreach ($arr_access_user as $access_user) {
                $arr_old_user[] = $access_user['access_to_fk'];
            }
            $this->web_services_model->deleteRows(array('user_id_fk' => $login_user_id, 'address_type_flag' => $address_type_flag), 'mst_user_contacts_access');
            foreach ($json_decode_arr['contact_list'] as $contact_list) {
                $arr_login_user_details = $this->web_services_model->getContactNameID($login_user_id, $contact_list["contact_user_id"]);
                if (!in_array($contact_list["contact_user_id"], $arr_old_user)) {
                    if ($address_type_flag == '2') {
                        $messge = " has granted you access for you to view his Current Home Address.";
                    } else if ($address_type_flag == '3') {
                        $messge = " has granted you access for you to view his Permanent Home Address.";
                    } else {
                        $messge = " has granted you access for you to view his Office Address.";
                    }

                    if (count($arr_login_user_details) > 0 && $arr_login_user_details[0]['contact_name'] != '') {
                        if ($arr_login_user_details[0]['contact_name'] != '') {
                            $contact = $arr_login_user_details[0]['contact_name'] . ' with mobile number ' . $arr_login_user_details[0]['contact_phone'];
                        } else {
                            $contact = $arr_login_user_details[0]['contact_phone'];
                        }
                    } else {
                        $arr_contact_details = $this->common_model->getRecords('mst_users', 'phone_number,first_name,last_name', array('user_id' => $login_user_id));
                        $contact = $arr_contact_details[0]['first_name'] . ' ' . $arr_contact_details[0]['last_name'] . ' with Mobile Number ' . $arr_contact_details[0]['phone_number'];
                    }

                    $message = $contact . $messge;
                    $subject = "Given Access";
                    $table_name = "mst_notifications";
                    $insert_data = array(
                        'notification_from' => $login_user_id,
                        'notification_to' => $contact_list['contact_user_id'],
                        'subject' => $subject,
                        'message' => $message,
                        'notification_date' => date('Y-m-d H:i:s')
                    );
                    $this->common_model->insertRow($insert_data, $table_name);
                }
                $insert_data = array(
                    'user_id_fk' => $login_user_id,
                    'access_to_fk' => $contact_list['contact_user_id'],
                    'user_address_id_fk' => $address_id,
                    'address_type_flag' => $address_type_flag
                );
                $this->common_model->insertRow($insert_data, 'mst_user_contacts_access');
            }
            $response_arr = array('msg' => 'Success');
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function viewAccessList() {
        $login_user_id = $this->input->post('login_user_id');
        $address_id = $this->input->post('address_id');
        if ($login_user_id != '' && $address_id != '') {
            $condition_to_pass = array(
                'uca.user_id_fk' => $login_user_id,
                'uca.user_address_id_fk' => $address_id
            );
            $arr_get_access_details = $this->web_services_model->getAddAccessList($condition_to_pass);
            if (COUNT($arr_get_access_details) > 0) {
                $response_arr = array('msg' => 'Success', 'add_access_list' => $arr_get_access_details);
            } else {
                $response_arr = array('msg' => 'Reocrds not found');
            }
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function deleteContactFronAccessList() {
        $json_encode_arr = $this->input->post('json_array');
//        $json_encode_arr = '{"contact_list" : [{"contact_access_id" : "3"},{"contact_access_id" : "2"}]}';
        $json_decode_arr = json_decode($json_encode_arr, true);
        if ($json_encode_arr != '') {
            foreach ($json_decode_arr['contact_list'] as $contact_id) {
                $this->common_model->deleteRows($contact_id, 'mst_user_contacts_access', 'contact_access_id');
            }
            $response_arr = array('msg' => 'Success');
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function addForwardingAddress() {
        $user_id = $this->input->post('user_id');
        $country_id = $this->input->post('country_id');
        $state_id = $this->input->post('state_id');
        $city_id = $this->input->post('city_id');
        $zipcode = $this->input->post('zipcode');
        $address1 = $this->input->post('address1');
        $address2 = $this->input->post('address2');
        $lat = $this->input->post('lat');
        $long = $this->input->post('long');
        $address_id = $this->input->post('address_id');
        $device = $this->input->post('device');
        $date_to = $this->input->post('date_to');
        $date_from = $this->input->post('date_from');
        $forwarded_flag = $this->input->post('forwarded_flag');
        if ($user_id != '' && $country_id != '' && $state_id != '' && $address1 != '' && $address_id != '' && $date_from != '' && $date_to != '') {
            $file_name = '';
            if ($device == 'A') {
                if (isset($_FILES['building_photo']['tmp_name'])) {
                    $file_name = time() . ".jpg";
                    move_uploaded_file($_FILES['building_photo']['tmp_name'], "media/front/img/address-picture/" . $file_name);
                }

                if ($this->input->post('building_photo') != '') {
                    $base = $this->input->post('building_photo');
                    $binary = base64_decode($base);
                    $file_name = uniqid() . time() . '.jpg';
                    $file = fopen("media/front/img/address-picture/" . $user_profile_file, 'wb');
                    fwrite($file, $binary);
                    fclose($file);
                }
            } else {
                $image_path = '';
                if (!empty($_FILES['building_photo'])) {
                    if ($_FILES['building_photo']['tmp_name'] != "") {
                        $config['upload_path'] = './media/front/img/address-picture/';
                        $config['allowed_types'] = '*';
                        $config['max_size'] = '100000000000000';
                        $config['max_width'] = '12024';
                        $config['max_height'] = '7268';
                        $config['file_name'] = rand();
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        //loading uploda library
                        if (!$this->upload->do_upload('building_photo')) {
                            $error = array('error' => $this->upload->display_errors());
                            $response_arr = array('msg' => 'Failed', 'Response' => $error['error']);
                            echo json_encode($response_arr);
                            exit;
                        } else {
                            $data = array('upload_data' => $this->upload->data());
                            $image_data = $this->upload->data();
                            $file_name = $image_data['file_name'];

                            $absolute_path = $this->common_model->absolutePath();
                            $image_path = $absolute_path . "media/front/img/address-picture";
                            $image_main = $image_path . "/" . $file_name;

                            $thumbs_image2 = $image_path . "/thumbs/" . $file_name;
                            $str_console2 = "convert " . $image_main . " -resize 120!X120! " . $thumbs_image2;
                            exec($str_console2);

                            $image_path = base_url() . 'media/front/img/address-picturethumbs/' . $file_name;
                        }
                    }
                }
            }

            $condition_to_pass = array(
                'user_id_fk' => $user_id,
                'user_address_id_fk' => $address_id
            );
            $chk_address_is_exists = $this->common_model->getRecords('mst_user_forwarded_address', '', $condition_to_pass);
            if (COUNT($chk_address_is_exists) > 0) {
                $expire_date = strtotime(date('Y-m-d', strtotime($chk_address_is_exists[0]['date_to'])));
                $current_date = strtotime(date('Y-m-d'));
//                if ($current_date > $expire_date) {
//                    $response_arr = array('msg' => 'Failed', 'Response' => "Sorry. Your forward address has been expired.");
//                } else {
                $update_data = array(
                    'country_id' => $country_id,
                    'state_id' => $state_id,
                    'city_id' => $city_id,
                    'zip_code' => $zipcode,
                    'address_line1' => $address1,
                    'address_line2' => $address2,
                    'address_picture' => $file_name,
                    'latitude' => $lat,
                    'longitude' => $long,
                    'updated_date' => date('Y-m-d H:i:s'),
                    'date_from' => $date_from,
                    'date_to' => $date_to
                );
                $condition = array(
                    'user_id_fk' => $user_id,
                    'user_address_id_fk' => $address_id
                );
                $this->common_model->updateRow('mst_user_forwarded_address', $update_data, $condition);
                $response_arr = array('msg' => 'Success', 'Response' => "Records updated successfully.");
//                }
                echo json_encode($response_arr);
            } else {
                $insert_data = array(
                    'user_id_fk' => $user_id,
                    'user_address_id_fk' => $address_id,
                    'country_id' => $country_id,
                    'state_id' => $state_id,
                    'city_id' => $city_id,
                    'zip_code' => $zipcode,
                    'address_line1' => $address1,
                    'address_line2' => $address2,
                    'address_picture' => $file_name,
                    'latitude' => $lat,
                    'longitude' => $long,
                    'created_date' => date('Y-m-d H:i:s'),
                    'type_flag' => '1',
                    'date_from' => $date_from,
                    'date_to' => $date_to
                );
                $this->common_model->insertRow($insert_data, 'mst_user_forwarded_address');

                $update_data = array(
                    'is_forwarded' => $forwarded_flag
                );
                $condition = array('address_id' => $address_id);
                $this->common_model->updateRow('mst_user_addresses', $update_data, $condition);
                $response_arr = array('msg' => 'Success', 'Response' => "Records inserted successfully.", "building_image" => $image_path);
                echo json_encode($response_arr);
            }
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function viewForwardedAddress() {
        $user_id = $this->input->post('user_id');
        $address_name_id = $this->input->post('address_id');
        if ($user_id != '' && $address_name_id != '') {
            $flag_image_path = base_url() . "media/backend/img/country-flag/thumbs/";
            $condition_to_pass = array('ua.user_id_fk' => $user_id, 'ua.user_address_id_fk' => $address_name_id);
            $arr_user_address = $this->web_services_model->getUserForwardedAddress($condition_to_pass);
            if (COUNT($arr_user_address) > 0) {
                $flag_path = $flag_image_path . $arr_user_address[0]['flag'];
                $response_arr = array('msg' => 'Success', 'user_address' => $arr_user_address[0], 'flag' => $flag_path);
                echo json_encode($response_arr);
            } else {
                $response_arr = array('msg' => 'Failed');
                echo json_encode($response_arr);
            }
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function deleteForwardedAddress() {
        $forwarded_address_id = $this->input->post('forwarded_address_id');
        if ($forwarded_address_id != '') {
            $this->common_model->deleteRows(array($forwarded_address_id), 'mst_user_forwarded_address', 'forwarded_address_id');
            $response_arr = array('msg' => 'Success');
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function giveTempraryAccess() {
        $json_encode_arr = $this->input->post('number_array');
        $login_user_id = $this->input->post('login_user_id');
        $start_time = $this->input->post('start_time');
        $end_time = $this->input->post('end_time');
        $address_id = $this->input->post('address_id');
        $group_name = $this->input->post('group_name');
        $address_type_flag = $this->input->post('address_type_flag');

//         $json_encode_arr = '{"contact_number" : [{"phone_number" : "1234567890","access_to_user_id" : "2"},{"phone_number" : "1234567890","access_to_user_id" : "3"}]}';

        $json_decode_arr = json_decode($json_encode_arr, true);

        if ($json_decode_arr != '' && $login_user_id != '' && $start_time != '' && $end_time != '' && $address_id != '') {

            $condition_to_pass = array('group_name' => $group_name, 'access_from_user_id_fk' => $login_user_id);
            $arr_check_number_exists = $this->common_model->getRecords('mst_user_temprary_access', '', $condition_to_pass);

            if (COUNT($arr_check_number_exists) == 0) {
                foreach ($json_decode_arr['contact_number'] as $number) {

                    $arr_get_user_id = end($this->common_model->getRecords('mst_users', 'user_id', array('phone_number' => $number['phone_number'])));
                    if (count($arr_get_user_id) == 0) {
                        $access_to_user_id = $number['access_to_user_id'];
                    } else {
                        $access_to_user_id = $arr_get_user_id['user_id'];
                    }

                    $insert_data = array(
                        'phone_number' => $number['phone_number'],
                        'access_from_user_id_fk' => $login_user_id,
                        'access_to_user_id_fk' => $access_to_user_id,
                        'start_time' => $start_time,
                        'end_time' => $end_time,
                        'added_time' => date('Y-m-d H:i:s'),
                        'user_address_id_fk' => $address_id,
                        'group_name' => $group_name
                    );
                    $last_inserted_id = $this->common_model->insertRow($insert_data, 'mst_user_temprary_access');
                    $response_arr = array('msg' => 'Success', 'last_inserted_access_id' => $last_inserted_id);

                    $arr_contact_details = $this->web_services_model->getContactNameID($login_user_id, $access_to_user_id);

                    if ($address_type_flag == 'current') {
                        $messge = " has given you temparary access of Current Home Address.";
                    } else if ($address_type_flag == 'permanent') {
                        $messge = " has given you temparary access of Permanent Home Address.";
                    } else {
                        $messge = " has given you temparary access of Office Address.";
                    }

                    if ($arr_contact_details[0]['contact_name'] != '') {
                        $message = $arr_contact_details[0]['contact_name'] . " with Mobile Number " . $arr_contact_details[0]['contact_phone'] . $messge;
                    } else {
                        $arr_login_user_details = $this->common_model->getRecords('mst_users', 'phone_number,first_name,last_name', array('user_id' => $login_user_id));
                        $message = $arr_login_user_details[0]['first_name'] . ' ' . $arr_login_user_details[0]['last_name'] . " with Mobile Number " . $arr_login_user_details[0]['phone_number'] . $messge;
                    }

                    $subject = 'Temporary Access of Address';
                    $table_name = "mst_notifications";
                    $insert_data = array(
                        'notification_from' => $login_user_id,
                        'notification_to' => $access_to_user_id,
                        'subject' => $subject,
                        'message' => $message,
                        'notification_date' => date('Y-m-d H:i:s')
                    );
                    $this->common_model->insertRow($insert_data, $table_name);
                }
//                else {
//                    $expire_date = strtotime(date('Y-m-d', strtotime($arr_check_number_exists[0]['end_time'])));
//                    $current_date = strtotime(date('Y-m-d'));
//                    if ($current_date > $expire_date) {
//                        $response_arr = array('msg' => 'Failed', 'Response' => "Sorry. Your temprary access of address has been expired.");
//                    } else {
//                        $update_data = array(
//                            'start_time' => $start_time,
//                            'end_time' => $end_time
//                        );
//                        $condition = array('phone_number' => $number['phone_number'], 'user_address_id_fk' => $address_id, 'access_to_user_id_fk' => $number['access_to_user_id']);
//                        $this->common_model->updateRow('mst_user_temprary_access', $update_data, $condition);
//                    }
//                    $response_arr = array('msg' => 'Success', 'Response' => 'Records updated successfully.');
//                }
            } else {
                $response_arr = array('msg' => 'Failed', 'Response' => 'Group name already exists.');
            }
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function updateTempraryAccess() {
        $json_encode_arr = $this->input->post('number_array');
        $login_user_id = $this->input->post('login_user_id');
        $start_time = $this->input->post('start_time');
        $end_time = $this->input->post('end_time');
        $address_id = $this->input->post('address_id');
        $old_group_name = $this->input->post('old_group_name');
        $new_group_name = $this->input->post('new_group_name');
        $json_decode_arr = json_decode($json_encode_arr, true);
        if ($json_decode_arr != '' && $login_user_id != '' && $address_id != '' && $old_group_name != '') {
            $condition_to_pass = array('group_name' => $old_group_name);
            $arr_number_exists = $this->common_model->getRecords('mst_user_temprary_access', '', $condition_to_pass);
            if (COUNT($arr_number_exists) > 0) {
                $condition_to_pass1 = array('group_name' => $new_group_name);
                $arr_group_name_exists = $this->common_model->getRecords('mst_user_temprary_access', '', $condition_to_pass1);
                if (COUNT($arr_group_name_exists) == 0) {
                    $this->web_services_model->deleteRows(array('group_name' => $old_group_name, 'access_from_user_id_fk' => $login_user_id), 'mst_user_temprary_access');
                    foreach ($json_decode_arr['contact_number'] as $number) {
                        $insert_data = array(
                            'phone_number' => $number['phone_number'],
                            'access_from_user_id_fk' => $login_user_id,
                            'access_to_user_id_fk' => $number['access_to_user_id'],
                            'start_time' => $start_time,
                            'end_time' => $end_time,
                            'added_time' => date('Y-m-d H:i:s'),
                            'user_address_id_fk' => $address_id,
                            'group_name' => $new_group_name
                        );
                        $last_inserted_id = $this->common_model->insertRow($insert_data, 'mst_user_temprary_access');
                        $response_arr = array('msg' => 'Success', 'last_inserted_access_id' => $last_inserted_id);
                    }
                } else if ($old_group_name == $new_group_name) {
                    $this->web_services_model->deleteRows(array('group_name' => $old_group_name, 'access_from_user_id_fk' => $login_user_id), 'mst_user_temprary_access');
                    foreach ($json_decode_arr['contact_number'] as $number) {
                        $insert_data = array(
                            'phone_number' => $number['phone_number'],
                            'access_from_user_id_fk' => $login_user_id,
                            'access_to_user_id_fk' => $number['access_to_user_id'],
                            'start_time' => $start_time,
                            'end_time' => $end_time,
                            'added_time' => date('Y-m-d H:i:s'),
                            'user_address_id_fk' => $address_id,
                            'group_name' => $new_group_name
                        );
                        $last_inserted_id = $this->common_model->insertRow($insert_data, 'mst_user_temprary_access');
                        $response_arr = array('msg' => 'Success', 'last_inserted_access_id' => $last_inserted_id);
                    }
                } else {
                    $response_arr = array('msg' => 'Failed.Group name already exists.');
                }
            } else {
                $response_arr = array('msg' => 'Failed.');
            }
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function viewTempraryAccess() {
//        $login_user_id = '524';
//        $address_id = '284';
        $login_user_id = $this->input->post('login_user_id');
        $address_id = $this->input->post('address_id');
        if ($login_user_id != '' && $address_id != '') {
            $condition_to_pass = "access_from_user_id_fk = '" . $login_user_id . "' AND user_address_id_fk = '" . $address_id . "' GROUP BY group_name";
            $arr_tempraty_access = $this->common_model->getRecords('mst_user_temprary_access', '', $condition_to_pass);

            $condition = array('user_id_fk' => $login_user_id);
            $arr_get_login_user_contact = $this->common_model->getRecords('mst_user_contacts', 'user_id_fk,contact_phone', $condition);
            $arr_get_temprary_access_list1 = array();
            if (COUNT($arr_tempraty_access) > 0) {
                foreach ($arr_tempraty_access as $key => $tmprary_access) {
                    // $arr_get_temprary_access_list1[$key]=$tmprary_access;
//                    $condition_to_pass = array('group_name' => $tmprary_access['group_name'], 'uc.user_id_fk' => $login_user_id);
                    $condition_to_pass = array('uta.group_name' => $tmprary_access['group_name'], 'uta.access_from_user_id_fk' => $login_user_id);
                    $arr_get_temprary_access_list = $this->web_services_model->getTempraryAccessList($condition_to_pass);
                    if (count($arr_get_temprary_access_list) > 0) {
                        $arr_get_temprary_access_list1[$key] = $arr_get_temprary_access_list;
                    }
                }
            }
//            echo '<pre>';
//            print_r($arr_get_temprary_access_list1);
//            die;
            $response_arr = array('msg' => 'Success', 'response' => $arr_get_temprary_access_list1);
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function deleteTempraryAccess() {
        $group_name = $this->input->post('group_name');
        $login_user_id = $this->input->post('user_id');
        if ($group_name != '' && $login_user_id != '') {
            $this->web_services_model->deleteRows(array('group_name' => $group_name, 'access_from_user_id_fk' => $login_user_id), 'mst_user_temprary_access');
            $response_arr = array('msg' => 'Success');
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function deleteOfficeAddress() {
        $address_id = $this->input->post('address_id');
        $login_user_id = $this->input->post('user_id');
        if ($address_id != '' && $login_user_id != '') {
            $this->web_services_model->deleteRows(array('address_id' => $address_id, 'user_id_fk' => $login_user_id), 'mst_user_addresses');
            $arr_user_details = $this->common_model->getRecords('mst_users', 'user_step', array('user_id' => $login_user_id));
            if (COUNT($arr_user_details) > 0) {
                if ($arr_user_details[0]['user_step'] == '5') {
                    $user_step = $arr_user_details[0]['user_step'] - 1;
                    $update_data = array('user_step' => $user_step);
                    $this->common_model->updateRow('mst_users', $update_data, array('user_id' => $login_user_id));
                }
            }
            $response_arr = array('msg' => 'Success');
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function checkIsEmailVerify() {
        $user_id = $this->input->post('user_id');
        if ($user_id != '') {
            $condition_to_pass = array('user_id' => $user_id);
            $arr_user_details = $this->common_model->getRecords('mst_users', 'user_email,email_verified', $condition_to_pass);
            if (COUNT($arr_user_details) > 0) {
                if ($arr_user_details[0]['email_verified'] == '1') {
                    $response_arr = array('msg' => 'Verified');
                    echo json_encode($response_arr);
                } else {
                    $response_arr = array('msg' => 'Not_Verified');
                    echo json_encode($response_arr);
                }
            } else {
                $response_arr = array('msg' => 'Failed');
                echo json_encode($response_arr);
            }
        }
    }

    public function verifyEmail() {
        $data = $this->common_model->commonFunction();
        $user_id = $this->input->post('user_id');
        if ($user_id != '') {
            $condition_to_pass = array('user_id' => $user_id);
            $arr_user_details = $this->common_model->getRecords('mst_users', 'first_name,last_name,user_email,email_verified,activation_code', $condition_to_pass);
            if (count($arr_user_details) > 0) {
                if ($arr_user_details[0]['email_verified'] == '0') {
                    $response_arr = array('msg' => 'success', 'Response' => 'Verification link sent successfully.');
                    echo json_encode($response_arr);
//                    $condition = array(
//                        'user_id' => $user_id
//                    );
//                    $update_data = array(
//                        'email_verified' => '1'
//                    );
//                    $this->common_model->updateRow('mst_users', $update_data, $condition);
//                    $response_arr = array('msg' => 'success', 'Response' => 'Your email verified successfully.');
//                    echo json_encode($response_arr);
                    //sending email after completing profile to user 
                    $lang_id = $this->session->userdata('lang_id');
                    if (isset($lang_id) && $lang_id != '') {
                        $lang_id = $this->session->userdata('lang_id');
                    } else {
                        $lang_id = 17; // Default is 17(English)
                    }
                    $activation_link = '<a href="' . base_url() . 'user-activation/' . $arr_user_details[0]['activation_code'] . '">Click here</a>';
                    $macros_array_detail = array();
                    $macros_array_detail = $this->common_model->getRecords('mst_email_template_macros', 'macros,value', $condition_to_pass = '', $order_by = '', $limit = '', $debug = 0);
                    $macros_array = array();
                    foreach ($macros_array_detail as $row) {
                        $macros_array[$row['macros']] = $row['value'];
                    }
                    $reserved_words = array();

                    $reserved_arr = array
                        (
                        "||USER_NAME||" => $arr_user_details[0]['first_name'] . ' ' . $arr_user_details[0]['last_name'],
                        "||ADMIN_ACTIVATION_LINK||" => $activation_link,
                        "||SITE_URL||" => base_url(),
                        "||SITE_TITLE||" => $data['global']['site_title']
                    );

                    $reserved_words = array_replace_recursive($macros_array, $reserved_arr);
                    $template_title = 'email-verify';
                    $arr_emailtemplate_data = $this->common_model->getEmailTemplateInfo($template_title, $lang_id, $reserved_words);
                    $recipeinets = $arr_user_details[0]['user_email'];
                    $from = array("email" => $data['global']['contact_email'], "name" => $data['global']['site_title']);
                    $subject = $arr_emailtemplate_data['subject'];
                    $message = $arr_emailtemplate_data['content'];
                    $this->common_model->sendEmail($recipeinets, $from, $subject, $message);
                }
            } else {
                $response_arr = array('msg' => 'Failed');
                echo json_encode($response_arr);
            }
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function blockContact() {
        $is_block = $this->input->post('is_block');
        $contact_user_id = $this->input->post('contact_user_id');
        if ($is_block != '' && $contact_user_id != '') {
            $condition_to_pass = array('user_contact_id' => $contact_user_id);
            $arr_contact_details = $this->common_model->getRecords('mst_user_contacts', '', $condition_to_pass);
            if (count($arr_contact_details) > 0) {
                $condition = array(
                    'user_contact_id' => $contact_user_id
                );
                $update_data = array('is_blocked' => $is_block);
                $this->common_model->updateRow('mst_user_contacts', $update_data, $condition);
                $response_arr = array('msg' => 'success');
                echo json_encode($response_arr);
            } else {
                $response_arr = array('msg' => 'Failed');
                echo json_encode($response_arr);
            }
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function faqsPageWebservice($pg = 0) {
        $this->load->model('faq_model');
        $pg = $this->input->post('page_no');
        $data = $this->common_model->commonFunction();
        $condition = array('f.status' => 'Active');
        $faq_question_details = $this->faq_model->getFAQS('question,faq_id,answer', $condition, 'faq_id Desc');
        $this->load->library('pagination');
        $data["faq_question_details"] = $faq_question_details;
        echo $this->load->view('front/faq/ws-faq', $data);
    }

    public function faqDetails($faq_id, $search_tag) {
        $this->load->model('faq_model');
        $data = $this->common_model->commonFunction();
        $condition = array('f.faq_id' => $faq_id);
        $faq_question_details = $this->faq_model->getFAQS('question,faq_id,answer', $condition, 'faq_id Desc');
        $data["faq_question_details"] = $faq_question_details;
        $data["search_tag"] = $search_tag;
        echo $this->load->view('front/faq/ws-faq-details', $data);
    }

    public function faqSeachTags($pg = 0) {
        $data = $this->common_model->commonFunction();
        $this->load->model('faq_model');
        $search_tags = $this->input->post('search_tags');
//        $search_tags = 'block test';
        $arr_search = explode(' ', $search_tags);
        foreach ($arr_search as $s) {
            $where = "FIND_IN_SET('" . $s . "',search_tags)";
            $faq_question_details = $this->faq_model->getFAQSearchTags('question,faq_id,search_tags', $where);
            if (count($faq_question_details) > 0) {
                foreach ($faq_question_details as $question) {
                    if (!in_array($question["faq_id"], $arr_faq)) {
                        $arr_array[] = $question;
                        $arr_faq[] = $question['faq_id'];
                    }
                }
            }
        }
        $faq_question_details = $arr_array;
        if (count($faq_question_details) > 0 && !empty($faq_question_details)) {
            foreach ($faq_question_details as $key => $faq) {
                $arr_return[$key]['question'] = $faq['question'];
                $arr_return[$key]['faq_id'] = $faq['faq_id'];
                $arr_return[$key]['faq_url'] = base_url() . 'ws-faqs-details/' . $faq['faq_id'] . '/search_tag';
            }
            $response_arr = array('msg' => 'success', 'faq_question_details' => $arr_return);
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Records Not Found');
            echo json_encode($response_arr);
        }
    }

//    public function faqsPageWebservice($pg = 0) {
//        $this->load->model('faq_model');
//        $pg = $this->input->post('page_no');
//
//        $data = $this->common_model->commonFunction();
//        $condition = array('f.status' => 'Active');
//        $faq_question_details = $this->faq_model->getFAQS('question,answer', $condition, 'faq_id Desc');
//
//        $this->load->library('pagination');
//        $arr_faq_details['count'] = count($faq_question_details);
//        $config['base_url'] = base_url() . 'faqs/';
//        $config['total_rows'] = count($faq_question_details);
//        $config['total_rows'];
//        $config['per_page'] = 10;
//        $config['cur_page'] = $pg;
//        $arr_faq_details['cur_page'] = $pg;
//        $config['num_links'] = 2;
//        $config['full_tag_open'] = ' <p class="paginationPara">';
//        $config['full_tag_close'] = '</p>';
//        $this->pagination->initialize($config);
//        $data['create_links'] = $this->pagination->create_links();
//        $arr_faq_details = $this->faq_model->getFAQS('question,answer', $condition, 'faq_id Desc', $config['per_page'], $pg);
//        $arr_faq_details['page'] = $pg;
//
//        if (count($arr_faq_details) > 0) {
//            $arr_cms_details = array(
//                'faq_id' => $arr_faq_details[0]['faq_id'],
//                'question' => $arr_faq_details[0]['question'],
//                'answer' => $arr_faq_details[0]['answer']
//            );
//            $response_arr = array('msg' => 'Success', 'Response' => $arr_cms_details);
//            echo json_encode($response_arr);
//        } else {
//            $response_arr = array('msg' => 'Failed');
//            echo json_encode($response_arr);
//        }
//    }

    public function contactUs() {
        $data = $this->common_model->commonFunction();
        $data['global'] = $this->common_model->getGlobalSettings();
        $data['city'] = $data['global']['city'];
        $data['street'] = $data['global']['street'];
        $data['phone_no'] = $data['global']['phone_no'];
        $data['contact_email'] = $data['global']['contact_email'];
        $data['zip_code'] = $data['global']['zip_code'];
        $data['address'] = $data['global']['address'];
        $data['message'] = isset($data['global']['contact_us_message']) ? $data['global']['contact_us_message'] : '';

        if ($this->input->post('first_name')) {
            /* insert contact details */
            $arr_fields = array(
                "name" => addslashes($this->input->post('first_name')),
                "subject" => addslashes($this->input->post('subject')),
                "mail_id" => addslashes($this->input->post('email')),
                "message" => addslashes($this->input->post('message')),
                "reply_status" => '0',
                "date" => date('Y-m-d H:i:s')
            );
            $last_insert_id = $this->contact_us_model->insertContactUs($arr_fields);
            if ($last_insert_id > 0) {
                $recipient = $data['global']['site_email'];
                $from = array("email" => $this->input->post('email'), "name" => stripslashes($data['global']['site_title']));
                $subject = $this->input->post('subject');
                $message = $this->input->post('message');
                $mail = $this->common_model->sendEmail($recipient, $from, $subject, $message);
                if ($mail) {
                    $response_arr = array('msg' => 'Success', 'Response' => "Your message has been posted successfully. We will get back to you soon.");
                } else {
                    $response_arr = array('msg' => 'Success', 'Response' => "Your message is failed, please try again.");
                }
            } else {
                $response_arr = array('msg' => 'Failed', 'Response' => "Your message is failed, please try again.");
            }
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed', 'Response' => $data);
            echo json_encode($response_arr);
        }
    }

    /*
     * Send notification to those contact in which regisrting user's mobile number had present
     */

    public function getReciepentUser($user_id) {
//        $user_id = $this->input->post('user_id');
        $condition_to_pass_receipent = array('user_id' => $user_id);
        $arr_check_receipent = $this->common_model->getRecords('mst_users', 'first_name,last_name,phone_number', $condition_to_pass_receipent);
        if (count($arr_check_receipent)) {
            $condition_to_pass_receipent_data = array('contact_phone' => $arr_check_receipent[0]['phone_number']);
            $arr_receipent_users = $this->common_model->getRecords('mst_user_contacts', '', $condition_to_pass_receipent_data);
            if (count($arr_receipent_users)) {
                $subject3 = "New Registration";
                foreach ($arr_receipent_users as $recipeinets_data) {
                    $message3 = $recipeinets_data['contact_name'] . " with mobile number " . $recipeinets_data['contact_phone'] . " has been register using PCO APP.";
                    $insert_data3 = array(
                        'notification_from' => $user_id,
                        'notification_to' => $recipeinets_data['user_id_fk'],
                        'subject' => $subject3,
                        'message' => $message3,
                        'notification_date' => date('Y-m-d H:i:s')
                    );
                    $this->common_model->insertRow($insert_data3, "mst_notifications");
                }
            }
        }
    }

    public function getNotificationCount() {
        $login_user_id = $this->input->post('login_user_id');
        if ($login_user_id != '') {
            $condition_to_pass = array("n.notification_to" => $login_user_id, 'read_status' => '0');
            $arr_get_notification = $this->web_services_model->getUserNotification($condition_to_pass);
            $response_arr = array('msg' => 'Success', 'notification_count' => COUNT($arr_get_notification));
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function readNotification() {
        $notification_id = $this->input->post('notification_id');
        $notification_status = $this->input->post('notification_status');
        if ($notification_id != '' && $notification_status != '') {
            $condition = array(
                'notification_id' => $notification_id
            );
            $update_data = array('read_status' => $notification_status);
            $this->common_model->updateRow('mst_notifications', $update_data, $condition);
            $response_arr = array('msg' => 'Success');
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function getContactBlockUser() {
        $user_id = $this->input->post('user_id');

        $arr_contact_user = $this->web_services_model->getContactBlockUser($user_id);
        $arr_block_user = array();
        foreach ($arr_contact_user as $key => $contact_user) {
            $first_name = '';
            if (isset($contact_user['first_name']) && $contact_user['first_name'] != '') {
                $first_name = $contact_user['first_name'];
            }
            $last_name = '';
            if (isset($contact_user['last_name']) && $contact_user['last_name'] != '') {
                $last_name = $contact_user['last_name'];
            }
            $user_id = '';
            if (isset($contact_user['user_id']) && $contact_user['user_id'] != '') {
                $user_id = $contact_user['user_id'];
            }
            $user_contact_id = '';
            if (isset($contact_user['user_contact_id']) && $contact_user['user_contact_id'] != '') {
                $user_contact_id = $contact_user['user_contact_id'];
            }
            $contact_name = '';
            if (isset($contact_user['contact_name']) && $contact_user['contact_name'] != '') {
                $contact_name = $contact_user['contact_name'];
            }
            $contact_phone = '';
            if (isset($contact_user['contact_phone']) && $contact_user['contact_phone'] != '') {
                $contact_phone = $contact_user['contact_phone'];
            }
            $contact_email = '';
            if (isset($contact_user['contact_email']) && $contact_user['contact_email'] != '') {
                $contact_email = $contact_user['contact_email'];
            }
            $added_date = '';
            if (isset($contact_user['added_date']) && $contact_user['added_date'] != '') {
                $added_date = $contact_user['added_date'];
            }
            $arr_block_user[$key] = array("first_name" => $first_name,
                "last_name" => $last_name,
                "user_id" => $user_id,
                "user_contact_id" => $user_contact_id,
                "contact_name" => $contact_name,
                "contact_phone" => $contact_phone,
                "contact_email" => $contact_email,
                "added_date" => $added_date
            );
        }



        if (is_array($arr_contact_user) && is_array($arr_block_user) && count($arr_block_user) > 0) {
            $response_arr = array('msg' => 'Success', 'arr_block_user' => $arr_block_user);
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    function countriesListWebServiceISO() {
        $country_iso = $this->input->post('country_iso_code');
        if ($country_iso != '') {
            $get_countries_list = $this->countries_model->getCountriesDetailsISO($country_iso);
            $flag_image_path = base_url() . "media/backend/img/country-flag/thumbs/";
            if (COUNT($get_countries_list) > 0) {
                for ($count = 0; $count < COUNT($get_countries_list); $count++) {
                    $countries_data = $get_countries_list[$count];
                    $arr_return[$count]['country_id'] = $countries_data["country_id"];
                    $arr_return[$count]['country_name'] = $countries_data["country_name"];
                    $arr_return[$count]['country_phone_code'] = $countries_data["country_phone_code"];
                    $arr_return[$count]['mobile_number_length'] = $countries_data["mobile_number_length"];
                    $arr_return[$count]['trunk_code'] = $countries_data["trunk_code"];
                    $arr_return[$count]['iso'] = $countries_data["iso"];
                    if ($countries_data["flag"] != '') {
                        $arr_return[$count]['flag'] = $flag_image_path . $countries_data["flag"];
                    } else {
                        $arr_return[$count]['flag'] = '';
                    }
                    $response_arr = array('msg' => 'Success', 'Response' => end($arr_return));
                }
                echo json_encode($response_arr);
            } else {
                $response_arr = array('msg' => 'Failed');
                echo json_encode($response_arr);
            }
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    function getRegistredContactDetails() {
        $user_id = $this->input->post('user_id');
        if ($user_id != '') {
            $condition_to_pass = array('uc.user_id_fk' => $user_id);
            $arr_user_details = $this->web_services_model->getContactUserInfo($condition_to_pass);
            $response_arr = array('msg' => 'Success', 'Response' => $arr_user_details);
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    function getAddressDetails() {
        $user_id = $this->input->post('user_id');
        if ($user_id != '') {
            $condition_to_pass = array('ua.user_id_fk' => $user_id);
            $arr_user_add_details = $this->web_services_model->getUserAddressDetails($condition_to_pass);
            if (count($arr_user_add_details) > 0) {
                foreach ($arr_user_add_details as $key => $address_details) {
                    $arr_address_details[$key] = $address_details;
                    $condition_to_pass = array('user_address_id_fk' => $address_details['address_id']);
                    $arr_forwarded_address = $this->web_services_model->getUserForwardedAddress($condition_to_pass);
                    $arr_address_details[$key]['is_forwarded_address'] = $arr_forwarded_address[0];
                }
            }
            $response_arr = array('msg' => 'Success', 'Response' => $arr_address_details);
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    function iAmHere() {
        $login_user_id = $this->input->post('login_user_id');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $arr_user_ids = $this->input->post('user_ids_array');

        if ($login_user_id != '' && $latitude != '' && $longitude != '' && $arr_user_ids != '') {
            $json_decode_arr = json_decode($arr_user_ids, true);
            if (count($json_decode_arr) > 0) {
                foreach ($json_decode_arr['user_ids'] as $user_ids) {
                    $condition_to_pass = array('user_id_fk' => $login_user_id, 'contact_user_id_fk' => $user_ids['user_id']);
                    $arr_login_user_details = end($this->web_services_model->getLoginUserContactInfo($condition_to_pass));
                    $user_name = ($arr_login_user_details['contact_name'] != '') ? $arr_login_user_details['contact_name'] : $arr_login_user_details['first_name'] . ' ' . $arr_login_user_details['last_name'];
                    $subject = "Location Share";

                    $message = $user_name . " has shared his current location.";
                    $notification = array('message' => $message, 'latitude' => $latitude, 'longitude' => $longitude);
                    $is_registration_id_exists = $this->common_model->getRecords('mst_mobile_number_and_otp_details', 'registration_id', array('mobile_number' => $user_ids['mobile_number']));

//                    if (count($is_registration_id_exists) > 0) {
//                        $this->send_notification($is_registration_id_exists[0]['registration_id'], $notification);
//                    }

                    $insert_data3 = array(
                        'notification_from' => $login_user_id,
                        'notification_to' => $user_ids['user_id'],
                        'subject' => $subject,
                        'message' => $message,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'notification_date' => date('Y-m-d H:i:s')
                    );
                    $this->common_model->insertRow($insert_data3, "mst_notifications");

                    $condition_to_pass2 = array('user_id_from' => $login_user_id, 'user_id_to' => $user_ids['user_id'],);
                    $is_location_share = $this->common_model->getRecords('trans_shared_location', '', $condition_to_pass2);
                    if (count($is_location_share) == 0) {
                        $arr_to_insert = array(
                            'user_id_from' => $login_user_id,
                            'user_id_to' => $user_ids['user_id'],
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                        );
                        $this->common_model->insertRow($arr_to_insert, "trans_shared_location");
                    } else {
                        $arr_to_update = array(
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                        );
                        $condition_to_pass3 = array('user_id_from' => $login_user_id, 'user_id_to' => $user_ids['user_id'],);
                        $this->common_model->updateRow('trans_shared_location', $arr_to_update, $condition_to_pass3);
                    }

                    $arr_to_return[] = array(
                        'user_id_from' => $login_user_id,
                        'user_id_to' => $user_ids['user_id'],
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    );
                }
                $response_arr = array('msg' => 'Success', 'Respose' => $arr_to_return);
                echo json_encode($response_arr);
            }
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function send_notification($registration_id, $message) {
        $url = 'https://android.googleapis.com/gcm/send';

        $fields = array(
            'registration_ids' => array($registration_id),
            'data' => array("message" => $message)
        );
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);
        print_r($result);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        return $result;
    }

    public function getLiveOrWorkedNearByUsers() {
        $user_id = $this->input->post('user_id');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        if ($user_id != '' && $latitude != '' && $longitude != '') {
            $condition_to_pass = array('uc.user_id_fk' => $user_id, 'contact_user_id_fk !=' => '0');
            $arr_get_user_details = $this->web_services_model->getNearByUsers($condition_to_pass, $latitude, $longitude);
            if (count($arr_get_user_details) > 0) {
                foreach ($arr_get_user_details as $key => $user_details) {
                    $arr_add_details[$key] = $user_details;
                    $condition_to_pass = array('ua.user_id_fk' => $user_details['user_id']);
                    $arr_user_add_details = $this->web_services_model->getClosestUserAddressDetails($condition_to_pass, $latitude, $longitude);
                    $arr_add_details[$key]['all_addresses'] = $arr_user_add_details;
                    if (count($arr_user_add_details) > 0) {
                        foreach ($arr_user_add_details as $key2 => $add_details) {
                            $condition_to_pass1 = array('user_address_id_fk' => $add_details['address_id'], 'user_id_fk' => $user_details['user_id'], 'access_to_fk' => $user_id);
                            $check_is_add_access = $this->common_model->getRecords('mst_user_contacts_access', '', $condition_to_pass1);
                            if (count($check_is_add_access) > 0) {
                                $arr_add_details[$key]['all_addresses'][$key2]['is_access'] = "Yes";
                            } else {
                                $arr_add_details[$key]['all_addresses'][$key2]['is_access'] = "No";
                            }
                        }
                    }
                }
            }
            $response_arr = array('msg' => 'Success', 'Respose' => $arr_add_details);
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function updateCurrentLocation() {
        $user_id = $this->input->post('user_id');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        if ($user_id != '' && $latitude != '' && $longitude != '') {
            $condition = array('user_id' => $user_id);
            $update_data = array(
                'latitude' => $latitude,
                'longitude' => $longitude
            );
            $this->common_model->updateRow("mst_users", $update_data, $condition);
            $response_arr = array('msg' => 'Success');
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function getCurrentNearByUsers() {
        $user_id = $this->input->post('user_id');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
//        $distance = $this->input->post('distance');
        $distance = 50;
        if ($user_id != '' && $latitude != '' && $longitude != '' && $distance != '') {
            $condition_to_pass = array('uc.user_id_fk' => $user_id, 'contact_user_id_fk !=' => '0');
            $arr_get_user_details = $this->web_services_model->getCurrentNearByUsersModel($condition_to_pass, $latitude, $longitude, $distance);
            $arr_add_details = array();
            if (count($arr_get_user_details) > 0) {
                foreach ($arr_get_user_details as $key => $user_details) {
                    $arr_add_details[$key] = $user_details;
//                    $condition_to_pass = array('ua.user_id_fk' => $user_details['user_id']);
//                    $arr_user_add_details = $this->web_services_model->getUserAddressDetails($condition_to_pass);
//                    $arr_add_details[$key]['all_addresses'] = $arr_user_add_details;
                    $condition_to_pass1 = array("user_id_fk" => $user_id, "access_to_user_id_fk" => $user_details['user_id']);
                    $chk_near_location_access = $this->common_model->getRecords('trans_near_by_location_access', '', $condition_to_pass1);
                    if (count($chk_near_location_access) > 0) {
                        $condition_to_pass = array('ua.user_id_fk' => $user_details['user_id']);
                        $arr_user_add_details = $this->web_services_model->getClosestUserAddressDetails($condition_to_pass, $latitude, $longitude);
                        $arr_add_details[$key]['all_addresses'] = $arr_user_add_details;
                        if (count($arr_user_add_details) > 0) {
                            foreach ($arr_user_add_details as $key2 => $add_details) {
                                $condition_to_pass1 = array('user_address_id_fk' => $add_details['address_id'], 'user_id_fk' => $user_details['user_id'], 'access_to_fk' => $user_id);
                                $check_is_add_access = $this->common_model->getRecords('mst_user_contacts_access', '', $condition_to_pass1);
                                if (count($check_is_add_access) > 0) {
                                    $arr_add_details[$key]['all_addresses'][$key2]['is_access'] = "Yes";
                                } else {
                                    $arr_add_details[$key]['all_addresses'][$key2]['is_access'] = "No";
                                }
                            }
                        }
                    }
                }
            }
            $response_arr = array('msg' => 'Success', 'Respose' => $arr_add_details);
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function giveCurrentNearByLocationAccess() {
        $user_id = $this->input->post('user_id');
        $json_encode_arr = $this->input->post('user_id_array');
//        $json_encode_arr = '{"users_ids_arr" : [{"access_to_user_id" : "2"},{"access_to_user_id" : "5"}]}';
        $json_decode_arr = json_decode($json_encode_arr, true);
        if ($json_decode_arr != '' && $user_id != '') {
            $arr_delete_condition = array('user_id_fk' => $user_id);
            $this->web_services_model->deleteRows($arr_delete_condition, 'trans_near_by_location_access');
            foreach ($json_decode_arr['users_ids_arr'] as $user_ids) {
                $insert_data = array(
                    'user_id_fk' => $user_id,
                    'access_to_user_id_fk' => $user_ids['access_to_user_id']
                );
                $this->common_model->insertRow($insert_data, 'trans_near_by_location_access');
            }
            $response_arr = array('msg' => 'Success');
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function viewCurrentLocationAccessList() {
        $user_id = $this->input->post('login_user_id');
        if ($user_id != '') {
            $condition_to_pass = array('nla.user_id_fk' => $user_id);
            $arr_get_access_details = $this->web_services_model->getCurrentLocationAddAccessList($condition_to_pass);
            if (COUNT($arr_get_access_details) > 0) {
                $response_arr = array('msg' => 'Success', 'access_list' => $arr_get_access_details);
            } else {
                $response_arr = array('msg' => 'Reocrds not found');
            }
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    public function deleteContactFromAccessList() {
        $user_id = $this->input->post('user_id');
        $json_encode_arr = $this->input->post('user_id_array');
        $json_decode_arr = json_decode($json_encode_arr, true);
        if ($json_decode_arr != '' && $user_id != '') {
            foreach ($json_decode_arr['users_ids_arr'] as $user_ids) {
                $arr_delete_condition = array('user_id_fk' => $user_id, 'access_to_user_id_fk' => $user_ids['access_to_user_id']);
                $this->web_services_model->deleteRows($arr_delete_condition, 'trans_near_by_location_access');
            }
            $response_arr = array('msg' => 'Success');
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
        }
    }

    public function emergncyHelp() {
        $user_id = $this->input->post('login_user_id');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        if ($user_id != '') {
            $arr_near_by_users = array();
            $condition_to_pass = array('uc.user_id_fk' => $user_id, 'contact_user_id_fk !=' => '0');
            $arr_near_by_users = $this->web_services_model->getCurrentNearByUsersModel($condition_to_pass, $latitude, $longitude, 50);
            if (count($arr_near_by_users) > 0) {
                foreach ($arr_near_by_users as $recipents_data) {
                    $message = $recipents_data['contact_name'] . " with mobile number " . $recipents_data['phone_number'] . " require emergency help.";
                    $insert_data3 = array(
                        'notification_from' => $user_id,
                        'notification_to' => $recipents_data['user_id'],
                        'subject' => "Emergency Help",
                        'message' => $message,
                        'notification_date' => date('Y-m-d H:i:s')
                    );
                    if ($user_id != $recipents_data['user_id']) {
                        $this->common_model->insertRow($insert_data3, "mst_notifications");
                    }
                }
                $response_arr = array('msg' => 'Success');
                echo json_encode($response_arr);
            } else {
                $response_arr = array('msg' => 'Failed');
                echo json_encode($response_arr);
            }
        }
    }

}
