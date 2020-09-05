<!DOCTYPE html>
<html>

<head>
    <?php $this->load->view(ADMINPATH . '/head'); ?>
    <title><?php echo $title; ?></title>
    <?php $this->load->view(ADMINPATH . '/common_css'); ?>
    <style>
        .nav-tabs-custom>.tab-content {
            padding: 20px;
        }
    </style>
</head>

<body class="hold-transition skin-yellow sidebar-mini">
    <div class="wrapper">
        <?php $this->load->view(ADMINPATH . '/header'); ?>
        <?php $this->load->view(ADMINPATH . '/sidebar'); ?>

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
                                <li class="<?php if($active_tab == 'general'){ echo "active"; } ?>"><a href="#tab_1" data-toggle="tab">General</a></li>
                                <li class="<?php if($active_tab == 'place_bid'){ echo "active"; } ?>"><a href="#tab_2" data-toggle="tab">Placed Bids</a></li>
                                <!-- <li><a href="#tab_3" data-toggle="tab">APPLICANT DETAILS</a></li> -->
                                <!-- <li><a href="#tab_4" data-toggle="tab">PAYMENT</a></li> -->
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane margin-l-5 <?php if($active_tab == 'general'){ echo "active"; } ?>" id="tab_1">

                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <strong>Added By</strong>
                                                    <p class="text-muted"><?php echo $form_data['added_by_name']; ?></p>
                                                    <hr>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Number Type</strong>
                                                    <p class="text-muted"><?php echo $form_data['numbertype_str']; ?></p>
                                                    <hr>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Number Sub Type</strong>
                                                    <p class="text-muted"><?php echo $form_data['numbersubtype_str']; ?></p>
                                                    <hr>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Number</strong>
                                                    <p class="text-muted"><?php echo $form_data['number']; ?></p>
                                                    <hr>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Upgrade Type</strong>
                                                    <p class="text-muted"><?php echo $form_data['upgrade_type']; ?></p>
                                                    <hr>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Starting Bid Amount</strong>
                                                    <p class="text-muted"><?php echo $form_data['starting_bid_amount']; ?></p>
                                                    <hr>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Duration (Days)</strong>
                                                    <p class="text-muted"><?php echo $form_data['duration_str']; ?></p>
                                                    <hr>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Fee</strong>
                                                    <p class="text-muted"><?php echo $form_data['fee_str']; ?></p>
                                                    <hr>
                                                </div>

                                                <?php if ($form_data['coupon'] != "") { ?>
                                                    <div class="col-md-6">
                                                        <strong>Coupon Used</strong>
                                                        <p class="text-muted"><?php echo $form_data['coupon']; ?></p>
                                                        <hr>
                                                    </div>
                                                <?php } ?>

                                                <div class="col-md-6">
                                                    <strong>Total</strong>
                                                    <p class="text-muted"><?php echo $form_data['total']; ?></p>
                                                    <hr>
                                                </div>

                                                <div class="col-md-6">
                                                    <strong>Bid End Date & Time</strong>
                                                    <p class="text-muted"><?php echo $form_data['bid_end_datetime']; ?></p>
                                                    <hr>
                                                </div>

                                                <div class="col-md-6">
                                                    <strong>Progress Status</strong>
                                                    <p class="text-muted">
                                                        <?php if($form_data['status']=='Enable'){ ?>
                                                            <?php if ($form_data['progress_status'] == '0') { ?>
                                                                <span class="label label-default">OnGoing</span>
                                                            <?php } else if ($form_data['progress_status'] == '1') { ?>
                                                                <span class="label label-success">Completed</span>
                                                            <?php } ?>
                                                        <?php }else{ ?>
                                                            <span class="label label-danger">Disabled</span>
                                                        <?php } ?>
                                                    </p>
                                                    <hr>
                                                </div>

                                                <div class="col-md-6">
                                                    <strong>Total Number Of Bids</strong>
                                                    <p class="text-muted">
                                                        <?php echo $form_data['no_of_bids']; ?>
                                                    </p>
                                                    <hr>
                                                </div>

                                                <div class="col-md-6">
                                                    <strong>Purchaser</strong>
                                                    <p class="text-muted"><?php if ($form_data['purchaser_name'] != null) {
                                                                                echo $form_data['purchaser_name'];
                                                                            } else {
                                                                                echo "Not Decide";
                                                                            } ?></p>
                                                    <hr>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Max Bid Amount</strong>
                                                    <p class="text-muted"><?php if ($form_data['max_amount'] != null) {
                                                                                echo $form_data['max_amount'];
                                                                            } else {
                                                                                echo "Not Any Bid";
                                                                            } ?></p>
                                                    <hr>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane margin-l-5 <?php if($active_tab == 'place_bid'){ echo "active"; } ?>" id="tab_2">

                                    <form action="<?=base_url().ADMINPATH.'bid/filter_place_bid/'.$form_data['id']; ?>" method="post">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Date From Start</label>
                                                    <input type="text" name="filter_date_start" id="filter_date_start" class="form-control" value="<?php if(isset($_SESSION['place_bid']['filter_date_start'])){ echo $_SESSION['place_bid']['filter_date_start']; } ?>" autocomplete="off"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Date From End</label>
                                                    <input type="text" name="filter_date_end" id="filter_date_end" class="form-control" value="<?php if(isset($_SESSION['place_bid']['filter_date_end'])){ echo $_SESSION['place_bid']['filter_date_end']; } ?>" autocomplete="off"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">&nbsp;</label><br/>
                                                    <input type="submit" name="submit" class="btn bg-black btn-flat" value="Apply"/>
                                                    <input type="submit" name="submit" class="btn bg-black btn-flat" value="Reset"/>
                                                    <a href="<?php echo base_url().ADMINPATH.'bid/placed_bid_exportXLS/'.$form_data['id']; ?>" class="btn bg-black btn-flat">Export Xls</a>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-3 text-right">
                                                <div class="form-group">
                                                    <label for="">&nbsp;</label><br/>
                                                    <a href="<?php echo base_url().ADMINPATH;?>bid/add" class="btn bg-black btn-flat">Add New <?php echo $title; ?></a>
                                                </div>
                                            </div> -->
                                        </div>
                                    </form>

                                    <table id="datatable" class="table table-bordered table-striped display responsive nowrap" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Member</th>
                                                <th>Amount</th>
                                                <th>Date & Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $cnt1 = 1; foreach ($placed_bids as $val) : ?>
                                                <tr>
                                                    <td><?php echo $cnt1; ?></td>
                                                    <td><?php echo $val['name']; ?></td>
                                                    <td><?php echo $val['amount']; ?></td>
                                                    <td><?php echo $val['created_at']; ?></td>
                                                </tr>
                                            <?php $cnt1++; endforeach; ?>
                                        </tbody>
                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </section>

        </div>

        <?php $this->load->view(ADMINPATH . '/footer'); ?>
    </div>
    <?php $this->load->view(ADMINPATH . '/common_js'); ?>

    <script type="text/javascript">
        $(function(){
            $('#datatable').DataTable({
                // 'columnDefs': [ {
                //         'targets': [13], /* column index */
                //         'orderable': false,
                //         'searchable': false, /* true or false */
                //     },
                // ],
                // "order": [[ 0, "desc" ]],
                
                // 'responsive': true
                "paging": false
            });
        });

        //on load
        filter_date_start = $("#filter_date_start").val();
        $('#filter_date_end').datepicker('setStartDate', filter_date_start);

        // on change startDate
        $("#filter_date_start").datepicker({
            todayBtn:  1,
            autoclose: true,
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#filter_date_end').datepicker('setStartDate', minDate);
            $('#filter_date_end').datepicker('setDate', minDate);
        });
        
        // on change endDate
        $("#filter_date_end").datepicker() .on('changeDate', function (selected) {
            // var maxDate = new Date(selected.date.valueOf());
            // $('#filter_date_start').datepicker('setEndDate', maxDate);
        });

    </script>

</body>

</html>