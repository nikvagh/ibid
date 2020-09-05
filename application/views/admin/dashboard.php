<!DOCTYPE html>
<html>

<head>
  <?php $this->load->view(ADMINPATH.'head'); ?>
  <title><?php //echo $this->system->company_name;?> <?php echo $title;?></title>
  <?php $this->load->view(ADMINPATH.'common_css'); ?>
  <style>
    .icon-box{
      position: absolute;
      right: 10px;
      top: 10px;
    }
    .icon-box img{
      width: 60px;
    }
    @media only screen and (max-width: 767px) {
      .icon-box img{
        display: none;
      }
    }
    /* ======================== */
      .highcharts-credits{
        display: none;
      }
      .highcharts-root {
        font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif!important;
      }
    /* ====================== */

    .new_graph_box{
      margin-bottom: 30px;
    }
    /* .new_graph_box figure > div{
      height: 500px;
    } */

  </style>
</head>

<body class="hold-transition skin-yellow sidebar-mini">
  <div class="wrapper">

    <?php $this->load->view(ADMINPATH.'header'); ?>
    <?php $this->load->view(ADMINPATH.'sidebar'); ?>
    
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Dashboard
          <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Dashboard</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
              <div class="inner">
                <h3><?php echo $total_bids; ?></h3>
                <p>Bids</p>
              </div>
              <div class="icon-box">
                <!-- <i class="bid_icon_dash"></i> -->
                <img src="<?php echo $this->back_assets.'dist/img/1.png'; ?>" alt="">
              </div>
              <a href="<?php echo base_url().ADMINPATH; ?>bid" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><?php echo $total_go_bids; ?></h3>
                <p>OnGoing Bids</p>
              </div>
              <div class="icon-box">
                <!-- <i class="fa fa-hand-pointer-o"></i> -->
                <img src="<?php echo $this->back_assets.'dist/img/2.png'; ?>" alt="">
              </div>
              <a href="<?php echo base_url().ADMINPATH; ?>bid" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3><?php echo $total_completed_bids; ?><sup style="font-size: 20px"></sup></h3>
                <p>Completed Bids</p>
              </div>
              <div class="icon-box">
                <!-- <i class="fa fa-smile-o"></i> -->
                <img src="<?php echo $this->back_assets.'dist/img/3.png'; ?>" alt="">
              </div>
              <a href="<?php echo base_url().ADMINPATH; ?>bid" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3><?php echo $total_members; ?></h3>
                <p>User Registrations</p>
              </div>
              <div class="icon-box">
                <!-- <i class="ion ion-person-add"></i> -->
                <img src="<?php echo $this->back_assets.'dist/img/4.png'; ?>" alt="">
              </div>
              <a href="<?php echo base_url().ADMINPATH; ?>member" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">

          <!-- <section class="col-md-6">
            <div class="box bg-sidebar border-rd-20">
              <div class="box-header">
                <div class="col-md-6">
                  <h3 class="box-title color-white">MEMBERS</h3>
                </div>
                <div class="col-md-6 text-right">
                  <button id="year_btn" class="btn btn-sm btn-flat">Years</button>
                  <button id="month_btn" class="btn btn-sm btn-flat">Month</button>
                  <button id="day_btn" class="btn btn-sm btn-flat">Day</button>
                </div>
              </div>

              <div class="box-body">
                <canvas id="graph"></canvas>
                <br/>
              </div>
            </div>
          </section> -->

          <div class="col-md-6 new_graph_box">
            <figure class="highcharts-figure">
              <div id="container1"></div>
              <p class="highcharts-description"> </p>
            </figure>

            <button id="year_btn11" class="btn btn-sm btn-flat btn-default">Years</button>
            <button id="month_btn11" class="btn btn-sm btn-flat btn-default">Month</button>
            <button id="day_btn11" class="btn btn-sm btn-flat btn-default">Day</button>
          </div>

          <!-- <section class="col-md-6">
            <div class="box bg-sidebar border-rd-20">
              <div class="box-header">
                <div class="col-md-6">
                  <h3 class="box-title color-white">BIDS</h3>
                </div>
                <div class="col-md-6 text-right">
                  <button id="year_btn2" class="btn btn-sm btn-flat">Years</button>
                  <button id="month_btn2" class="btn btn-sm btn-flat">Month</button>
                  <button id="day_btn2" class="btn btn-sm btn-flat">Day</button>
                </div>
              </div>
              <div class="box-body">
                <canvas id="graph2"></canvas>
                <br/>
              </div>
            </div>
          </section> -->

          <div class="col-md-6 new_graph_box">
            <figure class="highcharts-figure">
              <div id="container2"></div>
              <p class="highcharts-description"> </p>
            </figure>

            <button id="year_btn22" class="btn btn-sm btn-flat btn-default">Years</button>
            <button id="month_btn22" class="btn btn-sm btn-flat btn-default">Month</button>
            <button id="day_btn22" class="btn btn-sm btn-flat btn-default">Day</button>
          </div>

          <!-- <section class="col-md-6">
            <div class="box bg-sidebar border-rd-20">
              <div class="box-header">
                <div class="col-md-6">
                  <h3 class="box-title color-white">Transactions</h3>
                </div>
                <div class="col-md-6 text-right">
                  <button id="year_btn3" class="btn btn-sm btn-flat">Years</button>
                  <button id="month_btn3" class="btn btn-sm btn-flat">Month</button>
                  <button id="day_btn3" class="btn btn-sm btn-flat">Day</button>
                </div>
              </div>
              <div class="box-body">
                <canvas id="graph3"></canvas>
                <br/>
              </div>
            </div>
          </section> -->

          <div class="col-md-6 new_graph_box">
            <figure class="highcharts-figure">
              <div id="container3"></div>
              <p class="highcharts-description"> </p>
            </figure>

            <button id="year_btn33" class="btn btn-sm btn-flat btn-default">Years</button>
            <button id="month_btn33" class="btn btn-sm btn-flat btn-default">Month</button>
            <button id="day_btn33" class="btn btn-sm btn-flat btn-default">Day</button>
          </div>
          
        </div>
        <!-- /.row (main row) -->

      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php $this->load->view(ADMINPATH.'footer'); ?>
  </div>
  <!-- ./wrapper -->
  
  <?php $this->load->view(ADMINPATH.'common_js'); ?>

  <script>
    base_url = '<?php base_url().ADMINPATH; ?>';

    // var config_sales = {
    //   fillColor: "rgba(206,132,15,1)"
    // };

    // var config_sales2 = {
    //   fillColor: "rgba(0,0,0,1)"
    // };

    // var config_sales3 = {
    //   fillColor: "rgba(206,132,15,1)"
    // };

    // var context = document.querySelector('#graph').getContext('2d');
    // let _stroke = context.stroke;
    // context.stroke;
    // context.stroke = function() {
    //     context.save();
    //     context.shadowColor = '#E56590';
    //     context.shadowBlur = 40;
    //     context.shadowOffsetX = 0;
    //     context.shadowOffsetY = 20;
    //     _stroke.apply(this, arguments)
    //     context.restore();
    // }

    // var context2 = document.querySelector('#graph2').getContext('2d');
    // let _stroke2 = context.stroke;
    // context2.stroke;
    // context2.stroke = function() {
    //   context2.save();
    //   context2.shadowColor = '#E56590';
    //   context2.shadowBlur = 40;
    //   context2.shadowOffsetX = 0;
    //   context2.shadowOffsetY = 20;
    //   _stroke2.apply(this, arguments)
    //   context2.restore();
    // }

    // var context3 = document.querySelector('#graph3').getContext('2d');
    // let _stroke3 = context.stroke;
    // context3.stroke;
    // context3.stroke = function() {
    //   context3.save();
    //   context3.shadowColor = '#E56590';
    //   context3.shadowBlur = 40;
    //   context3.shadowOffsetX = 0;
    //   context3.shadowOffsetY = 20;
    //   _stroke3.apply(this, arguments)
    //   context3.restore();
    // }

    // ===================

      // $("#year_btn").on("click", function() {
      //   member_function('yearData','Member Registeration');
      // });
      // $("#month_btn").on("click", function() {
      //   member_function('monthData','Member Registeration');
      // });
      // $("#day_btn").on("click", function() {
      //   member_function('dayData','Member Registeration');
      // });

      // function member_function(time,label){
      //   $.ajax({
      //     url: base_url+'dashboard/memberData',
      //     type: 'post',
      //     data: {name:time},
      //     dataType: 'json',
      //     success: function(response){

      //           labels_new = [];y_axis_new = [];
      //           $.each(response, function(index, value){
      //             // if(value.year != "" && value.year != null){
      //               labels_new[index] = value.xaxis;
      //               y_axis_new[index] = value.total;
      //             // }
      //           });

      //           new_data = {
      //             labels: labels_new,
      //             datasets: [
      //                 {
      //                   label:label,
      //                   // fillColor: config_sales.fillColor,
      //                   // strokeColor: config_sales.strokeColor,
      //                   // pointStrokeColor: config_sales.pointStrokeColor,
      //                   // pointHighlightFill: config_sales.pointHighlightFill,
      //                   // pointHighlightStroke: config_sales.pointHighlightStroke,
      //                   backgroundColor: 'rgba(85, 80, 208, 0.9)',
      //                   borderColor: '#c182ff',
      //                   pointBackgroundColor: '#ffffff',
      //                   pointBorderColor: '#ffffff',
      //                   pointRadius: 3,
      //                   borderWidth: 4,
      //                   data: y_axis_new,
      //                 }
      //             ],
      //           };

      //           myChart = new Chart(context , {type: "line",data: new_data,options: {
      //             scales: {
      //                 xAxes: [{
      //                     ticks: {
      //                         // beginAtZero: true,
      //                         // userCallback: function(label, index, labels) {
      //                         //     // when the floored value is the same as the value we have a whole number
      //                         //     if (Math.floor(label) === label) {
      //                         //         return label;
      //                         //     }
      //                         // },
      //                         fontColor: '#b8c7ce',
      //                     },
      //                     gridLines: {
      //                       borderDash: [2, 1],
      //                       color: "#808F96"
      //                     }
      //                 }],
      //                 yAxes: [{
      //                     ticks: {
      //                         beginAtZero: true,
      //                         userCallback: function(label, index, labels) {
      //                             // when the floored value is the same as the value we have a whole number
      //                             if (Math.floor(label) === label) {
      //                                 return label;
      //                             }
      //                         },
      //                         fontColor: '#b8c7ce',
      //                     },
      //                     gridLines: {
      //                       borderDash: [2, 1],
      //                       color: "#808F96"
      //                     }
      //                 }]
      //             }
      //           }

      //         });

      //     }
      //   });
      // }

      // =================

      // $("#year_btn2").on("click", function() {
      //   bid_function('yearData','Bids');
      // });
      // $("#month_btn2").on("click", function() {
      //   bid_function('monthData','Bids');
      // });
      // // $("#week_btn").on("click", function() {
      // //   member_function('weekData','Member Registeration');
      // // });
      // $("#day_btn2").on("click", function() {
      //   bid_function('dayData','Bids');
      // });

      // function bid_function(time,label){
      //   // alert('ddd');
      //   $.ajax({
      //     url: base_url+'dashboard/bidsData',
      //     type: 'post',
      //     data: {name:time},
      //     dataType: 'json',
      //     success: function(response){

      //       // console.log(response);

      //       labels_new = [];y_axis_new = [];
      //       $.each(response, function(index, value){
      //         // if(value.year != "" && value.year != null){
      //           labels_new[index] = value.xaxis;
      //           y_axis_new[index] = value.total;
      //         // }
      //       });

      //       // console.log(labels_new);
      //       // console.log(y_axis_new);

      //       new_data = {
      //         labels: labels_new,
      //         datasets: [
      //             {
      //               label:label,
      //               // fillColor: config_sales2.fillColor,
      //               // strokeColor: config_sales2.strokeColor,
      //               // pointStrokeColor: config_sales2.pointStrokeColor,
      //               // pointHighlightFill: config_sales2.pointHighlightFill,
      //               // pointHighlightStroke: config_sales2.pointHighlightStroke,
      //               backgroundColor: 'rgba(85, 80, 208, 0.8)',
      //               borderColor: '#c182ff',
      //               pointBackgroundColor: '#ffffff',
      //               pointBorderColor: '#ffffff',
      //               pointRadius: 3,
      //               borderWidth: 4,
      //               data: y_axis_new
      //             }
      //         ]
      //       };

      //         myChart2 = new Chart(context2 , { type: "line",data: new_data,options: {
      //               scales: {
      //                 xAxes: [{
      //                     ticks: {
      //                         // beginAtZero: true,
      //                         // userCallback: function(label, index, labels) {
      //                         //     // when the floored value is the same as the value we have a whole number
      //                         //     if (Math.floor(label) === label) {
      //                         //         return label;
      //                         //     }
      //                         // },
      //                         fontColor: '#b8c7ce',
      //                     },
      //                     gridLines: {
      //                       borderDash: [2, 1],
      //                       color: "#808F96"
      //                     }
      //                 }],
      //                 yAxes: [{
      //                     ticks: {
      //                         beginAtZero: true,
      //                         userCallback: function(label, index, labels) {
      //                             // when the floored value is the same as the value we have a whole number
      //                             if (Math.floor(label) === label) {
      //                                 return label;
      //                             }
      //                         },
      //                         fontColor: '#b8c7ce',
      //                     },
      //                     gridLines: {
      //                       borderDash: [2, 1],
      //                       color: "#808F96"
      //                     }
      //                 }]
      //               }
      //           }
      //         });
      //     }
      //   });
      // }

      // ================

      // $("#year_btn3").on("click", function() {
      //   trans_function('yearData','Transactions');
      // });
      // $("#month_btn3").on("click", function() {
      //   trans_function('monthData','Transactions');
      // });
      // // $("#week_btn").on("click", function() {
      // //   member_function('weekData','Member Registeration');
      // // });
      // $("#day_btn3").on("click", function() {
      //   trans_function('dayData','Transactions');
      // });

      // function trans_function(time,label){
      //   // alert('ddd');
      //   $.ajax({
      //     url: base_url+'dashboard/transData',
      //     type: 'post',
      //     data: {name:time},
      //     dataType: 'json',
      //     success: function(response){
      //       // console.log(response);

      //         labels_new = [];y_axis_new = [];
      //         $.each(response, function(index, value){
      //           // if(value.year != "" && value.year != null){
      //             labels_new[index] = value.xaxis;
      //             y_axis_new[index] = value.total;
      //           // }
      //         });

      //         // console.log(labels_new);
      //         // console.log(y_axis_new);

      //         new_data = {
      //           labels: labels_new,
      //           datasets: [
      //               {
      //                 label:label,
      //                 // fillColor: config_sales3.fillColor,
      //                 // strokeColor: config_sales3.strokeColor,
      //                 // pointStrokeColor: config_sales3.pointStrokeColor,
      //                 // pointHighlightFill: config_sales3.pointHighlightFill,
      //                 // pointHighlightStroke: config_sales3.pointHighlightStroke,
      //                 backgroundColor: '#5550d0',
      //                 borderColor: '#c182ff',
      //                 pointBackgroundColor: '#ffffff',
      //                 pointBorderColor: '#ffffff',
      //                 pointRadius: 3,
      //                 borderWidth: 4,
      //                 data: y_axis_new
      //               }
      //           ]
      //         };

      //         myChart3 = new Chart(context3 , {type: "line",data: new_data,options: {
      //               scales: {
      //                 xAxes: [{
      //                     ticks: {
      //                         // beginAtZero: true,
      //                         // userCallback: function(label, index, labels) {
      //                         //     // when the floored value is the same as the value we have a whole number
      //                         //     if (Math.floor(label) === label) {
      //                         //         return label;
      //                         //     }
      //                         // },
      //                         fontColor: '#b8c7ce',
      //                     },
      //                     gridLines: {
      //                       borderDash: [2, 1],
      //                       color: "#808F96"
      //                     }
      //                 }],
      //                 yAxes: [{
      //                     ticks: {
      //                         beginAtZero: true,
      //                         userCallback: function(label, index, labels) {
      //                             // when the floored value is the same as the value we have a whole number
      //                             if (Math.floor(label) === label) {
      //                                 return label;
      //                             }
      //                         },
      //                         fontColor: '#b8c7ce',
      //                     },
      //                     gridLines: {
      //                       borderDash: [2, 1],
      //                       color: "#808F96"
      //                     }
      //                 }]
      //               }
      //           }
      //         });

      //     }
      //   });
      // }

    // ==============
  </script>


    <!-- <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script> -->


    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <!-- <script src="https://code.highcharts.com/stock/highstock.js"></script> -->
    <!-- <script src="https://code.highcharts.com/modules/data.js"></script> -->
    <!-- <script src="https://code.highcharts.com/highcharts-more.js"></script> -->
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script>

      $(document).ready( function () {
        $("#month_btn11").trigger('click');
        $("#month_btn22").trigger('click');
        $("#month_btn33").trigger('click');
      });

      function tooltip_day() {
        var tooltip = Highcharts.dateFormat('%A %b %e, %Y', new Date(this.x));
        var data = hc1.series[0].data;
        var index = this.point.index;
        tooltip += '<br><span style="color:' + this.series.color + '">' + this.series.name + '</span>: '+ this.y;
        return tooltip;
      }

      function tooltip_month() {
        // console.log(this.x);
        month_new_date = new Date(this.x);
        month_new_date.setDate(month_new_date.getDate() + 1);

        var tooltip = Highcharts.dateFormat('%b, %Y', new Date(month_new_date));
        var data = hc1.series[0].data;
        var index = this.point.index;
        tooltip += '<br><span style="color:' + this.series.color + '">' + this.series.name + '</span>: '+ this.y;
        return tooltip;
      }

      function tooltip_year() {
        var tooltip = Highcharts.dateFormat('%Y', new Date(this.x));
        var data = hc1.series[0].data;
        var index = this.point.index;
        tooltip += '<br><span style="color:' + this.series.color + '">' + this.series.name + '</span>: '+ this.y;
        return tooltip;
      }

        // var tooltip1 = tooltip_day;
        // time = 'dayData1';
        // var dayAjax1 = $.parseJSON(
        //                 $.ajax({
        //                     url: base_url+'dashboard/memberData',
        //                     type: 'POST',
        //                     data: {name:time},
        //                     cache: false,
        //                     dataType: 'json',
        //                     async: false
        //                 }).responseText);

        // alert(dayAjax1);
        // console.log(dayAjax1);

        array = [];

        // $.each( dayAjax1, function( index, value ){
        //   // console.log(value[0]);
        //   array0 = [];
        //   date = new Date(value[0]).getTime();
        //   array0.push(date);
        //   array0.push(parseInt(value[1]));
        //   array.push(array0);
        // });

        hc1 =  Highcharts.chart("container1", {
        title: {
          text: "Member Registartion Growth ",
          style: {
            color: '#E0E0E3'
          }
        },
        chart: {
          type: 'areaspline',
          backgroundColor: '#242e33',
          height: 400
        },

        yAxis: {
          title: {
            text: "Number of Members",
            style: {
              color: '#E0E0E3'
            }
          },
          allowDecimals: false,
          labels: {
            style: {
              color: '#E0E0E3'
            }
          }
        },

        xAxis: {
          type: 'datetime',
          tickInterval: 30 * 24 * 3600 * 1000,
          // type: 'datetime',
          // tickInterval: 24 * 3600 * 1000,
          labels: {
            style: {
              color: '#E0E0E3'
            }
          }
        },

        tooltip: {
          formatter: function() {
              return tooltip1.call(this)
          }
        },

        legend: {
          itemStyle: {
            color: '#E0E0E3'
          }  
        },

        plotOptions: {
          series: {
            // label: {
            //   connectorAllowed: false,
            // },
            pointStart: 1,
          },
        },

        series: [
          {
            name: "Registration",
            data: array,
            color: {
                linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
                stops: [
                    [0, '#c182ff'],
                    [1, '#504cc1']
                ]
            }
          },
        ],
        
      });

      $('#day_btn11').click(function(){
        member_function11('dayData1');
      });
      $('#month_btn11').click(function(){
        member_function11('monthData1');
      });
      $('#year_btn11').click(function(){
        member_function11('yearData1');
      });

      function member_function11(time){
        var dayAjax1 = $.parseJSON(
                        $.ajax({
                            url: base_url+'dashboard/memberData',
                            type: 'POST',
                            data: {name:time},
                            cache: false,
                            dataType: 'json',
                            async: false
                        }).responseText);

        if(time == "dayData1"){
          tooltip1 = tooltip_day;
        }else if(time == "monthData1"){
          tooltip1 = tooltip_month;
        }else if(time == "yearData1"){
          tooltip1 = tooltip_year;
        }

        array = [];
        $.each( dayAjax1, function( index, value ){
          // console.log(value[0]);
          array0 = [];
          date = new Date(value[0]).getTime();
          array0.push(date);
          array0.push(parseInt(value[1]));
          array.push(array0);
        });

        console.log(array);

        hc1.series[0].remove();
        hc1.addSeries({
          name: "Registration",
          data: array,
          color: {
              linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
              stops: [
                  [0, '#c182ff'],
                  [1, '#504cc1']
              ]
          }
        });

      }




      // ===============================================

        array2 = [];

        // $.each( dayAjax1, function( index, value ){
        //   // console.log(value[0]);
        //   array0 = [];
        //   date = new Date(value[0]).getTime();
        //   array0.push(date);
        //   array0.push(parseInt(value[1]));
        //   array.push(array0);
        // });

        hc2 =  Highcharts.chart("container2", {
        title: {
          text: "Bids ",
          style: {
            color: '#E0E0E3'
          }
        },
        chart: {
          type: 'areaspline',
          backgroundColor: '#242e33',
          height: 400
        },

        yAxis: {
          title: {
            text: "Number of Bids",
            style: {
              color: '#E0E0E3'
            }
          },
          allowDecimals: false,
          labels: {
            style: {
              color: '#E0E0E3'
            }
          }
        },

        xAxis: {
          type: 'datetime',
          tickInterval: 30 * 24 * 3600 * 1000,
          // type: 'datetime',
          // tickInterval: 24 * 3600 * 1000,
          labels: {
            style: {
              color: '#E0E0E3'
            }
          }
        },

        tooltip: {
          formatter: function() {
              return tooltip2.call(this)
          }
        },

        legend: {
          itemStyle: {
            color: '#E0E0E3'
          }  
        },

        plotOptions: {
          series: {
            // label: {
            //   connectorAllowed: false,
            // },
            pointStart: 1,
          },
        },

        series: [
          {
            name: "bids",
            data: array2,
            color: {
                linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
                stops: [
                    [0, '#c182ff'],
                    [1, '#504cc1']
                ]
            }
          },
        ],
        
      });

      $('#day_btn22').click(function(){
        bid_function11('dayData1');
      });
      $('#month_btn22').click(function(){
        bid_function11('monthData1');
      });
      $('#year_btn22').click(function(){
        bid_function11('yearData1');
      });

      function bid_function11(time){
        var dayAjax2 = $.parseJSON(
                        $.ajax({
                            url: base_url+'dashboard/bidsData',
                            type: 'POST',
                            data: {name:time},
                            cache: false,
                            dataType: 'json',
                            async: false
                        }).responseText);

        if(time == "dayData1"){
          tooltip2 = tooltip_day;
        }else if(time == "monthData1"){
          tooltip2 = tooltip_month;
        }else if(time == "yearData1"){
          tooltip2 = tooltip_year;
        }

        array2 = [];
        $.each( dayAjax2, function( index, value ){
          // console.log(value[0]);
          array0 = [];
          date = new Date(value[0]).getTime();
          array0.push(date);
          array0.push(parseInt(value[1]));
          array2.push(array0);
        });

        console.log(array2);

        hc2.series[0].remove();
        hc2.addSeries({
          name: "Bids",
          data: array2,
          color: {
              linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
              stops: [
                  [0, '#c182ff'],
                  [1, '#504cc1']
              ]
          }
        });

      }

      // =====================================


      array3 = [];

        hc3 =  Highcharts.chart("container3", {
        title: {
          text: "Transactions",
          style: {
            color: '#E0E0E3'
          }
        },
        chart: {
          type: 'areaspline',
          backgroundColor: '#242e33',
          height: 400
        },

        yAxis: {
          title: {
            text: "Amount",
            style: {
              color: '#E0E0E3'
            }
          },
          allowDecimals: false,
          labels: {
            style: {
              color: '#E0E0E3'
            }
          }
        },

        xAxis: {
          type: 'datetime',
          tickInterval: 30 * 24 * 3600 * 1000,
          // type: 'datetime',
          // tickInterval: 24 * 3600 * 1000,
          labels: {
            style: {
              color: '#E0E0E3'
            }
          }
        },

        tooltip: {
          formatter: function() {
              return tooltip3.call(this)
          }
        },

        legend: {
          itemStyle: {
            color: '#E0E0E3'
          }
        },

        plotOptions: {
          series: {
            // label: {
            //   connectorAllowed: false,
            // },
            pointStart: 1,
          },
        },

        series: [
          {
            name: "Transactions",
            data: array3,
            color: {
                linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
                stops: [
                    [0, '#c182ff'],
                    [1, '#504cc1']
                ]
            }
          },
        ],
        
      });

      $('#day_btn33').click(function(){
        trans_function11('dayData1');
      });
      $('#month_btn33').click(function(){
        trans_function11('monthData1');
      });
      $('#year_btn33').click(function(){
        trans_function11('yearData1');
      });

      function trans_function11(time){
        var dayAjax3 = $.parseJSON(
                        $.ajax({
                            url: base_url+'dashboard/transData',
                            type: 'POST',
                            data: {name:time},
                            cache: false,
                            dataType: 'json',
                            async: false
                        }).responseText);

        if(time == "dayData1"){
          tooltip3 = tooltip_day;
        }else if(time == "monthData1"){
          tooltip3 = tooltip_month;
        }else if(time == "yearData1"){
          tooltip3 = tooltip_year;
        }

        array3 = [];
        $.each( dayAjax3, function( index, value ){
          // console.log(value[0]);
          array0 = [];
          date = new Date(value[0]).getTime();
          array0.push(date);
          array0.push(parseInt(value[1]));
          array3.push(array0);
        });

        console.log(array3);

        hc3.series[0].remove();
        hc3.addSeries({
          name: "Transactions",
          data: array3,
          color: {
              linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
              stops: [
                  [0, '#c182ff'],
                  [1, '#504cc1']
              ]
          }
        });

      }

  </script>

</body>

</html>