# LAMP2
A repository for our LAMP 2 group project. This project is a proof of concept for an HR website to view and change employee information.

## Group Members
Brandon Klassen
<br>
Andrew Todd
<br>
Katherine Ziomek
<br>

## Configuring the Project

### Permissions
When testing the project, please ensure that the php/uploads folder has rwx permissions. Please run the following command:
<br><br>
sudo chmod 777 php/uploads

### Database
In order to configure our database, please navigate to the folder as index.php, sign into mysql with root permissions, and use the following command:
<br><br>
source employee_database.sql;

### SSL
Your system will need to have SSL enabled for our HTTPS redirect to work. Please run the following commands in the commandline:<br><br>

sudo a2enmod ssl
<br><br>
sudo a2ensite default-ssl.conf
<br><br>
sudo systemctl restart apache2

## Running the Website

### index.php
In order to use the website, please navigate to the following path in your browser:
<br><br>
/localhost/LAMP2
<br><br>
This will re-direct you to the HTTPS version of our website. Accept the use of the self-signed certificate for the re-direct to work.

### Signing in
In order to access the website, you will need to sign in using one of the users in our database. The login form asks for an ID number, and a password. You can use any of the following users to sign in:
1. ID: "123456" Password: "password"
2. ID: "987654" Password: "password"
3. ID: "111111" Password: "imbatman"
4. ID: "222222" Password: "hobbiton"

### CSV Files
Part of the project requires that we upload a .csv file to the website. The employees.csv file that we create is generated in the home directory of the project. In this case, in the following directory:
<br><br>
/var/www/html/LAMP2
<br><br>
Please select the employees.csv file in the home directory to add to the project. Files that are added to permanent storage are added to the following directory:
<br><br>
/var/www/html/LAMP2/php/uploads
