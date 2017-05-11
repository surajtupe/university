<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Blog extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('blog_model');
    }

    public function index($the_url = '', $pg = 0) {
        $data = $this->common_model->commonFunction();
        $data['global'] = $this->common_model->getGlobalSettings();
        $data['arr_all_languages'] = $this->common_model->getRecords("mst_languages", "lang_id,lang_name", array("status" => 'A'));
        $data['category_tree'] = $this->getBlogCategoriesTreeStructure();
        $data['user_session'] = $this->session->userdata('user_account');
        if ($this->session->userdata('referrer') == "") {
            
        }
        $posted_key = strip_quotes($this->input->post('search'));
        if ($this->input->post('search_null') == '1' && $posted_key == '') {
            $this->session->unset_userdata('search');
        }
        if ($posted_key != '') {
            $posted_key = $posted_key;
            $this->session->unset_userdata('search');
        } elseif ($this->session->userdata('search') != '') {
            $posted_key = strip_quotes($this->session->userdata('search'));
        } else {
            $this->session->unset_userdata('search');
        }
        if ($posted_key != "" && (is_numeric($the_url) || $the_url == '')) {

            if ((is_numeric($the_url))) {
                $pg = $the_url;
            } else {
                $pg = 0;
            }
            $this->session->set_userdata('search', $posted_key);
            $data['header'] = array("title" => "Search results for " . $posted_key, "keywords" => "", "description" => "");
            if ($this->session->userdata('lang_id') != "") {
                $condition_to_pass = "b.`status` = '1' and (tb.`post_title` like '%" . $posted_key . "%' or tb.`post_short_description` like '%" . $posted_key . "%' or tb.`post_content` like '%" . $posted_key . "%' or tb.`post_tags` like '%" . $posted_key . "%')";
            } else {
                $condition_to_pass = "b.`status` = '1' and (b.`post_title` like '%" . $posted_key . "%' or b.`post_short_description` like '%" . $posted_key . "%' or b.`post_content` like '%" . $posted_key . "%' or b.`post_tags` like '%" . $posted_key . "%')";
            }
            $data['blog_posts_one'] = $this->blog_model->getAllBlog('', $condition_to_pass);

            $this->load->library('pagination');
            $data['count'] = count($data['blog_posts_one']);
            $config['base_url'] = base_url() . 'blog/';
            $config['total_rows'] = count($data['blog_posts_one']);
            $config['total_rows'];
            $config['per_page'] = 12;
            $config['cur_page'] = $pg;
            $data['cur_page'] = $pg;
            $config['num_links'] = 2;
            $config['full_tag_open'] = ' <p class="paginationPara">';
            $config['full_tag_close'] = '</p>';
            $this->pagination->initialize($config);
            $data['create_links'] = $this->pagination->create_links();
            $data['blog_posts_two'] = $this->blog_model->getAllBlog('', $condition_to_pass, '', $config['per_page'], $pg);
            $data['page'] = $pg; //$pg is used to pass limit 
            /** Pagination end here * */
            foreach ($data['blog_posts_two'] as $key => $value) {
                $data['blog_posts'][$key] = $value;
                $result = $this->getPostComments($value['post_id']);
                $data['blog_posts'][$key]['comment_count'] = count($result);
            }
            $data['latest_blogs'] = $this->blog_model->getRecentBlogs();
            $data['site_title'] = 'Blog';

            $this->load->view('front/includes/header', $data);
            $this->load->view('front/includes/inner-top-nav', $data);
            $this->load->view('front/blogs/blog-home', $data);
            $this->load->view('front/includes/footer');
        } else {
            $this->session->unset_userdata('search');
            if ((is_numeric($the_url)) || $the_url == '') {
                if ((is_numeric($the_url))) {
                    $pg = $the_url;
                } else {
                    $pg = 0;
                }
                $data['blog_posts_one'] = $this->getFrontBlogs();

                $this->load->library('pagination');
                $data['count'] = count($data['blog_posts_one']);
                $config['base_url'] = base_url() . 'blog/';
                $config['total_rows'] = count($data['blog_posts_one']);
                $config['total_rows'];
                $config['per_page'] = 12;
                $config['cur_page'] = $pg;
                $data['cur_page'] = $pg;
                $config['num_links'] = 2;
                $config['full_tag_open'] = ' <p class="paginationPara">';
                $config['full_tag_close'] = '</p>';
                $this->pagination->initialize($config);
                $data['create_links'] = $this->pagination->create_links();
                $data['blog_posts_two'] = $this->blog_model->getAllBlog('', '', '', $config['per_page'], $pg);
                $data['page'] = $pg; //$pg is used to pass limit 
                /** Pagination end here * */
                foreach ($data['blog_posts_two'] as $key => $value) {
                    $data['blog_posts'][$key] = $value;
                    $result = $this->getPostComments($value['post_id']);
                    $data['blog_posts'][$key]['comment_count'] = count($result);
                }

                $data['latest_blogs'] = $this->blog_model->getRecentBlogs();
                $data['site_title'] = 'Blogs';
                $this->load->view('front/includes/header', $data);
                $this->load->view('front/includes/inner-top-nav', $data);
                $this->load->view('front/blogs/blog-home', $data);
                $this->load->view('front/includes/footer');
            } else {
                $the_page_info = $this->common_model->getPageInfoByUrl($the_url);

                if (count($the_page_info) > 0) {
                    $the_page_info = end($the_page_info);

                    if ($the_page_info['type'] == 'blog-category') {

                        $category_id = $the_page_info['rel_id'];

                        $category_info = $this->blog_model->getCategories('*', array('category_id' => $category_id));
                        $data['header'] = array("title" => $category_info[0]['page_title'], "keywords" => $category_info[0]['page_keywords'], "description" => $category_info[0]['page_description']);
                        $data['blog_posts'] = $this->getFrontBlogs('', "b.`category_id` ='" . $category_id . "'");
                        $data['site_title'] = 'Blog';
                        $this->load->view('front/includes/header', $data);
                        $this->load->view('front/includes/inner-top-nav', $data);
                        $this->load->view('front/blogs/blog-home', $data);
                        $this->load->view('front/includes/footer');
                    } elseif ($the_page_info['type'] == 'blog-post') {

                        $post_id = $the_page_info['rel_id'];
                        $data['blog_posts'] = $this->getFrontBlogs('', "b.`post_id` ='" . $post_id . "'");
                        if ($data['blog_posts'][0]['status'] == '1') {
                            $data['header'] = array("title" => $data['blog_posts'][0]['page_title'], "keywords" => $data['blog_posts'][0]['post_keywords'], "description" => $data['blog_posts'][0]['post_short_description']);
                            $data['post_id'] = $post_id;
                            $data['post_comments'] = $this->getPostComments($post_id);
                            $data['comment_count'] = count($data['post_comments']);
                        } else {
                            redirect(base_url() . "blog");
                        }
                        $data['meta_description'] = $data['blog_posts'][0]['post_short_description'];
                        $data['meta_keywords'] = $data['blog_posts'][0]['post_keywords'];
                        $data['meta_title'] = $data['blog_posts'][0]['page_title'];
                        $data['site_title'] = 'Blog post';
                        $this->load->view('front/includes/header', $data);
                        $this->load->view('front/includes/inner-top-nav', $data);
                        $this->load->view('front/blogs/blog-post', $data);
                        $this->load->view('front/includes/footer');
                    }
                } else {

                    echo "<h2>404 - Page not found</h2>";
                }
            }
        }
    }

    /*
     * Function will return all blog categories with category tree in desired format
     */

    public function getBlogCategoriesTreeStructure($type = 'ul') {

        if ($this->session->userdata('lang_id') != "") {
            $lang_id = $this->session->userdata('lang_id');
        } else {
            $lang_id = 17;
        }
        return $this->blog_model->getCategoryTreeResponse($type, $lang_id);
    }

    /*
     *  End Blog Categories Tree Structure function
     */
    /*
     *  Function to get all blog posts
     */

    private function getPosts($fields = '', $condition = '', $order_by) {
        if ($this->session->userdata("lang_id") != "") {
            $lang_id = $this->session->userdata("lang_id");
        } else {
            $lang_id = 17;
        }
        return $this->blog_model->getPosts($fields, $condition, $order_by, '', 0, $lang_id);
    }

    private function getFrontBlogs($fields = '', $condition = '', $order_by = '', $limit = '', $offset = '') {
        if ($this->session->userdata("lang_id") != "") {
            $lang_id = $this->session->userdata("lang_id");
        } else {
            $lang_id = 17;
        }
        return $this->blog_model->getAllBlog($fields, $condition, $order_by, $limit = '', $offset = '', '', $lang_id);
    }

    /*
     *  Function to search blog posts
     */

    private function searchPosts($searchKey) {
        return $this->blog_model->searchPosts($searchKey);
    }

    private function getPostComments($post_id) {
        $limit = "10";
        $condition_to_pass = array("post_id" => $post_id, "status" => "1");
        $order = ('comment_on desc');
        $arr_comments = $this->blog_model->getPostComments("", $condition_to_pass, $order, $limit);
        return $arr_comments;
    }

    public function add_comment() {
        $post_id = $this->input->post('p');
        $post_comment = $this->input->post('msg_comment');
        $posted_by = $this->input->post('posted_by');
        $user_id = $this->input->post('user_id');
        $arr_blog_comment = array();
        $arr_blog_comment["post_id"] = $post_id;
        $arr_blog_comment["comment"] = ($post_comment);
        $arr_blog_comment["comment_on"] = date("Y-m-d H:i:s");
        $arr_blog_comment["commented_by"] = $posted_by;
        $arr_blog_comment["commented_user_id"] = $user_id;

        $arr_blog_comment["status"] = "1";

        $arr_insert_data = $this->blog_model->add_comment($arr_blog_comment);
        $this->session->set_userdata('blog_comment', 'Your comment has been posted successfully.');
        echo json_encode(array("error" => "0"));
    }

    public function edit_category($post_id = '') {
        /*         * checking admin is logged in or not ** */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }
        /** using the email template model ** */
        $data = $this->common_model->commonFunction();
        $arr_privileges = array();
        //checking for admin privilages
        if ($data['user_account']['role_id'] != 1) {
            $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
            if (count($arr_privileges) > 0) {
                foreach ($arr_privileges as $privilege) {
                    $user_privileges[] = $privilege['privilege_id'];
                }
            }
            $arr_login_admin_privileges = $user_privileges;
            if (in_array('10', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }

        $post_category = $this->input->post("category_name");
        if ($post_category != "") {

            $edit_id = $this->input->post("edit_id");
            // this is update request
            $arr_forum_comment = array();
            $arr_forum_comment["category_name"] = ($post_category);
            $arr_forum_comment["parent_id"] = "" . $this->input->post("parent_category");
            $arr_update_condition = array("category_id" => $edit_id);
            $this->blog_model->updateCategory($arr_forum_comment, $arr_update_condition);
            $url = $post_category;
            $rel_id = $edit_id;
            $arr_update_url = array();
            $arr_update_url['url'] = preg_replace('/[^A-Za-z0-9\-_.]/', '', str_replace(" ", "-", $url));
            $where_field = array("type" => 'blog-category', "rel_id" => $rel_id);
            $this->common_model->updateURI($arr_update_url, $where_field);
            $this->session->set_userdata("msg", "<span class='success'>Category updated successfully!</span>");

            redirect(base_url() . "backend/blog/blog-category");
        }

        $arr_categories = $this->blog_model->getCategories();
        $data["title"] = "Edit Blog Category";
        $data["arr_categories"] = $arr_categories;
        $data["category_id"] = $post_id;
        $arr_cat_info = $this->blog_model->getCategories("*", array("category_id" => $post_id));
        $data["arr_cat_info"] = $arr_cat_info[0];
        $this->load->view('backend/blogs/edit-category', $data);
    }

    public function delete_category() {

        $post_id = $this->input->post("post_id");
        $post_ids = $this->input->post("post_ids");

        $this->load->model("blog_model");

        if ($post_id != "")
            $this->blog_model->deleteCategory(array("category_id" => "" . intval($post_id)));
        elseif ($post_ids != "") {
            foreach ($post_ids as $post_id) {

                $arr_delete = array("category_id" => "" . intval($post_id));
                $this->blog_model->deleteCategory($arr_delete);
            }
        }
        $this->session->set_userdata("msg", "<span class='success'>Categories deleted successfully!</span>");
        echo json_encode(array("msg" => "success", "error" => "0"));
    }

    public function manage_blog_posts() {
        /*         * checking admin is logged in or not ** */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }
        /*         * using the email template model ** */

        $data = $this->common_model->commonFunction();
        $arr_privileges = array();

        //checking for admin privilages
        if ($data['user_account']['role_id'] != 1) {
            $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
            if (count($arr_privileges) > 0) {
                foreach ($arr_privileges as $privilege) {
                    $user_privileges[] = $privilege['privilege_id'];
                }
            }
            $arr_login_admin_privileges = $user_privileges;
            if (in_array('10', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }

        $data['blog_posts'] = $this->getPosts('', '', 'post_id DESC');
        $data["title"] = "Manage Blog Posts";
        $this->load->view('backend/blogs/list', $data);
    }

    public function edit_post($post_id = '') {

        /*         * checking admin is logged in or not ** */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }
        /*         * using the email template model ** */
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
            if (in_array('10', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="validationError">', '</p>');
        $this->form_validation->set_rules('inputName', 'title', 'required');
        $this->form_validation->set_rules('lang_id', 'language', 'required');
        $this->form_validation->set_rules('inputPostShortDescription', 'short description', 'required');
        $this->form_validation->set_rules('inputPostPageTitle', 'page title', 'required');
        $this->form_validation->set_rules('lang_id', 'language', 'required');

        $post_title = $this->input->post("inputName");
        if ($this->form_validation->run() == true && $post_title != "") {
            $data1['user_session'] = $this->session->userdata('user_account');
            $edit_id = $this->input->post("edit_id");
            if ($edit_id != "") {
                if ($_FILES['blog_image']['name'][0] != '') {
                    $arr_file = $this->findExtension($_FILES['blog_image']['name']);
                    $image_name = time() . '.' . $arr_file['ext'];
                    $upload_dir = './media/backend/img/blog_image/';
                    $old_name = $upload_dir . $this->input->post('hidden_image');
                    unlink($old_name);
                    $config['upload_path'] = $upload_dir;
                    $config['allowed_types'] = 'gif|jpg|jpeg|png|ico|bmp';
                    $config['max_width'] = '102400';
                    $config['max_height'] = '76800';
                    $config['file_name'] = time();
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('blog_image')) {
                        $error = array('error' => $this->upload->display_errors());
                        $this->session->set_userdata('msg', $error['error']);
                        redirect(base_url() . 'backend/blog/add-post');
                    } else {
                        $data = array('upload_data' => $this->upload->data());
                        $image_data = $this->upload->data();
                        $image_name = $image_data['file_name'];
                        $absolute_path = $this->common_model->absolutePath();
                        $image_path = $absolute_path . $upload_dir;
                        $image_main = $image_path . "/" . $image_name;
                        $thumbs_image = $image_path . "/thumbs/" . $image_name;
                        $thumbs_image1 = $image_path . "/recent-thumbs/" . $image_name;

                        $str_console = "convert " . $image_main . " -resize 795!X400! " . $thumbs_image;
                        exec($str_console);

                        $str_console1 = "convert " . $image_main . " -resize 100!X50! " . $thumbs_image1;
                        exec($str_console1);
                    }

                    $arr_post_data = array(
                        "post_title" => strip_slashes($this->input->post('inputName')),
                        "lang_id" => addslashes($this->input->post('lang_id')),
                        'post_short_description' => strip_slashes($this->input->post('inputPostShortDescription')),
                        'post_content' => strip_slashes($this->input->post('inputPostDescription')),
                        'page_title' => strip_slashes($this->input->post('inputPostPageTitle')),
                        'post_tags' => strip_slashes($this->input->post('inputPostTags')),
                        'post_keywords' => strip_slashes($this->input->post('inputPostKeywords')),
                        'category_id' => ($this->input->post('inputParentCategory')),
                        'blog_image' => $image_name,
                        'posted_by' => $data1['user_session']['user_id'],
                        'status' => "" . intval($this->input->post('inputPublishStatus'))
                    );
                    $arr_update_condition = array("post_id" => $edit_id);
                    $this->blog_model->updatePost($arr_post_data, $arr_update_condition);
                } else {
                    $arr_post_data = array(
                        "post_title" => strip_slashes($this->input->post('inputName')),
                        "lang_id" => addslashes($this->input->post('lang_id')),
                        'post_short_description' => strip_slashes($this->input->post('inputPostShortDescription')),
                        'post_content' => strip_slashes($this->input->post('inputPostDescription')),
                        'page_title' => strip_slashes($this->input->post('inputPostPageTitle')),
                        'post_tags' => strip_slashes($this->input->post('inputPostTags')),
                        'post_keywords' => strip_slashes($this->input->post('inputPostKeywords')),
                        'category_id' => ($this->input->post('inputParentCategory')),
                        'posted_by' => $data1['user_session']['user_id'],
                        'status' => "" . intval($this->input->post('inputPublishStatus'))
                    );
                    $arr_update_condition = array("post_id" => $edit_id);
                    $this->blog_model->updatePost($arr_post_data, $arr_update_condition);
                }
                $url = mysql_escape_string($this->input->post('inputName'));
                $rel_id = $edit_id;
                $arr_update_url = array();
                $arr_update_url['url'] = preg_replace('/[^A-Za-z0-9\-_.]/', '', str_replace(" ", "-", $url));
                ;
                $where_field = array("type" => 'blog-post', "rel_id" => $rel_id);
                $this->common_model->updateURI($arr_update_url, $where_field);
                $this->session->set_userdata("msg", "<span class='success'>Blog updated successfully!</span>");
            } else {

                // this is insert request
                if ($_FILES['blog_image']['name'][0] != '') {

                    $arr_file = $this->findExtension($_FILES['blog_image']['name']);
                    $image_name = str_replace(' ', '_', $arr_file['file_name'] . '-' . time() . '.' . $arr_file['ext']);
                    $upload_dir = './media/backend/img/blog_image/';
                    $old_name = $upload_dir . $this->input->post('hidden_image');
                    unlink($old_name);
                    $config['upload_path'] = $upload_dir;
                    $config['allowed_types'] = 'gif|jpg|jpeg|png|ico|bmp';
                    $config['max_width'] = '102400';
                    $config['max_height'] = '76800';
                    $config['file_name'] = time();

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('blog_image')) {
                        $error = array('error' => $this->upload->display_errors());
                        $this->session->set_userdata('msg', $error['error']);
                        redirect(base_url() . 'backend/blog/add-post');
                    } else {
                        $data = array('upload_data' => $this->upload->data());
                        $image_data = $this->upload->data();
                        $image_name = $image_data['file_name'];
                        $absolute_path = $this->common_model->absolutePath();
                        $image_path = $absolute_path . $upload_dir;
                        $image_main = $image_path . "/" . $image_name;
                        $thumbs_image = $image_path . "/thumbs/" . $image_name;
                        $thumbs_image1 = $image_path . "/recent-thumbs/" . $image_name;


                        $str_console1 = "convert " . $image_main . " -resize 795!X400! " . $thumbs_image;
                        exec($str_console1);

                        $str_console1 = "convert " . $image_main . " -resize 100!X50! " . $thumbs_image1;
                        exec($str_console1);
                    }
                }

                $arr_post_data = array(
                    "post_title" => strip_slashes($this->input->post('inputName')),
                    "lang_id" => addslashes($this->input->post('lang_id')),
                    'post_short_description' => strip_slashes($this->input->post('inputPostShortDescription')),
                    'post_content' => strip_slashes($this->input->post('inputPostDescription')),
                    'page_title' => strip_slashes($this->input->post('inputPostPageTitle')),
                    'post_tags' => strip_slashes($this->input->post('inputPostTags')),
                    'post_keywords' => strip_slashes($this->input->post('inputPostKeywords')),
                    'category_id' => ($this->input->post('inputParentCategory')),
                    'blog_image' => $image_name,
                    'posted_by' => $data1['user_session']['user_id'],
                    'posted_on' => date("Y-m-d H:i:s"),
                    'status' => $this->input->post('inputPublishStatus')
                );

                $new_post_id = $this->blog_model->insertNewPost($arr_post_data);
                $rel_id = $new_post_id;
                $url = $post_title;
                $arr_update_url = array("type" => 'blog-post', "rel_id" => $rel_id);
                $arr_update_url['url'] = preg_replace('/[^A-Za-z0-9\-_.]/', '', str_replace(" ", "-", $url));
                $this->common_model->insertURI($arr_update_url, $where_field);
                $this->session->set_userdata("msg", "<span class='success'>Blog added successfully!</span>");
            }
            redirect(base_url() . "backend/blog");
        }
        $data['arr_get_language'] = $this->common_model->getLanguages();
        $arr_categories = $this->blog_model->getCategories();
        if ($post_id == "") {
            if ($this->input->post("edit_id") == '') {
                $data["title"] = "Add Blog Post";
                $data["arr_categories"] = $arr_categories;
                $this->load->view('backend/blogs/add', $data);
            } else {
                $data["title"] = "Update Blog Post";
                $data["arr_categories"] = $arr_categories;
                $data["post_id"] = $post_id;
                $arr_post_info = $this->blog_model->getPosts("", array("post_id" => $this->input->post("edit_id")));

                $data["post_info"] = $arr_post_info[0];
                $this->load->view('backend/blogs/edit', $data);
            }
        } else {
            $data["title"] = "Update Blog Post";
            $data["arr_categories"] = $arr_categories;
            $data["post_id"] = $post_id;
            $arr_post_info = $this->blog_model->getPosts("", array("post_id" => $post_id));

            $data["post_info"] = $arr_post_info[0];
            $this->load->view('backend/blogs/edit', $data);
        }
    }

    public function delete_post() {
        $post_id = $this->input->post("post_id");
        $post_ids = $this->input->post("post_ids");

        $this->load->model("blog_model");

        if ($post_id != "")
            $this->blog_model->deletePost(array("post_id" => "" . intval($post_id)));
        elseif ($post_ids != "") {
            foreach ($post_ids as $post_id) {

                $arr_delete = array("post_id" => "" . intval($post_id));
                $this->blog_model->deletePost($arr_delete);
            }
        }
        $this->session->set_userdata("msg", "<span class='success'>Blog(s) deleted successfully!</span>");
        echo json_encode(array("msg" => "success", "error" => "0"));
    }

    public function delete_post_comment() {
        $comment_id = $this->input->post("comment_id");
        $comment_ids = $this->input->post("comment_ids");

        if ($comment_id != "")
            $this->blog_model->deletePostComment(array("comment_id" => "" . intval($comment_id)));
        elseif ($comment_ids != "") {
            foreach ($comment_ids as $comment_id) {

                $arr_delete = array("comment_id" => "" . intval($comment_id));
                $this->blog_model->deletePostComment($arr_delete);
            }
        }
        $this->session->set_userdata("msg", "<span class='success'>Blog(s) deleted successfully!</span>");
        echo json_encode(array("msg" => "success", "error" => "0"));
    }

    public function view_post_comments($post_id) {
        /*         * checking admin is logged in or not ** */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }
        /*         * using the email template model ** */

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
            if (in_array('10', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        $post_comments = $this->blog_model->getPostComments("", array("post_id" => $post_id), 'comment_id DESC');
        $data["title"] = "Blog Post Comments";
        $data["post_id"] = $post_id;
        $data["arr_post_comments"] = $post_comments;
        $this->load->view('backend/blogs/post_comments', $data);
    }

    public function add_post_comment($post_id) {

        /*         * checking admin is logged in or not ** */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }
        /*         * using the email template model ** */

        $data = $this->common_model->commonFunction();
        $arr_privileges = array();
        //checking for admin privilages
        if ($data['user_account']['role_id'] != 1) {
            $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
            if (count($arr_privileges) > 0) {
                foreach ($arr_privileges as $privilege) {
                    $user_privileges[] = $privilege['privilege_id'];
                }
            }
            $arr_login_admin_privileges = $user_privileges;
            if (in_array('10', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        $post_comment = $this->input->post("inputComment");

        /*
         *
         * Post request check
         *
         */

        if ($post_comment != "") {
            $data['user_session'] = $this->session->userdata('user_account');
            $post_id = $this->input->post('post_id');
            $comment_by = $data['user_session']['first_name'] ? $data['user_session']['first_name'] . ' ' . $data['user_session']['last_name'] : $data['user_session']['user_name'];
            $arr_blog_comment = array();
            $arr_blog_comment["post_id"] = $post_id;
            $arr_blog_comment["comment"] = strip_slashes($post_comment);
            $arr_blog_comment["comment_on"] = date("Y-m-d H:i:s");
            $arr_blog_comment["commented_by"] = $comment_by;
            $arr_blog_comment["status"] = "" . $this->input->post("inputPublishStatus");
            $this->blog_model->add_comment($arr_blog_comment);
            $this->session->set_userdata("msg", "<span class='success'>Blog added successfully!</span>");
            redirect(base_url() . "backend/blog/view-comments/" . $post_id);
        }

        $data["title"] = "Add Post Comment - Post Comments Management ";
        $data["post_id"] = $post_id;

        $this->load->view('backend/blogs/add-post-comment', $data);
    }

    public function edit_post_comment($post_id, $comment_id) {

        /*         * checking admin is logged in or not ** */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }
        /*         * using the email template model ** */

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
            if (in_array('10', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }

        $post_comment = strip_slashes($this->input->post("inputComment"));

        /*
         *
         * Post request check
         *
         */

        if ($post_comment != "") {
            $post_id = $this->input->post('post_id');
            $comment_id = $this->input->post("comment_id");
            $arr_blog_comment = array();

            $arr_blog_comment["comment"] = $post_comment;
            $arr_blog_comment["status"] = "" . $this->input->post("inputPublishStatus");

            $this->blog_model->update_comment($arr_blog_comment, array("post_id" => $post_id, "comment_id" => $comment_id));
            $this->session->set_userdata("msg", "<span class='success'>Comment updated successfully!</span>");
            redirect(base_url() . "backend/blog/view-comments/" . $post_id);
        }

        $post_comment_info = $this->blog_model->getPostComments("*", array("comment_id" => $comment_id));

        $data["title"] = "Update Post Comments - Post Comments Management ";
        $data["post_id"] = $post_id;
        $data["comment_id"] = $comment_id;
        $data["arr_post_comment_info"] = $post_comment_info[0];

        $this->load->view('backend/blogs/edit-post-comment', $data);
    }

    public function add_post_data() {
        if (!$this->common_model->isLoggedInfront()) {
            redirect('sign-in');
        }

        $post_title = $this->input->post("inputName");
        if ($this->input->post("inputName") != "" && $this->input->post('inputPostShortDescription') != '' && $this->input->post('inputPostPageTitle') != '' && $this->input->post('inputPostKeywords') != '') {
            if ($_FILES['blog_image']['name'][0] != '') {
                $config['file_name'] = time();
                $config['upload_path'] = './media/backend/blog_image/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '5000000000';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('blog_image')) {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_userdata('images_error', $error['error']);
                    redirect(base_url() . 'blog/add-post-front');
                    die;
                } else {
                    $this->load->library('image_lib');
                    $data = array('upload_data' => $this->upload->data());
                    $image_data = $this->upload->data();
                    $file_name = $image_data['file_name'];
                    $image_data = $this->upload->data();
                    $width = 500;
                    $height = 150;
                    $config = array(
                        'source_image' => $image_data['full_path'],
                        'new_image' => 'media/backend/blog_image/thumb/',
                        'maintain_ration' => false,
                        'width' => $width,
                        'height' => $height
                    );
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                }
            }
            $arr_post_data = array(
                "post_title" => ($this->input->post('inputName')),
                "lang_id" => addslashes($this->input->post('lang_id')),
                'post_short_description' => ($this->input->post('inputPostShortDescription')),
                'post_content' => ($this->input->post('inputPostDescription')),
                'page_title' => mysql_escape_string($this->input->post('inputPostPageTitle')),
                'post_tags' => ($this->input->post('inputPostTags')),
                'post_keywords' => ($this->input->post('inputPostKeywords')),
                'category_id' => ($this->input->post('inputParentCategory')),
                'blog_image' => $file_name,
                'posted_by' => $this->session->userdata("user_id"),
                'posted_on' => date("Y-m-d H:i:s"),
                'status' => '0'
            );
            $new_post_id = $this->blog_model->insertNewPost($arr_post_data);
            $rel_id = $new_post_id;
            $url = $post_title;
            $arr_update_url = array("type" => 'blog-post', "rel_id" => $rel_id);
            $arr_update_url['url'] = preg_replace('/[^A-Za-z0-9\-_.]/', '', str_replace(" ", "-", $url));
            $this->common_model->insertURI($arr_update_url, $where_field);
            $this->session->set_userdata("blog_success", "Your blog has been posted successfully.");
            redirect(base_url() . 'blog');
        }
        $arr_categories = $this->blog_model->getCategories();
        $data["title"] = "Add Blog Post";
        $data["arr_categories"] = $arr_categories;
        $data['arr_get_language'] = $this->common_model->getLanguages();
        $this->load->view('front/includes/header', $data);
//        $this->load->view('front/includes/top-nav', $data);
        $this->load->view('front/blogs/add-blog', $data);
        $this->load->view('front/includes/footer');
    }

    public function lang_post($post_id) {
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
            if (in_array('10', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }
        $lang_id = intval($this->input->post("lang"));
        if ($lang_id > 0) {
            $post_id = intval($this->input->post("post_id"));
            if ($post_id > 0) {
                $is_inserted = $this->input->post('is_inserted');
                if ($is_inserted == 'Y') {
                    $arr_to_update = array(
                        "post_title" => mysql_escape_string($this->input->post('inputName')),
                        'page_title' => mysql_escape_string($this->input->post('inputPageTitle')),
                        'post_keywords' => mysql_escape_string($this->input->post('inputPostKeywords')),
                        'post_tags' => mysql_escape_string($this->input->post('inputPostTags')),
                        'post_short_description' => mysql_escape_string($this->input->post('inputPostShortDescription')),
                        'post_content' => mysql_escape_string($this->input->post('inputPostDescription')),
                    );

                    $where_field = array("post_id" => $post_id, "lang_id" => $lang_id);

                    $this->blog_model->updateLanguageValuesForPost($arr_to_update, $where_field);
                } else {

                    $arr_to_insert = array(
                        "post_title" => mysql_escape_string($this->input->post('inputName')),
                        'page_title' => mysql_escape_string($this->input->post('inputPageTitle')),
                        'post_keywords' => mysql_escape_string($this->input->post('inputPostKeywords')),
                        'post_tags' => mysql_escape_string($this->input->post('inputPostTags')),
                        'post_short_description' => mysql_escape_string($this->input->post('inputPostShortDescription')),
                        'post_content' => mysql_escape_string($this->input->post('inputPostDescription')),
                        'post_id' => $post_id,
                        'lang_id' => $lang_id
                    );

                    $this->blog_model->insertLanguageValuesForPost($arr_to_insert);
                }
            }

            redirect(base_url() . "backend/blog/");
        }

        $arr_languages = $this->common_model->getNonDefaultLanguages();


        $data["title"] = "Blog Module - Post Language Management ";
        $data["post_id"] = $post_id;
        $data["arr_languages"] = $arr_languages;
        $this->load->view('backend/blogs/lang-post', $data);
    }

    public function get_language_for_posts() {
        $lang_id = intval($this->input->post('lang'));
        $post_id = intval($this->input->post('post_id'));

        if ($lang_id > 0) {
            $this->load->model("blog_model");
            $arr_language_values = $this->blog_model->getLangValForPost("" . $lang_id, "" . $post_id);

            $arr_to_return = array();

            if (count($arr_language_values) > 0) {
                $arr_to_return["post_title"] = $arr_language_values[0]["post_title"];
                $arr_to_return["page_title"] = $arr_language_values[0]["page_title"];
                $arr_to_return["post_keywords"] = $arr_language_values[0]["post_keywords"];
                $arr_to_return["post_tags"] = $arr_language_values[0]["post_tags"];
                $arr_to_return["post_short_description"] = $arr_language_values[0]["post_short_description"];
                $arr_to_return["post_content"] = $arr_language_values[0]["post_content"];
                $arr_to_return["is_inserted"] = "Y";
            } else {
                $arr_to_return["post_title"] = '';
                $arr_to_return["page_title"] = '';
                $arr_to_return["post_keywords"] = '';
                $arr_to_return["post_tags"] = '';
                $arr_to_return["post_short_description"] = '';
                $arr_to_return["post_content"] = '';
                $arr_to_return["is_inserted"] = "N";
            }
            echo json_encode($arr_to_return);
        }
    }

    public function switchLanguage() {
        if ($this->input->post('lang_id') != "") {
            $this->session->set_userdata('lang_id', $this->input->post('lang_id'));
        } else {
            $this->session->set_userdata('lang_id', 17);
        }
    }

    public function blogCategory() {
        /*         * checking admin is logged in or not ** */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }
        /** using the email template model ** */
        $data = $this->common_model->commonFunction();
        $arr_privileges = array();

        //checking for admin privilages
        if ($data['user_account']['role_id'] != 1) {
            $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $data['user_account']['role_id']));
            if (count($arr_privileges) > 0) {
                foreach ($arr_privileges as $privilege) {
                    $user_privileges[] = $privilege['privilege_id'];
                }
            }
            $arr_login_admin_privileges = $user_privileges;
            if (in_array('10', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }

        $post_categories = $this->blog_model->getCategories('', '', 'category_id desc');
        $data["title"] = "Manage Blog Categories";
        $data["arr_post_categories"] = $post_categories;
        $this->load->view('backend/blogs/blog-category', $data);
    }

    public function add_category() {

        /*         * checking admin is logged in or not ** */
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }
        /*         * using the email template model ** */

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
            if (in_array('10', $arr_login_admin_privileges) == FALSE) {
                /* an admin which is not super admin not privileges to access Manage Role
                 * setting session for displaying notiication message. */
                $this->session->set_userdata("msg", "<span class='error'>You doesn't have priviliges to  manage user!</span>");
                redirect(base_url() . "backend/home");
                exit();
            }
        }

        $post_category = $this->input->post("category_name");

        if ($post_category != "") {

            $arr_blog_comment = array();
            $arr_blog_comment["category_name"] = ($post_category);
            $arr_blog_comment["parent_id"] = "" . $this->input->post("parent_category");
            $arr_blog_comment["created_on"] = date("Y-m-d H:i:s");
            $inserted_id = $this->blog_model->add_category($arr_blog_comment);

            $arr_to_insert_uri = array(
                "url" => str_replace(" ", "-", ($this->input->post('blog_model'))),
                'type' => 'blog-category',
                'rel_id' => $inserted_id,
            );

            $this->blog_model->insertURI($arr_to_insert_uri);
            $this->session->set_userdata("msg", "<span class='success'>Category added successfully!</span>");

            redirect(base_url() . "backend/blog/blog-category");
        }

        $arr_categories = $this->blog_model->getCategories();

        $data["title"] = "Add Blog Category";
        $data["arr_categories"] = $arr_categories;
        $this->load->view('backend/blogs/add_category', $data);
    }

    public function year_details($year, $month) {
        $data['global'] = $this->common_model->getGlobalSettings();

        $year_info = $this->blog_model->getYearsForBlog('*', array('year' => $year, 'month' => $month));
        if (count($year_info) > 0) {
            $data['header'] = array("title" => $year_info[0]['post_title'],
                "keywords" => $year_info[0]['post_keywords'],
                "description" => $year_info[0]['post_short_description']);
        }
        $data['year_info'] = $year_info;
        $data['category_tree'] = $this->getBlogCategoriesTreeStructure();
        $data['blog_posts'] = $this->blog_model->getYearsForBlog('', array('year' => $year, 'month' => $month), '', '');
        $data['site_title'] = 'Blog';
        $this->load->view('front/includes/header', $data);
//        $this->load->view('front/includes/top-nav', $data);
        $this->load->view('front/blogs/blog-home1', $data);
        $this->load->view('front/includes/footer');
    }

    public function checkCategoryName() {
        //checking admin is logged in or not
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }
        $data = $this->common_model->commonFunction();
        $category_name = $this->input->post('category_name');
        $parent_category_id = $this->input->post('parent_category');
        $get_category_list = $this->blog_model->getCategoryList($parent_category_id, $category_name);
        if (count($get_category_list) > 0) {
            echo "false";
        } else {
            echo "true";
        }
    }

    public function checkEditCategoryName() {
        //checking admin is logged in or not
        if (!$this->common_model->isLoggedIn()) {
            redirect(base_url() . "backend/login");
        }
        $data = $this->common_model->commonFunction();
        $category_name = $this->input->post('category_name');
        $old_category_name = $this->input->post('old_category_name');
        $parent_category_id = $this->input->post('parent_category');
        $get_category_list = $this->blog_model->getCategoryList($parent_category_id, $category_name);

        if (count($get_category_list) > 0) {
            if ($get_category_list[0]['category_name'] == $old_category_name) {
                echo "true";
            } else {
                echo "false";
            }
        } else {
            echo "true";
        }
    }

    function findExtension($filename) {
        $filename = strtolower($filename);
        $exts = explode(".", $filename);
        $file_name = '';
        for ($i = 0; $i <= count($exts) - 2; $i++) {
            $file_name .=$exts[$i];
        }
        $n = count($exts) - 1;
        $exts = $exts[$n];
        $arr_return = array(
            'file_name' => $file_name,
            'ext' => $exts
        );
        return $arr_return;
    }

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */