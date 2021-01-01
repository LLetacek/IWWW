create table kategorie
(
	id_kategorie int auto_increment
		primary key,
	nazev varchar(10) not null,
	constraint kategorie_nazev_uindex
		unique (nazev)
);

create table letiste
(
	id_letiste int auto_increment
		primary key,
	icao varchar(4) not null,
	poloha varchar(50) not null,
	constraint letiste_icao_uindex
		unique (icao)
);

create table opravneni
(
	id_opravneni int auto_increment
		primary key,
	nazev varchar(100) not null
);

create table role
(
	id_role int auto_increment
		primary key,
	nazev varchar(50) not null,
	constraint role_nazev_uindex
		unique (nazev)
);

create table role_opravneni
(
	opravneni_id_opravneni int not null,
	role_id_role int not null,
	primary key (opravneni_id_opravneni, role_id_role),
	constraint role_opravneni_opravneni_id_opravneni_fk
		foreign key (opravneni_id_opravneni) references opravneni (id_opravneni)
			on delete cascade,
	constraint role_opravneni_role_id_role_fk
		foreign key (role_id_role) references role (id_role)
			on delete cascade
);

create table uzivatel
(
	id_uzivatel int auto_increment
		primary key,
	username varchar(50) not null,
	heslo varchar(200) not null,
	email varchar(100) null,
	telefon varchar(16) null,
	jmeno varchar(50) not null,
	prijmeni varchar(50) not null,
	role_id_role int null,
	constraint UZIVATEL_username
		unique (username),
	constraint uzivatel_role_id_role_fk
		foreign key (role_id_role) references role (id_role)
			on delete set null
);

create table letadlo
(
	id_letadlo int auto_increment
		primary key,
	imatrikulace varchar(15) not null,
	nazev varchar(200) not null,
	letiste_id_letiste int not null,
	majitel int not null,
	obrazek varchar(100) not null,
	kategorie_id_kategorie int not null,
	stav varchar(50) not null,
	cena_hodiny int not null,
	constraint letadlo_imatrikulace_uindex
		unique (imatrikulace),
	constraint letadlo_kategorie_id_kategorie_fk
		foreign key (kategorie_id_kategorie) references kategorie (id_kategorie)
			on delete cascade,
	constraint letadlo_letiste_id_letiste_fk
		foreign key (letiste_id_letiste) references letiste (id_letiste)
			on delete cascade,
	constraint letadlo_uzivatel_id_uzivatel_fk
		foreign key (majitel) references uzivatel (id_uzivatel)
			on delete cascade
);

create table opravy
(
	id_oprava int auto_increment
		primary key,
	duvod varchar(500) not null,
	letadlo_id_letadlo int not null,
	datum date not null,
	constraint opravy_letadlo_id_letadlo_fk
		foreign key (letadlo_id_letadlo) references letadlo (id_letadlo)
			on delete cascade
);

create table rezervace
(
	uzivatel_id_uzivatel int not null,
	letadlo_id_letadlo int not null,
	datum timestamp not null,
	cena int not null,
	pocet_hodin int not null,
	primary key (uzivatel_id_uzivatel, datum),
	constraint rezervace_letadlo_id_letadlo_fk
		foreign key (letadlo_id_letadlo) references letadlo (id_letadlo)
			on delete cascade,
	constraint rezervace_uzivatel_id_uzivatel_fk
		foreign key (uzivatel_id_uzivatel) references uzivatel (id_uzivatel)
			on delete cascade
);

