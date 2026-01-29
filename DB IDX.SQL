CREATE INDEX idx_staff_name ON staff_info(staff_name);
CREATE INDEX idx_product_name ON product_details(product_name);
CREATE INDEX idx_category_id ON product_details(category_id);
CREATE INDEX idx_reg_transaction_staff_id ON REG_TRANSACTION(STAFF_ID);
CREATE INDEX idx_kiosk_trans_date_added ON kiosk_transaction(date_added);
CREATE INDEX idx_transaction_item_product_id ON transaction_item(PRODUCT_ID);
CREATE INDEX idx_inventory_item_name ON inventory_item(item_name);
CREATE INDEX idx_inventory_item_category ON inventory_item(inv_category_id);

CREATE INDEX idx_product_sizes_status ON product_sizes(status);
