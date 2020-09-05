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
                                    <a href="<?php echo base_url().ADMINPATH;?>member/add">
                                        <button class="btn btn-info"> Add New <?php echo $title; ?></button>
                                    </a>
                                </div> -->
                                <div class="box-body">
                                    <form action="<?=base_url().ADMINPATH;?>member/filter" method="post">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Date From Start</label>
                                                    <input type="text" name="filter_date_start" id="filter_date_start" class="form-control" value="<?php if(isset($_SESSION['member']['filter_date_start'])){ echo $_SESSION['member']['filter_date_start']; } ?>" autocomplete="off"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Date From End</label>
                                                    <input type="text" name="filter_date_end" id="filter_date_end" class="form-control" value="<?php if(isset($_SESSION['member']['filter_date_end'])){ echo $_SESSION['member']['filter_date_end']; } ?>" autocomplete="off"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">&nbsp;</label><br/>
                                                    <input type="submit" name="submit" class="btn bg-black btn-flat" value="Apply"/>
                                                    <input type="submit" name="submit" class="btn bg-black btn-flat" value="Reset"/>
                                                    <a href="<?php echo base_url().ADMINPATH; ?>member/exportXLS" class="btn bg-black btn-flat">Export Xls</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <form name="datatableForm" id="datatableForm" action="<?=base_url().ADMINPATH;?>member" method="post" enctype="multipart/form-data">
                                        <table id="datatable" class="table table-bordered table-striped display responsive nowrap" width="100%">
                                        <!-- display responsive nowrap -->
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th class="no-sort">Status</th>
                                                    <th class="no-sort">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($manage_data as $val): ?>    
                                                    <tr>
                                                        <td><?php echo $val['id']; ?></td>
                                                        <td><?php echo $val['name']; ?></td>
                                                        <td><?php echo $val['email']; ?></td>
                                                        <td><?php echo $val['phone']; ?></td>
                                                        <td class="text-center">
                                                            <?php if($val['status']=='Enable'){ ?>
                                                                    <span class="label label-info"><?Php echo $val['status']; ?></span>
                                                                    <button class="btn btn-xs btn-default" onclick="javascript: changePublishStatus('datatableForm','<?php echo $val['id']; ?>','Disable');">Click here to Disable</button>
                                                            <?php }else{ ?>
                                                                    <span class="label label-danger"><?php echo $val['status']; ?></span>
                                                                    <button class="btn btn-xs btn-default" onclick="javascript: changePublishStatus('datatableForm','<?php echo $val['id']; ?>','Enable');">Click here to Enable</button>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="text-center">
                                                            <!-- <a href="<?php //echo base_url();?>commpany/edit/<?php //echo $val['category_id']; ?>">
                                                                <span><i class="fa fa-edit edit" title="Edit"></i></span>
                                                            </a>&nbsp;
                                                            <span title="delete" onClick="javascript: confirmDeleteUser(document.frmcat,'<?php echo $val['category_id']; ?>');">
                                                                <i class="fa fa-trash-o delete" title="Delete"></i>
                                                            </span> -->

                                                            <a href="<?php echo base_url().ADMINPATH; ?>member/edit/<?php echo $val['id']; ?>" class="btn btn-primary btn-flat">Edit</a> &nbsp;
                                                            <a href="<?php echo base_url().ADMINPATH; ?>member/view/<?php echo $val['id']; ?>" class="btn btn-warning btn-flat">View</a> &nbsp;
                                                            <button type="button" class="btn btn-danger btn-flat" id="notification_<?php echo $val['id']; ?>" onClick="javascript: loadNotification('<?php echo $val['id']; ?>')">Notification</button>
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
            
            <div class="modal fade" id="notification_modal" role="dialog"></div>

            <?php $this->load->view(ADMINPATH.'footer'); ?>
        </div>

        <?php $this->load->view(ADMINPATH.'common_js'); ?>
        <script>

            base_url = '<?php echo base_url().ADMINPATH; ?>';
            $(function(){
                $('#datatable').DataTable({
                    'columnDefs': [ {
                        'targets': [4,5], /* column index */
                        'orderable': false,
                        'searchable': false, /* true or false */
                    },
                    {
                        "order": [[ 0, "desc" ]]
                    }],
                    
                    // 'responsive': true
                });
            });

            // function confirmDelete(frm, id)
            // {
            //     var agree=confirm("Are you sure to delete this product?");
            //     if (agree)
            //     {
            //         $("#id").val(id);
            //         $("#action").val("delete");
            //         $("#"+frm).submit();
            //     }

            // }
            

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

            function loadNotification(id){
                $.ajax({
                    url: base_url+'member/notification_modal/',
                    type: 'post',
                    data: {id:id},
                    // dataType: 'json',
                    success: function(response){
                    // console.log(response);
                    $('#notification_modal').html(response);
                    // Display Modal
                    $('#notification_modal').modal('show');
                    }
                });
                
            }


            function send_Single_Notification() {
                let member_id = $("#notification_member_id").val();
                let notification_title = $("#notification_title_s").val();
                let notification_msg = $("#notification_msg_s").val();

                if (notification_title == "" || notification_msg == "") {
                    $err = '<div class="alert alert-danger alert-dismissible">'+
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+
                                'Please enter notification title & message'+
                            '</div>';
                    $('#modal_error_s').html($err);
                    return false;
                }
                $('#modal_error_s').html("");

                $('.send_btn_s').html('Sending...');
                $('.send_btn_s').prop('disabled','true');

                let url = "<?php echo base_url().ADMINPATH; ?>" + "dashboard/send_bulk_notification";
                let xhr = new XMLHttpRequest();
                let formData = new FormData();
                formData.append('API_KEY', '<?php echo FCM_KEY; ?>');
                formData.append('member_id', member_id);
                formData.append('notification_title', notification_title);
                formData.append('notification_msg', notification_msg);
                xhr.open('POST', url);
                xhr.send(formData);
                xhr.onload = function() {
                
                    let obj = JSON.parse(xhr.responseText);
                    // console.log(obj);

                    if (xhr.status === 200) {
                        console.log('200');
                        let status = obj.Status;
                        let message = obj.Message;
                        if (!status) {
                        console.log('fail');
                        // console.log(message);
                        $err = '<div class="alert alert-danger alert-dismissible">'+
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+
                                message+
                            '</div>';
                        $('#'+'modal_error_s').html($err);
                        $('.send_btn_s').html('Send Now');
                        $('.send_btn_s').removeAttr("disabled");

                            // loader(false);
                            return false;
                        } else {
                        console.log('success');
                        $success = '<div class="alert alert-success alert-dismissible">'+
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+
                                message+
                            '</div>';
                        $('#'+'modal_error_s').html($success);
                        $("#notification_title_s").val("");
                        $("#notification_msg_s").val("");

                        $('.send_btn_s').html('Send Now');
                        $('.send_btn_s').removeAttr("disabled");
                        // loader(false);
                        setTimeout(function() {
                            $('#'+'modal_error_s').html("");
                        }, 4000);
                        }

                    } else {
                        $('#'+'modal_error_s').html(obj.Message);
                        $('.send_btn_s').html('Send Now');
                        $('.send_btn_s').removeAttr("disabled");
                        // loader(false);
                    }

                };
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
