<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test_update extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function simple_test()
    {
        echo "<h3>Simple Update Test</h3>";

        try {
            // Test reminder database access
            echo "<p>1. Testing reminder DB...</p>";
            $reminder_db = $this->load->database('reminder_db', TRUE);
            $count = $reminder_db->query("SELECT COUNT(*) as total FROM perkara_reminder WHERE status_reminder != 'SELESAI' LIMIT 10")->row();
            echo "<p>Found {$count->total} non-completed reminders</p>";

            // Get sample nomor perkara
            echo "<p>2. Getting sample data...</p>";
            $sample = $reminder_db->query("SELECT nomor_perkara FROM perkara_reminder WHERE status_reminder != 'SELESAI' LIMIT 3")->result();

            foreach ($sample as $row) {
                echo "<p>Sample: " . $row->nomor_perkara . "</p>";
            }

            // Test SIPP database with simple query
            echo "<p>3. Testing SIPP DB simple query...</p>";
            $sipp_db = $this->load->database('default', TRUE);
            $sipp_test = $sipp_db->query("SELECT COUNT(*) as total FROM perkara LIMIT 1")->row();
            echo "<p>SIPP DB accessible, has records</p>";

            echo "<p>4. Test completed successfully!</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        }
    }
}
