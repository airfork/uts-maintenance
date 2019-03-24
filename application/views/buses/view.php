<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url().'css/index.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url().'css/busView.css'; ?>">
    <title>Buses</title>
</head>
<body>
    <div class="container">
        <h2 class="bus-num" onclick="window.location.href = '<?php echo site_url('/'); ?>'">
            Bus <?php echo $bus['id']; ?>
        </h2>

        <div class="input-field col s12">
            <input id="name" type="text">
            <label for="name">Name</label>
        </div>


        <ul class="collapsible">
            <li>
                <div class="collapsible-header">Destination Signs & Emergency Button</div>
                <div class="collapsible-body">
                    <span>
                        <div class="input-field col s12">
                              <textarea id="dest" class="materialize-textarea"></textarea>
                              <label for="dest">Description</label>
                        </div>
                    </span>
                </div>
            </li>
            <li>
                <div class="collapsible-header">Zonar</div>
                <div class="collapsible-body">
                    <span>
                        <div class="input-field col s12">
                              <textarea id="zonar" class="materialize-textarea"></textarea>
                              <label for="zonar">Description</label>
                        </div>
                    </span>
                </div>
            </li>
            <li>
                <div class="collapsible-header">Stop Request</div>
                <div class="collapsible-body">
                    <span>
                        <div class="input-field col s12">
                              <textarea id="stop-request" class="materialize-textarea"></textarea>
                              <label for="stop-request">Description</label>
                        </div>
                    </span>
                </div>
            </li>
            <li>
                <div class="collapsible-header">Radio & PA</div>
                <div class="collapsible-body">
                    <span>
                        <div class="input-field col s12">
                              <textarea id="radio-pa" class="materialize-textarea"></textarea>
                              <label for="radio-pa">Description</label>
                        </div>
                    </span>
                </div>
            </li>
            <li>
                <div class="collapsible-header">Passenger Seats</div>
                <div class="collapsible-body">
                    <span>
                        <div class="input-field col s12">
                              <textarea id="pax" class="materialize-textarea"></textarea>
                              <label for="pax">Description</label>
                        </div>
                    </span>
                </div>
            </li>
            <li>
                <div class="collapsible-header">Emergency Equipment</div>
                <div class="collapsible-body">
                    <span>
                        <div class="input-field col s12">
                              <textarea id="emerg" class="materialize-textarea"></textarea>
                              <label for="emerg">Description</label>
                        </div>
                    </span>
                </div>
            </li>
            <li>
                <div class="collapsible-header">ADA</div>
                <div class="collapsible-body">
                    <span>
                        <div class="input-field col s12">
                              <textarea id="ada" class="materialize-textarea"></textarea>
                              <label for="ada">Description</label>
                        </div>
                    </span>
                </div>
            </li>
            <li>
                <div class="collapsible-header">Emergency Exits</div>
                <div class="collapsible-body">
                    <span>
                        <div class="input-field col s12">
                              <textarea id="exits" class="materialize-textarea"></textarea>
                              <label for="exits">Description</label>
                        </div>
                    </span>
                </div>
            </li>
            <li>
                <div class="collapsible-header">Auxiliary Fan</div>
                <div class="collapsible-body">
                    <span>
                        <div class="input-field col s12">
                              <textarea id="aux" class="materialize-textarea"></textarea>
                              <label for="aux">Description</label>
                        </div>
                    </span>
                </div>
            </li>
            <li>
                <div class="collapsible-header">Heat/AC</div>
                <div class="collapsible-body">
                    <span>
                        <div class="input-field col s12">
                              <textarea id="heat-ac" class="materialize-textarea"></textarea>
                              <label for="heat-ac">Description</label>
                        </div>
                    </span>
                </div>
            </li>
            <li>
                <div class="collapsible-header">Driver's Seat</div>
                <div class="collapsible-body">
                    <span>
                        <div class="input-field col s12">
                              <textarea id="seat" class="materialize-textarea"></textarea>
                              <label for="seat">Description</label>
                        </div>
                    </span>
                </div>
            </li>
            <li>
                <div class="collapsible-header">Mirrors</div>
                <div class="collapsible-body">
                    <span>
                        <div class="input-field col s12">
                              <textarea id="mirrors" class="materialize-textarea"></textarea>
                              <label for="mirrors">Description</label>
                        </div>
                    </span>
                </div>
            </li>
            <li>
                <div class="collapsible-header">Defroster</div>
                <div class="collapsible-body">
                    <span>
                        <div class="input-field col s12">
                              <textarea id="defrost" class="materialize-textarea"></textarea>
                              <label for="defrost">Description</label>
                        </div>
                    </span>
                </div>
            </li>
            <li>
                <div class="collapsible-header">Interior Lighting</div>
                <div class="collapsible-body">
                    <span>
                        <div class="input-field col s12">
                              <textarea id="lighting" class="materialize-textarea"></textarea>
                              <label for="lighting">Description</label>
                        </div>
                    </span>
                </div>
            </li>
            <li>
                <div class="collapsible-header">Windshield Wipers</div>
                <div class="collapsible-body">
                    <span>
                        <div class="input-field col s12">
                              <textarea id="wipers" class="materialize-textarea"></textarea>
                              <label for="wipers">Description</label>
                        </div>
                    </span>
                </div>
            </li>
            <li>
                <div class="collapsible-header">Glass Breakage</div>
                <div class="collapsible-body">
                    <span>
                        <div class="input-field col s12">
                              <textarea id="glass" class="materialize-textarea"></textarea>
                              <label for="glass">Description</label>
                        </div>
                    </span>
                </div>
            </li>
            <li>
                <div class="collapsible-header">Bike Racks</div>
                <div class="collapsible-body">
                    <span>
                        <div class="input-field col s12">
                              <textarea id="bikes" class="materialize-textarea"></textarea>
                              <label for="bikes">Description</label>
                        </div>
                    </span>
                </div>
            </li>
            <li>
                <div class="collapsible-header">Other</div>
                <div class="collapsible-body">
                    <span>
                        <div class="input-field col s12">
                              <textarea id="other" class="materialize-textarea"></textarea>
                              <label for="other">Description</label>
                        </div>
                    </span>
                </div>
            </li>
        </ul>
        <input type="hidden" id="url" value="<?php echo site_url('buses/').$bus['id']; ?> ;">
        <input type="hidden" id="site_url" value="<?php echo site_url('buses/') ?> ;">
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large pink lighten-2 submit">
            <i class="large material-icons">send</i>
        </a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="<?php echo base_url().'js/index.js'; ?>"></script>
</body>
</html>