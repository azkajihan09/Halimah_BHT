-- Create MySQL function to add working days
DELIMITER $$

DROP FUNCTION IF EXISTS ADD_WORKING_DAYS$$

CREATE FUNCTION ADD_WORKING_DAYS(start_date DATE, working_days INT)
RETURNS DATE
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE result_date DATE;
    DECLARE current_date DATE;
    DECLARE days_added INT DEFAULT 0;
    DECLARE day_of_week INT;
    DECLARE is_holiday BOOLEAN DEFAULT FALSE;
    
    SET current_date = start_date;
    
    WHILE days_added < working_days DO
        SET current_date = DATE_ADD(current_date, INTERVAL 1 DAY);
        SET day_of_week = DAYOFWEEK(current_date);
        
        -- Check if it's a public holiday (you can extend this list)
        SET is_holiday = FALSE;
        IF current_date IN (
            '2025-01-01', '2025-01-29', '2025-03-29', '2025-03-30', '2025-04-18',
            '2025-05-01', '2025-05-12', '2025-05-29', '2025-06-01', '2025-06-06',
            '2025-06-07', '2025-08-12', '2025-08-17', '2025-09-01', '2025-11-10',
            '2025-12-25'
        ) THEN
            SET is_holiday = TRUE;
        END IF;
        
        -- Count only working days (Monday=2 to Friday=6, excluding holidays)
        IF day_of_week >= 2 AND day_of_week <= 6 AND is_holiday = FALSE THEN
            SET days_added = days_added + 1;
        END IF;
    END WHILE;
    
    SET result_date = current_date;
    RETURN result_date;
END$$

DELIMITER;

-- Test the function
-- SELECT ADD_WORKING_DAYS('2025-11-01', 14) as target_bht;