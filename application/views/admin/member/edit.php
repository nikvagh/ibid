
<!DOCTYPE html>
<html>

<head>
    <?php $this->load->view(ADMINPATH . 'head'); ?>
    <title><?php echo $title; ?></title>
    <?php $this->load->view(ADMINPATH . 'common_css'); ?>
</head>

<body class="hold-transition skin-blue sidebar-mini">
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
                        
                            <form name="form1" id="form1" action="<?php echo base_url().ADMINPATH.'member/edit/'.$form_data['id'];?>" method="post" enctype="multipart/form-data" class="">
                                <input type="hidden" name="id" value="<?php echo $form_data['id']; ?>">

                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" name="password" id="password" placeholder="* * * * * *" value="<?php echo set_value('password'); ?>">
                                            <?php echo form_error('password'); ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="* * * * * *" value="<?php echo set_value('confirm_password'); ?>">
                                            <?php echo form_error('confirm_password'); ?>
                                        </div>
                                        <!-- <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" name="status" id="status">
                                                <option value="Y" <?php if($form_data['status'] == "Y"){ echo "selected"; } ?>>Enable</option>
                                                <option value="N" <?php if($form_data['status'] == "N"){ echo "selected"; } ?>>Disable</option>
                                            </select>
                                        </div> -->
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <button type="submit" name="submit" class="btn btn-warning margin btn-flat">Submit</button>
                                    <a href="<?php echo base_url().ADMINPATH.'member';?>" class="btn btn-default btn-flat">Cancel</a>
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
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>


</body>

</html>
