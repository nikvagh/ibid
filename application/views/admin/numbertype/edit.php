<!DOCTYPE html>
<html>

<head>
    <?php $this->load->view(ADMINPATH . 'head'); ?>
    <title><?php echo $this->system->company_name; ?> | <?php echo $title; ?></title>
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

                            <form name="form1" id="form1" action="<?php echo base_url() . ADMINPATH; ?>numbertype/edit" method="post" enctype="multipart/form-data" class="">
                                <input type="hidden" name="id" value="<?php echo $form_data['id']; ?>">

                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="product_name">Name</label>
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" value="<?php echo $form_data['name']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" name="status" id="status">
                                                <option value="Enable" <?php if ($form_data['status'] == "Enable") { echo "selected"; } ?>>Enable</option>
                                                <option value="Disable" <?php if ($form_data['status'] == "Disable") { echo "selected"; } ?>>Disable</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <button type="submit" name="submit" class="btn btn-warning margin btn-flat">Submit</button>
                                    <a href="<?php echo base_url().ADMINPATH; ?>numbertype" class="btn btn-default btn-flat">Cancel</a>
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
        $('#form1').validate({
            rules: {
                name: {
                    required: true
                }
            },
            messages: {}
        });
    </script>


</body>

</html>