<?php
/*
    File: ajax.php
    Group: 8
    Members: Brandon Klassen, Andrew Todd, Katherine Ziomek
    Purpose: Handles the information sent to the server using AJAX.
*/

    header("Content-Type: application/json");

    //includes
    require_once("./dbConnect.php");

    //get the POST value of the employee ID
    $employee_ID_value = $_POST['employeeIDSelectDropDown'];

    //create an SQL statement to retrieve the employee information from the database

    //make a connection to the database
    $db_conn = connectDB();

    if (!$db_conn){
        //output an error if the connection was unsuccessful
        echo "<p>Error connecting to the database</p>\n";
    }
    else {
        //prepare the select statement
        //create an SQL query
        $sqlSelectQuery = "select e.employee_id, e.last_name, e.first_name, e.middle_initial, e.birth_date, e.gender, e.hire_date, e.initial_level, e.employment_type

        from employees e
        inner join levels l on l.level_id = e.initial_level
        
        where e.employee_id = :selected_employee;
        ";

        $data = array(":selected_employee" => $employee_ID_value);

        //prepare query
        $stmt = $db_conn->prepare($sqlSelectQuery);
        //execute query
        $status = $stmt->execute($data);

        if($status){ //no error
            if ($stmt->rowCount() > 0){ //entry found
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){  
                    //assign values to variables 
                    $last_name = $row['last_name'];
                    $first_name = $row['first_name'];
                    $middle_initial = $row['middle_initial'];
                    $gender = $row['gender'];
                    $birth_date = $row['birth_date'];
                    $hire_date = $row['hire_date'];
                    $initial_level = $row['initial_level'];
                    $employment_type = $row['employment_type'];
                }   
            }
        }
    }

    //send the database information back to the webpage
    echo '{
        "employee_id": "'.$employee_ID_value.'",
        "first_name": "'.$first_name.'",
        "last_name": "'.$last_name.'",
        "middle_initial": "'.$middle_initial.'",
        "gender": "'.$gender.'",
        "birth_date": "'.$birth_date.'",
        "hire_date": "'.$hire_date.'",
        "initial_level": "'.$initial_level.'",
        "employment_type": "'.$employment_type.'"
    }';

?>