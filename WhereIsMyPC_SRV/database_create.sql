create database whrim_db;

use whrim_db;

create table if not exists main_tbl(
	uid int not null auto_increment, 
	pc_id varchar(255) not null,
	conn_ip varchar(15)not null,
	conn_date datetime not null,
	pc_dscrptn varchar(255) not null,
	primary key(uid)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;