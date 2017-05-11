<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Organization extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("common_model");
        $this->load->model("organization_model");
        $this->load->model("category_model");
    }

    public function documentList() {
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit;
        }
        $data = $this->common_model->commonFunction();
        if ($data['user_account']['role_id'] != 1) {
            $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
            if (count($arr_privileges) > 0) {
                foreach ($arr_privileges as $privilege) {
                    $user_privileges[] = $privilege['privilege_id'];
                }
            }
            $arr_login_admin_privileges = $user_privileges;
            if (in_array('7', $arr_login_admin_privileges) == FALSE) {
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }

        $data['document_list'] = $this->organization_model->getDocumentList('');
        $data['title'] = "Manage Document";
        $this->load->view('backend/document/list', $data);
    }

    public function documentAdd($edit_id = '') {
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit;
        }
        $lang_id = '17';
        if ($this->input->post('lang_id') == '')
            $lang_id = '17';
        else {
            $lang_id = $this->input->post('lang_id');
        }
        $edit_id = base64_decode($edit_id);
        if ($this->input->post('document_name') != '') {
            if ($this->input->post('edit_id') != '') {
                echo $edit_id;
                $arr_to_update = array(
                    "lang_id" => $lang_id,
                    'document_text' => $this->input->post('document_name')
                );
                $this->common_model->updateRow('trans_document_lang', $arr_to_update, array('service_document_lang_id' => $this->input->post('edit_id')));
                $this->session->set_userdata('msg', "<span class='success'>Document updated successfully!</span>");
            } else {
                $last_insert_id = $this->common_model->insertRow('document_id', "mst_documents");
                $insert_data = array(
                    'document_id_fk' => $last_insert_id,
                    'document_text' => $this->input->post('document_name')
                );
                $this->common_model->insertRow($insert_data, "trans_document_lang");
                $this->session->set_userdata("msg", "<span class='success'>Document added successfully!</span>");
                redirect(base_url() . "backend/document/list");
                exit;
            }
        }
        $data = $this->common_model->commonFunction();
        if ($data['user_account']['role_id'] != 1) {
            $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
            if (count($arr_privileges) > 0) {
                foreach ($arr_privileges as $privilege) {
                    $user_privileges[] = $privilege['privilege_id'];
                }
            }
            $arr_login_admin_privileges = $user_privileges;
            if (in_array('7', $arr_login_admin_privileges) == FALSE) {
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        if ($edit_id != '') {
            $data['edit_id'] = ($edit_id);
            $condition_to_pass = array('service_document_lang_id' => $edit_id);
            $data['document_details'] = $this->organization_model->getDocumentList($condition_to_pass);
            // single row fix
            $data['document_details'] = end($data['document_details']);
        }
        if ($edit_id != '') {
            $data['title'] = "Update Document";
        } else {
            $data['title'] = "Add Document";
        }
        $data['arr_get_language'] = $this->common_model->getLanguages();
        if (($this->input->post('edit_id') != '')) {
            redirect(base_url() . "backend/document/list");
        } else {
            $this->load->view('backend/document/add', $data);
        }
    }

    public function chkDocumentExits() {
        $document_name = $this->input->post('document_name');
        $old_document_name = $this->input->post('old_document_name');
        if ($old_document_name != '' && $document_name == $old_document_name) {
            echo 'true';
        } else {
            if ($document_name != '') {
                $condition_to_pass = array('document_text' => $document_name);
                $arr_get_records = $this->common_model->getRecords('trans_document_lang', '', $condition_to_pass);
                if (COUNT($arr_get_records) > 0) {
                    echo 'false';
                } else {
                    echo 'true';
                }
            }
        }
    }

    public function deleteDocument() {
        /* deleteing the single faq */
        $document_ids = $this->input->post('document_ids');
        if ($document_ids != "") {
            $this->common_model->deleteRows($document_ids, "mst_documents", "document_id ");
            echo json_encode(array("error" => "0", "errorMessage" => ""));
        } elseif ($this->input->post('$document_ids') != "") {
            /* deleting multipler faq selected */
            $arrDelete = array();
            foreach ($faq_ids as $fid) {
                array_push($arrDelete, $fid);
            }
            $this->common_model->deleteRows($arrDelete, "mst_documents", "document_id");
            $this->session->set_userdata("msg", "<span class='success'>FAQ deleted successfully!</span>");
            echo json_encode(array("error" => "0", "errorMessage" => ""));
        } else {
            /* returning error if any */
            echo json_encode(array("error" => "1", "errorMessage" => "Sorry, your request can not be fulfilled this time. Please try again later"));
        }
    }

    public function organizationList() {
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit;
        }
        $data = $this->common_model->commonFunction();
        if ($data['user_account']['role_id'] != 1) {
            $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
            if (count($arr_privileges) > 0) {
                foreach ($arr_privileges as $privilege) {
                    $user_privileges[] = $privilege['privilege_id'];
                }
            }
            $arr_login_admin_privileges = $user_privileges;
            if (in_array('7', $arr_login_admin_privileges) == FALSE) {
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }

        //getting all ides selected
        $arr_organization_ids = array();
        if ($this->input->post('checkbox') != '') {
            $arr_organization_ids = $this->input->post('checkbox');
            if (count($arr_organization_ids) > 0) {
                if (count($arr_organization_ids) > 0) {
                    //deleting the newsletter selected
                    $this->common_model->deleteRows($arr_organization_ids, "mst_orgnisational_details", "orgnisation_id");
                }
                $this->session->set_userdata('msg', "Records deleted successfully.");
                redirect(base_url() . "backend/newsletter/list");
            }
        }

        $data['organization_list'] = $this->common_model->getRecords('mst_orgnisational_details', 'orgnisation_id,orgnisation_name,primary_email,mobile_number,status');
        $data['title'] = "Manage Organization";
        $this->load->view('backend/organization/list', $data);
    }

    public function changeStatus() {
        $orgnisation_id = $this->input->post('orgnisation_id');
        if ($orgnisation_id != '') {
            /* changing status of faq */
            $arr_to_update = array("status" => $this->input->post('status'));
            $this->common_model->updateRow('mst_orgnisational_details', $arr_to_update, array('orgnisation_id' => $orgnisation_id));
            echo json_encode(array("error" => "0", "error_message" => ""));
        } else {
            /* returning error if any */
            echo json_encode(array("error" => "1", "error_message" => "Sorry, your request can not be fulfilled this time. Please try again later"));
        }
    }

    public function organizationAdd($edit_id = '') {
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
            exit;
        }
        $lang_id = '17';
        if ($this->input->post('lang_id') == '')
            $lang_id = '17';
        else {
            $lang_id = $this->input->post('lang_id');
        }
        $edit_id = base64_decode($edit_id);

        if ($this->input->post('orgnisation_name') != '' && $this->input->post('address') != '' && $this->input->post('primary_email') != '' && $this->input->post('mobile_number') != '') {
            if ($this->input->post('edit_id') != '') {
                $arr_to_update = array(
                    "lang_id" => $lang_id,
                    'document_text' => $this->input->post('document_name')
                );
                $this->common_model->updateRow('trans_document_lang', $arr_to_update, array('service_document_lang_id' => $this->input->post('edit_id')));
                $this->session->set_userdata('msg', "<span class='success'>Document updated successfully!</span>");
                $update_data = array(
                    'service_category_id_fk' => $this->input->post('sub_category_id'),
                    'orgnisation_name' => $this->input->post('orgnisation_name'),
                    'address' => $this->input->post('address'),
                    'primary_email' => $this->input->post('primary_email'),
                    'secondry_email' => $this->input->post('secondry_email'),
                    'mobile_number' => $this->input->post('mobile_number'),
                    'fax_number' => $this->input->post('fax_number'),
                    'landline_number' => $this->input->post('landline_number'),
                    'number_changed_notified' => $this->input->post('number_changed_notified'),
                    'address_changed_notified' => $this->input->post('address_changed_notified'),
                    'poc_changed_notified' => $this->input->post('poc_changed_notified'),
                    'support_poc' => $this->input->post('support_poc'),
                    'document_required_address_change' => $this->input->post('document_required_address_change'),
                    'payment_required_address_change' => $this->input->post('payment_required_address_change'),
                    'document_required_mobile_change' => $this->input->post('document_required_mobile_change'),
                    'payment_required_mobile_change' => $this->input->post('payment_required_mobile_change'),
                    'support_number_change' => $this->input->post('support_number_change'),
                    'support_address_change' => $this->input->post('support_address_change'),
                    'status' => '0',
                );
                $con = array('orgnisation_id' => $edit_id);
                $this->common_model->updateRow("mst_orgnisational_details", $update_data, $con);
                $con = array('organisation_id_fk' => $edit_id);
                $this->common_model->deleteRows($con, "trans_service_required_documents", 'organisation_id_fk');
                $document = $this->input->post('document_required');
                if (COUNT($document) > 0) {
                    foreach ($document as $doc_id) {
                        $update_data = array(
                            'document_id_fk' => $doc_id,
                            'organisation_id_fk' => $edit_id
                        );
                        $this->common_model->insertRow($update_data, "trans_service_required_documents");
                    }
                }

                $con = array('organisation_id_fk' => $edit_id);
                $update_data = array(
                    'admin_fees_for_mobile_change' => $this->input->post('admin_fees_for_mobile_change'),
                    'admin_fees_for_address_change' => $this->input->post('admin_fees_for_address_change'),
                    'admin_fees_for_POC_change' => $this->input->post('admin_fees_for_POC_change'),
                    'mobile_change_charges' => $this->input->post('mobile_change_charges'),
                    'address_change_charges' => $this->input->post('address_change_charges'),
                    'poc_change_charges' => $this->input->post('poc_change_charges'),
                );
                $this->common_model->updateRow("trans_service_catgory_charges", $update_data, $con);
                $this->session->set_userdata("msg", "<span class='success'>Document updated successfully!</span>");
                redirect(base_url() . "backend/organization/list");
                exit;
            } else {

                $insert_data = array(
                    'service_category_id_fk' => $this->input->post('sub_category_id'),
                    'orgnisation_name' => $this->input->post('orgnisation_name'),
                    'address' => $this->input->post('address'),
                    'primary_email' => $this->input->post('primary_email'),
                    'secondry_email' => $this->input->post('secondry_email'),
                    'mobile_number' => $this->input->post('mobile_number'),
                    'fax_number' => $this->input->post('fax_number'),
                    'landline_number' => $this->input->post('landline_number'),
                    'number_changed_notified' => $this->input->post('number_changed_notified'),
                    'address_changed_notified' => $this->input->post('address_changed_notified'),
                    'poc_changed_notified' => $this->input->post('poc_changed_notified'),
                    'support_poc' => $this->input->post('support_poc'),
                    'document_required_address_change' => $this->input->post('document_required_address_change'),
                    'payment_required_address_change' => $this->input->post('payment_required_address_change'),
                    'document_required_mobile_change' => $this->input->post('document_required_mobile_change'),
                    'payment_required_mobile_change' => $this->input->post('payment_required_mobile_change'),
                    'support_number_change' => $this->input->post('support_number_change'),
                    'support_address_change' => $this->input->post('support_address_change'),
                    'status' => '0',
                );
                $last_insert_id = $this->common_model->insertRow($insert_data, "mst_orgnisational_details");

                $document = $this->input->post('document_required');
                if (COUNT($document) > 0) {
                    foreach ($document as $doc_id) {
                        $insert_data = array(
                            'document_id_fk' => $doc_id,
                            'organisation_id_fk' => $last_insert_id
                        );
                        $this->common_model->insertRow($insert_data, "trans_service_required_documents");
                    }
                }

                $insert_data = array(
                    'organisation_id_fk' => $last_insert_id,
                    'admin_fees_for_mobile_change' => $this->input->post('admin_fees_for_mobile_change'),
                    'admin_fees_for_address_change' => $this->input->post('admin_fees_for_address_change'),
                    'admin_fees_for_POC_change' => $this->input->post('admin_fees_for_POC_change'),
                    'mobile_change_charges' => $this->input->post('mobile_change_charges'),
                    'address_change_charges' => $this->input->post('address_change_charges'),
                    'poc_change_charges' => $this->input->post('poc_change_charges'),
                );
                $this->common_model->insertRow($insert_data, "trans_service_catgory_charges");
                $this->session->set_userdata("msg", "<span class='success'>Document added successfully!</span>");
                redirect(base_url() . "backend/organization/list");
                exit;
            }
        }
        $data = $this->common_model->commonFunction();
        if ($data['user_account']['role_id'] != 1) {
            $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
            if (count($arr_privileges) > 0) {
                foreach ($arr_privileges as $privilege) {
                    $user_privileges[] = $privilege['privilege_id'];
                }
            }
            $arr_login_admin_privileges = $user_privileges;
            if (in_array('7', $arr_login_admin_privileges) == FALSE) {
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        if ($edit_id != '') {
            $data['edit_id'] = ($edit_id);
            $condition_to_pass = array('organisation_id_fk' => $edit_id);
            $oganization_details = $this->organization_model->getOrganizationDetials($condition_to_pass);

            $document_id = array();
            if (COUNT($oganization_details) > 0) {
                foreach ($oganization_details as $key => $org_detials) {
                    $arr_organization_details[$key] = $org_detials;
                    $condition_to_pass = array('organisation_id_fk' => $org_detials['orgnisation_id']);
                    $arr_document_details = $this->organization_model->getSeviceRequiredDocument($condition_to_pass);
                    foreach ($arr_document_details as $document_details) {
                        $document_id[] = $document_details['document_id'];
                    }
                    $arr_organization_details[$key]['selected_document_list'] = $document_id;
                }
            }
//              echo '<pre>'; print_R($arr_organization_details);die;
            $data['oganization_details'] = end($arr_organization_details);
            $condition_to_pass = array('parent_id' => $data['oganization_details']['parent_id']);
            $data['arr_get_sub_category'] = $this->category_model->getAllCategoriesForSelect($condition_to_pass);
            $data['title'] = "Update Organization";
        } else {
            $data['title'] = "Add Organization";
        }

        $condition_to_pass = array("lang_id" => '17', 'parent_id' => 0);
        $data['arr_categary_list'] = $this->category_model->getAllCategoriesForSelect($condition_to_pass);
        $data['arr_get_language'] = $this->common_model->getLanguages();
        $data['arr_document_list'] = $this->organization_model->getDocumentList('');

//        $data['arr_selcted']

        if (($this->input->post('edit_id') != '')) {
            redirect(base_url() . "backend/organization/list");
        } else {
            $this->load->view('backend/organization/add', $data);
        }
    }

    public function organizationName() {
        $ognization_name = $this->input->post('orgnisation_name');
        $old_organization_name = $this->input->post('old_organization_name');
        if ($ognization_name == $old_organization_name) {
            echo 'true';
        } else {
            if ($ognization_name != '') {
                $codition_to_pass = array('orgnisation_name' => $ognization_name);
                $arr_orgnization_details = $this->common_model->getRecords('mst_orgnisational_details', '', $codition_to_pass);
                if (COUNT($arr_orgnization_details) > 0) {
                    echo 'false';
                } else {
                    echo 'true';
                }
            }
        }
    }

    public function categoryInfo() {
        $category_id = $this->input->post('category_id');
        if ($category_id != '') {
            $condition_to_pass = array('parent_id' => $category_id);
            $arr_get_category = $this->category_model->getAllCategoriesForSelect($condition_to_pass);
            if (count($arr_get_category) > 0) {
                ?>
                <label for="Language">Sub Category<sup class="mandatory">*</sup></label>
                <select class="form-control" id="sub_category_id" name="sub_category_id">
                    <option value="">Select Sub Category</option>
                    <?php
                    for ($i = 0; $i < count($arr_get_category); $i++) {
                        ?>
                        <option value="<?php echo $arr_get_category[$i]['service_category_id']; ?>"><?php echo stripslashes($arr_get_category[$i]['category_name']); ?></option>
                    <?php }
                    ?>
                </select>
            <?php } else { ?>
                <label for="Language">Sub Category<sup class="mandatory">*</sup></label>
                <select class="form-control" id="sub_category_id" name="sub_category_id" >
                    <option value="">Select Sub Category</option>
                </select>
                <?php
            }
        }
    }

}
