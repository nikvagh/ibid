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
                        
                            <form name="form1" id="form1" action="<?php echo base_url().ADMINPATH; ?>ad/add" method="post" enctype="multipart/form-data">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label class="control-label">Title</label>
                                        <input type="text" name="title" id="title" value="<?php echo set_value('title'); ?>" class="form-control"/>
                                        <?php echo form_error('title'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Banner Pic</label>
                                        <input type="file" name="pic" class="form-control"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Target Url</label>
                                        <input type="text" name="target" id="target" value="<?php echo set_value('target'); ?>" class="form-control"/>
                                        <?php echo form_error('target'); ?>
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
                                    <a href="<?php echo base_url().ADMINPATH; ?>ad" class="btn btn-default btn-flat">Cancel</a>
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
    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js"></script>

    <script>
        base_url = '<?php echo base_url().ADMINPATH; ?>';
        $('#form1').validate({
            rules: {
                title:{
                    required: true,
                },
                pic:{
                    required: true,
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

        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</body>

</html>