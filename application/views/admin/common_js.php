<!-- jQuery 2.2.3 -->
<script src="<?php echo $this->back_assets; ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo $this->back_assets; ?>bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $this->back_assets; ?>dist/js/app.min.js"></script>
<!-- FastClick -->
<script src="<?php echo $this->back_assets; ?>plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $this->back_assets; ?>dist/js/demo.js"></script>

<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  // $.widget.bridge('uibutton', $.ui.button);
</script>

<?php if (isset($dashboard)) { ?>
  <!-- <script src="<?php echo $this->back_assets; ?>plugins/knob/jquery.knob.js"></script> -->
  <!-- daterangepicker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
  <!-- <script src="<?php echo $this->back_assets; ?>plugins/daterangepicker/daterangepicker.js"></script> -->
  <!-- datepicker -->
  <!-- <script src="<?php echo $this->back_assets; ?>plugins/datepicker/bootstrap-datepicker.js"></script> -->
  <!-- Bootstrap WYSIHTML5 -->
  <!-- <script src="<?php echo $this->back_assets; ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script> -->
  <!-- Slimscroll -->
  <!-- <script src="<?php echo $this->back_assets; ?>plugins/slimScroll/jquery.slimscroll.min.js"></script> -->
<?php } ?>

<?php if (isset($dashboard)) : ?>
  <script src="<?php echo $this->back_assets; ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="<?php echo $this->back_assets; ?>dist/js/pages/dashboard.js"></script>
<?php endif; ?>

<?php if (isset($member_manage) || isset($numbertype_manage) || isset($numbersubtype_manage) || isset($bid_manage) || isset($bid_view) || isset($fee_manage) || isset($coupon_manage) || isset($duration_manage) || isset($user_manage) || isset($ad_manage)) : ?>
  <!-- DataTables -->
  <script src="<?php echo $this->back_assets; ?>plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?php echo $this->back_assets; ?>plugins/datatables/dataTables.bootstrap.min.js"></script>
  <!-- <script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script> -->
  <script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js"></script>
  <script src="<?php echo $this->back_assets; ?>plugins/datepicker/bootstrap-datepicker.js"></script>
<?php endif; ?>

<?php if (isset($numbertype_form) || isset($numbersubtype_form) || isset($fee_form) || isset($coupon_form) || isset($duration_form) || isset($setting) || isset($ad_form)) { ?>
  <script src="<?php echo $this->back_assets; ?>js/jquery.validate.js"></script>
<?php } ?>

<?php if (isset($coupon_form)) { ?>
  <!-- bootstrap datepicker -->
  <script src="<?php echo $this->back_assets; ?>plugins/datepicker/bootstrap-datepicker.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<?php } ?>
<?php if (isset($dashboard)) { ?>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<?php } ?>

<?php if (isset($setting)) { ?>
  <script src="<?php echo $this->back_assets; ?>plugins/ckeditor/ckeditor.js"></script>
<?php } ?>





<?php if (isset($order_form)) : ?>
  <script src="<?php echo $this->back_assets; ?>js/jquery.validate.js"></script>
<?php endif; ?>

<?php if (isset($product_manage)) : ?>
  <!-- DataTables -->
  <script src="<?php echo $this->back_assets; ?>plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?php echo $this->back_assets; ?>plugins/datatables/dataTables.bootstrap.min.js"></script>
<?php endif; ?>

<?php if (isset($product_form)) : ?>
  <script src="<?php echo $this->back_assets; ?>js/jquery.validate.js"></script>
<?php endif; ?>




<div class="modal fade" id="print_modal" role="dialog">
  <div class="modal-dialog">

    <!-- <form role="form" id="print_Form" method="post" action=""> -->
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
          <!-- <span class="sr-only">Close</span> -->
        </button>
        <h4 class="modal-title" id="myModalLabel">Send Bulk Notification</h4>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <div id="modal_error"></div>
        <div class="form-group">
          <label>Select Notification Type</label>
          <select class="form-control" id="notification_type">
            <option value="">Select</option>
            <option value="users">All Users</option>
          </select>
        </div>
        <div class="form-group">
          <label>Notification Title</label>
          <input type="text" id="notification_title" class="form-control" placeholder="Notification Title">
        </div>
        <div class="form-group">
          <label>Notification Message</label>
          <input type="text" id="notification_msg" class="form-control" placeholder="Notification message">
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer with-border">
        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancel</button>
        <button class="btn btn-warning btn-flat send_btn" onclick="sendBulkNotification()"> Send Now</button>
      </div>
    </div>
    <!-- </form> -->

  </div>
</div>


    <script>
      // function sendNotification() {
      //   var modalBody = '<div class="row"><div class="col-md-12 font-weight-bold">' +
      //     '<div class="col-md-12"><label>Select Notification Type</label>' +
      //     '<select class="form-control" id="notification_type"><option value="">Select</option>' +
      //     '<option value="users">All Users</option><option value="providers">All Providers</option>' +
      //     '<option value="both">Both</option></select>' +
      //     '</div>' +
      //     '<div class="col-md-12"><label>Notification Title</label>' +
      //     '<input type="text" id="notification_title" class="form-control" placeholder="Notification Title"></div>' +


      //     '<div class="col-md-12"><label>Notification Message</label>' +
      //     '<input type="text" id="notification_msg" class="form-control" placeholder="Notification message"></div>' +
      //     '</div>' +
      //     '<div class="col-md-12 text-right mt-3"><button data-dismiss="modal" ' +
      //     'class="btn btn-danger">Cancel</button>&nbsp;&nbsp;<button class="btn btn-info" ' +
      //     'onclick=sendBulkNotification()> Send Now</button></div></div>'
      //   showModal("Send Bulk Notification", modalBody, "green", null, "60");
      //   $('#modal_error').html("");
      // }

      function sendBulkNotification() {
        let notification_type = $("#notification_type").val();
        let notification_title = $("#notification_title").val();
        let notification_msg = $("#notification_msg").val();

        if (notification_type == "") {
          $err = '<div class="alert alert-danger alert-dismissible">'+
                      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+
                      'Please select notification type'+
                  '</div>';
          $('#modal_error').html($err);
          return false;
        }
        if (notification_type == "" || notification_title == "" || notification_msg == "") {
          $err = '<div class="alert alert-danger alert-dismissible">'+
                      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+
                      'Please enter notification title & message'+
                  '</div>';
          $('#modal_error').html($err);
          return false;
        }
        $('#modal_error').html("");

        $('.send_btn').html('Sending...');
        $('.send_btn').prop('disabled','true');

        let url = "<?php echo base_url().ADMINPATH; ?>" + "dashboard/send_bulk_notification";
        let xhr = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('API_KEY', '<?php echo FCM_KEY; ?>');
        formData.append('notification_type', notification_type);
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
              $('#modal_error').html($err);
              $('.send_btn').html('Send Now');
              $('.send_btn').removeAttr("disabled");

              // loader(false);
              return false;
            } else {
              console.log('success');
              $success = '<div class="alert alert-success alert-dismissible">'+
                      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+
                      message+
                  '</div>';
              $('#modal_error').html($success);
              $("#notification_title").val("");
              $("#notification_msg").val("");

              $('.send_btn').html('Send Now');
              $('.send_btn').removeAttr("disabled");
              // loader(false);
              setTimeout(function() {
                $('#modal_error').html("");
              }, 4000);
            }

          } else {
            $('#modal_error').html(obj.Message);
            $('.send_btn').html('Send Now');
            $('.send_btn').removeAttr("disabled");
            // loader(false);
          }
        };
      }
    </script>