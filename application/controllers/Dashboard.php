<?php
/*
  
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of employee
 *
 * @author YANIX-MRML
 */
class Dashboard extends CI_Controller{
    //put your code here
    private $template = "dashboard/template.php";
    
    function _construct(){
        parent::_construct();
    }

    /**** Viewing *****/

    function index(){
        $this->home();
    }

    function home(){
        $this->checkUser();
        $data["userInfo"] = $this->session->userdata("account");
        $data["page"] = "Home";
        $data["page_include"] = "dashboard/home.php";
        $data["page_javascript"] = "assets/js/dashboard.js";
        $data["auth"] = $this->tools->mc_encrypt($_SESSION['a_id'],ENCRYPTION_KEY);
        $data["notificationsList"] = $this->load_model->getNotifications();
        $this->load->view($this->template,$data);
    }

    function ats(){
        $this->checkUser();
        $data["userInfo"] = $this->session->userdata("account"); 
        $data["page"] = "Automatic Transfer Switch";
        $data["page_include"] = "dashboard/ats.php"; 
        $data["page_javascript"] = "assets/js/dashboard.js";
        $data["page_specialized_script"] = "assets/js/ats.js";
        $data["current_settings"] = $this->load_model->getCurrentSettings();
        $data["sources"] = $this->load_model->getSource();
        $data["auth"] = $this->tools->mc_encrypt($_SESSION['a_id'],ENCRYPTION_KEY);
        $this->load->view($this->template,$data);
    }
    
    function powermanagement(){
        $this->checkUser();
        $data["userInfo"] = $this->session->userdata("account"); 
        $data["page"] = "Power Management";
        $data["page_include"] = "dashboard/powermanagement.php"; 
        $data["page_javascript"] = "assets/js/dashboard.js";
        $data["page_specialized_script"] = "assets/js/powermanagement.js";
        $data["sources"] = $this->load_model->getSource();
        $connected_loads = $this->load_model->getConnectedLoad();
        if($connected_loads != null && !empty($connected_loads)){
            for($i=0;$i<count($connected_loads);$i++){
                $connected_loads[$i]["schedule_on"] = $this->tools->formatDateTime($connected_loads[$i]["schedule_on"],"g:i:s A");                    
                $connected_loads[$i]["schedule_off"] = $this->tools->formatDateTime($connected_loads[$i]["schedule_off"],"g:i:s A");  
                $connected_loads[$i]["schedule_day"] = $connected_loads[$i]["schedule_day"]==""?"N/A": $connected_loads[$i]["schedule_day"];
                $connected_loads[$i]["last_updated"] = $this->tools->formatDateTime($connected_loads[$i]["last_updated"],"F j, Y");
                $connected_loads[$i]["user_fullname"] = ($connected_loads[$i]["user_fullname"]==null ||
                    intval($connected_loads[$i]["user_id"])==0?"SYSTEM":$connected_loads[$i]["user_fullname"]);
                $connected_loads[$i]["sched_on"] = $this->tools->formatDateTime($connected_loads[$i]["sched_on"],"g:i:s A");                    
                $connected_loads[$i]["sched_off"] = $this->tools->formatDateTime($connected_loads[$i]["sched_off"],"g:i:s A");  
            }
        }        
        $data["connected_load"] = $connected_loads;
        $data["auth"] = $this->tools->mc_encrypt($_SESSION['a_id'],ENCRYPTION_KEY);
        $this->load->view($this->template,$data);
    }
    
    function reports(){
        $this->checkUser();
        $data["userInfo"] = $this->session->userdata("account"); 
        $data["page"] = "Reports";
        $data["currentSettings"] = $this->load_model->getCurrentSettings();
        $data["page_include"] = "dashboard/reports.php";
        $data["page_javascript"] = "assets/js/dashboard.js";
        $data["page_specialized_script"] = "assets/js/reports.js";
        $this->load->view($this->template,$data);
    }

    function users(){
        $this->checkUser();
        $data["userInfo"] = $this->session->userdata("account"); 
        $data["page"] = "User Accounts";
        $data["page_include"] = "dashboard/users.php";
        $data["page_javascript"] = "assets/js/dashboard.js";
        $data["auth"] = $this->tools->mc_encrypt($_SESSION['a_id'],ENCRYPTION_KEY);
        $this->load->view($this->template,$data);
    }
    
