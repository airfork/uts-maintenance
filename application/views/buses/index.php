<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url().'css/index.css'; ?>">
    <title>Buses</title>
</head>
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
                    <tr onclick="window.location.href = '<?php echo site_url('buses/').$bus['id']; ?>';">
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