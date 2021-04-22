<?php
    /*
    File: determineButton.php
    Group: 8
    Members: Brandon Klassen, Andrew Todd, Katherine Ziomek
    Purpose of file:
    - determines which button is clicked (update or save new employee) and then completes the selected action.
    */

    // includes
    require_once("./dbConnect.php");

    // check to see if update info button has been clicked
    function determineButton() {

        // create database connection
        $db_conn = connectDB();

        // if 'update' button was clicked
        if($_SERVER['REQUEST_METHOD'] == "POST" and (isset($_POST['update']) || isset($_POST['savenew']))) {
            //var_dump($_POST);
            
            // read input fields
            $id = $_POST['employee_id'];
            $fName = trim($_POST['first_name']);
            $lName = trim($_POST['last_name']);
            $mName = strtoupper(trim($_POST['middle_initial']));
            $gender = strtoupper(trim($_POST['gender']));
            $bDate = trim($_POST['birth_date']);
            $hDate = trim($_POST['hire_date']);
            $level = intval(trim($_POST['initial_level']));
            $type = strtoupper(trim($_POST['employment_type']));
            
            // verify and validate input fields
            $error = false;
            $error_msg = "";

            // check first name
            if (strlen($fName) < 1) {
                $error = true;
                $error_msg = $error_msg . "First name cannot be blank.\\n";
            }
            if (strlen($fName) > 50) {
                $error = true;
                $error_msg = $error_msg . "First name must be 50 characters or less.\\n";
            }

            // check last name
            if (strlen($lName) < 1) {
                $error = true;
                $error_msg = $error_msg . "Last name cannot be blank.\\n";
            }
            if (strlen($lName) > 50) {
                $error = true;
                $error_msg = $error_msg . "Last name must be 50 characters or less.\\n";
            }

            // check middle initials
            if (strlen($mName) > 2) {
                $error = true;
                $error_msg = $error_msg . "Only allowed up to two middle initials.\\n";
            }

            // check gender
            if (strlen($gender) < 1) {
                $error = true;
                $error_msg = $error_msg . "Gender cannot be blank.\\n";
            }
            if (strlen($gender) > 1) {
                $error = true;
                $error_msg = $error_msg . "Please use one letter for gender.\\n";
            }

            // check birth date
            if (strlen($bDate) !== 10) {
                $error = true;
                $error_msg = $error_msg . "Please use YYYY-MM-DD format for birth date.\\n";
            }

            // check hire date
            if (strlen($hDate) !== 10) {
                $error = true;
                $error_msg = $error_msg . "Please use YYYY-MM-DD format for hire date.\\n";
            }
            // check level
            if ($level < 1 || $level > 9) {
                $error = true;
                $error_msg = $error_msg . "Initial level must be between 1-9.\\n";
            }            
        
            // check employment type
            if ($type !== "PT" && $type !== "FT") {
                $error = true;
                $error_msg = $error_msg . "Employment type must be FT (full-time) or PT (part-time).\\n";
            }

            // check for duplicate name in the employees table
            // SQL query - check for duplicate name
            /*
            $querySQL = "SELECT COUNT(*) FROM employees WHERE (upper(last_name) = :lName && upper(first_name) = :fName);";

            // asign values to count variables
            $data = array(":lName" => strtoupper($lName), ":fName" => strtoupper($fName));

            // prepare query
            $stmt = $db_conn->prepare($querySQL);            

            // prepare error check
            if(!$stmt) {
                echo "<p>Error: ".$db_conn->errorCode()."<br>Message: ".implode($db_conn->errorInfo())."</p><br>";
                exit(1);
            }

            // execute query in database
            $status = $stmt->execute($data);

            // execute error
            if(!$status) {
                echo "<p>Error: ".$stmt->errorCode()."<br>Message: ".implode($stmt->errorInfo())."</p><br>";
                exit(1);
            }
            
            //var_dump($stmt->fetch(PDO::FETCH_ASSOC));
            // retrieve count result
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // if duplicate name found
            if(intval($row) == 1) {
                $error = true;
                $error_msg = $error_msg . "Duplicate employee name found. Employee name not allowed.\\n";
            }*/

            // if there are no validation errors
            if (!$error) {
                // if update button was clicked
                if (isset($_POST['update'])) {

                    // SQL query - updates employee's data
                    $querySQL = "UPDATE employees SET last_name = :lName, first_name = :fName, middle_initial = :mName, birth_date = :bDate, gender = :gender, hire_date = :hDate, initial_level = :level, employment_type = :type WHERE employee_id = :id;";
                    
                    // assign values to update variables    
                    $data = array(":lName" => $lName, ":fName" => $fName, ":mName" => $mName, ":bDate" => $bDate, ":gender" => $gender, ":hDate" => $hDate, ":level" => $level, ":type" => $type, ":id" => $id);
                }

                // if save new employee button was clicked
                else {
                    // SQL query - creates new employee
                    $querySQL = "INSERT INTO employees (last_name, first_name, middle_initial, birth_date, gender, hire_date, initial_level, employment_type) VALUES (:lName, :fName, :mName, :bDate, :gender, :hDate, :level, :type);";

                    // assign values to insert variables    
                    $data = array(":lName" => $lName, ":fName" => $fName, ":mName" => $mName, ":bDate" => $bDate, ":gender" => $gender, ":hDate" => $hDate, ":level" => $level, ":type" => $type);
                }

                // prepare query
                $stmt = $db_conn->prepare($querySQL);            

                // prepare error check
                if(!$stmt) {
                    echo "<p>Error: ".$db_conn->errorCode()."<br>Message: ".implode($db_conn->errorInfo())."</p><br>";
                    exit(1);
                }

                // execute query in database
                $status = $stmt->execute($data);

                // execute error
                if(!$status) {
                    echo "<p>Error: ".$stmt->errorCode()."<br>Message: ".implode($stmt->errorInfo())."</p><br>";
                    exit(1);
                }
            }
            // validation error(s) found
            else {
                echo '<script>alert("'.$error_msg.'");window.location.href="./viewData.php";</script>';
            }
        }   
            
        // close database connection
        $db_conn = null;  
    }
?>