    function settings(){
        $this->checkUser();
        $data["userInfo"] = $this->session->userdata("account"); 
        $data["page"] = "Settings";
        $data["page_include"] = "dashboard/settings.php";
        $data["page_javascript"] = "assets/js/dashboard.js";
        $data["auth"] = $this->tools->mc_encrypt($_SESSION['a_id'],ENCRYPTION_KEY);
        $this->load->view($this->template,$data);
    }
    
    function error_404(){
        $this->checkUser();
        $data["userInfo"] = $this->session->userdata("account"); 
        $data["page"] = "Home";
        $data["heading"] = "Page Not Found";
        $data["message"] = "This page is either removed or not existed";
        $data["page_include"] = "errors/cli/error_404.php";
        $data["page_javascript"] = "assets/js/dashboard.js";
        $data["auth"] = $this->tools->mc_encrypt($_SESSION['a_id'],ENCRYPTION_KEY);
        $this->load->view($this->template,$data); 
    }
    
    /********* JSON / AJAX **********/

    function update_password(){
        if($_POST && $this->input->is_ajax_request()){
            $this->checkUser();
            $user_id = intval($this->input->post("user_id"));
            $old_password = $this->input->post("old_password");
            $new_password = $this->input->post("new_password");
            $status = $this->system_model->changePassword($user_id,$old_password,$new_password);
            if($status!=null && !empty($status)){
                if(intval($status["@out_status"])>0){
                    echo "You have successfully updated your password.";
                }else{
                    echo "Authentication failed due to invalid old password. New password was <b>NOT</b> successfully updated.";
                }
            }else{
               echo "Problem occured. New password was <b>NOT</b> successfully updated.";
            }   
        }
    }

    function get_users_list(){
        if($_POST && $this->input->is_ajax_request()){
            $this->checkUser();
            $name = $this->input->post("name");
            $id = $this->input->post("user_id");
            if(isset($id) && $id!=null && is_numeric($id)){
                $user = $this->load_model->getUserAccount($id);
                if(!empty($user)){
                    echo json_encode($user);    
                }else{
                    echo json_encode(array("message"=>"Record of selected user account cannot be retrieved. Kindly contact the developers."));
                }
            }else{
                $usersList = $this->load_model->getUserList($name);
                if(!empty($usersList)){
                    foreach($usersList as $user){
                        //print_r($user);
                        if(intval($user["status"])>0){
                            echo   ("<tr>" .
                                   "<td class='text-center'>" . $user["user_id"] . "</td>" .
                                   "<td>" . $user["lastname"] . ", " . $user["firstname"] . "</td>" .
                                   "<td>" . $user["username"]  . "</td>" .
                                   "<td>" . $user["contact_number"] . "</td>" .
                                   "<td>" . $user["email_address"] . "</td>" .
                                   "<td>Active</td>" .
                                   "<td>" . (intval($user["access_level"])==1?"Administrator":"Registered") . "</td>" .
                                   "<td>" . $this->tools->formatDateTime($user["last_login"],"D, F j, Y") . "</td>" .
                                   "<td>" . $this->tools->formatDateTime($user["date_created"],"D, F j, Y") . "</td>" .
                                   '<td><input type="button" class="btn btn-primary update-user-button" id="' .  $user["user_id"] . '" name="user_update" value="Update"></td>' .  
                                   "</tr>");
                        }else{
                            echo   ("<tr class='table-danger'>" .
                                   "<td class='text-center'>" . $user["user_id"] . "</td>" .
                                   "<td>" . $user["lastname"] . ", " . $user["firstname"] . "</td>" .
                                   "<td>" . $user["username"]  . "</td>" .
                                   "<td>" . $user["contact_number"] . "</td>" .
                                   "<td>" . $user["email_address"] . "</td>" .
                                   "<td>Deactivated</td>" .
                                   "<td>" . (intval($user["access_level"])==1?"Administrator":"Registered") . "</td>" .
                                   "<td>" . $this->tools->formatDateTime($user["last_login"],"D, F j, Y") . "</td>" .
                                   "<td>" . $this->tools->formatDateTime($user["date_created"],"D, F j, Y") . "</td>" .
                                   '<td><input type="button" class="btn btn-primary update-user-button" id="' .  $user["user_id"] . '" name="user_update" value="Update"></td>' .  
                                   "</tr>");
                        }
                    }
                }
            }
        }
    }

