<!DOCTYPE html>
<html>
    <head>
        <?php $this->load->view(ADMINPATH.'head'); ?>
        <title><?php //echo $this->system->company_name; ?> <?php echo $title;?></title>
        <?php $this->load->view(ADMINPATH.'common_css'); ?>
    </head>
    
    <body class="hold-transition skin-yellow sidebar-mini">
        <div class="wrapper">
        <?php $this->load->view(ADMINPATH.'header'); ?>
        <?php $this->load->view(ADMINPATH.'sidebar'); ?>

            <div class="content-wrapper">
                <section class="content-header">
                    <h1><?php echo $title;?></h1>
                </section>
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            
                            <?php if ($this->session->flashdata('notification')): ?>
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                                    <?php echo $this->session->flashdata('notification');?>
                                </div>
                            <?php endif; ?>

                            <div class="box">
                                <!-- <div class="box-header text-right">
                                    
                                </div> -->
                                <div class="box-body">
                                    
                                    <form action="<?=base_url().ADMINPATH;?>numbertype/filter" method="post">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Date From Start</label>
                                                    <input type="text" name="filter_date_start" id="filter_date_start" class="form-control" value="<?php if(isset($_SESSION['numbertype']['filter_date_start'])){ echo $_SESSION['numbertype']['filter_date_start']; } ?>" autocomplete="off"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Date From End</label>
                                                    <input type="text" name="filter_date_end" id="filter_date_end" class="form-control" value="<?php if(isset($_SESSION['numbertype']['filter_date_end'])){ echo $_SESSION['numbertype']['filter_date_end']; } ?>" autocomplete="off"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">&nbsp;</label><br/>
                                                    <input type="submit" name="submit" class="btn bg-black btn-flat" value="Apply"/>
                                                    <input type="submit" name="submit" class="btn bg-black btn-flat" value="Reset"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <label for="">&nbsp;</label><br/>
                                                <a href="<?php echo base_url().ADMINPATH;?>numbertype/add" class="btn bg-black btn-flat"> Add New <?php echo $title; ?></a>
                                            </div>
                                        </div>
                                    </form>

                                    <form name="datatableForm" id="datatableForm" action="<?=base_url().ADMINPATH;?>numbertype" method="post" enctype="multipart/form-data">
                                        <table id="datatable" class="table table-bordered table-striped display responsive nowrap" width="100%">
                                        <!-- display responsive nowrap -->
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Type</th>
                                                    <th class="no-sort">Status</th>
                                                    <th class="no-sort">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($manage_data as $val): ?>    
                                                    <tr>
                                                        <td><?php echo $val['id']; ?></td>
                                                        <td><?php echo $val['name']; ?></td>
                                                        <td class="">
                                                            <?php if($val['status']=='Enable'){ ?>
                                                                    <span class="label label-info"><?Php echo $val['status']; ?></span>
                                                                    <button class="btn btn-xs btn-default" onclick="javascript: changePublishStatus('datatableForm','<?php echo $val['id']; ?>','Disable');">Click here to Disable</button>
                                                            <?php }else{ ?>
                                                                    <span class="label label-danger"><?php echo $val['status']; ?></span>
                                                                    <button class="btn btn-xs btn-default" onclick="javascript: changePublishStatus('datatableForm','<?php echo $val['id']; ?>','Enable');">Click here to Enable</button>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="">
                                                            <!-- <a href="<?php echo base_url().ADMINPATH; ?>numbertypes/view/<?php echo $val['id']; ?>" class="btn btn-warning btn-flat">View</a> -->
                                                            <a href="<?php echo base_url().ADMINPATH; ?>numbertype/edit/<?php echo $val['id']; ?>" class="btn btn-warning btn-flat">Edit</a>
                                                            <!-- <button type="button" class="btn btn-danger" id="delete_<?php echo $val['id']; ?>" onClick="javascript: confirmDelete('datatableForm','<?php echo $val['id']; ?>')" disabled>Delete</button> -->
                                                        </td>     
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        <input type="hidden" name="action" id="action" />
                                        <input type="hidden" name="id" id="id"/>
                                        <input type="hidden" name="publish" id="publish"/>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>  

            <?php $this->load->view(ADMINPATH.'footer'); ?>
        </div>

        <?php $this->load->view(ADMINPATH.'common_js'); ?>
        <script>
            $(function(){
                $('#datatable').DataTable({
                    'columnDefs': [ {
                        'targets': [2,3], /* column index */
                        'orderable': false,
                        'searchable': false, /* true or false */
                    },
                    {
                        "order": [[ 0, "desc" ]]
                    }],
                    
                    // 'responsive': true
                });
            });

            function confirmDelete(frm, id)
            {
                var agree=confirm("Are you sure to delete this product?");
                if (agree)
                {
                    $("#id").val(id);
                    $("#action").val("delete");
                    $("#"+frm).submit();
                }

            }

            function changePublishStatus(frm, id, status)
            {
                var agree=confirm("Are you sure to change status?");
                if (agree)
                {
                    $("#id").val(id);
                    $("#action").val("change_publish");
                    $("#publish").val(status);
                    $("#"+frm).submit();
                }
            }

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
