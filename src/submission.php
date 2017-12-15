<?php
	require("inc/_inc_mysql.php");

	class User {
		public $id = false;
		public $email = false;
		public $password = false;
		public $first_name = false;
		public $last_name = false;
		public $street_address_1 = false;
		public $street_address_2 = false;
		public $zip_code = false;
		public $type = false;
		public $meta = false;
	}

	class SitterMeta {
		public $about = false;
		public $video_interview = false;
		public $journey = false;
		public $song = false;
		public $verse = false;
		public $denomination = false;
		public $birth_month = false;
		public $birth_day = false;
		public $birth_year = false;

		public $newborn_experience = false;
		public $infant_experience = false;
		public $toddler_experience = false;
		public $youngchild_experience = false;
		public $preteen_experience = false;
		public $teen_experience = false;
		public $other_experience = false;
		public $years_experience = false;

		public $autism_experience = false;
		public $downsyndrom_experience = false;
		public $hearing_impaired_experience = false;
		public $visually_impaired_experience = false;
		public $cp_experience = false;
		public $pd_experience = false;
		public $other_special_experience = false;

		public $cpr_certification = false;
		public $firstaid_certification = false;
		public $lifeguard_certification = false;
		public $other_certification = false;

		public $speak_english = false;
		public $speak_spanish = false;
		public $speak_french = false;
		public $speak_mandarin = false;
		public $speak_japanese = false;
		public $speak_asl = false;
		public $other_languages = false;

		public $dog_friendly = false;
		public $cat_friendly = false;
		public $bird_friendly = false;
		public $uncool_animals = false;

		public $cooking_service = false;
		public $dishes_service = false;
		public $laundry_service = false;
		public $dusting_service = false;
		public $vacuum_service = false;
		public $pets_service = false;
		public $other_services = false;

		public $payment_structure = false;
		public $hourly_rate = false;
		public $child1_rate = false;
		public $child2_rate = false;
		public $child3_rate = false;
		public $child4_rate = false;
		public $child5_rate = false;
		public $child6_rate = false;
		public $newborn_rate = false;
		public $infant_rate = false;
		public $toddler_rate = false;
		public $youngchild_rate = false;
		public $preteen_rate = false;
		public $teen_rate = false;
	}

	class ParentMeta {
		public $number_children = false;
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

		if (!$mysqli->connect_errno) {
			if ($email && $password && $first_name && $last_name) {
				$insert_statement = $mysqli->stmt_init();
				if ($insert_statement->prepare("INSERT INTO `kcs_temp_users` (email, password, user_type, first_name, last_name, street_address_1, street_address_2, zip_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")) {
					
					if (!$insert_statement->bind_param("ssssssss", $email, $password, $type, $first_name, $last_name, $street_address_1, $street_address_2, $zip_code)) { $messages[] = "BIND-00"; }
					if (!$insert_statement->execute()) { $messages[] = "EXEC-00"; }
					

					$insert_id = $mysqli->insert_id;
					$insert_statement->close();
					$insert_statement = null;

					if (is_int($insert_id) && $insert_id > 0) {
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
								} else { $messages[] = "EXEC-PREP"; }
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
        <div class="app-view error-registration">
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

    <script type="text/javascript" src="js/all.js"></script>
</body>
</html><?php endif; ?>