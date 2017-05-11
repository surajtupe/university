<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Newsletter extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('newsletter_model');
        $this->load->model('common_model');
        //checking admin is logged in or not
       
    }

    public function listNewsletter() {
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
             if(in_array('6',$arr_login_admin_privileges)==FALSE)    
             {
                  /*an admin which is not super admin not privileges to access Manage Role
                   *setting session for displaying notiication message.*/
                  $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                  redirect(base_url() . "backend/home");
                  exit();
             }
            } 
        //getting all ides selected
        $arr_newsletter_ids=array();
        if($this->input->post('checkbox')!=''){
        $arr_newsletter_ids = $this->input->post('checkbox');
        if (count($arr_newsletter_ids) > 0) {
            if (count($arr_newsletter_ids) > 0) {
                //deleting the newsletter selected
                $this->common_model->deleteRows($arr_newsletter_ids, "mst_newsletter", "newsletter_id");
            }
           $msg_data = array('msg_type' => 'success', 'newsletter_msg_val' => 'Records deleted successfully.');
           $this->session->set_userdata('newsletter_msg', $msg_data);
           redirect(base_url() . "backend/newsletter/list");
        }
        }

        $data['global'] = $this->common_model->getGlobalSettings();
        $data['title'] = "Manage Newsletter";
        $data['arr_newsletter_list'] = $this->newsletter_model->getNewsletterDetails();
        $this->load->view('backend/newsletter/list', $data);
    }

    public function changeStatus() {
         if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }
        if ($this->input->post('newsletter_id') != "") {
            //updating the newsletter status.
            
            $arr_to_update = array(
                "newsletter_status" => $this->input->post('status')
            );
            //condition to update record	for the newsletter status
            $condition_array = array('newsletter_id' => ($this->input->post('newsletter_id')));
            $this->common_model->updateRow('mst_newsletter', $arr_to_update, $condition_array);
            echo json_encode(array("error" => "0", "error_message" => "Status has been changed successflly."));
        } else {
            //if something going wrong providing error message. 
            echo json_encode(array("error" => "1", "error_message" => "Sorry, your request can not be fulfilled this time. Please try again later"));
        }
    }

    public function addNewsletter() {
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
             if(in_array('6',$arr_login_admin_privileges)==FALSE)    
             {
                  /*an admin which is not super admin not privileges to access Manage Role
                   *setting session for displaying notiication message.*/
                  $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                  redirect(base_url() . "backend/home");
                  exit();
             }
        } 
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="validationError">', '</p>');
        $this->form_validation->set_rules('input_subject', 'newsletter subject', 'required');
        $this->form_validation->set_rules('input_content', 'newsletter content', 'required');

        if ($this->form_validation->run() == true) {
            $newsletter_details = array("newsletter_subject" => $this->input->post('input_subject'), "newsletter_content" => $this->input->post('input_content'), "add_date" => date('Y-m-d H:i:s'));
            $this->newsletter_model->addNewsletterDetails($newsletter_details);
            $msg_data = array('msg_type' => 'success', 'newsletter_msg_val' => 'Record added successfully.');
            $this->session->set_userdata('newsletter_msg', $msg_data);
            redirect(base_url() . "backend/newsletter/list");
        }

        //using the newsletter model
        $data['title'] = "Add Newsletter";
        $this->load->view('backend/newsletter/add', $data);
    }

    function uploadClEditorImage() {
        if ($_FILES["imageName"]['name'] != "") {
            $file_destination = "media/backend/userfiles/" . str_replace(" ", "_", microtime()) . "." . strtolower(end(explode(".", $_FILES["imageName"]['name'])));
            move_uploaded_file($_FILES['imageName']['tmp_name'], $file_destination);
            ?>
            <div id="image"><?php echo base_url() . $file_destination; ?></div>
            <?php
        } else {
            echo "false";
        }
    }

    public function editNewsletter($newsletter_id) {
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
             if(in_array('6',$arr_login_admin_privileges)==FALSE)    
             {
                  /*an admin which is not super admin not privileges to access Manage Role
                   *setting session for displaying notiication message.*/
                  $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                  redirect(base_url() . "backend/home");
                  exit();
             }
            } 
        $arr_newsletter_data = array();
        /*
         * Get newsletter details by id
         */
        $arr_newsletter_data = $this->newsletter_model->getNewsletterDetailById($newsletter_id);
        /*
         * If invalid newsletter id the requiret to newsletter listing page
         */
        if (count($arr_newsletter_data)==0) {
            $msg_data = array('msg_type' => 'error', 'newsletter_msg_val' => 'Invalid url.');
            $this->session->set_userdata('newsletter_msg', $msg_data);
            redirect(base_url() . "backend/newsletter/list");
        }
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="validationError">', '</p>');
        $this->form_validation->set_rules('input_subject', 'newsletter subject', 'required');
       // $this->form_validation->set_rules('input_content', 'newsletter content', 'required');

        if ($this->form_validation->run() == true) {
            $newsletter_details = array("newsletter_subject" => $this->input->post('input_subject'), "newsletter_content" => $this->input->post('input_content'), "update_date" => date('Y-m-d'));
            $condition = array('newsletter_id'=> $newsletter_id);
            $this->newsletter_model->updateNewsletterDetails($newsletter_details, $condition);
            $msg_data = array('msg_type' => 'success', 'newsletter_msg_val' => 'Record updated successfully.');
            $this->session->set_userdata('newsletter_msg', $msg_data);
            redirect(base_url() . "backend/newsletter/list");
        }

        //using the newsletter model
        $data['title'] = "Edit Newsletter";
        $data['arr_newsletter_data'] = $arr_newsletter_data[0];
        $this->load->view('backend/newsletter/edit', $data);
    }
    /*
     * function to send newsletter 
     */
    function sendNewsletter($newsletter_id = '') {
         if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }
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
             if(in_array('6',$arr_login_admin_privileges)==FALSE)    
             {
                  /*an admin which is not super admin not privileges to access Manage Role
                   *setting session for displaying notiication message.*/
                  $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                  redirect(base_url() . "backend/home");
                  exit();
             }
            } 
        $data['global'] = $this->common_model->getGlobalSettings();
        $attachement = "No";
        $attachement_path = "";

        $data['email_template'] = $this->newsletter_model->getNewsletterDetailsById($newsletter_id);
        if ($this->input->post('newsletter_id') != "") {
            if ($_FILES['attachement']['name'] == "") {
                $attachement = "0";
                $attachement_path = "";
            } else {
                $attachement = "1";
                $config['upload_path'] = './media/backend/img/newsletter_attachment/';
                $config['allowed_types'] = '*';
                $config['max_size'] = '9000000000';
                $config['file_name'] = time();
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('attachement')) {
                    $this->upload->display_errors();
                    $error = array('error' => $this->upload->display_errors());
                    $msg_data = array('msg_type' => 'error', 'newsletter_msg_val' => $this->upload->display_errors());
                    $this->session->set_userdata('newsletter_msg', $msg_data);
                    redirect("backend/newsletter/list");
                    $this->session->set_userdata('image_not_uploaded', 'yes');
                } else {
                    $data = array('upload_data' => $this->upload->data());
                    $image_data = $this->upload->data();
                }
                $attachement_path = $image_data['file_name'];
            }
            $emails = $this->input->post("list_of_users");
            $email_for_newsletter = explode(",", $emails);
            $data['email_template'] = $this->newsletter_model->getNewsletterDetailsById($newsletter_id);
            foreach ($email_for_newsletter as $email) {
                $newsletter_content = $data['email_template'][0]["newsletter_content"];
                $newsletter_subject = $data['email_template'][0]["newsletter_subject"];
                $var_array = array("%%user_email%%" => $email);
                $condition = array("user_email" => $email);
                $user_details = $this->common_model->getRecords("mst_users", $fields = '', $condition, $order_by = '', $limit = '', $debug = 0);
                if (count($email) > 0) {
                    $send_arr = array("user_email" => $email, "user_id" => $user_details[0]['user_id'], "newsletter_id" => $newsletter_id, "date_created" => date('Y-m-d H:i:s'));
                    //$last_insert_id = $this->newsletter_model->insertRow($send_arr, 'trans_newsletter_send');
                    //$send_detail_arr = array("newsletter_send_id" => $last_insert_id, "newsletter_subject" => $newsletter_subject, "newsletter_content" => $newsletter_content, "attachement" => $attachement, "attachement_path" => $attachement_path);
                    $result = $this->emailTemplates($data['email_template'], $var_array, $data, $email, $attachement, $attachement_path);
                }
            }
            $msg_data = array('msg_type' => 'success', 'newsletter_msg_val' => 'Newsletter sent successfully.');
            $this->session->set_userdata('newsletter_msg', $msg_data);
            redirect("backend/newsletter/list");
        }

        $data['logged_username'] = $this->session->userdata('admin_name');
        $data['title'] = 'Send newsletter ';
        $data['newsletter_id'] = $newsletter_id;
        $this->load->view('backend/newsletter/send-newsletter', $data); 
    }

       public function emailTemplates($templateDetails, $var_array, $global, $to_email, $attachement, $attachement_path) {

        $data['global'] = $this->common_model->getGlobalSettings();
        $config['protocol'] = 'mail';
        $config['wordwrap'] = true;
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['crlf'] = "\r\n";
        $config['newline'] = "\r\n";
		
        $this->load->library('email');
        $this->email->initialize($config);
        $this->email->from($data['global']['site_email'], stripslashes($data['global']['site_title']));
        $this->email->to($to_email);
		
	
        $this->email->subject(($templateDetails[0]['newsletter_subject']));
        foreach ($var_array as $k => $v) {
            $templateDetails[0]['newsletter_content'] = str_replace($k, $v, $templateDetails[0]['newsletter_content']);
        }
        $this->email->message($templateDetails[0]['newsletter_content']);
        $absolute_path = $this->common_model->absolutePath();
        
        if ($attachement == "1") {
            $this->email->attach($absolute_path . "/media/backend/img/newsletter_attachment/" . $attachement_path);
        }
        $result = $this->email->send();
        
        $this->email->clear(TRUE);
    } 

    /*
     * function to get all users to send newsletter on selected conditions
     */
    
    function gettingAllUsersByStatus() {
        $user_status = $this->input->post('user_status');
         if ($user_status == '0' || $user_status == '1' || $user_status == '2') {
            $users['user_list'] = $this->newsletter_model->getAllUsersByStatus($user_status);
            $i = 0;
            foreach ($users['user_list'] as $userEmail) {
                if ($i > 0)
                    echo ",";
                $userEmail['user_id'];
                echo $userEmail['user_email'];
                $i++;
            }
        }else {
            if ($user_status == '4') {
                $subscribe_status = 'Active';
                $users['user_list'] = $this->newsletter_model->getAllSubscribersByStatus($subscribe_status);
            } elseif ($user_status == '3') {
                $subscribe_status = 'Inactive';
                $users['user_list'] = $this->newsletter_model->getAllSubscribersByStatus($subscribe_status);
            }
            $i = 0;
            foreach ($users['user_list'] as $userEmail) {
                if ($i > 0)
                    echo ",";
                echo $userEmail['user_email'];
                $i++;
            }
        }
    }
}

