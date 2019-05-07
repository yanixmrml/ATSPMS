<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
/**
 * Description of studentModel
 *
 * @author YANIX-MRML
 */
class Load_model extends CI_Model{
    //put your code here
    public $semester_str_length = 16; //16 characters long
    public $password_max_length = 16;
    
    public function _construct(){
        parent::_construct();
    }
    
    public function getUserList($name=""){
        if($name==""){                               
            $sql = "SELECT u.user_id, u.lastname, u.firstname, u.username,
                u.email_address, u.status,  u.contact_number, u.access_level, u.last_login, u.date_created
                FROM user u ORDER BY u.lastname,u.firstname";
            $query = $this->db->query($sql); 
            if($query->num_rows() > 0){
                return $query->result_array();
            }
        }else{ 
            $this->db->select(array('user_id','lastname','firstname','username','email_address',
                              'status','contact_number','access_level','last_login','date_created'));
            $this->db->like('firstname',$name);
            $this->db->or_like('lastname',$name);
            $this->db->order_by('lastname ASC, firstname ASC');
            //echo $this->db->get_compiled_select('user');
            $query = $this->db->get('user');
            if($query->num_rows() > 0){
                return $query->result_array();
            }
        }
        return FALSE;
    }
    
    public function getUserAccount($user_id){
        if($user_id!=null && is_numeric($user_id)){                               
            $sql = "SELECT u.*
                FROM user u WHERE u.user_id = ? ORDER BY u.lastname,u.firstname";
            $query = $this->db->query($sql,$user_id); 
            if($query->num_rows() > 0){
                return $query->row_array();
            }
        }
        return FALSE;
    }
    
    public function getSettingsList($effectivity_date=""){
        if($effectivity_date==""){                               
            $sql = "SELECT s.*, CONCAT(u.lastname, ', ', u.firstname) AS user_fullname
                FROM settings s JOIN user u ON s.user_id = u.user_id ORDER BY s.start_effectivity_date DESC";
            $query = $this->db->query($sql); 
            if($query->num_rows() > 0){
                return $query->result_array();
            }
        }else{ 
            $sql = "SELECT s.*, CONCAT(u.lastname, ', ', u.firstname) AS user_fullname
                FROM settings s JOIN user u ON s.user_id = u.user_id WHERE s.start_effectivity_date >= DATE(?)
                ORDER BY s.start_effectivity_date DESC";
            $query = $this->db->query($sql,$effectivity_date); 
            if($query->num_rows() > 0){
                return $query->result_array();
            }
        }
        return FALSE;
    }
    
    public function getSettings($id){
        if(isset($id) && !is_null($id) && is_numeric($id)){
            $sql = "SELECT s.* FROM settings s WHERE s.settings_id = ?";
            $query = $this->db->query($sql,$id);
            if($query->num_rows()>0){
                return $query->row_array();    
            }
        }
        return FALSE;
    }
    
    public function getCurrentSettings(){
        $sql = "SELECT * FROM settings s WHERE s.start_effectivity_date <= CURRENT_DATE
            ORDER BY s.start_effectivity_date DESC LIMIT 1";
        $query = $this->db->query($sql);
        if($query->num_rows()>0){
            return $query->row_array();    
        }
        return FALSE;
    }
    
    public function getSource(){
        $sql = "SELECT s.* FROM source s ORDER BY source_id LIMIT 2";
        $query = $this->db->query($sql);
        if($query->num_rows()>0){
            return $query->result_array();        
        }
        return FALSE;
    }
    
    public function getConnectedLoad(){
        $sql = "SELECT c.*, c.schedule_on AS sched_on, c.schedule_off AS sched_off, CONCAT(u.lastname, ', ', u.firstname) AS user_fullname FROM connected_load c LEFT JOIN user u ON
                c.user_id = u.user_id ORDER BY c.con_load_id";
        $query = $this->db->query($sql);
        if($query->num_rows()>0){
            return $query->result_array();    
        }
        return FALSE;
    }
    
    public function getNotifications(){
        $sql = "SELECT n.*,CONCAT(u.lastname, ', ', u.firstname) AS user_fullname FROM notifications n LEFT JOIN user u ON
                n.user_id = u.user_id ORDER BY n.posted_on DESC LIMIT 10";
        $query = $this->db->query($sql);
        if($query->num_rows()>0){
            return $query->result_array();    
        }
        return FALSE;
    }
    
    public function getPowerInterruptions(){
        $sql = "SELECT n.* FROM notifications n WHERE n.status = 3 ORDER BY n.posted_on DESC LIMIT 10";
        $query = $this->db->query($sql);
        if($query->num_rows()>0){
            return $query->result_array();    
        }
        return FALSE;
    }
    
    public function getCommulativePowerStartMonth(){
        $sql = "SELECT SUM(l.power) AS total_power_so_far FROM load_side l WHERE l.datetime >= DATE_FORMAT(NOW() ,'%Y-%m-01')";
        $query = $this->db->query($sql);
        if($query->num_rows()>0){
            return $query->row_array();    
        }
        return FALSE;
    }
    
