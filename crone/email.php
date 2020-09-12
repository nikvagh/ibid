
<?php
    function get_email_template($name,$bid_number,$email_msg_for,$name1 = "",$email1 = "",$phone1 = ""){ 
        $base_url = "http://ibid.cherrydemoserver10.com/";
        
        $html = "";
        $html .= '<style>
            table tr td{
                padding: 10px;
            }
        </style>
        <div style="background:#f6f6f6;padding:15px;min-width: 320px;max-width: 650px;margin:auto;">

            <div style="text-align: center;background:#999999;">
                <img src="'.$base_url.'application/views/back_assets/dist/img/logo.png" style="width: 100px;margin:10px"/>
            </div>
            <table style="margin:auto;background:#fff;font-size:18px;padding: 15px;">
                <tr>
                    <td>
                        <b>Hi '.$name.',</b>';
                        ?>

                        <?php if($email_msg_for == 'winner'){
                            $html .= '<p> We are glad to inform you that , you have placed largest bid on ad with number '.$bid_number.', so you are winner of the bid.
                            </p>';

                            $html .= '<p> Contact with ad owner <br/>
                                    Email: '.$email1.' <br/>
                                    Phone: '.$phone1.' 
                            </p>';

                            }
                        ?>

                        <?php if($email_msg_for == 'owner1'){
                            $html .= '<p>
                                We are glad to inform you that your ad with number '.$bid_number.' is completed, largest bid have been placed by '.$name1.'.
                            </p>';

                            $html .= '<p> Contact with bid winner <br/>
                                    Email: '.$email1.' <br/>
                                    Phone: '.$phone1.' 
                            </p>';

                        } ?>


                        <?php if($email_msg_for == 'owner2'){
                            $html .= '<p>
                                we are informing you that your ad with number '.$bid_number.' has times up. There was no bids placed by anyone. 
                            </p>';
                        } ?>

                        <?PHP
                        $html .= '<p>
                        Thanks,
                        <br/>
                        Team Ibid
                        </p>

                    </td>
                </tr>
            </table>
            <div style="text-align: center;background:#333333;padding:20px;">
                <a style="color:white;font-size:12px;">Copyright © I Bid services. All rights reserved.</a>
            </div>

        </div>';

        return $html;
    } 
?>


