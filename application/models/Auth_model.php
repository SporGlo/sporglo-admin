<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    protected $user_table; // Holds the name of the user table
    protected $token_table; // Holds the name of the token table

    public function __construct()
    {
        parent::__construct();
        $this->user_table = 'admins'; // Initialize user table
        $this->token_table = 'authtokens'; // Initialize token table
    }

    /**
     * Verify user credentials for login
     *
     * @param string $email User email
     * @param string $password User password
     * @return array|null User data or null if credentials are invalid
     */
    public function verify_user(string $email, string $password)
    {
        $user = $this->get_user_by_email($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user; // Return user data if password matches
        }
        return null; // Return null if credentials are invalid
    }

    /**
     * Get user by email
     *
     * @param string $email User email
     * @return array|null User data or null if not found
     */
    public function get_user_by_email(string $email)
    {
        $query = $this->db->get_where($this->user_table, ['email' => $email]);
        return $query->row_array(); // Return user data or null
    }

    /**
     * Create a new token for the user
     *
     * @param int $user_id User ID
     * @param string $token Token value
     * @param string $token_type Type of the token (e.g., access, refresh)
     * @return int Inserted token ID
     */
    public function create_token(int $user_id, string $token, string $token_type, string $expiry)
    {
        $data = [
            'user_id' => $user_id,
            'token' => $token,
            'token_type' => $token_type,
            'expiry' => $expiry,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert($this->token_table, $data);
        return $this->db->insert_id(); // Return the ID of the inserted token
    }

    /**
     * Get a token by user ID and token type
     *
     * @param int $user_id User ID
     * @param string $token_type Type of the token
     * @return array|null Token data or null if not found
     */
    public function get_token_by_user_id(int $user_id, string $token_type)
    {
        $query = $this->db->get_where($this->token_table, [
            'user_id' => $user_id,
            'token_type' => $token_type
        ]);
        return $query->row_array(); // Return token data or null
    }

    /**
     * Validate a token
     *
     * @param string $token Token value
     * @return bool TRUE if valid, FALSE otherwise
     */
    public function validate_token(string $token)
    {
        $query = $this->db->get_where($this->token_table, ['token' => $token]);
        return $query->row_array(); // Return true if token exists
    }

    /**
     * Delete a token by user ID and token type
     *
     * @param int $user_id User ID
     * @param string $token_type Type of the token
     * @return bool TRUE on success, FALSE on failure
     */
    public function delete_token(int $user_id, string $token_type)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('token_type', $token_type);
        return $this->db->delete($this->token_table);
    }
}
