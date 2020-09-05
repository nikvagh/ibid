<!DOCTYPE html>
<html>

<head>
    <?php $this->load->view(ADMINPATH.'/head'); ?>
    <title> <?php echo $title; ?></title>
    <?php $this->load->view(ADMINPATH.'/common_css'); ?>
</head>

<body class="hold-transition skin-yellow sidebar-mini">
    <div class="wrapper">
        <?php $this->load->view(ADMINPATH.'/header'); ?>
        <?php $this->load->view(ADMINPATH.'/sidebar'); ?>

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
                        
                            <form name="form1" id="form1" action="<?php echo base_url().ADMINPATH; ?>coupon/add" method="post" enctype="multipart/form-data">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Code</label>
                                        <input type="text" class="form-control" name="code" id="code" placeholder="Enter Code" value="<?php echo set_value('code'); ?>">
                                        <?php echo form_error('code'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="text" class="form-control" name="amount" id="amount" placeholder="Enter Amount" value="<?php echo set_value('amount'); ?>">
                                        <?php echo form_error('amount'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Discount Type</label>
                                        <select class="form-control" name="amount_type" id="amount_type">
                                            <option value="">--Select--</option>
                                            <option value="0" <?php if(set_value('amount_type') == '0'){ echo "selected"; } ?>>Percentage</option>
                                            <option value="1" <?php if(set_value('amount_type') == '1'){ echo "selected"; } ?>>Amount</option>
                                        </select>
                                        <?php echo form_error('amount_type'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Discount On</label>
                                        <select class="form-control" name="discount_on" id="discount_on">
                                            <option value="">--Select--</option>
                                            <option value="0" <?php if(set_value('discount_on') == '0'){ echo "selected"; } ?>>Bid Fee</option>
                                            <option value="1" <?php if(set_value('discount_on') == '1'){ echo "selected"; } ?>>Premium Amount</option>
                                        </select>
                                        <?php echo form_error('discount_on'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Apply On</label>
                                        <select class="form-control" name="users" id="users">
                                            <option value="">--Select--</option>
                                            <option value="0" <?php if(set_value('users') == '0'){ echo "selected"; } ?>>All Users</option>
                                            <option value="1" <?php if(set_value('users') == '1'){ echo "selected"; } ?>>Single User</option>
                                        </select>
                                        <?php echo form_error('users'); ?>
                                    </div>
                                    <div class="form-group member_box">
                                        <label>User</label>
                                        <select class="form-control" name="member_id" id="member_id">
                                            <option value="">--Select--</option>
                                            <?php foreach($members as $key=>$val){ ?>
                                                <option value="<?php echo $val['id'] ; ?>" <?php if(set_value('member_id') == $val['id']){ echo "selected"; } ?>><?php echo $val['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <?php echo form_error('member_id'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Expiry Date</label>
                                        <input type="text" class="form-control" name="expiry_date" id="expiry_date" placeholder="Select Date" value="<?php echo set_value('expiry_date'); ?>">
                                        <?php echo form_error('expiry_date'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="Enable" <?php if(set_value('status') == 'Enable'){ echo "selected"; } ?>>Enable</option>
                                            <option value="Disable" <?php if(set_value('status') == 'Disable'){ echo "selected"; } ?>>Disable</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <button type="submit" name="submit" class="btn btn-warning margin btn-flat">Submit</button>
                                    <a href="<?php echo base_url().ADMINPATH; ?>coupon" class="btn btn-default btn-flat">Cancel</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </section>

        </div>

        <?php $this->load->view(ADMINPATH.'/footer'); ?>
    </div>
    <?php $this->load->view(ADMINPATH.'/common_js'); ?>

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
                code:{
                    required: true,
                },
                amount:{
                    required: true,
                    number: true,
                    coupon_type: true,
                },
                amount_type:{
                    required: true
                },
                expiry_date:{
                    required: true
                }
            },
            messages: {
                amount:{
                    coupon_type: 'Percentage Must Be Less Than 100',
                }
            }
        });

        $('#expiry_date').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '0',
            autoclose: true
        });

        $("#member_id").select2();

        member_box();
        $("#users").on('change',function(){
            member_box();
        });
        function member_box(){
            user_val = $("#users").val();
            if(user_val == '1'){
                $(".member_box").show();
            }else{
                $(".member_box").hide();
            }
        }
        
    </script>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>


</body>

</html>