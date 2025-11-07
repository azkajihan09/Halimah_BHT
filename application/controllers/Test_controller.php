<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Reminder_model');
    }

    public function test_update_sipp()
    {
        echo "<h3>Test Update from SIPP</h3>";

        try {
            echo "<p>1. Loading model...</p>";
            flush();

            echo "<p>2. Testing database connections...</p>";

            // Test reminder DB connection
            $reminder_count = $this->db->query("SELECT COUNT(*) as count FROM bht_reminder_system.perkara_reminder")->row();
            echo "<p>Reminder DB records: " . $reminder_count->count . "</p>";

            // Test SIPP DB connection  
            $sipp_count = $this->db->query("SELECT COUNT(*) as count FROM sipp_tebaru4.perkara LIMIT 1")->row();
            echo "<p>SIPP DB accessible: YES</p>";

            echo "<p>3. Attempting simple query...</p>";
            flush();

            // Simple query first
            $simple_query = "SELECT nomor_perkara FROM bht_reminder_system.perkara_reminder LIMIT 5";
            $simple_result = $this->db->query($simple_query)->result();
            echo "<p>Found " . count($simple_result) . " records in reminder DB</p>";

            echo "<p>Test completed - no fatal errors!</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
            echo "<p>File: " . $e->getFile() . "</p>";
            echo "<p>Line: " . $e->getLine() . "</p>";
        }
    }
}
