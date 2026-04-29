drop database if exists `CollectionKeeper`;

create database `CollectionKeeper`;

use `CollectionKeeper`;

create table `users` (
    id int not null AUTO_INCREMENT primary key,
    name varchar(20) not null,
    email varchar(255) not null,
    password varchar(100) not null
);

insert into `users` (`id`,`name`,`email`,`password`)
            values  (null, "Gideon", "spamlakemang@gmail.com", "Karveel@2005!");

create table `vinyl` (
    artist varchar(255) not null,
    album varchar(255) not null
);

create table `cd` (
    artist varchar(255) not null,
    album varchar(255) not null
);

create table `cassette` (
    artist varchar(255) not null,
    album varchar(255) not null
);