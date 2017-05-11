<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class States extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('state_model');
        $this->load->model('admin_model');
    }

    public function checkStatesName() {
        /*  checking admin is logged in or not */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit;
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
            if(in_array('8', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        $name = $this->input->post('state_name');
        $old_state_name = $this->input->post('old_state');
        $data['get_state_list'] = $this->state_model->getNamesList($name);
        if ($old_state_name != '') {
            if (count($data['get_state_list']) > 0 && ($data['get_state_list'][0]['state_name'] != $old_state_name)) {
                echo "false";
            } else {
                echo "true";
            }
        } else {
            if (count($data['get_state_list']) > 0) {
                echo "false";
            } else {
                echo "true";
            }
        }
    }

    /* Here we get the list */

    function statesList() {
        /*  checking admin is logged in or not */
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
            if(in_array('8', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        $data['title'] = "Manage States";
        $data['get_state_list'] = $this->state_model->getStatesList();
        $this->load->view('backend/states/states-list', $data);
    }

    function editStates($state_id = 0) {
        /*  checking admin is logged in or not */
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
            if(in_array('8', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        if ($this->input->post('state_name')) {

            $name = addslashes($this->input->post('state_name'));
            $arr_set_fields = array(
                "country" => mysql_real_escape_string($this->input->post('country_id')),
            );
            $arr_set_fields1 = array(
                "state_name" => $name,
            );
            $lang_id = 17;
            $this->common_model->updateRow('mst_states', $arr_set_fields, array("id" => $state_id));
            $this->common_model->updateRow('trans_states_lang', $arr_set_fields1, array("state_id_fk" => $state_id, "lang_id" => $lang_id));
            $this->session->set_userdata("msg", "<span class='success'>State updated successfully!</span>");
            redirect(base_url() . 'backend/states');
            exit;
        }
        $data['title'] = "Edit State";
        $data['arr_state_details'] = $this->state_model->getStatePageDetails($state_id);
        $data['arr_country_details'] = $this->state_model->getCountriesPageNames();

        $this->load->view('backend/states/edit-states', $data);
    }

    public function deleteStatesList() {

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
            if(in_array('8', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        /* getting all ids selected */
        $arr_state_id = $this->input->post('checkbox');
        if (count($arr_state_id) > 0) {
            $this->common_model->deleteRows($arr_state_id, "mst_states", "id");
            $this->common_model->deleteRows($arr_state_id, "trans_states_lang", "state_id_fk");
            $this->session->set_userdata("msg", "Records deleted successfully!");
            redirect(base_url() . "backend/states");
            exit;
            $this->session->set_userdata("msg", "<span class='success'>Records deleted successfully!</span>");
            echo json_encode(array("error" => "0", "error_message" => ""));
        } else {
            $this->session->set_userdata("msg_error", "Sorry, your request can not be fulfilled this time. Please try again later!");
            redirect(base_url() . "backend/states");
            exit;
        }
    }

    public function addState() {
        /*         * *
         *  checking admin is logged in or not
         * */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit;
        }

        /* getting common data */
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
            if(in_array('8', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        if ($this->input->post('state_name') != "") {
            if ($this->input->post('lang_id') == '') {
                $lang_id = '17';
            } else {
                $this->input->post('lang_id');
            }
            /* state record to add */
            $arr_to_insert = array(
                "country" => mysql_real_escape_string($this->input->post('country_id')),
            );
            /* inserting state details into the dabase */
            $last_insert_id = $this->common_model->insertRow($arr_to_insert, "mst_states");
            $arr_to_insert_trans = array(
                "state_name" => mysql_real_escape_string($this->input->post('state_name')),
                "state_id_fk" => $last_insert_id,
                "lang_id" => $lang_id
            );
            $this->common_model->insertRow($arr_to_insert_trans, "trans_states_lang");
            $this->session->set_userdata("msg", "<span class='success'>State added successfully!</span>");
            redirect(base_url() . "backend/states");
            exit;
        }
        $data['arr_country_details'] = $this->state_model->getCountriesPageNames();
        $data['title'] = "Add State";
        $data['get_state_list'] = $this->state_model->getStatesList();
        $this->load->view('backend/states/add', $data);
    }

    public function changeLanguage($state_lang_id) {
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
            if(in_array('8', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        $state_lang_id = ($state_lang_id);
        $data['state_lang_id'] = $state_lang_id;
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit;
        }
        $lang_id = $this->input->post('lang_id');
        if ($lang_id == '' || $lang_id == 0)
            $lang_id = 17;

        $data['arr_get_language'] = $this->common_model->getAllLanguages();
        $data['arr_state_list'] = $this->state_model->getAllStatesByStateIdAndLang($state_lang_id, $lang_id);


        if ($this->input->post("state_name") != "") {
            $lang_id = $this->input->post('lang_id');
            if ($lang_id == '' || $lang_id == 0)
                $lang_id = 17;

            $data['arr_state_list'] = $this->state_model->getAllStatesByStateIdAndLang($this->input->post('state_id_fk'), $lang_id);

            if ((count($data['arr_state_list']) > 0) && ($this->input->post("lang_id") == $data['arr_state_list'][0]['lang_id'])) {

                $update_data = array(
                    "state_name" => $this->input->post("state_name"));
                $condition = array(
                    'state_lang_id' => $data['arr_state_list'][0]['state_lang_id']
                );
                $this->common_model->updateRow('trans_states_lang', $update_data, $condition);
                $this->session->set_userdata("msg", "<span class='success'> State updated successfully!</span>");
                redirect(base_url() . "backend/states");
                exit;
            } else {

                $arr_fields = array("state_id_fk" => $this->input->post('state_id_fk'),
                    "lang_id" => $this->input->post("lang_id"),
                    "state_name" => $this->input->post("state_name")
                );
                $this->common_model->insertRow($arr_fields, 'trans_states_lang');
                $this->session->set_userdata("msg", "<span class='success'> State added successfully!</span>");
                redirect(base_url() . "backend/states");
                exit;
            }
        }
        $data['title'] = 'Change State Infor In multilanguage';
        $this->load->view('backend/states/change-language', $data);
    }

    public function getAllStateNames() {
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
            if(in_array('8', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        $data['arr_state_names'] = array();
        $data['arr_state_list'] = array();
        $lang_id = $this->input->post('lang_id');
        $state_id_fk = $this->input->post('state_id_fk');
        $table_to_pass = 'trans_states_lang';
        $condition_to_pass = array("lang_id" => $lang_id, 'state_id_fk' => $state_id_fk);
        $data['arr_state_names'] = $this->state_model->getStateInformation($table_to_pass, $fields_to_pass = '', $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        $data['arr_state_list'] = $this->state_model->get_all_states($condition_to_pass = '');
        if (count($data['arr_state_names']) > 0) {
            ?>
            <div class="form-group">
                <label for="State">State<sup class="mandatory">*</sup></label>
                <input type="text"  class="form-control"  name="state_name" id="state_name" value="<?php
            if (isset($data['arr_state_names'])) {
                echo stripslashes($data['arr_state_names'][0]['state_name']);
            }
            ?>">
                <input type="hidden" name="old_state" id="old_state" value="<?php
            if (isset($data['arr_state_names'])) {
                echo $data['arr_state_names'][0]['state_name'];
            }
            ?>">
                <input type="hidden" name="state_lang_id" id="state_lang_id" value="<?php
            if (isset($data['arr_state_names'])) {
                echo $data['arr_state_names'][0]['state_lang_id'];
            }
            ?>">

            </div>

            <?php } else {
                ?>
            <div class="form-group">
                <label for="State">State<sup class="mandatory">*</sup></label>
                <input type="text"  class="form-control" name="state_name" id="state_name" value="">
                <input type="hidden" name="state_lang_id" id="state_lang_id" value=" ">
            </div>
            <?php
        }
    }

}