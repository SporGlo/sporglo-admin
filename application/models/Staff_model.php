<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Staff_model extends CI_Model
{
    private $staff_user_type_table;
    private $sports_table;

    public function __construct()
    {
        parent::__construct();
        $this->staff_user_type_table = 'staff_user_type';
        $this->sports_table = 'sports';
    }
    function get_staff_by_name($name)
    {
        return $this->db->where('user_type', $name)->get($this->staff_user_type_table)->row_array();
    }

    function get_staff__by_id($id)
    {
        return $this->db->where('id', $id)->get($this->staff_user_type_table)->row_array();
    }

    // Add Supplier
    function add_staff_user_type($data)
    {
        // supplier data
        $position_data = [
            'sport_id' => $data['sport_id'],
            'user_type' => $data['user_type'],
            'is_active' => $data['is_active'],
            'registered_at' => date('Y-m-d H:i:s')
        ];

        if ($this->db->insert($this->staff_user_type_table, $position_data)) {
            $inserted_id = $this->db->insert_id();
            return $this->get_staff__by_id($inserted_id);
        } else {
            return false;
        }
    }

    function update_staff_user_type($id, $data)
    {
        // Prepare sport data
        $position_data = [
            'sport_id' => $data['sport_id'],
            'user_type' => $data['user_type'],
            'is_active' => $data['is_active'],
            'updated_at' => date('Y-m-d H:i:s'),
        ];
    
      
    
        // Update the record in the database
        if ($this->db->where('id', $id)->update($this->staff_user_type_table, $position_data)) {
            // Return the updated sport data
            return $this->get_staff__by_id($id);
        } else {
            return false;
        }
    }
    
    function get_staff($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("su.id,su.sport_id, su.user_type,su.is_active,s.sport_name");
        $this->db->from($this->staff_user_type_table . " su");
        $this->db->join($this->sports_table . " s", "su.sport_id = s.id", "left"); // Adjust the join type if necessary

        $this->db->order_by("su.id", "DESC");

        // Apply filters dynamically from the $filters array
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        // if (!empty($search) && is_array($search)) {
        //     if (isset($search['product'])) {
        //         $this->db->group_start(); // Begin group for OR conditions
        //         $this->db->like('p.PRODUCT_NAME', $search['product'], 'both', false);
        //         $this->db->or_like('p.PRODUCT_CODE', $search['product'], 'both', false);
        //         $this->db->group_end(); // End group for OR conditions
        //     }
        // }


        // Apply limit and offset only if 'list' type and offset is greater than zero
        if ($type == 'list') {
            if ($limit > 0) {
                $this->db->limit($limit, ($offset > 0 ? $offset : 0));
            }
        }

        // Execute query
        $query = $this->db->get();

        if ($type == 'list') {
            return $query->result_array();
        } else {
            return $query->num_rows();
        }
    }


    function delete_staff_by_id($id)
    {
        $this->db->trans_start();

        $this->db->delete($this->staff_user_type_table, array('id' => $id));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }
}
