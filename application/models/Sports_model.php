<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sports_model extends CI_Model
{
    private $sports_table;
    public function __construct()
    {
        parent::__construct();
        $this->sports_table = 'sports';
    }
    function get_sport_by_name($name)
    {
        return $this->db->where('sport_name', $name)->get($this->sports_table)->row_array();
    }

    function get_sport_by_id($id)
    {
        return $this->db->where('id', $id)->get($this->sports_table)->row_array();
    }

    // Add Supplier
    function add_sport($data)
    {
        // supplier data
        $sport_data = [
            'sport_name' => $data['sport_name'],
            'description' => $data['description'],
            'history' => $data['history'],
            'type' => $data['type'],
            'sport_order' => $data['sport_order'],
            'cover_image' => $data['cover_image'],
            'category_image' => $data['category_image'],
            'created_by' => 1,
            'created_at' => date('Y-m-d')
        ];

        if ($this->db->insert($this->sports_table, $sport_data)) {
            $inserted_id = $this->db->insert_id();
            return $this->get_sport_by_id($inserted_id);
        } else {
            return false;
        }
    }

    function update_sport($id, $data)
    {
        // Prepare sport data
        $sport_data = [
            'sport_name' => $data['sport_name'],
            'description' => $data['description'],
            'history' => $data['history'],
            'type' => $data['type'],
            'sport_order' => $data['sport_order'],
            'updated_by' => 1,
            'updated_at' => date('Y-m-d'),
        ];
    
        // Include cover_image only if it's not empty
        if (!empty($data['cover_image'])) {
            $sport_data['cover_image'] = $data['cover_image'];
        }
    
        // Include category_image only if it's not empty
        if (!empty($data['category_image'])) {
            $sport_data['category_image'] = $data['category_image'];
        }
    
        // Update the record in the database
        if ($this->db->where('id', $id)->update($this->sports_table, $sport_data)) {
            // Return the updated sport data
            return $this->get_sport_by_id($id);
        } else {
            return false;
        }
    }
    
    function get_sports($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("s.id, s.sport_name, s.type,s.history, s.description, s.sport_order, s.cover_image, s.category_image");
        $this->db->from($this->sports_table . " s");
        $this->db->order_by("s.id", "DESC");

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


    function delete_sport_by_id($id)
    {
        $this->db->trans_start();

        $this->db->delete($this->sports_table, array('id' => $id));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }
}
