<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="theme-color" content="#f0e2f5">
	<!-- Compiled and minified CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


	<link rel="stylesheet" type="text/css" href="<?php if(getenv('PRODUCTION')){
		echo 'https://inspection-list.herokuapp.com/css/index.css';
	} else {
		echo base_url() . 'css/index.css';
	} ?>">
	<link rel="stylesheet" type="text/css" href="<?php if(getenv('PRODUCTION')){
		echo 'https://inspection-list.herokuapp.com/css/busView.css';
	} else {
		echo base_url() . 'css/busView.css';
	} ?>">
	<?php
	$favURL = base_url() . 'bus-favicon';
	if (getenv('PRODUCTION')) {
		$favURL = 'https://inspection-list.herokuapp.com/bus-favicon';
	}
	$web = base_url();
	if (getenv('PRODUCTION')) {
		$web = 'https://inspection-list.herokuapp.com/';
	}
	?>

	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $favURL.'/apple-touch-icon.png'; ?>">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo $favURL.'/favicon-32x32.png'; ?>">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo $favURL.'/favicon-16x16.png '; ?>">
	<link rel="manifest" href="<?php echo $favURL.'/site.webmanifest'; ?>">

	<title>Inspections</title>
</head>

<?php
$url = site_url('/');
if (getenv('PRODUCTION')) {
	$url = 'https://inspection-list.herokuapp.com/';
}
?>

<nav>
	<div class="nav-wrapper">
		<ul id="nav-mobile" class="right">
			<li><a href="<?php echo $web; ?>">Home</a></li>
			<li><a href="<?php echo $web.'dashboard'; ?>">Dashboard</a></li>
			<li><a href="<?php echo $web.'logout'; ?>">Logout</a></li>
		</ul>
	</div>
</nav>
<body>
	<div class="container bus-list">
		<h2>Contacts</h2>
			<div class="row">
				<div class="input-field col s12 m6">
					<input id="name" type="text" class="input">
					<label for="name">Name</label>
				</div>

				<div class="input-field col s12 m6">
					<input id="email" type="email" class="input">
					<label for="email">Email</label>
				</div>
			</div>

			<table class="striped highlight">
				<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
					</tr>
				</thead>
				<tbody id="contact-table">
					<?php foreach ($contacts as $contact): ?>
						<tr onclick="deleteContact(this, <?php echo $contact['id']; ?>)">
							<td>
								<?php echo $contact['name']; ?>
							</td>
							<td>
								<?php echo $contact['email']; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<input type="hidden" value="<?php echo $url; ?>" id="url">
	</div>

	<div class="fixed-action-btn">
		<a class="btn-floating btn-large pink lighten-2" onclick="addContact()">
			<i class="large material-icons">add</i>
		</a>
	</div>


	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
	<script src="<?php if(getenv('PRODUCTION')){
		echo 'https://inspection-list.herokuapp.com/js/contacts.js';
	} else {
		echo base_url() . 'js/contacts.js';
	} ?>">
	</script>
</body>
</html>
