<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Extract year from Itsbat Nikah posita text
 * 
 * @param string $posita The posita text
 * @return string|null The extracted year or null if not found
 */
if (!function_exists('extract_marriage_year')) {
    function extract_marriage_year($posita)
    {
        if (empty($posita)) {
            return null;
        }

        // Try to match year pattern in text
        preg_match('/\b(19|20)\d{2}\b/', $posita, $matches);

        return !empty($matches[0]) ? $matches[0] : null;
    }
}

/**
 * Format date to Indonesian format
 * 
 * @param string $date Date string in Y-m-d format
 * @return string Formatted date in Indonesian format
 */
if (!function_exists('format_indo_date')) {
    function format_indo_date($date)
    {
        if (empty($date)) {
            return '-';
        }

        $bulan = array(
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        );

        $date = DateTime::createFromFormat('Y-m-d', $date);
        if (!$date) {
            return '-';
        }

        return $date->format('d') . ' ' . $bulan[$date->format('m')] . ' ' . $date->format('Y');
    }
}

/**
 * Calculate marriage duration in years
 * 
 * @param int $marriageYear The year of marriage
 * @return int|null The duration in years or null if not possible
 */
if (!function_exists('calculate_marriage_duration')) {
    function calculate_marriage_duration($marriageYear)
    {
        if (empty($marriageYear) || !is_numeric($marriageYear)) {
            return null;
        }

        $currentYear = date('Y');
        return $currentYear - (int)$marriageYear;
    }
}

/**
 * Extract full marriage date information from Itsbat Nikah posita text
 * 
 * @param string $posita The posita text
 * @return array Marriage date information
 */
if (!function_exists('extract_marriage_date')) {
    function extract_marriage_date($posita)
    {
        if (empty($posita)) {
            return null;
        }

        $result = [
            'year' => null,
            'month' => null,
            'day' => null,
            'formatted_date' => null,
            'display_text' => null
        ];

        // Try to find a full date pattern like DD-MM-YYYY or DD/MM/YYYY
        preg_match('/\b\d{1,2}[\/-]\d{1,2}[\/-](19|20)\d{2}\b/', $posita, $fullDateMatches);

        if (!empty($fullDateMatches[0])) {
            // Try to parse the full date
            $dateStr = str_replace('/', '-', $fullDateMatches[0]);
            $marriageDate = date_create_from_format('d-m-Y', $dateStr);

            if ($marriageDate) {
                $result['year'] = date_format($marriageDate, 'Y');
                $result['month'] = date_format($marriageDate, 'm');
                $result['day'] = date_format($marriageDate, 'd');
                $result['formatted_date'] = date_format($marriageDate, 'd-m-Y');
                $result['display_text'] = $result['formatted_date'];
                return $result;
            }
        }

        // If no full date found, try to find just the year
        preg_match('/\b(19|20)\d{2}\b/', $posita, $yearMatches);
        if (!empty($yearMatches[0])) {
            $result['year'] = (int)$yearMatches[0];
            $result['display_text'] = "Tahun " . $result['year'];

            // Also try to find month if available
            $monthNames = [
                'januari' => '01',
                'februari' => '02',
                'maret' => '03',
                'april' => '04',
                'mei' => '05',
                'juni' => '06',
                'juli' => '07',
                'agustus' => '08',
                'september' => '09',
                'oktober' => '10',
                'november' => '11',
                'desember' => '12'
            ];

            // Search for month names
            foreach ($monthNames as $monthName => $monthNum) {
                if (stripos($posita, $monthName) !== false) {
                    $result['month'] = $monthNum;
                    $result['display_text'] = ucfirst($monthName) . " " . $result['year'];

                    // Try to find day pattern near the month name
                    $monthPos = stripos($posita, $monthName);
                    $contextBefore = substr($posita, max(0, $monthPos - 20), 20);

                    preg_match('/\b(\d{1,2})\b/', $contextBefore, $dayMatches);
                    if (!empty($dayMatches[0]) && $dayMatches[0] > 0 && $dayMatches[0] <= 31) {
                        $result['day'] = sprintf("%02d", $dayMatches[0]);
                        $result['display_text'] = $result['day'] . "-" . $result['month'] . "-" . $result['year'];
                        $result['formatted_date'] = $result['day'] . "-" . $result['month'] . "-" . $result['year'];
                    }

                    break;
                }
            }
        }

        return $result;
    }
}
