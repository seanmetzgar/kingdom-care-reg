<?php
	require("inc/_inc_mysql.php");
	require("inc/_inc_classes.php");

	function getFormUpload($field, $filename) {
		$target_dir = "uploads/avatars/";
		$target_file = false;
		$mime_types = array("image/jpeg", "image/png", "image/gif");
		$filetype = false;
		
		$uploadOK = true;

		if ($_FILES[$field]["size"] > 6000000) {
    		$uploadOK = false;
		}

		if (!in_array($_FILES[$field]["type"], $mime_types)) {
    		$uploadOk = false;
		} else {
			switch ($_FILES[$field]["type"]) {
				case "image/jpeg":
					$filetype = ".jpg";
					break;
				case "image/png":
					$filetype = ".png";
					break;
				case "image/gif":
					$filetype = ".gif";
					break;					
			}

			if ($filetype) {
				$target_file = $target_dir . md5($filename) . $filetype;
			} else { $uploadOK = false; }
		}

		if ($uploadOK) {
			if(file_exists($target_file)) {
    			chmod($target_file, 0755); //Change the file permissions if allowed
    			unlink($target_file); //remove the file
			}

			if (move_uploaded_file($_FILES[$field]["tmp_name"], $target_file)) {
				$uploadOK = $target_file;
			} else {
				$uploadOK = false;
			}
		}

		return $uploadOK;
	}

	function createUser() {

		$messages = [];
		global $mysqli;

		$insert_id = null;
		$rVal = false;

		$type = trim($_REQUEST["user-type"]);
		$type = (strlen($type) > 0) ? $type : "";
		$email = trim($_REQUEST["email"]);
		$email = strlen($email) > 0 ? $email : "";
		$password = trim($_REQUEST["password"]);
		$password = strlen($password) > 0 ? $password : "";
		$first_name = trim($_REQUEST["first-name"]);
		$first_name = strlen($first_name) > 0 ? $first_name : "";
		$last_name = trim($_REQUEST["last-name"]);
		$last_name = strlen($last_name) > 0 ? $last_name : "";
		if ($type == "sitter") {
			$street_address_1 = trim($_REQUEST["street-address-1"]);
			$street_address_1 = strlen($street_address_1) > 0 ? $street_address_1 : "";
			$street_address_2 = trim($_REQUEST["street-address-2"]);
			$street_address_2 = strlen($street_address_2) > 0 ? $street_address_2 : "";
		} else {
			$street_address_1 = "";
			$street_address_2 = "";
		}
		
		$zip_code = trim($_REQUEST["zip-code"]);
		$zip_code = strlen($zip_code) > 0 ? $zip_code : "";

		$agree_tos = (isset($_REQUEST["agree-tos"]) && strlen($_REQUEST["agree-tos"]) > 0) ? true : false;
		$agree_privacy = (isset($_REQUEST["agree-privacy"]) && strlen($_REQUEST["agree-privacy"]) > 0) ? true : false;

		$avatar = (is_array($_FILES) && array_key_exists("avatar", $_FILES) && $_FILES["avatar"]["error"] == 0 && $email) ? getFormUpload("avatar", $email) : false;

		if (!$mysqli->connect_errno) {
			if ($email && $password && $first_name && $last_name) {
				$insert_statement = $mysqli->stmt_init();
				if ($insert_statement->prepare("INSERT INTO `kcs_temp_users` (email, password, user_type, first_name, last_name, street_address_1, street_address_2, zip_code, agree_tos, agree_privacy) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
					
					if (!$insert_statement->bind_param("ssssssssii", $email, $password, $type, $first_name, $last_name, $street_address_1, $street_address_2, $zip_code, $agree_tos, $agree_privacy)) { $messages[] = "BIND-00"; }
					if (!$insert_statement->execute()) { $messages[] = "EXEC-00"; }
					

					$insert_id = $mysqli->insert_id;
					$insert_statement->close();
					$insert_statement = null;

					if (is_int($insert_id) && $insert_id > 0) {
						if ($avatar) {
							$avatar_insert_statement = $mysqli->stmt_init();
							if ($avatar_insert_statement->prepare("INSERT INTO `kcs_temp_users_avatars` (user_id, avatar) VALUES (?, ?)")) {
								if (!$avatar_insert_statement->bind_param("is", $insert_id, $avatar)) { $messages[] = "BIND-AVAT"; }
								if (!$avatar_insert_statement->execute()) { $messages[] = "EXEC-AVAT"; }
								$avatar_insert_statement->close();
								$avatar_insert_statement = null;
							} else { $messages[] = "PREP-AVAT"; }
						}

						$user_meta_fields = ($type == "sitter") ? new SitterMeta : new ParentMeta;

						foreach($user_meta_fields as $key => $value) {
							$sanitized_key = str_replace("_", "-", $key);
							if (isset($_REQUEST[$sanitized_key]) && strlen($_REQUEST[$sanitized_key]) > 0) {
								$meta_value = $_REQUEST[$sanitized_key];
								$insert_statement2 = $mysqli->stmt_init();
								if ($insert_statement2->prepare("INSERT INTO `kcs_temp_users_meta` (user_id, meta_key, meta_value) VALUES (?, ?, ?)")) {
									if (!$insert_statement2->bind_param("iss", $insert_id, $key, $meta_value)) { $messages[] = "BIND-META"; }
									if (!$insert_statement2->execute()) { $messages[] = "EXEC-META"; }
									$insert_statement2->close();
									$insert_statement2 = null;
								} else { $messages[] = "PREP-META"; }
							}
						}
					} else { $messages[] = "INST-00"; }
				} else { $messages[] = "PREP-00"; }
			} else { $messages[] = "REQD-00"; }
		} else { $messages[] = "MSQL-00"; }

		return $messages;
	}

	$messages = createUser();

	if (count($messages) === 0):
		header("Location: /#/register/thanks");
	else:

?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Kingdom Care</title>
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,300i,400,400i,600,600i,700,700i|Josefin+Slab:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="css/all.min.css" rel="stylesheet">
</head>

<body>
	<div id="app-wrapper">
        <div class="app-view error-registration" style="display: block;">
        	<header class="view-header">
			    <a class="logo-link" href="/"><h1 class="visuallyhidden">Kingdom Care</h1></a>
			</header>

			<div class="content-wrap">
				<div class="container content">
					<div class="row">
						<div class="col text-center align-self-center">
							<h3>Oops! Something went wrong.</h3>
							<p>There was an error while processing your pre-registration. Please try again in a little bit.</p>
							<?php if (count($messages) > 0): $messages = join(", ", $messages); ?>
					        <p class="error-messages"><span class="caption">The following errors were received:</span><br><?php echo $messages; ?></p>
					        <?php endif; ?>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
</body>
</html><?php endif; ?>