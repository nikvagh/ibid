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
                        
                            <form name="form1" id="form1" action="<?php echo base_url().ADMINPATH; ?>fee/add" method="post" enctype="multipart/form-data">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Start Amount</label>
                                        <input type="text" class="form-control" name="start_amount" id="start_amount" placeholder="Enter Start Amount">
                                    </div>
                                    <div class="form-group">
                                        <label>To Amount</label>
                                        <input type="text" class="form-control" name="to_amount" id="to_amount" placeholder="Enter To Amount">
                                    </div>
                                    <div class="form-group">
                                        <label>Fee</label>
                                        <input type="text" class="form-control" name="fee_amount" id="fee_amount" placeholder="Enter Fee">
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="Enable">Enable</option>
                                            <option value="Disable">Disable</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <button type="submit" name="submit" class="btn btn-warning margin btn-flat">Submit</button>
                                    <a href="<?php echo base_url().ADMINPATH; ?>fee" class="btn btn-default btn-flat">Cancel</a>
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
        $('#form1').validate({
            rules: {
                start_amount:{
                    required: true,
                    number: true
                },
                to_amount:{
                    required: true,
                    number: true
                },
                fee_amount:{
                    required: true,
                    number: true
                }
            }
        });
    </script>


</body>

</html>