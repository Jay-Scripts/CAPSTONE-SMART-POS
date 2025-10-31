SET GLOBAL event_scheduler = ON;

CREATE EVENT ev_auto_stock_status
ON SCHEDULE EVERY 1 SECOND
DO
  UPDATE inventory_item
  SET status = 
    CASE
      WHEN quantity <= 0 THEN 'OUT OF STOCK'
      WHEN quantity <= 2000 THEN 'LOW STOCK'
      ELSE 'IN STOCK'
    END;
