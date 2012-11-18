3w8r2s9c4p


ALTER TABLE  `cdb_users` ADD  `taobao_name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `real_name`

CREATE TABLE cdb_upload_files(
upload_id MEDIUMINT( 8 ) UNSIGNED AUTO_INCREMENT PRIMARY KEY ,
upload_path VARCHAR( 255 ) NOT NULL ,
upload_time INT( 10 ) UNSIGNED DEFAULT 0,
source_filename VARCHAR( 100 ) NOT NULL ,
user_id MEDIUMINT( 8 ) UNSIGNED DEFAULT 0,
STATUS TINYINT( 1 ) UNSIGNED DEFAULT 0
) CHARSET = utf8;


create table cdb_taobao_sales(
	upload_id MEDIUMINT(8) UNSIGNED default 0,
	order_sn char(20) not null,
	taobao_name varchar(100) not null,
	alipay varchar(100) not null,
	goods_fee decimal(10,2) not null default 0.00,
	shipping_fee decimal(3,2) not null default 0.00,
	pay_point MEDIUMINT(8) not null default 0,
	total_fee decimal(10,2) not null default 0.00,
	rebate_point MEDIUMINT(8) not null default 0,
	paid_fee decimal(10,2) not null default 0.00,
	paid_point MEDIUMINT(8) not null default 0,
	order_status varchar(100) not null,
	buyer_msg varchar(255) not null,
	buyer_name char(20) not null,
	address varchar(255) not null,
	shipping_type char(20) not null,
	phone char(20) not null,
	cellphone char(20) not null,
	create_time char(20) not null,
	paid_time char(20) not null,
	goods_name varchar(255) not null,
	close_reason varchar(60) not null
) charset=utf8;

ALTER TABLE  `cdb_users` ADD  `rebate` INT UNSIGNED NOT NULL DEFAULT  '0' AFTER  `birthday_month`