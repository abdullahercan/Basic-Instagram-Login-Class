<?php
include "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>

	<div class="container">
		
		<div class="card mt-5">
			<div class="card-header">
				User Register
			</div>
			<div class="card-body">

				<?php
				$user_data = [];
				if( empty($_GET["token"]) ){
				?>
					<div class="form-group text-center">
						<a class="btn btn-primary" href="<?php echo $instagram->loginUrl()?>">Verify with Instagram</a>
					</div>
				<?php 
				}else{
					$user_data = $instagram->getUser();
					if( $user_data->meta->code != "200" ){
						echo '<div class="alert alert-warning text-center">Verification error, please try again.</div>';
					}
				}
				?>
				<input type="hidden" name="instagram_id" value="<?php echo $user_data->data->id?>">
				<div class="form-group">
					<label for="form_name">Name</label>
					<input type="text" name="form_name" id="form_name" class="form-control" 
					value="<?php echo $user_data->data->full_name?>">
				</div>
				<div class="form-group">
					<label for="form_username">User Name</label>
					<input type="text" name="form_username" id="form_username" class="form-control"
					value="<?php echo $user_data->data->username?>">
				</div>
				<div class="form-group">
					<label for="form_email">E-mail</label>
					<input type="email" name="form_email" id="form_email" class="form-control">
				</div>
			</div>
			<div class="card-footer">
				<button class="btn btn-primary">Register</button>
			</div>
		</div>

	</div>
</body>
</html>