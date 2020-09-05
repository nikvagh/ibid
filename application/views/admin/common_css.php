<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="<?php echo $this->back_assets; ?>bootstrap/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="<?php echo $this->back_assets; ?>dist/css/AdminLTE.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins
      folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="<?php echo $this->back_assets; ?>dist/css/skins/_all-skins.min.css">
<link rel="stylesheet" href="<?php echo $this->back_assets;?>css/style.css">



<!-- iCheck -->
<!-- <link rel="stylesheet" href="<?php //echo $this->back_assets; ?>plugins/iCheck/flat/blue.css"> -->
<!-- Date Picker -->
<!-- <link rel="stylesheet" href="<?php //echo $this->back_assets; ?>plugins/datepicker/datepicker3.css"> -->
<!-- Daterange picker -->
<!-- <link rel="stylesheet" href="<?php //echo $this->back_assets; ?>plugins/daterangepicker/daterangepicker.css"> -->
<!-- bootstrap wysihtml5 - text editor -->
<!-- <link rel="stylesheet" href="<?php //echo $this->back_assets; ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"> -->

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<?php if(isset($dashboard)): ?>
    <link rel="stylesheet" href="<?php echo $this->back_assets; ?>plugins/iCheck/flat/blue.css">
<?php endif; ?>
<?php if(isset($member_manage) || isset($numbertype_manage) || isset($numbersubtype_manage) || isset($bid_manage) || isset($bid_view) || isset($fee_manage) || isset($coupon_manage) || isset($duration_manage) || isset($user_manage) || isset($ad_manage)){ ?>
    <link rel="stylesheet" href="<?php echo $this->back_assets; ?>plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css" />
    <link rel="stylesheet" href="<?php echo $this->back_assets; ?>plugins/datepicker/datepicker3.css">
<?php } ?>

<?php if(isset($coupon_form) ){ ?>
    <link rel="stylesheet" href="<?php echo $this->back_assets; ?>plugins/datepicker/datepicker3.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .select2-container{
            width: 100%;
        }
        .select2-container--default .select2-selection--single{
            border: 1px solid #d2d6de;
            height: 34px;
            border-radius: 0;
        }
    </style>
<?php } ?>


<?php if(isset($order_manage)){ ?>
    <link rel="stylesheet" href="<?php echo $this->back_assets; ?>plugins/datatables/dataTables.bootstrap.css">
<?php } ?>
<?php if(isset($product_manage)){ ?>
    <link rel="stylesheet" href="<?php echo $this->back_assets; ?>plugins/datatables/dataTables.bootstrap.css">
<?php } ?>