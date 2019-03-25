<?php
    $production = false;
    if(getenv('PRODUCTION')) {
        $production = true;
    }
    $url = site_url('/');;
    if ($production) {
        $url = 'https://inspection-list.herokuapp.com';
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php if($production){
        echo 'https://inspection-list.herokuapp.com/css/index.css';
    } else {
        echo base_url() . 'css/index.css';
    } ?>">
    <link rel="stylesheet" type="text/css" href="<?php if($production){
        echo 'https://inspection-list.herokuapp.com/css/busView.css';
    } else {
        echo base_url() . 'css/busView.css';
    } ?>">
    <link rel="stylesheet" type="text/css" href="<?php if($production){
        echo 'https://inspection-list.herokuapp.com/css/dashboard.css';
    } else {
        echo base_url() . 'css/dashboard.css';
    } ?>">
    <?php
    $favURL = base_url() . 'bus-favicon';
    if ($production) {
        $favURL = 'https://inspection-list.herokuapp.com/bus-favicon';
    }
    ?>

    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $favURL.'/apple-touch-icon.png'; ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $favURL.'/favicon-32x32.png'; ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $favURL.'/favicon-16x16.png '; ?>">
    <link rel="manifest" href="<?php echo $favURL.'/site.webmanifest'; ?>">

    <title>Inspections</title>
</head>
<body>
    <div class="container">
        <h2 onclick="window.location.href = '<?php echo $url ?>'" class="dash">Dashboard</h2>
        <ul class="collapsible">
            <li>
                <div class="collapsible-header"><i class="material-icons">restore</i>Bus List</div>
                <div class="collapsible-body collapse-background center-align">
                    <span>
                        <a href="#" class="dashboard-action" id="bus-list"">
                            Reset Bus List
                        </a>
                    </span>
                </div>
            </li>
            <li>
                <div class="collapsible-header"><i class="material-icons">list</i>Master List</div>
                <div class="collapsible-body collapse-background center-align">
                    <span>
                        <form action="<?php echo $url.'/issues/master'; ?>" method="GET" id="master-list">
                            <a href="#" class="dashboard-action" onclick="document.getElementById('master-list').submit();">
                                Download Master List
                            </a>
                        </form>
                    </span>
                </div>
            </li>
    </div>

    <input type="hidden" id="url" value="<?php echo $url ?> ;">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="<?php if($production){
        echo 'https://inspection-list.herokuapp.com/js/dashboard.js';
    } else {
        echo base_url() . 'js/dashboard.js';
    } ?>"></script>
</body>
</html>