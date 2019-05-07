<?php echo form_open("dashboard/get_settings_list",array("id"=>"get-settings-list","method"=>"post"));?>
<div class="container">    
    <div class="row">
        <div class="col-lg-12 col-centered">
            <div class="input-group">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default" id="settings-search-button" name="settings-search" value="Search Settings">Search</button>
                </span>
                <span id="search-effectivity-date-calendar">
                    <input id="search-effectivity-date" data-format="MM/dd/yyyy" onsubmit="return false" placeholder="Search by effectivity date..." type="text" class="form-control" value=""/>
                    <span class="input-group-btn add-on">
                            <i data-time-icon="icon-time">&nbsp;<img src="<?php echo base_url(); ?>assets/css/images/datetime.svg" alt="Open" style="width: 20px; height: 20px;"></i>
                    </span>
                </span>
                &nbsp;&nbsp;&nbsp;
                <span class="input-group-btn">
                    <button type="button" class="btn btn-primary" id="settings-add-button" name="settings-add" value="Add Settings">Add Settings Configuration</button>
                </span>
            </div>
        </div>
    </div>
</div>
<hr />
<?php echo form_close(); ?>
<p id="dynamicMessage-settings" class='async-message'></p>
<div class="table-responsive">
    <table class="table table-hover" id="settings-table">
        <caption>
            List of settings configuration <br/>
            Note: <br/>
            &nbsp;Click 'Update' button to update the selected setting configuration.
        </caption>
        <thead>
            <tr class="table-secondary">
                <th>Settings No.</th>
                <th>Nominal Voltage</th>
                <th>Nominal Frequency</th>
                <th>Cost pkwh</th>
                <th>Cost Goal</th>
                <th>Power Goal</th>
                <th>Starting Effectivity Date</th>
                <th>Last Updated</th>
                <th>Updated By</th>
                <th>&nbsp;&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr ><td colspan='10'><p class='information-message'>Search settings by effectivity date to populate the list . </p></td></tr>
        </tbody>
    </table>
