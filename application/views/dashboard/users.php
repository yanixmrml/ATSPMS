<?php echo form_open("dashboard/get_users_list",array("id"=>"get-users-list","method"=>"post"   ));?>
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-centered">
            <div class="input-group">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="user-search-button" name="user_search">Search</button>
                </span>
                <input id="search-name" class="form-control" type="text" name="account-name" onsubmit="return false" placeholder="Search name..." />
                &nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-primary" id="user-add-button" name="user_add" >Add Account</button>
            </div>
        </div>
    </div>
</div>
<hr />
<?php echo form_close(); ?>
<p id="dynamicMessage-users" class='async-message'></p>
<div class="table-responsive">
    <table class="table table-hover" id="users-table">
        <caption>
            List of user accounts<br/>
            Note: <br/>
            &nbsp;<b style="color: #F1B0B7;">Red</b> means user account is deactivated. <br/>
            &nbsp;Click 'Update' button to update the selected user account.
        </caption>
        <thead>
            <tr class="table-secondary">
                <th>Account No.</th>
                <th>Name</th>
                <th>Username</th>
                <th>Contact No.</th>
                <th>Email Address</th>
                <th>Status</th>
                <th>Access Level</th>
                <th>Last Logged In</th>
                <th>Date Created</th>
                <th>&nbsp;&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr ><td colspan='10'><p class='information-message'>Search the account's name to populate the list . </p></td></tr>
        </tbody>
    </table>
