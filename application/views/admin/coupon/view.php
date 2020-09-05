
<!DOCTYPE html>
<html>

<head>
    <?php $this->load->view(ADMINPATH.'/head'); ?>
    <title><?php echo $title; ?></title>
    <?php $this->load->view(ADMINPATH.'/common_css'); ?>
    <style>
        .nav-tabs-custom > .tab-content{
            padding:20px;
        }
    </style>
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
                    <div class="col-md-12">

                        <!-- Custom Tabs -->
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1" data-toggle="tab">General</a></li>
                                <!-- <li><a href="#tab_2" data-toggle="tab">COVERAGE</a></li>
                                <li><a href="#tab_3" data-toggle="tab">APPLICANT DETAILS</a></li> -->
                                <!-- <li><a href="#tab_4" data-toggle="tab">PAYMENT</a></li> -->
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active margin-l-5" id="tab_1">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Name</strong>
                                            <p class="text-muted"><?php echo $form_data['name']; ?></p>
                                            <hr>

                                            <strong>Email</strong>
                                            <p class="text-muted"><?php echo $form_data['email']; ?></p>
                                            <hr>

                                            <strong>Phone</strong>
                                            <p class="text-muted"><?php echo $form_data['phone']; ?></p>
                                            <hr>

                                            <strong>Username</strong>
                                            <p class="text-muted"><?php echo $form_data['username']; ?></p>
                                            <hr>
                                        </div>

                                        <div class="col-md-4">
                                            <strong>Qid</strong>
                                            <p class="text-muted"><?php echo $form_data['qid']; ?></p>
                                            <hr>

                                            <strong>Notification</strong>
                                            <p class="text-muted"><?php echo $form_data['notification']; ?></p>
                                            <hr>

                                            <strong>Status</strong>
                                            <p class="text-muted"><?php echo $form_data['status']; ?></p>
                                            <hr>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

        </div>

        <?php $this->load->view(ADMINPATH.'/footer'); ?>
    </div>
    <?php $this->load->view(ADMINPATH.'/common_js'); ?>

    <script type="text/javascript">
        // $('#form1').validate({
        //     rules: {
        //         company_name: {
        //             required: true
        //         },
        //         api_url: {
        //             required: true
        //         },
        //         authentication_key: {
        //             required: true
        //         }
        //     },
        //     messages: {
        //         company_name: {
        //             required: 'Please enter category name!'
        //         },
        //         api_url: {
        //             required: 'Please enter API Url!'
        //         },
        //         authentication_key: {
        //             required: 'Please enter Authentication Key!'
        //         }
        //     }
        // });
    </script>


</body>

</html>
