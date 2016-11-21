USE shops;
-- (1) 用户管理：用户资料的记录与维护
CREATE TABLE users (
  u_id      INT         NOT NULL AUTO_INCREMENT PRIMARY KEY,
  u_name    VARCHAR(30) NOT NULL,
  u_pass    VARCHAR(30) NOT NULL,
  u_balance INT         NOT NULL DEFAULT 0
  -- 余额
)
  CHARACTER SET = utf8;

INSERT shops.users(`u_id`,`u_name`, `u_pass`, `u_balance`) VALUES
  (1, 'admin', 'admin', 0);

-- (2) 商品信息管理：商品信息的录入与维护
-- (3) 商品查询：根据多种条件（如分类、名称等）查询商品信息
CREATE TABLE products (
  p_id          INT         NOT NULL PRIMARY KEY AUTO_INCREMENT,
  p_name        VARCHAR(30) NOT NULL,
  p_category    VARCHAR(30) NOT NULL,
  p_description VARCHAR(255),
  p_price       INT         NOT NULL
)
  CHARACTER SET = utf8;

-- (4) 购物车管理：用户可在购物车中添加和删除商品，提交生成订单
CREATE TABLE cart (
  c_id  INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  u_id  INT NOT NULL,
  p_id  INT NOT NULL,
  p_num INT NOT NULL,
  p_name VARCHAR(30) NOT NULL
)
  CHARACTER SET = utf8;

-- (5) 订单查询：用户查询自己的订单信息
CREATE TABLE orders (
  o_id        INT    NOT NULL PRIMARY KEY AUTO_INCREMENT,
  u_id        INT    NOT NULL,
  total_price BIGINT NOT NULL
)
  CHARACTER SET = utf8;

CREATE TABLE orders_details (
  od_id   INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  o_id    INT NOT NULL,
  p_id    INT NOT NULL,
  p_name VARCHAR(30) NOT NULL,
  p_price INT NOT NULL,
  p_nums  INT NOT NULL
)
  CHARACTER SET = utf8;
