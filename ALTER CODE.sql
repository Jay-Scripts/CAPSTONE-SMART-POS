ALTER TABLE REG_TRANSACTION
ADD COLUMN kiosk_transaction_id INT NULL AFTER STAFF_ID,
ADD CONSTRAINT fk_reg_kiosk_transaction
FOREIGN KEY (kiosk_transaction_id)
REFERENCES kiosk_transaction(kiosk_transaction_id)
ON DELETE SET NULL;


