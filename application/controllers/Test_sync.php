<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test_sync extends CI_Controller
{

    public function index()
    {
        echo "<h2>Test Sync Simple</h2>";

        try {
            // Load model
            $this->load->model('Reminder_model');

            echo "✅ Model loaded successfully<br>";

            // Test simple query via model
            $result = $this->Reminder_model->sync_from_sipp(5);

            echo "✅ Sync completed:<br>";
            echo "- Synced: " . $result['synced_count'] . "<br>";
            echo "- Total processed: " . $result['total_processed'] . "<br>";

            if (!empty($result['errors'])) {
                echo "❌ Errors:<br>";
                foreach ($result['errors'] as $error) {
                    echo "- " . htmlspecialchars($error) . "<br>";
                }
            }
        } catch (Exception $e) {
            echo "❌ Error: " . htmlspecialchars($e->getMessage()) . "<br>";
        }
    }
}
