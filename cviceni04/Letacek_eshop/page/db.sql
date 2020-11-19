create table eshop_produkt
(
	id_produkt int auto_increment
		primary key,
	obrazek varchar(25) not null,
	jmeno varchar(100) not null,
	cena int not null,
	constraint eshop_produkt_jmeno_uindex
		unique (jmeno)
);

create table eshop_role
(
	id_role int auto_increment
		primary key,
	nazev varchar(20) null,
	constraint eshop_role_nazev_uindex
		unique (nazev)
);

create table eshop_uzivatel
(
	id_uzivatel int auto_increment
		primary key,
	username varchar(50) not null,
	heslo varchar(100) not null,
	email varchar(100) null,
	telefon varchar(16) null,
	jmeno varchar(50) not null,
	prijmeni varchar(50) not null,
	role_id_role int not null,
	constraint eshop_uzivatel_username_uindex
		unique (username),
	constraint role_id_role
		foreign key (role_id_role) references eshop_role (id_role)
			on delete cascade
);

create table eshop_kosik
(
	id_uzivatel int not null,
	id_produkt int not null,
	mnozstvi int not null,
	primary key (id_uzivatel, id_produkt),
	constraint produkt_kosik
		foreign key (id_produkt) references eshop_produkt (id_produkt),
	constraint uzivatel_kosik
		foreign key (id_uzivatel) references eshop_uzivatel (id_uzivatel)
);

create table eshop_nakup
(
	datum datetime not null,
	produkt_id_produkt int not null,
	cena int not null,
	uzivatel_id_uzivatel int not null,
	constraint nakup_pk
		unique (datum, produkt_id_produkt, uzivatel_id_uzivatel),
	constraint nakup_uzivatel__fk
		foreign key (uzivatel_id_uzivatel) references eshop_uzivatel (id_uzivatel),
	constraint produkt__fk
		foreign key (produkt_id_produkt) references eshop_produkt (id_produkt)
);

