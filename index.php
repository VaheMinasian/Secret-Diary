<?php
session_start();
$error = "";

if(isset($_COOKIE['id'])) {
	setcookie(session_name(), '', time() - 60*60*24*365);
}

if(array_key_exists("logout", $_GET)){
	session_unset();
	setcookie("id", "", time() -60*60);
	unset($_COOKIE['id']);

} else if((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {
	header("Location: loggedInPage.php");
}

if(array_key_exists("submit", $_POST)){
	
	include("connection.php");

	
	if(!$_POST['email']){
		$error .= "An email adress is required<br>";
	}
	if(!$_POST['password']){
		$error .= "A password is required<br>";
	}
	if ($error != ""){
		$error = "<p>There were error(s) in your form:</p>".$error;
	} else{
		if($_POST['signUp']== '1'){

			$query = "SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
			$result = mysqli_query($link, $query);

			if(mysqli_num_rows($result)>0){
				$error = "That email adress is taken.";
			} else{
				$query = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";

				if(!mysqli_query($link, $query)){
					$error = "<p>Could not sign you in, please try again later.</p>";

				} else{
					$query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = '".mysqli_insert_id($link)."' LIMIT 1";

					$id = mysqli_insert_id($link);

					mysqli_query($link, $query);

					$_SESSION['id']= $id;
					if(isset ($_POST['stayLoggedIn'])){
					if ($_POST['stayLoggedIn'] == '1') {
						setcookie("id", $id, time() + 60*60*24*30);

					}}
						header("Location: loggedInPage.php");
				}					

			} 
		} 

		else{
			$query= "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'"; 
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_array($result);
			if(isset($row)) {
				$hashedPassword = md5(md5($row['id']).$_POST['password']);
				if ($hashedPassword == $row['password']){
					$_SESSION['id'] = $row['id'];

					if(isset ($_POST['stayLoggedIn'])){
					if ($_POST['stayLoggedIn'] == '1') {

						setcookie("id", $row['id'], time() + 60*60*24*30);
					}}
						header("Location: loggedInPage.php");
				} else {
					$error = "that email/password combination could not be found";
				}
			} else{
				$error = "that email/password combination could not be found";
			}
		}
	}
}
?>

<?php include("header.php"); ?>

<section class="container form-group">
	<header class="centerAlign" id="the-title">
		<h1 id="page-title">Your Secret Diary</h1>
		<p id="explanatory-text">Store your thoughts permanently and securely</p>
	</header> 

	<div id="error"><?php echo $error; ?></div>

	<!--SIGN UP FORM -->

	<form method="post" id="Signup-form">
		<div class="form-group row">
			<label for="email" class="col-sm-2 col-form-label">Email:</label>
			<div class="col-sm-10">
				<input class="form-control" id="email" type="email" name="email" placeholder="someone@example.com">
			</div>
		</div>
		<div class="form-group row">
			<label for="password" class="col-sm-2 col-form-label">Password:</label>
			<div class="col-sm-10">
				<input class="form-control" id="password" type="password" name="password" placeholder="your password">
			</div>
		</div>
		<div class="checkbox">
			<label>
				<input class="pointer " type="checkbox" name="stayLoggedIn" value="1"> Stay logged in
			</label>
		</div>
		<fieldset class="form-group ">
			<input type="hidden" name="signUp" value="1">
			<input class="btn btn-success pointer" type="submit" name="submit" value="Sign Up">
		</fieldset>
		<fieldset class=" form-group sign-log-in ">
			<p> Already signed up? <a role="button" href="#" class="toggleForms">Log in</a></p>
		</fieldset>
	</form>

	<!--LOG IN FORM -->

	<form method="post" id="Login-form">
		<div class="form-group row">
			<label for="email" class="col-sm-2 col-form-label">Email:</label>
			<div class="col-sm-10">
				<input class="form-control" id="email" type="email" name="email" placeholder="someone@example.com">
			</div>
		</div>

		<div class="form-group row">
			<label for="password" class="col-sm-2 col-form-label">Password:</label>
			<div class="col-sm-10">
				<input class="form-control" id="password" type="password" name="password" placeholder="your password">
			</div>
		</div>
		<div class="checkbox">
			<label>
				<input class="pointer" type="checkbox" name="stayLoggedIn" value="1"> Stay logged in
			</label>
		</div>
		<fieldset class="form-group">
			<input type="hidden" name="signUp" value="0">
			<input class="btn btn-success pointer" type="submit" name="submit" value="Log in">
		</fieldset>
		<fieldset class="form-group sign-log-in">
			<p>New user? <a role="button" href="#" class="toggleForms">Sign Up!</a></p>
		</fieldset>
	</form>
</section>


<?php include("footer.php"); ?>