	<?php 

	//  
	// File: upload.php
	// Group: 8
	// Members: Brandon Klassen, Andrew Todd, Katherine Ziomek
	// Purpose of file:
	// This file will be used to validate and upload the CSV file to the server.
	// 

	//require_once("./php/insertData.php");
	
	
	session_start();

	// set the page - store a session variable to track whether the user has entered this page yet
	if (!isset($_SESSION['page'])){
		$_SESSION['page'] = "upload";
	}
	$_SESSION['page'] = "upload";
	
	if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
		$err_msgs = validateFormData();
		$_SESSION['uploadOutput'] = $err_msgs;
		
		
		if (count($err_msgs) >0){
			//displayErrors($err_msgs);
			//send the user back to index.php and display file errors
			header('Location: ../index.php');		
			$_SESSION['status'] = "";
			
			
		} else {
			//processUploads();
		    $status = processUploads();		   
		    $_SESSION['status'] = $status;
			
		}
	} else {
		header('Location: ../index.php');
		
	}
	
	function validateFormData():array {
	    $err_msgs = array();
	    $allowed_exts = array("csv");
	    $allowed_types = array("text/csv");
	    if (isset($_FILES['uploads']) && !empty($_FILES['uploads']['name'])){
	        $up =$_FILES['uploads'];
	        if ($up['error'] == 0){
	            if ($up['size'] == 0){
	                $err_msgs[] = "An empty file was uploaded";
	            }
	            $ext = strtolower(pathinfo($up['name'], PATHINFO_EXTENSION));
	            if (!in_array($ext, $allowed_exts)){
	                $err_msgs[] = "File extension does not indicate that the uploaded file is of an  allowed file type";
	            }
	            if (!in_array($up['type'], $allowed_types)){
	                $err_msgs[] = "The uploaded file's MIME type is not allowed";
	            }
	            if (!file_exists($up['tmp_name'])){
	                $err_msgs[] = "No files exists on the server for this upload";
	            }
	            // Do virus scan of file in temporary storage here, if scanner available
	        } else {
	            $err_msgs[] = "An error occured during file upload";
	        }
	    } else {
	        $err_msgs[] = "No file was uploaded";
	    }
	    
	    // If there are other form data items to validate do so here
	    
	    return $err_msgs;	   
	}
	
	header('Location: ../index.php');
	?>


    <?php
    //}
    
    function displayErrors(array $error_msg){
        echo "<p>\n";
        var_dump($error_msg);
        foreach($error_msg as $v){
            echo $v."<br>\n";
           
        }
        echo "</p>\n";
    }
    
    
    function processUploads(){
	$status = "OK";
	if(!is_dir('uploads') || !is_writable("uploads")){ 
		$status = "NoDirectory";
	} else {
		$ext = strtolower(pathinfo($_FILES['uploads']['name'], PATHINFO_EXTENSION));
		$fn = $_FILES['uploads']['name'];
		$newName = "uploads/$fn.".rand(0, 50) . ".". $ext;
		$success = @move_uploaded_file($_FILES['uploads']['tmp_name'], $newName);
	
		if (!$success){
			$status = "FailMove";
		}
		else {
		    //insert data here
		    //pull the insertdata function
			require_once("dbConnect.php");
		    require_once("insertData.php");
		   //insert the data from the newly formed permanent file into the database
		   insertData($newName);
		   $_SESSION['file'] = $newName;
		   //require_once("viewData.php");
		   //echo "File inserted into employee database successfully";
		   //viewData($newName);
		    
		}
	}
	
	return $status;
}


?>
		