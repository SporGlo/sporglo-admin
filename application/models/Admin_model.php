<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin_model extends CI_Model
{
    protected $admin_table; // Holds the name of the user table
    protected $user_2fa_table; // Holds the name of the user table

    public function __construct()
    {
        parent::__construct();
        $this->admin_table = 'admins'; // Initialize user table
        $this->user_2fa_table = 'users_2fa_details'; // Initialize user 2fa table

    }

    /**
     * Create a new user
     *
     * @param array $data User data
     * @return int Inserted user ID
     */
    public function add_admin($data,$userid)
    {
        $user_data = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'admin_type' => $data['admin_type'],
            'city' => $data['city'],
            'country' => $data['country'],
            'registered_at' => date('Y-m-d H:i:s'),
            'token' => null,  // This will be populated after insert
    
            'password' => password_hash($data['confirm_password'], PASSWORD_ARGON2ID)
        ];

        // Insert new lead
        $inserted = $this->db->insert($this->admin_table, $user_data);
        if ($inserted) {
            $inserted_id = $this->db->insert_id();
            // Create product_code in the required format
            $user_gen_id = "AD-" . str_pad($inserted_id, 6, '0', STR_PAD_LEFT);
            $hashed_token = hash("sha256", $user_gen_id);

            // Update the user_gen_id field for the newly inserted product
            $this->db->where('id', $inserted_id);

            $this->db->update($this->admin_table, ['admin_id' => $user_gen_id,'token' => $hashed_token]);
            return $this->get_admin_by_id($inserted_id);
        } else
            return false;
    }




    function get_admin($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select('u.id,u.first_name, u.last_name, u.email,  u.admin_id,u.admin_type,u.city,u.country');
        $this->db->from($this->admin_table . " u");
        $this->db->order_by("u.id", "DESC");


        // Apply filters dynamically from the $filters array
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $key => $value) {
                $this->db->where($key, $value);
            }
        }

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
    /**
     * Get user by ID
     *
     * @param int $user_id User ID
     * @return array|null User data or null if not found
     */
    // public function get_user_by_id(int $user_id): ?array
    // {
    //     $query = $this->db->get_where($this->user_table, ['id' => $user_id]);
    //     return $query->row_array(); // Return user data or null
    // } 
     function get_admin_by_id($id)
    {
        return $this->db->where('id', $id)->get($this->admin_table)->row_array();
    }

    /**
     * Get user by email
     *
     * @param string $email User email
     * @return array|null User data or null if not found
     */
    public function get_admin_by_email(string $email)
    {
        $query = $this->db->get_where($this->admin_table, ['email' => $email]);
        return $query->row_array(); // Return user data or null
    }

    /**
     * Update user data
     *
     * @param int $user_id User ID
     * @param array $data User data to update
     * @return bool TRUE on success, FALSE on failure
     */
    public function update_admin_details($userID, $data)
    {
        $user_data = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'admin_type' => $data['admin_type'],
            'city' => $data['city'],
            'country' => $data['country'],
        ];

        // Insert new lead
        $updated = $this->db->where('id', $userID)->update($this->admin_table, $user_data);
        if ($updated) {
            return $this->get_admin_by_id($userID);
        } else
            return false;
    }

    /**
     * Delete a user
     *
     * @param int $user_id User ID
     * @return bool TRUE on success, FALSE on failure
     */
    function delete_admin_by_id($id)
    {
        $this->db->trans_start();

        $this->db->delete($this->admin_table, array('id' => $id));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }
    /**
     * Get all users
     *
     * @return array List of users
     */
    public function get_all_users()
    {
        $query = $this->db->get($this->user_table);
        return $query->result_array(); // Return array of user data
    }

    /**
     * Get all users by query
     *
     * @return array List of users
     */
    public function get_all_users_by_query()
    {
        $query = $this->db->query("SELECT * FROM users");
        return $query->result_array(); // Return array of user data
    }

    /**
     * Check if a user exists by user ID
     *
     * @param int $user_id User ID
     * @return bool TRUE if user exists, FALSE otherwise
     */
    public function validate_user(int $user_id)
    {
        $query = $this->db->get_where($this->admin_table, ['id' => $user_id]);
        return $query->row(); // Return true if user exists
    }


    // Function to update user password
    function update_password($password, $userid)
    {
        // Generate the hashed password using ARGON2ID
        $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);
        $this->db->where('id', $userid);
        return $this->db->update($this->user_table, ['password' => $hashedPassword]);
    }



   

    public function update_user_password($userID, $data, $created_by)
    {

        // Insert new lead
        return $this->db->where('id', $userID)->update($this->user_table, ['password' => password_hash($data['confirm_password'], PASSWORD_ARGON2ID)]);
    }
}
