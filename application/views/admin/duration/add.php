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
                        
                            <form name="form1" id="form1" action="<?php echo base_url().ADMINPATH; ?>duration/add" method="post" enctype="multipart/form-data">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Number Of Days</label>
                                        <input type="text" class="form-control" name="no_of_days" id="no_of_days" placeholder="Enter Number Of Days">
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
                                    <a href="<?php echo base_url().ADMINPATH; ?>duration" class="btn btn-default btn-flat">Cancel</a>
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
                no_of_days:{
                    required: true,
                    number: true
                }
            }
        });
    </script>


</body>

</html>