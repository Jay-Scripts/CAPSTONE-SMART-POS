
-- ====================================================================================================================================
--                      TO CONTROL THE STATUS IF THE BASE PRODUCT IS LESS THAN THE SERVING RATIO                                      =
-- ====================================================================================================================================


DELIMITER $$

CREATE DEFINER=`root`@`localhost`
EVENT `update_product_status_based_on_category`
ON SCHEDULE EVERY 1 SECOND
STARTS CURRENT_TIMESTAMP
ON COMPLETION NOT PRESERVE
ENABLE
DO
BEGIN
    -- 🔹 If category is inactive, set its products to inactive
    UPDATE product_details 
    SET status = 'inactive'
    WHERE category_id IN (
        SELECT category_id FROM category WHERE status = 'INACTIVE'
    );

    -- 🔹 If category is active, set its products to active
    UPDATE product_details 
    SET status = 'active'
    WHERE category_id IN (
        SELECT category_id FROM category WHERE status = 'ACTIVE'
    );
END$$

DELIMITER ;



-- ====================================================================================================================================
--                      TO CONTROL THE TEA BASED PRODUCT                                                                              =
-- ====================================================================================================================================
DROP EVENT IF EXISTS ev_auto_stock_status;

DELIMITER $$

CREATE DEFINER=`root`@`localhost`
EVENT ev_auto_stock_status
ON SCHEDULE EVERY 1 SECOND
STARTS CURRENT_TIMESTAMP
ON COMPLETION NOT PRESERVE
ENABLE
DO
BEGIN
    -- 🔹 Update inventory_item status
    UPDATE inventory_item
    SET status = CASE
        WHEN DATEDIFF(date_expiry, CURDATE()) <= 0 THEN 'EXPIRED'
        WHEN DATEDIFF(date_expiry, CURDATE()) <= 60 THEN 'SOON TO EXPIRE'
        WHEN quantity <= 0 THEN 'OUT OF STOCK'
        WHEN quantity <= 2000 THEN 'LOW STOCK'
        ELSE 'IN STOCK'
    END;

    -- 🔹 Update categories 1 & 2 based on Tea quantity
    UPDATE category
    SET status = CASE
        WHEN (SELECT quantity FROM inventory_item WHERE item_name = 'Tea' LIMIT 1) < 250 THEN 'INACTIVE'
        ELSE 'ACTIVE'
    END
    WHERE category_id IN (1,2);

    -- 🔹 Update categories 3 & 6 based on Coffee quantity
    UPDATE category
    SET status = CASE
        WHEN (SELECT quantity FROM inventory_item WHERE item_name = 'Coffee' LIMIT 1) < 350 THEN 'INACTIVE'
        ELSE 'ACTIVE'
    END
    WHERE category_id IN (3,6);
END$$

DELIMITER ;
