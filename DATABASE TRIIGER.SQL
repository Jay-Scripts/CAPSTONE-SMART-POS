    --   ==========================================================================================================================================
    --   =                                                     FOR STOCKS QTY COUNTS,  STATUS CHECKING                                            =
    --   ==========================================================================================================================================

    --   ==========================================================================================================================================
    --   =                                                     FOR SOON TO EXPIRE 2 MONTHS LEFT  CHECKING                                         =
    --   ==========================================================================================================================================


DELIMITER $$

CREATE TRIGGER trg_insert_inventory_status
BEFORE INSERT ON inventory_item
FOR EACH ROW
BEGIN
    DECLARE days_left INT;
    SET days_left = DATEDIFF(NEW.date_expiry, CURDATE());

    IF days_left < 0 THEN
        SET NEW.status = 'UNAVAILABLE'; -- expired
    ELSEIF days_left <= 60 THEN
        SET NEW.status = 'SOON TO EXPIRE'
    ELSEIF NEW.quantity <= 0 THEN
        SET NEW.status = 'OUT OF STOCK';
    ELSEIF NEW.quantity <= 2000 THEN
        SET NEW.status = 'LOW STOCK';
    ELSE
        SET NEW.status = 'IN STOCK';
    END IF;
END$$


CREATE TRIGGER trg_update_inventory_status
BEFORE UPDATE ON inventory_item
FOR EACH ROW
BEGIN
    DECLARE days_left INT;
    SET days_left = DATEDIFF(NEW.date_expiry, CURDATE());

    IF days_left < 0 THEN
        SET NEW.status = 'UNAVAILABLE';
    ELSEIF days_left <= 60 THEN
        SET NEW.status = 'SOON TO EXPIRE';
    ELSEIF NEW.quantity <= 0 THEN
        SET NEW.status = 'OUT OF STOCK';
    ELSEIF NEW.quantity <= 2000 THEN
        SET NEW.status = 'LOW STOCK';
    ELSE
        SET NEW.status = 'IN STOCK';
    END IF;
END$$

DELIMITER ;



    --   ==========================================================================================================================================
    --   =                                                     FOR SOON TO EXPIRE 2 MONTHS LEFT  CHECKING    OLD DATA                             =
    --   ==========================================================================================================================================


UPDATE inventory_item
SET status = 
    CASE
        WHEN DATEDIFF(date_expiry, CURDATE()) BETWEEN 0 AND 60 THEN 'SOON TO EXPIRE'
        WHEN quantity <= 0 THEN 'OUT OF STOCK'
        WHEN quantity <= 2000 THEN 'LOW STOCK'
        ELSE 'IN STOCK'
    END
WHERE status != 'UNAVAILABLE';
