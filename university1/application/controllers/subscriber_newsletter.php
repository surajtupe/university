<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Subscriber_Newsletter extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('subscriber_newsletter_model');
        $this->load->model('common_model');
        $this->load->model('user_model');
    }

    public function listSubscriberNewsletter() {
        #Getting Common data
        $data = $this->common_model->commonFunction();
        #using the subscribernewsletter model
        #checking user has privilige for the Manage newsletter
        if ($data['user_account']['role_id'] != 1) {
            $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
            if (count($arr_privileges) > 0) {
                foreach ($arr_privileges as $privilege) {
                    $user_privileges[] = $privilege['privilege_id'];
                }
            }
            $arr_login_admin_privileges = $user_privileges;
            if (in_array('9', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You dont have priviliges to  manage newsletter!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        if (isset($_POST['btn_delete_all'])) {
            #getting all ides selected
            $arr_newsletter_ids = $this->input->post('checkbox');
            if (count($arr_newsletter_ids) > 0) {
                if (count($arr_newsletter_ids) > 0) {
                    #deleting the newsletter selected
                    $this->common_model->deleteRows($arr_newsletter_ids, "trans_newsletter_subscription", "newsletter_subscription_id");
                }
                $this->session->set_userdata("msg", "<span class='success'>Newsletter Subscriber deleted successfully!</span>");
                redirect(base_url() . 'backend/newsletter-subscriber/list');
            }
        }
        $data['title'] = "Manage Subscriber Newsletter";
        $data['arr_newsletter_list'] = $this->subscriber_newsletter_model->getSubscriberNewsletterDetails();

        $this->load->view('backend/subscriber-newsletter/list', $data);
    }

    public function changeStatus() {
        if ($this->input->post('newsletter_subscription_id') != "") {
            /* updating the article status. */
            $arr_to_update = array("subscribe_status" => $this->input->post('subscribe_status'));
            /* condition to update record for the article status */
            $condition_array = array('newsletter_subscription_id' => intval($this->input->post('newsletter_subscription_id')));
            $this->common_model->updateRow('trans_newsletter_subscription', $arr_to_update, $condition_array);
            $arr_newsletter_list = $this->subscriber_newsletter_model->getSubcriberNewsletterDetailsById($this->input->post('newsletter_subscription_id'));

            $table_to_pass = 'mst_users';
            $fields_to_pass = '*';
            $condition_to_pass = array("user_email" => $arr_newsletter_list[0]['user_email']);
            $arr_user_data = $this->user_model->getUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);

            echo json_encode(array("error" => "0", "error_message" => ""));
        } else {
            /* if something going wrong providing error message.  */
            echo json_encode(array("error" => "1", "error_message" => "Sorry, your request can not be fulfilled this time. Please try again later"));
        }
    }

    /* Function to add subscriber user details * */

    public function addNewsletterSubscriber() {

        if ($this->input->post('user_email') != '') {
            $data = $this->common_model->commonFunction();
            $user_email = $this->input->post('user_email');
            $table_to_pass = 'trans_newsletter_subscription';
            $fields_to_pass = array('user_email', 'user_subscription_code');
            $condition_to_pass = array("user_email" => $user_email);
            $data['newsletetter_info'] = $this->subscriber_newsletter_model->getSubscriberUserInformation($table_to_pass, $fields_to_pass, $condition_to_pass, $order_by_to_pass = '', $limit_to_pass = '', $debug_to_pass = 0);
            if (count($data['newsletetter_info']) > 0) {

                $newsletter_code = $data['newsletetter_info'][0]['user_subscription_code'];
                $this->newslettersubscribed($newsletter_code);
            } else {
                /* insert subscriber user details */
                $user_subscription_code = rand(9999, 1000000000);
                $arr_fields = array(
                    "user_email" => $this->input->post('user_email'),
                    "subscribe_status" => 'Active',
                    "user_subscription_code" => $user_subscription_code,
                    "is_subscribe_for_daily" => '0',
                );
                $last_insert_id = $this->subscriber_newsletter_model->insertNewsletterSubscriber($arr_fields);
                if ($last_insert_id > 0) {
                    /* Activation link  */
                    $activation_link = '<a href="' . base_url() . 'newsletter-unsubscribe/' . $user_subscription_code . '">Unsubscribe</a>';
                    /* setting reserved_words for email content */
                    $macros_array_details = array();
                    $macros_array_details = $this->common_model->getRecords("mst_email_template_macros", $fields_to_pass = 'macros,value', $condition_to_pass = '', $order_by = '', $limit = '', $debug = 0);
                    $macros_array = array();
                    foreach ($macros_array_details as $row) {
                        $macros_array[$row['macros']] = $row['value'];
                    }
                    $reserved_words = array(
                        "||SITE_TITLE||" => stripslashes($data['global']['site_title']),
                        "||SITE_PATH||" => "<a target= '_blank' href=" . base_url() . " >" . base_url() . "</a>",
                        "||USER_NAME||" => $this->input->post('user_email'),
                        "||unsubscribe_link||" => $activation_link
                    );
                    $reserved = array_replace_recursive($macros_array, $reserved_words);
                    /* getting mail subect and mail message using email template title and lang_id and reserved works */
                    $email_content = $this->common_model->getEmailTemplateInfo('newsletter-subscription', 17, $reserved);
                    $mail = $this->common_model->sendEmail(array($this->input->post('user_email')), array("email" => $data['global']['site_email'], "name" => stripslashes($data['global']['site_title'])), $email_content['subject'], $email_content['content']);
                    $this->session->set_userdata("newsletter_subscribed", "You have successfully subscribed to our newsletter.");
                    redirect(base_url());
                }
            }
        } else {
            redirect(base_url());
        }
    }

    /* function to unsubscribe newsletter. */

    public function newsletterUnsubscribed($unsubscribe_code) {
        //Get global settings.
        $data['global'] = $this->common_model->getGlobalSettings();

        $fields_to_pass = array('newsletter_subscription_id', 'user_email', 'subscribe_status', 'is_subscribe_for_daily');
        /* get user details to verify the email address */
        $data['newsletetter_info'] = $this->common_model->getRecords("trans_newsletter_subscription", $fields_to_pass, array("user_subscription_code" => $unsubscribe_code));

        if ($data['newsletetter_info'][0]['subscribe_status'] == 'Inactive') {
            $this->session->set_userdata('newsletter_unsubscribed', 'You have already unsubscribed from our newsletter.');
            redirect(base_url());
        } else if ($data['newsletetter_info'][0]['subscribe_status'] == 'Active') {
            $update_data = array(
                'subscribe_status' => 'Inactive');
            $this->common_model->updateRow("trans_newsletter_subscription", $update_data, array("user_subscription_code" => $unsubscribe_code));
            /* Activation link  */
            $activation_link = '<a href="' . base_url() . 'newsletter-subscribe/' . $unsubscribe_code . '">Subscribe Newsletter.</a>';
            /* setting reserved_words for email content */
            $macros_array_details = array();
            $macros_array_details = $this->common_model->getRecords("mst_email_template_macros", $fields_to_pass = 'macros,value', $condition_to_pass = '', $order_by = '', $limit = '', $debug = 0);
            $macros_array = array();
            foreach ($macros_array_details as $row) {
                $macros_array[$row['macros']] = $row['value'];
            }
            $reserved_words = array(
                "||SITE_TITLE||" => stripslashes($data['global']['site_title']),
                "||SITE_PATH||" => "<a target= '_blank' href=" . base_url() . " >" . base_url() . "</a>",
                "||USER_NAME||" => $data['newsletetter_info'][0]['user_email'],
                "||UNSUBCRIBE_LINK||" => $activation_link
            );
            $reserved = array_replace_recursive($macros_array, $reserved_words);
            /* getting mail subect and mail message using email template title and lang_id and reserved works */
            $email_content = $this->common_model->getEmailTemplateInfo('newsletter-unsubscription', 17, $reserved);
            $mail = $this->common_model->sendEmail(array($data['newsletetter_info'][0]['user_email']), array("email" => $data['global']['site_email'], "name" => stripslashes($data['global']['site_title'])), $email_content['subject'], $email_content['content']);
            if ($mail) {
                $this->session->set_userdata("newsletter_unsubscribed", "You have successfully unsubscribed from our newsletter.");
                redirect(base_url());
            }
        } else {
            /* if any error invalid activation link found account */
            $_SESSION['msg'] = '<div class="alert alert-error">Invalid unsubscribe code.</div>';
        }
        redirect(base_url());
    }

    /*
     * FUnction to subscribe user's newsletter 
     */

    public function newsletterSubscribed($subscribed_code) {
        //Get global settings.
        $data['global'] = $this->common_model->getGlobalSettings();

        $fields_to_pass = array('newsletter_subscription_id', 'user_email', 'subscribe_status', 'is_subscribe_for_daily');
        /* get user details to verify the email address */
        $arr_login_data = $this->common_model->getRecords("trans_newsletter_subscription", $fields_to_pass, array("user_subscription_code" => $subscribed_code));
        if (count($arr_login_data)) {
            $user_unsubscription_code = rand(9999, 1000000000);
            /* if email already verified */
            if ($arr_login_data[0]['subscribe_status'] == "Active") {
                $this->session->set_userdata('newsletter_subscribed', 'You have already subscribed to our newsletter.');
//                redirect(base_url());
            } else {
                /* if email not verified. */
                $update_data = array(
                    'subscribe_status' => 'Active',
                    'user_subscription_code' => $user_unsubscription_code
                );
                $this->common_model->updateRow("trans_newsletter_subscription", $update_data, array("user_subscription_code" => $subscribed_code));
                /* Send newsletter subscription email */

                /* Activation link  */
                $activation_link = '<a href="' . base_url() . 'newsletter-unsubscribe/' . $user_unsubscription_code . '">unsubscribe.</a>';
                /* setting reserved_words for email content */
                $macros_array_details = array();
                $macros_array_details = $this->common_model->getRecords("mst_email_template_macros", $fields_to_pass = 'macros,value', $condition_to_pass = '', $order_by = '', $limit = '', $debug = 0);
                $macros_array = array();
                foreach ($macros_array_details as $row) {
                    $macros_array[$row['macros']] = $row['value'];
                }

                $reserved_words = array(
                    "||SITE_TITLE||" => stripslashes($data['global']['site_title']),
                    "||SITE_PATH||" => "<a target= '_blank' href=" . base_url() . " >" . base_url() . "</a>",
                    "||USER_NAME||" => $arr_login_data[0]['user_email'],
                    "||unsubscribe_link||" => $activation_link
                );

                $reserved = array_replace_recursive($macros_array, $reserved_words);
                /* getting mail subect and mail message using email template title and lang_id and reserved works */
                $email_content = $this->common_model->getEmailTemplateInfo('newsletter-subscription', 17, $reserved);
                $mail = $this->common_model->sendEmail(array($arr_login_data[0]['user_email']), array("email" => $data['global']['site_email'], "name" => stripslashes($data['global']['site_title'])), $email_content['subject'], $email_content['content']);

                if ($mail) {
                    $this->session->set_userdata("newsletter_subscribed", "You subscribed our newsletter successfully.");
                    redirect(base_url());
                }
            }
        } else {
            /* if any error invalid activation link found account */
            $_SESSION['msg'] = '<div class="alert alert-error">Invalid unsubscribe code.</div>';
        }
        redirect(base_url());
    }

}
