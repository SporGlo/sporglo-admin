<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Welcome extends App_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{

		// Query the database to get the counts
		$query = $this->db->query("
				SELECT 'admin' AS type, COUNT(*) AS count FROM admins
				UNION ALL
				SELECT 'player' AS type, COUNT(*) AS count FROM players
				UNION ALL
				SELECT 'club' AS type, COUNT(*) AS count FROM clubs
				UNION ALL
				SELECT 'staff' AS type, COUNT(*) AS count FROM staff_users
			");

		$results = $query->result_array();

		// Prepare the counts array
		$counts = [
			'admin' => 0,
			'player' => 0,
			'club' => 0,
			'staff' => 0,
		];

		foreach ($results as $row) {
			$counts[$row['type']] = $row['count'];
		}

		// Pass the counts and other data to the view
		$data['counts'] = $counts;
		$data['view_path'] = 'pages/home';
		$data['page_title'] = "Dashboard- Sporglo Admin Panel";

		$this->load->view('layout', $data);
	}


	// Not Found Rote
	function not_found()
	{
		$data['view_path'] = 'pages/not-found';
		$data['page_title'] = "Page Not Found - Sporglo Admin Panel";
		$this->load->view('layout', $data);
	}
}
