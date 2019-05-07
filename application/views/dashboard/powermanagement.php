<?php echo form_open("dashboard/update_load_shedding",array("id"=>"update-load-shedding","method"=>"post")); ?>
<div class="container-fluid">    
    <div class="row">
        <div class="col-lg-4  col-centered">
            <div class="input-group text-center" id="auto-load-shedding">
                <span class="input-group-btn load-shedding-buttons">
                        <button type="button" class="btn btn-primary" id="auto-load-shedding-on">On</button>
                        <button type="button" class="btn btn-danger" id="auto-load-shedding-off">Off</button>
                </span>
                &nbsp;
                <?php
                        if(isset($sources) && !empty($sources)){
                                if(intval($sources[0]["is_auto_load_shedding"])){
                                        echo '<span for="auto-load-shedding" class="input-group-text" id="auto-load-shedding-label">Auto Load Shedding is&nbsp;<b>ON</b></span>';        
                                }else{
                                        echo '<span for="auto-load-shedding" class="input-group-text" id="auto-load-shedding-label">Auto Load Shedding is&nbsp;<b>OFF</b></span>';
                                }
                        }
                ?>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<hr />
<p id="dynamicMessage-powermanagement" class='async-message'></p>
<?php echo form_open("dashboard/get_connected_loads",array("id"=>"get-connected-loads","method"=>"post")); ?>
<div class="container-fluid" id="connected-load-info">
        <div class="row">
                <?php
                $num_con_loads = count($connected_load);
                if(isset($connected_load) && !empty($connected_load)){
                    for($i = 1;$i <= count($connected_load);$i++){  ?>
                        <div class="col-md-3">
                            <div class="text-center"><b><?php echo "Load " . $i; ?></b></div>
                            <div id="<?php echo "load-" . $i . "-body"; ?>">
                                <p class="text-center" id="load-image-status-<?php echo $i; ?>">
                                    <?php if($connected_load[($i-1)]["status"]){ ?>
                                        <img alt="ON IMAGE" src="<?php echo base_url() . "assets/css/images/load-on.gif"; ?>" class="load-image-status img-fluid">
                                    <?php }else{ ?>
                                        <img alt="OFF IMAGE" src="<?php echo base_url() . "assets/css/images/load-off.gif"; ?>" class="load-image-status img-fluid">
                                    <?php } ?>
                                </p>
                                <p class="text-center">
                                    <button type="button" class="btn btn-primary update-con-load-button" id="<?php echo $connected_load[($i-1)]["con_load_id"]; ?>">Update</button>
                                </p>
                                <p class="text-justify load-body-info" id="load-body-info-<?php echo $i; ?>">
                                    Description : <b><span class="con-load-description"><?php echo $connected_load[($i-1)]["description"] ?></span></b></br>
                                    Priority :  <b><span class="con-load-priority"><?php echo $connected_load[($i-1)]["priority"] ?></span></b><br/>
                                    Status :  <b><span class="con-load-status"><?php echo intval($connected_load[($i-1)]["status"])?"On":"Off"; ?></span></b><br/>
                                    Schedule Day(s): <b><span class="con-load-sched-day"><?php echo $connected_load[($i-1)]["schedule_day"] ?></span></b><br/>
                                    Time On : <b><span class="con-load-sched-on"><?php echo $connected_load[($i-1)]["schedule_on"]; ?></span></b><br/>
                                    Time Off : <b><span class="con-load-sched-off"><?php echo $connected_load[($i-1)]["schedule_off"]; ?></span></b><br/>
                                    Last Updated : <b><span class="con-load-updated-on"><?php echo $connected_load[($i-1)]["last_updated"]; ?></span></b><br/>
                                    Updated By : <b><span class="con-load-updated-by"><?php echo ($connected_load[($i-1)]["user_fullname"]==null || intval($connected_load[($i-1)]["user_id"])==0?"SYSTEM":$connected_load[($i-1)]["user_fullname"]); ?></span></b>
                                    <input type="hidden" name="load-status" class="load-status" value="<?php echo intval($connected_load[($i-1)]["status"]); ?>" >
                                    <input type="hidden" name="sched-day" class="sched-day" value="<?php echo $connected_load[($i-1)]["schedule_day"]; ?>" >
                                    <input type="hidden" name="sched-on" class="sched-on" value="<?php echo $connected_load[($i-1)]["sched_on"]; ?>" >
                                    <input type="hidden" name="sched-off" class="sched-off" value="<?php echo $connected_load[($i-1)]["sched_off"]; ?>" >
                                    <input type="hidden" name="load-user-id" id="load-user-id" value="<?php echo $userInfo["user_id"]; ?>" >
                                    <input type="hidden" name="base-url" id="powermanagement-base-url" value="<?php echo base_url(); ?>" >    
                                </p>
                            </div>
                        </div>
                <?php } ?>
                <?php }else{ ?>
                            <div class="col-lg-12 col-centered">
                                <p class="text-center">
                                    No Connected Loads in the Record
                                </p>
                            </div>
                <?php } ?>
        </div>
