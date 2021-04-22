<?php
/*
    File: insertData.php
    Group: 8
    Members: Brandon Klassen, Andrew Todd, Katherine Ziomek
    Purpose: reads a csv file and inserts the contents into the database
*/

// comment out or remove when external file upload is ready
//$tempFile = "employees.csv";

//insertData($tempFile);

// create query and execute it
function insertData($fileLocation) {

    // create database connection
    $db_conn = connectDB();

    // SQL query - clear previous employee data
    //$querySQL = "TRUNCATE employees; ALTER TABLE employees AUTO_INCREMENT=12345;";
    $querySQL = "DELETE FROM employees";

    // prepare query
    $stmt = $db_conn->prepare($querySQL);

    // prepare error check
    if(!$stmt) {
        echo "<p>Error: ".$db_conn->errorCode()."<br>Message: ".implode($db_conn->errorInfo())."</p><br>";
        exit(1);
    }

    // execute query in database
    $status = $stmt->execute();

    // execute error
    if(!$status) {
        echo "<p>Error: ".$stmt->errorCode()."<br>Message: ".implode($stmt->errorInfo())."</p><br>";
        exit(1);
    }

    // setup for reading and storing the csv file data
    $dataArray = [];   

    // Open the file.
    $fileHandle = fopen($fileLocation, "r");

    // read the data into an array
    while (($row = fgetcsv($fileHandle, 0, ",")) !== FALSE) {

        // store header row
        if (count($dataArray) == 0) {
            $dataArray[0] = $row;
        }
        else {
            // data has same number of elements as header and has no blank required fields
            if (count($row) == count($dataArray[0]) && $row[1] !== "" && $row[2] !== "" && $row[1] !== "" && $row[4] !== "" && $row[5] !== "" && $row[6] !== "" && $row[7] !== "") {
                $duplicate = false;
                // check for duplicate names
                for($i = 1; $i < count($dataArray); $i++) {
                    // check last_name
                    if ($row[0] == $dataArray[$i][0]) {
                        // check first name
                        if ($row[1] == $dataArray[$i][1]) {
                            // duplicate name found                            
                            echo "Duplicate name found (" . $row[0] . ", " . $row[1] . ") Employee will not be inserted<br>";
                            $duplicate = true;
                        }
                    }   
                }
                
                // name is not a duplicate
                if (!$duplicate) {
                    $dataArray[count($dataArray)] = $row;
                    // excluding insert of header row
                    if (count($dataArray) > 1) {

                        // insert into the database
                        // SQL query - insert into employee table
                        $querySQL = "INSERT INTO employees (last_name, first_name, middle_initial, birth_date, gender, hire_date, initial_level, employment_type) VALUES (:last_name, :first_name, :middle_initial, :birth_date, :gender, :hire_date, :initial_level, :employment_type);";

                        // prepare query
                        $stmt = $db_conn->prepare($querySQL);

                        // assign values to insert variables    
                        $data = array(":last_name" => $row[0], ":first_name" => $row[1], ":middle_initial" => $row[2], ":birth_date" => $row[3], ":gender" => $row[4], ":hire_date" => $row[5], ":initial_level" => $row[6], ":employment_type" => $row[7]);

                        // prepare error check
                        if(!$stmt) {
                            echo '<p>Error: '.$db_conn->errorCode().'<br>Message: '.implode($db_conn->errorInfo()).'</p>';
                            exit(1);
                        } 

                        // execute query in database
                        $status = $stmt->execute($data);

                        // execute error check
                        if(!$status) {
                            echo '<p>Error: '.$stmt->errorCode().'<br>Message: '.implode($stmt->errorInfo()).'</p>';
                            exit(1);
                        }   
                        else {
                            // echo "Employee #".(count($dataArray)-1)." successfully inserted into database<br>";
                        }
                    }
                }
            }
            else { // row has different number of elements than headers or missing required field
                echo "Invalid data, will not be inserted<br>";
            }
        }
    }

    // close database connection
    $db_conn = null;    
}
?>