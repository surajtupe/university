<?php   
if (!defined('BASEPATH'))
    exit('No direct script access allowed'); 
class Email_template extends CI_Controller {    
	
    public function __construct() {  
		parent::__construct();
		$this->load->model('common_model');
                $this->load->model('email_template_model');	
    }
    
   /*
    * function to display all the email templates pages 
    */
    public function index() {
           /**checking admin is logged in or not ***/
            if(!$this->common_model->isLoggedIn())
            {
                    redirect(base_url()."backend/login");
            }
           /**using the email template model ***/
            $data=$this->common_model->commonFunction();
            $arr_privileges=array();
            //checking for admin privilages
            if ($data['user_account']['role_id'] != 1) {
             $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
             if (count($arr_privileges) > 0) {
                 foreach ($arr_privileges as $privilege) {
                     $user_privileges[] = $privilege['privilege_id'];
                 }
             }
             $arr_login_admin_privileges = $user_privileges;
             if(in_array('2',$arr_login_admin_privileges)==FALSE)    
             {
                  /*an admin which is not super admin not privileges to access Manage Role
                   *setting session for displaying notiication message.*/
                  $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                  redirect(base_url() . "backend/home");
                  exit();
             }
            } 
           /**getting all email templates from email template table. ***/
            $data['arr_email_templates']=$this->email_template_model->getEmailTemplateDetails();
            $data['title']="Manage Email Templates";
            $this->load->view('backend/email-template/list', $data);	
	
    }
		
   /*
    * function for edi temail template 
    */
    public function editEmailTemplate($email_template_id='')
    {
         /*** checking admin is logged in or not ***/
            if(!$this->common_model->isLoggedIn())
            {
                    redirect(base_url()."backend/login");
            }
           /**using the email template model ***/
            $data=$this->common_model->commonFunction();
             //checking for admin privilages
            if ($data['user_account']['role_id'] != 1) {
             $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
             if (count($arr_privileges) > 0) {
                 foreach ($arr_privileges as $privilege) {
                     $user_privileges[] = $privilege['privilege_id'];
                 }
             }
             $arr_login_admin_privileges = $user_privileges;
             if(in_array('2',$arr_login_admin_privileges)==FALSE)    
             {
                  /*an admin which is not super admin not privileges to access Manage Role
                   *setting session for displaying notiication message.*/
                  $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                  redirect(base_url() . "backend/home");
                  exit();
             }
            } 
            $arr_privileges=array();
          
            if($this->input->post('input_subject')!='')
            {
                   $arr_to_update=array("email_template_subject"=>($this->input->post('input_subject')),"email_template_content"=>($this->input->post('text_content')),"date_updated"=>date("Y-m-d H:i:s"));
                   $email_template_id_to_update=$this->input->post('email_template_hidden_id');
                   $this->email_template_model->updateEmailTemplateDetailsById($email_template_id_to_update,$arr_to_update);
                   $this->session->set_userdata('msg','Email Template details has been updated successfully.');
                   redirect(base_url()."backend/email-template/list");
            }
           /**getting all email templates from email template table ***/
            $data['arr_email_template_details']=$this->email_template_model->getEmailTemplateDetailsById($email_template_id);
            $data['arr_macros']=$this->common_model->getRecords('mst_email_template_macros');
            $data['title']="Edit Email Templates";
            $data['email_template_id']=$email_template_id;
            $this->load->view('backend/email-template/edit-email-template', $data);	
    }
}
