CREATE DEFINER=`root`@`localhost` 
EVENT `ev_auto_stock_status` 
ON SCHEDULE EVERY 1 SECOND 
STARTS CURRENT_TIMESTAMP 
ON COMPLETION NOT PRESERVE 
ENABLE 
DO
  UPDATE inventory_item
  SET status = 
    CASE
      WHEN DATEDIFF(date_expiry, CURDATE()) <= 0 THEN 'EXPIRED'      -- ❌ expired
      WHEN DATEDIFF(date_expiry, CURDATE()) <= 60 THEN 'SOON TO EXPIRE'  -- ⚠️ near expiry
      WHEN quantity <= 0 THEN 'OUT OF STOCK'                             -- 🟥 no stock
      WHEN quantity <= 2000 THEN 'LOW STOCK'                             -- 🟧 low stock
      ELSE 'IN STOCK'                                                    -- 🟩 okay
    END;
