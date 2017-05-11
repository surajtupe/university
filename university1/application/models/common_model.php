<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common_Model extends CI_Model {
    /* function to get global setttings from the database */

    public function getGlobalSettings($lang_id = '') {
        $global = array();
        $this->db->select('mst_global.*,trans_global.*');
        $this->db->from('mst_global_settings as mst_global');
        $this->db->join('trans_global_settings as trans_global', 'mst_global.global_name_id = trans_global.global_name_id', 'left');
        if ($lang_id != '') {
            $this->db->where("trans_global.lang_id", $lang_id); /* for lnag id passed ie. english */
        } else {
            $this->db->where("trans_global.lang_id", 17); /* for default language ie. english */
        }
        $result = $this->db->get();
        foreach ($result->result_array() as $row) {
            $global[$row['name']] = $row['value'];
        }
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'getGlobalSettings',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }
        return $global;
    }

    /* common function to get records from the database table */

    public function getRecords($table, $fields = '', $condition = '', $order_by = '', $limit = '', $debug = 0) {
        $str_sql = '';
        if (is_array($fields)) { /* $fields passed as array */
            $str_sql.=implode(",", $fields);
        } elseif ($fields != "") { /* $fields passed as string */
            $str_sql .= $fields;
        } else {
            $str_sql .= '*';  /* $fields passed blank */
        }
        $this->db->select($str_sql, FALSE);
        if (is_array($condition)) { /* $condition passed as array */
            if (count($condition) > 0) {
                foreach ($condition as $field_name => $field_value) {
                    if ($field_name != '' && $field_value != '') {
                        $this->db->where($field_name, $field_value);
                    }
                }
            }
        } else if ($condition != "") { /* $condition passed as string */
            $this->db->where($condition);
        }
        if ($limit != "") {
            $this->db->limit($limit); /* limit is not blank */
        }
        if (is_array($order_by)) {
            $this->db->order_by($order_by[0], $order_by[1]);  /* $order_by is not blank */
        } else if ($order_by != "") {
            $this->db->order_by($order_by);  /* $order_by is not blank */
        }
        $this->db->from($table);  /* getting record from table name passed */
        $query = $this->db->get();
        if ($debug) {
            die($this->db->last_query());
        }
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'getRecords',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }
        return $query->result_array();
    }

    /* function to get common record for the user	ie. absoulute path, global settings. */

    public function commonFunction() {
        $global = array();
        /* geting global settings from file */
        $lang_id = 17; /* default is 17 for english set lang id from session if global setting required for different language. */
        $file_name = "global-settings-" . $lang_id;
        $resp = file_get_contents($this->absolutePath() . "application/views/backend/global-setting/" . $file_name);
        $data['global'] = unserialize($resp);
        $data['absolute_path'] = $this->absolutePath();
        $data['user_account'] = $this->session->userdata('user_account');
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'commonFunction',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }
        return($data);
    }

    /* function to check user loged in or not. */

    public function isLoggedIn() {
        $user_account = $this->session->userdata('user_account');
        $path_info = explode('/', $_SERVER['REQUEST_URI']);

//        if (isset($path_info[2]) && ($path_info[2] == 'admin' || $path_info[2] == 'backend')) {
        if ($this->uri->segment(1) == 'admin' || $this->uri->segment(1) == 'backend') {

            if ($user_account['user_type'] != '') {

                if ($user_account['user_type'] != 2) {
                    $this->session->set_userdata('login_error', 'It seems you are already logged in with some other user. Please <a href=' . base_url() . 'backend/log-out>Logout</a> first.');
                    redirect(base_url() . "sign-in");
                    exit();
                }
            } else {
                redirect(base_url() . "backend/login");
                exit();
            }
        } else {
            if ($user_account['user_type'] != '') {
                if ($user_account['user_type'] == 2) {
                    $msg = '<strong>Sorry!</strong>"It seems you are already logged in with admin user. Please <a href=' . base_url() . 'logout>Logout</a> first.';
                    $this->session->set_userdata("msg", $msg);
                    redirect(base_url() . "backend/login");
                    exit();
                }
            } else {
                redirect(base_url() . "sign-in");
                exit();
            }
        }

        if (isset($user_account['user_id']) && $user_account['user_id'] != '') {
            //For checking the changed email verification
            $arr_ad_detail = $this->common_model->getRecords("mst_users", "*", array("user_id" => $user_account['user_id']));
            if (count($arr_ad_detail) > 0) {
                if (($arr_ad_detail[0]['user_status'] == 0) && $arr_ad_detail[0]['email_verified'] == 0) {
                    $this->session->unset_userdata("user_account");
                    $msg = '<div class="alert alert-block"><strong>Sorry!</strong> Your account is not activated yet, Please activate it and get log in.</div>';
                    $this->session->set_userdata("msg", $msg);
                    redirect(base_url() . "backend/login");
                    exit();
                }
                /* checking if user into blocked list or not 
                  checking file is exists or not */
                $absolute_path = $this->absolutePath();
                if (file_exists($absolute_path . "media/front/user-status/blocked-user")) {
                    /* getting all blocked user from file */
                    $blocked_user = $this->read_file($absolute_path . "media/front/user-status/blocked-user");
                    if (in_array($user_account['user_id'], $blocked_user)) {
                        /* removing the user from the bloked file list */
                        $key = array_search($user_account['user_id'], $blocked_user);
                        if ($key !== false) {
                            unset($blocked_user[$key]);
                        }
                        $this->write_file($absolute_path . "media/front/user-status/blocked-user", $blocked_user);
                        /* unsetting the session and redirecting to user to login */
                        if ($user_account['user_type'] == '2') {
                            $this->session->unset_userdata("user_account");
                            $msg = '<div class="alert alert-block"><strong>Sorry!</strong> Your account has been blocked by Administrator.</div>';
                            $this->session->set_userdata("msg", $msg);
                            redirect(base_url() . "backend/login");
                            exit();
                        } else {
                            $this->session->unset_userdata("user_account");
                            $this->session->set_userdata('login_error', "Your account has been blocked by administrator.");
                            redirect(base_url() . "sign-in");
                            exit();
                        }
                    }
                }

                /* checking if user into deleted list or not */
                if (file_exists($absolute_path . "media/front/user-status/deleted-user")) {
                    /* getting all blocked user from file */
                    $deleted_user = $this->read_file($absolute_path . "media/front/user-status/deleted-user");
                    if (in_array($user_account['user_id'], $deleted_user)) {
                        /* removing the user from the deleted file list */
                        $key = array_search($user_account['user_id'], $deleted_user);
                        if ($key !== false) {
                            unset($deleted_user[$key]);
                        }
                        $this->write_file($absolute_path . "media/front/user-status/deleted-user", $deleted_user);
                        /* unsetting the session and redirecting to user to login */
                        if ($user_account['user_type'] == '2') {
                            $this->session->unset_userdata("user_account");
                            $msg = '<div class="alert alert-block"><strong>Sorry!</strong> Your account has been deleted by Administrator.</div>';
                            $this->session->set_userdata("msg", $msg);
                            redirect(base_url() . "backend/login");
                            exit();
                        } else {
                            $this->session->unset_userdata("user_account");
                            $this->session->set_userdata('login_error', "Your account has been deleted by administrator.");
                            redirect(base_url() . "sign-in");
                            exit();
                        }
                    }
                }
                $error = $this->db->_error_message();
                $error_number = $this->db->_error_number();
                if ($error) {
                    $controller = $this->router->fetch_class();
                    $method = $this->router->fetch_method();
                    $error_details = array(
                        'error_name' => $error,
                        'error_number' => $error_number,
                        'model_name' => 'common_model',
                        'model_method_name' => 'isLoggedIn',
                        'controller_name' => $controller,
                        'controller_method_name' => $method
                    );
                    $this->common_model->errorSendEmail($error_details);
                    redirect(base_url() . 'page-not-found');
                    exit();
                }
                return true;
            } else {
                if ($user_account['user_type'] == '2') {
                    $this->session->unset_userdata("user_account");
                    $msg = '<div class="alert alert-block"><strong>Sorry!</strong> Your account has been deleted by Administrator.</div>';
                    $this->session->set_userdata("msg", $msg);
                    redirect(base_url() . "backend/login");
                    exit();
                } else {
                    $this->session->unset_userdata("user_account");
                    $this->session->set_userdata('login_error', "Your account has been deleted by administrator.");
                    redirect(base_url() . "sign-in");
                    exit();
                }
            }
        } else {
            $error = $this->db->_error_message();
            $error_number = $this->db->_error_number();
            if ($error) {
                $controller = $this->router->fetch_class();
                $method = $this->router->fetch_method();
                $error_details = array(
                    'error_name' => $error,
                    'error_number' => $error_number,
                    'model_name' => 'common_model',
                    'model_method_name' => 'isLoggedIn',
                    'controller_name' => $controller,
                    'controller_method_name' => $method
                );
                $this->common_model->errorSendEmail($error_details);
                redirect(base_url() . 'page-not-found');
                exit();
            }
            return false;
        }
    }

    /* unction to insert record into the database */

    public function insertRow($insert_data, $table_name) {
        $this->db->insert($table_name, $insert_data);
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'insertRow',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }
        return $this->db->insert_id();
    }

    /* function to update record in the database
     * Modified by Arvind	
     */

    public function updateRow($table_name, $update_data, $condition) {

        if (is_array($condition)) {
            if (count($condition) > 0) {
                foreach ($condition as $field_name => $field_value) {
                    if ($field_name != '' && $field_value != '' && $field_value != NULL) {
                        $this->db->where($field_name, $field_value);
                    }
                }
            }
        } else if ($condition != "" && $condition != NULL) {
            $this->db->where($condition);
        }
        $this->db->update($table_name, $update_data);
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'updateRow',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }
    }

    /* common function to delete rows from the table
     * Modified by Arvind
     */

    public function deleteRows($arr_delete_array, $table_name, $field_name) {
        if (count($arr_delete_array) > 0) {
            foreach ($arr_delete_array as $id) {
                if ($id) {
                    $this->db->where($field_name, $id);
                    $query = $this->db->delete($table_name);
                }
            }
        }

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'deleteRows',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }
    }

    /*
     * function to get absolute path for project
     */

    public function absolutePath($path = '') {
        $abs_path = str_replace('system/', $path, BASEPATH);
        //Add a trailing slash if it doesn't exist.
        $abs_path = preg_replace("#([^/])/*$#", "\\1/", $abs_path);
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'absolutePath',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }
        return $abs_path;
    }

    public function getEmailTemplateInfo($template_title, $lang_id, $reserved_words) {

        // gather information for database table
        $template_data = $this->getRecords('mst_email_templates', '', array("email_template_title" => $template_title, "lang_id" => $lang_id));

        $content = $template_data[0]['email_template_content'];
        $subject = $template_data[0]['email_template_subject'];

        // replace reserved words if any
        foreach ($reserved_words as $k => $v) {
            $content = str_replace($k, $v, $content);
            $subject = str_replace($k, $v, $subject);
        }
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'getEmailTemplateInfo',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }
        return array("subject" => $subject, "content" => $content);
    }

    public function sendEmail($recipeinets, $from, $subject, $message) {
        // ci email helper initialization
        $config['protocol'] = 'smtp';
        $config['wordwrap'] = FALSE;
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['crlf'] = "\r\n";
        $config['newline'] = "\r\n";
        $this->load->library('email', $config);
        $this->email->initialize($config);

        // set the from address
        $this->email->from($from['email'], $from['name']);

        // set the subject
        $this->email->subject($subject);

        // set recipeinets
        $this->email->to($recipeinets);

        // set mail message
        $this->email->message($message);

        // return boolean value for email send
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'sendEmail',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }
        return $this->email->send();
    }

    public function getPageInfoByUrl($uri) {
        $arr_to_return = $this->getRecords(
                "mst_uri_map", "*", array("url" => $uri)
        );
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'getPageInfoByUrl',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }
        return $arr_to_return;
    }

    public function validateUriForExists($arr, $arr_exists) {
        $str_condition = "`url` = '" . $arr['uri'] . "' and `type` = '" . $arr['type'] . "'";

        if (count($arr_exists) > 0) {
            $str_condition.=" and rel_id !='" . $arr_exists['rel_id'] . "'";
        }

        $arr_to_return = $this->getRecords(
                "mst_uri_map", "*", $str_condition
        );
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'validateUriForExists',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }
        return $arr_to_return;
    }

    public function updateURI($arr_fields, $arr_condition) {
        $this->db->update("mst_uri_map", $arr_fields, $arr_condition);
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'updateURI',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }
    }

    public function insertURI($arr_fields) {
        $this->db->insert("mst_uri_map", $arr_fields);
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'insertURI',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }
    }

    public function getNonDefaultLanguages() {
        $arr_to_return = $this->getRecords(
                "mst_languages", "*", array("is_default" => 'N')
        );
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'getNonDefaultLanguages',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }
        return $arr_to_return;
    }

    /* function to writer serialize data to file */

    public function write_file($file_path, $file_data) {
        /* Opening the file for writing. */
        $fp = fopen($file_path, "w+");
        /* wrinting into file */
        fwrite($fp, serialize($file_data));
        /* closing the file for writing. */
        fclose($fp);
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'write_file',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }
    }

    /* function to read file from the specified path */

    public function read_file($file_path) {
        /* reading content for file */
        $file_content = file_get_contents($file_path);
        /* returning the unsearilized array of file data */
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'read_file',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }
        return unserialize($file_content);
    }

    public function errorSendEmail($error_details) {
        // ci email helper initialization
        $config['protocol'] = 'mail';
        $config['wordwrap'] = FALSE;
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['crlf'] = "\r\n";
        $config['newline'] = "\r\n";
        $this->load->library('email', $config);
        $this->email->initialize($config);
        // set the from address
        $data['global'] = $this->getGlobalSettings();
        $from = array(
            'email' => $data['global']['site_email'],
            'name' => $data['global']['site_title']
        );
        $this->email->from($from['email'], $from['name']);

        // set the subject
        $subject = 'Error in model file';
        $this->email->subject($subject);

        // set recipeinets
        $recipeinets = 'joy@panaceatek.com';
        $this->email->to($recipeinets);

        // set mail message
        $message = 'You got an error  <b>' . $error_details['error_name'] .
                '</b> error no - <b>' . $error_details['error_number'] . '</b><br/> Model Name:- <b>' . $error_details['model_name'] . '</b> <br/>  model method is :-<b>' . $error_details['model_method_name'] . '</b><br/> Controller <b>' . $error_details['controller_name'] . '</b>  <br/> Controller method is :<b>' . $error_details['controller_method_name'] . '</b>';


        $this->email->message($message);

        // return boolean value for email send
        return $this->email->send();
    }

    public function getURIInfo($data) {
        //$table, $fields = '', $condition = '', $order_by = '', $limit = '', $debug = 0
        $arr_to_return = $this->getRecords(
                "mst_uri_map", "*", $data, "", "", "");

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'getURIInfo',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }

        return $arr_to_return;
    }

    public function getDefaultLanguageId() {
        $arr_to_return = $this->getRecords("mst_languages", "lang_id", array("is_default" => 'Y'));

        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'getDefaultLanguageId',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }

        return $arr_to_return[0]['lang_id'];
    }

    // function to get seo url from give url
    public function seoUrl($string) {
        $string = trim($string);
        //Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
        $string = strtolower($string);
        //Strip any unwanted characters
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);

        /* if string becomes empty then it will take current time stamp */
        if ($string != "") {
            return $string;
        } else {
            return time();
        }
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'seoUrl',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }
    }

    public function getTestimonial($limit = '', $offset = 0) {
        $field_to_pass = ('t.user_id,t.name,t.testimonial,t.added_date,t.testimonial_img,u.first_name,u.last_name');
        $this->db->select($field_to_pass);
        $this->db->from('mst_testimonial as t');
        $this->db->join('mst_users as u', 'u.user_id = t.user_id', 'left');
        $this->db->where('t.status', 'Active');
        $this->db->order_by('t.testimonial_id DESC');
        if ($limit != '')
            $this->db->limit($limit, $offset);

        $query = $this->db->get();
        $error = $this->db->_error_message();
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'model_method_name' => 'getDefaultLanguageId',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'page-not-found');
            exit();
        }
        return $query->result_array();
    }

    //get all languages

    public function getAllLanguages() {

        $this->db->select('*');
        $this->db->from('mst_languages');
        $this->db->where('lang_id <>', '17');
        $this->db->where('status', 'A');

        $query = $this->db->get();
        // $query = $this->db->get_where('mst_category', $arr);
        /*         * * this is to print error message ** */
        $error = $this->db->_error_message();

        /*         * * this is to print number ** */
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'category_model',
                'method_name' => 'getAllLanguages',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
            exit();
        }
        return $query->result_array();
    }

    public function getLanguages() {

        $this->db->select('*');
        $this->db->from('mst_languages');
//        $this->db->where('lang_id <>', '17');
        $this->db->where('status', 'A');

        $query = $this->db->get();
        // $query = $this->db->get_where('mst_category', $arr);
        /*         * * this is to print error message ** */
        $error = $this->db->_error_message();

        /*         * * this is to print number ** */
        $error_number = $this->db->_error_number();
        if ($error) {
            $controller = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $error_details = array(
                'error_name' => $error,
                'error_number' => $error_number,
                'model_name' => 'common_model',
                'method_name' => 'getLanguages',
                'controller_name' => $controller,
                'controller_method_name' => $method
            );
            $this->common_model->errorSendEmail($error_details);
            redirect(base_url() . 'error-redirect');
            exit();
        }
        return $query->result_array();
    }

    public function getWebsiteUsersCount() {

        $this->db->select('*');
        $this->db->from('mst_users');
        $this->db->where_not_in('user_type', '2');
        $query = $this->db->get();
        return $query->result_array();
    }

}
