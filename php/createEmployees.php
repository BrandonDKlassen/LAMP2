<?php
    /*
    File: createEmployees.php
    Group: 8
    Members: Brandon Klassen, Andrew Todd, Katherine Ziomek
    Purpose of file:
    This file will be used to generate the CSV file containing random employee data
    */

/*create a function that will be used to generate random data*/
function randomVal($array) {
    /*find the array length to find the max value*/
    $length = sizeof($array);
    return rand(0,$length-1);
}

/*create a function that will create random but valid dates*/
function randomDate($dateArray) {
    /*first check the year*/
    $length = sizeof($dateArray);
    $selected_year = $dateArray[rand(0,$length-1)];

    /*create an array for months*/
    $months = array();
    for ($i = 1; $i < 13; $i++){
        array_push($months, $i);
    }

    /*create an array for days*/
    $days = array();
    for ($i = 1; $i < 32; $i++){
        array_push($days, $i);
    }
    
    /*month*/
    $selected_month = $months[rand(0,11)];
    /*If month is a single digit, add a 0 to the beginning */
    if($selected_month <= 9) {
        $selected_month = "0" . $selected_month;
    }

    /*date*/
    /*check if the month is february*/
    if($selected_month == 2) {
        /*check if it is a leap year*/
        if(($selected_year % 4) == 0) {
            $length = 29;
        }
        else {
            $length = 28;
        }
    }
    /*check if the month has 31 days*/
    elseif($selected_month == 1 || $selected_month == 3 
    || $selected_month == 5 || $selected_month == 7 || $selected_month == 8 
    || $selected_month == 10 || $selected_month == 12) {
        $length = 31;
    }
    /*30 days*/
    else {
        $length = 30;
    }
    /*find a random day*/
    $selected_day = $days[rand(0,$length-1)];
    /*If day is a single digit, add a 0 to the beginning */
    if($selected_day <= 9) {
        $selected_day = "0" . $selected_day;
    }

    /*format the date*/
    $selected_date = $selected_year . "-" . $selected_month . "-" . $selected_day;
    /*return the selected date*/
    return $selected_date;
}


/*create a function that will generate the necessary information
and export the employees data to a .CSV file */
function createEmployeeData() {
    /*Create arrays to store the information that will be added to the employees array*/
    /*first_names array*/
    $first_names = array("Alex","Blake","Taylor","Jordan","Ryan",
    "Cameron","Charlie","Ezra","Blair","Casey",
    "Dallas","Dana","Carey","Jamie","Hayden",
    "Kai","Ash","Riley","Rowan","Sawyer");

    /*last_names array*/
    $last_names = array("Smith","Johnson","Williams","Jones","Brown",
    "Davis","Miller","Hall","Allen","Young",
    "Hernandez","King","Wright","Lopez","Stewart",
    "Price","Bennett","Wood","Henderson","Anderson");

    /*middle_initial array*/
    $middle_initial = range('A', 'Z');

    /*birth_years array*/
    $birth_years = array();
    for ($i = 1950; $i < 2001; $i++){
        array_push($birth_years, $i);
    }

    /*hire_years array*/
    $hire_years = array();
    for ($i = 2000; $i < 2022; $i++){
        array_push($hire_years, $i);
    }

    /*genders array*/
    $genders = array("M","F","X");

    /*initial_levels array*/
    $initial_levels = array(1,2,3,4,5);

    /*employment_type array*/
    $employment_type = array("FT","PT");

    /*Create an array to store employee data */
    $employees = array();
    /*create a for loop - loop 400 times to create 400 employees*/
    for ($i = 0; $i < sizeof($first_names); $i++){ 
        for ($j = 0; $j < sizeof($last_names); $j++){ 
            /*create random data and push to the array*/
            $new_employee = array(
                $last_names[$j],
                $first_names[$i],
                $middle_initial[randomVal($middle_initial)],
                randomDate($birth_years),
                $genders[randomVal($genders)],
                randomDate($hire_years),
                $initial_levels[randomVal($initial_levels)],
                $employment_type[randomVal($employment_type)]
            );

            array_push($employees, $new_employee);
        }
    }    

    /*export the employees data to a .CSV file*/
    /*Create/open the file*/
    $fp = fopen('employees.csv', 'w');

    /*Set header information*/
    //header("Content-Type:text/plain"); 
    //header("Content-Disposition:attachment;filename=employees.csv"); 
    fputcsv($fp, array('last_name','first_name','middle_initial', 'birth_date', 'gender', 'hire_date', 'initial_level', 'employment_type'));
    /*Output the employees array to the .CSV file*/
    foreach ($employees as $fields) {
        fputcsv($fp, $fields);
    }
    /*Close the file*/
    fclose($fp);
}

/*Call the function to create the employee dataset 
Note: this can be commented out if the function is called in 
another file*/
// createEmployeeData();

?>