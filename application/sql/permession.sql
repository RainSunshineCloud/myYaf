create table permession_zone(
	`id` int unsigned not null auto_increment primary key,
	`name` varchar(50) not null comment "标题",
	`update_time` int unsigned not null comment "更新时间",
	`create_time` int unsigned not null comment "创建时间",
	unique key name_unique (`name`)
) engine=innodb default charset=utf8 comment "权限作用域表";


create table permession(
	`id` int unsigned not null auto_increment primary key,
	`ssid` varchar(255) not null comment "标识符",
	`name` varchar(50) not null comment "标题",
	`zid` int unsigned not null comment "作用域id",
	`permession` tinyint unsigned not null comment "权限",
	`other_permession` tinyint unsigned not null comment "其他人权限",
	`update_time` int unsigned not null comment "更新时间",
	`create_time` int unsigned not null comment "创建时间",
	unique key ssid_gid_unique (`ssid`,`zid`)
) engine=innodb default charset=utf8 comment "权限表";

create table permession_role(
	`pid` int unsigned not null comment "权限id",
	`rid` int unsigned not null comment "角色id",
	`status` tinyint unsigned not null comment "状态：1.有效，2:无效",
	`update_time` int unsigned not null comment "更新时间",
	`create_time` int unsigned not null comment "创建时间",
	unique key ssid_gid_unique (`pid`,`rid`)
) engine=innodb default charset=utf8 comment "权限角色关联表";

create table role(
	`id` int unsigned not null auto_increment primary key,
	`name` varchar(50) not null comment "角色名",
	`update_time` int unsigned not null comment "更新时间",
	`create_time` int unsigned not null comment "创建时间",
	unique key name_unique (`name`)
) engine=innodb default charset=utf8 comment "角色表";

create table user_role (
	`id`	int unsigned not null auto_increment primary key,
	`user_id` int unsigned not null comment "用户id",
	`role_id` int unsigned not null comment "角色id",
	`status` tinyint unsigned not null comment "状态：1.有效，2:无效",
	`update_time` int unsigned not null comment "更新时间",
	`create_time` int unsigned not null comment "创建时间",
	unique key role_id_user_id_unique (`user_id`,`role_id`)
)engine=innodb default charset=utf8 comment "用户角色关联表";