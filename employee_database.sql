    -- /*
    -- File: employee_database.sql
    -- Group: 8
    -- Members: Brandon Klassen, Andrew Todd, Katherine Ziomek
    -- Purpose of file: The file used to create the database, the DB user, grant privileges, and create tables.
    -- */

-- Delete the database if it exists
DROP DATABASE if exists employee_database;

-- Create the new database
create database employee_database CHARACTER SET utf8 COLLATE utf8_bin;

-- Delete the database user account if it exists
DROP USER if exists 'hr_user'@'localhost';

-- Create the new user
CREATE USER 'hr_user'@'localhost' IDENTIFIED BY 'lamp2Project!';
GRANT ALL PRIVILEGES on employee_database.* TO 'hr_user'@'localhost';

-- Select the database to use
use employee_database;

-- Create tables

-- Create levels table
create table levels (
        level_id int NOT NULL,
        CONSTRAINT pk_levels PRIMARY KEY (level_id)
);

-- Create salaries table
create table salaries (
        salary_id int AUTO_INCREMENT NOT NULL,
        level_id int NOT NULL,
        date_min date NOT NULL,
        date_max date NOT NULL,
        salary float(7,2) NOT NULL, 
        CONSTRAINT pk_salaries PRIMARY KEY (salary_id),
	CONSTRAINT fk_salaries_levels FOREIGN KEY (level_id) REFERENCES levels(level_id)
);

-- Create employees table
create table employees (
	employee_id int AUTO_INCREMENT NOT NULL,
	last_name varchar(50) NOT NULL,
	first_name varchar(50) NOT NULL,
	middle_initial varchar(2),
	birth_date date NOT NULL,
	gender varchar(1) NOT NULL,
	hire_date date NOT NULL,
	initial_level int NOT NULL,
	employment_type varchar(2) NOT NULL,
	CONSTRAINT pk_employees PRIMARY KEY (employee_id),
	CONSTRAINT fk_employees_levels FOREIGN KEY (initial_level) REFERENCES levels(level_id)
);

-- Make the employee_id value start at a higher number (to be the employee number)
ALTER TABLE employees AUTO_INCREMENT = 12345;

-- Create users table
create table users (
	user_id int AUTO_INCREMENT NOT NULL,
	hr_id int NOT NULL,
	last_name varchar(50) NOT NULL,
	first_name varchar(50) NOT NULL,
	password varchar(50) NOT NULL,
	CONSTRAINT pk_users PRIMARY KEY (user_id)
);

-- Insert data into the tables

-- Insert data into levels table
insert into levels (level_id) 
	values 
		(1),
		(2),
		(3),
		(4),
		(5),
		(6),
		(7),
		(8),
		(9);

-- Insert data into salaries table
insert into salaries (level_id, date_min, date_max, salary)
values
(1, '2000-01-01', '2017-12-31', 60000),
(2, '2000-01-01', '2017-12-31', 62000),
(3, '2000-01-01', '2017-12-31', 64000),
(4, '2000-01-01', '2017-12-31', 66000),
(5, '2000-01-01', '2017-12-31', 68000),
(6, '2000-01-01', '2017-12-31', 70000),
(7, '2000-01-01', '2017-12-31', 72000),
(8, '2000-01-01', '2017-12-31', 74000),
(9, '2000-01-01', '2017-12-31', 76000),
(1, '2018-01-01', '2020-03-13', 61200),
(2, '2018-01-01', '2020-03-13', 63240),
(3, '2018-01-01', '2020-03-13', 65280),
(4, '2018-01-01', '2020-03-13', 67320),
(5, '2018-01-01', '2020-03-13', 69360),
(6, '2018-01-01', '2020-03-13', 71400),
(7, '2018-01-01', '2020-03-13', 73440),
(8, '2018-01-01', '2020-03-13', 74000),
(9, '2018-01-01', '2020-03-13', 76000),
(1, '2020-03-14', '2020-12-31', 62730),
(2, '2020-03-14', '2020-12-31', 64821),
(3, '2020-03-14', '2020-12-31', 66912),
(4, '2020-03-14', '2020-12-31', 69003),
(5, '2020-03-14', '2020-12-31', 71094),
(6, '2020-03-14', '2020-12-31', 73185),
(7, '2020-03-14', '2020-12-31', 75276),
(8, '2020-03-14', '2020-12-31', 77367),
(9, '2020-03-14', '2020-12-31', 79458),
(1, '2021-01-01', '2021-12-31', 63357.3),
(2, '2021-01-01', '2021-12-31', 65469.21),
(3, '2021-01-01', '2021-12-31', 67581.12),
(4, '2021-01-01', '2021-12-31', 69693.03),
(5, '2021-01-01', '2021-12-31', 71804.94),
(6, '2021-01-01', '2021-12-31', 73916.85),
(7, '2021-01-01', '2021-12-31', 76028.76),
(8, '2021-01-01', '2021-12-31', 78140.67),
(9, '2021-01-01', '2021-12-31', 80252.58);

-- Insert data into users table
insert into users (hr_id, last_name, first_name, password) 
	values 
		(123456,'Doe','Jane','password'),
		(987654,'Smith','John','password'),
		(111111,'Wayne','Bruce','imbatman'),
		(222222,'Baggins','Bilbo','hobbiton');
