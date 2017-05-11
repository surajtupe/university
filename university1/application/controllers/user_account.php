<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_Account extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("common_model");
        $this->load->model("user_model");
        $this->load->model("address_model");
    }

    /*
     * Get user's profile information
     */

    public function profile() {
        if (!$this->common_model->isLoggedIn()) {
            redirect('sign-in');
        }
        $data = $this->common_model->commonFunction();
        $data['user_session'] = $this->session->userdata('user_account');
        $user_id = $data['user_session']['user_id'];
        $condition_to_pass = array('ua.user_id_fk' => $user_id);
        $data['arr_get_address_details'] = $this->address_model->getAllAddress($condition_to_pass);

        $arr_user_data = array();
        $table_to_pass = 'mst_users';
        $fields_to_pass = 'user_id,first_name,last_name,middle_name,title,user_email,user_name,profile_picture,gender,email_verified,phone_number,user_birth_date';
        $condition_to_pass = array("user_id" => $data['user_session']['user_id']);
        $arr_user_data = $this->user_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        $data['arr_user_data'] = $arr_user_data[0];
        $this->session->unset_userdata('security_code');
        $data['site_title'] = "Profile";
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/includes/inner-top-nav');
        $this->load->view('front/user-account/user-profile', $data);
        $this->load->view('front/includes/footer');
    }

    /*
     * Edit user profile information 
     */

    function editProfile($security_code) {
        if (!$this->common_model->isLoggedIn()) {
            redirect('sign-in');
        }
        $security_code = base64_decode($security_code);
        $sess_security_code = $this->session->userdata('security_code');
        if (isset($sess_security_code) && $sess_security_code != $security_code) {
            $this->session->set_userdata('msg_failed', 'Sorry you are not authorise person to edit this details.');
            redirect(base_url() . 'profile');
        }

        $data = $this->common_model->commonFunction();
        $data['user_session'] = $this->session->userdata('user_account');
        $arr_user_data = array();
        $table_to_pass = 'mst_users';
        $fields_to_pass = 'user_id,first_name,last_name,middle_name,title,user_email,user_name,profile_picture,gender,email_verified,activation_code,phone_number,user_birth_date';
        $condition_to_pass = array("user_id" => $data['user_session']['user_id']);
        $arr_user_data = $this->user_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        $data['arr_user_data'] = $arr_user_data[0];

        if ($this->input->post('title') != '' && $this->input->post('first_name') != '' && $this->input->post('last_name') != '' && $this->input->post('user_email') != '' && $this->input->post('day') != '' && $this->input->post('month') != '' && $this->input->post('year') != '') {
            $date_of_birth = $this->input->post('day') . '/' . $this->input->post('month') . '/' . $this->input->post('year');

            if ($data['arr_user_data']['email_verified'] == '1') {
                if ($this->input->post('user_email') != $this->input->post('user_email_old')) {
                    $verified_status = '0';
                    $activation_code = time();
                } else {
                    $verified_status = '1';
                    $activation_code = $data['arr_user_data']['activation_code'];
                }
            } else {
                $verified_status = '0';
                $activation_code = $data['arr_user_data']['activation_code'];
            }

            if ($this->input->post('change_password') == 'on') {
                // generating the password by using hashing technique and applyng salt on it
                //crypt has method is used. 2y is crypt algorith selector
                //12 is workload factor on core processor.
                $salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
                $hash_password = crypt($this->input->post('new_user_password'), '$2y$12$' . $salt);
                /* if password need to change */
                $update_data = array(
                    'title' => $this->input->post('title'),
                    'first_name' => $this->input->post('first_name'),
                    'middle_name' => $this->input->post('middle_name'),
                    'last_name' => $this->input->post('last_name'),
                    'user_email' => $this->input->post('user_email'),
                    'user_birth_date' => $date_of_birth,
                    'email_verified' => $verified_status,
                    'activation_code' => $activation_code,
                    'user_password' => $hash_password
                );
            } else {
                /* if passwording need not need to change */
                $update_data = array(
                    'title' => $this->input->post('title'),
                    'first_name' => $this->input->post('first_name'),
                    'middle_name' => $this->input->post('middle_name'),
                    'last_name' => $this->input->post('last_name'),
                    'user_email' => $this->input->post('user_email'),
                    'user_birth_date' => $date_of_birth,
                    'email_verified' => $verified_status,
                    'activation_code' => $activation_code
                );
            }

            $condition = array('user_id' => $data['user_session']['user_id']);
            $this->common_model->updateRow('mst_users', $update_data, $condition);
            if ($this->input->post('user_email') == $this->input->post('user_email_old')) {
                $this->session->set_userdata('msg_success', "Your profile has been updated successfully.");
                redirect(base_url() . 'profile');
            } else {
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
                if ($mail) {
                    $this->session->set_userdata('msg_success', "Please check your email and verified email from <strong>" . $this->input->post('user_email') . "</strong>");
                    redirect(base_url() . 'profile');
                } else {
                    $this->session->set_userdata('msg_success', "Your profile has been updated successfully.");
                    redirect(base_url() . 'profile');
                }
            }
        }
        $data['site_title'] = "Edit Profile";
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/includes/inner-top-nav');
        $this->load->view('front/user-account/edit-user-profile', $data);
        $this->load->view('front/includes/footer');
    }

    public function validatePassword() {
        $data = $this->common_model->commonFunction();
        $data['user_session'] = $this->session->userdata('user_account');
        $user_id = $data['user_session']['user_id'];
        $password = $this->input->post('password');
        if ($user_id != '' && $password != '') {
            $condition_to_pass = array('user_id' => $user_id);
            $arr_user_password = $this->common_model->getRecords('mst_users', 'user_password', $condition_to_pass);
            if (COUNT($arr_user_password) > 0) {
                $user_password = crypt($password, $arr_user_password[0] ['user_password']);
                $condition_to_pass = array('user_password' => $user_password);
                $arr_user_details = $this->common_model->getRecords('mst_users', '', $condition_to_pass);
                if (COUNT($arr_user_details) > 0) {
                    $code = time();
                    $this->session->set_userdata('security_code', $code);
                    $response_arr = array('msg' => 'success', 'security_code' => base64_encode($code));
                } else {
                    $response_arr = array('msg' => 'failed', 'response' => 'Please enter valid password');
                }
                echo json_encode($response_arr);
            } else {
                $response_arr = array('msg' => 'failed', 'response' => 'Please enter valid password');
                echo json_encode($response_arr);
            }
        } else {
            $response_arr = array('msg' => 'failed', 'response' => 'Please enter valid password');
            echo json_encode($response_arr);
        }
    }

    /*
     * Check user email for dupliation 
     */

    public function chkEditEmailDuplicate() {

        if ($this->input->post('user_email') == $this->input->post('user_email_old')) {
            echo 'true';
        } else {
            $table_to_pass = 'mst_users';
            $fields_to_pass = array('user_id', 'user_email');
            $condition_to_pass = array("user_email" => $this->input->post('user_email'));
            $arr_login_data = $this->user_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
            if (count($arr_login_data)) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    }

    /*
     * Check username for dupliation 
     */

    public function chkEditUsernameDuplicate() {

        if ($this->input->post('user_name') == $this->input->post('user_name_old')) {
            echo 'true';
        } else {
            $table_to_pass = 'mst_users';
            $fields_to_pass = array('user_id', 'user_name');
            $condition_to_pass = array("user_email" => $this->input->post('user_name'));
            $arr_login_data = $this->user_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
            if (count($arr_login_data)) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    }

    /*
     * Change user password
     */

//    public function changePassword() {
//
//        if (!$this->common_model->isLoggedIn()) {
//            redirect('sign-in');
//        }
//        $data = $this->common_model->commonFunction();
//        $data['user_session'] = $this->session->userdata('user_account');
//        if ($this->input->post('new_user_password') != '') {
//            // generating the password by using hashing technique and applyng salt on it
//            //crypt has method is used. 2y is crypt algorith selector
//            //12 is workload factor on core processor.
//            $salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
//            $hash_password = crypt($this->input->post('new_user_password'), '$2y$12$' . $salt);
//
//            $table_name = 'mst_users';
//            $update_data = array('user_password' => $hash_password, "reset_password_code" => '0');
//            $condition = array("user_id" => $data['user_session']['user_id']);
//            $this->common_model->updateRow($table_name, $update_data, $condition);
//            $this->session->set_userdata('edit_profile_success', "Your password has been updated successfully.");
//            redirect(base_url() . 'profile');
//            exit;
//        }
//
//        $table_to_pass = 'mst_users';
//        $fields_to_pass = 'user_id,first_name,last_name,user_email,user_name,user_type,user_status,profile_picture,gender';
//        $condition_to_pass = array("user_id" => $data['user_session']['user_id']);
//        $arr_user_data = array();
//        $arr_user_data = $this->user_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
//        $data['arr_user_data'] = $arr_user_data[0];
//
//        $data['site_title'] = "Change Password";
//        $this->load->view('front/includes/header', $data);
//        $this->load->view('front/includes/inner-top-nav');
//        $this->load->view('front/user-account/change-password', $data);
//        $this->load->view('front/includes/footer');
//    }

    /*
     * Change user password
     */

    public function changePassword() {
        if (!$this->common_model->isLoggedIn()) {
            redirect('signin');
        }
        $data = $this->common_model->commonFunction();
        $data['global'] = $this->common_model->getGlobalSettings();
        $data['user_session'] = $this->session->userdata('user_account');
        if ($this->input->post('new_user_password') != '') {
            // generating the password by using hashing technique and applyng salt on it
            //crypt has method is used. 2y is crypt algorith selector
            //12 is workload factor on core processor.
            $salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
            $hash_password = crypt($this->input->post('new_user_password'), '$2y$12$' . $salt);

            $table_name = 'mst_users';
            $update_data = array('user_password' => $hash_password, "reset_password_code" => '0');
            $condition = array("user_id" => $data['user_session']['user_id']);
            $this->common_model->updateRow($table_name, $update_data, $condition);
            $this->session->set_userdata('msg_success', "Your password has been updated successfully.");
            redirect(base_url() . 'profile');
            exit;
        }

        $table_to_pass = 'mst_users';
        $fields_to_pass = 'user_id,first_name,last_name,user_email,user_type,user_status,profile_picture,gender';
        $condition_to_pass = array("user_id" => $data['user_session']['user_id']);
        $arr_user_data = array();
        $arr_user_data = $this->user_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        $data['arr_user_data'] = $arr_user_data[0];

        $value = $this->generateNewPasswordCode($data['arr_user_data']['first_name']);
        $data['site_title'] = "Change Password";
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/includes/inner-top-nav');
        $this->load->view('front/user-account/change-password', $data);
        $this->load->view('front/includes/footer');
    }

    /*     * *
     * It will generate a random code and save in his record  , sending it over email to user.
     */

    public function generateNewPasswordCode($first_name) {
        if (!$this->common_model->isLoggedIn()) {
            redirect('signin');
        }
        $code = rand(9991, 999899);
        $data = $this->common_model->commonFunction();
        $data['user_session'] = $this->session->userdata('user_account');

        if ($data['user_session']['user_id'] != '') {
            $table_name = 'mst_users';
            $update_data = array('password_change_security_code' => $code);
            $condition = array("user_id" => $data['user_session']['user_id']);
            $this->common_model->updateRow($table_name, $update_data, $condition);
            //sending this code to user 
            $reserved_words = array
                ("||SITE_TITLE||" => $data['global']['site_title'],
                "||SITE_PATH||" => base_url(),
                "||CODE||" => $code,
                "||FIRST_NAME||" => $first_name
            );

            /*
             * getting mail subect and mail message using email template title  and reserved works 
             */
            $email_content = $this->common_model->getEmailTemplateInfo('send-password-reset-code', 17, $reserved_words);
            $mail = $this->common_model->sendEmail(array($data['user_session']['user_email']), array("email" => $data['global']['site_email'], "name" => $data['global']['site_title']), $email_content['subject'], $email_content['content']);

            return json_encode(array("success" => '1', "success_msg" => 'Security code has been sent on your registered email!!'));
            exit;
        }
        return json_encode(array("success" => 0, "error_msg" => 'Error in request0'));
        exit;
    }

    /*     * *
     * It will check the security code.
     */

    public function checkForValidCodeOnchangePassword() {
        if (!$this->common_model->isLoggedIn()) {
            echo "false";
            exit;
        }
        $code = $this->input->post('security_code');
        $data = $this->common_model->commonFunction();
        $data['user_session'] = $this->session->userdata('user_account');
        if ($data['user_session']['user_id'] != '') {
            $table_name = 'mst_users';
            $select_data = array('user_id,password_change_security_code');
            $condition = array("user_id" => $data['user_session']['user_id'], 'password_change_security_code' => $code);
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

    public function changeProfilePicture() {
        $data = $this->common_model->commonFunction();
        $data['user_session'] = $this->session->userdata('user_account');
        $user_id = $data['user_session']['user_id'];
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['upload_path'] = './media/front/img/user-profile-pictures/';
        $config['max_size'] = 1024 * 3;
        $config['file_name'] = rand();
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($_FILES['profile_image']['name'] != '') {

            if (!$this->upload->do_upload('profile_image')) {
                $error = $this->upload->display_errors();
                $this->session->set_userdata('image_error', $error);
            } else {
                $thumb_file = explode('.', $this->input->post('old_logo'));
                $absolute_path = $this->common_model->absolutePath();
                $data = $this->upload->data();
                $logo_name = $data['file_name'];

                /* create thumbnail start here */
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = './media/front/img/user-profile-pictures/' . $data['file_name'];
                $config['create_thumb'] = TRUE;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 200;
                $config['height'] = 200;
                $config['new_image'] = './media/front/img/user-profile-pictures/thumb/' . $data['file_name'];

                /* create thumbnail start here */
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = './media/front/img/user-profile-pictures/' . $data['file_name'];
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 200;
                $config['height'] = 200;

                $config['new_image'] = './media/front/img/user-profile-pictures/thumb/' . $data['file_name'];

                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    $error = $this->image_lib->display_errors();
                    $this->session->set_userdata('image_error', $error);
                }

                $profile_image = array("profile_picture" => $data['file_name']);

                //condition to update record	for the user status
                $condition_array = array('user_id' => $user_id);
                $this->common_model->updateRow('mst_users', $profile_image, $condition_array);
            }
            $profile_image = array("profile_picture" => $data['file_name']);
            //condition to update record	for the user status
            $condition_array = array('user_id' => $user_id);
            $this->common_model->updateRow('mst_users', $profile_image, $condition_array);
            $response_arr = array('msg' => 'Success', 'image' => $data['file_name']);
            echo json_encode($response_arr);
        } else {
            $response_arr = array('msg' => 'Failed');
            echo json_encode($response_arr);
        }
    }

    /*
     * Destroy the user session
     */

    function logout() {
        $this->session->unset_userdata('user_account');
        redirect(base_url('sign-in'));
    }

}

?>
