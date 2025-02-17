<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Position_model extends CI_Model
{
    private $position_table;
    private $sports_table;

    public function __construct()
    {
        parent::__construct();
        $this->position_table = 'sport_positions';
        $this->sports_table = 'sports';
    }
    function get_position_by_name($name)
    {
        return $this->db->where('position_name', $name)->get($this->position_table)->row_array();
    }

    function get_position_by_id($id)
    {
        return $this->db->where('id', $id)->get($this->position_table)->row_array();
    }

    // Add Supplier
    function add_position($data)
    {
        // supplier data
        $position_data = [
            'sport_id' => $data['sport_id'],
            'position_name' => $data['position_name'],
            'position_type' => $data['position_type'],
            'created_by' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->db->insert($this->position_table, $position_data)) {
            $inserted_id = $this->db->insert_id();
            return $this->get_position_by_id($inserted_id);
        } else {
            return false;
        }
    }

    function update_position($id, $data)
    {
        // Prepare sport data
        $position_data = [
            'sport_id' => $data['sport_id'],
            'position_name' => $data['position_name'],
            'position_type' => $data['position_type'],
            'updated_by' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
    
      
    
        // Update the record in the database
        if ($this->db->where('id', $id)->update($this->position_table, $position_data)) {
            // Return the updated sport data
            return $this->get_position_by_id($id);
        } else {
            return false;
        }
    }
    
    function get_position($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("p.id,p.sport_id, p.position_name,p.position_type,s.sport_name");
        $this->db->from($this->position_table . " p");
        $this->db->join($this->sports_table . " s", "p.sport_id = s.id", "left"); // Adjust the join type if necessary

        $this->db->order_by("p.id", "DESC");

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


    function delete_position_by_id($id)
    {
        $this->db->trans_start();

        $this->db->delete($this->position_table, array('id' => $id));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }
}
