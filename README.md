# Punctual

A cloud based attendance application that follows the general functionality of an attendance application, however provides general purpose use for anyone who wishes to use the attendance features provided.  Along with the basic functionality is a QR code implementation, which aims to provide convenience for both admin and non-admin users.

## Web Link: http://ec2-54-173-102-15.compute-1.amazonaws.com/Punctual/Punctual/home.php

## Group Members
- Omair Farooqui
- Siddharth Tripathi
- Jay Patel
- Eric Tsim

## Description

This is a attendance application built using PHP and HTML. It is hosted on an AWS EC2 ubuntu instance. 
The application allows admins to create your own rooms for users to join. Once users join rooms their information starts being logged which include the timestamps of the clock in and the duration spent in the room. The clock out timestamp is only generated once the user clicks the leave button in the room. All the logging information and user information is stored on the Amazon RDS MySQL database. The logging information is also available to admin users when they are in their room. 

## Commands
Commands used to run webserver on EC2 ubuntu instance:
```
sudo apt-get update
sudo apt-get install apache2
sudo apt-get install php libapache2-mod-php php-mysql php-curl php-gd php-json php-zip php-mbstring
sudo service apache2 restart
sudo apt install phpmyadmin
    select apache by pressing space bar, tab, and then enter
    next navigate to the cancel button by using tab and once hovering over cancel press enter to prevent setting up a mysql
navigate to the default directory on the ec2 instance (use command: cd ..)
navigate to directory /var/www/html
sudo git clone https://github.com/omairf/Punctual.git
```
