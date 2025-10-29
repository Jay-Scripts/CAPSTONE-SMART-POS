ALTER TABLE REG_TRANSACTION
ADD COLUMN kiosk_transaction_id INT NULL AFTER STAFF_ID,
ADD CONSTRAINT fk_reg_kiosk_transaction
FOREIGN KEY (kiosk_transaction_id)
REFERENCES kiosk_transaction(kiosk_transaction_id)
ON DELETE SET NULL;


-- First, add the category_id column with a default value of 8
ALTER TABLE PRODUCT_ADD_ONS
ADD COLUMN category_id INT NOT NULL DEFAULT 8;

ALTER TABLE PRODUCT_ADD_ONS
ADD CONSTRAINT fk_addons_category
FOREIGN KEY (category_id) REFERENCES category(category_id)
 
ON DELETE cascade; 