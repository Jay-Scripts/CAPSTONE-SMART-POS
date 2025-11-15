
-- ====================================================================================================================================
--                      TO CONTROL THE STATUS IF THE BASE PRODUCT IS LESS THAN THE SERVING RATIO                                      =
-- ====================================================================================================================================




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
    WHEN quantity <= 0 THEN 'OUT OF STOCK'
    WHEN DATEDIFF(date_expiry, CURDATE()) <= 0 THEN 'EXPIRED'
    WHEN DATEDIFF(date_expiry, CURDATE()) <= 60 THEN 'SOON TO EXPIRE'
    WHEN quantity <= 2000 THEN 'LOW STOCK'
    ELSE 'IN STOCK'
END;

    -- For Tea (categories 1 & 2)
UPDATE category
SET status = CASE
    WHEN (SELECT SUM(quantity) FROM inventory_item WHERE item_name = 'Tea') < 250 THEN 'INACTIVE'
    ELSE 'ACTIVE'
END
WHERE category_id IN (1,2);

-- For Coffee (categories 3 & 6)
UPDATE category
SET status = CASE
    WHEN (SELECT SUM(quantity) FROM inventory_item WHERE item_name = 'Coffee') < 250 THEN 'INACTIVE'
    ELSE 'ACTIVE'
END
WHERE category_id IN (3,6);

END$$

DELIMITER ;




-- For Tea (categories 1 & 2)
UPDATE category
SET status = CASE
    WHEN (SELECT SUM(quantity) FROM inventory_item WHERE item_name = 'Tea') < 250 THEN 'INACTIVE'
    ELSE 'ACTIVE'
END
WHERE category_id IN (1,2);

-- For Coffee (categories 3 & 6)
UPDATE category
SET status = CASE
    WHEN (SELECT SUM(quantity) FROM inventory_item WHERE item_name = 'Coffee') < 250 THEN 'INACTIVE'
    ELSE 'ACTIVE'
END
WHERE category_id IN (3,6);



  --          
  --       =================================================================================================================================================
  --       =                                                     To deduct Tea per order                                                          =
  --       =================================================================================================================================================
  --    

DELIMITER $$

DROP TRIGGER IF EXISTS tr_deduct_tea_stock$$

CREATE TRIGGER tr_deduct_tea_stock
AFTER INSERT ON TRANSACTION_ITEM
FOR EACH ROW
BEGIN
    -- Declare variables
    DECLARE trans_status ENUM('PENDING','PAID','NOW SERVING','COMPLETED','REFUNDED','WASTE','VOID');
    DECLARE size_name ENUM('medio','grande','promo','hot brew');
    DECLARE prod_category INT;

    -- Get the transaction status
    SELECT STATUS INTO trans_status
    FROM REG_TRANSACTION
    WHERE REG_TRANSACTION_ID = NEW.REG_TRANSACTION_ID;

    -- Only proceed if transaction is PAID
    IF trans_status = 'PAID' THEN

        -- Get product size
        SELECT size INTO size_name
        FROM PRODUCT_SIZES
        WHERE SIZE_ID = NEW.SIZE_ID;

        -- Get product category
        SELECT category_id INTO prod_category
        FROM PRODUCT_DETAILS
        WHERE PRODUCT_ID = NEW.PRODUCT_ID;

        -- Only apply to Milk Tea (1) or Fruit Tea (2)
        IF prod_category IN (1,2) THEN

            -- Deduct Tea based on size safely (no negative stock)
            IF size_name = 'medio' THEN
                UPDATE inventory_item
                SET quantity = GREATEST(quantity - (250 * NEW.QUANTITY), 0)
                WHERE item_name = 'Tea';

                -- Deduct Cup M 16oz
                UPDATE inventory_item
                SET quantity = GREATEST(quantity - NEW.QUANTITY, 0)
                WHERE item_name = 'Cup M 16oz';

            ELSEIF size_name = 'grande' THEN
                UPDATE inventory_item
                SET quantity = GREATEST(quantity - (350 * NEW.QUANTITY), 0)
                WHERE item_name = 'Tea';

                -- Deduct Cup G 22oz
                UPDATE inventory_item
                SET quantity = GREATEST(quantity - NEW.QUANTITY, 0)
                WHERE item_name = 'Cup G 22oz';
            END IF;

            -- Always deduct Sealing Film
            UPDATE inventory_item
            SET quantity = GREATEST(quantity - NEW.QUANTITY, 0)
            WHERE item_name = 'Sealing Film';

            -- Always deduct Straw
            UPDATE inventory_item
            SET quantity = GREATEST(quantity - NEW.QUANTITY, 0)
            WHERE item_name = 'Straw';

            -- 🔹 Update product status immediately
            UPDATE inventory_item
            SET status = CASE
                WHEN quantity <= 0 THEN 'OUT OF STOCK'
                WHEN DATEDIFF(date_expiry, CURDATE()) <= 0 THEN 'EXPIRED'
                WHEN DATEDIFF(date_expiry, CURDATE()) <= 60 THEN 'SOON TO EXPIRE'
                WHEN quantity <= 2000 THEN 'LOW STOCK'
                ELSE 'IN STOCK'
            END
            WHERE item_name IN ('Tea','Cup M 16oz','Cup G 22oz','Sealing Film','Straw');

            -- 🔹 Update category status immediately
            UPDATE category
            SET status = CASE
                WHEN (SELECT SUM(quantity) 
                      FROM inventory_item 
                      WHERE item_name = 'Tea') < 250 THEN 'INACTIVE'
                ELSE 'ACTIVE'
            END
            WHERE category_id IN (1,2);

        END IF;

    END IF;

END$$

DELIMITER ;




  --          
  --       =================================================================================================================================================
  --       =                                                     To deduct materials                                                          =
  --       =================================================================================================================================================
  --    
-- Update all products under inactive categories
UPDATE product_details pd
JOIN category c ON pd.category_id = c.category_id
SET pd.status = 'inactive'
WHERE c.status = 'INACTIVE';

-- Optional: reactivate if category becomes active
UPDATE product_details pd
JOIN category c ON pd.category_id = c.category_id
SET pd.status = 'active'
WHERE c.status = 'ACTIVE';
-- Update product status to match category
UPDATE product_details pd
JOIN category c ON pd.category_id = c.category_id
SET pd.status = CASE 
    WHEN c.status = 'INACTIVE' THEN 'inactive'
    ELSE 'active'
END;
