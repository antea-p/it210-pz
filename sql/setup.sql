-- https://stackoverflow.com/a/29929677
SET NAMES utf8mb4;

USE recipe_site;
CREATE USER 'recipe_site_user'@'localhost' IDENTIFIED BY 'password';
GRANT ALL ON recipe_site.* TO 'recipe_site_user'@'localhost';

create table Users
(
	id int auto_increment primary key,
	username varchar(64) not null unique,
	password_hash varchar(256) not null, -- SHA1
	is_admin bit not null
);

INSERT INTO Users (username, password_hash, is_admin)
  VALUES ('admin', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 1);

create table Categories
(
    id int auto_increment primary key,
    name varchar(64) not null,
    image_url varchar(64) not null
);

insert into Categories (name, image_url)
    values ('Torte', 'static/images/cake.png'),
           ('Browniesi', 'static/images/brownie.png'),
           ('Keksi', 'static/images/cookie.png'),
           ('Pločice', 'static/images/bar.png');

create table Preparation_Difficulties (
    id int auto_increment primary key,
    name varchar(64) not null
);

insert into Preparation_Difficulties (name)
    values ('Lako'),
           ('Srednje'),
           ('Zahtjevno');

create table Recipes
(
    id int auto_increment primary key,
    created_by_user_id int not null references Users(id),
    title varchar(64) not null,
    category_id int not null references Categories(id),
    description varchar(1024) not null,
    preparation_time_minutes int not null,
    preparation_difficulty_id int not null references Preparation_Difficulties(id),
    number_of_servings int not null
);

insert into Recipes (created_by_user_id, title, category_id, description, preparation_time_minutes, preparation_difficulty_id, number_of_servings)
  values (1, 'Sacher torta', 1, 'Recept za originalnu Sacher tortu.', 140, 3, 8),
         (1, 'Oreo brownie', 2, 'Brownie s Oreo keksima.', 50, 1, 12),
         (1, 'Čokoladni keksi', 3, 'Američki keksi s komadićima tamne, mliječne i bijele čokolade.', 40, 2, 16),
         (1, 'Kikiriki pločice', 4, 'Ukusne energetske pločice sa kikiriki maslacem. Bogate proteinima i zdravim mastima za postojanu energiju.', 20, 1, 4),
         (1, 'Kuglof od čokolade', 1, 'Ponekad su najjednostavniji recepti najbolji. Takva je priča s ovim kuglofom. Sočan, mekan, pun okusa,
           ovaj kuglof će vam postati prvi izbor za pripremiti kad vam dođu gosti..', 45, 1, 6);

create table Recipe_Ingredients
(
    id int auto_increment primary key,
    recipe_id int not null references Recipes(id),
    title varchar(64) not null,
    amount varchar(64) not null
);

create table Recipe_Steps
(
    id int auto_increment primary key,
    recipe_id int not null references Recipes(id),
    step varchar(256) not null,
    constraint fk_recipe
      foreign key (recipe_id) references Recipes(id)
      on delete cascade
);


