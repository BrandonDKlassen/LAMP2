<?php

    declare(strict_types=1);
    session_start();
    
    /*
    File: index.php
    Group: 8
    Members: Brandon Klassen, Andrew Todd, Katherine Ziomek
    Purpose of file:
    -A landing page for the user where they can generate the CSV file of employees, and navigate to other pages.
    */

    //include files
    define('__ROOT__', dirname(__FILE__));

    require_once("./php/createEmployees.php");  
    require_once("./php/redirect.php");
    require_once("./php/userLogin.php");    
    require_once("./php/dbConnect.php"); 
        
    // set the page
    if (!isset($_SESSION['page'])){
        $_SESSION['page'] = "home";
    }

    //uncomment for debugging
    // echo "Post vars:<br>";
    // var_dump($_POST);
    // echo "<br><br>";
    // echo "Session vars:<br>";
    // var_dump($_SESSION);
    // echo "<br><br>";

    displayLogoutButton();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">

        <!--include a stylesheet-->
        <link rel="stylesheet" href="css/style.css"> 
        
        <title>INFO5094</title>
    </head>

    <body class="text-dark bg-light">
        <div class="container vertical-center-element">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-2">Welcome to HR Simulator</h1>
                </div>
                <div class="col-lg-4">
        <?php
  
        //check if the user has signed in
        if (isset($_POST['userLogin'])){
            //verify the user
            verifyUser();
            //if the user is verified,          
            //set a session variable
            if(isset($_SESSION['hr_id'])){
                //output the homepage form
                homepageForm();  
            }
            //if the user is not verified,
            //return them to the homepage, display login form
            else{
                displayLoginForm();
            }
        }
        //check if the user is returning from the employee table page
        elseif (isset($_POST['returnHome'])) { 
            $_SESSION['page'] = "home";
            //output the homepage form
            homepageForm();  
        }  
        //output once the user has created employee data
        //and check if the user is trying to upload the file
        elseif (isset($_POST['createEmployees']) or ($_SESSION['page'] == "upload")) { 
            //call the createEmployeeData function
            createEmployeeData();

            //only display error messages if a user selects "Upload"
            if ($_SESSION['page'] == "upload") {

                if (!isset($_SESSION['status'])){
                    $_SESSION['status'] = "";
                    uploadForm();
                }

                if (!isset($_SESSION['uploadOutput'])){
                    $_SESSION['uploadOutput'] = [];
                    uploadForm(); 
                }
                else {
                    $err_msgs = $_SESSION['uploadOutput'];
                    displayErrors($err_msgs);
                    if ($_SESSION['status'] == "OK") {
                        //viewData();
                    } else{
                        uploadForm(); 
                    }
                              
                }   
                
                if (!isset($_SESSION['status'])){
                    $_SESSION['status'] == "";
                    uploadForm();
                    } 
                else {
                    $statusmsg = $_SESSION['status'];
                    displayStatus($statusmsg);
                    if ($_SESSION['status'] == "OK") {
                        viewData();
                    }
                }    
            }
            elseif (isset($_POST['createEmployees'])) {
                uploadForm();
            }
        }
        //check if the user has already signed in
        elseif(isset($_SESSION['hr_id'])){            
            //output the homepage form
            homepageForm();  
        }
        // Outputs to the page if it is the first time the user has visited the page
        elseif (!isset($_POST['createEmployees']) or isset($_POST['returnHome'])) { 
            $_SESSION['page'] = "home";
            //output the homepage form
            displayLoginForm();
        }

        //Create a function that will output the form that appears on the homepage/landing page of index.php
        function homepageForm() {
            echo '
            <p class="lead mt-5">In order to begin, we need some employee data.</p>
            <form action="./index.php" method="POST">
                <button type="submit" name="createEmployees" class="btn btn-info btn-lg">Click Here to Generate Employees</button>
            </form>
            ';
        }

        //Create a function that will output a form for the user to use to upload a file to the server
        function uploadForm() {
            echo '
            <!--Outputs to the page if the user has generated employee data-->
            <p class="lead mt-5">Thank you for generating employee data. <br>Please choose a file with a .CSV extension to upload to the employee database:</p>
            <!--display another form to upload the data-->

            <form action="php/upload.php" method="POST" enctype="multipart/form-data" class="mt-3">
                <input type="hidden" name="MAX_FILE_SIZE" value="2000000" class="form-control-file" />
                <input type="file" name="uploads" />
                <button type="submit" name="uploadFiles" value="Upload" class="mt-3 max-width-element-50 btn btn-info btn-lg">Upload</button>
            </form>';	
        }

        //Create a function that will output a form for the user to use to navigate to viewData.php
        function viewData() {
            echo '
            <p class="lead mt-5">Would you like to view the employee data?</p>
            <!--display another form-->

            <form action="php/viewData.php" method="POST" class="mt-3">
                <button type="submit" name="viewEmployees" class="btn btn-info">Click Here to See Employee Data</button>
            </form>';	
        }
        
        //display any errors that occur when uploading the file
        function displayErrors(array $error_msg){
            echo "<p>\n";
            
            foreach($error_msg as $v){
                echo $v."<br>\n";                
            }
            echo "</p>\n";
        }     
        //display any errors that occur when moving the file to permanant storage
        function displayStatus($status){
                        
         if ($status == "FailMove"){
                
		  echo "File upload failed - failed to move file to permanent storage";
          uploadForm();
		  } else if ($status == "NoDirectory"){ 
		  echo "File upload failed - The permanent storage directory does not exist or is not accessible";
          uploadForm();
		  } else if($status == "OK"){ 		    
		  echo "File uploaded and moved to permanent storage successfully";	
		  }		
        }  


        ?>   
        
                </div>
            </div>
        </div>
   </body>
</html>
