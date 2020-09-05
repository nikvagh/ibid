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
                    <div class="col-md-12">
                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success">
                                <button class="close" data-close="alert"></button>
                                <span><?php echo $this->session->flashdata('success'); ?></span>
                            </div>
                        <?php } ?>
                    </div>

                    <form name="form1" id="form1" action="<?php echo base_url() . ADMINPATH .'setting/update'; ?>" method="post" enctype="multipart/form-data" class="">
                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-body">  
                                    <div class="form-group">
                                        <label class="control-label">Company Name</label>
                                        <input type="text" name="company_name" id="company_name" value="<?=$this->system->company_name?>" class="form-control"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Company Address</label>
                                        <textarea type="text" name="company_address" id="company_address" class="form-control"><?=$this->system->company_address?></textarea>
                                    </div> 
                                    <div class="form-group">
                                        <label class="control-label">Company Contact</label>
                                        <input type="text" name="company_mobile" id="company_mobile" value="<?=$this->system->company_mobile?>" class="form-control"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Company Email</label>
                                        <input type="text" name="company_email" id="company_email" value="<?=$this->system->company_email?>" class="form-control"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="site_name" class="control-label">Site Name</label>
                                        <input type="text" name="site_name" id="site_name" value="<?=$this->system->site_name?>" class="form-control"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Copyright Text</label>
                                        <textarea type="text" name="company_copyright" id="company_copyright" class="form-control"><?=$this->system->company_copyright?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">From Email Address</label>
                                        <input type="text" name="from_email_address" id="from_email_address" value="<?=$this->system->from_email_address?>" class="form-control"/>
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <button type="submit" name="submit" class="btn btn-warning margin btn-flat">Submit</button>
                                    <a href="<?php echo base_url().ADMINPATH; ?>" class="btn btn-default btn-flat">Cancel</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label class="control-label">Terms And Condition</label>
                                        <textarea type="text" name="terms_condition" id="terms_condition" class="form-control"><?=$this->system->terms_condition?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </section>

        </div>

        <?php $this->load->view(ADMINPATH . 'footer'); ?>
    </div>
    <?php $this->load->view(ADMINPATH . 'common_js'); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            CKEDITOR.replace('terms_condition', { height: '500px', startupFocus : true });
        });
    </script>

    <script type="text/javascript">
        $.validator.methods.coupon_type = function(value, element) {
            // console.log(value);
            amount_type = $('#amount_type').val();
            if(value > 100 && amount_type == '0'){
                return false;
            }else{
                return true;
            }
        }

        $('#form1').validate({
            rules: {
                company_mobile:{
                    required: true,
                },
                company_email:{
                    required: true,
                    email: true
                },
                site_name:{
                    required: true
                },
                from_email_address:{
                    required: true,
                    email: true
                }
            },
            messages: {
            }
        });

        $('#expiry_date').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '0',
            autoclose: true
        });
    </script>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>


</body>

</html>