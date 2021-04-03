# Punctual

Description Here

## Web Link: http://3.92.136.22/

## Description

This is a polling application built using python and html with flask. It is hosted on an AWS EC2 ubuntu instance. 
The application allows you to create your own polls and store them in the database (which was made with AWS RDS using MySQL). 
Along with being able to create polls you are able to view polls made by yourself and other and vote in them, which updates the results in the results section. 

## Services

- The first service is being able to create your own poll 
- The second service is being able to vote in any poll
- The third service is being able to view results for all polls.

## Commands
Commands I used to run webserver on EC2 ubuntu instance:
```
git clone https://github.com/UOITEngineering3/assignment1winter2021-omairf.git
sudo apt-get update
sudo apt-get install python3
sudo apt-get install python3-pip
sudo pip3 install flask
sudo pip3 install pymysql
sudo pip3 install flask_mysqldb
sudo apt-get install mysql-client
sudo apt-get install python3-flask
sudo apt-get install python3-pymysql
cd assignment1winter2021-omairf
pip3 install --user -r requirements.txt
sudo python3 main.py
```

If you want run EC2 instance in background:
```
nohup sudo python3 main.py &
```

To Create Database:
```
CREATE DATABASE poll;
use poll;
CREATE TABLE questions(
    questionID INT NOT NULL,
    question VARCHAR(1000) NOT NULL,
    optionA VARCHAR(128) NOT NULL,
    optionB VARCHAR(128) NOT NULL,
    votesForA INT(11) DEFAULT NULL,
    votesForB INT(11) DEFAULT NULL,
    PRIMARY KEY (questionID)
    );
quit;
```
