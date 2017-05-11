<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Address_Model extends CI_Model {

    public function getAllAddress($condition_to_pass) {
        $this->db->select('uan.address_type_id_fk,ua.address_name_id_fk,ua.address_id,uan.address_name');
        $this->db->from('mst_user_addresses as ua');
        $this->db->join('mst_user_addresses_name as uan', 'uan.address_name_id = ua.address_name_id_fk');
        $this->db->where($condition_to_pass);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAddressDetails($condition_to_pass) {
        $this->db->select('ua.address_picture,c.country_id_fk,c.country_name,s.state_name,ci.city_name,,s.state_id_fk,ci.city_id_fk,uan.address_type_id_fk,ua.address_name_id_fk,ua.address_id,ua.zip_code,ua.address_line1,ua.address_line2,ua.latitude,ua.longitude,uan.address_name');
        $this->db->from('mst_user_addresses as ua');
        $this->db->join('mst_user_addresses_name as uan', 'uan.address_name_id = ua.address_name_id_fk');
        $this->db->join('mst_countries as mc', 'mc.country_id = ua.country_id', 'inner');
        $this->db->join('trans_country_lang as c', 'c.country_id_fk = ua.country_id', 'inner');
        $this->db->join('trans_states_lang as s', 's.state_id_fk = ua.state_id', 'inner');
        $this->db->join('trans_cities_lang as ci', 'ci.city_id_fk = ua.city_id', 'inner');
        $this->db->where($condition_to_pass);
        $this->db->group_by('ua.address_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getBackendAddressDetails($condition_to_pass) {
        $this->db->select('ua.address_picture,ua.user_id_fk,c.country_id_fk,c.country_name,s.state_name,ci.city_name,,s.state_id_fk,ci.city_id_fk,uan.address_type_id_fk,ua.address_name_id_fk,ua.address_id,ua.zip_code,ua.address_line1,ua.address_line2,ua.latitude,ua.longitude,uan.address_name,la.address_type_text');
        $this->db->from('mst_user_addresses as ua');
        $this->db->join('mst_user_addresses_name as uan', 'uan.address_name_id = ua.address_name_id_fk');
        $this->db->join('trans_address_type_lang as la', 'la.address_type_id_fk = uan.address_type_id_fk');
        $this->db->join('mst_countries as mc', 'mc.country_id = ua.country_id', 'inner');
        $this->db->join('trans_country_lang as c', 'c.country_id_fk = ua.country_id', 'inner');
        $this->db->join('trans_states_lang as s', 's.state_id_fk = ua.state_id', 'inner');
        $this->db->join('trans_cities_lang as ci', 'ci.city_id_fk = ua.city_id', 'inner');
        $this->db->where($condition_to_pass);
        $this->db->group_by('ua.address_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getForwardingAddressDetails($condition_to_pass) {
        $this->db->select('ufa.same_location_flag,ufa.address_picture,ufa.user_address_id_fk,c.country_id_fk,c.country_name,s.state_name,ci.city_name,s.state_id_fk,ci.city_id_fk,ufa.zip_code,ufa.address_line1,ufa.address_line2,ufa.latitude,ufa.longitude,ufa.date_from,ufa.date_to,uan.address_name,uan.address_type_id_fk');
        $this->db->from('mst_user_forwarded_address as ufa');
        $this->db->join('mst_user_addresses as ua', 'ua.address_id = ufa.user_address_id_fk', 'inner');
        $this->db->join('mst_user_addresses_name as uan', 'uan.address_name_id = ua.address_name_id_fk', 'inner');
        $this->db->join('mst_countries as mc', 'mc.country_id = ufa.country_id', 'inner');
        $this->db->join('trans_country_lang as c', 'c.country_id_fk = ufa.country_id', 'inner');
        $this->db->join('trans_states_lang as s', 's.state_id_fk = ufa.state_id', 'inner');
        $this->db->join('trans_cities_lang as ci', 'ci.city_id_fk = ufa.city_id', 'inner');
        $this->db->where($condition_to_pass);
        $this->db->group_by('ufa.forwarded_address_id');
        $query = $this->db->get();
        return $query->result_array();
    }
    

}
