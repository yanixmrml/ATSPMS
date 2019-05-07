<h1 class="text-center" style="float: none; display: block; ">Hi <?php echo $userInfo['firstname'] ?>!</h1>
<hr/>
<p class="text-justify text-muted">Welcome to your ATSPMS (Automatic Transfer Switch and Power Management System)
Dashboard Account. The ATSPMS Dashboard is a control panel with administrative privileges to manage and configure the settings of your automatic transfer switch,
power management, load shedding, load profile and other essential information related to power consumption.

<?php echo date("Y/m/d H:i:s"); ?>
</p>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 text-center activity-panel"> 
            <div class="container-fluid">
                <h4>Recent Activity</h4>
                <ul id="activity-lists">
                    <?php
                        if(isset($notificationsList) && !empty($notificationsList)){
                            foreach($notificationsList as $note){
                                switch($note["status"]){
                                    case 1:
                                    case 2:
                                        echo "<li class='alert alert-warning'><b>Warning! </b> " . $note["description"] . "</li>";
                                        break;
                                    case 3:
                                        echo "<li class='alert alert-danger'><b>Important! </b> " . $note["description"] . "</li>";
                                        break;
                                    default:
                                        echo "<li class='alert'>" . $note["description"] . "</li>";
                                    
                                }
                            }
                        }
                    ?>
                </ul>
            </div>
        </div>
        <div class="col-md-8 text-center">
            &nbsp;
            <div id="dashboard-home-panel" class="container-fluid text-center">
                <div class="row">
                <?php
                    $size = count($menus);
                    for($i=0;$i<$size;$i++){
                        echo "<div class='col-md-2 dashboard-atspms-dashboard-services'>"
                            . "<a href='" . $menus[$i]["URL"] . "' id='" . $menus[$i]["ID"]    ."'><img src='" . $menus[$i]["ICON"] . "'><br/>" . $menus[$i]["NAME"] . "</a></div>";
                    }
                ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="updatePasswordLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userInfoLabel">Change Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="password-warning-message"></div>
        <?php
            echo form_open("dashboard/update_password",array("id"=>"update-password-form","method"=>"post"));
        ?>
        <div class="form-group" >
            <label for="old-password">Old Password</label>&nbsp;
            <input type="password" name="old-password" id="old-password" class="form-control" value="" placeholder="Enter old password" required>
        </div>
        <div class="form-group" >
            <label for="new-password">New Password</label>&nbsp;
            <input type="password" name="new-password" id="new-password" class="form-control" value="" placeholder="Enter new password" required>
        </div>
        <div class="form-group" >
            <label for="confirm-password">Confirm Password</label>&nbsp;
            <input type="password" name="confirm-password" id="confirm-password" class="form-control" value="" placeholder="Confirm the new password" required>
        </div>
        <input type="hidden" name="auth" value="<?php echo $auth; ?>" />
        <input type="hidden" name="u_id"  id="home-user-id" value="<?php echo $userInfo['user_id']; ?>" />
        <?php
            echo form_close();
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" id="save-pass-button" class="btn btn-primary">Save changes</button>
        <button type="button" id="cancel-pass-button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="homeMessageModal" tabindex="-1" role="dialog" aria-labelledby="homeMessageModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="homeMessageModal">Information Message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="lead center"></p>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>