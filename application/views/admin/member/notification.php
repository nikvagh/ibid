<div class="modal-dialog">

    <!-- <form role="form" id="print_Form" method="post" action=""> -->
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span>
                <!-- <span class="sr-only">Close</span> -->
            </button>
            <h4 class="modal-title" id="myModalLabel">Send Notification</h4>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
            <div id="modal_error_s"></div>
            <div class="form-group">
                <label>Notification Title</label>
                <input type="text" id="notification_title_s" class="form-control" placeholder="Notification Title">
            </div>
            <div class="form-group">
                <label>Notification Message</label>
                <input type="text" id="notification_msg_s" class="form-control" placeholder="Notification message">
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer with-border">
            <input type="hidden" name="notification_member_id" name="notification_member_id" id="notification_member_id" value="<?php echo $member_id; ?>"/>
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancel</button>
            <button class="btn btn-warning btn-flat send_btn_s" onclick="send_Single_Notification()"> Send Now</button>
        </div>
    </div>
    <!-- </form> -->

</div>