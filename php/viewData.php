<?php
    declare(strict_types=1); // strict type checking
    session_start();

    //Session variables
    $file = $_SESSION['file'];
    //includes
    require_once("./dbConnect.php");

    //make sure to redirect the user if they get to this page without logging in
    if(!isset($_SESSION['hr_id'])){
        header("Location: ../index.php");
    }
    else {
        $_SESSION['page'] = "viewData";
        require_once("./userLogin.php");
        displayLogoutButton();
    }

    //uncomment for debugging
    // echo "Post vars:<br>";
    // var_dump($_POST);
    // echo "<br><br>";
    // echo "Session vars:<br>";
    // var_dump($_SESSION);
    // echo "<br><br>";

    require_once("./determineButton.php");

    determineButton();

?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <!-- 
            File: viewData.php
            Group: 8
            Members: Brandon Klassen, Andrew Todd, Katherine Ziomek
            Purpose of file:
            This file will be used to output a table with the employees listed in the CSV file
        -->

        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> 
        <script src="../js/script.js"></script> 

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="../css/bootstrap.min.css">

        <!--include a stylesheet-->
        <link rel="stylesheet" href="../css/style.css"> 
        <title>Raw Employee Data Output</title>
    </head>
    <body>
        <!--using Bootstrap grids and custom CSS to output the table-->
        <div class='container'>
            <div class='row'>
                <div class='col-lg-3'>
                    <!--form to return back to index.php-->
                    <form action="../index.php" method="POST">
                        <button type="submit" name="returnHome" class="mt-5 max-width-element-80 btn btn-info">Return Home</button>
                    </form>   
                </div>
                <div class='col-lg-6'>
                    <h1 class='text-center display-4 mt-3'>Employee Data</h1>
                </div>
                <div class='col-lg-3'></div>
            </div>
            <hr class='bg-info'>
            <div class='row'>
                <div class='col-md-3'>
                    <h4>Select an Employee:</h4>
                    <form method="POST" id="employeeSelect">
                    <?php
                        //output the employee name drop down
                        displayEmployeeDropDown();
                    ?>
                        <!--button-->
                        <button type="submit" name="employeeSelect" class="mt-4 max-width-element-80 btn btn-info">Confirm Selection</button>
                        <p id='errorMessages' class='mt-3 text-danger'></p>
                    </form>
                </div>
                <!--container for the employee form-->
                <div class='col-md-6'>
                    <!--Employee Form-->
                    <form action="viewData.php" method="post">
                        <!--employee_id-->
                        <div class="form-group">
                            <label for="employee_id">Employee ID</label>
                            <input type="text" class="form-control" id="employee_id_input" name="employee_id" maxlength=100 value="" readonly="readonnly">
                        </div>   
                        <!--first_name-->
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name_input" name="first_name" maxlength=100 value="">
                        </div>   
                        <!--last_name-->
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name_input" name="last_name" maxlength=100 value="">
                        </div>  
                        <div class="row form-group">
                            <!--middle_initial-->
                            <div class="col">
                                <label for="middle_initial">Middle Initial</label>
                                <input type="text" class="form-control" id="middle_initial_input" name="middle_initial" maxlength=100 value="">
                            </div>
                            <!--gender-->
                            <div class="col">
                                <label for="gender">Gender Marker</label>
                                <input type="text" class="form-control" id="gender_input" name="gender" maxlength=100 value="">
                            </div>   
                        </div>
                        <!--birth_date-->
                        <div class="form-group">
                            <label for="birth_date">Birth Date</label>
                            <input type="text" class="form-control" id="birth_date_input" name="birth_date" maxlength=100 value="">
                        </div> 
                        <!--hire_date-->
                        <div class="form-group">
                            <label for="hire_date">Hire Date</label>
                            <input type="text" class="form-control" id="hire_date_input" name="hire_date" maxlength=100 value="">
                        </div> 
                        <div class="row form-group">
                            <!--initial_level-->
                            <div class="col">
                                <label for="initial_level">Initial Level</label>
                                <input type="text" class="form-control" id="initial_level_input" name="initial_level" maxlength=100 value="">
                            </div>  
                            <!--employment_type-->
                            <div class="col">
                                <label for="employment_type">Employment Type</label>
                                <input type="text" class="form-control" id="employment_type_input" name="employment_type" maxlength=100 value="">
                            </div>  
                        </div>
                        <!--Control Buttons-->
                        <div class='row'>
                            <button type="submit" name="clear" value="clear" class="m-3 full-width-element btn btn-info">Clear All</button>
                            <button type="submit" name="update" value="update" class="m-3 full-width-element btn btn-info">Update</button>
                            <button type="submit" name="savenew" value="savenew" class="m-3 full-width-element btn btn-info">Save New Employee</button>
                        </div>
                    </form>
                </div>
                <!--container for the earnings information for part 3 of the project-->
                <div class='col-md-3'>
                    <h4>Earnings Information</h4>
                    <p>Coming Soon!</p>
                </div>
            </div>
        </div>

        <?php
            //PHP functions that will be used on this page

            //Create a function that will display the employee names in a dynamically generated drop down
            function displayEmployeeDropDown() {
                //make a connection to the database
                $db_conn = connectDB();

                if (!$db_conn){
                    //output an error if the connection was unsuccessful
                    echo "<p>Error connecting to the database</p>\n";
                } 
                else {
                    //prepare the select statement
                    //create an SQL query
                    $sqlSelectQuery = "select employee_id, first_name, last_name
                        from employees order by first_name, last_name";

                    //prepare query
                    $stmt = $db_conn->prepare($sqlSelectQuery);

                    if (!$stmt){
                        echo "<p>Error preparing to read data from the database</p>\n";
                    } 
                    else {
                        //execute the statement
                        $status = $stmt->execute();

                        if(!$status){
                        echo "<p>Error reading data from the database</p>\n";
                        } 
                        else {
                            //uncomment for testing
                            //echo "<p>Number of rows found is " . $stmt->rowCount() . "</p>\n";

                            //if entries found
                            if ($stmt->rowCount() > 0){
                                //create a drop down
                                echo "<select class='form-select' id='employeeSelectDropDown' name='employeeIDSelectDropDown'>";
                                echo "<option selected>All Employees</option>";

                                //output employee names
                                //fetch the information from the database and output to the drop down
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){  
                                        echo "<option value='".$row['employee_id']."'>";
                                        echo $row['first_name']." ".$row['last_name'];
                                        echo "</option>";
                                    }
                                
                                echo "</select>";
                            }
                        }
                    }
                }
            }

            /*
            This function was used to display the CSV file data in a table - not called anywhere in the code, but kept in case we need it in the future
            */
            function displayCSVData() {
                //Open the file.
                $fileHandle = fopen($file, "r");
                //make a count to read through the first line of the csv to make table headings
                $count = 0;
                //open a table
                echo "<table class='full-width-element table table-sm table-striped m-3'>";
                //Loop through the CSV rows.
                while (($row = fgetcsv($fileHandle, 0, ",")) !== FALSE) {
                    //Dump out the row for the sake of clarity.
                // var_dump($row);
                //open a table row for each customer dataset
                echo "<tr>"; 
                
                    //for each $row value, create a new row of data
                foreach ($row as $value) {   
                    //use the first line of the csv to generate tableheads
                    if($count < 1){
                        echo "<th>".$value."</th>";
                    } //Once past the first row, output data as <td> instead of <th>
                    else {
                        echo "<td>".$value."</td>";       
                    }
                }
                    //close the table row at the end of each data set
                    echo "</tr>";
                    $count++; //count is only used for the first row 
                }
                //close the table
                echo "</table>";
            }

        ?>
    </body>
</html>
