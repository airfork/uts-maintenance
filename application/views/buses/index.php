<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
    $url = site_url('buses/');
    if (getenv('PRODUCTION')) {
        $url = 'https://inspection-list.herokuapp.com/buses/';
    }
?>


<nav>
    <div class="nav-wrapper">
        <ul id="nav-mobile" class="right">
            <?php if($signedIn){ ?>
                <li><a href="<?php echo $web.'dashboard'; ?>">Dashboard</a></li>
                <li><a href="<?php echo $web.'logout'; ?>">Logout</a></li>
            <?php } else { ?>
                <li><a href="<?php echo $web.'login'; ?>">Login</a></li>
            <?php } ?>
        </ul>
    </div>
</nav>

<body>
    <div class="container bus-list">
        <table class="striped highlight">
            <thead>
                <tr>
                    <th>Bus Number</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($buses as $bus): ?>
                    <tr onclick="window.location.href = '<?php echo $url.$bus['id']; ?>';">
                        <td>
                            <?php echo $bus['id']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>