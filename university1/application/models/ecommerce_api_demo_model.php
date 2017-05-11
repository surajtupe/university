<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ecommerce_Api_Demo_Model extends CI_Model {

    public function getAddressDetails($condition_to_pass) {
        $this->db->select('u2.first_name,u2.last_name,ua.user_id_fk,ua.address_id,ua.zip_code,m_c.flag,c.country_id_fk,s.state_id_fk,ci.city_id_fk,ua.address_line1,ua.address_line2,ua.address_picture,ua.latitude,ua.longitude,c.country_name,s.state_name,ci.city_name,ua.is_current_add_same_as_permanant_add,ua.is_forwarded');
        $this->db->from('mst_user_addresses_name as uan');
        $this->db->join('mst_user_addresses as ua', 'ua.address_name_id_fk=uan.address_name_id', 'inner');
        $this->db->join('mst_user_contacts_access as uca', 'uca.user_address_id_fk=ua.address_id', 'inner');
        $this->db->join('mst_users as u', 'u.user_id=uca.access_to_fk', 'inner');
        $this->db->join('mst_users as u2', 'u2.user_id=uca.user_id_fk', 'inner');
        $this->db->join('mst_countries as m_c', 'm_c.country_id = ua.country_id', 'inner');
        $this->db->join('trans_country_lang as c', 'c.country_id_fk = ua.country_id', 'inner');
        $this->db->join('trans_states_lang as s', 's.state_id_fk = ua.state_id', 'inner');
        $this->db->join('trans_cities_lang as ci', 'ci.city_id_fk = ua.city_id', 'inner');
        $this->db->where($condition_to_pass);
        $this->db->group_by('ua.address_id');
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

}
