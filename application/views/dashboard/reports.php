<div class="row">
    <div class="col-sm-3 centered-pills col-centered">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a href="#pills-realtime" id="pills-realtime-tab" aria-controls="pills-realtime" aria-selected="true" data-toggle="pill" class="nav-link active">Realtime</a>
                </li>
                <li class="nav-item">
                    <a href="#pills-history" id="pills-history-tab" aria-controls="pills-history" aria-selected="false" data-toggle="pill" class="nav-link">History</a>
                </li>
        </ul>
    </div>
</div>
<hr/>
<div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="pills-realtime" role="tabpanel" aria-labelledby="pills-realtime-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <?php echo form_open("dashboard/get_total_power_start_month",array("id"=>"update-realtime-summary","method"=>"post")); ?>
                    <?php echo form_close(); ?>
                    <div id="realtime-reports-container" class="col-lg-12">
                    </div>
                </div>
                <div class="col-md-4">
                    <?php echo form_open("dashboard/get_selected_source",array("id"=>"update-load-summary","method"=>"post")); ?>
                    <?php echo form_close(); ?>
                    <h5 class="text-center">Summary</h5>
                    <div class="alert alert-warning text-center">
                        <b>Note:&nbsp;</b>The realtime readings below may have been delayed by a
                        minimum of 1.5 seconds depending on the speed of your connection.
                    </div>
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
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <h5 class="text-center">Total Monthly Cost</h5>
                    <?php echo form_open("dashboard/get_total_cost_start_month",array("id"=>"get-monthly-cost","method"=>"post")); ?>
                    <?php echo form_close(); ?>
                    <div id="monthly-cost" class="container-fluid">
                    </div>
                </div>
                <div class="col-md-4">
                    <h5 class="text-center">Recent Power Interruptions</h5>
                    <?php echo form_open("dashboard/get_recent_power_interruptions",array("id"=>"update-power-interruptions","method"=>"post")); ?>
                    <?php echo form_close(); ?>
                    <div id="power-interruptions-data">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="pills-history" role="tabpanel" aria-labelledby="pills-history-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-centered">
                    <h5 class="text-center">Historical Data</h5>
                    <hr/>
                    <div id="options" class="input-group">
                        <select id="data-type" name="data-type" class="custom-select">
                            <option value="0">Power</option>
                            <option value="1">Voltage</option>
                            <option value="2">Current</option>
                            <option value="3">Frequency</option>
                            <option value="4">Power Interruptions</option>
                        </select>&nbsp;&nbsp;
                        <label for="report-from" style="line-height: 30px;">
                            From
                        </label>
                        &nbsp;
                        <span id="report-from">
                            <input id="search-from" data-format="MM/dd/yyyy" onsubmit="return false" placeholder="From" type="text" class="form-control" value="" disabled="disabled"/>
                            <span class="input-group-btn add-on">
                                    <i data-time-icon="icon-time">&nbsp;<img src="<?php echo base_url(); ?>assets/css/images/datetime.svg" alt="Open" style="width: 20px; height: 20px;"></i>
                            </span>
                        </span>
                        &nbsp;&nbsp;
                        <label for="report-to" style="line-height: 30px;">
                            To
                        </label>
                        &nbsp;
                        <span id="report-to">
                            <input id="search-to" data-format="MM/dd/yyyy" onsubmit="return false" placeholder="To" type="text" class="form-control" value="" disabled="disabled"/>
                            <span class="input-group-btn add-on">
                                    <i data-time-icon="icon-time">&nbsp;<img src="<?php echo base_url(); ?>assets/css/images/datetime.svg" alt="Open" style="width: 20px; height: 20px;"></i>
                            </span>
                        </span>
                        &nbsp;&nbsp;
                        <button type="button" id="update-data-button" name="update-data-button" class="btn btn-primary" style="line-height: 10px; height: 40px;">Update Data</button> 
                    </div>
                    <hr/>
                    <div id="historical-reports-container">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="power_goal" value="<?php echo $currentSettings["power_goal"]; ?>">
<input type="hidden" id="cost_goal" value="<?php echo $currentSettings["cost_goal"]; ?>">
<input type="hidden" id="cost_pkwh" value="<?php echo $currentSettings["cost_pkwh"]; ?>">
<input type="hidden" id="battery_level" value="<?php echo $currentSettings["battery_level"]; ?>">
<div class="modal" id="reportsMessageModal" tabindex="-1" role="dialog" aria-labelledby="reportsMessageModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reportsMessageModalTitle">Information Message</h5>
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
    