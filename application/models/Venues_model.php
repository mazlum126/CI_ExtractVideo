<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Venues_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function venuesListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.venuesId, BaseTbl.venues_name, BaseTbl.instagram_user_info, BaseTbl.facebook_user_info, BaseTbl.resident_advisor_id, BaseTbl.ig_location_id');
        $this->db->from('tbl_venues as BaseTbl');
        // $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.venues_name  LIKE '%".$searchText."%'
                            OR  BaseTbl.instagram_user_info  LIKE '%".$searchText."%'
                            OR  BaseTbl.facebook_user_info  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        // $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function venuesListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.venuesId, BaseTbl.venues_name, BaseTbl.instagram_user_info, BaseTbl.facebook_user_info, BaseTbl.resident_advisor_id, BaseTbl.ig_location_id');
        $this->db->from('tbl_venues as BaseTbl');
        // $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        // if(!empty($searchText)) {
        //     $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
        //                     OR  BaseTbl.name  LIKE '%".$searchText."%'
        //                     OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
        //     $this->db->where($likeCriteria);
        // }
        // $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewVenue($venueInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_venues', $venueInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getVenueInfo($venuesId)
    {
        $this->db->select('venuesId, venues_name, instagram_user_info, facebook_user_info, resident_advisor_id, ig_location_id');
        $this->db->from('tbl_venues');
        // $this->db->where('isDeleted', 0);
        $this->db->where('venuesId', $venuesId);
        $query = $this->db->get();
        
        return $query->result();
    }

    function editVenue($venuesInfo, $venuesId)
    {
        $this->db->where('venuesId', $venuesId);
        $this->db->update('tbl_venues', $venuesInfo);
        
        return TRUE;
    }

        /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteVenue($venuesId)
    {
        $this->db->where('venuesId', $venuesId);
        $this->db->delete('tbl_venues');
        
        return $this->db->affected_rows();
    }

}

  