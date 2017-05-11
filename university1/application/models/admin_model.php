<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_Model extends CI_Model {  
	//function to get admin list from the database 
	public function getAdminDetails($user_id='')
	{	
		$this->db->select('u.user_id,u.user_name,u.first_name,u.last_name,u.user_email,u.user_status,u.user_type,u.register_date,u.role_id,u.gender,r.role_name');
		$this->db->from('mst_users as u');
		$this->db->join('mst_role as r','u.role_id = r.role_id','left');
		if($user_id!='')
		{
			$this->db->where("u.user_id",$user_id);	
		}
		$this->db->where("u.user_type",2);
		$result = $this->db->get();
                $error=$this->db->_error_message(); 
                $error_number=$this->db->_error_number();
                if ($error) {
                    $controller = $this->router->fetch_class();
                    $method = $this->router->fetch_method();
                    $error_details=array(
                        'error_name'=>$error,
                        'error_number'=>$error_number,
                        'model_name'=>'admin_model',
                        'model_method_name'=>'getAdminDetails',
                        'controller_name'=>$controller,
                        'controller_method_name'=>$method
                    );
                    $this->common_model->errorSendEmail($error_details);
                    redirect(base_url() . 'page-not-found');
                }
                return $result->result_array();
	}
	
	/* Updating Blocked user list */
	public function updateBlockedUserFile($absolute_path,$status,$user_id)
	{	
		/* checking file is exists or not */
		if(!file_exists($absolute_path."media/front/user-status/blocked-user"))
		{
			/*if not update the first blocked user to file */
			$blocked_user=array();
			if($status=='2')
			{
				$blocked_user[0]=$user_id;
			}
		}
		else
		{
			/* getting all blocked user from file*/ 
			$blocked_user=$this->read_file($absolute_path."media/front/user-status/blocked-user");
			if($status=='2')
			{
				if(!in_array($user_id,$blocked_user))
				{
					/* Adding new blocked user to file*/
					array_push($blocked_user,$user_id);
				}
			}
			else
			{
				$key = array_search($user_id,$blocked_user);				
				if($key!==false){
					/* Removing the user from bloked list */
					unset($blocked_user[$key]);
				}
			}
		}
		$this->write_file($absolute_path."media/front/user-status/blocked-user",$blocked_user);
                $error=$this->db->_error_message(); 
                $error_number=$this->db->_error_number();
                if ($error) {
                    $controller = $this->router->fetch_class();
                    $method = $this->router->fetch_method();
                    $error_details=array(
                        'error_name'=>$error,
                        'error_number'=>$error_number,
                        'model_name'=>'admin_model',
                        'model_method_name'=>'updateBlockedUserFile',
                        'controller_name'=>$controller,
                        'controller_method_name'=>$method
                    );
                    $this->common_model->errorSendEmail($error_details);
                    redirect(base_url() . 'page-not-found');
                }
	}	
	
	/* Updating Deleted user list */
	public function updateDeletedUserFile($absolute_path,$user_id)
	{	
		/* checking file is exists or not */
		if(!file_exists($absolute_path."media/front/user-status/deleted-user"))
		{
			/*if not update the first deleted user to file */
			$deleted_user=array();
			$deleted_user[0]=$user_id;
		}
		else
		{
			/* getting all deleted user from file*/ 
			$deleted_user=$this->read_file($absolute_path."media/front/user-status/deleted-user");
			if(!in_array($user_id,$deleted_user))
			{
				/* Adding new deleted user to file*/
				array_push($deleted_user,$user_id);
			}
		}
		$this->write_file($absolute_path."media/front/user-status/deleted-user",$deleted_user);
                $error=$this->db->_error_message(); 
                $error_number=$this->db->_error_number();
                if ($error) {
                    $controller = $this->router->fetch_class();
                    $method = $this->router->fetch_method();
                    $error_details=array(
                        'error_name'=>$error,
                        'error_number'=>$error_number,
                        'model_name'=>'admin_model',
                        'model_method_name'=>'updateDeletedUserFile',
                        'controller_name'=>$controller,
                        'controller_method_name'=>$method
                    );
                    $this->common_model->errorSendEmail($error_details);
                    redirect(base_url() . 'page-not-found');
                }
	}
	
	public function write_file($file_path,$file_data)
	{
		#Opening the file for writing.
//                $file_boolean=true;
		$file_path=fopen($file_path,"write");
		#wrinting into file
		fwrite($file_path,serialize($file_data));
		#closing the file for writing.
		fclose($file_path);
                $error=$this->db->_error_message(); 
                $error_number=$this->db->_error_number();
                if ($error) {
                    $controller = $this->router->fetch_class();
                    $method = $this->router->fetch_method();
                    $error_details=array(
                        'error_name'=>$error,
                        'error_number'=>$error_number,
                        'model_name'=>'admin_model',
                        'model_method_name'=>'write_file',
                        'controller_name'=>$controller,
                        'controller_method_name'=>$method
                    );
                    $this->common_model->errorSendEmail($error_details);
                    redirect(base_url() . 'page-not-found');
                }
	} 
	
	public function read_file($file_path)
	{
		$file_content = file_get_contents($file_path);
		return unserialize($file_content);
                $error=$this->db->_error_message(); 
                $error_number=$this->db->_error_number();
                if ($error) {
                    $controller = $this->router->fetch_class();
                    $method = $this->router->fetch_method();
                    $error_details=array(
                        'error_name'=>$error,
                        'error_number'=>$error_number,
                        'model_name'=>'admin_model',
                        'model_method_name'=>'read_file',
                        'controller_name'=>$controller,
                        'controller_method_name'=>$method
                    );
                    $this->common_model->errorSendEmail($error_details);
                    redirect(base_url() . 'page-not-found');
                }
	}
}
