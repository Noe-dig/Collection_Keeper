drop database if exists `CollectionKeeper`;

create database `CollectionKeeper`;

use `CollectionKeeper`;

create table `users` (
    id int not null AUTO_INCREMENT primary key,
    name varchar(255) not null,
    email varchar(255) not null,
    password varchar(255) not null
);

create table `media` (
    id int not null AUTO_INCREMENT primary key,
    artist varchar(255) not null,
    album varchar(255) not null,
    type ENUM('vinyl','cd','cassette') not null,
    cover_url varchar(255) default 'img.png',
    user_id int, 
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);