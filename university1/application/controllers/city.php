<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class City extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('city_model');
        $this->load->model('common_model');
    }

    public function city_master($fn_to_call, $param1 = "", $param2 = "") {
        $fn_to_call = str_replace("-", "_", $fn_to_call);
        if (method_exists("City", $fn_to_call)) {
            if ($param1 != "" && $param2 != "") {
                $this->$fn_to_call($param1, $param2);
            } else if ($param1 != "") {
                $this->$fn_to_call($param1);
            } else {
                $this->$fn_to_call();
            }
        }
    }

    /* list category functionlity backend */

    public function listCity() {
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit;
        }

        $arr_category_ids = $this->input->post('checkbox');
        if (is_array($arr_category_ids)) {
            if (count($arr_category_ids) > 0) {
                $this->common_model->deleteRows($arr_category_ids, "trans_cities_lang", "city_id_lang");
                $this->session->set_userdata("msg", "<span class='success'>City deleted successfully!</span>");
            }
        }
        /* call to delete function to delete the table row */
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
            if(in_array('9', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        if (isset($lang_id) && $lang_id != '') {
            $lang_id = $this->session->userdata('$lang_id');
        } else {
            $lang_id = 17; //default is 17(English)
        }
        $data['title'] = 'Manage Cities';
        $condition_to_pass = array("city_lang.lang_id" => '17');

        $data['arr_city_list'] = $this->city_model->get_all_city($condition_to_pass);

        $this->load->view('backend/cities/cities-list', $data);
    }

    /* edit category functionlity backend */

    public function editCity($city_lang_id) {
        $city_lang_id = ($city_lang_id);
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit;
        }
        $data = $this->common_model->commonFunction();

        if ($this->input->post('city_name') != '') {
            $lang_id = $this->input->post('lang_id');

            if ($lang_id == '' || $lang_id == 0)
                $lang_id = 17;

            $city_id = $this->input->post('city_id');

            $update_data = array(
                "country_id_fk" => $this->input->post('country_id'),
                "state_id_fk" => $this->input->post('state_id')
            );
            $update_data1 = array(
                "city_name" => $this->input->post('city_name'),
            );
            $this->city_model->updateCityDetails("mst_cities", $update_data, array("city_id" => $city_id));
            $this->city_model->updateCityDetails("trans_cities_lang", $update_data1, array("city_id_fk" => $city_id, "lang_id" => $lang_id));
            $this->session->set_userdata("msg", "<span class='success'> City updated successfully!</span>");
            redirect(base_url() . "backend/cities");
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
            if(in_array('9', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        $data['title'] = "Edit City";
        $data['arr_city_details'] = $this->city_model->getCitiesPageDetails($city_lang_id);
        $data['arr_county_details'] = $this->city_model->getCountriesPageNames();
        $data['arr_state_details'] = array();
        if ($data['arr_city_details'][0]['country_id_fk'] != '') {
            $condition = array('state.country' => $data['arr_city_details'][0]['country_id_fk'], 'state_lang.lang_id' => '17');
            $data['arr_state_details'] = $this->city_model->getStateDetailsByCountry($condition);
        }

        $data['city_id'] = $city_lang_id;
        $this->load->view('backend/cities/edit-cities', $data);
    }

    /* add category functionlity backend */

    public function addCity() {

        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit;
        }
        $data = $this->common_model->commonFunction();

        if ($this->input->post('city_name') != '') {
            $city_name = $this->input->post('city_name');
            $country_id_fk = $this->input->post('country_id');
            $state_id_fk = $this->input->post('state_id');
            $lang_id = 17;

            if (count($city_name) > 0) {
                $insert = array(
                    "city_id" => '',
                    "country_id_fk" => $country_id_fk,
                    "state_id_fk" => $state_id_fk
                );
                $last_insert_id = $this->city_model->insertCityId($insert);
                $insert_data = array(
                    "city_name" => addslashes($city_name),
                    "city_id_fk" => $last_insert_id,
                    "lang_id" => $lang_id
                );
                $this->city_model->insertCity($insert_data);
                $this->session->set_userdata("msg", "<span class='success'> City added successfully!</span>");
                redirect(base_url() . "backend/cities");
                exit;
            }
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
            if(in_array('9', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        $data['arr_country_details'] = $this->city_model->getCountriesPageNames();
        $data['title'] = 'Add city';
        $this->load->view('backend/cities/add', $data);
    }

    public function changeLanguage($city_lang_id) {
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
            if(in_array('9', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        $city_lang_id = ($city_lang_id);
        $data['city_id_lang'] = $city_lang_id;
        if (!$this->common_model->isLoggedIn()) {

            redirect(base_url() . "backend/login");
            exit;
        }
        $data['arr_get_language'] = $this->city_model->getAllLanguages();
        $data['arr_city_list'] = $this->city_model->get_all_city_by($city_lang_id);

        if ($this->input->post("city_name") != "") {
            if ($this->input->post("lang_id") != '' && $this->input->post("city_lang_id") == ' ') {
                $arr_fields = array(
                    "city_id_fk" => $this->input->post('city_id_fk'),
                    "lang_id" => $this->input->post("lang_id"),
                    "city_name" => $this->input->post("city_name")
                );
                $insert_data = $this->city_model->insertCity($arr_fields);
                $this->session->set_userdata("msg", "<span class='success'> City added successfully!</span>");
                redirect(base_url() . "backend/cities");
                exit;
            } else {
                $update_data = array(
                    "city_name" => $this->input->post("city_name"));
                $condition = array(
                    'city_id_lang' => $this->input->post('city_id_lang')
                );
                $this->common_model->updateRow('trans_cities_lang', $update_data, $condition);
                $this->session->set_userdata("msg", "<span class='success'> City updated successfully!</span>");
                redirect(base_url() . "backend/cities");
                exit;
            }
        }
        $data['title'] = 'change City in multilanguage';
        $this->load->view('backend/cities/change-language', $data);
    }

    public function getCityName() {
        $data['arr_city_names'] = array();
        $data['arr_city_list'] = array();
        $lang_id = $this->input->post('lang_id');
        $city_id_fk = $this->input->post('city_id_fk');
        $table_to_pass = 'trans_city_lang';
        $condition_to_pass = array("lang_id" => $lang_id, 'city_id_fk' => $city_id_fk);
        $data['arr_city_names'] = $this->city_model->getCityInformation($table_to_pass, $fields_to_pass = '', $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        $data['arr_city_list'] = $this->city_model->get_all_city($condition_to_pass = '');
        echo $data['arr_city_names'][0]['city_name'];
        exit;
    }

    public function getAllCityNames() {
        $data['arr_city_names'] = array();
        $data['arr_city_list'] = array();
        $lang_id = $this->input->post('lang_id');
        $city_id_fk = $this->input->post('city_id_fk');
        $table_to_pass = 'trans_city_lang';
        $condition_to_pass = array("lang_id" => $lang_id, 'city_id_fk' => $city_id_fk);
        $data['arr_city_names'] = $this->city_model->getCityInformation($table_to_pass, $fields_to_pass = '', $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
        $data['arr_city_list'] = $this->city_model->get_all_city($condition_to_pass = '');

        if (count($data['arr_city_names']) > 0) {
            ?>
            <div class="form-group">
                <label for="parametername">City<sup class="mandatory">*</sup></label>
                <input type="text" name="city_name" class="form-control" id="city_name" value="<?php
                if (isset($data['arr_city_names'])) {
                    echo $data['arr_city_names'][0]['city_name'];
                }
                ?>">
                <input type="hidden" name="city_id_lang" id="city_id_lang" value="<?php
                if (isset($data['arr_city_names'])) {
                    echo $data['arr_city_names'][0]['city_id_lang'];
                }
                ?>">
            </div>
        <?php } else {
            ?>
            <div class="form-group">
                <label for="parametername">City<sup class="mandatory">*</sup></label>

                <input type="text" class="form-control" name="city_name" id="city_name" value="">
                <input type="hidden" name="city_lang_id" id="city_lang_id" value=" ">
            </div>
            <?php
        }
    }

    /* function to check existancs of city name */

    public function checkCityName() {
        $country = $this->input->post('country_id');
        $arr_city_detail = array();
        $state = $this->input->post('state');
        if ($this->input->post('type') != "") {
            if (strtolower($this->input->post('city_name')) == strtolower($this->input->post('old_city_name'))) {
                echo "true";
            } else {
                if ($country != '' && $state != '') {
                    $arr_city_detail = $this->city_model->checkforCityExists(array("city_name" => mysql_real_escape_string($this->input->post('city_name')), "country_id_fk" => mysql_real_escape_string($this->input->post('country_id')), "state_id_fk" => mysql_real_escape_string($this->input->post('state'))));
                } else {
                    $arr_city_detail = $this->city_model->checkforCityExists(array("city_name" => mysql_real_escape_string($this->input->post('city_name'))));
                }
                if (count($arr_city_detail) == 0) {
                    echo "true";
                } else {
                    echo "false";
                }
            }
        } else {

            if ($country != '' && $state != '') {
                $arr_city_detail = $this->city_model->checkforCityExists(array("city_name" => mysql_real_escape_string($this->input->post('city_name')), "country_id_fk" => mysql_real_escape_string($this->input->post('country_id')), "state_id_fk" => mysql_real_escape_string($this->input->post('state'))));
            } else {
                $arr_city_detail = $this->city_model->checkforCityExists(array("city_name" => mysql_real_escape_string($this->input->post('city_name'))));
            }

            if (count($arr_city_detail) == 0) {
                echo "true";
            } else {
                echo "false";
            }
        }
    }

    public function checkCityLanguage() {
        if ($this->input->post('type') != "") {

            if (($this->input->post('lang_id') == $this->input->post('old_lang_id'))) {
                echo "true";
            } else {
                $arr_city_detail = $this->common_model->getRecords('trans_city_lang', 'lang_id', array("lang_id" => $this->input->post('lang_id')));
                if (count($arr_city_detail) == 0) {
                    echo "true";
                } else {
                    echo "false";
                }
            }
        } else {

            $arr_city_detail = $this->common_model->getRecords('trans_city_lang', 'lang_id', array("lang_id" => $this->input->post('lang_id')));
            if (count($arr_city_detail) == 0) {
                echo "true";
            } else {
                echo "false";
            }
        }
    }

    /* delete category function */

    public function deleteCity() {
        /* Getting Common data */

        $data = $this->common_model->commonFunction();

        if ($this->input->post('city_id') != '') {
            /* getting all ids selected */
            $arr_city_id = $this->input->post('city_id');
            if (count($arr_city_id) > 0) {
                $this->common_model->deleteRows($arr_city_id, "trans_cities_lang", "city_id_lang");
                $this->session->set_userdata("msg", "City deleted successfully!");
                echo json_encode(array("error" => "0", "error_message" => ""));
                die;
            } else {
                echo json_encode(array("error" => "1", "error_message" => "Sorry, your request can not be fulfilled this time. Please try again later"));
                die;
            }
        }
    }

    public function getAllStateInfo() {

        $county_id = $this->input->post('country_id');
        $table_to_pass = 'mst_states';
        $fields_to_pass = array('id', 'state_name', 'country');
        $condition_to_pass = array("country" => $county_id, "lang_id" => '17');
        $data['arr_country_data'] = $this->city_model->getStateDetailsByCountry($condition_to_pass);
        if (count($data['arr_country_data']) > 0) {
            ?>
            <select class="form-control" id="state_id" name="state_id"  onchange="applyRemoteStateNameRule();">
                <option value="">--Select State--</option>
                <?php
                for ($i = 0; $i < count($data['arr_country_data']); $i++) {
                    ?>
                <option value="<?php echo $data['arr_country_data'][$i]['id']; ?>"><?php echo stripslashes($data['arr_country_data'][$i]['state_name']); ?></option>
                <?php }
                ?>
            </select>
        <?php } else { ?>
            <select class="form-control" id="state_id" name="state_id" >
                <option value="">--Select State--</option>
            </select>
            <?php
        }
    }

    public function getStateInfoFront() {

        $county_id = $this->input->post('country_id');
        $permanant_add = $this->input->post('permanant_add');
        $office_add = $this->input->post('office_add');
        $table_to_pass = 'mst_states';
        $fields_to_pass = array('id', 'state_name', 'country');
        $condition_to_pass = array("country" => $county_id, "lang_id" => '17');
        $data['arr_country_data'] = $this->city_model->getStateDetailsByCountry($condition_to_pass);
        if (count($data['arr_country_data']) > 0) {
            if ($permanant_add != '' && $permanant_add == 'Yes') {
                ?>
                <select class="form-control" id="permanant_add_state" name="permanant_add_state"  onchange="permanantAddCityInfo(this.value)">
                    <?php
                } else if ($office_add != '' && $office_add == 'Yes') {
                    ?>
                    <select class="form-control" id="office_add_state" name="office_add_state"  onchange="officeAddCityInfo(this.value)">
                    <?php } else {
                        ?>
                        <select class="form-control" id="current_add_state" name="current_add_state"  onchange="currentAddCityInfo(this.value)">
                        <?php } ?>
                        <option value=""> SELECT STATE </option>
                        <?php
                        for ($i = 0; $i < count($data['arr_country_data']); $i++) {
                            ?>
                        <option value="<?php echo $data['arr_country_data'][$i]['id']; ?>"><?php echo stripslashes($data['arr_country_data'][$i]['state_name']); ?></option>
                        <?php }
                        ?>
                    </select>
                <?php } else { ?>
                    <select class="form-control" id="current_add_state" name="current_add_state" >
                        <option value=""> SELECT STATE </option>
                    </select>
                    <?php
                }
            }

            public function getCityInfoFront() {
                $state_id = $this->input->post('state_id');
                $permanant_add = $this->input->post('permanant_add');
                $office_add = $this->input->post('office_add');
                $condition_to_pass = array("A.state_id_fk" => $state_id, "lang_id" => '17');
                $data['arr_city_data'] = $this->city_model->getCitiesByState($condition_to_pass);
                if (count($data['arr_city_data']) > 0) {

                    if ($permanant_add == 'Yes' && $permanant_add != '') {
                        ?>
                        <select class="form-control" id="permanant_add_city" name="permanant_add_city">
                        <?php } else if ($office_add == 'Yes' && $office_add != '') {
                            ?>
                            <select class="form-control" id="office_add_city" name="office_add_city">
                                <?php
                            } else {
                                ?>
                                <select class="form-control" id="current_add_city" name="current_add_city">
                                <?php } ?>
                                <option value=""> SELECT CITY </option>
                                <?php
                                for ($i = 0; $i < count($data['arr_city_data']); $i++) {
                                    ?>
                                <option value="<?php echo $data['arr_city_data'][$i]['city_id']; ?>"><?php echo stripslashes($data['arr_city_data'][$i]['city_name']); ?></option>
                                <?php }
                                ?>
                            </select>
                        <?php } else { ?>
                            <select class="form-control" id="current_add_city" name="current_add_city" >
                                <option value=""> SELECT CITY </option>
                            </select>
                            <?php
                        }
                    }

                }
                