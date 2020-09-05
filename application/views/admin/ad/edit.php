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
            <?php if ($this->session->flashdata('notification')) { ?>
                <div class="alert alert-success">
                    <button class="close" data-close="alert"></button>
                    <span><?php echo $this->session->flashdata('notification'); ?></span>
                </div>
            <?php } ?>

            <section class="content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="box">

                            <form name="form1" id="form1" action="<?php echo base_url() . ADMINPATH .'ad/edit/'.$form_data['id']; ?>" method="post" enctype="multipart/form-data" class="">
                                <input type="hidden" name="id" value="<?php echo $form_data['id']; ?>">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label class="control-label">Title</label>
                                        <input type="text" name="title" id="title" value="<?php echo $form_data['title']; ?>" class="form-control"/>
                                        <?php echo form_error('title'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Banner Pic</label>
                                        <br/>
                                        <?php
                                            // echo base_url(). ADBNR_PATH . 'thumb/50x50_' . $val['pic'];
                                            if (file_exists(ADBNR_PATH . 'thumb/50x50_' . $form_data['pic'])) {
                                                // $profile_pic = '../../'.PROFILE_PATH.'thumb/120x120_'.$this->session->userdata('loginData')->profile_pic;
                                                $profile_pic = base_url() . ADBNR_PATH . 'thumb/50x50_' . $form_data['pic'];
                                            } else {
                                                $profile_pic = "";
                                            }
                                        ?>
                                        <img src="<?php echo $profile_pic; ?>"/>
                                        <br/><br/>
                                        <input type="file" name="pic" class="form-control" value="<?php //echo set_value('profile_pic'); ?>"/>
                                        <input type="hidden" name="pic_old" value="<?php echo $form_data['pic']; ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Target Url</label>
                                        <input type="text" name="target" id="target" value="<?php echo set_value('target'); ?>" class="form-control"/>
                                        <?php echo form_error('target'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="Enable" <?php if($form_data['status'] == 'Enable'){ echo "selected"; } ?>>Enable</option>
                                            <option value="Disable" <?php if($form_data['status'] == 'Disable'){ echo "selected"; } ?>>Disable</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <button type="submit" name="submit" class="btn btn-warning margin btn-flat">Submit</button>
                                    <a href="<?php echo base_url().ADMINPATH; ?>ad" class="btn btn-default btn-flat">Cancel</a>
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
    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js"></script>

    <script type="text/javascript">
        base_url = '<?php echo base_url().ADMINPATH; ?>';
        // $.validator.methods.coupon_type = function(value, element) {
        //     amount_type = $('#amount_type').val();
        //     if(value > 100 && amount_type == '0'){
        //         return false;
        //     }else{
        //         return true;
        //     }
        // }

        $('#form1').validate({
            rules: {
                title:{
                    required: true,
                },
                pic:{
                    // required: true,
                    accept: "image/jpg,image/jpeg,image/png,image/gif"
                },
                target:{
                    required: true,
                    remote: base_url+'ad/valid_url_format'
                }
            },
            messages: {
                pic:{
                    accept: "Invalid Image Type!!"
                },
                target:{
                    remote: 'Invalid Url'
                }
            }
        });

        // $('#expiry_date').datepicker({
        //     format: 'yyyy-mm-dd',
        //     startDate: '0',
        //     autoclose: true
        // });

        $('#checkAll').change(function(){
            if($(this).prop("checked")){
                $('.accees_check').prop('checked',true);
            }else{
                $('.accees_check').prop('checked',false);
            }
        });
    </script>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>


</body>

</html>