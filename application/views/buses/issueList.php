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

    <link rel="stylesheet" type="text/css" href="<?php if(getenv('PRODUCTION')){
        echo 'https://inspection-list.herokuapp.com/css/index.css';
    } else {
        echo base_url() . 'css/index.css';
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
        <h2>Issues - <?php echo $busNumber; ?></h2>
		<?php if (empty($issues)) { ?>
			<p>This bus has no issues</p>
		<?php } else { ?>
			<table class="striped highlight">
				<thead>
				<tr>
					<th>Location</th>
					<th>Issue</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($issues as $issue): ?>
					<tr onclick="issue(this, <?php echo $issue['id']; ?>)">
						<td>
							<?php echo $issue['location']; ?>
						</td>
						<td>
							<?php echo $issue['description']; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php } ?>
		<input type="hidden" value="<?php echo $url; ?>" id="url">
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
	<script src="<?php if(getenv('PRODUCTION')){
		echo 'https://inspection-list.herokuapp.com/js/issues.js';
	} else {
		echo base_url() . 'js/issues.js';
	} ?>">
	</script>
</body>
</html>
