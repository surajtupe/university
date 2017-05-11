<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class FAQS extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('faq_model');
    }

    public function index($pg = 0) {
        $data = $this->common_model->commonFunction();
        $condition = array('f.status' => 'Active');
        $faq_question_details = $this->faq_model->getFAQS('question,answer', $condition, 'faq_id Desc');

        $this->load->library('pagination');
        $data['count'] = count($faq_question_details);
        $config['base_url'] = base_url() . 'faqs/';
        $config['total_rows'] = count($faq_question_details);
        $config['total_rows'];
        $config['per_page'] = 10;
        $config['cur_page'] = $pg;
        $data['cur_page'] = $pg;
        $config['num_links'] = 2;
        $config['full_tag_open'] = ' <p class="paginationPara">';
        $config['full_tag_close'] = '</p>';
        $this->pagination->initialize($config);
        $data['create_links'] = $this->pagination->create_links();
        $data['faq_question_details'] = $this->faq_model->getFAQS('question,answer', $condition, 'faq_id Desc', $config['per_page'], $pg);
        $data['page'] = $pg;

        $data['site_title'] = "Frequently asked questions";
        $this->load->view('front/includes/header', $data);
        $this->load->view('front/includes/inner-top-nav', $data);
        $this->load->view('front/faq/faq', $data);
        $this->load->view('front/includes/footer', $data);
    }

    public function searchFaq() {
        $search_keyword = $this->input->post('search_keyword');
        $condition = "(f.status = 'Active') AND (question LIKE '%" . $search_keyword . "%')";
        $faq_question_details = $this->faq_model->getFAQS('', $condition);
        echo json_encode($faq_question_details);
        die;
    }

    public function getFaqCategories() {

        /* checking admin is logged in or not */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit;
        }
        /* Getting Common data */
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
            if (in_array('5', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        $data['title'] = "Manage FAQ Categories";
        /* getting all the FAQ categories */
        $order_by_to_pass = "category_id DESC";
        $data['arr_faq_categories'] = $this->faq_model->getCategories('', '', $order_by_to_pass);
        $this->load->view('backend/faq/faq-categories', $data);
    }

    public function addFaqCategories($edit_id = '') {
        /* checking admin is logged in or not */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit;
        }
        if (count($this->input->post('edit_id')) > 0) {
            if ($this->input->post('input_name') != "") {
                if ($this->input->post('edit_id') != '') {
                    $arr_to_update = array("title" => $this->input->post('input_name'));
                    /* condition to update record */
                    $condition_array = array('category_id' => ($this->input->post('edit_id')));
                    /* updating the value into database */
                    $this->common_model->updateRow('mst_faq_categories', $arr_to_update, $condition_array);
                    /* setting session for displaying notification message. */
                    $this->session->set_userdata('msg', "<span class='success'>Category updated successfully!</span>");
                } else {
                    /* inserting the new faq category */
                    $arr_to_insert = array("title" => $this->input->post('input_name'), 'created_on' => date('Y-m-d H:i:s'));
                    $this->common_model->insertRow($arr_to_insert, "mst_faq_categories");
                    $this->session->set_userdata("msg", "<span class='success'>Category added successfully!</span>");
                }
                redirect(base_url() . "backend/faqs/categories");
                exit;
            }
        }
        /* Getting Common data */
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
            if (in_array('5', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }

        $data['edit_id'] = $edit_id;
        if ($edit_id != '') {
            $data['arr_category'] = $this->faq_model->getCategories("*", "category_id ='" . intval($edit_id) . "'");
            // single row fix
            $data['arr_category'] = end($data['arr_category']);
        }
        if ($edit_id != '') {
            $data['title'] = "Update FAQ Category";
        } else {
            $data['title'] = "Add FAQ Category";
        }
        $this->load->view('backend/faq/add-category', $data);
    }

    /* funcation to check category already exists */

    public function checkDuplicateCategoryName() {
        if ($this->input->post("action") != "Update") {
            $condition = "title ='" . $this->input->post("input_name") . "'";
        } else {
            /* checking duplicate category for edit category */
            $condition = "title ='" . $this->input->post("input_name") . "' and category_id !='" . $this->input->post('edit_id') . "'";
        }

        $arr_faq_categories = $this->faq_model->getCategories("category_id", $condition);
        if (count($arr_faq_categories) > 0) {
            echo "false";
        } else {
            echo "true";
        }
    }

    public function deleteCategory() {
        /* deleteing the single category */
        $category_id = $this->input->post('category_id');
        $category_ids = $this->input->post('category_ids');
        if ($category_id != "") {
            $this->common_model->deleteRows($category_id, "mst_faq_categories", "category_id");
            echo json_encode(array("error" => "0", "errorMessage" => ""));
        } elseif ($category_ids != "") {
            /* deleting multipler category selected */
            $arrDelete = array();
            foreach ($category_ids as $fcid) {
                array_push($arrDelete, $fcid);
            }
            $this->common_model->deleteRows($arrDelete, "mst_faq_categories", "category_id");
            $this->session->set_userdata("msg", "<span class='success'>Category deleted successfully!</span>");
            echo json_encode(array("error" => "0", "errorMessage" => ""));
        } else {
            /* returning error if any */
            echo json_encode(array("error" => "1", "errorMessage" => "Sorry, your request can not be fulfilled this time. Please try again later"));
        }
    }

    public function listFAQS() {
        /* checking admin is logged in or not */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit;
        }
        /* Getting Common data */
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
            if (in_array('5', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }

        $data['title'] = "Manage FAQs";
        /* getting all the FAQ categories */
        $order_by_to_pass = "faq_id DESC";
        $data['arr_faqs'] = $this->faq_model->getFAQS('', '', $order_by_to_pass);

        $this->load->view('backend/faq/list', $data);
    }

    public function addFAQ($edit_id = '') {
        /* checking admin is logged in or not */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit;
        }
        $lang_id = '17';
        $data['arr_faq'] = array();
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="validationError">', '</p>');
        $this->form_validation->set_rules('input_question', 'question', 'required');
        $this->form_validation->set_rules('input_answer', 'answer', 'required');
        $this->form_validation->set_rules('lang_id', 'language', 'required');
        if ($this->form_validation->run() == true && $this->input->post('input_question') != "") {

            if ($this->input->post('lang_id') == '')
                $lang_id = '17';
            else {
                $lang_id = $this->input->post('lang_id');
            }
            if ($this->input->post('edit_id') != '') {
                $arr_to_update = array(
                    "lang_id" => $lang_id,
                    "question" => $this->input->post('input_question'),
                    'answer' => $this->input->post('input_answer'),
                    'search_tags' => $this->input->post('search_tags'),
                    'category_id' => '0');
                /* condition to update record */
                $condition_array = array(
                    'faq_id' => intval(base64_decode($this->input->post('edit_id')))
                );
                /* updating the role name value into database */
                $this->common_model->updateRow('mst_faqs', $arr_to_update, $condition_array);
                $this->session->set_userdata('msg', "<span class='success'>FAQ updated successfully!</span>");
            } else {
                // insert new record
                $arr_to_insert = array(
                    "lang_id" => $lang_id,
                    "question" => $this->input->post('input_question'),
                    'answer' => $this->input->post('input_answer'),
                    'search_tags' => $this->input->post('search_tags'),
                    'category_id' => '0',
                    'created_on' => date("Y-m-d h:i:s"),
                    "status" => "Active");
                $last_insert_id = $this->common_model->insertRow($arr_to_insert, "mst_faqs");
                $this->session->set_userdata("msg", "<span class='success'>FAQ added successfully!</span>");
            }
            redirect(base_url() . "backend/faqs/list");
            exit;
        }

        /* Getting Common data */
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
            if (in_array('5', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        if ($edit_id != '') {
            $data['edit_id'] = ($edit_id);
            $data['arr_faq'] = $this->faq_model->getFAQS("*", "faq_id ='" . intval(base64_decode($edit_id)) . "'");
            // single row fix
            $data['arr_faq'] = end($data['arr_faq']);
        }
        if ($edit_id != '') {
            $data['title'] = "Update FAQ";
        } else {
            $data['title'] = "Add FAQ";
        }

        $data['arr_faq_categories'] = $this->faq_model->getCategories();
        $data['arr_get_language'] = $this->common_model->getLanguages();
        if (($this->input->post('edit_id') != '')) {
            redirect(base_url() . "backend/faqs/add/" . $this->input->post('edit_id'));
        } else {
            $this->load->view('backend/faq/add', $data);
        }
    }

    public function deleteFAQ() {
        /* deleteing the single faq */
        $faq_id = $this->input->post('faq_id');
        $faq_ids = $this->input->post('faq_ids');
        if ($faq_id != "") {
            $this->common_model->deleteRows($faq_id, "mst_faqs", "faq_id");
            echo json_encode(array("error" => "0", "errorMessage" => ""));
        } elseif ($this->input->post('faq_ids') != "") {
            /* deleting multipler faq selected */
            $arrDelete = array();
            foreach ($faq_ids as $fid) {
                array_push($arrDelete, $fid);
            }
            $this->common_model->deleteRows($arrDelete, "mst_faqs", "faq_id");
            $this->session->set_userdata("msg", "<span class='success'>FAQ deleted successfully!</span>");
            echo json_encode(array("error" => "0", "errorMessage" => ""));
        } else {
            /* returning error if any */
            echo json_encode(array("error" => "1", "errorMessage" => "Sorry, your request can not be fulfilled this time. Please try again later"));
        }
    }

    public function changeStatus() {
        $faq_id = $this->input->post('faq_id');
        if ($faq_id != '') {
            /* changing status of faq */
            $arr_to_update = array("status" => $this->input->post('status'));
            $this->common_model->updateRow('mst_faqs', $arr_to_update, array('faq_id' => $faq_id));
            echo json_encode(array("error" => "0", "error_message" => ""));
        } else {
            /* returning error if any */
            echo json_encode(array("error" => "1", "error_message" => "Sorry, your request can not be fulfilled this time. Please try again later"));
        }
    }

}

/* End of file faqs.php */
/* Location: ./application/controllers/faqs.php */
