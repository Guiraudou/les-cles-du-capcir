<?php
/**
 * En-tête HTML commun à tous les emails du site.
 * Variable attendue : $emailTitle
 */
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<style>
		body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
		.container { max-width: 600px; margin: 0 auto; padding: 20px; }
		.header { background: #2d5016; color: white; padding: 20px; text-align: center; }
		.content { background: #f8f9fa; padding: 20px; margin: 20px 0; }
		.field { margin-bottom: 15px; }
		.label { font-weight: bold; color: #2d5016; }
		.value { margin-top: 5px; }
		.alert-text { color: #c62828; font-weight: bold; }
		.footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
	</style>
</head>
<body>
	<div class="container">
		<div class="header">
			<h2><?= htmlspecialchars($emailTitle) ?></h2>
			<p><?= htmlspecialchars(SITE_NAME) ?></p>
		</div>
		<div class="content">