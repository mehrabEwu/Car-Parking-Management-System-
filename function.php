

<?php 
session_start();

// connect to database
$db = mysqli_connect("localhost", "root", "", "carparkingg_2");

// variable declaration
$userID = "";
$mailID    = "";
$errors   = array();
//$userID_error= array();
//$mailID_error =array(); 
$passw    = "";
$name  = "";
$location = "";
$gender  = "";
$parkingSlotName="";
$slotSize="";
$price="";
$cntNO ="";
$bkashNO  = "";
$gender  = "";

//$password_1 = "";
//$password_2 = "";
$user_type  = "";

// call the register() function if register_btn is clicked
if (isset($_POST['register_btn'])) {     // register btn
	register();
}

// REGISTER USER
function register(){
	// call these variables with the global keyword to make them available in function
	global $db, $errors, $userID, $mailID,$passw ,$name, $address, $propic,  $gender,$password_1 , $password_2, $user_type ,$location,$parkingSlotName,$slotSize,$price,$bkashNO,$gender;
	 
                  
                             

	// receive all input values from the form. Call the e() function
    // defined below to escape form values
   /*$username    =  e($_POST['username']);
	$email       =  e($_POST['email']);
	$email       =  e($_POST['blood']);
	$email       =  e($_POST['email']);
	$email       =  e($_POST['email']);
	$email       =  e($_POST['email']);
	$password_1  =  e($_POST['password_1']);
	$password_2  =  e($_POST['password_2']); */


$userID= e($_POST['userID']);

$mailID=  e($_POST['mailID']);
$password_1  =  e($_POST['password_1']);
$password_2  =  e($_POST['password_2']);  
$name  =   e($_POST['name']);
$gender=  e($_POST['gender']);

$location=  e($_POST['location']);
$parkingSlotName=  e($_POST['parkingSlotName']);
$slotSize=  e($_POST['slotSize']);
$price=  e($_POST['price']);
$cntNO=  e($_POST['cntNO']);
$bkashNO=  e($_POST['bkashNO']);

$user_type=  e($_POST['user_type']);	

	// form validation: ensure that the form is correctly filled//
	if (empty($userID)) { 
		array_push($errors, "UserID is required"); 
	}
	if (empty($mailID)) { 
		array_push($errors, "Email is required"); 
	}
	if (empty($password_1)) { 
		array_push($errors, "Password is required"); 
	}
	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	}
	
	if (empty($name)) { 
		array_push($errors, "name is required"); 
	}
	
	
	if (empty($gender)) { 
		array_push($errors, "gender is required"); 
	}
	
	
	if (empty($location)) { 
		array_push($errors, "location is required"); 
	}
	
	
	if (empty($parkingSlotName)) { 
		array_push($errors, "parkingSlotName is required"); 
	}
	
	
	if (empty($slotSize)) { 
		array_push($errors, "slotSize is required"); 
	}
	
	
	if (empty($price)) { 
		array_push($errors, " price is required"); 
	}
	
	
	if (empty($cntNO)) { 
		array_push($errors, "cntNO is required"); 
	}
	
	
	if (empty($bkashNO)) { 
		array_push($errors, "bkashNO is required"); 
	}
	
	if (empty($user_type)) { 
		array_push($errors, "userdtype is required"); 
	}
	
	
	
	
	
	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$passw = md5($password_1);//encrypt the password before saving in the database

		
		
		$sql_u = "SELECT * FROM userprofile WHERE userID='$userID'";
  	$sql_e = "SELECT * FROM userprofile WHERE mailID='$mailID'";
  	$res_u = mysqli_query($db, $sql_u);
  	$res_e = mysqli_query($db, $sql_e);
		
		if (mysqli_num_rows($res_u) > 0) {
  	  $userID_error = "Sorry... userID  already taken"; 	
  	}else if(mysqli_num_rows($res_e) > 0){
  	  $mailID_error = "Sorry... mailID already taken"; 	
  	}else{
		
		
		
		if (isset($_POST['user_type'])) {
			$user_type = e($_POST['user_type']);
			$query = "INSERT INTO userprofile (userID, mailID ,passw,name,gender,location,parkingSlotName,slotSize,price,cntNO,bkashNO, user_type) 
					  VALUES('$userID', '$mailID' ,'$passw', '$name',  '$gender','$location','$parkingSlotName','$slotSize','$price','$cntNO','$bkashNO', '$user_type')";
			mysqli_query($db, $query);
			$_SESSION['success']  = "New user successfully created!!";
			header('location: home.php');
		}else{
			$query = "INSERT INTO userprofile (userID, mailID ,passw,name,gender,location,parkingSlotName,slotSize,price,cntNO,bkashNO, user_type) 
					  VALUES('$userID', '$mailID' ,'$passw', '$name',  '$gender','$location','$parkingSlotName','$slotSize','$price','$cntNO','$bkashNO', 'user')";
			mysqli_query($db, $query);

			// get id of the created user
			$logged_in_user_id = mysqli_insert_id($db);

			$_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
			$_SESSION['success']  = "You are now logged in ";
			header('location: index.php');				
             //header('location: login.php');		
		}
		
    }
}
	}

// return user array from their id
function getUserById($id){
	global $db;
	$query = "SELECT * FROM users WHERE id=" . $id;
	$result = mysqli_query($db, $query);

	$user = mysqli_fetch_assoc($result);
	return $user;
}

// escape string
function e($val){
	global $db;
	return mysqli_real_escape_string($db, trim($val));
}

function display_error() {
	global $errors;

	if (count($errors) > 0){
		echo '<div class="error">';
			foreach ($errors as $error){
				echo $error .'<br>';
			}
		echo '</div>';
	}
}

function isLoggedIn()
{
	if (isset($_SESSION['user'])) {
		return true;
	}else{
		return false;
	}
}	

if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
   header("location: login.php");
   //header('location: Adminhomepage.html');
  }

if (isset($_POST['login_btn'])) {
	login();
}

// LOGIN USER
function login(){
	global $db, $userID, $errors;

	// grap form values
	$userID = e($_POST['userID']);
	$passw = e($_POST['passw']);

	// make sure form is filled properly
	if (empty($userID)) {
		array_push($errors, "Username is required");
	}
	if (empty($passw)) {
		array_push($errors, "Password is required");
	}

	// attempt login if no errors on form
	if (count($errors) == 0) {
		$passw = md5($passw); // encrypt.

		$query = "SELECT * FROM userprofile WHERE userID='$userID' AND passw='$passw' LIMIT 1";
		$results = mysqli_query($db, $query);

		if (mysqli_num_rows($results) == 1) { // user found
			// check if user is admin or user
			$logged_in_user = mysqli_fetch_assoc($results);
			if ($logged_in_user['user_type'] == 'admin') {

				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";
				header('location: adminpage.php');		  
			}else{
				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";

				header('location: index.php');
			    // header('location: Adminhomepage.html');
			}
		}else {
			array_push($errors, "Wrong username/password combination");
		}
	}
}

function isAdmin()
{
	if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin' ) {
		return true;
	}else{
		return false;
	}
}
