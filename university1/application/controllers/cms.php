<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CMS extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('cms_model');
    }

    function listCMS() {

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
        if(in_array('3',$arr_login_admin_privileges)==FALSE)    
        {
             /*an admin which is not super admin not privileges to access Manage Role
              *setting session for displaying notiication message.*/
             $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
             redirect(base_url() . "backend/home");
             exit();
        }
        }
       
        //using the admin model
        $data['title'] = "Manage CMS";
        $data['get_cms_list'] = $this->cms_model->getCmsList();
      
        $this->load->view('backend/cms/cms-list', $data);
    }

    function editCMS($cms_id = 0) {
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
        if(in_array('3',$arr_login_admin_privileges)==FALSE)    
        {
             /*an admin which is not super admin not privileges to access Manage Role
              *setting session for displaying notiication message.*/
             $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
             redirect(base_url() . "backend/home");
             exit();
        }
        }
        
        if ($this->input->post()) {
            $cms_page_title = addslashes($this->input->post('cms_page_title'));
            $cms_page_content = addslashes($this->input->post('cms_content'));
            $cms_page_meta_keywords = addslashes($this->input->post('cms_page_meta_keywords'));
            $cms_page_meta_description = addslashes($this->input->post('cms_page_meta_description'));
            $cms_page_seo_title = addslashes($this->input->post('cms_page_seo_title'));
            $arr_set_fields = array(
                "page_content" => $cms_page_content,
                "page_title" => $cms_page_title,
                "page_meta_keyword" => $cms_page_meta_keywords,
                "page_meta_description" => $cms_page_meta_description,
                "page_seo_title_lang" => $cms_page_seo_title
            );

            $this->common_model->updateRow('trans_cms', $arr_set_fields, array("cms_id" => $cms_id));

            $this->session->set_userdata("msg", "<span class='success'>Record updated successfully!</span>");

            redirect(base_url() . 'backend/cms');
        }

        //using the admin model
        $data['title'] = "Edit CMS Page";
        $data['arr_cms_details'] = $this->cms_model->getCmsPageDetails($cms_id);
          
        $this->load->view('backend/cms/edit-cms', $data);
    }

    public function editCmsLanguage($edit_id = '') {

        /* checking admin is logged in or not */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }

        /* getting commen data required */
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
        if(in_array('3',$arr_login_admin_privileges)==FALSE)    
        {
             /*an admin which is not super admin not privileges to access Manage Role
              *setting session for displaying notiication message.*/
             $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
             redirect(base_url() . "backend/home");
             exit();
        }
        }  
      
        if (count($this->input->post()) > 0) {

            if ($this->input->post('cms_page_title') != "") {
			 $arr_cms = $this->cms_model->getCMS(intval(($edit_id)), intval($this->input->post('lang_id')));
			
                if (count($arr_cms)>0) {

      
                        $arr_to_update = array(
                            "page_title" => mysql_real_escape_string($this->input->post('cms_page_title')),
                            "page_content" => ($this->input->post('cms_content')),
                            "page_seo_title_lang" => mysql_real_escape_string($this->input->post('cms_page_seo_title')),
                            "page_meta_keyword" => mysql_real_escape_string($this->input->post('cms_page_meta_keywords')),
                            "page_meta_description" => ($this->input->post('cms_page_meta_description'))
                        );

                        /* condition to update record	 */
                        $condition_array = array('lang_id' => intval($this->input->post('lang_id')), 'cms_id' => intval(($this->input->post('edit_id'))));

                        /* updating the cms into database */
                        $this->common_model->updateRow('trans_cms', $arr_to_update, $condition_array);
                    } else {

                        $arr_to_insert = array(
                            "page_title" => mysql_real_escape_string($this->input->post('cms_page_title')),
                            "page_content" => ($this->input->post('cms_content')),
                            "page_seo_title_lang" => mysql_real_escape_string($this->input->post('cms_page_seo_title')),
                            "page_meta_keyword" => mysql_real_escape_string($this->input->post('cms_page_meta_keywords')),
                            "page_meta_description" => mysql_real_escape_string($this->input->post('cms_page_meta_description')),
                            "lang_id" => mysql_real_escape_string($this->input->post('lang_id')),
                            "cms_id" => mysql_real_escape_string($this->input->post('cms_id'))
                        );

                        $this->common_model->insertRow($arr_to_insert, 'trans_cms');
                    }
                    /* setting session for displaying notiication message. */
                    $this->session->set_userdata('msg', "<span class='success'>Record updated successfully!</span>");
                     /* redirecting to cms list */
                    redirect(base_url() . "backend/cms");
                   }
               
               
            
        }

        $data['title'] = "Edit Cms";

        if (($edit_id != '')) {
            $data['edit_id'] = $edit_id;
            $arr_cms = $this->cms_model->getCMS(intval(($edit_id)));
            // single row fix
            $data['arr_cms'] = end($arr_cms);
            /* getting all the active languages from the database */
            $data['arr_languages'] = $this->common_model->getRecords("mst_languages", "", array("status" => 'A', "lang_id !=" => '17'));

            $this->load->view('backend/cms/edit-language-cms', $data);
        } 
    }

    function getCmsLanguage() {
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }


        if ($this->input->post('lang_id') != "") {
            /* getting the global settings using the language id and parameter id. */
            $arr_language_values = $this->cms_model->getCMS(intval($this->input->post('edit_id')), intval($this->input->post('lang_id')));

            $arr_to_return = array();

            if (count($arr_language_values) > 0) {
                $arr_to_return["page_content"] = stripslashes($arr_language_values[0]["page_content"]);
                $arr_to_return["page_title"] = stripslashes($arr_language_values[0]["page_title"]);
                $arr_to_return["page_seo_title_lang"] = stripslashes($arr_language_values[0]["page_seo_title_lang"]);
                $arr_to_return["page_meta_keyword"] = stripslashes($arr_language_values[0]["page_meta_keyword"]);
                $arr_to_return["page_meta_description"] = stripslashes($arr_language_values[0]["page_meta_description"]);
            } else {
                $arr_to_return["page_content"] = "";
                $arr_to_return["page_title"] = "";
                $arr_to_return["page_seo_title_lang"] = "";
                $arr_to_return["page_meta_keyword"] = "";
                $arr_to_return["page_meta_description"] = "";
            }
            /* encodeing the array into json format */
            echo json_encode($arr_to_return);
        }
    }

    public function cmsPage($page_name) {
        $global = $this->common_model->getGlobalSettings();
        $data = $this->common_model->commonFunction();
		$lang_id='17';
		$condition = array('A.page_alias' => $page_name, 'A.status' => 'Published',"B.lang_id"=>$lang_id);
        $data['arr_cms'] = $this->cms_model->getCmsPageDetailsFront($condition);
	
        if (count($data['arr_cms']) == 0)
            redirect(base_url());

        if ($data['arr_cms'][0]['page_title'] == '') {
            $data['header']['title'] = stripslashes(ucwords($global['site_title']));
        } else {
            $data['header']['title'] = ucwords($data['arr_cms'][0]['page_title']);
        }

        $data['site_title'] = $data['arr_cms'][0]['page_title'];
        $data['meta_description'] = $data['arr_cms'][0]['page_meta_description'];
        $data['meta_keywords'] = $data['arr_cms'][0]['page_meta_keyword'];
        $data['meta_title'] = $data['arr_cms'][0]['page_seo_title_lang'];

        $this->load->view('front/includes/header', $data);
        $this->load->view('front/includes/inner-top-nav', $data);
        $this->load->view('front/cms/cms-page', $data);
        $this->load->view('front/includes/footer');
    }

    public function uploadImage() {
       $data = $this->common_model->commonFunction();
         if ($_FILES['upload']['name'] != '') {

             $file_type = explode('/', $_FILES['upload']['type']);
             if ($file_type[0] != 'image') {
                 echo "<script type='text/javascript'>alert('Sorry!!!. 
Please upload image only.');</script>";
                 return false;
             }

             $filename = time() . "." . strtolower(end(explode(".", $_FILES['upload']["name"])));
             move_uploaded_file($_FILES['upload']["tmp_name"],
$data['absolute_path'] . "media/backend/userfiles/" . $filename);
             ?><script
type="text/javascript">window.parent.CKEDITOR.tools.callFunction(1,
"<?php echo base_url(); ?>media/backend/userfiles/<?php echo $filename; ?>", '');</script><?php
         }
    }
    
    public function databaseError() {
        $global = $this->common_model->getGlobalSettings();
        $data = $this->common_model->commonFunction();
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/cms/database-error-page');
        $this->load->view('front/includes/footer');
    }

}