</div>
<div class="modal" id="updateUserModal" tabindex="-1" role="dialog" aria-labelledby="updateUserLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userInfoLabel">Update User Account</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="user-update-warning-message"></div>
        <?php
            echo form_open_multipart("dashboard/update_user",array("id"=>"update-user-account","method"=>"post","enctype"=>"multipart/form-data"));
        ?>
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="update-account-number">Account Number</label>&nbsp;
                    <input type="text" name="update-account-number" id="update-account-number" class="form-control" disabled="disabled"/>
                </div>
                <div class="col picture-selection">
                    <img src="<?php echo base_url() . "assets/css/images/no-pic.jpg"; ?>" class="img-user img-fluid rounded mx-auto d-block" id="selected-update-img-thumbnail"/><br/>
                    <div class="custom-file">
                        <input type="file" name="updateUserImage" id="updateUserImage" class="custom-file-input" aria-describedby="fileHelp"/>
                        <label for="updateUserImage" class="custom-file-label"></label>
                        <span class="custom-file-control form-control-file"></span>
                    </div>
                    <input type="hidden" id="previousPicture" value="">
                    <input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
                </div>
            </div>
        </div>
        <hr/>
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="update-firstname">Firstname</label>&nbsp;
                    <input type="text" name="update-firstname" id="update-firstname" class="form-control" placeholder="Firstname" required>
                </div>
                <div class="col">
                    <label for="update-lastname">Lastname</label>&nbsp;
                    <input type="text" name="update-lastname" id="update-lastname" class="form-control" placeholder="Lastname" required>
                </div>
            </div>
        </div>
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="update-contact-number">Contact Number</label>&nbsp;
                    <input type="text" name="update-contact-number" id="update-contact-number" class="form-control" placeholder="(e.g. +63995772230)"/>
                </div>
                <div class="col">
                    <label for="update-email-address">Email Address</label>&nbsp;
                    <input type="text" name="update-email-address" id="update-email-address" class="form-control" placeholder="(e.g. myemail@host.com)"/>
                </div>
            </div>
        </div>
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="update-user-status">Status</label>&nbsp;
                    <select name="update-user-status" id="update-user-status" class="custom-select mr-sm-2"/>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col">
                    <label for="update-access-level">Access Level</label>&nbsp;
                    <select name="update-access-level" id="update-access-level" class="custom-select mr-sm-2"/>
                        <option value="0">Registered User</option>
                        <option value="1">Administrator</option>
                    </select>
                </div>
            </div>
        </div>
        <hr />
        <div class="form-group" >
            <div class="custom-control custom-checkbox" id="reset-password-div">
                <input type="checkbox" class="custom-control-input" name="user-check-box" id="user-check-box" />&nbsp;
                <label for="user-check-box" class="custom-control-label">Reset Password?</label>
            </div>
        </div>
        <div class="form-group" ><label for="update-username">Username</label>&nbsp;<input type="text" name="update-username" id="update-username" class="form-control" disabled="disabled"></div>
        <div class="form-group" ><label for="update-password">Password</label>&nbsp;<input type="text" name="update-password" id="update-password" class="form-control" disabled="disabled"/></div>
        <input type="hidden" name="auth" value="<?php echo 1111; ?>" />
        <?php
            echo form_close();
        ?>
      </div>
      <div class="modal-footer">
        <button type="submit"  id="save-update-user-button" class="btn btn-primary">Save changes</button>
        <button type="button" id="cancel-update-user-button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userInfoLabel">Add User Account</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="user-add-warning-message"></div>
        <?php
            echo form_open("dashboard/add_user",array("id"=>"add-user-account","method"=>"post"));
        ?>
        <div class="form-group" >
            <div class="form-row">
                <div class="col picture-selection">
                    <img src="<?php echo base_url() . "assets/css/images/no-pic.jpg"; ?>" class="img-user img-fluid rounded mx-auto d-block"/><br/>
                    <div class="custom-file">
                        <input type="file" name="addUserImage" id="addUserImage" class="custom-file-input" aria-describedby="fileHelp"/>
                        <label for="addUserImage" class="custom-file-label"></label>
                        <span class="custom-file-control form-control-file"></span>
                    </div>
                    <input type="hidden" id="add_base_url" value="<?php echo base_url(); ?>">
                </div>
            </div>
        </div>
        <hr/>
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="add-firstname">Firstname</label>&nbsp;
                    <input type="text" name="add-firstname" id="add-firstname" class="form-control" placeholder="Firstname" required>
                </div>
                <div class="col">
                    <label for="add-lastname">Lastname</label>&nbsp;
                    <input type="text" name="add-lastname" id="add-lastname" class="form-control" placeholder="Lastname" required>
                </div>
            </div>
        </div>
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="add-contact-number">Contact Number</label>&nbsp;
                    <input type="text" name="add-contact-number" id="add-contact-number" class="form-control" placeholder="(e.g. +63995772230)"/>
                </div>
                <div class="col">
                    <label for="add-email-address">Email Address</label>&nbsp;
                    <input type="text" name="add-email-address" id="add-email-address" class="form-control" placeholder="(e.g. myemail@host.com)"/>
                </div>
            </div>
        </div>
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="add-user-status">Status</label>&nbsp;
                    <select name="add-user-status" id="add-user-status" class="custom-select mr-sm-2"/>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col">
                    <label for="add-access-level">Access Level</label>&nbsp;
                    <select name="add-access-level" id="add-access-level" class="custom-select mr-sm-2"/>
                        <option value="0">Registered User</option>
                        <option value="1">Administrator</option>
                    </select>
                </div>
            </div>
        </div>
        <hr />
        <hr />
        <div class="form-group" ><label for="add-username">Username</label>&nbsp;<input type="text" name="add-username" id="add-username" class="form-control" disabled="disabled"></div>
        <div class="form-group" ><label for="add-password">Password</label>&nbsp;<input type="text" name="add-password" id="add-password" class="form-control" disabled="disabled"/></div>
        <input type="hidden" name="auth" value="<?php echo 1111; ?>" />
        <?php
            echo form_close();
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" id="save-add-user-button" class="btn btn-primary">Save changes</button>
        <button type="button" id="cancel-add-user-button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="userAccountMessageModal" tabindex="-1" role="dialog" aria-labelledby="userAccountMessageModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userAccountMessageModalTitle">Information Message</h5>
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