    function update_user(){
        if($_POST && $this->input->is_ajax_request()){
            $this->checkUser();
            
            $config['upload_path']          = './uploads/';
            $config['allowed_types'] = 'png|gif|jpg|jpeg';
            $config['max_size']    = '7000';
            //$config['allowed_types']        = 'gif|jpg|png';
            //$config['max_size']             = 100;
            //$config['max_width']            = 200;
            //$config['max_height']           = 200;

            $this->load->library('upload', $config);
            $image = null;
            
            if ( ! $this->upload->do_upload('updateUserImage'))
            {
                    $error = array('error' => $this->upload->display_errors());
                    echo "Error Occured. Image was <b>NOT</b> successfully uploaded.<br/>";
                    //$this->load->view('upload_form', $error);
                    echo $this->upload->display_errors();
            }
            else
            {
                    $data = $this->upload->data();
                    if($this->input->post("previousPicture")!=""){
                        $file = fopen("uploads/" . $this->input->post("previousPicture"),"w");
                        fclose($file);
                        unlink("uploads/" . $this->input->post("previousPicture"));
                        //$this->load->view('upload_success', $data);
                    }
                    $image = $data["file_name"];
            }
            
            $user = array(  "user_id"=>   $this->input->post("user_id"),
                            "firstname"=> $this->input->post("firstname"),
                            "lastname"=>  $this->input->post("lastname"),
                            "username"=>  $this->input->post("username"),
                            "password"=>  $this->input->post("password"),
                            "contact_number"=> $this->input->post("contact_number"),
                            "email_address"=> $this->input->post("email_address"),
                            "status"=> $this->input->post("user_status"),
                            "picture"=>$image,
                            "access_level"=> $this->input->post("access_level"),
                            "is_reset"=> $this->input->post("is_reset"));     
         
            if(isset($user["user_id"])&&$user["user_id"]!=null){
                if($this->load_model->updateUserAccount($user)){
                    echo "User account profile of <b>" . $user['lastname'] . ", " . $user['firstname'] . "</b> was successfully updated.";
                    $u_id = $this->session->userdata("a_id");
                    if($user["user_id"]==$u_id){
                        $data = $this->load_model->getUserAccount($u_id);
                        $this->setUserSession($data);
                    }
                }else{
                    echo "Error Occured. User account was <b>NOT</b> successfully updated.";
                }
            }else{
                echo "Error Occured. User account was <b>NOT</b> successfully updated.";
            }
            
        }
    }
    
    function add_user(){
        if($_POST && $this->input->is_ajax_request()){
            $this->checkUser();
            
            $config['upload_path']          = './uploads/';
            $config['allowed_types'] = 'png|gif|jpg|jpeg';
            $config['max_size']    = '7000';
            //$config['allowed_types']        = 'gif|jpg|png';
            //$config['max_size']             = 100;
            //$config['max_width']            = 200;
            //$config['max_height']           = 200;

            $this->load->library('upload', $config);
            $image = null;
            
            if ( ! $this->upload->do_upload('addUserImage'))
            {
                    $error = array('error' => $this->upload->display_errors());
                    echo "Error Occured. Image was <b>NOT</b> successfully uploaded.<br/>";
                    //$this->load->view('upload_form', $error);
                    echo $this->upload->display_errors();
            }
            else
            {
                    $data = $this->upload->data();
                    $image = $data["file_name"];
            }
             
            $user = array(  "firstname"=> $this->input->post("firstname"),
                            "lastname"=>  $this->input->post("lastname"),
                            "username"=>  $this->input->post("username"),
                            "password"=>  $this->input->post("password"),
                            "contact_number"=> $this->input->post("contact_number"),
                            "email_address"=> $this->input->post("email_address"),
                            "status"=> $this->input->post("user_status"),
                            "picture"=> $image,
                            "access_level"=> $this->input->post("access_level"));   
            if(isset($user["firstname"])&& $user["firstname"]!=null
               && isset($user["lastname"])&& $user["lastname"]!=null){
                if($this->load_model->addUserAccount($user)){
                    echo "<b>" . $user['lastname'] . ", " . $user['firstname'] . "</b> was added to the list of user accounts.";
                }else{
                    echo "Error Occured. User account was <b>NOT</b> successfully added.";
                }
            }else{
                echo "Error Occured. User account was <b>NOT</b> successfully added.";
            }
        }
    }
    
