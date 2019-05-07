<?php
if(isset($userInfo)){
    //if($this->session->userdata("picture")){                    
    //    $userPic = $this->session->userdata("picture");
    //}else{
    if(isset($userInfo['picture']) && $userInfo['picture']!=null){
        $userPic = "uploads/" . $userInfo['picture'];
    }else{
        $userPic = "assets/css/images/no-pic.jpg";    
    }   
    //}
    echo "<div class='user-info-panel'>";
    echo "<p><img class='img-user img-thumbnail' src='" . base_url() .  $userPic . "'></p>";
    echo "<p><b>" . $userInfo['lastname'] . ", " . $userInfo['firstname'] . "</b><br/>";  
    echo "Account Number: " . $userInfo['user_id'] . "<br/>" . (isset($userInfo['contact_number']) && $userInfo['contact_number']!=""?$userInfo['contact_number']."<br/>":"") .
        (isset($userInfo['email_address']) && $userInfo['email_address']!=""?$userInfo['email_address']."<br/>":"");
    echo "Last Logged In: " . $this->tools->formatDateTime($userInfo['last_login'],"D, F j, Y");
    echo "</p>";
    echo "<hr/></div>";
    
    $notifications = $this->load_model->getNotifications();
    if(!empty($notifications)){
        echo '<div id="notifications-board" class="user-info-panel container-fluid"><p>
            <span id="prevNotifications">
                <img alt="Previous"  src="' . base_url() . 'assets/css/images/left.png"/>
            </span>   
            &nbsp;<b>Notifications</b>&nbsp;
            <span class="next" id="nextNotifications">
                <img alt="Next"  src="' . base_url() . 'assets/css/images/right.png"/>
            </span>
            </p><hr/></div>';
        echo "<div class='notificationsList' id='notificationsPane'><ul>";
        foreach($notifications as $notification){
            echo "<li><p class='notifications-head'>From: <b>" . ($notification["user_fullname"]!=null&&$notification["user_fullname"]!=""?$notification["user_fullname"]:"System") . "</b><br/>";
            echo "Posted: <b>" . $this->tools->formatDateTime($notification['posted_on'],"D, F j, Y") . "</b></p>";
            switch($notification["status"]){
                case 1:
                case 2:
                    echo "<p class='text-justify notifications-body alert alert-warning'>" . $notification["description"] . "</p><hr/></li>";
                    break;
                case 3:
                    echo "<p class='text-justify notifications-body alert alert-danger'>" . $notification["description"] . "</p><hr/></li>";
                    break;
                default:
                    echo "<p class='text-justify notifications-body alert'>" . $notification["description"] . "</p><hr/></li>";      
            }
        }
        echo "</ul></div>";
        echo "</div>";
    }else{
        echo "<div id='notifications-board' class='user-info-panel'><p><b>Notifications</b></p><hr/>";
        echo "<div><p>No notifications posted<br/>.....</p></div>";
        echo "</div>";
    }    
}
?>