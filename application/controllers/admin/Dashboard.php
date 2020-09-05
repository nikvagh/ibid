<?php
    class Dashboard extends CI_Controller {
        function __construct()
        {
            parent::__construct();
            $this->load->model(ADMINPATH.'dashboard_model','dashboard');
            // $this->load->model('company_model','company');
            // $this->load->library('administration');
            checkLogin('admin');
        }
        function index()
        {
            $data['dashboard'] = TRUE;
            $data['title'] = "Dashboard";
            $data['view'] = "index";
            // $data['profile'] = $this->dashboard->get_admin();
            $data['total_members'] = $this->dashboard->get_total_members();
            $data['total_bids'] = $this->dashboard->get_total_bids();
            $data['total_go_bids'] = $this->dashboard->get_total_ongoing_bids();
            $data['total_completed_bids'] = $this->dashboard->get_total_completed_bids();
            // $data['companies'] = $this->company->get_companies();
            $this->load->view(ADMINPATH.'dashboard', $data);
        }

        function memberData(){

            if(isset($_POST['name'])){

                if($_POST['name'] == 'yearData'){
                    $this->db->select('YEAR(m.created_at) as xaxis,count(id) as total');
                    $this->db->from('members m');
                    $this->db->where('m.otp_varified','Y');
                    $this->db->group_by("xaxis");
                    $this->db->order_by("xaxis","ASC");
                    $query = $this->db->get();
                    // echo $this->db->last_query();

                    $result = array();
                    if ($query->num_rows() > 0) {
                        $result = $query->result_array();
                    }
                    echo json_encode($result);
                }

                if($_POST['name'] == 'monthData'){
                    $this->db->select('DATE_FORMAT(m.created_at, "%b %Y") as xaxis,count(id) as total');
                    $this->db->from('members m');
                    $this->db->where('m.otp_varified','Y');
                    $this->db->group_by("YEAR(m.created_at),MONTH(m.created_at)");
                    $this->db->order_by("m.created_at","ASC");
                    $query = $this->db->get();
                    // echo $this->db->last_query();

                    $result = array();
                    if ($query->num_rows() > 0) {
                        $result = $query->result_array();
                    }
                    echo json_encode($result);
                }

                // if($_POST['name'] == 'weekData'){
                //     $this->db->select('DATE_FORMAT(m.created_at, "%e %b %Y") as xaxis,count(id) as total');
                //     $this->db->from('members m');
                //     // $this->db->where('m.otp_varified','Y');
                //     $this->db->group_by("xaxis");
                //     $this->db->order_by("m.created_at","ASC");
                //     $query = $this->db->get();
                //     // echo $this->db->last_query();

                //     $result = array();
                //     if ($query->num_rows() > 0) {
                //         $result = $query->result_array();
                //     }
                //     echo json_encode($result);
                // }

                if($_POST['name'] == 'dayData'){
                    $this->db->select('DATE_FORMAT(m.created_at, "%e %b %Y") as xaxis,count(id) as total');
                    $this->db->from('members m');
                    $this->db->where('m.otp_varified','Y');
                    // $this->db->group_by("YEAR(m.created_at),MONTH(m.created_at),DAY(m.created_at)");
                    $this->db->group_by("xaxis");
                    $this->db->order_by("m.created_at","ASC");
                    $query = $this->db->get();
                    // echo $this->db->last_query();

                    $result = array();
                    if ($query->num_rows() > 0) {
                        $result = $query->result_array();
                    }
                    echo json_encode($result);
                }

                if($_POST['name'] == 'dayData1'){
                    // $this->db->select('DATE_FORMAT(m.created_at, "%e %b %Y") as xaxis,count(id) as total');
                    $this->db->select('DATE_FORMAT(m.created_at, "%e %b %Y") as xaxis,count(id) as total');
                    $this->db->from('members m');
                    $this->db->where('m.otp_varified','Y');
                    // $this->db->group_by("YEAR(m.created_at),MONTH(m.created_at),DAY(m.created_at)");
                    $this->db->group_by("xaxis");
                    $this->db->order_by("m.created_at","ASC");
                    $query = $this->db->get();
                    // echo $this->db->last_query();

                    $result1 = array();
                    if ($query->num_rows() > 0) {
                        $results = $query->result_array();

                        foreach($results as $key=>$val){
                            $time = date('Y-m-d H:i:s',strtotime($val['xaxis']));
                            $newArray = array(
                                $time,$val['total']
                            );
                            $result1[] = $newArray;
                        }

                    }
                    // echo "<pre>";print_r($result1);
                    echo json_encode($result1);
                }

                if($_POST['name'] == 'monthData1'){
                    $this->db->select('DATE_FORMAT(m.created_at, "%b %Y") as xaxis,count(id) as total');
                    $this->db->from('members m');
                    $this->db->where('m.otp_varified','Y');
                    $this->db->group_by("YEAR(m.created_at),MONTH(m.created_at)");
                    $this->db->order_by("m.created_at","ASC");
                    $query = $this->db->get();
                    // echo $this->db->last_query();

                    $result1 = array();
                    if ($query->num_rows() > 0) {
                        $results = $query->result_array();

                        foreach($results as $key=>$val){
                            $time = date('Y-m-d H:i:s',strtotime($val['xaxis']));
                            $newArray = array(
                                $time,$val['total']
                            );
                            $result1[] = $newArray;
                        }
                    }
                    echo json_encode($result1);
                }

                if($_POST['name'] == 'yearData1'){
                    $this->db->select('DATE_FORMAT(m.created_at, "%e %b %Y") as xaxis,YEAR(m.created_at) as year,count(id) as total');
                    $this->db->from('members m');
                    $this->db->where('m.otp_varified','Y');
                    $this->db->group_by("year");
                    $this->db->order_by("xaxis","ASC");
                    $query = $this->db->get();
                    // echo $this->db->last_query();

                    $result1 = array();
                    if ($query->num_rows() > 0) {
                        $results = $query->result_array();

                        foreach($results as $key=>$val){
                            $time = date('Y-m-d H:i:s',strtotime($val['xaxis']));
                            $newArray = array(
                                $time,$val['total']
                            );
                            // $newArray1 = array(
                            //     1752972336,'22'
                            // );
                            $result1[] = $newArray;
                            // $result1[] = $newArray1;
                        }
                    }

                    // echo "<pre>";
                    // print_r($result1);

                    echo json_encode($result1);
                }

            }

        }

        function bidsData(){

            if(isset($_POST['name'])){

                if($_POST['name'] == 'yearData'){
                    $this->db->select('YEAR(b.created_at) as xaxis,count(id) as total');
                    $this->db->from('bids b');
                    $this->db->where('b.status','Enable');
                    $this->db->where('b.live','Y');
                    $this->db->group_by("xaxis");
                    $this->db->order_by("xaxis","ASC");
                    $query = $this->db->get();
                    // echo $this->db->last_query();

                    $result = array();
                    if ($query->num_rows() > 0) {
                        $result = $query->result_array();
                    }
                    echo json_encode($result);
                }

                if($_POST['name'] == 'monthData'){
                    $this->db->select('DATE_FORMAT(b.created_at, "%b %Y") as xaxis,count(id) as total');
                    $this->db->from('bids b');
                    $this->db->where('b.status','Enable');
                    $this->db->where('b.live','Y');
                    // $this->db->group_by("YEAR(b.created_at),MONTH(b.created_at)");
                    $this->db->group_by("xaxis");
                    $this->db->order_by("b.created_at","ASC");
                    $query = $this->db->get();
                    // echo $this->db->last_query();

                    $result = array();
                    if ($query->num_rows() > 0) {
                        $result = $query->result_array();
                    }
                    echo json_encode($result);
                }

                if($_POST['name'] == 'dayData'){
                    $this->db->select('DATE_FORMAT(b.created_at, "%e %b %Y") as xaxis,count(id) as total');
                    $this->db->from('bids b');
                    $this->db->where('b.status','Enable');
                    $this->db->where('b.live','Y');
                    $this->db->group_by("xaxis");
                    $this->db->order_by("b.created_at","ASC");
                    $query = $this->db->get();
                    // echo $this->db->last_query();

                    $result = array();
                    if ($query->num_rows() > 0) {
                        $result = $query->result_array();
                    }
                    echo json_encode($result);
                }


                if($_POST['name'] == 'yearData1'){
                    $this->db->select('DATE_FORMAT(b.created_at, "%b %Y") as xaxis,YEAR(b.created_at) as year,count(id) as total');
                    $this->db->from('bids b');
                    $this->db->where('b.status','Enable');
                    $this->db->where('b.live','Y');
                    $this->db->group_by("year");
                    $this->db->order_by("xaxis","ASC");
                    $query = $this->db->get();
                    // echo $this->db->last_query();

                    $result1 = array();
                    if ($query->num_rows() > 0) {
                        $results = $query->result_array();

                        foreach($results as $key=>$val){
                            $time = date('Y-m-d H:i:s',strtotime($val['xaxis']));
                            $newArray = array(
                                $time,$val['total']
                            );
                            $result1[] = $newArray;
                        }
                    }
                    echo json_encode($result1);
                }

                if($_POST['name'] == 'monthData1'){
                    $this->db->select('DATE_FORMAT(b.created_at, "%b %Y") as xaxis,count(id) as total');
                    $this->db->from('bids b');
                    $this->db->where('b.status','Enable');
                    $this->db->where('b.live','Y');
                    // $this->db->group_by("YEAR(b.created_at),MONTH(b.created_at)");
                    $this->db->group_by("xaxis");
                    $this->db->order_by("b.created_at","ASC");
                    $query = $this->db->get();
                    // echo $this->db->last_query();

                    $result1 = array();
                    if ($query->num_rows() > 0) {
                        $results = $query->result_array();

                        foreach($results as $key=>$val){
                            $time = date('Y-m-d H:i:s',strtotime($val['xaxis']));
                            $newArray = array(
                                $time,$val['total']
                            );
                            $result1[] = $newArray;
                        }

                    }
                    echo json_encode($result1);
                }

                if($_POST['name'] == 'dayData1'){
                    $this->db->select('DATE_FORMAT(b.created_at, "%e %b %Y") as xaxis,count(id) as total');
                    $this->db->from('bids b');
                    $this->db->where('b.status','Enable');
                    $this->db->where('b.live','Y');
                    $this->db->group_by("xaxis");
                    $this->db->order_by("b.created_at","ASC");
                    $query = $this->db->get();
                    // echo $this->db->last_query();

                    $result1 = array();
                    if ($query->num_rows() > 0) {
                        $results = $query->result_array();

                        foreach($results as $key=>$val){
                            $time = date('Y-m-d H:i:s',strtotime($val['xaxis']));
                            $newArray = array(
                                $time,$val['total']
                            );
                            $result1[] = $newArray;
                        }

                    }
                    echo json_encode($result1);

                }

            }
        }

        function transData(){

            if(isset($_POST['name'])){

                if($_POST['name'] == 'yearData'){
                    $this->db->select('YEAR(b.created_at) as xaxis,SUM(total) as total');
                    $this->db->from('bids b');
                    $this->db->where('b.status','Enable');
                    $this->db->where('b.live','Y');
                    $this->db->group_by("xaxis");
                    $this->db->order_by("xaxis","ASC");
                    $query = $this->db->get();
                    // echo $this->db->last_query();

                    $result = array();
                    if ($query->num_rows() > 0) {
                        $result = $query->result_array();
                    }
                    echo json_encode($result);
                }

                if($_POST['name'] == 'monthData'){
                    $this->db->select('DATE_FORMAT(b.created_at, "%b %Y") as xaxis,SUM(total) as total');
                    $this->db->from('bids b');
                    $this->db->where('b.status','Enable');
                    $this->db->where('b.live','Y');
                    // $this->db->group_by("YEAR(b.created_at),MONTH(b.created_at)");
                    $this->db->group_by("xaxis");
                    $this->db->order_by("b.created_at","ASC");
                    $query = $this->db->get();
                    // echo $this->db->last_query();

                    $result = array();
                    if ($query->num_rows() > 0) {
                        $result = $query->result_array();
                    }
                    echo json_encode($result);
                }

                if($_POST['name'] == 'dayData'){
                    $this->db->select('DATE_FORMAT(b.created_at, "%e %b %Y") as xaxis,SUM(total) as total');
                    $this->db->from('bids b');
                    $this->db->where('b.status','Enable');
                    $this->db->where('b.live','Y');
                    $this->db->group_by("xaxis");
                    $this->db->order_by("b.created_at","ASC");
                    $query = $this->db->get();
                    // echo $this->db->last_query();

                    $result = array();
                    if ($query->num_rows() > 0) {
                        $result = $query->result_array();
                    }
                    echo json_encode($result);
                }

                if($_POST['name'] == 'yearData1'){
                    $this->db->select('DATE_FORMAT(b.created_at, "%b %Y") as xaxis,YEAR(b.created_at) as year,SUM(total) as total');
                    $this->db->from('bids b');
                    $this->db->where('b.status','Enable');
                    $this->db->where('b.live','Y');
                    $this->db->group_by("year");
                    $this->db->order_by("xaxis","ASC");
                    $query = $this->db->get();
                    // echo $this->db->last_query();

                    $result1 = array();
                    if ($query->num_rows() > 0) {
                        $results = $query->result_array();

                        foreach($results as $key=>$val){
                            $time = date('Y-m-d H:i:s',strtotime($val['xaxis']));
                            $newArray = array(
                                $time,$val['total']
                            );
                            $result1[] = $newArray;
                        }
                    }
                    echo json_encode($result1);
                }

                if($_POST['name'] == 'monthData1'){
                    $this->db->select('DATE_FORMAT(b.created_at, "%b %Y") as xaxis,SUM(total) as total');
                    $this->db->from('bids b');
                    $this->db->where('b.status','Enable');
                    $this->db->where('b.live','Y');
                    // $this->db->group_by("YEAR(b.created_at),MONTH(b.created_at)");
                    $this->db->group_by("xaxis");
                    $this->db->order_by("b.created_at","ASC");
                    $query = $this->db->get();
                    // echo $this->db->last_query();

                    $result1 = array();
                    if ($query->num_rows() > 0) {
                        $results = $query->result_array();

                        foreach($results as $key=>$val){
                            $time = date('Y-m-d H:i:s',strtotime($val['xaxis']));
                            $newArray = array(
                                $time,$val['total']
                            );
                            $result1[] = $newArray;
                        }
                    }
                    // echo "<pre>";
                    // print_r($result1);
                    echo json_encode($result1);
                }

                if($_POST['name'] == 'dayData1'){
                    $this->db->select('DATE_FORMAT(b.created_at, "%e %b %Y") as xaxis,SUM(total) as total');
                    $this->db->from('bids b');
                    $this->db->where('b.status','Enable');
                    $this->db->where('b.live','Y');
                    $this->db->group_by("xaxis");
                    $this->db->order_by("b.created_at","ASC");
                    $query = $this->db->get();
                    // echo $this->db->last_query();

                    $result1 = array();
                    if ($query->num_rows() > 0) {
                        $results = $query->result_array();

                        foreach($results as $key=>$val){
                            $time = date('Y-m-d H:i:s',strtotime($val['xaxis']));
                            $newArray = array(
                                $time,$val['total']
                            );
                            $result1[] = $newArray;
                        }
                    }
                    echo json_encode($result1);
                }

            }
        }

        function send_bulk_notification(){
            // echo "<pre>";print_r($_POST);
            // exit;

            $total = 0;
            $this->db->select('m.notification as member_notification,m.firebase_token,m.id');
            $this->db->where('m.notification','on');
            $this->db->where('m.otp_varified','Y');
            $this->db->where('m.status','Enable');
            if(isset($_POST['member_id'])){
                $this->db->where('m.id',$_POST['member_id']);
            }
            $this->db->from('members m');
            $query0 = $this->db->get();
            // echo $query0->num_rows();

            if ($query0->num_rows() > 0) {
                    $array0 = $query0->result();

                    $title = $_POST['notification_title'];
                    $body = $_POST['notification_msg'];
                    $isNotificationType = true;

                    foreach($array0 as $val){
                        if($this->sendPushToAndroid($_POST['API_KEY'],$val->firebase_token,$title,$body,$isNotificationType)){
                            $notification_add0 = array(
                                'member_id' => $val->id,
                                'bid_id' => '',
                                'title' => $title,
                                'message' => $body,
                                'created_at' => date('Y-m-d H:i:s')
                            );
                            if($this->db->insert('notifications',$notification_add0)){
                                $total++;
                            }
                        }
                    }
            }

            if($total > 0){
                $res['Status'] = true;
                $res['Message'] = 'success! Notification Sent Successfully';
                echo json_encode($res);
            }else{
                $res['Status'] = false;
                $res['Message'] = 'Something Wrong! Notification Sending Failed';
                echo json_encode($res);
            }

        }

        public function sendPushToAndroid($API_KEY, $deviceToken, $title, $body, $isNotificationType)
        {
            $FIREBASE_API_KEY = $API_KEY;
            $FIREBASE_FCM_URL = "https://fcm.googleapis.com/fcm/send";

            $fields = array();

            $fields['to'] = $deviceToken;
            $fields['priority'] = "high";

            if ($isNotificationType) {
                $payload['title'] = $title;
                $payload['body'] = $body;
                $fields['notification'] = $payload;
            }

            $headers = array(
                'Authorization: key=' . $FIREBASE_API_KEY,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $FIREBASE_FCM_URL);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Problem occurred: ' . curl_error($ch));
            }

            curl_close($ch);
            // print_r($result);
            $result1 = json_decode($result);
            // print_r($result1);
            if($result1->success == '0'){
                return false;
            }elseif($result1->success == '1'){
                return true;
            }
        }

    }
?>