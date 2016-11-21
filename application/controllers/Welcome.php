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

	public function test1()
	{
		$pro_id = 1000000000;
		$sql_1 = "SELECT * FROM cart WHERE p_id = ?";
		$res_sql1 = $this->db->query($sql_1,$pro_id)->result_array();
		var_dump($res_sql1);
			if($res_sql1 == NULL)
			{
				$sql_2 = "INSERT INTO `cart`(`c_id`, `u_id`, `p_id`, `p_num`, `p_name`) VALUES (NULL,?,?,?,?)";
				$this->db->query($sql_2,array(3,$pro_id,1,"a"));
			}
			else {
				echo 1;
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
			$sql_1 = "SELECT * FROM cart WHERE p_id = ? AND u_id = ?";
			$res_sql1 = $this->db->query($sql_1,array($pro_id,intval($res_uid[0]['u_id'])))->result_array();
			if($res_sql1 == NULL)
			{
				$sql_2 = "INSERT INTO `cart`(`c_id`, `u_id`, `p_id`, `p_num`, `p_name`) VALUES (NULL,?,?,?,?)";
				$this->db->query($sql_2,array(intval($res_uid[0]['u_id']),$pro_id,$pro_num,$pro_name));
			}
			else
			{
				$p_new_num = $pro_num + $res_sql1[0]["p_num"];
				$sql_3 = "UPDATE cart SET p_num = ? WHERE p_id = ?";
				$this->db->query($sql_3,array($p_new_num,$pro_id));
			}

			echo json_encode( array('result' => '1', 'pro_id' => $pro_id, 'pro_num' => $res_sql1 ) );
		}
		else
		{
			redirect("Welcome/index");
		}
	}
}
