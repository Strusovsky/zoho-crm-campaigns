<?php

	require '../includes/connection.inc.php';
	require '../includes/autoload.php';

	$id				= $_GET['id'];	
	$zoho_id		= $_GET['zoho_id'];	
	$campaign_id	= $_GET['campaign_id'];

	if (isset($id) && $id !== NULL)
	{
		$update = new UpdateCampaign($id, $campaign_id, TRUE);

		$stmt = $conn->prepare("DELETE FROM zoho.campaigns WHERE id = :id");
		$stmt->bindParam(':id', $id);
		$stmt->execute();

		$update->updateCampaign($id, $campaign_id, TRUE);
	}

	ob_start();
	header("Location: http://" . $_SERVER['HTTP_HOST'] . "/campaign-information.php?id=" . $zoho_id);
	ob_end_clean();