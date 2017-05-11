<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('user_model');
        $this->load->model('address_model');
        /* checking admin is logged in or not */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit();
        }
    }

    /* function to list all the users */

    public function listUser() {
        /* Getting Common data */
        $data = $this->common_model->commonFunction();
        /* checking user has privilige for the Manage Admin */
        if ($data['user_account']['role_id'] != 1) {
            $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
            if (count($arr_privileges) > 0) {
                foreach ($arr_privileges as $privilege) {
                    $user_privileges[] = $privilege['privilege_id'];
                }
            }
            $arr_login_admin_privileges = $user_privileges;
            if (in_array('1', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        if (count($this->input->post()) > 0) {
            if ($this->input->post('btn_delete_all') != "") {
                /* getting all ides selected */
                $arr_user_ids = $this->input->post('checkbox');
                if (count($arr_user_ids) > 0 && is_array($arr_user_ids)) {
                    foreach ($arr_user_ids as $user_id) {
                        /* getting user details */
                        $arr_admin_detail = $this->common_model->getRecords("mst_users", "", array("user_id" => intval($user_id)));
                        if (count($arr_admin_detail) > 0) {
                            /* setting reserved_words for email content */
                            $lang_id = 17;
                            $macros_array_detail = array();
                            $macros_array_detail = $this->common_model->getRecords('mst_email_template_macros', 'macros,value', $condition_to_pass = '', $order_by = '', $limit = '', $debug = 0);
                            $macros_array = array();
                            if (is_array($macros_array_detail)) {
                                foreach ($macros_array_detail as $row) {
                                    $macros_array[$row['macros']] = $row['value'];
                                }
                            }
                            $reserved_words = array();
                            $reserved_arr = array("||SITE_TITLE||" => $data['global']['site_title'],
                                "||SITE_PATH||" => base_url(),
                                "||ADMIN_NAME||" => $arr_admin_detail[0]['user_name']
                            );
                            $reserved_words = array_replace_recursive($macros_array, $reserved_arr);
                            $template_title = 'admin-deleted';
                            /* getting mail subject and mail message using email template title and lang_id and reserved works */
                            $email_content = $this->common_model->getEmailTemplateInfo('admin-deleted', 17, $reserved_words);
                            /* sending admin user mail for account deletion.
                              1.recipient array. 2.From array containing email and name, 3.Mail subject 4.Mail Body
                             */
                            $mail = $this->common_model->sendEmail(array($arr_admin_detail[0]['user_email']), array("email" => $data['global']['site_email'], "name" => $data['global']['site_title']), $email_content['subject'], $email_content['content']);
                            $this->load->model('admin_model');
                            if ($user_id) {
                                $this->admin_model->updateDeletedUserFile($this->common_model->absolutePath(), intval($user_id));
                            }
                        }
                    }
                    if (count($arr_user_ids) > 0) {
                        /* deleting the user selected */
                        $this->common_model->deleteRows($arr_user_ids, "mst_users", "user_id");
                    }
                    $this->session->set_userdata("msg", "<span class='success'>User deleted successfully!</span>");
                }
            }
        }
        /* using the user model */
        $data['title'] = "Manage User";
        $data['arr_user_list'] = $this->user_model->getUserDetails('');
        $this->load->view('backend/user/list', $data);
    }

    public function changeStatus() {
        if ($this->input->post('user_id') != "") {
            /* updating the user status. */
            $arr_to_update = array(
                "user_status" => $this->input->post('user_status')
            );
            /* condition to update record	for the user status */
            $condition_array = array('user_id' => intval($this->input->post('user_id')));
            $this->common_model->updateRow('mst_users', $arr_to_update, $condition_array);
            /* Addmin the user in bloked list into file */
            $this->load->model('admin_model');
            if (intval($this->input->post('user_id'))) {
                $this->admin_model->updateBlockedUserFile($this->common_model->absolutePath(), $this->input->post('user_status'), intval($this->input->post('user_id')));
            }
            echo json_encode(array("error" => "0", "error_message" => "Status has changed successflly."));
        } else {
            /* if something going wrong providing error message. */
            echo json_encode(array("error" => "1", "error_message" => "Sorry, your request can not be fulfilled this time. Please try again later."));
        }
    }

    public function changeStatusEmail() {
        if ($this->input->post('user_id') != "" && $this->input->post('user_email_verified') != '') {
            /* condition to update record for the user status */
            $condition_array = array('user_id' => intval($this->input->post('user_id')));
            $data['user_deatils'] = $this->user_model->getUserInformation('mst_users', '*', $condition_array);
            if ($data['user_deatils'][0]['user_status'] == 0 && $this->input->post('user_email_verified') == '1') {
                $arr_to_update = array(
                    "email_verified" => $this->input->post('user_email_verified'),
                    "user_status" => '1'
                );
            } else {
                $arr_to_update = array(
                    "email_verified" => $this->input->post('user_email_verified')
                );
            }
            $this->common_model->updateRow('mst_users', $arr_to_update, $condition_array);
            echo json_encode(array("error" => "0", "error_message" => "Status has changed successflly."));
        } else {
            /* if something going wrong providing error message. */
            echo json_encode(array("error" => "1", "error_message" => "Sorry, your request can not be fulfilled this time. Please try again later"));
        }
    }

    /* function to check existancs of username */

    public function checkUserUsername() {
        if ($this->input->post('type') != "") {
            /* checking username already exist or not for edit user */
            if (strtolower($this->input->post('user_name')) == strtolower($this->input->post('old_username'))) {
                echo "true";
            } else {
                $arr_admin_detail = $this->common_model->getRecords('mst_users', 'user_name', array("user_name" => mysql_real_escape_string($this->input->post('user_name'))));
                if (count($arr_admin_detail) == 0) {
                    echo "true";
                } else {
                    echo "false";
                }
            }
        } else {
            /* checking username already exist or not for add user */
            $arr_admin_detail = $this->common_model->getRecords('mst_users', 'user_name', array("user_name" => mysql_real_escape_string($this->input->post('user_name'))));
            if (count($arr_admin_detail) == 0) {
                echo "true";
            } else {
                echo "false";
            }
        }
    }

    /* function to check existancs of user email */

    public function checkUserEmail() {
        if ($this->input->post('type') != "") {
            /* checking user email already exist or not for edit user */
            if (strtolower($this->input->post('user_email')) == strtolower($this->input->post('old_email'))) {
                echo "true";
            } else {
                $arr_admin_detail = $this->common_model->getRecords('mst_users', 'user_email', array("user_email" => mysql_real_escape_string($this->input->post('user_email'))));
                if (count($arr_admin_detail) == 0) {
                    echo "true";
                } else {
                    echo "false";
                }
            }
        } else {
            /* checking user email already exist or not for add user */
            $arr_admin_detail = $this->common_model->getRecords('mst_users', 'user_email', array("user_email" => mysql_real_escape_string($this->input->post('user_email'))));
            if (count($arr_admin_detail) == 0) {
                echo "true";
            } else {
                echo "false";
            }
        }
    }

    /* function to add new user from backend */

    public function addUser() {
        /* Getting Common data */
        $data = $this->common_model->commonFunction();
        /* checking user has privilige for the Manage Admin */
        if ($data['user_account']['role_id'] != 1) {
            $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
            if (count($arr_privileges) > 0) {
                foreach ($arr_privileges as $privilege) {
                    $user_privileges[] = $privilege['privilege_id'];
                }
            }
            $arr_login_admin_privileges = $user_privileges;
            if (in_array('1', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        if (count($this->input->post()) > 0) {
            if ($this->input->post('btn_submit') != "") {
                $activation_code = time();
                /* user record to add */
                $arr_to_insert = array(
                    "user_name" => mysql_real_escape_string($this->input->post('user_name')),
                    "first_name" => mysql_real_escape_string($this->input->post('first_name')),
                    "last_name" => mysql_real_escape_string($this->input->post('last_name')),
                    "user_email" => mysql_real_escape_string($this->input->post('user_email')),
                    "user_password" => base64_encode($this->input->post('user_password')),
                    'user_type' => 1,
                    'user_status' => 0,
                    'activation_code' => $activation_code,
                    'email_verified' => 1,
                    'role_id' => 0,
                    'register_date' => date("Y-m-d H:i:s")
                );
                /* inserting user details into the database */
                $last_insert_id = $this->common_model->insertRow($arr_to_insert, "mst_users");
                /* Activation link */
                $activation_link = '<a href="' . base_url() . 'backend/user/account-activate/' . $activation_code . '">Activate Account</a>';
                /* setting reserved_words for email content */
                $reserved_words = array(
                    "||SITE_TITLE||" => $data['global']['site_title'],
                    "||SITE_PATH||" => base_url(),
                    "||USER_NAME||" => $this->input->post('user_name'),
                    "||ADMIN_NAME||" => $this->input->post('first_name') . ' ' . $this->input->post('last_name'),
                    "||ADMIN_EMAIL||" => $this->input->post('user_email'),
                    "||PASSWORD||" => $this->input->post('user_password'),
                    "||ACTIVATION_LINK||" => $activation_link
                );

                /* getting mail subect and mail message using email template title and lang_id and reserved works */
                $email_content = $this->common_model->getEmailTemplateInfo('user-added-by-admin', 17, $reserved_words);
                /* sending admin added mail to added admin mail id.
                 * 1.recipient array. 2.From array containing email and name, 3.Mail subject 4.Mail Body
                 */
                $mail = $this->common_model->sendEmail(array($this->input->post('user_email')), array("email" => $data['global']['site_email'], "name" => $data['global']['site_title']), $email_content['subject'], $email_content['content']);
                $this->session->set_userdata("msg", "<span class='success'>User added successfully!</span>");
                redirect(base_url() . "backend/user/list");
                exit();
            }
        }
        $arr_privileges = array();
        /* getting all privileges */
        $data['arr_privileges'] = $this->common_model->getRecords('mst_privileges');
        $data['title'] = "Add User";
        $data['arr_roles'] = $this->common_model->getRecords("mst_role");
        $this->load->view('backend/user/add', $data);
    }

    /* function to activate user account */

    public function activateAccount($activation_code) {
        $fields_to_pass = array('user_id', 'first_name', 'last_name', 'user_name', 'user_email', 'user_type', 'email_verified', 'user_status');
        // get user details to verify the email address
        $arr_login_data = $this->common_model->getRecords("mst_users", $fields_to_pass, array("activation_code" => $activation_code));
        if (count($arr_login_data)) {
            /* if email already verified */
            if ($arr_login_data[0]['email_verified'] == 1) {
                $this->session->set_userdata('msg', '<div class="alert alert-success">Your have already activated your account. Please login.</div>');
            } else {
                /* if email not verified. */
                $update_data = array('email_verified' => '1', 'user_status' => '1');
                $this->common_model->updateRow("mst_users", $update_data, array("activation_code" => $activation_code));
                $this->session->set_userdata('msg', '<div class="alert alert-success"><strong>Congratulation!</strong> Your account has been activated successfully. Please login.</div>');
            }
        } else {
            /* if any error invalid activation link found account */
            $this->session->set_userdata('msg', '<div class="alert alert-error">Invalid activation code.</div>');
        }
        redirect(base_url() . "backend/login");
        exit();
    }

    public function editUser($edit_id = '') {
        $data = $this->common_model->commonFunction();
        /* checking user has privilige for the Manage Admin */
        if ($data['user_account']['role_id'] != 1) {
            $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
            if (count($arr_privileges) > 0) {
                foreach ($arr_privileges as $privilege) {
                    $user_privileges[] = $privilege['privilege_id'];
                }
            }
            $arr_login_admin_privileges = $user_privileges;
            if (in_array('1', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="validationError">', '</p>');
        $this->form_validation->set_rules('first_name', 'user first name', 'required');
        $this->form_validation->set_rules('last_name', 'user last name', 'required');
        $this->form_validation->set_rules('user_email', 'user email', 'required|valid_email');
        if ($this->form_validation->run() == true && $this->input->post('user_email') != "") {

            if ($this->input->post('edit_id') != "") {
                $arr_admin_detail = $this->common_model->getRecords("mst_users", "", array("user_id" => intval($this->input->post('edit_id'))));
                // single row fix
                $arr_admin_detail = end($arr_admin_detail);
                /* setting variable to update or add user record.. */
                if ($this->input->post('user_email') == $this->input->post('old_email')) {
                    if ($this->input->post('user_status') != "" && $this->input->post('user_status') != "0") {
                        $status = $this->input->post('user_status');
                        if ($status == '1') {
                            if ($arr_admin_detail['email_verified'] == '0') {
                                $email_verified = "0";
                            } else {
                                $email_verified = "1";
                            }
                        } else {
                            if ($arr_admin_detail['email_verified'] == '0') {
                                $email_verified = "0";
                            } else {
                                $email_verified = "1";
                            }
                        }
                    } else {
                        $status = "1";
                    }
                    $activation_code = $arr_admin_detail['activation_code'];
                } else {
                    $status = "1";
                    $activation_code = time();
                    $email_verified = "0";
                }
                $date_of_birth = $this->input->post('day') . '/' . $this->input->post('month') . '/' . $this->input->post('year');
                if ($this->input->post('change_password') == 'on') {
                    $user_password = $this->input->post('user_password');
                    if ($this->input->post('user_status') != "" && $this->input->post('user_status') != "0") {
                        $status = $this->input->post('user_status');
                        if ($status == 1) {
                            $email_verified = "1";
                        }
                    } else {
                        $status = "0";
                    }
                    // generating the password by using hashing technique and applyng salt on it
                    //crypt has method is used. 2y is crypt algorith selector
                    //12 is workload factor on core processor.

                    $salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
                    $hash_password = crypt($this->input->post('new_user_password'), '$2y$12$' . $salt);
                    /* if password need to change */
                    /* if password need to change */
                    $arr_to_update = array(
                        "first_name" => mysql_real_escape_string($this->input->post('first_name')),
                        "last_name" => mysql_real_escape_string($this->input->post('last_name')),
                        "user_email" => mysql_real_escape_string($this->input->post('user_email')),
                        "user_status" => $status,
                        'email_verified' => $email_verified,
                        'activation_code' => $activation_code,
                        'user_birth_date' => $date_of_birth,
                        'role_id' => 1,
                        'user_password' => $hash_password
                    );
                } else {
                    /* if passwording need not need to change */
                    $arr_to_update = array(
                        "first_name" => mysql_real_escape_string($this->input->post('first_name')),
                        "middle_name" => mysql_real_escape_string($this->input->post('middle_name')),
                        "last_name" => mysql_real_escape_string($this->input->post('last_name')),
                        "user_email" => mysql_real_escape_string($this->input->post('user_email')),
                        "user_status" => $status,
                        'email_verified' => $email_verified,
                        'activation_code' => $activation_code,
                        'role_id' => 1,
                        'user_birth_date' => $date_of_birth,
                    );
                }

                /* updating the user details */
                $this->common_model->updateRow("mst_users", $arr_to_update, array("user_id" => $this->input->post('edit_id')));
                if ($this->input->post('user_email') == $this->input->post('old_email')) {
                    /* sending account updating mail to user */
                    $user_login_link = '<a href="' . base_url() . 'sign-in" target="_new">Please login</a>';
                    $macros_array_detail = array();
                    $macros_array_detail = $this->common_model->getRecords('mst_email_template_macros', 'macros,value', $condition_to_pass = '', $order_by = '', $limit = '', $debug = 0);
                    $macros_array = array();
                    if (is_array($macros_array_detail)) {
                        foreach ($macros_array_detail as $row) {
                            $macros_array[$row['macros']] = $row['value'];
                        }
                    }
                    $reserved_words = array();
                    $reserved_arr = array
                        ("||SITE_TITLE||" => $data['global']['site_title'],
                        "||SITE_PATH||" => base_url(),
                        "||ADMIN_NAME||" => $this->input->post('first_name') . ' ' . $this->input->post('last_name'),
                        "||ADMIN_EMAIL||" => $this->input->post('user_email'),
                        "||ADMIN_LOGIN_LINK||" => $user_login_link
                    );
                    $reserved_words = array_replace_recursive($macros_array, $reserved_arr);
                    /* getting mail subect and mail message using email template title and lang_id and reserved works */
                    $email_content = $this->common_model->getEmailTemplateInfo('admin-updated', 17, $reserved_words);
                    /* sending the mail to deleting user
                     * 1.recipient array. 2.From array containing email and name, 3.Mail subject 4.Mail Body */
                    $mail = $this->common_model->sendEmail(array($this->input->post('user_email')), array("email" => $data['global']['site_email'], "name" => $data['global']['site_title']), $email_content['subject'], $email_content['content']);
                } else {
                    /* sending account verification link mail to user */
                    $lang_id = 17;
                    $activation_link = '<a href="' . base_url() . 'backend/user/account-activate/' . $activation_code . '">Activate Account</a>';

                    $macros_array_detail = array();
                    $macros_array_detail = $this->common_model->getRecords('mst_email_template_macros', 'macros,value', $condition_to_pass = '', $order_by = '', $limit = '', $debug = 0);
                    $macros_array = array();
                    if (is_array($macros_array_detail)) {
                        foreach ($macros_array_detail as $row) {
                            $macros_array[$row['macros']] = $row['value'];
                        }
                    }
                    $reserved_words = array();
                    $reserved_arr = array
                        ("||SITE_TITLE||" => $data['global']['site_title'],
                        "||SITE_PATH||" => base_url(),
                        "||USER_NAME||" => $this->input->post('first_name') . ' ' . $this->input->post('last_name'),
                        "||ADMIN_EMAIL||" => $this->input->post('user_email'),
                        "||PASSWORD||" => $user_password,
                        "||ADMIN_ACTIVATION_LINK||" => $activation_link
                    );
                    $reserved_words = array_replace_recursive($macros_array, $reserved_arr);
                    /* getting mail subect and mail message using email template title and lang_id and reserved works */
                    $email_content = $this->common_model->getEmailTemplateInfo('admin-email-updated', 17, $reserved_words);
                    /* sending the mail to deleting user
                     * 1.recipient array. 2.From array containing email and name, 3.Mail subject 4.Mail Body */
                    $mail = $this->common_model->sendEmail(array($this->input->post('user_email')), array("email" => $data['global']['site_email'], "name" => $data['global']['site_title']), $email_content['subject'], $email_content['content']);
                }
                $this->session->set_userdata("msg", "<span class='success'>User updated successfully!</span>");
                redirect(base_url() . "backend/user/list");
                exit();
            }
        }
        $arr_privileges = array();
        /* getting all privileges */
        $data['arr_privileges'] = $this->common_model->getRecords('mst_privileges');
        /* getting user details from $edit_id from function parameter */
        $data['arr_admin_detail'] = $this->common_model->getRecords("mst_users", "", array("user_id" => intval(base64_decode($edit_id))));
        $data['arr_admin_detail'] = end($data['arr_admin_detail']);
        $data['title'] = "Edit User";
        $data['edit_id'] = $edit_id;
        $data['arr_roles'] = $this->common_model->getRecords("mst_role");
        $this->load->view('backend/user/edit', $data);
    }

    /* function to display admin profile */

    public function userProfile($user_id) {
        $user_id = base64_decode($user_id);
        /* using the user model */
        $data = $this->common_model->commonFunction();
        $arr_privileges = array();
        /* getting all privileges */
        $data['arr_privileges'] = $this->common_model->getRecords('mst_privileges');
        /* setting user details from user id from function parameter  */
        $data['arr_user_detail'] = $this->user_model->getUserDetailsByID($user_id);
        // single row fix
        $data['arr_user_detail'] = end($data['arr_user_detail']);
        $data['title'] = "Profile";
        $this->load->view('backend/user/user-profile', $data);
    }

//    public function userAddress($user_id) {
//        $user_id = base64_decode($user_id);
//        /* using the user model */
//        $data = $this->common_model->commonFunction();
//        $arr_privileges = array();
//        /* getting all privileges */
//        $data['arr_privileges'] = $this->common_model->getRecords('mst_privileges');
//        /* setting user details from user id from function parameter  */
//
//        $con = array("ua.user_id_fk" => $user_id);
//        $address_details = $this->address_model->getBackendAddressDetails($con);
////        echo "<pre>";
////        print_r($address_details);
////        die;
//        $current_address = array();
//        $permanent_address = array();
//        $office_address = array();
//        foreach ($address_details as $key => $value) {
//            if ($value['address_type_text'] == "Current Address") {
//                $con = array("la.address_type_text" => "Current Address");
//                $current_address = end($this->address_model->getBackendAddressDetails($con));
//            }
//            if ($value['address_type_text'] == "Permanent Address") {
//                $con = array("la.address_type_text" => "Permanent Address");
//                $permanent_address = end($this->address_model->getBackendAddressDetails($con));
//            }
//            if ($value['address_type_text'] == "Office Address") {
//                $con = array("la.address_type_text" => "Office Address");
//                $office_address = end($this->address_model->getBackendAddressDetails($con));
//            }
//        }
//        $data['current_address'] = $current_address;
//        $data['permanent_address'] = $permanent_address;
//        $data['office_address'] = $office_address;
//        $data['title'] = "View User Address";
//        $this->load->view('backend/user/user-address', $data);
//    }
    public function userCurrentAddress($user_id) {
        $user_id = base64_decode($user_id);
        /* using the user model */
        $data = $this->common_model->commonFunction();
        $arr_privileges = array();
        /* getting all privileges */
        $data['arr_privileges'] = $this->common_model->getRecords('mst_privileges');
        /* setting user details from user id from function parameter  */

        $con = array("ua.user_id_fk" => $user_id, "la.address_type_text" => "Current Address");
        $address_details = end($this->address_model->getBackendAddressDetails($con));
        $data['current_address'] = $address_details;
        $data['user_id'] = $user_id;
        $data['title'] = "View User Address";
        $this->load->view('backend/user/user-current-address', $data);
    }

    public function userPermantAddress($user_id) {
        $user_id = base64_decode($user_id);
        /* using the user model */
        $data = $this->common_model->commonFunction();
        $arr_privileges = array();
        /* getting all privileges */
        $data['arr_privileges'] = $this->common_model->getRecords('mst_privileges');
        /* setting user details from user id from function parameter  */

        $con = array("ua.user_id_fk" => $user_id, "la.address_type_text" => "Permanent Address");
        $address_details = end($this->address_model->getBackendAddressDetails($con));
        $data['permanent_address'] = $address_details;
        $data['user_id'] = $user_id;
        $data['title'] = "View User Address";
        $this->load->view('backend/user/user-permanent-address', $data);
    }

    public function userOfficeAddress($user_id) {
        $user_id = base64_decode($user_id);
        /* using the user model */
        $data = $this->common_model->commonFunction();
        $arr_privileges = array();
        /* getting all privileges */
        $data['arr_privileges'] = $this->common_model->getRecords('mst_privileges');
        /* setting user details from user id from function parameter  */

        $con = array("ua.user_id_fk" => $user_id, "la.address_type_text" => "Office Address");
        $address_details = end($this->address_model->getBackendAddressDetails($con));
        $data['office_address'] = $address_details;
        $data['user_id'] = $user_id;
        $data['title'] = "View User Address";
        $this->load->view('backend/user/user-office-address', $data);
    }

    public function userForwardAddress($user_id) {

        $user_id = base64_decode($user_id);
        /* using the user model */
        $data = $this->common_model->commonFunction();
        $arr_privileges = array();
        /* getting all privileges */
        $data['arr_privileges'] = $this->common_model->getRecords('mst_privileges');
        /* setting user details from user id from function parameter  */

        $con = array("ua.user_id_fk" => $user_id);
        $address_details = end($this->address_model->getForwardingAddressDetails($con));
        $data['permanent_address'] = $address_details;

        $data['user_id'] = $user_id;
        $data['title'] = "View User Address";
        $this->load->view('backend/user/user-forward-address', $data);
    }

    function reverificationLink($user_id = 0, $user_type) {
        /* Getting Common data */
        $data = $this->common_model->commonFunction();
        $data['title'] = "Reverification Link";
        if ($user_id != '' && $user_type != '') {
            $user_id = base64_decode($user_id);
            $data['arr_user_details'] = $this->user_model->getUserDetailsByID($user_id);
            $activation_code = $data['arr_user_details'][0]['activation_code'];
            $user_name = $data['arr_user_details'][0]['first_name'] . ' ' . $data['arr_user_details'][0]['last_name'];
            $user_email = $data['arr_user_details'][0]['user_email'];
            $varification_link = '<a href="' . base_url() . 'user-activation/' . $activation_code . '" target="_blank">Activate your account</a>';
            $macros_array_detail = array();
            $macros_array_detail = $this->common_model->getRecords('mst_email_template_macros', 'macros,value', $condition_to_pass = '', $order_by = '', $limit = '', $debug = 0);
            $macros_array = array();
            if (is_array($macros_array_detail)) {
                foreach ($macros_array_detail as $row) {
                    $macros_array[$row['macros']] = $row['value'];
                }
            }
            $reserved_words = array();

            $reserved_arr = array
                (
                "||USER_NAME||" => $user_name,
                "||VARIFICATION_LINK||" => $varification_link,
                "||SITE_TITLE||" => $data['global']['site_title']
            );
            $reserved_words = array_replace_recursive($macros_array, $reserved_arr);
            $template_title = 'reverification-link-send';
            $lang_id = 17; // Default is 17(English)
            $arr_emailtemplate_data = $this->common_model->getEmailTemplateInfo($template_title, $lang_id, $reserved_words);
            $recipeinets = $user_email;
            $from = array("email" => $data['global']['site_email'], "name" => $data['global']['site_title']);
            $subject = $arr_emailtemplate_data['subject'];
            $message = $arr_emailtemplate_data['content'];
            $mail = $this->common_model->sendEmail($recipeinets, $from, $subject, $message);
            if ($mail) {
                if ($user_type == '1') {
                    $this->session->set_userdata('msg', 'Reverification link  sent successfully!');
                    redirect(base_url() . 'backend/user/list');
                    exit();
                }
            } else {
                if ($user_type == '2') {
                    $this->session->set_userdata('msg', 'Reverification link not sent successfully!');
                    redirect(base_url() . 'backend/user');
                    exit();
                }
            }
        } else {
            redirect(base_url() . 'backend/dashboard');
            exit();
        }
    }

}
