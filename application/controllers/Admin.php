<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
/**
 * Class : Admin (AdminController)
 * Admin class to control to authenticate admin credentials and include admin functions.
 * @author : Samet Aydın / sametay153@gmail.com
 * @version : 1.0
 * @since : 27.02.2018
 */
class Admin extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->model('user_model');
        $this->load->model('venues_model');
        // Datas -> libraries ->BaseController / This function used load user sessions
        $this->datas();
        // isLoggedIn / Login control function /  This function used login control
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            redirect('login');
        }
        
        else
        {
            // isAdmin / Admin role control function / This function used admin role control
            if($this->isAdmin() == TRUE)
            {
                $this->accesslogincontrol();
            }
        }
    }
	
     /**
     * This function is used to load the user list
     */
    function userListing()
    {   
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->user_model->userListingCount($searchText);

			$returns = $this->paginationCompress ( "userListing/", $count, 10 );
            
            $data['userRecords'] = $this->user_model->userListing($searchText, $returns["page"], $returns["segment"]);
            
            $process = 'User List';
            $processFunction = 'Admin/userListing';
            $this->logrecord($process,$processFunction);

            $this->global['pageTitle'] = 'BSEU : User List';
            
            $this->loadViews("user/users", $this->global, $data, NULL);
    }

    /**
     * This function is used to load the add new form
     */
    function addNew()
    {
            $data['roles'] = $this->user_model->getUserRoles();

            $this->global['pageTitle'] = 'BSEU : Add User';

            $this->loadViews("user/addNew", $this->global, $data, NULL);
    }


    /**
     * This function is used to add new user to the system
     */
    function addNewUser()
    {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password','Password','required|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
            $this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNew();
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = $this->security->xss_clean($this->input->post('email'));
                $password = $this->input->post('password');
                $roleId = $this->input->post('role');
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                
                $userInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'roleId'=>$roleId, 'name'=> $name,
                                    'mobile'=>$mobile, 'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                                    
                $result = $this->user_model->addNewUser($userInfo);
                
                if($result > 0)
                {
                    $process = 'Add User';
                    $processFunction = 'Admin/addNewUser';
                    $this->logrecord($process,$processFunction);

                    $this->session->set_flashdata('success', 'User successfully created');
                }
                else
                {
                    $this->session->set_flashdata('error', 'User creation failed');
                }
                
                redirect('userListing');
            }
        }

     /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOld($userId = NULL)
    {
            if($userId == null)
            {
                redirect('userListing');
            }
            
            $data['roles'] = $this->user_model->getUserRoles();
            $data['userInfo'] = $this->user_model->getUserInfo($userId);

            $this->global['pageTitle'] = 'BSEU : Edit User';
            
            $this->loadViews("user/editOld", $this->global, $data, NULL);
    }


    /**
     * This function is used to edit the user informations
     */
    function editUser()
    {
            $this->load->library('form_validation');
            
            $userId = $this->input->post('userId');
            
            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password','Password','matches[cpassword]|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirm Password','matches[password]|max_length[20]');
            $this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->editOld($userId);
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = $this->security->xss_clean($this->input->post('email'));
                $password = $this->input->post('password');
                $roleId = $this->input->post('role');
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                
                $userInfo = array();
                
                if(empty($password))
                {
                    $userInfo = array('email'=>$email, 'roleId'=>$roleId, 'name'=>$name,
                                    'mobile'=>$mobile, 'status'=>0, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                else
                {
                    $userInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'roleId'=>$roleId,
                        'name'=>ucwords($name), 'mobile'=>$mobile,'status'=>0, 'updatedBy'=>$this->vendorId, 
                        'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                
                $result = $this->user_model->editUser($userInfo, $userId);
                
                if($result == true)
                {
                    $process = 'User Update';
                    $processFunction = 'Admin/editUser';
                    $this->logrecord($process,$processFunction);

                    $this->session->set_flashdata('success', 'User successfully updated');
                }
                else
                {
                    $this->session->set_flashdata('error', 'User update failed');
                }
                
                redirect('userListing');
            }
    }

     /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser()
    {
            $userId = $this->input->post('userId');
            $userInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->user_model->deleteUser($userId, $userInfo);
            
            if ($result > 0) {
                 echo(json_encode(array('status'=>TRUE)));

                 $process = 'Deleting a User';
                 $processFunction = 'Admin/deleteUser';
                 $this->logrecord($process,$processFunction);

                }
            else { echo(json_encode(array('status'=>FALSE))); }
    }


    /**
     * This function is used to load the events list
     */
    function djsListing()
    {   
        $data = [];
        $this->loadViews("djs/djs", $this->global, $data, NULL);
    }


    /**
     * This function is used to load the venues list
     */
    function venuesListing()
    {   
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->venues_model->venuesListingCount($searchText);

			$returns = $this->paginationCompress ( "venuesListing/", $count, 10 );
            
            $data['venuesRecords'] = $this->venues_model->venuesListing($searchText, $returns["page"], $returns["segment"]);
            
            $process = 'Venues List';
            $processFunction = 'Admin/venuesListing';
            $this->logrecord($process,$processFunction);

            $this->global['pageTitle'] = 'BSEU : Venues List';
            
            $this->loadViews("venues/venues", $this->global, $data, NULL);
    }

    /**
     * This function is used to load the add new form
     */
    function showAddVenueForm()
    {
            $data['roles'] = $this->user_model->getUserRoles();

            $this->global['pageTitle'] = 'BSEU : Add Venue';

            $this->loadViews("venues/addVenue", $this->global, $data, NULL);
    }


    /**
     * This function is used to add new user to the system
     */
    function addNewVenue()
    {
            $this->load->library('form_validation');
            
            // $this->form_validation->set_rules('venue_name','Full Name','trim|required|max_length[128]');
            // $this->form_validation->set_rules('instagram_user_info','Email','trim|required|valid_email|max_length[128]');
            // $this->form_validation->set_rules('facebook_user_info','Password','required|max_length[20]');
            // $this->form_validation->set_rules('resident_advisor_id','Confirm Password','trim|required|matches[password]|max_length[20]');
            // $this->form_validation->set_rules('ig_location_id','Role','trim|required|numeric');
            
            // if($this->form_validation->run() == FALSE)
            // {
            //     $this->addNew();
            // }
            // else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $venue_name = $this->security->xss_clean($this->input->post('venues_name'));
                $instagram_user_info = $this->input->post('instagram_user_info');
                $facebook_user_info = $this->input->post('facebook_user_info');
                $resident_advisor_id = $this->security->xss_clean($this->input->post('resident_advisor_id'));
                $ig_location_id = $this->input->post('ig_location_id');

                $venueInfo = array('venues_name'=>$venue_name, 'instagram_user_info'=>$instagram_user_info, 'facebook_user_info'=>$facebook_user_info, 'resident_advisor_id'=> $resident_advisor_id,
                                    'ig_location_id'=>$ig_location_id);
                                    
                $result = $this->venues_model->addNewVenue($venueInfo);
                
                if($result > 0)
                {
                    $process = 'Add Venue';
                    $processFunction = 'Admin/addNewVenue';
                    $this->logrecord($process,$processFunction);

                    $this->session->set_flashdata('success', 'Venue successfully created');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Venue creation failed');
                }
                
                redirect('venuesListing');
            }
        }

     /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function showEditVenueForm($venuesId = NULL)
    {
            if($venuesId == null)
            {
                redirect('venuesListing');
            }
            
            // $data['roles'] = $this->venues_model->getUserRoles();
            $data['venuesInfo'] = $this->venues_model->getVenueInfo($venuesId);

            $this->global['pageTitle'] = 'BSEU : Edit Venue';
            
            $this->loadViews("venues/editVenueForm", $this->global, $data, NULL);
    }


    /**
     * This function is used to edit the venue informations
     */
    function editVenue()
    {

            $this->load->library('form_validation');
            
            $venuesId = $this->input->post('venuesId');
    
            // $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            // $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            // $this->form_validation->set_rules('password','Password','matches[cpassword]|max_length[20]');
            // $this->form_validation->set_rules('cpassword','Confirm Password','matches[password]|max_length[20]');
            // $this->form_validation->set_rules('role','Role','trim|required|numeric');
            // $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
            
            // if($this->form_validation->run() == FALSE)
            // {
            //     $this->editOld($userId);
            // }
            // else
            {
                $venues_name = $this->security->xss_clean($this->input->post('venues_name'));
                $instagram_user_info = $this->input->post('instagram_user_info');
                $facebook_user_info = $this->input->post('facebook_user_info');
                $resident_advisor_id = $this->security->xss_clean($this->input->post('resident_advisor_id'));
                $ig_location_id = $this->input->post('ig_location_id');

                $venuesInfo = array('venues_name'=>$venues_name, 'instagram_user_info'=>$instagram_user_info, 'facebook_user_info'=>$facebook_user_info, 'resident_advisor_id'=> $resident_advisor_id,
                'ig_location_id'=>$ig_location_id);
                
                $result = $this->venues_model->editVenue($venuesInfo, $venuesId);
                
                if($result == true)
                {
                    $process = 'Venue Update';
                    $processFunction = 'Admin/editVenue';
                    $this->logrecord($process,$processFunction);

                    $this->session->set_flashdata('success', 'Venue successfully updated');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Venue update failed');
                }
                
                redirect('venuesListing');
            }
    }

     /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteVenue()
    {
            $venuesId = $this->input->post('venuesId');
            // $userInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
        
            $result = $this->venues_model->deleteVenue($venuesId);
            
            if ($result > 0) {
                 echo(json_encode(array('status'=>TRUE)));

                 $process = 'Deleting a Venue';
                 $processFunction = 'Admin/deleteVenue';
                 $this->logrecord($process,$processFunction);

                }
            else { echo(json_encode(array('status'=>FALSE))); }
    }

    /**
     * This function is used to load the events list
     */
    function eventsListing()
    {   
        $data = [];
        $this->loadViews("events/events", $this->global, $data, NULL);
    }


    function getStories()
    {
        
        // $location_id = 'the_location_id_of_the_venue_instagram';
        $location_id = '218435836';
        $session_id = '30561969329%3A1qo3IHMa4WxrQd%3A6';
        $url = 'https://stevesie.com/cloud/api/v1/endpoints/bf081b41-5f87-47c9-8116-d20713ae10b8/executions';
        $params = '{
            "inputs": {
                "location_id": "'.$location_id .'",
                "session_id": "'.$session_id.'"
            },
            "proxy": {
            "type": "custom",
            "url": "lum-customer-domix-zone-zone4:i7axramu62v9@zproxy.lum-superproxy.io:22225"
            },
            "format": "json"
        }';

        $defaults = array(
        CURLOPT_URL => $url,
        CURLOPT_POSTFIELDS => $params,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HTTPHEADER => array('Token: 4ff70330-44d4-433b-b411-550c77900263',
        'Content-Type: application/json')
        );
        $ch = curl_init();
        curl_setopt_array($ch, $defaults);
        $output = curl_exec($ch);     
        $data['instagramStories'] = json_decode($output);
        // $data['instagramStories'] = $output;
        
        $this->loadViews("scrap_stories/stories", $this->global, $data, NULL);
    }

     /**
     * This function used to show log history
     * @param number $userId : This is user id
     */
    function logHistory($userId = NULL)
    {
            $data['dbinfo'] = $this->user_model->gettablemb('tbl_log','cias');
            if(isset($data['dbinfo']->total_size))
            {
                if(($data['dbinfo']->total_size)>1000){
                    $this->backupLogTable();
                }
            }
            $data['userRecords'] = $this->user_model->logHistory($userId);

            $process = 'Log Viewing';
            $processFunction = 'Admin/logHistory';
            $this->logrecord($process,$processFunction);

            $this->global['pageTitle'] = 'BSEU : User Login History';
            
            $this->loadViews("logHistory", $this->global, $data, NULL);
    }

    /**
     * This function used to show specific user log history
     * @param number $userId : This is user id
     */
    function logHistorysingle($userId = NULL)
    {       
            $userId = ($userId == NULL ? $this->session->userdata("userId") : $userId);
            $data["userInfo"] = $this->user_model->getUserInfoById($userId);
            $data['userRecords'] = $this->user_model->logHistory($userId);
            
            $process = 'Single Log Display';
            $processFunction = 'Admin/logHistorysingle';
            $this->logrecord($process,$processFunction);

            $this->global['pageTitle'] = 'BSEU : Kullanıcı Giriş Geçmişi';
            
            $this->loadViews("logHistorysingle", $this->global, $data, NULL);      
    }
    
    /**
     * This function used to backup and delete log table
     */
    function backupLogTable()
    {
        $this->load->dbutil();
        $prefs = array(
            'tables'=>array('tbl_log')
        );
        $backup=$this->dbutil->backup($prefs) ;

        date_default_timezone_set('Europe/Istanbul');
        $date = date('d-m-Y H-i');

        $filename = './backup/'.$date.'.sql.gz';
        $this->load->helper('file');
        write_file($filename,$backup);

        $this->user_model->clearlogtbl();

        if($backup)
        {
            $this->session->set_flashdata('success', 'Backup and Table cleanup successful');
            redirect('log-history');
        }
        else
        {
            $this->session->set_flashdata('error', 'Backup and Table cleanup failed');
            redirect('log-history');
        }
    }

    /**
     * This function used to open the logHistoryBackup page
     */
    function logHistoryBackup()
    {
            $data['dbinfo'] = $this->user_model->gettablemb('tbl_log_backup','cias');
            if(isset($data['dbinfo']->total_size))
            {
            if(($data['dbinfo']->total_size)>1000){
                $this->backupLogTable();
            }
            }
            $data['userRecords'] = $this->user_model->logHistoryBackup();

            $process = 'Backup Log Viewing';
            $processFunction = 'Admin/logHistoryBackup';
            $this->logrecord($process,$processFunction);

            $this->global['pageTitle'] = 'BSEU : User Backup Login History';
            
            $this->loadViews("logHistoryBackup", $this->global, $data, NULL);
    }

    /**
     * This function used to delete backup_log table
     */
    function backupLogTableDelete()
    {
        $backup=$this->user_model->clearlogBackuptbl();

        if($backup)
        {
            $this->session->set_flashdata('success', 'Table cleanup successful');
            redirect('log-history-backup');
        }
        else
        {
            $this->session->set_flashdata('error', 'Table cleanup fails');
            redirect('log-history-backup');
        }
    }

    /**
     * This function used to open the logHistoryUpload page
     */
    function logHistoryUpload()
    {       
            $this->load->helper('directory');
            $map = directory_map('./backup/', FALSE, TRUE);
        
            $data['backups']=$map;

            $process = 'Backup Log Upload';
            $processFunction = 'Admin/logHistoryUpload';
            $this->logrecord($process,$processFunction);

            $this->global['pageTitle'] = 'BSEU : User Log Upload';
            
            $this->loadViews("logHistoryUpload", $this->global, $data, NULL);      
    }

    /**
     * This function used to upload backup for backup_log table
     */
    function logHistoryUploadFile()
    {
        $optioninput = $this->input->post('optionfilebackup');

        if ($optioninput == '0' && $_FILES['filebackup']['name'] != '')
        {
            $config = array(
            'upload_path' => "./uploads/",
            'allowed_types' => "gz|sql|gzip",
            'overwrite' => TRUE,
            'max_size' => "20048000", // Can be set to particular file size , here it is 20 MB(20048 Kb)
            );

            $this->load->library('upload', $config);
            $upload= $this->upload->do_upload('filebackup');
                $data = $this->upload->data();
                $filepath = $data['full_path'];
                $path_parts = pathinfo($filepath);
                $filetype = $path_parts['extension'];
                if ($filetype == 'gz')
                {
                    // Read entire gz file
                    $lines = gzfile($filepath);
                    $lines = str_replace('tbl_log','tbl_log_backup', $lines);
                }
                else
                {
                    // Read in entire file
                    $lines = file($filepath);
                    $lines = str_replace('tbl_log','tbl_log_backup', $lines);
                }
        }

        else if ($optioninput != '0' && $_FILES['filebackup']['name'] == '')
        {
            $filepath = './backup/'.$optioninput;
            $path_parts = pathinfo($filepath);
            $filetype = $path_parts['extension'];
            if ($filetype == 'gz')
            {
                // Read entire gz file
                $lines = gzfile($filepath);
                $lines = str_replace('tbl_log','tbl_log_backup', $lines);
            }
            else
            {
                // Read in entire file
                $lines = file($filepath);
                $lines = str_replace('tbl_log','tbl_log_backup', $lines);
            }
        }
                // Set line to collect lines that wrap
                $templine = '';
                
                // Loop through each line
                foreach ($lines as $line)
                {
                    // Skip it if it's a comment
                    if (substr($line, 0, 2) == '--' || $line == '')
                    continue;
                    // Add this line to the current templine we are creating
                    $templine .= $line;

                    // If it has a semicolon at the end, it's the end of the query so can process this templine
                    if (substr(trim($line), -1, 1) == ';')
                    {
                        // Perform the query
                        $this->db->query($templine);

                        // Reset temp variable to empty
                        $templine = '';
                    }
                }
            if (empty($lines) || !isset($lines))
            {
                $this->session->set_flashdata('error', 'Backup installation failed');
                redirect('log-history-upload');
            }
            else
            {
                $this->session->set_flashdata('success', 'Backup installation is successful');
                redirect('log-history-upload');
            }
    }
}