    public function getReports($dataType,$from,$to){
        switch($dataType){
            
            case 1: // Power
                $sql = "SELECT l.power, l.var FROM load_side l WHERE l.datetime BETWEEN ? AND ?";
                $data = array($from,$to);
                $query = $this->db->query($sql,$data);
                if($query->num_rows()>0){
                    return $query->result_array();    
                }
                break;
            case 2: //Voltage
                break;
            case 3: //Current
                break;
            case 4: //Frequency
                break;
            case 5: //Power Interruptions
                break;
                
        }
    }
    
    /****************** Update **************************/
    
    public function updateUserAccount($user){
        if(isset($user)&&!empty($user) && $user!=null){
            if(isset($user['is_reset']) && intval($user['is_reset'])>0){
                $sql = "UPDATE user u SET u.firstname = ?, u.lastname = ?, u.username = ?,
                    u.contact_number = ?, u.email_address = ?, u.status = ?, u.access_level = ?, u.picture = ?,u.password = PASSWORD(?)
                    WHERE u.user_id = ?";
                $query = $this->db->query($sql,array($user['firstname'],$user['lastname'],$user['username'],
                                      $user['contact_number'],$user['email_address'],$user['status'],
                                      $user['access_level'],$user['picture'],$user['password'],$user['user_id']));     
            }else{
                $sql = "UPDATE user SET firstname = ?, lastname = ?, username = ?,
                    contact_number = ?, email_address = ?, status = ?, access_level = ?, picture = ?
                    WHERE user_id = ?";
                $query = $this->db->query($sql,array($user['firstname'],$user['lastname'],$user['username'],
                                      $user['contact_number'],$user['email_address'],$user['status'],
                                      $user['access_level'],$user['picture'],$user['user_id']));     
            }
            return TRUE;
        }
        return FALSE;
    }
    
    public function updateSettings($settings){
        if(isset($settings) && !empty($settings) && $settings!=null){    
            $sql = "UPDATE settings SET voltage_max = ?, voltage_min = ?, frequency_critical_max = ?, frequency_critical_min = ?,
                    current_max = ?, current_min = ?, power_max = ?, power_goal = ?, power_factor_goal = ?, battery_level = ?,
                    temperature_level = ?, cost_pkwh = ?, cost_goal = ?, nominal_voltage = ?, nominal_frequency = ?, start_effectivity_date = ?, last_updated_on = NOW(), 
                    user_id = ? WHERE settings_id = ?";
            $query = $this->db->query($sql,$settings);
            return TRUE;
        }
        return FALSE;
    }
    
    public function updateSource($id){
        if(isset($id) && is_numeric($id)){
            $sql1 = "UPDATE source SET is_selected = 0";
            $query1 = $this->db->query($sql1); 
            $sql2 = "UPDATE source SET is_selected = 1 WHERE source_id = ?";
            $query2 = $this->db->query($sql2,intval($id));
            return TRUE;
        }
        return FALSE;
    }
    
    public function updatePowermanagement($powermanagement){
        if(isset($powermanagement)){
            $sql = "UPDATE connected_load c SET c.description = ?, c.priority = ?, c.status = ?, c.schedule_day = ?, c.schedule_on = ?, c.schedule_off = ?,
                c.last_updated = NOW(), c.user_id = ? WHERE c.con_load_id = ?";
            $query = $this->db->query($sql,$powermanagement);
            return TRUE;
        }
        return FALSE;
    }
    
    public function updateAutoLoadShedding($status){
        if(isset($status) && is_numeric($status)){
            $sql = "UPDATE source SET is_auto_load_shedding = ?";
            $query = $this->db->query($sql,intval($status)); 
            return TRUE;
        }
        return FALSE;
    }
    
    /****************** Add **************************/
    
    public function addUserAccount($user){
        if(isset($user)&&!empty($user) && $user!=null){
            $sql = "INSERT INTO user(firstname,lastname,username,contact_number,email_address,status,access_level,picture,password,date_created)
                    VALUES(?,?,?,?,?,?,?,?,PASSWORD(?),NOW())";
            $query = $this->db->query($sql,array($user['firstname'],$user['lastname'],$user['username'],
                $user['contact_number'],$user['email_address'],$user['status'],
                $user['access_level'],$user['picture'],$user['password']));     
            return TRUE;
        }
        return FALSE;
    }
    
    public function addSettings($settings){
        if(isset($settings) && !empty($settings) && $settings!=null){    
            $sql = "INSERT INTO settings(voltage_max,voltage_min,frequency_critical_max,frequency_critical_min,
                    current_max,current_min,power_max,power_goal,power_factor_goal,battery_level,
                    temperature_level,cost_pkwh,cost_goal,nominal_voltage,nominal_frequency,start_effectivity_date,last_updated_on,
                    user_id)
                    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(),?)";
            $query = $this->db->query($sql,$settings);
            return TRUE;
        }
        return FALSE;
    }
    
    /*************************************************/
    
}

/**** Procedures to be added....
 *  AKAN_SEL_OFFR_SCTN
 *  AKAN_SEL_OFFR_SUBJCT
 *
 **/
