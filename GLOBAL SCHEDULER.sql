SET GLOBAL event_scheduler = ON;

DELIMITER $$

CREATE EVENT IF NOT EXISTS ev_auto_stock_status
ON SCHEDULE EVERY 1 SECOND
DO
BEGIN
    -- Prevent overlapping execution
    IF GET_LOCK('ev_auto_stock_status_lock', 0) THEN

        -- 🔹 Update inventory_item status
        UPDATE inventory_item
        SET status = CASE
            WHEN quantity <= 0 THEN 'OUT OF STOCK'
            WHEN quantity <= 2000 THEN 'LOW STOCK'
            ELSE 'IN STOCK'
        END;

        -- 🔹 Update category status for Tea (categories 1 & 2)
        UPDATE category
        SET status = CASE
            WHEN (SELECT SUM(quantity) FROM inventory_item WHERE item_name = 'Tea') < 250 THEN 'INACTIVE'
            ELSE 'ACTIVE'
        END
        WHERE category_id IN (1,2);

        -- 🔹 Update category status for Coffee (categories 3 & 6)
        UPDATE category
        SET status = CASE
            WHEN (SELECT SUM(quantity) FROM inventory_item WHERE item_name = 'Coffee') < 250 THEN 'INACTIVE'
            ELSE 'ACTIVE'
        END
        WHERE category_id IN (3,6);


        -- Release lock
        DO RELEASE_LOCK('ev_auto_stock_status_lock');
    END IF;
END$$

DELIMITER ;




DELIMITER $$

CREATE EVENT IF NOT EXISTS ev_addons_status
ON SCHEDULE EVERY 5 SECOND
DO
BEGIN
    IF GET_LOCK('ev_addons_status_lock', 5) THEN

        -- Update all add-ons based on inventory quantity
        UPDATE PRODUCT_ADD_ONS pa
        JOIN inventory_item ii
          ON (pa.ADD_ONS_NAME = 'CHEESE CAKE' AND ii.item_name = 'Cheese Cake AddOn')
          OR (pa.ADD_ONS_NAME = 'PEARL' AND ii.item_name = 'Pearl AddOn')
          OR (pa.ADD_ONS_NAME = 'CREAM CHEESE' AND ii.item_name = 'Cream Cheese AddOn')
          OR (pa.ADD_ONS_NAME = 'COFFEE JELLY' AND ii.item_name = 'Coffee Jelly AddOn')
          OR (pa.ADD_ONS_NAME = 'CRUSHED OREO' AND ii.item_name = 'Crushed Oreo AddOn')
          OR (pa.ADD_ONS_NAME = 'CHIA SEED' AND ii.item_name = 'Chia Seed AddOn')
        SET pa.status = CASE
            WHEN ii.quantity < 20 THEN 'inactive'
            ELSE 'active'
        END;

        DO RELEASE_LOCK('ev_addons_status_lock');
    END IF;
END$$

DELIMITER ;