</div>
<?php echo form_close(); ?>
<hr/>
<div class="container-fluid">
    <div class="row">
        <div id="realtime-powermanagement-container" class="col-lg-8">
            Real Power Realtime Graph
        </div>
        <div class="col-lg-4">
            <h5 class="text-center">Summary</h5>
            <div class="alert alert-warning text-center">
                <b>Note:&nbsp;</b>The realtime readings below may have been delayed by a
                minimum of 1.5 seconds depending on the speed of your connection.
            </div>
            <?php echo form_open("dashboard/get_selected_source",array("id"=>"update-load-summary","method"=>"post")); ?>
                <div id="load-summary-info-body">
                    Mode: <br/>    
                    Source : <br/>
                    Frequency : <br/>
                    Voltage : <br/>
                    Current : <br/>
                    Apparent Power : <br/> 
                    Average Power : <br/>
                    Power Factor : <br/>                                    
                </div>
             <?php echo form_close(); ?>
        </div>
    </div>
</div>
<div class="modal" id="powermanagementModal" tabindex="-1" role="dialog" aria-labelledby="powermanagementModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="powermanagementModalLabel">Update Load</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="powermanagement-warning-message"></div>
        <?php echo form_open("dashboard/update_powermanagement",array("id"=>"update-powermanagement","method"=>"post")); ?>
        <div class="form-group" >
            <label for="update-load-id">Load ID</label>&nbsp;
            <input type="text" name="update-load-id" id="update-load-id" class="form-control" placeholder="Load ID" value="" disabled="disabled">    
        </div>
        <div class="form-group" >
            <label for="update-load-description">Description</label>&nbsp;
            <input type="text" name="update-load-description" id="update-load-description" class="form-control" value="" placeholder="Description"  required>    
        </div>
        <div class="form-group" >
            <label for="update-load-priority">Priority</label><br/>
            <select name="update-load-priority" id="update-load-priority" class="custom-select mr-sm-2">
                <?php for($i = 1;$i<=$num_con_loads;$i++){ ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group" >
            <label for="update-load-status">Status</label><br/>
            <input type="checkbox" id="update-load-status" class="checkbox" data-toggle="toggle" data-offstyle="danger" data-size="normal" checked="checked" required>    
        </div>
        <hr/>
        <p class="text-center">
                <label><b>Schedule</b></label>
        </p>
        <div class="form-group">
             <div class="form-row">
                <div class="col">
                        <label for="update-load-sched-day">Day</label>&nbsp;
                        <input type="text" name="update-load-sched-day" id="update-load-sched-day" class="form-control" value="" placeholder="Days of Week" disabled="disabled">
                </div>
             </div>
             <br/>
             <div class="form-row">
                <div class="col text-center day-button">
                        <button type="button" name="Mon-button" id="Mon-button" class="btn btn-primary">Mon</button>
                        <button type="button" name="Tue-button" id="Tue-button" class="btn btn-primary">Tue</button>
                        <button type="button" name="Wed-button" id="Wed-button" class="btn btn-primary">Wed</button>
                        <button type="button" name="Thu-button" id="Thu-button" class="btn btn-primary">Thu</button>
                        <button type="button" name="Fri-button" id="Fri-button" class="btn btn-primary">Fri</button>
                        <button type="button" name="Sat-button" id="Sat-button" class="btn btn-primary">Sat</button>
                        <button type="button" name="Sun-button" id="Sun-button" class="btn btn-primary">Sun</button>
                        <button type="button" name="clear-button" id="clear-button" class="btn btn-danger">Clear</button>
                </div>
             </div>
        </div>
        <div class="form-group" >
            <div class="form-row">
                <div class="col">    
                        <label for="sched-time-on">Time On</label>&nbsp;
                        <div id="sched-time-on" class="input-append">
                                <input id="update-sched-time-on" data-format="HH:mm:ss PP" type="text" class="form-control" value="" placeholder="Time On" disabled="disabled"/>
                                <span class="add-on">
                                        <i data-time-icon="icon-time">Open</i>
                                </span>
                        </div>
                </div>
                <div class="col">   
                        <label for="sched-time-off">Time Off</label>&nbsp;
                        <div id="sched-time-off" class="input-append">
                                <input id="update-sched-time-off" data-format="HH:mm:ss PP" type="text" class="form-control" value="" placeholder="Time On" disabled="disabled"/>
                                <span class="add-on">
                                        <i data-time-icon="icon-time">Open</i>
                                </span>
                        </div>
                </div>                
            </div>
        </div>
        <div class="container alert alert-warning text-justify">
                <b clas="alert-heading">Warning:</b>&nbsp;Enabling the schedule on and off will trigger the "On" or "Off" toggle to be automatic, hence a system operation.
                Manual toggling of "On" and "Off" status will be rendered useless since the system will follow the schedule of "On" and "Off" operation set by the user.
        </div>
      </div>
      <?php echo form_close(); ?>
      <div class="modal-footer">
        <button type="button" id="save-update-powermanagement" class="btn btn-primary">Save Changes</button>
        <button type="button" id="cancel-update-powermanagement" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="confirmPowerManagementModal" tabindex="-1" role="dialog" aria-labelledby="confirmPowerManagementModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmPowerManagementModalLabel">Confirm Auto Load Shedding Update</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="lead center"></p>
      </div>
      <div class="modal-footer">
        <button type="button" id="yes-update-powermanagement" class="btn btn-primary">Yes</button>
        <button type="button" id="no-update-powermanagement" class="btn btn-secondary" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="powermanagementMessageModal" tabindex="-1" role="dialog" aria-labelledby="powermanagementMessageModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="powermanagementMessageModalTitle">Information Message</h5>
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
    