<!DOCTYPE html>
<?php //echo "<pre>";print_r($this->config->config['admin_js']); ?>
<?php //echo $this->config['config']['admin_js']; ?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Log in</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="<?php echo $this->assets;?>bootstrap/css/bootstrap.min.css">
        <!-- <link rel="stylesheet" href="<?php echo $this->assets;?>font-awesome.min.css"> -->
        <!-- <link rel="stylesheet" href="<?php echo $this->assets;?>ionicons.min.css"> -->
        <link rel="stylesheet" href="<?php echo $this->assets; ?>dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="<?php echo $this->assets; ?>plugins/iCheck/flat/blue.css">
        <link rel="stylesheet" href="<?php echo $this->assets;?>css/style.css">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a>LOGIN</a>
            </div>
            <div class="login-box-body">
                <p class="login-box-msg">Sign in to start your session</p>
                    
                <?php if(isset($msg)): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $msg;?>
                    </div>
                <?php endif; ?>
                
                <?php if ($this->session->flashdata('notification')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('notification');?>
                    </div>
                <?php endif; ?>

                <form name="frmlogin" id="frmlogin" action="<?php echo site_url('login/dologin/')?>" method="post">
                    <div class="form-group has-feedback">
                        <input type="text" name="username" id="username" class="form-control" placeholder="Username / Email">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        <span id="username_error" class="error"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        <span id="password_error"></span>
                    </div>
                    <div class="row">
                        <!-- <div class="col-xs-8">
                            <div class="checkbox icheck">
                                <label> -->
                                    <!-- <a href="<?php //echo site_url()?>forgotpassword">I forgot my password</a> -->
                                <!-- </label>
                            </div>
                        </div> -->
                        <div class="col-xs-4">
                            <input type="submit" name="submit" value="Login" id="login_button" class="btn btn-primary btn-block btn-flat"/>
                        </div>
                    </div>
                </form>

          <!--    <div class="social-auth-links text-center">
                <p>- OR -</p>
                <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
                  Facebook</a>
                <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
                  Google+</a>
              </div>-->
              <!-- /.social-auth-links -->

                
            </div>
        </div>

        <script src="<?php echo $this->assets; ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="<?php echo $this->assets; ?>bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo $this->assets;?>plugins/iCheck/icheck.min.js"></script>
        <script src="<?php echo $this->assets;?>js/jquery.validate.js"></script>

        <script>
            $(function () {
              $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
              });
            });
        </script>
        <script type="text/javascript">
            $().ready(function() {
                $("#frmlogin").validate({
                    rules: 
                    {
                        username: 
                        {
                            required: true
                        },
                        password: 
                        {
                            required: true,
                            minlength: 6
                        }
                    },
                    messages: 
                    {
                            username: 
                            {
                                    required: "Please enter username"
                            },
                            password: 
                            {
                                    required: "Please enter password",
                                    minlength: "Password must be at least 6 characters long"
                            }
                    },
                    errorPlacement: function(error, element) {
                        if(element.attr("name") === "username") {
                            $("#username_error").html(error);
                        } else if(element.attr("name") === "password") {
                            $("#password_error").html(error);
                        }else{
                            error.insertAfter(element);
                        }
                    }
                });
            });
        </script>
    </body>
</html>
