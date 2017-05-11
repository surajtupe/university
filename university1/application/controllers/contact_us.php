<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CONTACT_US extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('contact_us_model');
        $this->load->model('common_model');
    }

    public function index() { 
        //Getting Common data
        $data = $this->common_model->commonFunction();
        $data['global'] = $this->common_model->getGlobalSettings();
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
                    $this->session->set_userdata('contact_success', 'Your message has been posted successfully. We will get back to you soon.');
                } else {
                    $this->session->set_userdata('contact_fail', 'Your message is failed, please try again.');
                }
            } else {
                $this->session->set_userdata('contact_success', 'Your message is failed, please try again.');
            }
            redirect(base_url() . 'contact-us');
        }
        $data['city'] = $data['global']['city'];
        $data['street'] = $data['global']['street'];
        $data['phone_no'] = $data['global']['phone_no'];
        $data['contact_email'] = $data['global']['contact_email'];
        $data['zip_code'] = $data['global']['zip_code'];
        $data['address'] = $data['global']['address'];
        $data['message'] = isset($data['global']['contact_us_message']) ? $data['global']['contact_us_message'] : '';
        $user_details = $this->session->userdata('user_account');
        //Seeting user data
        $data['name'] = ($user_details['first_name']) ? $user_details['first_name'] . " " . $user_details['last_name'] : '';
        $data['email_address'] = ($user_details['user_email']) ? $user_details['user_email'] : '';
        $data['site_title'] = "Contact Us";
        $data['header'] = array("title" => "Welcome to Contact Us module", "keywords" => "", "description" => "");
        $data['user_session'] = $this->session->userdata('user_account');
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/includes/inner-top-nav', $data);
        $this->load->view('front/contact-us/contact-us', $data);
        $this->load->view('front/includes/footer', $data);
    }

    //function to list all the roles

    public function listContactUs() {

        //checking admin is logged in or not
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }
        //Getting Common data
        $data = $this->common_model->commonFunction();
        //checking for admin privilages

        if ($data['user_account']['role_id'] != 1) {
            $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
            if (count($arr_privileges) > 0) {
                foreach ($arr_privileges as $privilege) {
                    $user_privileges[] = $privilege['privilege_id'];
                }
            }
            $arr_login_admin_privileges = $user_privileges;
            if (in_array('4', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        $data['global'] = $this->common_model->getGlobalSettings();
        if (count($this->input->post()) > 0) {
            if ($this->input->post('contact_ids') != "") {
                //getting all id's selected
                $arr_contact_ids = $this->input->post('contact_ids');

                if (count($arr_contact_ids) > 0) {
                    if (count($arr_contact_ids) > 0) {
                        //deleting the admin selected
                        $this->common_model->deleteRows($arr_contact_ids, "mst_contact_us", "contact_id");
                        $this->common_model->deleteRows($arr_contact_ids, "trans_contact_feedback_reply", "contact_id");
                    }
                    $this->session->set_userdata("msg", "<span class='success'>Records deleted successfully!</span>");
                    redirect(base_url() . 'backend/contact-us');
                    exit;
                } else {
                    redirect(base_url() . 'backend/contact-us');
                    exit;
                }
            }
        }

        $data['title'] = "Manage Contact Us";
        $data['arr_contact_us'] = $this->contact_us_model->getContactUs("mst_contact_us", "*", '', 'contact_id desc');
        $this->load->view('backend/contact-us/list', $data);
    }

    function view($contact_id = '') {
        $contact_id = base64_decode($contact_id);
        //checking admin is logged in or not
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }
        //Getting Common data
        $data = $this->common_model->commonFunction();
        $data['title'] = "Message Detail";
        /* get user message */
        $data['arr_contact'] = $this->common_model->getRecords("mst_contact_us", "*", array("contact_id" => intval($contact_id)));
        /* get all replied messages */
        $data['arr_feedback_contact'] = $this->common_model->getRecords("trans_contact_feedback_reply", "message_body,message_subject,reply_date", array("contact_id" => intval($contact_id)));
        /* Appending messages */
        $message = '';
        foreach ($data['arr_feedback_contact'] as $val) {
            $reply_msg = 'Please see below you replied on user message on ' . date($data['global']['date_format'], strtotime($val['reply_date'])) . ':- ' . PHP_EOL . PHP_EOL;
            $message .= $reply_msg . $val['message_body'] . PHP_EOL . PHP_EOL;
        }
        $reply_msg = 'Please see user message on ' . date($data['global']['date_format'], strtotime($data['arr_contact'][0]['date'])) . ':- ' . PHP_EOL . PHP_EOL;
        $message .= $reply_msg . $data['arr_contact'][0]['message'] . PHP_EOL . PHP_EOL;
        $data['reply_msg'] = $reply_msg;
        $data['message'] = $message;
        $this->load->view('backend/contact-us/view', $data);
    }

    function viewMultiLang($contact_id = '') {
        $contact_id = base64_decode($contact_id);
        //checking admin is logged in or not
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }
        //Getting Common data
        $data = $this->common_model->commonFunction();
        //checking for admin privilages
        if ($data['user_account']['role_id'] != 1) {
            $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
            if (count($arr_privileges) > 0) {
                foreach ($arr_privileges as $privilege) {
                    $user_privileges[] = $privilege['privilege_id'];
                }
            }
            $arr_login_admin_privileges = $user_privileges;
            if (in_array('4', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        $data['title'] = "Message Detail";
        /* get user message */
        $data['arr_contact'] = $this->common_model->getRecords("mst_contact_us", "*", array("contact_id" => intval($contact_id)));
        /* get all replied messages */
        $data['arr_feedback_contact'] = $this->common_model->getRecords("trans_contact_feedback_reply", "message_body,message_subject,reply_date", array("contact_id" => intval($contact_id)));
        /* Appending messages */
        $message = '';
        foreach ($data['arr_feedback_contact'] as $val) {
            $reply_msg = 'Please see below you replied on user message on ' . date($data['global']['date_format'], strtotime($val['reply_date'])) . ':- ' . PHP_EOL . PHP_EOL;
            $message .= $reply_msg . $val['message_body'] . PHP_EOL . PHP_EOL;
        }
        $reply_msg = 'Please see user message on ' . date($data['global']['date_format'], strtotime($data['arr_contact'][0]['date'])) . ':- ' . PHP_EOL . PHP_EOL;
        $message .= $reply_msg . $data['arr_contact'][0]['message'] . PHP_EOL . PHP_EOL;
        $data['reply_msg'] = $reply_msg;
        $data['message'] = $message;
        $this->load->view('backend/contact-us/view', $data);
    }

    function reply($contact_id = '') {
        $contact_id = base64_decode($contact_id);
        //checking admin is logged in or not
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }
        //Getting Common data
        $data = $this->common_model->commonFunction();
        //checking for admin privilages
        if ($data['user_account']['role_id'] != 1) {
            $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
            if (count($arr_privileges) > 0) {
                foreach ($arr_privileges as $privilege) {
                    $user_privileges[] = $privilege['privilege_id'];
                }
            }
            $arr_login_admin_privileges = $user_privileges;
            if (in_array('4', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        if ($this->input->post('contact_id') != "") {
            /* insert contact feedback details */
            $arr_insert_fields = array(
                "contact_id" => $this->input->post('contact_id'),
                "message_to" => addslashes($this->input->post('to')),
                "message_from_name" => addslashes($this->input->post('from_name')),
                "message_from_email" => addslashes($this->input->post('from_email')),
                "message_subject" => addslashes($this->input->post('subject')),
                "message_body" => addslashes($this->input->post('message')),
                "reply_date" => date('Y-m-d H:i:s')
            );
            $last_id = $this->common_model->insertRow($arr_insert_fields, 'trans_contact_feedback_reply');
            $arr_update_fields = array(
                "reply_status" => '1'
            );
            /* update reply status */
            $arr_condition = array("contact_id" => $this->input->post('contact_id'));
            $this->common_model->updateRow('mst_contact_us', $arr_update_fields, $arr_condition);
            /* send mail */
            $mail = $this->common_model->sendEmail(array($this->input->post('to')), array("email" => $data['global']['contact_email'], "name" => stripslashes($data['global']['site_title'])), $this->input->post('subject'), nl2br($this->input->post('message')));
            if ($mail) {
                $this->session->set_userdata("msg", "<span class='success'>Reply message sent successfully!</span>");
            } else {
                $this->session->set_userdata("msg", "<span class='success'>Reply message sent failed!</span>");
            }
            redirect(base_url() . 'backend/contact-us');
        }

        $data['title'] = "Reply Message";
        /* get user message */
        $data['arr_contact'] = $this->common_model->getRecords("mst_contact_us", "*", array("contact_id" => intval($contact_id)));
        /* get all replied messages */
        $data['arr_feedback_contact'] = $this->common_model->getRecords("trans_contact_feedback_reply", "message_body,message_subject,reply_date", array("contact_id" => intval($contact_id)));
        $data['reply_msg'] = "";
        $data['message'] = "";
        $this->load->view('backend/contact-us/reply', $data);
    }

    function replyMultiLang($contact_id = '') {
        $contact_id = base64_decode($contact_id);
        //checking admin is logged in or not
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }
        //Getting Common data
        $data = $this->common_model->commonFunction();
        //checking for admin privilages
        if ($data['user_account']['role_id'] != 1) {
            $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
            if (count($arr_privileges) > 0) {
                foreach ($arr_privileges as $privilege) {
                    $user_privileges[] = $privilege['privilege_id'];
                }
            }
            $arr_login_admin_privileges = $user_privileges;
            if (in_array('4', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        if ($this->input->post('contact_id') != "") {
            /* insert contact feedback details */
            $arr_insert_fields = array(
                "contact_id" => $this->input->post('contact_id'),
                "message_to" => addslashes($this->input->post('to')),
                "message_from_name" => addslashes($this->input->post('from_name')),
                "message_from_email" => addslashes($this->input->post('from_email')),
                "message_subject" => addslashes($this->input->post('subject')),
                "message_body" => addslashes($this->input->post('message')),
                "reply_date" => date('Y-m-d H:i:s')
            );
            $last_id = $this->common_model->insertRow($arr_insert_fields, 'trans_contact_feedback_reply');
            $arr_update_fields = array(
                "reply_status" => '1'
            );
            /* update reply status */
            $arr_condition = array("contact_id" => $this->input->post('contact_id'));
            $this->common_model->updateRow('mst_contact_us', $arr_update_fields, $arr_condition);
            /* send mail */
            $mail = $this->common_model->sendEmail(array($this->input->post('to')), array("email" => $data['global']['site_email'], "name" => stripslashes($data['global']['site_title'])), $this->input->post('subject'), nl2br($this->input->post('message')));
            if ($mail) {
                $this->session->set_userdata("msg", "<span class='success'>Reply message sent successfully!</span>");
            } else {
                $this->session->set_userdata("msg", "<span class='success'>Reply message sent failed!</span>");
            }
            redirect(base_url() . 'backend/contact-us');
        }

        $data['title'] = "Reply Message";
        /* get user message */
        $data['arr_contact'] = $this->common_model->getRecords("mst_contact_us", "*", array("contact_id" => intval($contact_id)));
        /* get all replied messages */
        $data['arr_feedback_contact'] = $this->common_model->getRecords("trans_contact_feedback_reply", "message_body,message_subject,reply_date", array("contact_id" => intval($contact_id)));
        $data['reply_msg'] = "";
        $data['message'] = "";
        $this->load->view('backend/contact-us/reply', $data);
    }

}

/* End of file contact-us.php */
/* Location: ./application/controllers/contact_us.php */