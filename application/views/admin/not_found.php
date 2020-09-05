<!DOCTYPE html>
<?php //echo "<pre>";print_r($this->config->config['admin_js']); ?>
<?php //echo $this->config['config']['admin_js']; ?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Log in</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="<?php echo $this->back_assets;?>bootstrap/css/bootstrap.min.css">
        <!-- <link rel="stylesheet" href="<?php echo $this->back_assets;?>font-awesome.min.css"> -->
        <!-- <link rel="stylesheet" href="<?php echo $this->back_assets;?>ionicons.min.css"> -->
        <link rel="stylesheet" href="<?php echo $this->back_assets; ?>dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="<?php echo $this->back_assets; ?>plugins/iCheck/flat/blue.css">
        <link rel="stylesheet" href="<?php echo $this->back_assets;?>css/style.css">
    </head>
    <body class="hold-transition login-page">

        <section class="content">
            <div class="text-center">
                
                <!-- <div class=""> -->
                    <div class="jumbostron">
                    <h1 class="headline text-yellow"> 404</h1>
                    </div>
                    <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
                    <p>
                        We could not find the page you were looking for.
                        <!-- Meanwhile, you may <a href="../../index.html">return to dashboard</a> or try using the search form. -->
                    </p>
                <!-- </div> -->
            </div>
        </section>

        <script src="<?php echo $this->back_assets; ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="<?php echo $this->back_assets; ?>bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo $this->back_assets;?>plugins/iCheck/icheck.min.js"></script>
    </body>
</html>
