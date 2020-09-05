<aside class="main-sidebar">
  <?php
  // echo "<pre>";
  // print_r($this->admin->loginData->name);
  // echo "</pre>";
  ?>
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <?php
        // echo PROFILE_PATH.'thumb/120x120_'.$this->session->userdata('loginData')->profile_pic;
        if (file_exists(PROFILE_PATH . 'thumb/120x120_' . $this->session->userdata('loginData')->profile_pic)) {
          // $profile_pic = '../../'.PROFILE_PATH.'thumb/120x120_'.$this->session->userdata('loginData')->profile_pic;
          $profile_pic = base_url() . PROFILE_PATH . 'thumb/120x120_' . $this->session->userdata('loginData')->profile_pic;
        } else {
          $profile_pic = $this->back_assets . 'dist/img/avatar5.png';
        }
        ?>
        <img src="<?php echo $profile_pic; ?>" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p class="text-uppercase"><?php echo $this->admin->loginData->name; ?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>

    <!-- search form -->
    <!-- <form action="#" method="get" class="sidebar-form">
          <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Search...">
            <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
          </div>
        </form> -->
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->

    <?php $page_selected = $this->uri->segment(2); ?>

    <ul class="sidebar-menu">
      <li class="header">MAIN NAVIGATION</li>

      <li class="<?php if ($page_selected == 'dashboard' || $page_selected == '') {
                    echo 'active';
                  } ?>">
        <a href="<?php echo base_url() . ADMINPATH; ?>dashboard">
          <!-- <i class="fa fa-dashboard"></i>  -->
          <img src="<?php echo $this->back_assets . 'dist/img/icons/Dashboard.png'; ?>" width="20px" /> &nbsp;
          <span>Dashboard <?php //echo $this->uri->segment(2); 
                          ?></span>
        </a>
      </li>

      <?php if ($this->accessibility->check_access1('member')) { ?>
        <li class="<?php if ($page_selected == 'member') {
                      echo 'active';
                    } ?>">
          <a href="<?php echo base_url() . ADMINPATH; ?>member">
            <!-- <i class="fa fa-users"></i>  -->
            <img src="<?php echo $this->back_assets . 'dist/img/icons/Members.png'; ?>" width="20px" /> &nbsp;
            <span>Members</span>
          </a>
        </li>
      <?php } ?>

      <?php
      $numbertype_acc = $this->accessibility->check_access1('numbertype');
      $numbersubtype_acc = $this->accessibility->check_access1('numbersubtype');
      ?>

      <?php //if ($numbertype_acc || $numbersubtype_acc) { ?>
        <!-- <li class="treeview <?php //if ($page_selected == 'numbertype' || $page_selected == 'numbersubtype') {
                              //echo 'active';
                            //} ?>">
          <a href="#">
            <img src="<?php //echo $this->back_assets . 'dist/img/icons/Number Type.png'; ?>" width="20px" /> &nbsp;
            <span>Number Type</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php //if ($numbertype_acc) { ?>
              <li class="<?php //if ($page_selected == 'numbertype') {
                            //echo 'active';
                          //} ?>"><a href="<?php //echo base_url() . ADMINPATH; ?>numbertype"><i class="fa fa-circle-o"></i> Number Types </a></li>
            <?php //} ?>
            <?php //if ($numbersubtype_acc) { ?>
              <li class="<?php //if ($page_selected == 'numbersubtype') {
                            //echo 'active';
                          //} ?>"><a href="<?php //echo base_url() . ADMINPATH; ?>numbersubtype"><i class="fa fa-circle-o"></i> Numbers Sub Types</a></li>
            <?php //} ?>
          </ul>
        </li> -->
      <?php //} ?>

      <?php if ($this->accessibility->check_access1('bid')) { ?>
        <li class="<?php if ($page_selected == 'bid') {
                      echo 'active';
                    } ?>">
          <a href="<?php echo base_url() . ADMINPATH; ?>bid">
            <!-- <i class="fa fa-hand-paper-o"></i>  -->
            <img src="<?php echo $this->back_assets . 'dist/img/icons/Bid.png'; ?>" width="20px" /> &nbsp;
            <span>Bids</span>
          </a>
        </li>
      <?php } ?>

      <?php if ($this->accessibility->check_access1('fee')) { ?>
        <li class="<?php if ($page_selected == 'fee') {
                      echo 'active';
                    } ?>">
          <a href="<?php echo base_url() . ADMINPATH; ?>fee">
            <!-- <i class="fa fa-credit-card"></i>  -->
            <img src="<?php echo $this->back_assets . 'dist/img/icons/Fees.png'; ?>" width="20px" /> &nbsp;
            <span>Fees</span>
          </a>
        </li>
      <?php } ?>

      <?php if ($this->accessibility->check_access1('duration')) { ?>
        <li class="<?php if ($page_selected == 'duration') {
                      echo 'active';
                    } ?>">
          <a href="<?php echo base_url() . ADMINPATH; ?>duration">
            <!-- <i class="fa fa-sun-o"></i>  -->
            <img src="<?php echo $this->back_assets . 'dist/img/icons/Days.png'; ?>" width="20px" /> &nbsp;
            <span>Days</span>
          </a>
        </li>
      <?php } ?>

      <?php if ($this->accessibility->check_access1('coupon')) { ?>
        <li class="<?php if ($page_selected == 'coupon') {
                      echo 'active';
                    } ?>">
          <a href="<?php echo base_url() . ADMINPATH; ?>coupon">
            <!-- <i class="fa fa-money"></i>  -->
            <img src="<?php echo $this->back_assets . 'dist/img/icons/Coupan.png'; ?>" width="20px" /> &nbsp;
            <span>Coupon</span>
          </a>
        </li>
      <?php } ?>

      <?php if ($this->accessibility->check_access1('ad')) { ?>
        <li class="<?php if ($page_selected == 'ad') {
                      echo 'active';
                    } ?>">
          <a href="<?php echo base_url() . ADMINPATH; ?>ad">
            <!-- <i class="fa fa-money"></i>  -->
            <img src="<?php echo $this->back_assets . 'dist/img/icons/Advertisment.png'; ?>" width="20px" /> &nbsp;
            <span>Ads</span>
          </a>
        </li>
      <?php } ?>

      <?php if ($this->accessibility->check_access1('setting')) { ?>
        <li class="<?php if ($page_selected == 'setting') {
                      echo 'active';
                    } ?>">
          <a href="<?php echo base_url() . ADMINPATH; ?>setting">
            <!-- <i class="fa fa-gear"></i>  -->
            <img src="<?php echo $this->back_assets . 'dist/img/icons/Settings.png'; ?>" width="20px" /> &nbsp;
            <span>Settings</span>
          </a>
        </li>
      <?php } ?>

      <?php if ($this->session->userdata('loginData')->user_type == "super_admin") { ?>
        <li class="<?php if ($page_selected == 'user') {
                      echo 'active';
                    } ?>">
          <a href="<?php echo base_url() . ADMINPATH; ?>user">
            <!-- <i class="fa fa-user-plus"></i>  -->
            <img src="<?php echo $this->back_assets . 'dist/img/icons/Admin User.png'; ?>" width="20px" /> &nbsp;
            <span>Admin User</span>
          </a>
        </li>
      <?php } ?>

      <li class="">
          <a data-target="#print_modal" data-toggle="modal" style="cursor: pointer;">
            <img src="<?php echo $this->back_assets . 'dist/img/icons/Advertisment.png'; ?>" width="20px" /> &nbsp;
            <span>Notification</span>
          </a>
      </li>

    </ul>

  </section>
  <!-- /.sidebar -->
</aside>