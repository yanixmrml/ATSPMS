$(document).ready(function(){
    
    $('#demo').calendarsPicker();
    //$('#search-date').dcalendar(); //creates the calendar
    
    /********* Users *************************/
    
    function populateUserAccountsTable(search_name){
	var src = $("#get-users-list").attr("action");
	$.post(src,{name:search_name},
            function(data){
                var tableName = $("#users-table");
                var tbody = $("#users-table tbody");
                $('tbody tr',tableName).remove();
		//alert("SDSDSD");
                if(data!=''&& data!=null){                    
                    tbody.append(data);
                }else{
                    var row = "<tr ><td colspan='10'><p class='information-message'>Search result is empty. No records match '" +  search_name  +  "'</p></td></tr>";
                    tbody.append(row);
                }
                $("#dynamicMessage-users").html("");
        },'html');
    }
    
    $("#user-search-button").click(function(){
	//alert("Searching " + $("#search-name").val());
        $("#dynamicMessage-users").html("Loading the user accounts list...");
        populateUserAccountsTable($("#search-name").val());
    });
    
    $("#search-name").keypress(function(e){
	if(e.which==13){
	    e.preventDefault();
	    $("#dynamicMessage-users").html("Loading the user accounts list...");
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
        $.post(src,{user_id:id},
            function(data){
                if(data!=null && data.responseText!='' && data.length!=0){
		    $("#update-account-number").val(data["user_id"]);
		    $("#update-username").val(data["username"]);
		    $("#update-firstname").val(data["firstname"]);
		    $("#update-lastname").val(data["lastname"]);
		    $("#update-contact-number").val(data["contact_number"]);
		    $("#update-email-address").val(data["email_address"]);
		    $("#update-user-status").val(data["status"]);
		    $("#update-access-level").val(data["access_level"]);
		}else{                    
                    $("#userAccountMessageModal").find('.modal-body p').html("Problem occured, transaction failed. Record of the selected " +
								      " user account cannot be viewed. Kindly contact the developer.");
		    $("#userAccountMessageModal").modal("toggle");
                }
                $("#dynamicMessage-users").html("");    
		$("#updateUserModal").modal("show");
		$("#userAccountMessageModal").modal("hide");
        },'json');
    }
    
    $(document).on('click','.update-user-button',function(){//.find("input.subject-enroll").text());
       $("#dynamicMessage-users").html("Please wait while opening the information of selected user account...");
       $("#user-check-box").removeAttr("checked");
       $("#update-password").val("");
       processSelectedUserAccount($(this).attr("id"));
    });
    
    function processUpdateUserAccount(id,f_name,l_name,u_name,pass,c_number,e_address,u_status,pic,a_level){
	var src = $("#update-user-account").attr("action");
	$.post(src,{user_id:id,firstname:f_name,lastname:l_name,username:u_name,password:pass,contact_number:c_number,
	    email_address:e_address,user_status:u_status,picture:pic,access_level:a_level},
	    function(data){
		//alert(data.responseText);
		$("#userAccountMessageModal").find('.modal-body p').html(data);
	},'html');
    }
    
    $(document).on('click','#save-update-user-button',function(){
        //$("#update-user-account").submit();
	//$("#userAccountMessageModal").modal("show");
	if($("#update-lastname").val()==""){
            $("#update-lastname").focus();
            $("#update-user-info-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the lastname correctly.</div>");
            $(document).on('alert','.alert');
        }else if($("#update-firstname").val()==""){
            $("#update-firstname").focus();
            $("#update-user-info-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the firstname correctly.</div>");
            $(document).on('alert','.alert');
        }else if($("#update-username").val()==""
                 || $("#update-username").val().length <6){
            $("#update-username").focus();
            $("#update-user-info-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Username must be at least 6 characters characters.</div>");
            $(document).on('alert','.alert');
        }else if(($("#update-password").val()=="" ||
           $("#update-password").val().length <6 || $("#update-password").val().length >16)
	    && $("#update-user-check-box").prop("checked")){
            $("#update-password").focus();
            $("#update-user-info-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Password must at least 6 characters and 16 characters</div>");
            $(document).on('alert','.alert');
        }else{
        
	    $("#updateUserModal").modal("hide");
	    $("#userAccountMessageModal").find('.modal-body p').html("Please wait while saving the changes of this user account...");
	    $("#userAccountMessageModal").modal("show");	
	    
	    processUpdateUserAccount($("#update-account-number").val(), $("#update-firstname").val(),
				     $("#update-lastname").val(),$("#update-username").val(),$("#update-password").val(),$("#update-contact-number").val(),
				     $("#update-email-address").val(),$("#update-user-status").val(),$("#update-user-picture").val(),
				     $("#update-access-level").val());
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
	generateRandomPassword("#update-password");
    });
    
    $(document).on('keyup','#update-firstname',function(){
	var fname = $("#update-firstname").val()!=null?$("#update-firstname").val().toLowerCase():"";
	var lname = $("#update-lastname").val()!=null?$("#update-lastname").val().toLowerCase():"";
	$("#update-username").val(fname.replace(" ",'')+ "." + lname.replace(" ",''));
    });
    
    $(document).on('keyup','#update-lastname',function(){
	var fname = $("#update-firstname").val()!=null?$("#update-firstname").val().toLowerCase():"";
	var lname = $("#update-lastname").val()!=null?$("#update-lastname").val().toLowerCase():"";
	$("#update-username").val(fname.replace(" ",'')+ "." + lname.replace(" ",''));
    });
    
    $("#user-add-button").click(function(){
	$("#addUserModal").modal("show");
	//$("#userAccountMessageModal").modal("hide");
	$("#add-picture").val("");
	$("#add-firstname").val("");
	$("#add-lastname").val("");
	$("#add-contact-number").val("");
	$("#add-email-address").val("");
	$("#add-user-status").val("1");
	$("#add-access-level").val("0");
	$("#add-username").val("");
	generateRandomPassword("#add-password");
    });
    
     $(document).on('keyup','#add-firstname',function(){
	var fname = $("#add-firstname").val()!=null?$("#add-firstname").val().toLowerCase():"";
	var lname = $("#add-lastname").val()!=null?$("#add-lastname").val().toLowerCase():"";
	$("#add-username").val(fname.replace(" ",'')+ "." + lname.replace(" ",''));
    });
    
    $(document).on('keyup','#add-lastname',function(){
	var fname = $("#add-firstname").val()!=null?$("#add-firstname").val().toLowerCase():"";
	var lname = $("#add-lastname").val()!=null?$("#add-lastname").val().toLowerCase():"";
	$("#add-username").val(fname.replace(" ",'')+ "." + lname.replace(" ",''));
    });
    
    function processAddUserAccount(f_name,l_name,u_name,pass,c_number,e_address,u_status,pic,a_level){
	var src = $("#add-user-account").attr("action");
	$.post(src,{firstname:f_name,lastname:l_name,username:u_name,password:pass,contact_number:c_number,
	    email_address:e_address,user_status:u_status,picture:pic,access_level:a_level},
	    function(data){
		//alert(data.responseText);
		$("#userAccountMessageModal").find('.modal-body p').html(data);
	},'html');
    }
    
    $(document).on('click','#save-add-user-button',function(){
        //$("#update-user-account").ssubmit();
	//$("#userAccountMessageModal").modal("show");
	if($("#add-lastname").val()==""){
            $("#add-lastname").focus();
            $("#add-user-info-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the lastname correctly.</div>");
            $(document).on('alert','.alert');
        }else if($("#add-firstname").val()==""){
            $("#add-firstname").focus();
            $("#add-user-info-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the firstname correctly.</div>");
            $(document).on('alert','.alert');
        }else if($("#add-username").val()==""
                 || $("#add-username").val().length <6){
            $("#add-username").focus();
            $("#add-user-info-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Username must be at least 6 characters characters.</div>");
            $(document).on('alert','.alert');
        }else if(($("#add-password").val()=="" ||
           $("#add-password").val().length <6 || $("#add-password").val().length >16)
	    && $("#add-user-check-box").prop("checked")){
            $("#add-password").focus();
            $("#add-user-info-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Password must at least 6 characters and 16 characters</div>");
            $(document).on('alert','.alert');
        }else{
        
	    $("#addUserModal").modal("hide");
	    $("#userAccountMessageModal").find('.modal-body p').html("Please wait while saving the changes of this user account...");
	    $("#userAccountMessageModal").modal("show");	
	    
	    processAddUserAccount($("#add-firstname").val(),
				     $("#add-lastname").val(),$("#add-username").val(),$("#add-password").val(),$("#add-contact-number").val(),
				     $("#add-email-address").val(),$("#add-user-status").val(),$("#add-user-picture").val(),
				     $("#add-access-level").val());
	}
	
    });
    
    //Settings
    

    
    
    /*//Testing
    var counter = 1;
    setInterval(function(){
        
        $("#counter-id").html("Counter : " + counter);
	counter++;
        },1500); */
   
    /***** Home ******/
    
    $("#update-pass-button").click(function(){
        $("#passwordModal").modal("toggle");
        $("#profile-message").alert("close");
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
            $("#update-password-frm").submit();
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
    
});
