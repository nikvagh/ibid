<!DOCTYPE html>
<html>

<head>
    <?php $this->load->view('includes/head'); ?>
    <title><?php echo $this->system->company_name; ?> | <?php echo $title; ?></title>
    <?php $this->load->view('includes/common_css'); ?>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php $this->load->view('includes/header'); ?>
        <?php $this->load->view('includes/sidebar'); ?>

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
                        
                            <form name="form1" id="form1" action="<?php echo base_url();?>product/add" method="post" enctype="multipart/form-data" class="">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="company_id">Company</label>
                                        <select class="form-control" name="company_id" id="company_id">
                                            <option value=""></option>
                                            <?php foreach($companies as $val){ ?>
                                                <option value="<?php echo $val['id']; ?>"><?php echo $val['company_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_name">Product name</label>
                                        <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Enter Product Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="Y">Enable</option>
                                            <option value="N">Disable</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <button type="submit" name="submit" class="btn btn-primary margin">Submit</button>
                                    <a href="<?php echo base_url();?>product" class="btn btn-default">Cancel</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </section>

        </div>

        <?php $this->load->view('includes/footer'); ?>
    </div>
    <?php $this->load->view('includes/common_js'); ?>

    <script type="text/javascript">
        $('#form1').validate({
            rules: {
                product_name: {
                    required: true
                }
            },
            messages: {
                product_name: {
                    required: 'Please enter product name!'
                }
            }
        });
    </script>


</body>

</html>