    function get_settings_list(){
        if($_POST && $this->input->is_ajax_request()){
            $this->checkUser();
            $effectivity_date = $this->input->post("effectivity_date");
            $id = $this->input->post("settings_id");
            if(isset($id) && $id!=null && is_numeric($id)){
                $settings = $this->load_model->getSettings($id);
                if(!empty($settings)){
                    $date = $this->tools->formatDateTime($settings["start_effectivity_date"],'m/d/Y');
                    $settings["start_effectivity_date"] = $date;
                    echo json_encode($settings);    
                }else{
                    echo json_encode(array("message"=>"Record of selected settings configuration cannot be retrieved. Kindly contact the developers."));
                }
            }else{
                $date = "";
                if($effectivity_date!=null & $effectivity_date!=""){
                    $date = $this->tools->formatDateTime($effectivity_date,'Y-m-d');
                }
                
                $settingsList = $this->load_model->getSettingsList($date);
                if(!empty($settingsList)){
                    foreach($settingsList as $settings){
                        echo  ("<tr class='text-center'>" .
                               "<td>" . $settings["settings_id"] . "</td>" .
                               "<td>" . $settings["nominal_voltage"] .  "</td>" .
                               "<td>" . $settings["nominal_frequency"]  . "</td>" .
                               "<td>" . $settings["cost_pkwh"] . "</td>" .
                               "<td>" . $settings["cost_goal"] . "</td>" .
                               "<td>" . $settings["power_goal"] . "</td>" .
                               "<td>" . $this->tools->formatDate($settings["start_effectivity_date"],"D, F j, Y")  . "</td>" .
                               "<td>" . $this->tools->formatDate($settings["last_updated_on"],"D, F j, Y") . "</td>" .
                               "<td>" . $settings["user_fullname"] . "</td>" .
                               '<td><input type="button" class="btn btn-primary update-settings-button" id="' .  $settings["settings_id"] . '" name="setting_update" value="Update"></td>' .  
                               "</tr>");
                    }
                }
            }
        }    
    }
    
    function add_settings(){
        if($_POST && $this->input->is_ajax_request()){
            $this->checkUser();
            $effectivity_date = $this->input->post("effectivity_date");
            $date = "";
            
            if($effectivity_date!=null & $effectivity_date!=""){
                $date = $this->tools->formatDateTime($effectivity_date,'Y-m-d');
            }
            
            $settings = array(floatval($this->input->post("max_voltage")),floatval($this->input->post("min_voltage")),
                              floatval($this->input->post("max_frequency")),floatval($this->input->post("min_frequency")),
                              floatval($this->input->post("max_current")),floatval($this->input->post("min_current")),
                              floatval($this->input->post("max_power")),
                              floatval($this->input->post("power_goal")),floatval($this->input->post("power_factor_goal")),
                              floatval($this->input->post("battery_level")),floatval($this->input->post("temperature_level")),
                              floatval($this->input->post("cost_pkwh")),floatval($this->input->post("cost_goal")),
                              floatval($this->input->post("nominal_voltage")),floatval($this->input->post("nominal_frequency")),
                              $date,intval($this->input->post("user_id")));
            if($this->load_model->addSettings($settings)){
                echo "<b>New settings configuration was added to the record.";
            }else{
                echo "Error Occured. New settings configuration was <b>NOT</b> successfully added.";
            }
        }
    }
    
    function update_settings(){
        if($_POST && $this->input->is_ajax_request()){
            $this->checkUser();
            $effectivity_date = $this->input->post("effectivity_date");
            $date = "";
            
            if($effectivity_date!=null & $effectivity_date!=""){
                $date = $this->tools->formatDateTime($effectivity_date,'Y-m-d');
            }
            
            $settings = array(floatval($this->input->post("max_voltage")),floatval($this->input->post("min_voltage")),
                              floatval($this->input->post("max_frequency")),floatval($this->input->post("min_frequency")),
                              floatval($this->input->post("max_current")),floatval($this->input->post("min_current")),
                              floatval($this->input->post("max_power")),
                              floatval($this->input->post("power_goal")),floatval($this->input->post("power_factor_goal")),
                              floatval($this->input->post("battery_level")),floatval($this->input->post("temperature_level")),
                              floatval($this->input->post("cost_pkwh")),floatval($this->input->post("cost_goal")),
                              floatval($this->input->post("nominal_voltage")),floatval($this->input->post("nominal_frequency")),
                              $date,intval($this->input->post("user_id")),intval($this->input->post("settings_id")));
            if($this->load_model->updateSettings($settings)){
                echo "<b>Selected settings configuration was successfully updated.";
            }else{
                echo "Error Occured. Selected settings configuration was <b>NOT</b> successfully updated.";
            }
        }
    }
    
