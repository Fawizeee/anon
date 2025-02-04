

-- Step 1: Change the delimiter
DELIMITER $$

-- Step 2: Create the event
CREATE EVENT IF NOT EXISTS delete_old_messages
ON SCHEDULE EVERY 2 DAY
STARTS CURRENT_TIMESTAMP
DO
BEGIN
    -- Delete rows older than 10 seconds
    DELETE FROM MESSAGES
    WHERE CTIME < NOW() - INTERVAL 2 DAY;
END$$

-- Step 3: Reset the delimiter
DELIMITER ;