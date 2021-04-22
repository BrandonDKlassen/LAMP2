<?php
    declare(strict_types=1);
    //session_start();

    /*
    File: userLogin.php
    Group: 8
    Members: Brandon Klassen, Andrew Todd, Katherine Ziomek
    Purpose of file:
    -Contains the functions that handle users logging in and logging out.
    */

    // create a function that will display the login form
    function displayLoginForm() {
        echo '
        <p class="lead">Please sign in:</p>
        <form action="./index.php" method="POST">
            <div class="form-group">
                <label for="hr_id">ID</label>
                <input type="hr_id" class="form-control" id="hr_id" name="hr_id" placeholder="Enter your HR ID">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password">
            <button type="submit" class="mt-3 btn btn-info btn-lg full-width-element" name="userLogin">Log In</button>
        </form>        
        ';
    }

    //create a function that will verify the user that is logging in
    function verifyUser() {
        //connect to the database
        //include to the database made in index.php
		
		$mysqli = new mysqli(DBHOST,DBUSER,DBPW,DBDB);

        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . 
        $mysqli->connect_errno . ") " .
        $mysqli->connect_error;
        }

		$hr_id = $mysqli->real_escape_string($_POST['hr_id']);
		$password = $mysqli->real_escape_string($_POST['password']);
			   
        $result = $mysqli->query("select * from users where hr_id='$hr_id' and password='$password';");

        if ($result->num_rows == 1) { 
            if(!isset($_SESSION['hr_id'])) {
                $_SESSION['hr_id'] = $hr_id;
            }
            echo "
                <div class='alert alert-primary' role='alert'>
                    Thank you for logging in!
                </div>
            ";
            // commented for now
            // //check which page the user is currently on
            // if ($_SESSION['page'] == "home" or $_SESSION['page'] == "upload") {
            //     //header("Location: ./index.php");
            // }
            // else {
            //     //header("Location: ../index.php");
            // }
		} 
        else {
			echo "
            <div class='alert alert-danger' role='alert'>
                Your login credentials are invalid. Please try again.
            </div>";
            unset($_SESSION['hr_id']);
		}
    }

    //create a function that will handle logging out
    function logoutUser() {
        if(isset($_POST['logoutButton'])) {
            if ($_SESSION['page'] == "home" or $_SESSION['page'] == "upload") {
                session_destroy();
                header("Location: ./index.php");
            }
            else {
                session_destroy();
                header("Location: ./index.php");
            }
        }
    }

    //create a function that will output a logout button
    function displayLogoutButton() {
        if(isset($_SESSION['hr_id'])) {
            //check which page the user is currently on
            if ($_SESSION['page'] == "home" or $_SESSION['page'] == "upload") {
                echo '
                <div class="container">
                    <div class="row justify-content-end">                
                        <form action="./index.php" method="POST">
                            <button type="submit" class="mt-3 btn btn-danger btn-lg" name="logoutButton">Log Out</button>
                        </form>
                    </div>
                </div>          
                ';
            }
            else {
                echo '
                <div class="container">
                    <div class="row justify-content-end">                
                        <form action="../index.php" method="POST">
                            <button type="submit" class="mt-3 btn btn-danger btn-lg" name="logoutButton">Log Out</button>
                        </form>
                    </div>
                </div>                
                ';
            }
        }
    }

    //call the logout function
    logoutUser();

?>



