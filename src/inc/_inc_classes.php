<?php
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
		public $avatar = false;
		public $agree_tos = false;
		public $agree_privacy = false;
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

		public $own_vehicle = false;
		public $drivers_license = false;
		public $willing_transport = false;
		public $infant_carseat = false;
		public $toddler_carseat = false;
		public $carseat_installation = false;
		public $travel_distance = false;
		public $transport_capacity = false;

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