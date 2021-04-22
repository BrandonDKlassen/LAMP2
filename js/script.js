/*
    File: script.js
    Group: 8
    Members: Brandon Klassen, Andrew Todd, Katherine Ziomek
    Purpose: Contains the AJAX code that will be used in the application.
*/

// create a function that will select one employee from all employees in the database
$(document).ready( function(){

	$("#employeeSelect").submit( function(event){
		$.post("../php/ajax.php", $(this).serialize(), 
			selectEmployee);
		event.preventDefault();	 
	});

	var selectEmployee = function(response){
        console.log($("#employeeSelectDropDown").val());
        //check if the user has not changed the drop down to select an employee
        if ($("#employeeSelectDropDown").val() == "All Employees"){
            //output an error to the page
            $("#errorMessages").removeClass("d-none");
            $("#errorMessages").html("Select an employee.");
        }
        else {
            //if a valid employee was selected, clear the error messages
            $("#errorMessages").addClass("d-none");
            $("#errorMessages").html("");

            //output the chosen user to the input elements
            //$("#errorMessages").html(response.employee_id);
            $('#employee_id_input').val(response.employee_id);
            $('#first_name_input').val(response.first_name);
            $('#last_name_input').val(response.last_name);
            $('#middle_initial_input').val(response.middle_initial);
            $('#gender_input').val(response.gender);
            $('#birth_date_input').val(response.birth_date);
            $('#hire_date_input').val(response.hire_date);
            $('#initial_level_input').val(response.initial_level);
            $('#employment_type_input').val(response.employment_type);

            //send the chosen employee to the backend
            $('input[name="employeeIDSelectDropDown"]').val("");
        }
	};
});