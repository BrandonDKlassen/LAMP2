<?php
/*
    File: dbConnect.php
    Group: 8
    Members: Brandon Klassen, Andrew Todd, Katherine Ziomek
    Purpose: Contains the database configuration values, and the function to connect to the database. Will be included on other php pages.
*/

    // MySQL database configuration values
    define('DBHOST', 'localhost');
    define('DBDB', 'employee_database');
    define('DBUSER', 'hr_user');
    define('DBPW', 'lamp2Project!');

    // Open MySQL connection
    function connectDB() {
        // mySQL database statement
        $dsn = "mysql:host=".DBHOST.";dbname=".DBDB.";charset=utf8";
        
        try {
            $db_conn = new PDO($dsn, DBUSER, DBPW);
            
            // return connection to database
            return $db_conn;
        }
        catch (PDOException $e){
            echo"<p>Error opening database:<br>".$e->getMessage()."</p><br>";
            exit(1);
        }
    }    
?>