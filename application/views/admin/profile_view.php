<!DOCTYPE html>
<html>

<head>
    <?php $this->load->view(ADMINPATH . 'head'); ?>
    <title><?php echo $title; ?></title>
    <?php $this->load->view(ADMINPATH . 'common_css'); ?>
</head>

<body class="hold-transition skin-yellow sidebar-mini">
    <div class="wrapper">
        <?php $this->load->view(ADMINPATH . 'header'); ?>
        <?php $this->load->view(ADMINPATH . 'sidebar'); ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1><?php echo $title; ?></h1>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-md-6">
                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success">
                                <button class="close" data-close="alert"></button>
                                <span><?php echo $this->session->flashdata('success'); ?></span>
                            </div>
                        <?php } ?>
                        
                        <div class="box">

                            <form name="form1" id="form1" action="<?php echo base_url() . ADMINPATH .'profile'; ?>" method="post" enctype="multipart/form-data" class="">
                                <div class="box-body">  
                                    <div class="form-group">
                                        <label class="control-label">Name</label>
                                        <input type="text" name="name" id="name" value="<?php if(set_value('name')){ echo set_value('name'); }else{ echo $profile['name']; } ?>" class="form-control"/>
                                        <?php echo form_error('name'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Username</label>
                                        <input type="text" name="username" id="username" value="<?php if(set_value('username')){ echo set_value('username'); }else{ echo $profile['username']; } ?>" class="form-control"/>
                                        <?php echo form_error('username'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Email</label>
                                        <input type="text" name="email" id="email" value="<?php if(set_value('email')){ echo set_value('email'); }else{ echo $profile['email']; } ?>" class="form-control"/>
                                        <?php echo form_error('email'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Phone</label>
                                        <input type="text" name="phone" id="phone" value="<?php if(set_value('phone')){ echo set_value('phone'); }else{ echo $profile['phone']; } ?>" class="form-control"/>
                                        <?php echo form_error('phone'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Profile Picture</label>
                                        <input type="file" name="profile_pic" class="form-control" value="<?php echo set_value('profile_pic'); ?>"/>
                                        <input type="hidden" name="profile_pic_old" value="<?php echo $profile['profile_pic']; ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Passowrd</label>
                                        <input type="password" name="password" id="password" value="<?php echo set_value('password'); ?>" class="form-control"/>
                                        <?php echo form_error('password'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Confirm Passowrd</label>
                                        <input type="password" name="confirm_password" id="confirm_password" value="<?php echo set_value('confirm_password'); ?>" class="form-control"/>
                                        <?php echo form_error('confirm_password'); ?>
                                    </div>
                                    <label class="control-label text-danger" for="inputWarning"><i class="fa fa-bell-o"></i> NOTE:  Only Enter Password When You Want To Chnage It. </label>
                                </div>

                                <div class="box-footer">
                                    <button type="submit" name="submit" class="btn btn-warning margin btn-flat">Submit</button>
                                    <a href="<?php echo base_url().ADMINPATH; ?>profile/update_profile" class="btn btn-default btn-flat">Cancel</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </section>

        </div>

        <?php $this->load->view(ADMINPATH . 'footer'); ?>
    </div>
    <?php $this->load->view(ADMINPATH . 'common_js'); ?>

    <script type="text/javascript">
        // $.validator.methods.coupon_type = function(value, element) {
        //     // console.log(value);
        //     amount_type = $('#amount_type').val();
        //     if(value > 100 && amount_type == '0'){
        //         return false;
        //     }else{
        //         return true;
        //     }
        // }

        // $('#form1').validate({
        //     rules: {
        //         company_mobile:{
        //             required: true,
        //         },
        //         company_email:{
        //             required: true,
        //             email: true
        //         },
        //         site_name:{
        //             required: true
        //         },
        //         from_email_address:{
        //             required: true,
        //             email: true
        //         }
        //     },
        //     messages: {
        //     }
        // });

        // $('#expiry_date').datepicker({
        //     format: 'yyyy-mm-dd',
        //     startDate: '0',
        //     autoclose: true
        // });
    </script>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>


</body>

</html>