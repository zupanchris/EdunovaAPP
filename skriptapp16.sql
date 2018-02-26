# sipavanje baze
# c:\xampp\mysql\bin\mysql.exe -uedunova -pedunova --default_character_set=utf8 < C:\xampp\htdocs\EdunovaAPP\skriptapp16.sql

drop database if exists edunovapp16;
create database edunovapp16 character set utf8 collate utf8_croatian_ci;

#za byethost
#alter database character set utf8 collate utf8_croatian_ci;

use edunovapp16;

create table operater(
sifra int not null primary key auto_increment,
email varchar(50) not null,
lozinka char(32) not null,
ime varchar(50) not null,
prezime varchar(50) not null,
uloga varchar(20) not null
);

create table smjer(
sifra int not null primary key auto_increment,
naziv varchar(50) not null,
cijena decimal(18,2) not null,
upisnina decimal(18,2) not null,
brojsati int not null
); 

create table grupa(
sifra int not null primary key auto_increment,
smjer int not null,
naziv varchar(50) not null,
predavac int not null,
datumpocetka datetime 
);

create table predavac(
sifra int not null primary key auto_increment,
osoba int not null,
placa decimal(18,2)
);

create table polaznik(
sifra int not null primary key auto_increment,
osoba int not null,
brojugovora varchar(20) not null
);

create table osoba(
sifra int not null primary key auto_increment,
oib char(11) not null,
ime varchar(50) not null default 'Ana',
prezime varchar(50) not null,
email varchar(100),
spol boolean
);

create table clan(
grupa int not null,
polaznik int not null
);

#create unique index ix_oib on osoba(oib);

alter table grupa add foreign key (smjer) references smjer(sifra);
alter table grupa add foreign key (predavac) references predavac(sifra);

alter table predavac add foreign key (osoba) references osoba(sifra);

alter table polaznik add foreign key (osoba) references osoba(sifra);

alter table clan add foreign key (grupa) references grupa(sifra);
alter table clan add foreign key (polaznik) references polaznik(sifra);


insert into operater (email,lozinka,ime,prezime,uloga) values 
('mario@edunova.hr', md5('e'),'Mario','Vukić','oper'),
('edunova@edunova.hr', md5('e'),'Eduard','Kuzma','admin');

insert into smjer(sifra,naziv,cijena,upisnina,brojsati) values 
(null,'PHP programiranje',5000.99,500,130),
(null,'Java programiranje',3000.99,200,130),
(null,'Računalni operator',1000,500,100),
(null,'Serviser računala',2000,500,130);

insert into osoba (oib,ime,prezime,email,spol) values
('91178695652','Tomislav','Jakopec','tjakopec@gmail.com',1),
('86050773518','Josip','Abramović','jabramovic95@gmail.com',1),
('96883106136','Ana Maria','Anić','anicanamaria@gmail.com',0),
('71097397278','Vedran','Baričević','veco444@gmail.com',1),
('96009276808','Andi','Bašić','andi.basic10@gmail.com',1),
('93510598970','Ivan','Birovljević','ivanbirovljevic@yahoo.com',1),
('01138682383','Andrija','Buzinac','andrija.buzinac@gmail.com',1),
('65641541514','Miro','Čičerić','miro.ciceric@gmail.com',1),
('94538544747','Ivica','Džambo','jumbo.ivica@gmail.com',1),
('79100142273','Zvonimir','Grizelj','grizelj.zvonimir@gmail.com',1),
('06484435384','Matija','Kiš','matijaa.kis@gmail.com',1),
('15448361225','Damijan','Krešić','damjan1304@gmail.com',1),
('85670558335','Franko','Kulešević','franko.kule97@gmail.com',1),
('08461780173','Leon','Kurtović','kurtovicleon@gmail.com',1),
('12538357064','Ivana','Martinović','imartinovic97@gmail.com',0),
('97444321582','Davor','Posavčević','davor.posavcevic1991@gmail.com',1),
('20314650442','Siniša','Rajković','sinisaos@gmail.com',1),
('09605107163','Bojana','Sarka','bojana.sarka@gmail.com',0),
('27349341183','Chris','Župan','zupan.chris@gmail.com',1),
('54173404752','Matko','Pejić','pejicmatko@gmail.com',1),
('91069362335','Domagoj','Glavačević','glavacevic.d@gmail.com',1),
('36906684671','Davor','Ilišević','davor.ilisevic1@gmail.com',1);

insert into predavac (osoba,placa) values (1,5000);

insert into grupa(naziv,smjer,predavac,datumpocetka) values
('PP16',1,1,'2017-10-28'),
('J17',2,1,'2017-10-28');

insert into polaznik (osoba,brojugovora) values
(2,''),
(3,''),
(4,''),
(5,''),
(6,''),
(7,''),
(8,''),
(9,''),
(10,''),
(11,''),
(12,''),
(13,''),
(14,''),
(15,''),
(16,''),
(17,''),
(18,''),
(19,''),
(20,''),
(21,''),
(22,'');

insert into clan(grupa,polaznik) values 
(1,1), (1,2), (1,3), (1,4), (1,5), (1,6), (1,7), (1,8), (1,9), (1,10), (1,11), (1,12), (1,13), (1,14), (1,15), (1,16), (1,17), (1,18), (1,19), (1,20),(1,21),(2,1),(2,21); 





DELIMITER $$
CREATE FUNCTION pocisti(tekst varchar(50)) RETURNS varchar(50)
begin
return lower(
replace(
replace(
replace(
replace(
replace(replace(upper(tekst),' ',''),'Č','C')
,'Š','S')
,'Đ','D')
,'Ć','C')
,'Ž','Z'));
end;
$$
DELIMITER ;

DELIMITER $$
CREATE FUNCTION email(ime varchar(50),prezime varchar(50)) RETURNS varchar(50)
begin
return concat(left(lower(pocisti(ime)),1),lower(pocisti(prezime)),'@edunova.hr');
end;
$$
DELIMITER ;














