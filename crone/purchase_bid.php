<?php
    // sms gateway
    define('SMS_BASE', "https://rest.nexmo.com/sms/");
    define('SMS_API', "d83371f2");
    define('SMS_SECRET', "SB2eD9Ot6QclT66P");
    define('SMS_FROM', "IbidCM");
    define('C_CODE', "974");

    // echo 'date_default_timezone_set: ' . date_default_timezone_get() . '<br />';
    date_default_timezone_set('Asia/Kolkata');
    $currDateTime = date('Y-m-d H:i:s');
    // exit;

    include('../database.php');
    include('email.php');
    // $sql1 = "select * from bids where bid_end_datetime <= CURRENT_TIMESTAMP() AND progress_status = '0'";
    $sql1 = "select * from bids where bid_end_datetime <= '".$currDateTime."' AND progress_status = '0' AND status = 'Enable'";
    // $sql1 = "select * from bids where";
    $res1 = $conn->query($sql1);

    // echo $res1->num_rows;exit;
    // echo "Ddd";
    // printf("Select query returned %d rows.\n", $res->num_rows);
    if($res1->num_rows > 0){ 
        while ($row1 = $res1->fetch_assoc())
        {
            $bid_id = $row1['id'];
            $sql2 = "select max(amount) as amount,member_id,bid_id from member_bids where bid_id = ".$bid_id;
            $res2 = $conn->query($sql2);

            while ($row2 = $res2->fetch_assoc()){
                // echo "<pre>";print_r($row2);
                if($row2['amount'] > 0 && $row2['member_id'] > 0){

                    $sql_update = "UPDATE bids SET purchaser = ".$row2['member_id'].",progress_status = '1' WHERE id = ".$bid_id;
                    $res3 = $conn->query($sql_update);

                    // ================ winner ======================
                    $winner_name = "";
                    $sql_get_member = "select id,name,phone,email,firebase_token,notification from members where id = ".$row2['member_id'];
                    $query_get_member1 = $conn->query($sql_get_member);
                    if($query_get_member1->num_rows > 0){
                        while ($row = $query_get_member1->fetch_assoc())
                        {   
                            $winner = $row;
                        }
                    }

                    // ================ Bid Posted user ================

                    $sql_get_member = "select id,name,phone,email,firebase_token,notification from members where id = ".$row1['member_id'];
                    $query_get_member2 = $conn->query($sql_get_member);
                    if($query_get_member2->num_rows > 0){
                        while ($row = $query_get_member2->fetch_assoc())
                        {
                            $member = $row;
                        }
                    }

                    // =============== winner mail =============

                        $winner_name = $winner['name'];
                        // email
                        $subject = "Bid - ".$row1['number'];
                        // $body = "You Have Placed Largest Bid on Ad, Contact To Owner, Thanks";
                        $body = get_email_template($winner['name'],$row1['number'],'winner',$member['name'],$member['email'],$member['phone']);

                        // echo $body;exit;
                        send_mail($winner['email'],$subject,$body);

                        $msg = "You are winner of the bid. Contact Bid Owner - ".$member['email']." : ".$member['phone'];

                        // SMS
                        $fields = array();
                        $fields['api_key'] = SMS_API;
                        $fields['api_secret'] = SMS_SECRET;
                        $fields['to'] = C_CODE.$winner['phone'];
                        $fields['from'] = SMS_FROM;
                        $fields['text'] = $subject." ".$msg;
                        $data_string = json_encode($fields);
                        $sms_res = sent_SMS($data_string);

                        //Notification
                        if($member['notification'] == 'on'){

                            $sql_ins_noti_winner = "INSERT INTO notifications SET 
                                                member_id = ".$winner['id'].",
                                                bid_id = ".$bid_id.",
                                                title = '".$subject."',
                                                message = '".$msg."',
                                                created_at = '".date('Y-m-d H:i:s')."'
                                            ";
                            $query_ins_not_winner = $conn->query($sql_ins_noti_winner);

                            sendPushToAndroid($winner['firebase_token'],$subject,$msg,true);
                        }

                    // =============== bid poster mail =============

                        // email
                        $subject = "Bid - ".$row1['number'];
                        // $body = "Contact your Purchaser. Bid Time over., Thanks";
                        $body = get_email_template($member['name'],$row1['number'],'owner1',$winner['name'],$winner['email'],$winner['phone']);
                        send_mail($member['email'],$subject,$body);


                        $msg = "Bid Completed. Contact Winner - ".$winner['email']." : ".$winner['phone'];

                        // SMS
                        $fields = array();
                        $fields['api_key'] = SMS_API;
                        $fields['api_secret'] = SMS_SECRET;
                        $fields['to'] = C_CODE.$member['phone'];
                        $fields['from'] = SMS_FROM;
                        $fields['text'] = $subject." ".$msg;
                        $data_string = json_encode($fields);
                        $sms_res = sent_SMS($data_string);

                        // add Notification
                        if($member['notification'] == 'on'){
                            
                            $sql_ins_not_member = "INSERT INTO notifications SET 
                                                member_id = ".$member['id'].",
                                                bid_id = ".$bid_id.",
                                                title = '".$subject."',
                                                message = '".$msg."',
                                                created_at = '".date('Y-m-d H:i:s')."'
                                            ";
                            $query_ins_not_member = $conn->query($sql_ins_not_member);
                            sendPushToAndroid($member['firebase_token'],$subject,$msg,true);
                        }

                }else{

                	// 0 bid placed
                    // echo "bid=".$bid_id;

                    $sql_update = "UPDATE bids SET progress_status = '1' WHERE id = ".$bid_id;
                    $res3 = $conn->query($sql_update);


                    // ================ Bid Posted user ================
                    $sql_get_member = "select id,name,phone,email,firebase_token,notification from members where id = ".$row1['member_id'];
                    $query_get_member2 = $conn->query($sql_get_member);
                    if($query_get_member2->num_rows > 0){
                        while ($member = $query_get_member2->fetch_assoc())
                        {
                            // echo "<pre>";print_r($member);exit;

                            // email
                            $subject = "Bid - ".$row1['number'];
                            // $body = "Bid Time Out, Not Any one placed bids on your Ad !!!";
                            $body = get_email_template($member['name'],$row1['number'],'owner2');
                            send_mail($member['email'],$subject,$body);

                            $msg = "Ad times up. There was no bids placed by anyone.";

                            // SMS
                            $fields = array();
                            $fields['api_key'] = SMS_API;
                            $fields['api_secret'] = SMS_SECRET;
                            $fields['to'] = C_CODE.$member['phone'];
                            $fields['from'] = SMS_FROM;
                            $fields['text'] = $subject." ".$msg;
                            $data_string = json_encode($fields);
                            $sms_res = sent_SMS($data_string);

                            //Notification
                            if($member['notification'] == 'on'){
                                
                                $sql_ins_not_member = "INSERT INTO notifications SET 
                                                member_id = ".$member['id'].",
                                                bid_id = ".$bid_id.",
                                                title = '".$subject."',
                                                message = '".$msg."',
                                                created_at = '".date('Y-m-d H:i:s')."'
                                            ";
                                $query_ins_not_member = $conn->query($sql_ins_not_member);

                                sendPushToAndroid($member['firebase_token'],$subject,$msg,true);
                            }

                        }
                    }
                    
                }
            }
            // printf("%s %s %s\n", $row['id'], $row['name'], $row['price']);
        }
    }

    // echo $body = get_email_template('name1','123456','winner');
    // send_mail('nikul@kartuminfotech.com',"test",$body);

    function sent_SMS($data_string){
        $curl = curl_init(SMS_BASE."json");
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                   
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))                                                                       
        );

        $curl_res = curl_exec($curl);
        $status_data = json_decode($curl_res);
        // print_r($status_data);
        curl_close($curl);
        return $status_data;
    }

    function send_mail($to_email,$subject,$msg,$cc=""){
        // echo "send_mail";
        $timecode = strtotime("NOW");
        $timecode = md5($timecode);

        $from = "test111@gmail.com";
        // $headers = "From: ".$from;
        // $headers .= "Content-type: text/html\r\n";

        $headers = 'To: '.$to_email. "\r\n";
        $headers .= 'From: '.$from . "\r\n";
        if($cc != ""){
            $headers .= 'Cc: '.$cc. "\r\n";
        }
        $headers .= "Content-type: text/html;\r\n";
        $message = '<html><body>'.$msg.'</body></html>';

        // echo $to_email;
        // echo "<br/>";
        // echo $subject;
        // echo "<br/>";
        // echo $message;
        // echo "<br/>";
        // exit;
        mail($to_email, $subject, $message, $headers);

        // if(mail($to_email, $subject, $message, $headers)){
        //     // echo "send";
        //     return true;
        // }else{
        //     // echo "err";
        //     return false;
        // }
    }

    function sendPushToAndroid($deviceToken, $title, $body, $isNotificationType)
    {
        // define("FIREBASE_API_KEY", "AAAA9qk5Ss8:APA91bG6KptZIsjyv9tnRixr1vqi86OVo3tbQLZhtOn74vzkeO0yzPJnIxDmrIdgqypoDZuDV5m6mLOuQEh_LQE6db0utHYdPacgxJdWXv4e4Sr4l8NiQy8bfOhihNrZLYBU1EURuaAv");
        // define("FIREBASE_FCM_URL", "https://fcm.googleapis.com/fcm/send");
        $FIREBASE_API_KEY = "AAAA9qk5Ss8:APA91bG6KptZIsjyv9tnRixr1vqi86OVo3tbQLZhtOn74vzkeO0yzPJnIxDmrIdgqypoDZuDV5m6mLOuQEh_LQE6db0utHYdPacgxJdWXv4e4Sr4l8NiQy8bfOhihNrZLYBU1EURuaAv";
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
        return $result;
    }
?>