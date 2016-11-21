<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

	public function __construct()
	{
			parent::__construct();
      //加载各种库文件
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

		//查询当前用户的订单列表
		$sql_orders = "SELECT * FROM orders WHERE u_id = ?";
		$order_uid = $this->db->query($sql_orders,$u_id)->result_array();
		if($order_uid!=NULL)
		{
			foreach ($order_uid as $value) {

				$o_id = $value["o_id"];
				$total_price = $value["total_price"];
				//根据订单id查询当前每个用户订单的详细内容
				$sql_orders_details = "SELECT * FROM orders_details WHERE o_id = ?";
				$res_sql_details = $this->db->query($sql_orders_details,$o_id)->result_array();
				//加上每条订单的总金额
				$res_sql_details["total_price"] = $total_price;
				//所有数据赋给order_data变量
				$order_data[$o_id] = $res_sql_details;
			}
			$order_all_data["res"] = $order_data;
		}
		else
		{
				$order_all_data["res"] = 1;
		}

		// var_dump($order_all_data);
		$this->load->view("order.html",$order_all_data);
	}

  public function addOrder()
  {
		if(isset($_POST["f_data"]))
		{
			$total_price = 0;
			$data = $_POST['f_data'];
			$u_id = $data[0]["u_id"];

			foreach ($data as $value) {

				$p_num = $value["p_num"];
				$p_id  = $value["p_id"];
				//根据id查询价格
				$sql     = "SELECT p_price FROM products WHERE p_id = ?";
				$p_price = $this->db->query($sql,$p_id)->result_array()[0]["p_price"];

				$total_price += $p_price * $p_num ;
			}
	    $sql = "INSERT INTO `orders`(`u_id`, `total_price`) VALUES (?,?)";
	    $this->db->query($sql,array($u_id,$total_price));
			//订单id
			$o_id = $this->db->insert_id();

			foreach ($data as $item) {

				$p_num = $item["p_num"];
				$p_id  = $item["p_id"];

				//根据价格查询id和name
				$sql = "SELECT p_price FROM products WHERE p_id = ?";
				$p_price = $this->db->query($sql,$p_id)->result_array()[0]["p_price"];
				$sql_n = "SELECT p_name FROM products WHERE p_id = ?";
				$p_name = $this->db->query($sql_n,$p_id)->result_array()[0]['p_name'];

				$sql_d = "INSERT INTO `orders_details`( `o_id`, `p_id`,`p_name`, `p_price`, `p_nums`) VALUES (?,?,?,?,?)";
				$this->db->query($sql_d,array($o_id,$p_id,$p_name,$p_price,$p_num));
			}
	    echo json_encode(array("result"=>1));
		}
		else
		{
			redirect("Sign/signIn");
		}
  }
	public function deleteOrder()
	{
		if(isset($_POST["o_id"]))
		{
			$o_id = $_POST["o_id"];
			//删除对应的orders表内容
			$sql = "DELETE FROM orders WHERE o_id = ?";
			$this->db->query($sql,$o_id);
			//删除对应的orders_details表的内容
			$sql_details = "DELETE FROM orders_details WHERE o_id = ?";
			$this->db->query($sql_details,$o_id);

			echo json_encode(array("result"=>1));
		}
		else
		{
			redirect("Sign/signIn");
		}
	}
	public function orderSubmit()
	{
		if(isset($_POST["o_id"]))
		{
			$o_id = $_POST["o_id"];

			$sql = "SELECT * FROM orders WHERE o_id = ?";
			$res_sql = $this->db->query($sql,$o_id)->result_array();
			$u_id = $res_sql[0]["u_id"];
			$total_price = $res_sql[0]["total_price"];

			$sql_users = "SELECT * FROM users WHERE u_id = ?";
			$res_sql_users = $this->db->query($sql_users,$u_id)->result_array();
			$balance = $res_sql_users[0]["u_balance"];

			if($balance < $total_price)
			{
				echo json_encode(array("result"=>0));
				// echo '<script type="text/javascript">alert("Submit failure: Your balance is not enough！");</script>';
				// echo '<script type="text/javascript">window.location.href="Order/index"</script>';
			}
			else
			{
				$new_balance = $balance - $total_price;
				$sql_new_balance = "UPDATE users SET u_balance = ? WHERE u_id = ?";
				$this->db->query($sql_new_balance,array($new_balance,$u_id));
				echo json_encode(array("result"=>1));
				// echo '<script type="text/javascript">alert("Submit Successfully");</script>';
			}
		}
		else
		{
			redirect("Sign/signIn");
		}
	}
}
