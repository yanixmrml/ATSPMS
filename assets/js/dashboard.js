$(document).ready(function(){
    
    /********* Users *************************/
    
    function populateUserAccountsTable(search_name){
	var src = $("#get-users-list").attr("action");
	$.ajax({
	    url: src,
	    type: 'POST',
	    data: {name:search_name},
	    dataType: 'html',
	    beforeSend: function(){
		$("#dynamicMessage-users").html("Loading the user accounts list...");
	    },
	    success: function(data,textStatus,jqXHR){
		var tableName = $("#users-table");
                var tbody = $("#users-table tbody");
                $('tbody tr',tableName).remove();
		//alert("SDSDSD");
                if(data !=''&& data!=null){                    
                    tbody.append(data);
                }else{
                    var row = "<tr ><td colspan='10'><p class='information-message'>Search result is empty. No records match '" +  search_name  +  "'</p></td></tr>";
                    tbody.append(row);
                }
	    },
	    error: function(jqXHR, textStatus, errorThrown){
		$("#userAccountMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		$("#userAccountMessageModal").modal("show");
	    },
	    complete: function(){
		$("#dynamicMessage-users").html("");
	    }
	});
    }
    
    $("#user-search-button").click(function(){
	//alert("Searching " + $("#search-name").val());
        populateUserAccountsTable($("#search-name").val());
    });
    
    $("#search-name").keypress(function(e){
	if(e.which==13){
	    e.preventDefault();
	    populateUserAccountsTable($("#search-name").val());
	}
    });
    
    $(document).on('focus','#search-name',function(){
	$("#search-name").val("");
    });
    
    
    /******* Update User Account *******/
    //Be carefull with Enye, fixed that problem
    function processSelectedUserAccount(id){
        var src = $("#get-users-list").attr("action");
	$('#update-user-account').get(0).reset();
        $.ajax({
	    url: src,
	    type: 'POST',
	    data: {user_id:id},
	    dataType: 'json',
	    beforeSend: function(){
		$("#update-password").val("");
		$("#updateUserImage").val("");
		$(".custom-file-label").html("");
		$("#user-update-warning-message").html("");
		$("#dynamicMessage-users").html("Please wait while opening the information of selected user account...");
	    },
	    success: function(data,textStatus,jqXHR){
		if(data!=null && data.responseText!='' && data.length!=0){
		    $("#update-account-number").val(data["user_id"]);
		    $("#update-username").val(data["username"]);
		    $("#update-firstname").val(data["firstname"]);
		    $("#update-lastname").val(data["lastname"]);
		    $("#update-contact-number").val(data["contact_number"]);
		    $("#update-email-address").val(data["email_address"]);
		    $("#update-user-status").val(data["status"]);
		    $("#update-access-level").val(data["access_level"]);
		    if(data["picture"]!=null && data["picture"]!=""){
			$("#previousPicture").val(data["picture"]);
			$("#selected-update-img-thumbnail").attr("src",$("#base_url").val() + "/uploads/" + data["picture"]);
		    }else{
			$("#previousPicture").val("");
			$("#selected-update-img-thumbnail").attr("src",$("#base_url").val() + "assets/css/images/no-pic.jpg");
		    }
		}else{                    
                    $("#userAccountMessageModal").find('.modal-body p').html("Problem occured, transaction failed. Record of the selected " +
								      " user account cannot be viewed. Kindly contact the developer.");
		    $("#userAccountMessageModal").modal("toggle");
                }
		$("#updateUserModal").modal("show");
		$("#userAccountMessageModal").modal("hide");
	    },
	    error: function(jqXHR, textStatus, errorThrown){
		$("#userAccountMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		$("#userAccountMessageModal").modal("show");
	    },
	    complete: function(){		
		$("#user-check-box").prop('checked',false);
		$("#dynamicMessage-users").html("");
	    }
	});
    }
    
    $(document).on('click','.update-user-button',function(){//.find("input.subject-enroll").text());
       processSelectedUserAccount($(this).attr("id"));
    });
    
    $(".custom-file-input").on("change", function(){
	var fileName = $(this).val();
	$(this).next(".custom-file-label").addClass("selected").html(fileName);
    });
    
    function processUpdateUserAccount(id,f_name,l_name,u_name,pass,c_number,e_address,u_status,a_level,reset_pass){
	var src = $("#update-user-account").attr("action");
	var form = $('form').get(1);
	var formData = new FormData(form);
	formData.append("user_id",id);
	formData.append("firstname",f_name);
	formData.append("lastname",l_name);
	formData.append("username",u_name);
	formData.append("password",pass);
	formData.append("contact_number",c_number);
	formData.append("email_address",e_address);
	formData.append("user_status",u_status);	
	formData.append("picture",$("#updateUserImage").val());
	formData.append("previousPicture",$("#previousPicture").val());	
	formData.append("access_level",a_level);	
	formData.append("is_reset",reset_pass);
	$.ajax({
	    url: src,
	    type: 'POST',
	    data: formData,
	    processData: false,
	    contentType: false,
	    dataType: 'html',
	    beforeSend: function(){
		$("#updateUserModal").modal("hide");
		$("#userAccountMessageModal").find('.modal-body p').html("Please wait while saving the changes of this user account...");
		$("#userAccountMessageModal").modal("show");
	    },
	    success: function(data,textStatus,jqXHR){
		$("#userAccountMessageModal").find('.modal-body p').html(data);
	    },
	    error: function(jqXHR, textStatus, errorThrown){
		$("#userAccountMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		$("#userAccountMessageModal").modal("show");
	    },
	    complete: function(){
		
	    }
	});
    }
    
    $(document).on('click','#save-update-user-button',function(){
        //$("#update-user-account").submit();
	//$("#userAccountMessageModal").modal("show");
	//alert("Hello");
	if($("#update-lastname").val()==""){
            $("#update-lastname").focus();
            $("#user-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the lastname correctly.</div>");
            $(document).on('alert','.alert');
        }else if($("#update-firstname").val()==""){
            $("#update-firstname").focus();
            $("#user-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the firstname correctly.</div>");
            $(document).on('alert','.alert');
        }else if($("#update-username").val()==""
                 || $("#update-username").val().length <6){
            $("#update-username").focus();
            $("#user-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Username must be at least 6 characters characters.</div>");
            $(document).on('alert','.alert');
        }else if(($("#update-password").val()=="" ||
           $("#update-password").val().length <6 || $("#update-password").val().length >16)
	    && $("#update-user-check-box").prop("checked")){
            $("#update-password").focus();
            $("#user-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Password must at least 6 characters and 16 characters</div>");
            $(document).on('alert','.alert');
        }else{
	    $("#user-update-warning-message").html("");
	    processUpdateUserAccount($("#update-account-number").val(), $("#update-firstname").val(),
				     $("#update-lastname").val(),$("#update-username").val(),$("#update-password").val(),$("#update-contact-number").val(),
				     $("#update-email-address").val(),$("#update-user-status").val(),
				     $("#update-access-level").val(),($("#user-check-box").prop("checked")?1:0));
	}
    });
    
    function generateRandomPassword(id){
	var text = "";
	var possible = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	for(var i=0; i < 6; i++){
	    text += possible.charAt(Math.floor(Math.random()* possible.length));
	}
	$(id).val(text);	
    }
    
    $(document).on('click','#user-check-box',function(){
	if($("#user-check-box").prop("checked")){
	    generateRandomPassword("#update-password");
	}else{
	    $("#update-password").val("");    
	}
    });
    
    $(document).on('keyup','#update-firstname',function(){
	var fname = $("#update-firstname").val()!=null?$("#update-firstname").val().toLowerCase():"";
	var lname = $("#update-lastname").val()!=null?$("#update-lastname").val().toLowerCase():"";
	$("#update-username").val(fname.replace(" ",'')+ "." + lname.replace(" ",''));
	$("#user-update-warning-message").html("");
    });
    
    $(document).on('keyup','#update-lastname',function(){
	var fname = $("#update-firstname").val()!=null?$("#update-firstname").val().toLowerCase():"";
	var lname = $("#update-lastname").val()!=null?$("#update-lastname").val().toLowerCase():"";
	$("#update-username").val(fname.replace(" ",'')+ "." + lname.replace(" ",''));
	$("#user-update-warning-message").html("");
    });
    
    $("#user-add-button").click(function(){
	$("#addUserModal").modal("show");
	$("#userAccountMessageModal").modal("hide");
	$("#add-picture").val("");
	$("#add-firstname").val("");
	$("#add-lastname").val("");
	$("#add-contact-number").val("");
	$("#add-email-address").val("");
	$("#add-user-status").val("1");
	$("#add-access-level").val("0");
	$("#add-username").val("");
	$("#addUserImage").val("");
	$(".form-control-file").html("");
	$("#user-add-warning-message").html("");
	generateRandomPassword("#add-password");
    });
    
     $(document).on('keyup','#add-firstname',function(){
	var fname = $("#add-firstname").val()!=null?$("#add-firstname").val().toLowerCase():"";
	var lname = $("#add-lastname").val()!=null?$("#add-lastname").val().toLowerCase():"";
	$("#add-username").val(fname.replace(" ",'')+ "." + lname.replace(" ",''));
	$("#user-add-warning-message").html("");
    });
    
    $(document).on('keyup','#add-lastname',function(){
	var fname = $("#add-firstname").val()!=null?$("#add-firstname").val().toLowerCase():"";
	var lname = $("#add-lastname").val()!=null?$("#add-lastname").val().toLowerCase():"";
	$("#add-username").val(fname.replace(" ",'')+ "." + lname.replace(" ",''));
	$("#user-add-warning-message").html("");
    });
    
    function processAddUserAccount(f_name,l_name,u_name,pass,c_number,e_address,u_status,a_level){
	var src = $("#add-user-account").attr("action");
	var form = $('form').get(1);
	var formData = new FormData(form);
	formData.append("user_id",id);
	formData.append("firstname",f_name);
	formData.append("lastname",l_name);
	formData.append("username",u_name);
	formData.append("password",pass);
	formData.append("contact_number",c_number);
	formData.append("email_address",e_address);
	formData.append("user_status",u_status);	
	formData.append("picture",$("#addUserImage").val());
	formData.append("access_level",a_level);
	$.ajax({
	    url: src,
	    type: 'POST',
	    data: formData,
	    contentType: false,
	    processData: false,
	    dataType: 'html',
	    beforeSend: function(){
		$("#addUserModal").modal("hide");
		$("#userAccountMessageModal").find('.modal-body p').html("Please wait while saving the changes of this user account...");
		$("#userAccountMessageModal").modal("show");	
	    
	    },
	    success: function(data,textStatus,jqXHR){
		$("#userAccountMessageModal").find('.modal-body p').html(data);
	    },
	    error: function(jqXHR, textStatus, errorThrown){
		$("#userAccountMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		$("#userAccountMessageModal").modal("show");
	    },
	    complete: function(){
		
	    }
	});
    }
    
    $(document).on('click','#save-add-user-button',function(){
        //$("#update-user-account").ssubmit();
	//$("#userAccountMessageModal").modal("show");
	if($("#add-firstname").val()==""){
            $("#add-firstname").focus();
            $("#user-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the firstname correctly.</div>");
            $(document).on('alert','.alert');
	}else if($("#add-lastname").val()==""){
            $("#add-lastname").focus();
            $("#user-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the lastname correctly.</div>");
            $(document).on('alert','.alert');
        }else if($("#add-username").val()==""
                 || $("#add-username").val().length <6){
            $("#add-username").focus();
            $("#user-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Username must be at least 6 characters characters.</div>");
            $(document).on('alert','.alert');
        }else if(($("#add-password").val()=="" ||
           $("#add-password").val().length <6 || $("#add-password").val().length >16)){
            $("#add-password").focus();
            $("#user-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Password must at least 6 characters and 16 characters</div>");
            $(document).on('alert','.alert');
        }else{
	    $("#user-add-warning-message").html("");
	    processAddUserAccount($("#add-firstname").val(),
				     $("#add-lastname").val(),$("#add-username").val(),$("#add-password").val(),$("#add-contact-number").val(),
				     $("#add-email-address").val(),$("#add-user-status").val(),
				     $("#add-access-level").val());
	}
	
    });
    
    /*********** Settings ******************/
    
    $('#search-effectivity-date-calendar').datetimepicker({
        //language:  'fr',        
        pickTime: false
    });
    
    function populateSettingsTable(effect_date){
	var src = $("#get-settings-list").attr("action");
	$.ajax({
	    url: src,
	    type: 'POST',
	    data: {effectivity_date:effect_date},
	    dataType: 'html',
	    beforeSend: function(){
		$("#dynamicMessage-settings").html("Loading the settings list...");
	    },
	    success: function(data,textStatus,jqXHR){
		var tableName = $("#settings-table");
                var tbody = $("#settings-table tbody");
                $('tbody tr',tableName).remove();
		
		if(data!=''&& data!=null){                    
                    tbody.append(data);
                }else{
                    var row = "<tr><td colspan='10'><p class='information-message'>Search result is empty. No records match at selected effectivity date.</p></td></tr>";
                    tbody.append(row);
                }
	    },
	    error: function(jqXHR, textStatus, errorThrown){
		$("#settingsMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		$("#settingsMessageModal").modal("show");
	    },
	    complete: function(){
		$("#dynamicMessage-settings").html("");
	    }
	});
    }
    
    $("#settings-search-button").click(function(){
	//alert("Searching " + $("#search-name").val());
        populateSettingsTable($("#search-effectivity-date").val());
    });
    
    $("#search-effectivity-date").keypress(function(e){
	if(e.which==13){
	    e.preventDefault();
	    populateSettingsTable($("#search-effectivity-date").val());
	}
    });
    
    $('#update-effectivity-date-calendar').datetimepicker({
        //language:  'fr',
        pickTime: false
    });
    
    function processSelectedSettings(id){
        var src = $("#get-settings-list").attr("action");
        $.ajax({
	    url: src,
	    type: 'POST',
	    data: {settings_id:id},
	    dataType: 'json',
	    beforeSend: function(){
		$("#settings-update-warning-message").html("");
		$("#dynamicMessage-settings").html("Please wait while opening the information of selected settings configuration...");
	    },
	    success: function(data,textStatus,jqXHR){
		if(data!=null && data.responseText!='' && data.length!=0){
		    $("#update-voltage-max").val(data["voltage_max"]);
		    $("#update-voltage-min").val(data["voltage_min"]);
		    $("#update-current-max").val(data["current_max"]);
		    $("#update-current-min").val(data["current_min"]);
		    $("#update-frequency-max").val(data["frequency_critical_max"]);
		    $("#update-frequency-min").val(data["frequency_critical_min"]);
		    $("#update-power-max").val(data["power_max"]);
		    $("#update-power-goal").val(data["power_goal"]);
		    $("#update-power-factor").val(data["power_factor_goal"]);
		    $("#update-battery-level").val(data["battery_level"]);
		    $("#update-temperature-level").val(data["temperature_level"]);
		    $("#update-cost-pkwh").val(data["cost_pkwh"]);
		    $("#update-cost-goal").val(data["cost_goal"]);
		    $("#update-nominal-voltage").val(data["nominal_voltage"]);
		    $("#update-nominal-frequency").val(data["nominal_frequency"]);
		    //$('#update-effectivity-date').datepicker('update',data["start_effectivity_date"]);
		    $('#update-effectivity-date').val(data["start_effectivity_date"]);
		    $('#update-effectivity-date-calendar').datetimepicker("update");
		    $("#update-settings-id").val(data["settings_id"]);
		    $("#updateSettingsModal").modal("show");
		    $("#settingsMessageModal").modal("hide");
		}else{                    
                    $("#settingsMessageModal").find('.modal-body p').html("Problem occured, transaction failed. Record of the selected " +
								      " user account cannot be viewed. Kindly contact the developer.");
		    $("#settingsMessageModal").modal("toggle");
                }
	    },
	    error: function(jqXHR, textStatus, errorThrown){
		$("#settingsMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		$("#settingsMessageModal").modal("show");
	    },
	    complete: function(){
		$("#dynamicMessage-settings").html("");
	    }
	});
    }
    
    $(document).on('click','.update-settings-button',function(){//.find("input.subject-enroll").text());
       processSelectedSettings($(this).attr("id"));
    });
    
    function processUpdateSettings(max_v,min_v,max_c,min_c,max_f,min_f,max_p,p_goal,pf_goal,bat_level,temp_level,c_pkwh,c_goal,
				nom_v,nom_f,effect_date,u_id,s_id){
	var src = $("#update-settings").attr("action");
	$.ajax({
	    url: src,
	    type: 'POST',
	    data: {max_voltage:max_v,min_voltage:min_v,max_current:max_c,min_current:min_c, max_frequency:max_f,
		min_frequency:min_f, max_power:max_p, power_goal:p_goal, cost_pkwh:c_pkwh, cost_goal:c_goal,
		power_factor_goal:pf_goal, battery_level:bat_level, temperature_level:temp_level, nominal_voltage:nom_v, nominal_frequency:nom_f,
		effectivity_date:effect_date,user_id:u_id,settings_id:s_id},
	    dataType: 'html',
	    beforeSend: function(){
		$("#updateSettingsModal").modal("hide");
		$("#settingsMessageModal").find('.modal-body p').html("Please wait while saving the changes of this settings configuration...");
		$("#settingsMessageModal").modal("show");
	    },
	    success: function(data,textStatus,jqXHR){
		$("#settingsMessageModal").find('.modal-body p').html("The selected settings configuration was successfully saved.");
	    },
	    error: function(jqXHR, textStatus, errorThrown){
		$("#settingsMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		$("#settingsMessageModal").modal("show");
	    },
	    complete: function(){
		$("#dynamicMessage-settings").html("");
	    }
	});
    }
    
    $(document).on('click','#save-update-settings-button',function(){
	//settings-add-warning-message
	if($("#update-effectivity-date").val()==""){
	    $("#update-effectivity-date").focus();
	    $("#settings-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter effectivity date approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#update-voltage-max").val()==""){
	    $("#update-voltage-max").focus();
	    $("#settings-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter maximum voltage approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#update-voltage-min").val()==""){
	    $("#update-voltage-min").focus();
	    $("#settings-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter minimum voltage approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#update-current-max").val()==""){
	    $("#update-current-max").focus();
	    $("#settings-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter maximum current approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#update-current-min").val()==""){
	    $("#update-current-min").focus();
	    $("#settings-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter minimum current approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#update-frequency-max").val()==""){
	    $("#update-frequency-max").focus();
	    $("#settings-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter maximum critical frequency approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#update-frequency-min").val()==""){
	    $("#update-frequency-min").focus();
	    $("#settings-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter minimum critical frequency approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#update-power-max").val()==""){
	    $("#update-power-max").focus();
	    $("#settings-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter maximum power approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#update-power-goal").val()==""){
	    $("#update-power-goal").focus();
	    $("#settings-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter power goal approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#update-power-factor").val()==""){
	    $("#update-power-factor").focus();
	    $("#settings-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the target power factor approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#update-battery-level").val()==""){
	    $("#update-battery-level").focus();
	    $("#settings-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the critical batter level approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#update-temperature-level").val()==""){
	    $("#update-temperature-level").focus();
	    $("#settings-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the critical temperature level approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#update-cost-pkwh").val()==""){
	    $("#update-cost-pkwh").focus();
	    $("#settings-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the cost pkwh approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#update-cost-goal").val()==""){
	    $("#update-cost-goal").focus();
	    $("#settings-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the target power cost per day approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#update-nominal-voltage").val()==""){
	    $("#update-nominal-voltage").focus();
	    $("#settings-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the nominal voltage approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#update-nominal-frequency").val()==""){
	    $("#update-nominal-frequency").focus();
	    $("#settings-update-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter nominal frequency approriately.</div>");
            $(document).on('alert','.alert');
	}else{
	    processUpdateSettings($("#update-voltage-max").val(),$("#update-voltage-min").val(),
			       $("#update-current-max").val(),$("#update-current-min").val(),
			       $("#update-frequency-max").val(),$("#update-frequency-min").val(),
			       $("#update-power-max").val(),
			       $("#update-power-goal").val(),$("#update-power-factor").val(),
			       $("#update-battery-level").val(),$("#update-temperature-level").val(),
			       $("#update-cost-pkwh").val(),$("#update-cost-goal").val(),
			       $("#update-nominal-voltage").val(),$("#update-nominal-frequency").val(),
			       $("#update-effectivity-date").val(),$("#update-settings-user-id").val(),$("#update-settings-id").val());
	}
    });
    
    $(document).on('keyup','#updateSettingsModal input',function(){
	$("#settings-update-warning-message").html("");
    });
    
    //#add-effectivity-date
    $('#add-effectivity-date-calendar').datetimepicker({
        //language:  'fr',
        pickTime: false
    });
    
    $(document).on('click','#settings-add-button',function(){
	$("#settingsMessageModal").modal("hide");
	$("#settings-add-warning-message").html("");
	$("#addSettingsModal").modal("show");
	$("#add-voltage-max").val("");
	$("#add-voltage-min").val("");
	$("#add-current-max").val("");
	$("#add-current-min").val("");
	//$("#add-frequency-max").val("");
	//$("#add-frequency-min").val("");
	$("#add-power-max").val("");
	$("#add-power-goal").val("");
	$("#add-power-factor").val("");
	$("#add-battery-level").val("");
	$("#add-temperature-level").val("");
	$("#add-cost-pkwh").val("");
	$("#add-cost-goal").val("");
	//$("#add-nominal-voltage").val("");
	//$("#add-nominal-frequency").val("");
	$("#add-effectivity-date").val("");
	$("#add-effectivity-date-calendar").datetimepicker("update");
	//generateRandomPassword("#add-password");
    });
    
    function processAddSettings(max_v,min_v,max_c,min_c,max_f,min_f,max_p,p_goal,pf_goal,bat_level,temp_level,c_pkwh,c_goal,
				nom_v,nom_f,effect_date,u_id){
	var src = $("#add-settings").attr("action");
	$.ajax({
	    url: src,
	    type: 'POST',
	    data: {max_voltage:max_v,min_voltage:min_v,max_current:max_c,min_current:min_c, max_frequency:max_f,
		min_frequency:min_f, max_power:max_p, power_goal:p_goal, cost_pkwh:c_pkwh, cost_goal:c_goal,
		power_factor_goal:pf_goal, battery_level:bat_level, temperature_level:temp_level, nominal_voltage:nom_v, nominal_frequency:nom_f,
		effectivity_date:effect_date,user_id:u_id},
	    dataType: 'html',
	    beforeSend: function(){
		$("#addSettingsModal").modal("hide");
		$("#settingsMessageModal").find('.modal-body p').html("Please wait while saving the changes of this settings configuration...");
		$("#settingsMessageModal").modal("show");
	    },
	    success: function(data,textStatus,jqXHR){
		$("#settingsMessageModal").find('.modal-body p').html("The newly created setting configuration was successfully saved.");
	    },
	    error: function(jqXHR, textStatus, errorThrown){
		$("#settingsMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		$("#settingsMessageModal").modal("show");
	    },
	    complete: function(){
		$("#dynamicMessage-settings").html("");
	    }
	});
    }
    
    $(document).on('click','#save-add-settings-button',function(){
	if($("#add-effectivity-date").val()==""){
	    $("#add-effectivity-date").focus();
	    $("#settings-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the effectivity date approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#add-voltage-max").val()==""){
	    $("#add-voltage-max").focus();
	    $("#settings-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter maximum voltage approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#add-voltage-min").val()==""){
	    $("#add-voltage-min").focus();
	    $("#settings-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter minimum voltage approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#add-current-max").val()==""){
	    $("#add-current-max").focus();
	    $("#settings-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter maximum current approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#add-current-min").val()==""){
	    $("#add-current-min").focus();
	    $("#settings-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter minimum current approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#add-frequency-max").val()==""){
	    $("#add-frequency-max").focus();
	    $("#settings-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter maximum critical frequency approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#add-frequency-min").val()==""){
	    $("#add-frequency-min").focus();
	    $("#settings-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter maximum critical frequency approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#add-power-max").val()==""){
	    $("#add-power-max").focus();
	    $("#settings-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter maximum power approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#add-power-goal").val()==""){
	    $("#add-power-goal").focus();
	    $("#settings-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the target power consumption per day approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#add-power-factor").val()==""){
	    $("#add-power-factor").focus();
	    $("#settings-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the target power factor approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#add-battery-level").val()==""){
	    $("#add-battery-level").focus();
	    $("#settings-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the critical battery level approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#add-temperature-level").val()==""){
	    $("#add-temperature-level").focus();
	    $("#settings-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the critical temperature level approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#add-cost-pkwh").val()==""){
	    $("#add-cost-pkwh").focus();
	    $("#settings-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the electrical cost pkwh approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#add-cost-goal").val()==""){
	    $("#add-cost-goal").focus();
	    $("#settings-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the target electrical cost consumption approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#add-nominal-voltage").val()==""){
	    $("#add-nominal-voltage").focus();
	    $("#settings-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the nominal voltage approriately.</div>");
            $(document).on('alert','.alert');
	}else if($("#add-nominal-frequency").val()==""){
	    $("#add-nominal-frequency").focus();
	    $("#settings-add-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the nominal frequency approriately.</div>");
            $(document).on('alert','.alert');
	}else{
	    processAddSettings($("#add-voltage-max").val(),$("#add-voltage-min").val(),
			       $("#add-current-max").val(),$("#add-current-min").val(),
			       $("#add-frequency-max").val(),$("#add-frequency-min").val(),
			       $("#add-power-max").val(),
			       $("#add-power-goal").val(),$("#add-power-factor").val(),
			       $("#add-battery-level").val(),$("#add-temperature-level").val(),
			       $("#add-cost-pkwh").val(),$("#add-cost-goal").val(),
			       $("#add-nominal-voltage").val(),$("#add-nominal-frequency").val(),
			       $("#add-effectivity-date").val(),$("#add-settings-user-id").val());
	}
    });
    
    $(document).on("keyup","#addSettingsModal input",function(){
	 $("#settings-add-warning-message").html("");
    });
    /*//Testing
    var counter = 1;
    setInterval(function(){
        
        $("#counter-id").html("Counter : " + counter);
	counter++;
        },1500); */
   
    /******************** Home ********************/
    
    $(document).on('click',"#home-change-password",function(){
        $("#passwordModal").modal("show");
	$("#old-password").val("");
	$("#new-password").val("");
	$("#confirm-password").val("");
    });
    
    $(document).on('click','#save-pass-button',function(){
        if($("#old-password").val()=="" ||
	    $("#old-password").val().length <6){
            $("#old-password").focus();
            $("#password-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter your old password correctly.</div>");
            $(document).on('alert','.alert');
        }else if($("#new-password").val()==""
                 || $("#new-password").val().length <6
                 || $("#new-password").val().length >16){
            $("#new-password").focus();
            $("#password-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Password must be at least 6 characters and at most 16 characters.</div>");
            $(document).on('alert','.alert');
        }else if($("#confirm-password").val()==""){
            $("#confirm-password").focus();
            $("#password-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please confirm your new password.</div>");
            $(document).on('alert','.alert');
        }else if($("#new-password").val()!=$("#confirm-password").val()){
            $("#confirm-password").focus();
            $("#password-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please verify your password correctly, confirm password doesn't match with new password.</div>");
            $(document).on('alert','.alert');
        }else{
            var src = $("#update-password-form").attr("action");
	    $.ajax({
		url: src,
		type: 'POST',
		data: {user_id:$("#home-user-id").val(),old_password:$("#old-password").val(),
		    new_password:$("#new-password").val()},
		dataType: 'html',
		beforeSend: function(){
		    $("#passwordModal").modal("hide");
		    $("#homeMessageModal").find('.modal-body p').html("Please wait while saving your new password.");
		    $("#homeMessageModal").modal("show");
		},
		success: function(data,textStatus,jqXHR){
		    $("#homeMessageModal").find('.modal-body p').html(data);
		    $("#homeMessageModal").modal("show");
		},
		error: function(jqXHR, textStatus, errorThrown){
		    $("#settingsMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		    $("#settingsMessageModal").modal("show");
		},
		complete: function(){
		    $("#dynamicMessage-settings").html("");
		}
	    });
        }
    });
    
    $("#passwordModal").on("hide.bs.modal",function(e){
        $("#password-warning-message").text("");
        $("#old-password").val("");
        $("#new-password").val("");
        $("#confirm-password").val("");
    });
    
    $("#old-password").keypress(function(event){
        $("#password-warning-message").text("");
    });
    
    $("#new-password").keypress(function(event){
        $("#password-warning-message").text("");
    });
    
    $("#confirm-password").keypress(function(event){
        $("#password-warning-message").text("");
    });
    
    $("#update-other-button").click(function(){
        $("#otherInfoModal").modal("toggle");
        $("#profile-message").alert("close");
    });
    
    $(document).on('click','#save-other-info-button',function(){
        $("#update-other-info-frm").submit();
    });
    
    $(function(){
	$("#notificationsPane").jCarouselLite({
	    btnNext: "#nextNotifications",
	    btnPrev: "#prevNotifications",
	    vertical: true,
	    auto: 4000,
	    speed: 1000
	});
    });
    
    
    
});
