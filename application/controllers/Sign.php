<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sign	 extends CI_Controller {
	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->database();
			$this->load->helper('form');
	}
	public function signIn()
	{
		$this->load->view('signIn.html');
	}
	public function signUp()
	{
		$this->load->view("signUp.html");
	}
	//登录结果处理页面
	public function signInSubmit()
	{
		// $sql = "";
		// $this->db->query($sql);
		 $this->load->library('session');
		 $username = $this->input->post("username",TRUE);
		 $password = $this->input->post("password",TRUE);
		 if ($username!=NULL && $password!=NULL)
		 {
			 //查询数据库该帐号是否存在
			 $sql = "SELECT * FROM users WHERE u_name = ? AND u_pass = ?";
			 $res = $this->db->query($sql, array($username,$password));
			 //echo $res->num_rows();
			 if ($res->num_rows()==1)
			 {
				$sess_data = array('shops_username' => $username);
				$this->session->set_userdata($sess_data);
			 	redirect("Welcome/index");
			 }
			 else
			 {
				 echo '<script type="text/javascript">alert("Logon failure: unknown username or bad password！");</script>';
				 echo '<script type="text/javascript">window.location.href="signIn"</script>';
			 }
		 }
		 else
		 {
			 redirect("Sign/signIn");
		 }
	}
	//注册结果处理页面
	public function signUpSubmit()
	{
		$username = $this->input->post("username",TRUE);
		$password = $this->input->post("password",TRUE);
		if ($username!=NULL && $password!=NULL)
		{
			//判断该用户名是否已注册
			$sql = "SELECT * FROM users WHERE u_name = ? ";
			$res = $this->db->query($sql,$username);
			if($res->num_rows()==1)
			{
				echo '<script type="text/javascript">alert("该账户已经注册，请直接登录！");</script>';
				echo '<script type="text/javascript">window.location.href="signIn"</script>';
			}
			else
			{
				//将注册帐号插入数据库
				$data = array(
					'u_name' => $username,
					'u_pass' => $password
				);
				$this->db->insert("users",$data);
				echo '<script type="text/javascript">alert("success");</script>';
				echo '<script type="text/javascript">window.location.href="../Welcome"</script>';
			}
		}
	}
}
