<?php echo form_open("dashboard/update_ats",array("id"=>"update-ats","method"=>"post")); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6  col-centered">
            <div class="input-group">
                <?php if(isset($current_settings) && !empty($current_settings)){ ?>
                    <input type="hidden" id="ats-voltage-max" value="<?php echo floatval($current_settings["voltage_max"]); ?>" >    
                    <input type="hidden" id="ats-voltage-min" value="<?php echo floatval($current_settings["voltage_min"]); ?>" >
                    <input type="hidden" id="ats-frequency-max" value="<?php echo floatval($current_settings["frequency_critical_max"]); ?>" >
                    <input type="hidden" id="ats-frequency-min" value="<?php echo floatval($current_settings["frequency_critical_min"]); ?>" >
                    <input type="hidden" id="ats-current-max" value="<?php echo floatval($current_settings["current_max"]); ?>" >
                    <input type="hidden" id="ats-current-min" value="<?php echo floatval($current_settings["current_min"]); ?>" >
                    <input type="hidden" id="ats-current-max" value="<?php echo floatval($current_settings["current_max"]); ?>" >
                    <input type="hidden" id="ats-power-max" value="<?php echo floatval($current_settings["power_max"]); ?>" >
                    <input type="hidden" id="ats-nominal-frequency" value="<?php echo floatval($current_settings["nominal_frequency"]); ?>" >
                    <input type="hidden" id="ats-nominal-voltage" value="<?php echo floatval($current_settings["nominal_voltage"]); ?>" >
                <?php } ?>
                <?php
                    if(intval($sources[0]["is_manual_selection"])==1){
                        echo '<select id="source-type" name="source-type" class="custom-select" disabled="disabled"/>';
                    }else{
                        echo '<select id="source-type" name="source-type" class="custom-select"/>';  
                    }
                ?>
                    <?php if(!empty($sources)){ ?>
                        <?php if(intval($sources[0]["source_id"])==1 && intval($sources[0]["is_selected"])){ ?>
                            <option value="1" selected="selected">Mains</option>
                            <option value="2">Secondary</option>
                        <?php }else{ ?>
                            <option value="1">Mains</option>
                            <option value="2" selected="selected">Secondary</option>
                        <?php } ?>
                    <?php }else{ ?>
                        <option value="1">Mains</option>
                        <option value="2">Secondary</option>
                    <?php } ?>
                </select>
                &nbsp;&nbsp;&nbsp;
                <?php
                    if(intval($sources[0]["is_manual_selection"])==1){
                        echo '<button type="button" id="update-ats-button" name="update-ats-button" class="btn btn-primary" disabled="disabled">Update Source</button>';    
                    }else{
                        echo '<button type="button" id="update-ats-button" name="update-ats-button" class="btn btn-primary">Update Source</button>';
                    }
                ?>   
            </div>
            <?php
                if(intval($sources[0]["is_manual_selection"])==1){
            ?>
                    <div class="container-fluid" id="manual-selection-message">
                        <br/>
                        <p class="text-center alert alert-warning">
                            <b clas="alert-heading">Warning:</b>&nbsp;Automatic Transfer Switch is disabled,<br/>
                            Manual operation is selected.
                        </p>
                    </div>
            <?php
                }else{
            ?>
                    <div class="container-fluid" id="manual-selection-message"></div>
            <?php
                }
            ?>
        </div>
    </div>
</div>
<hr />
<!---<hr />--->
<?php echo form_close(); ?>
<p id="dynamicMessage-ats" class='async-message'></p>
<div class="container">    
    <div class="row row-eq-height" id="ats-data">
        <div class="col-lg">
            <h4 class="text-center"><b>Main Supply</b></h4>
            <div id="mains-information" class="container-fluid">
                <div class="row">
                        <div id="container-mains-information" class="col-lg ats-col">
                            <p class="text-center text-muted mains-info-title"><b>SUMMARY</b></p>
                            <div class="container-fluid">
                                <div id="mains-info-body" class="ats-info-body">
                                    Status : <br/>
                                    Frequency : <br/>
                                    Voltage : <br/>
                                    Current : <br/>
                                    Apparent Power : <br/> 
                                    Average Power : <br/>
                                    Power Factor : <br/>                                    
                                </div>
                            </div>
                        </div>
                        <div id="container-mains-frequency" class="col-lg-6 ats-col"></div>
                </div>
                <div class="row">
                        <div id="container-mains-voltage"  class="col-lg-6 ats-col"></div>
                        <div id="container-mains-current" class="col-lg-6 ats-col"></div>                   
                </div>
                <div class="row">
                        <div id="container-mains-power" class="col-lg-6 ats-col"></div>
                        <div id="container-mains-power-factor" class="col-lg-6 ats-col"></div>
                </div>
            </div>
        </div>
        <div class="col-lg">
            <h4 class="text-center" ><b>Secondary Supply</b></h4>
            <div id="secondary-information" class="container-fluid">
                <div class="row">
                        <div id="container-secondary-information" class="col-lg ats-col">
                            <p class="text-center text-muted secondary-info-title"><b>SUMMARY</b></p>
                            <div class="container-fluid">
                                <div id="secondary-info-body" class="ats-info-body">
                                    Status : <br/>
                                    Frequency : <br/>
                                    Voltage : <br/>
                                    Current : <br/>
                                    Apparent Power : <br/> 
                                    Average Power : <br/>
                                    Power Factor : <br/>                                    
                                </div>
                            </div>
                        </div>
                        <div id="container-secondary-frequency" class="col-lg-6 ats-col"></div>
                </div>
                <div class="row">
                        <div id="container-secondary-voltage"  class="col-lg-6 ats-col"></div>
                        <div id="container-secondary-current" class="col-lg-6 ats-col"></div>                   
                </div>
                <div class="row">
                        <div id="container-secondary-power" class="col-lg-6 ats-col"></div>
                        <div id="container-secondary-power-factor" class="col-lg-6 ats-col"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_open("dashboard/getATSData",array("id"=>"get-ats-data","method"=>"post")); ?>
<?php echo form_close(); ?>
<div class="modal" id="confirmATSModal" tabindex="-1" role="dialog" aria-labelledby="confirmATSModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="atsMessageModalLabel">Confirm Source Update</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="lead center"></p>
      </div>
      <div class="modal-footer">
        <button type="button" id="yes-update-ats" class="btn btn-primary">Yes</button>
        <button type="button" id="no-update-ats" class="btn btn-secondary" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="atsMessageModal" tabindex="-1" role="dialog" aria-labelledby="atsMessageModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="atsMessageModalTitle">Information Message</h5>
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