</div>
<div class="modal" id="updateSettingsModal" tabindex="-1" role="dialog" aria-labelledby="updateSettingsLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="settingsAddLabel">Update Settings Configuration</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="settings-update-warning-message"></div>
        <?php
            echo form_open("dashboard/update_settings",array("id"=>"update-settings","method"=>"post"));
        ?>
        <div class="form-group" >
            <label for="update-effectivity-date">Starting Date of Effectivity</label>&nbsp;
            <div id="update-effectivity-date-calendar" class="input-group">
                    <input id="update-effectivity-date" data-format="MM/dd/yyyy" type="text" class="form-control" value="" placeholder="Enter effecitivty date..." disabled="disabled"/>
                    <span class="input-group-btn add-on">
                            <i data-time-icon="icon-time">&nbsp;<img src="<?php echo base_url(); ?>assets/css/images/datetime.svg" alt="Open" style="width: 20px; height: 20px;"></i>
                    </span>
            </div>
        </div>
        <hr />
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="update-voltage-max">Maximum Voltage (V)</label>&nbsp;
                    <input type="text" name="update-voltage-max" id="update-voltage-max" class="form-control" placeholder="Max Critical Voltage"  required>
                </div>
                <div class="col">
                    <label for="update-voltage-min">Minimum Voltage (V)</label>&nbsp;
                    <input type="text" name="update-voltage-min" id="update-voltage-min" class="form-control" placeholder="Min Critical Voltage" required>
                </div>
            </div>
        </div>
        <hr />
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="update-current-max">Maximum Current (A)</label>&nbsp;
                    <input type="text" name="update-current-max" id="update-current-max" class="form-control" placeholder="Max Critical Current"  required>
                </div>
                <div class="col">
                    <label for="update-current-min">Minimum Current (A)</label>&nbsp;
                    <input type="text" name="update-current-min" id="update-current-min" class="form-control" placeholder="Min Critical Current"  required>
                </div>
            </div>
        </div>
        <hr />
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="update-frequency-max">Maximum Frequency (Hz)</label>&nbsp;
                    <input type="text" name="update-frequency-max" id="update-frequency-max" class="form-control" placeholder="Max Critical Frequency"  value="63" readonly>
                </div>
                <div class="col">
                    <label for="update-frequency-min">Minimum Frequency (Hz)</label>&nbsp;
                    <input type="text" name="update-frequency-min" id="update-frequency-min" class="form-control" placeholder="Min Critical Frequency"  value="57" readonly>
                </div>
            </div>
        </div>
        <hr />
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="update-power-max">Maximum Critical Power (W) per Hour</label>&nbsp;
                    <input type="text" name="update-power-max" id="update-power-max" class="form-control" placeholder="Max Critical Power"  required>
                </div>
            </div>
        </div>
        <hr />
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="update-power-goal">Power Goal (W) per Month</label>&nbsp;
                    <input type="text" name="update-power-goal" id="update-power-goal" class="form-control" placeholder="Power Goal"  required>
                </div>
                <div class="col">
                    <label for="update-power-factor">Power Factor Goal</label>&nbsp;
                    <input type="text" name="update-power-factor" id="update-power-factor" class="form-control" placeholder="Power Factor (e.g. 0.85)"  required>
                </div>
            </div>
        </div>
        <hr />
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="update-battery-level">Battery Level (V)</label>&nbsp;
                    <input type="text" name="update-battery-level" id="update-battery-level" class="form-control" placeholder="Critical Battery Level"  required>
                </div>
                <div class="col">
                    <label for="update-temperature-level">Temperature Level (deg. C)</label>&nbsp;
                    <input type="text" name="update-temperature-level" id="update-temperature-level" class="form-control" placeholder="Critical Temperature Level"  required>
                </div>
            </div>
        </div>
        <hr />
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="update-cost-pkwh">Cost per kwh (Peso/kWh)</label>&nbsp;
                    <input type="text" name="update-cost-pkwh" id="update-cost-pkwh" class="form-control" placeholder="Cost Per kWh"  required>
                </div>
                <div class="col">
                    <label for="update-cost-goal">Cost Goal (Peso) per Month</label>&nbsp;
                    <input type="text" name="update-cost-goal" id="update-cost-goal" class="form-control" placeholder="Cost Goal"  required>
                </div>
            </div>
        </div>        
        <hr />
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="update-nominal-voltage">Nominal Voltage (V)</label>&nbsp;
                    <input type="text" name="update-nominal-voltage" id="update-nominal-voltage" class="form-control" placeholder="Nominal Voltage"  value="230" readonly>
                </div>
                <div class="col">
                    <label for="update-nominal-frequency">Nominal Frequency (Hz)</label>&nbsp;
                    <input type="text" name="update-nominal-frequency" id="update-nominal-frequency" class="form-control" placeholder="Nominal Frequency"  value="60" readonly>
                </div>
            </div>
        </div>
        <input type="hidden" name="user_id" id="update-settings-user-id" value="<?php echo $userInfo['user_id']; ?>" />
        <input type="hidden" name="settings_id" id="update-settings-id" value="" />
        <?php
            echo form_close();
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" id="save-update-settings-button" class="btn btn-primary">Save changes</button>
        <button type="button" id="cancel-update-settings-button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="addSettingsModal" tabindex="-1" role="dialog" aria-labelledby="addSettingsLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="settingsAddLabel">Add Settings Configuration</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="settings-add-warning-message"></div>
        <?php
            echo form_open("dashboard/add_settings",array("id"=>"add-settings","method"=>"post"));
        ?>
        <div class="form-group" >
            <label for="add-effectivity-date">Starting Date of Effectivity</label>&nbsp;
            <div id="add-effectivity-date-calendar" class="input-group">
                    <input id="add-effectivity-date" data-format="MM/dd/yyyy" type="text" class="form-control" value="" placeholder="Enter effecitivty date..." disabled="disabled"/>
                    <span class="input-group-btn add-on">
                            <i data-time-icon="icon-time">&nbsp;<img src="<?php echo base_url(); ?>assets/css/images/datetime.svg" alt="Open" style="width: 20px; height: 20px;"></i>
                    </span>
            </div>
        </div>
        <hr />
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="add-voltage-max">Maximum Voltage (V)</label>&nbsp;
                    <input type="text" name="add-voltage-max" id="add-voltage-max" class="form-control" placeholder="Max Critical Voltage"  required>
                </div>
                <div class="col">
                    <label for="add-voltage-min">Minimum Voltage (V)</label>&nbsp;
                    <input type="text" name="add-voltage-min" id="add-voltage-min" class="form-control" placeholder="Min Critical Voltage" required>
                </div>
            </div>
        </div>
        <hr />
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="add-current-max">Maximum Current (A)</label>&nbsp;
                    <input type="text" name="add-current-max" id="add-current-max" class="form-control" placeholder="Max Critical Current"  required>
                </div>
                <div class="col">
                    <label for="add-current-min">Minimum Current (A)</label>&nbsp;
                    <input type="text" name="add-current-min" id="add-current-min" class="form-control" placeholder="Min Critical Current"  required>
                </div>
            </div>
        </div>
        <hr />
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="add-frequency-max">Maximum Frequency (Hz)</label>&nbsp;
                    <input type="text" name="add-frequency-max" id="add-frequency-max" class="form-control" placeholder="Max Critical Frequency"  value="63" readonly>
                </div>
                <div class="col">
                    <label for="add-frequency-min">Minimum Frequency (Hz)</label>&nbsp;
                    <input type="text" name="add-frequency-min" id="add-frequency-min" class="form-control" placeholder="Min Critical Frequency"  value="57" readonly>
                </div>
            </div>
        </div>
        <hr />
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="add-power-max">Maximum Critical Power (W) per Hour</label>&nbsp;
                    <input type="text" name="add-power-max" id="add-power-max" class="form-control" placeholder="Max Critical Power"  required>
                </div>
            </div>
        </div>
        <hr />
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="add-power-goal">Power Goal (kW) per Month</label>&nbsp;
                    <input type="text" name="add-power-goal" id="add-power-goal" class="form-control" placeholder="Power Goal"  required>
                </div>
                <div class="col">
                    <label for="add-power-factor">Power Factor Goal</label>&nbsp;
                    <input type="text" name="add-power-factor" id="add-power-factor" class="form-control" placeholder="Power Factor (e.g. 0.85)"  required>
                </div>
            </div>
        </div>
        <hr />
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="add-battery-level">Battery Level (V)</label>&nbsp;
                    <input type="text" name="add-battery-level" id="add-battery-level" class="form-control" placeholder="Critical Battery Level"  required>
                </div>
                <div class="col">
                    <label for="add-temperature-level">Temperature Level (deg. C)</label>&nbsp;
                    <input type="text" name="add-temperature-level" id="add-temperature-level" class="form-control" placeholder="Critical Temperature Level"  required>
                </div>
            </div>
        </div>
        <hr />
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="add-cost-pkwh">Cost per kwh (Peso/kWh)</label>&nbsp;
                    <input type="text" name="add-cost-pkwh" id="add-cost-pkwh" class="form-control" placeholder="Cost Per kWh"  required>
                </div>
                <div class="col">
                    <label for="add-cost-goal">Cost Goal (Peso) per Month</label>&nbsp;
                    <input type="text" name="add-cost-goal" id="add-cost-goal" class="form-control" placeholder="Cost Goal"  required>
                </div>
            </div>
        </div>
        <hr />
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="add-nominal-voltage">Nominal Voltage (V)</label>&nbsp;
                    <input type="text" name="add-nominal-voltage" id="add-nominal-voltage" class="form-control" placeholder="Nominal Voltage"  value="230" readonly>
                </div>
                <div class="col">
                    <label for="add-nominal-frequency">Nominal Frequency (Hz)</label>&nbsp;
                    <input type="text" name="add-nominal-frequency" id="add-nominal-frequency" class="form-control" placeholder="Nominal Frequency"  value="60" readonly>
                </div>
            </div>
        </div>
        <input type="hidden" name="user_id" id="add-settings-user-id" value="<?php echo $userInfo['user_id']; ?>" />
        <?php
            echo form_close();
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" id="save-add-settings-button" class="btn btn-primary">Save changes</button>
        <button type="button" id="cancel-add-settings-button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="settingsMessageModal" tabindex="-1" role="dialog" aria-labelledby="userAccountMessageModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="settingsMessageModalTitle">Information Message</h5>
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