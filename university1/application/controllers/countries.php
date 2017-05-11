<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Countries extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('countries_model');
        $this->load->model('admin_model');
    }

    function countriesList() {
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
            if(in_array('7', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        $data['title'] = "Countries List";
        $data['get_countries_list'] = $this->countries_model->getCountriesList();
        $this->load->view('backend/countries/countries-list', $data);
    }

    function editCountry($country_id = 0) {
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
            if(in_array('7', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        if ($this->input->post('country_name')) {
            $file_name = '';
            $country_name = ($this->input->post('country_name'));
            $country_iso_name = ($this->input->post('country_iso_name'));
            if ($_FILES['country_flag']['name'] != '') {
//                echo '<pre>'; print_r($_FILES);die;
                //config initialise for uploading image 
                $config['upload_path'] = './media/backend/img/country-flag/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '100000000000000';
                $config['max_width'] = '12024';
                $config['max_height'] = '7268';
                $config['file_name'] = rand();
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                //loading uploda library
                if (!$this->upload->do_upload('country_flag')) {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_userdata('msg', $error['error']);
                    redirect(base_url() . "backend/countries/add");
                } else {
                    $data = array('upload_data' => $this->upload->data());
                    $image_data = $this->upload->data();
                    $file_name = $image_data['file_name'];

                    $absolute_path = $this->common_model->absolutePath();
                    $image_path = $absolute_path . "media/backend/img/country-flag/";
                    $image_main = $image_path . "/" . $file_name;

                    $thumbs_image2 = $image_path . "/thumbs/" . $file_name;
                    $str_console2 = "convert " . $image_main . " -resize 40!X30! " . $thumbs_image2;
                    exec($str_console2);
                }
            }

            if ($file_name != '') {
                $flag_path_name = $file_name;
            } else {
                $flag_path_name = $this->input->post('old_flag');
            }

            /* user record to add */
            $arr_to_insert = array(
                "iso" => ($this->input->post('country_iso_name')),
                "flag" => $flag_path_name,
                "country_phone_code" => $this->input->post('country_phone_code')
            );

            $arr_to_insert_trans = array(
                "country_name" => ($country_name),
            );
            $this->common_model->updateRow('mst_countries', $arr_to_insert, array("country_id" => $country_id));
            $this->common_model->updateRow('trans_country_lang', $arr_to_insert_trans, array("country_id_fk" => $country_id, "lang_id" => '17'));
            $this->session->set_userdata("msg", "<span class='success'>Country updated successfully!</span>");
            redirect(base_url() . 'backend/countries');
            exit;
        }

        $data['title'] = "Edit Country";
        $data['arr_country_details'] = $this->countries_model->getCountriesPageDetails($country_id);
        $this->load->view('backend/countries/edit-country', $data);
    }

    /* To check whether country already exists or nor before inserting */

    public function checkCountryName() {
        /* checking admin is logged in or not */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit;
        }
        $data = $this->common_model->commonFunction();
        $country_name = $this->input->post('country_name');
        $old_country_name = $this->input->post('old_country');
        $country_id = $this->input->post('country_id');
        $data['get_countries_list'] = $this->countries_model->getNamesList($country_name);
        if ($old_country_name != '') {
            if (isset($data['get_countries_list'][0]['country_id']) && ($data['get_countries_list'][0]['country_id'] === $country_id)) {
                echo "true";
            } else {
                if (count($data['get_countries_list']) > 0 && ($data['get_countries_list'][0]['country_name'] != $old_country_name)) {
                    echo "false";
                } else {
                    echo "true";
                }
            }
        } else {
            if (count($data['get_countries_list']) > 0) {
                echo "false";
            } else {
                echo "true";
            }
        }
    }

    /* To check whether country Iso already exists or nor  */

    public function checkCountryIso() {
        /* checking admin is logged in or not */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit;
        }
        $data = $this->common_model->commonFunction();
        $country_iso_name = ltrim($this->input->post('country_iso_name'));
        $old_country_iso_name = $this->input->post('old_country_iso_name');
        $data['get_countries_list'] = $this->countries_model->checkCountryISO($country_iso_name);

        if ($old_country_iso_name != '') {
            if (count($data['get_countries_list']) > 0 && (strtoupper($data['get_countries_list'][0]['iso']) != strtoupper($old_country_iso_name))) {
                echo "false";
            } else {
                echo "true";
            }
        } else {
            if (count($data['get_countries_list']) > 0) {
                echo "false";
            } else {
                echo "true";
            }
        }
    }

    public function checkCountryPhoneCode() {
        /* checking admin is logged in or not */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit;
        }
        $data = $this->common_model->commonFunction();
        $country_phone_code = ltrim($this->input->post('country_phone_code'));
        $old_country_phone_code = $this->input->post('old_country_phone_code');
        $condition_to_pass = array('country_phone_code' => $country_phone_code);
        $data['get_phone_code_list'] = $this->common_model->getRecords('mst_countries', '*', $condition_to_pass);
        if ($old_country_phone_code != '') {
            if (count($data['get_phone_code_list']) > 0 && ($data['get_phone_code_list'][0]['country_phone_code'] != $old_country_phone_code)) {
                echo "false";
            } else {
                echo "true";
            }
        } else {
            if (count($data['get_phone_code_list']) > 0) {
                echo "false";
            } else {
                echo "true";
            }
        }
    }

    /* Here we delete the countries from list */

    public function deleteCountriesList() {

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
            if(in_array('7', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }

        /* getting all ids selected */
        $arr_country_id = $this->input->post('checkbox');

        if (count($arr_country_id) > 0) {


            /* deleting the country selected */
            $this->common_model->deleteRows($arr_country_id, "mst_countries", "country_id");
            $this->common_model->deleteRows($arr_country_id, "mst_cities", "country_id_fk");

            $this->session->set_userdata("msg", "Countries deleted successfully!");
            redirect(base_url() . "backend/countries");
            exit;
        } else {
            $this->session->set_userdata("msg_error", "Sorry, your request can not be fulfilled this time. Please try again later!");
            redirect(base_url() . "backend/countries");
            exit;
        }

        $data['title'] = "Delete Countries";
        $data['get_countries_list'] = $this->countries_model->getCountriesList();
        $this->load->view('backend/countries/countries-list', $data);
    }

    /* Here we add countries */

    public function addCountry() {
        /* checking admin is logged in or not */
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
            if(in_array('7', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="validationError">', '</p>');
        $this->form_validation->set_rules('country_name', 'country name', 'required');
        $this->form_validation->set_rules('country_iso_name', 'country iso name', 'required');
        if ($this->form_validation->run() == true && $this->input->post('country_name') != "") {

            if ($_FILES['country_flag']['name'] != '') {
                //config initialise for uploading image 
                $config['upload_path'] = './media/backend/img/country-flag/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '100000000000000';
                $config['max_width'] = '12024';
                $config['max_height'] = '7268';
                $config['file_name'] = rand();
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                //loading uploda library
                if (!$this->upload->do_upload('country_flag')) {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_userdata('msg', $error['error']);
                    redirect(base_url() . "backend/countries/add");
                } else {
                    $data = array('upload_data' => $this->upload->data());
                    $image_data = $this->upload->data();
                    $file_name = $image_data['file_name'];

                    $absolute_path = $this->common_model->absolutePath();
                    $image_path = $absolute_path . "media/backend/img/country-flag/";
                    $image_main = $image_path . "/" . $file_name;

                    $thumbs_image2 = $image_path . "/thumbs/" . $file_name;
                    $str_console2 = "convert " . $image_main . " -resize 40!X30! " . $thumbs_image2;
                    exec($str_console2);
                }
            }
            /* user record to add */
            $arr_to_insert = array(
                "iso" => ($this->input->post('country_iso_name')),
                "flag" => $file_name,
                "country_phone_code" => $this->input->post('country_phone_code')
            );
            /* inserting admin details into the dabase */
            $last_insert_id = $this->common_model->insertRow($arr_to_insert, "mst_countries");
            $arr_to_insert_trans = array(
                "country_name" => ($this->input->post('country_name')),
                "country_id_fk" => $last_insert_id,
                "lang_id" => 17,
                "status" => '1'
            );
            $this->common_model->insertRow($arr_to_insert_trans, "trans_country_lang");

            $this->session->set_userdata("msg", "<span class='success'>Country added successfully!</span>");
            /* getting mail subect and mail message using email template title and lang_id and reserved works */
            redirect(base_url() . "backend/countries");
            exit;
        }
        /* getting all privileges */
        $data['arr_privileges'] = $this->common_model->getRecords('mst_privileges');
        $data['title'] = "Add Country";
        $data['get_countries_list'] = $this->countries_model->getCountriesList();
        $this->load->view('backend/countries/add', $data);
    }

    //function to change countr in multilanguage


    public function changeLanguage($country_lang_id) {
        $data = $this->common_model->commonFunction();
        $country_lang_id = ($country_lang_id);
        $data['country_lang_id'] = $country_lang_id;
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit;
        }
        //checking for admin privilages
        if ($data['user_account']['role_id'] != 1) {
            $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
            if (count($arr_privileges) > 0) {
                foreach ($arr_privileges as $privilege) {
                    $user_privileges[] = $privilege['privilege_id'];
                }
            }
            $arr_login_admin_privileges = $user_privileges;
            if(in_array('7', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        $lang_id = $this->input->post('lang_id');
        if ($lang_id == '' || $lang_id == 0)
            $lang_id = 17;
        $data['arr_get_language'] = $this->common_model->getAllLanguages();
        $data['arr_country_list'] = $this->countries_model->getAllCountriesByLangAndCountryId($country_lang_id, $lang_id);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="validationError">', '</p>');
        $this->form_validation->set_rules('country_name', 'country name', 'required');
        $this->form_validation->set_rules('lang_id', 'language', 'required');
        if ($this->form_validation->run() == true && $this->input->post("country_name") != "") {
            $table_to_pass = 'trans_country_lang';
            $condition_to_pass = array("lang_id" => $this->input->post("lang_id"), 'country_id_fk' => $this->input->post('country_id_fk'));
            $data['arr_country_names'] = $this->countries_model->getCountryInformation($table_to_pass, $fields_to_pass = '', $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
            if (count($data['arr_country_names']) <= 0) {
                $arr_fields = array("country_id_fk" => $this->input->post('country_id_fk'),
                    "lang_id" => $this->input->post("lang_id"),
                    "status" => $this->input->post("status"),
                    "country_name" => $this->input->post("country_name")
                );
                $insert_data = $this->common_model->insertRow($arr_fields, 'trans_country_lang');
                $this->session->set_userdata("msg", "<span class='success'> Record added successfully!</span>");
                redirect(base_url() . "backend/countries");
                exit;
            } else {
                $update_data = array("status" => $this->input->post("status"),
                    "country_name" => $this->input->post("country_name"),
                    "lang_id" => $this->input->post('lang_id'),
                    "status" => $this->input->post("status"),
                );

                $condition = array(
                    'country_lang_id' => $this->input->post('country_lang_id')
                );
                $this->common_model->updateRow('trans_country_lang', $update_data, $condition);
                $this->session->set_userdata("msg", "<span class='success'> Records updated successfully!</span>");
                redirect(base_url() . "backend/countries");
                exit;
            }
        }
        $data['title'] = 'Update Counrty in multilanguage';
        $this->load->view('backend/countries/change-language', $data);
    }

    /* functio to get all the country names */

    public function getAllCountryNames() {
        $data = $this->common_model->commonFunction();
        $data['arr_country_names'] = array();
        $data['arr_country_list'] = array();
        $lang_id = $this->input->post('lang_id');
        $country_id_fk = $this->input->post('country_id_fk');
        $table_to_pass = 'trans_country_lang';
        $condition_to_pass = array("lang_id" => $lang_id, 'country_id_fk' => $country_id_fk);
        $data['arr_country_names'] = $this->countries_model->getCountryInformation($table_to_pass, $fields_to_pass = '', $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        $data['arr_country_list'] = $this->countries_model->get_all_country($condition_to_pass = '');
        if (count($data['arr_country_names']) > 0) {
            ?>
            <div class="form-group">
                <label for="parametername">Country<sup class="mandatory">*</sup></label>
                <input  class="form-control"  type="text" name="country_name" id="country_name" value="<?php
                if (isset($data['arr_country_names'])) {
                    echo $data['arr_country_names'][0]['country_name'];
                }
                ?>">
                <input type="hidden" class="form-control"  name="country_lang_id" id="country_lang_id" value="<?php
                if (isset($data['arr_country_names'])) {
                    echo $data['arr_country_names'][0]['country_lang_id'];
                }
                ?>">
                <input type="hidden" class="form-control"  name="old_country_name" id="old_country_name" value="<?php
                if (isset($data['arr_country_names'])) {
                    echo $data['arr_country_names'][0]['country_name'];
                }
                ?>">
            </div>
            <div class="form-group">
                <label for="parametername">Status<sup class="mandatory">*</sup></label>
                <select   class="form-control" name="status" id="status">
                    <option value="1" <?php
                    if ($data['arr_country_names'][0]['status'] == "1") {
                        echo "selected=selected";
                    }
                    ?>>Active</option>
                    <option value="0" <?php
                    if ($data['arr_country_names'][0]['status'] == "0") {
                        echo "selected=selected";
                    }
                    ?>>Inactive</option>
                </select>
            </div>

        <?php } else {
            ?>
            <div class="form-group">
                <label for="Country">Country<sup class="mandatory">*</sup></label>

                <input type="text"   class="form-control" name="country_name" id="country_name" value="">

            </div>
            <div class="form-group">
                <label for="Country">Status<sup class="mandatory">*</sup></label>
                <select name="status"   class="form-control" id="status">
                    <option value="1">Active</option>
                    <option value="0" >Inactive</option>
                </select>

            </div>


            <?php
        }
    }

}