    function getATSData(){
        if($this->input->is_ajax_request()){
            $this->checkUser();
            $source = $this->load_model->getSource();
            $primary = null;
            $secondary = null;
            if(!empty($source)){
                $primary = $source[0];
                $secondary = $source[1];
            }
            echo json_encode(array("primary"=>$primary,"secondary"=>$secondary));
        }
    }
    
    function update_ATS(){
        if($_POST && $this->input->is_ajax_request()){
            $this->checkUser();
            $source_id = $this->input->post("source_id");
            if($this->load_model->updateSource($source_id)){
                echo "<b>" . ($source_id==1?"MAIN SUPPLY":"SECONDARY SUPPLY") . "</b> was successfully chosen as source type.";    
            }else{
                echo "Problem occured. Changes of ATS source type was not successfully saved. Please contact the developers.";
            }
        }
    }
    
    function update_powermanagement(){
        if($_POST && $this->input->is_ajax_request()){
            $this->checkUser();
            $load_id = intval($this->input->post("con_load_id"));
            $description = trim($this->input->post("description"));
            $priority = $this->input->post("priority");
            $status = intval($this->input->post("status"));
            $sched_day = trim($this->input->post("schedule_day"));
            if($this->input->post("schedule_on")!="" || $this->input->post("schedule_on")!=null){
                $sched_on = $this->tools->formatDateTime($this->input->post("schedule_on"),"H:i:s");
            }else{
                $sched_on = null;
            }
            if($this->input->post("schedule_off")!="" || $this->input->post("schedule_off")!=null){
                $sched_off = $this->tools->formatDateTime($this->input->post("schedule_off"),"H:i:s");
            }else{
                $sched_off = null;
            }
            $user_id = intval($this->input->post("user_id"));
            
            if($this->load_model->updatePowermanagement(array($description,$priority,$status,
                                                              $sched_day, $sched_on,$sched_off,$user_id,$load_id))){
                echo "Load " . $load_id . " was successfully updated.";
            }else{
                echo "Problem Occured. Load " . $load_id . " was <b>NOT</b> successfully updated.";
            }
        }
    }
    
    function get_connected_loads(){
        if($this->input->is_ajax_request()){
            $this->checkUser();
            $connected_loads = $this->load_model->getConnectedLoad();
            if($connected_loads != null && !empty($connected_loads)){
                for($i=0;$i<count($connected_loads);$i++){
                    $connected_loads[$i]["schedule_on"] = $this->tools->formatDateTime($connected_loads[$i]["schedule_on"],"g:i:s A");                    
                    $connected_loads[$i]["schedule_off"] = $this->tools->formatDateTime($connected_loads[$i]["schedule_off"],"g:i:s A");  
                    $connected_loads[$i]["schedule_day"] = $connected_loads[$i]["schedule_day"]==""?"N/A": $connected_loads[$i]["schedule_day"];
                    $connected_loads[$i]["last_updated"] = $this->tools->formatDateTime($connected_loads[$i]["last_updated"],"F j, Y");
                    $connected_loads[$i]["user_fullname"] = ($connected_loads[$i]["user_fullname"]==null ||
                        intval($connected_loads[$i]["user_id"])==0?"SYSTEM":$connected_loads[$i]["user_fullname"]);
                    $connected_loads[$i]["sched_day"] = $connected_loads[$i]["schedule_day"]==""?"N/A": $connected_loads[$i]["schedule_day"];
                    $connected_loads[$i]["sched_on"] = $this->tools->formatDateTime($connected_loads[$i]["sched_on"],"g:i:s A");                    
                    $connected_loads[$i]["sched_off"] = $this->tools->formatDateTime($connected_loads[$i]["sched_off"],"g:i:s A");  
                    
                }
            }
            echo json_encode($connected_loads);
        }
    }
    
