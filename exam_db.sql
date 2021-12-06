CREATE DATABASE IF NOT EXISTS exam_db;
USE exam_db;


CREATE TABLE teams (
	id INT(3) NOT NULL,
	abbreviation VARCHAR(3),
	city VARCHAR(30),
	conference VARCHAR(10),
	division VARCHAR(20),
	full_name VARCHAR(40),
	name VARCHAR(20),
	PRIMARY KEY(id)
);