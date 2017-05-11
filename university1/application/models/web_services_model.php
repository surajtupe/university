<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Web_Services_Model extends CI_Model {

    public function getUserAddressDetails($condition_to_pass) {
        $this->db->select('ua.address_id,ua.zip_code,ua.is_forwarded,c.country_id_fk,s.state_id_fk,ci.city_id_fk,ua.address_line1,ua.address_line2,ua.address_picture,ua.latitude,ua.longitude,c.country_name,s.state_name,ci.city_name,atl.address_type_text,an.address_name');
        $this->db->from('mst_user_addresses as ua');
        $this->db->join('trans_country_lang as c', 'c.country_id_fk = ua.country_id', 'inner');
        $this->db->join('trans_states_lang as s', 's.state_id_fk = ua.state_id', 'inner');
        $this->db->join('trans_cities_lang as ci', 'ci.city_id_fk = ua.city_id', 'inner');
        $this->db->join('mst_user_addresses_name as an', 'an.address_name_id = ua.address_name_id_fk', 'inner');
        $this->db->join('mst_address_type as at', 'at.address_type_id = an.address_type_id_fk', 'inner');
        $this->db->join('trans_address_type_lang as atl', 'atl.address_type_id_fk = at.address_type_id', 'inner');
        $this->db->where($condition_to_pass);
        $this->db->group_by('ua.address_id');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function userAddressByAddressNameId($condition_to_pass) {
        $this->db->select('ua.address_id,ua.zip_code,m_c.flag,c.country_id_fk,s.state_id_fk,ci.city_id_fk,ua.address_line1,ua.address_line2,ua.address_picture,ua.latitude,ua.longitude,c.country_name,s.state_name,ci.city_name,ua.is_current_add_same_as_permanant_add,ua.is_forwarded,ua.current_location_same_as_above_flag');
        $this->db->from('mst_user_addresses as ua');
        $this->db->join('mst_countries as m_c', 'm_c.country_id = ua.country_id', 'inner');
        $this->db->join('trans_country_lang as c', 'c.country_id_fk = ua.country_id', 'inner');
        $this->db->join('trans_states_lang as s', 's.state_id_fk = ua.state_id', 'inner');
        $this->db->join('trans_cities_lang as ci', 'ci.city_id_fk = ua.city_id', 'inner');
        $this->db->where($condition_to_pass);
        $this->db->group_by('ua.address_id');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getContactUserInfo($condition_to_pass) {
        $this->db->select('uc.user_contact_id,uc.contact_name,u.user_id,u.title,u.first_name,u.last_name,u.user_email,u.phone_number,u.user_birth_date,u.profile_picture,uc.is_blocked');
        $this->db->from('mst_user_contacts as uc');
        $this->db->join('mst_users as u', 'u.user_id = uc.contact_user_id_fk', 'inner');
        $this->db->where($condition_to_pass);
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getUserAccessDetails($condition_to_pass) {
        $this->db->select('ua.*,atl.address_type_text,uca.access_to_fk,u.profile_picture,co.country_name,st.state_name,ci.city_name,uan.address_name');
        $this->db->from('mst_user_contacts as uc');
        $this->db->join('mst_users as u', 'u.user_id = uc.contact_user_id_fk', 'inner');
        $this->db->join('mst_user_addresses as ua', 'ua.user_id_fk = uc.contact_user_id_fk', 'inner');
        $this->db->join('mst_user_addresses_name as uan', 'uan.address_name_id = ua.address_name_id_fk', 'inner');
        $this->db->join('mst_address_type as at', 'at.address_type_id = uan.address_type_id_fk', 'inner');
        $this->db->join('trans_address_type_lang as atl', 'atl.address_type_id_fk = at.address_type_id', 'inner');
        $this->db->join('mst_user_contacts_access as uca', 'uca.user_address_id_fk = ua.address_id', 'left');
        $this->db->join('trans_country_lang as co', 'co.country_id_fk = ua.country_id', 'inner');
        $this->db->join('trans_states_lang as st', 'st.state_id_fk = ua.state_id', 'inner');
        $this->db->join('trans_cities_lang as ci', 'ci.city_id_fk = ua.city_id', 'inner');
        $this->db->where($condition_to_pass);
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getAddAccessList($condition_to_pass) {
        $this->db->select('u.user_id,u.first_name,u.last_name,u.user_email,u.profile_picture,uca.contact_access_id,u.phone_number');
        $this->db->from('mst_user_contacts_access as uca');
        $this->db->join('mst_users as u', 'u.user_id = uca.access_to_fk', 'inner');
        $this->db->where($condition_to_pass);
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getUserForwardedAddress($condition_to_pass) {
        $this->db->select('ua.user_address_id_fk,ua.forwarded_address_id,ua.zip_code,mc.flag,c.country_id_fk,s.state_id_fk,ci.city_id_fk,ua.address_line1,ua.address_line2,ua.address_picture,ua.latitude,ua.longitude,c.country_name,s.state_name,ci.city_name,ua.date_from,ua.date_to');
        $this->db->from('mst_user_forwarded_address as ua');
        $this->db->join('mst_countries as mc', 'mc.country_id = ua.country_id', 'inner');
        $this->db->join('trans_country_lang as c', 'c.country_id_fk = ua.country_id', 'inner');
        $this->db->join('trans_states_lang as s', 's.state_id_fk = ua.state_id', 'inner');
        $this->db->join('trans_cities_lang as ci', 'ci.city_id_fk = ua.city_id', 'inner');
        $this->db->where($condition_to_pass);
        $this->db->group_by('ua.forwarded_address_id');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getUserNotification($condition_to_pass) {
        $this->db->select('u.first_name,u.last_name,u.profile_picture,n.*');
        $this->db->from('mst_notifications as n');
        $this->db->join('mst_users as u', 'u.user_id = n.notification_from', 'inner');
        $this->db->where($condition_to_pass);
//        $this->db->group_by('n.notification_from');
        $this->db->order_by('notification_date', 'DESC');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getTempraryAccessList($condition_to_pass) {
        $this->db->select('u.first_name,u.last_name,u.profile_picture,uta.*');
        $this->db->from('mst_user_temprary_access as uta');
        $this->db->join('mst_users as u', 'u.user_id = uta.access_to_user_id_fk', 'left');
//        $this->db->join('mst_user_contacts as uc', 'uc.contact_phone = uta.phone_number', 'left');
        $this->db->where($condition_to_pass);
        $this->db->order_by('u.first_name', 'DESC');
        $this->db->group_by('phone_number');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function deleteRows($arr_delete_condition, $table_name) {
        $this->db->where($arr_delete_condition);
        $query = $this->db->delete($table_name);
    }

    public function getContactBlockUser($user_id) {
        $this->db->select('mu.first_name,mu.last_name,mu.user_id,muc.user_contact_id,muc.contact_name,muc.contact_phone,muc.contact_email,muc.added_date');
        $this->db->from('mst_user_contacts as muc');
        $this->db->join('mst_users as mu', 'mu.user_id = muc.user_id_fk', 'left');
        $this->db->where("muc.is_blocked", '0');
        $this->db->where("muc.user_id_fk", $user_id);
        $this->db->order_by('muc.user_contact_id', 'DESC');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getGroupList($condition) {
        $this->db->select('temp_access_id,end_time,access_to_user_id_fk,access_from_user_id_fk,group_name');
        $this->db->from('mst_user_temprary_access');
        $this->db->where($condition);
        $this->db->group_by('group_name');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getContactName($user_id) {
        $this->db->select('muc.*');
        $this->db->from('mst_user_contacts as muc');
        $this->db->where("muc.contact_user_id_fk", $user_id);
        $this->db->group_by('muc.contact_user_id_fk');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getContactNameID($user_id, $contact_user_id_fk) {
        $this->db->select('muc.*');
        $this->db->from('mst_user_contacts as muc');
        $this->db->where("muc.user_id_fk", $contact_user_id_fk);
        $this->db->where("muc.contact_user_id_fk", $user_id);
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getLoginUserContactInfo($condition_to_pass) {
        $this->db->select('uc.user_contact_id,uc.contact_name,u.first_name,u.last_name');
        $this->db->from('mst_user_contacts as uc');
        $this->db->join('mst_users as u', 'u.user_id = uc.contact_user_id_fk', 'inner');
        $this->db->where($condition_to_pass);
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getNearByUsers($condition_to_pass, $latitude, $longitude) {
        $this->db->select("uc.user_contact_id,uc.contact_name,u.user_id,u.first_name,u.last_name,u.phone_number,u.profile_picture,ua.latitude,ua.longitude,(((acos(sin(($latitude*pi()/180)) *
        sin((ua.latitude*pi()/180))+cos(($latitude*pi()/180)) * cos((ua.latitude*pi()/180))
        * cos((($longitude-ua.longitude)*pi()/180))))*180/pi())*60*1.1515*1.609344) AS
        distance");
        $this->db->from('mst_user_contacts as uc');
        $this->db->join('mst_user_addresses as ua', 'ua.user_id_fk = uc.contact_user_id_fk', 'inner');
        $this->db->join('mst_users as u', 'u.user_id = uc.contact_user_id_fk', 'inner');
        $this->db->where($condition_to_pass);
        $this->db->having('distance <=', '50');
        $this->db->group_by('user_contact_id');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getCurrentNearByUsersModel($condition_to_pass, $latitude, $longitude, $distance) {
        $this->db->select("uc.user_contact_id,uc.contact_name,u.user_id,u.first_name,u.last_name,u.phone_number,u.profile_picture,u.latitude,u.longitude,(((acos(sin(($latitude*pi()/180)) *
        sin((u.latitude*pi()/180))+cos(($latitude*pi()/180)) * cos((u.latitude*pi()/180))
        * cos((($longitude-u.longitude)*pi()/180))))*180/pi())*60*1.1515*1.609344) AS
        distance");
        $this->db->from('mst_user_contacts as uc');
        $this->db->join('mst_users as u', 'u.user_id = uc.contact_user_id_fk', 'inner');
        $this->db->where($condition_to_pass);
        $this->db->having('distance <=', $distance);
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getClosestUserAddressDetails($condition_to_pass, $latitude, $longitude) {
        $this->db->select("ua.address_id,ua.zip_code,ua.is_forwarded,c.country_id_fk,s.state_id_fk,ci.city_id_fk,ua.address_line1,ua.address_line2,ua.address_picture,ua.latitude,ua.longitude,c.country_name,s.state_name,ci.city_name,atl.address_type_text,an.address_name,(((acos(sin(($latitude*pi()/180)) *
        sin((ua.latitude*pi()/180))+cos(($latitude*pi()/180)) * cos((ua.latitude*pi()/180))
        * cos((($longitude-ua.longitude)*pi()/180))))*180/pi())*60*1.1515*1.609344) AS
        distance");
        $this->db->from('mst_user_addresses as ua');
        $this->db->join('trans_country_lang as c', 'c.country_id_fk = ua.country_id', 'inner');
        $this->db->join('trans_states_lang as s', 's.state_id_fk = ua.state_id', 'inner');
        $this->db->join('trans_cities_lang as ci', 'ci.city_id_fk = ua.city_id', 'inner');
        $this->db->join('mst_user_addresses_name as an', 'an.address_name_id = ua.address_name_id_fk', 'inner');
        $this->db->join('mst_address_type as at', 'at.address_type_id = an.address_type_id_fk', 'inner');
        $this->db->join('trans_address_type_lang as atl', 'atl.address_type_id_fk = at.address_type_id', 'inner');
        $this->db->where($condition_to_pass);
        $this->db->order_by('distance', 'ASC');
        $this->db->group_by('ua.address_id');
        $this->db->limit(1);
        $result = $this->db->get();
        return $result->result_array();
    }

      public function getCurrentLocationAddAccessList($condition_to_pass) {
        $this->db->select('u.user_id,u.first_name,u.last_name,u.user_email,u.profile_picture,u.phone_number,nla.*');
        $this->db->from('trans_near_by_location_access as nla');
        $this->db->join('mst_users as u', 'u.user_id = nla.access_to_user_id_fk', 'inner');
        $this->db->where($condition_to_pass);
        $result = $this->db->get();
        return $result->result_array();
    }
}