    function get_selected_source(){
        if($this->input->is_ajax_request()){
            $this->checkUser();
            $source = $this->load_model->getSource();
            
            if(!empty($source)){
                if(intval($source[0]["is_selected"])==1){
                   echo json_encode($source[0]);
                }else if(intval($source[1]["is_selected"])==1){
                   echo json_encode($source[1]);
                }
            }
        }
    }
    
    function update_load_shedding(){
        if($_POST && $this->input->is_ajax_request()){
            $this->checkUser();
            $status = $this->input->post("status");
            if($status != null && is_numeric($status)){
                $this->load_model->updateAutoLoadShedding($status);
                echo "Configuration for auto load shedding was successfully updated.";
            }else{                
                echo "Configuration for auto load shedding was <b>NOT</b> successfully updated.";
            }
        }else{
            echo "Configuration for auto load shedding was <b>NOT</b> successfully updated.";
        }
    }
    
    function get_notifications(){
        if($this->input->is_ajax_request()){
            $this->checkUser();
            $notifications = $this->load_model->getNotifications();
            echo json_encode($notifications);
        }
    }
    
    function get_total_power_start_month(){
        if($this->input->is_ajax_request()){
            $this->checkUser();
            $power = $this->load_model->getCommulativePowerStartMonth();
            echo json_encode($power);
        }
    }
    
    function get_total_cost_start_month(){
        if($_POST &&  $this->input->is_ajax_request()){
            $cost_pkwh = $this->input->post("c_pkwh");
            $total_cost = 0;
            $this->checkUser();
            $total_power = $this->load_model->getCommulativePowerStartMonth();
            //$current_date
            $total_hours = 24;
            $interval_time = 10; //30 mins
            if(!empty($total_power)){
                $total_energy = ($interval_time/60.0) * $total_power["total_power_so_far"];
                $total_cost = $total_energy * $cost_pkwh;
            }
            echo json_encode(array("total_cost"=>$total_cost));
        }
    }
    
    function get_recent_power_interruptions(){
        if($this->input->is_ajax_request()){
            $interruptions = $this->load_model->getPowerInterruptions();
            if(!empty($interruptions)){
                echo "<ul>";
                foreach($interruptions as $interrupt){
                    echo "<li class='alert'>" . $interrupt["description"] . "</li>";
                }
                echo "</ul>";
            }else{
                echo "<div class='alert text-center'>No power interruptions has been recorded recently</div>";
            }
        }
    }
    
    function get_historical_reports(){
        
        $from = '2012-12-01 01:20:30';
        $to = '2019-09-01 01:30:30';
        $dataType = 1;
        $data = $this->load_model->getReports($dataType,$from,$to);
        echo json_encode($data);
    }
    
    /************** Security / Login ******************/

    function logout(){
        $this->system_model->logout();
        //javascript remove history
        redirect("login");
    }
    
    private function checkUser(){
        if(!$this->settings->offline){    
            if(!$this->system_model->hasLoggedIn()){
                redirect("login");
            }    
        }else{
            $this->logout();
        }
    }
    
    function setUserSession($dashboardAccount){
        $this->system_model->setSession('account', array("user_id"=>$dashboardAccount['user_id'],"lastname"=>$dashboardAccount['lastname'],
                                            "firstname"=>$dashboardAccount['firstname'],
                                            "username"=>$dashboardAccount['username'],"email_address"=>$dashboardAccount['email_address'],"access_level"=>$dashboardAccount['access_level'],
                                            "contact_number"=>$dashboardAccount['contact_number'],"status"=>$dashboardAccount['status'],"date_created"=>$dashboardAccount['date_created'],
                                            "last_login"=>$dashboardAccount['last_login'],"picture"=>$dashboardAccount['picture']));
    }
    
    /********* Testing **********/
    
    public function testing(){
        //if($_POST && $this->input->is_ajax_request()){
        //    $this->checkUser();
        $source_id = 2; //$this->input->post("source_id");
        if($this->load_model->updateSource($source_id)){
            echo "<b>" . ($source_id==1?"MAIN SUPPLY":"SECONDARY SUPPLY") . "</b> was successfully chosen as source type.";    
        }else{
            echo "Problem occured. Changes of ATS source type was not successfully saved. Please contact the developers.";
        }
    }
    
    /********************** Model *********************/
    
}

