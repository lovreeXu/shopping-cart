<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {

	public function __construct()
	{
			parent::__construct();
			$this->load->helper('url');
			$this->load->database();
			$this->load->helper('form');
			//判断用户是否登录
			$this->load->library('session');
			if ($this->session->userdata('shops_username')==NULL)
			{
				redirect("Sign/signIn");
			}
	}
  public function index()
  {
    //根据用户名字查询用户id
    $u_name = $this->session->userdata('shops_username');
    $sql = "SELECT u_id FROM users WHERE u_name = ?";
    $res_uid = $this->db->query($sql,$u_name)->result_array();
    $u_id = $res_uid[0]['u_id'];

    //查询当前用户的购物车列表
    $sql_cart = "SELECT * FROM cart WHERE u_id = ?";
    $cart_data['res'] = $this->db->query($sql_cart,$u_id)->result_array();
    $this->load->view("cart.html",$cart_data);
  }

	public function removeProduct()
	{
		//$_POST['p_id'] = 3;
		if(isset($_POST['p_id']))
		{
			//根据用户名字查询用户id
			$u_name = $this->session->userdata('shops_username');
			$sql = "SELECT u_id FROM users WHERE u_name = ?";
			$res_uid = $this->db->query($sql,$u_name)->result_array();
			$u_id = $res_uid[0]['u_id'];

			 $p_id = $_POST['p_id'];
			 $sql = "DELETE FROM cart WHERE p_id=? AND u_id=?";
			 $res = $this->db->query($sql,array($p_id,$u_id));
			 echo json_encode( array('result' => $res ));
		}
		else
		{
			redirect("Welcome/index");
		}
	}
}
