create table iwww.uzivatel
(
	id_uzivatel int auto_increment
		primary key,
	username varchar(50) not null,
	heslo varchar(100) not null,
	email varchar(100) null,
	telefon varchar(16) null,
	jmeno varchar(50) not null,
	prijmeni varchar(50) not null,
	role_id_role int null,
	constraint UZIVATEL_username
		unique (username),
	constraint role_id_role
		foreign key (role_id_role) references iwww.role (id_role)
);

create table iwww.role
(
	id_role int auto_increment
		primary key,
	nazev varchar(50) not null,
	constraint role_nazev_uindex
		unique (nazev)
);




