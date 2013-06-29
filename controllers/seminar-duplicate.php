<?php

require '../includes/connection.inc.php';
require '../includes/autoload.php';

$id 			= $_GET['id'];
$campaign_id 	= $_GET['campaign_id'];

$stmt = $conn->prepare("INSERT INTO seminars (
									campaign_id,
									name,
									contact,
									phone,
									street,
									city,
									state,
									zip_code,
									county,
									coop_contact,
									coop_phone,
									refreshments_offered,
									refreshments_type,
									refreshments_payment,
									radio_station,
									radio_phone,
									newspaper_name,
									newspaper_phone,
									projector_screen,
									wifi,
									cell_reception
						) 
						SELECT campaign_id,
							   name,
							   contact,
							   phone,
							   street,
							   city,
							   state,
							   zip_code,
							   county,
							   coop_contact,
							   coop_phone,
							   refreshments_offered,
							   refreshments_type,
							   refreshments_payment,
							   radio_station,
							   radio_phone,
							   newspaper_name,
							   newspaper_phone,
							   projector_screen,
							   wifi,
							   cell_reception
						FROM seminars WHERE id = :id");
$stmt->execute(array(':id' => $id));

$insert_id = $conn->lastInsertId();

ob_start();
header("Location: http://" . $_SERVER['HTTP_HOST'] . "/seminar-information.php?id=" . $insert_id . "&campaign_id=" . $campaign_id);
ob_end_clean();