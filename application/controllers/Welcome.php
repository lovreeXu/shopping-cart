<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
 		$sql = "SELECT * FROM products";
 		$data['res'] = $this->db->query($sql)->result_array();
 		$this->load->view('index.html',$data);
 	}
	//查询商品结果函数
	public function searchSubmit()
	{
		$type = $_POST['usertype'];
		$value = $_POST['search'];
		if($type==NULL || $value==NULL)
		{
			redirect("Welcome/index");
		}
		else
		{
			$sql = "SELECT * FROM products";
			$data['res'] = $this->db->select('*')->from('products')->where($type,$value)->get()->result_array();
			$this->load->view('index.html',$data);
		}
	}

	public function test()
	{


			$people = array("Bill"=>1, "Steve"=>2, array("Mark"=>3), array("Mark"=>3));

			echo current($people) . "<br>";
			if(is_array(end($people)))
			{
				var_dump(end($people));
			}


	}

	public function addToCart()
	{
		if(isset($_POST['pro_id']) && isset($_POST['pro_num']))
		{
			$pro_id = $_POST['pro_id'];
			$pro_name = $_POST['pro_name'];
			$pro_num = $_POST['pro_num'];
			$u_name = $this->session->userdata('shops_username');
			//根据用户名字查询用户id
			$sql = "SELECT u_id FROM users WHERE u_name = ?";
			$res_uid = $this->db->query($sql,$u_name)->result_array();

			//插入数据到数据库
			$sql_2 = "INSERT INTO `cart`(`c_id`, `u_id`, `p_id`, `p_num`, `p_name`) VALUES (NULL,?,?,?,?)";
			$this->db->query($sql_2,array(intval($res_uid[0]['u_id']),$pro_id,$pro_num,$pro_name));

			echo json_encode( array('result' => '1', 'pro_id' => $pro_id, 'pro_num' => $pro_num ) );
		}
		else
		{
			redirect("Welcome/index");
			// $u_name = $this->session->userdata('shops_username');
			//
			// $sql = "SELECT u_id FROM users WHERE u_name = ?";
			// $res_uid = $this->db->query($sql,$u_name)->result_array();
			//
			// $sql_2 = "INSERT INTO `cart`( `u_id`, `p_id`, `p_num`) VALUES (?,?,?)";
			// $this->db->query($sql_2,array(intval($res_uid[0]['u_id']),3,1));
			//
			// var_dump($res_uid[0]['u_id']);
		}
	}
}
