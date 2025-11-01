DELIMITER $$

-- When inserting new inventory items
CREATE TRIGGER trg_insert_stock_status
BEFORE INSERT ON inventory_item
FOR EACH ROW
BEGIN
    IF NEW.status <> 'UNAVAILABLE' THEN
        IF NEW.quantity <= 0 THEN
            SET NEW.status = 'OUT OF STOCK';
        ELSEIF NEW.quantity <= 2000 THEN
            SET NEW.status = 'LOW STOCK';
        ELSE
            SET NEW.status = 'IN STOCK';
        END IF;
    END IF;
END$$

-- When updating inventory quantities
CREATE TRIGGER trg_update_stock_status
BEFORE UPDATE ON inventory_item
FOR EACH ROW
BEGIN
    IF NEW.status <> 'UNAVAILABLE' THEN
        IF NEW.quantity <= 0 THEN
            SET NEW.status = 'OUT OF STOCK';
        ELSEIF NEW.quantity <= 2000 THEN
            SET NEW.status = 'LOW STOCK';
        ELSE
            SET NEW.status = 'IN STOCK';
        END IF;
    END IF;
END$$

DELIMITER ;
