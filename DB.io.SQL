// ========================= STAFF TABLES =========================
Table staff_info {
  staff_id integer [primary key, increment]
  staff_name varchar [unique, not null]
  added_by integer
  status varchar [default: 'ACTIVE'] // ENUM('ACTIVE','INACTIVE')
  date_added timestamp [default: `CURRENT_TIMESTAMP`]
}

Table staff_roles {
  role_id integer [primary key, increment]
  staff_id integer [not null]
  role varchar [not null] // ENUM('BARISTA','CASHIER','MANAGER')
  date_added timestamp [default: `CURRENT_TIMESTAMP`]
}

Table staff_logs {
  logs_id integer [primary key, increment]
  staff_id integer [not null]
  log_type varchar [default: 'OUT'] // ENUM('IN','OUT')
  log_time timestamp [default: `CURRENT_TIMESTAMP`]
}

// ========================= CATEGORY TABLE =========================
Table category {
  category_id integer [primary key, increment]
  category_name varchar [unique, not null]
  status varchar [default: 'ACTIVE'] // ENUM('ACTIVE','INACTIVE')
  date_added timestamp [default: `CURRENT_TIMESTAMP`]
}

// ========================= PRODUCT TABLES =========================
Table product_details {
  product_id integer [primary key, increment]
  product_name varchar
  category_id integer [not null]
  thumbnail_path varchar [not null]
  status varchar [default: 'inactive'] // ENUM('active','inactive')
  date_added timestamp [default: `CURRENT_TIMESTAMP`]
}

Table product_sizes {
  size_id integer [primary key, increment]
  product_id integer [not null]
  size varchar [default: 'medio'] // ENUM('medio','grande','promo','hot brew')
  regular_price decimal(6,2) [default: 0.00]
  promo_price decimal(6,2) [default: 0.00]
  status varchar [default: 'active'] // ENUM('active','inactive')
}

Table product_add_ons {
  add_ons_id integer [primary key, increment]
  category_id integer [not null]
  add_ons_name varchar [not null]
  price decimal(6,2) [default: 0.00]
  status varchar [default: 'active'] // ENUM('active','inactive')
  date_added timestamp [default: `CURRENT_TIMESTAMP`]
}

Table product_modifications {
  modification_id integer [primary key, increment]
  modification_name varchar [not null]
  status varchar [default: 'active'] // ENUM('active','inactive')
  date_added timestamp [default: `CURRENT_TIMESTAMP`]
}

// ========================= KIOSK TABLES =========================
Table kiosk_transaction {
  kiosk_transaction_id integer [primary key, increment]
  total_amount decimal(10,2) [default: 0.00]
  vat_amount decimal(10,2) [default: 0.00]
  status varchar [default: 'PENDING'] // ENUM('PENDING','PAID','VOID')
  date_added timestamp [default: `CURRENT_TIMESTAMP`]
}

Table kiosk_transaction_item {
  item_id integer [primary key, increment]
  kiosk_transaction_id integer [not null]
  product_id integer [not null]
  size_id integer [not null]
  quantity integer [default: 1]
  price decimal(10,2) [not null]
  date_added timestamp [default: `CURRENT_TIMESTAMP`]
}

Table kiosk_item_addons {
  kiosk_item_addon_id integer [primary key, increment]
  add_ons_id integer [not null]
  kiosk_item_id integer [not null]
  date_added timestamp [default: `CURRENT_TIMESTAMP`]
}

Table kiosk_item_modification {
  kiosk_item_modification_id integer [primary key, increment]
  kiosk_item_id integer [not null]
  modification_id integer [not null]
  date_added timestamp [default: `CURRENT_TIMESTAMP`]
}

// ========================= REG TRANSACTION TABLES =========================
Table reg_transaction {
  reg_transaction_id integer [primary key, increment]
  kiosk_transaction_id integer
  staff_id integer [not null]
  ordered_by varchar [default: 'POS'] // ENUM('KIOSK','POS','REWARDS APP')
  vatable_sales decimal(10,2) [default: 0.00]
  vat_amount decimal(6,2) [default: 0.00]
  total_amount decimal(6,2) [default: 0.00]
  amount_tendered decimal(10,2) [default: 0.00]
  change_amount decimal(10,2) [default: 0.00]
  status varchar [default: 'PENDING'] // ENUM('PENDING','PAID','NOW SERVING','COMPLETED','REFUNDED','WASTE','VOID')
  date_added timestamp [default: `CURRENT_TIMESTAMP`]
}

Table transaction_item {
  item_id integer [primary key, increment]
  reg_transaction_id integer [not null]
  product_id integer [not null]
  size_id integer [not null]
  quantity integer [default: 1]
  price decimal(10,2) [not null]
  date_added timestamp [default: `CURRENT_TIMESTAMP`]
}

Table item_add_ons {
  item_add_on_id integer [primary key, increment]
  add_ons_id integer [not null]
  item_id integer [not null]
  date_added timestamp [default: `CURRENT_TIMESTAMP`]
}

Table item_modification {
  item_modification_id integer [primary key, increment]
  item_id integer [not null]
  modification_id integer [not null]
  date_added timestamp [default: `CURRENT_TIMESTAMP`]
}

Table payment_methods {
  payment_id integer [primary key, increment]
  reg_transaction_id integer [not null]
  type varchar [default: 'CASH'] // ENUM('CASH','E-PAYMENT')
  amount_sent decimal(6,2) [default: 0.00]
  change_amount decimal(6,2) [default: 0.00]
}

// ========================= DISCOUNT, EPAY, WASTE, REFUND =========================
Table disc_transaction {
  disc_transaction_id integer [primary key, increment]
  reg_transaction_id integer [not null]
  id_type varchar [not null] // ENUM('PWD','SC')
  id_num integer [not null]
  first_name varchar [not null]
  last_name varchar [not null]
  disc_total_amount decimal(6,2) [default: 0.00]
  transaction_time timestamp [default: `CURRENT_TIMESTAMP`]
}

Table epayment_transaction {
  epay_transaction_id integer [primary key, increment]
  reg_transaction_id integer [not null]
  amount decimal(6,2) [not null]
  references_num integer [not null]
  transaction_time timestamp [default: `CURRENT_TIMESTAMP`]
}

Table waste_transactions {
  waste_id integer [primary key, increment]
  reg_transaction_id integer [not null]
  reason varchar [not null] // ENUM(...)
  notes text
  transaction_time timestamp [default: `CURRENT_TIMESTAMP`]
}

Table refund_transactions {
  refund_id integer [primary key, increment]
  reg_transaction_id integer [not null]
  reason varchar [not null] // ENUM(...)
  notes text
  transaction_time timestamp [default: `CURRENT_TIMESTAMP`]
}

// ========================= INVENTORY TABLES =========================
Table inventory_category {
  inv_category_id integer [primary key, increment]
  category_name varchar [unique, not null]
  date_added timestamp [default: `CURRENT_TIMESTAMP`]
}

Table inventory_item {
  item_id integer [primary key, increment]
  inv_category_id integer [not null]
  item_name varchar
  added_by integer [not null]
  product_id integer
  category_id integer
  unit varchar [not null] // ENUM('pcs','ml','g')
  quantity decimal(10,2) [not null]
  status varchar [default: 'IN STOCK'] // ENUM(...)
  date_made date [not null]
  date_expiry date [not null]
  date_added timestamp [default: `CURRENT_TIMESTAMP`]
}

Table inventory_item_logs {
  log_id integer [primary key, increment]
  item_id integer [not null]
  staff_id integer [not null]
  action_type varchar [not null] // ENUM('RESTOCK','ADJUSTMENT','EXPIRED','DAMAGED','INVENTORY')
  last_quantity decimal(10,2) [not null]
  quantity_adjusted decimal(10,2) [not null]
  total_after decimal(10,2) [not null]
  remarks varchar
  date_logged timestamp [default: `CURRENT_TIMESTAMP`]
}

Table product_ingredient_ratio {
  ratio_id integer [primary key, increment]
  product_id integer [not null]
  item_id integer [not null]
  quantity_needed decimal(10,2) [not null]
}

// ========================= CUSTOMER FEEDBACK =========================
Table customer_feedback {
  feedback_id integer [primary key, increment]
  reg_transaction_id integer [not null]
  staff_attitude tinyint [not null]
  product_accuracy tinyint [not null]
  cleanliness tinyint [not null]
  speed_of_service tinyint [not null]
  overall_satisfaction tinyint [not null]
  feedback_text text
  date_submitted timestamp [default: `CURRENT_TIMESTAMP`]
}

// ========================= RELATIONSHIPS =========================
Ref: staff_roles.staff_id > staff_info.staff_id
Ref: staff_logs.staff_id > staff_info.staff_id
Ref: product_details.category_id > category.category_id
Ref: product_sizes.product_id > product_details.product_id
Ref: product_add_ons.category_id > category.category_id
Ref: kiosk_transaction_item.kiosk_transaction_id > kiosk_transaction.kiosk_transaction_id
Ref: kiosk_transaction_item.product_id > product_details.product_id
Ref: kiosk_transaction_item.size_id > product_sizes.size_id
Ref: kiosk_item_addons.add_ons_id > product_add_ons.add_ons_id
Ref: kiosk_item_addons.kiosk_item_id > kiosk_transaction_item.item_id
Ref: kiosk_item_modification.kiosk_item_id > kiosk_transaction_item.item_id
Ref: kiosk_item_modification.modification_id > product_modifications.modification_id
Ref: transaction_item.reg_transaction_id > reg_transaction.reg_transaction_id
Ref: transaction_item.product_id > product_details.product_id
Ref: transaction_item.size_id > product_sizes.size_id
Ref: item_add_ons.item_id > transaction_item.item_id
Ref: item_add_ons.add_ons_id > product_add_ons.add_ons_id
Ref: item_modification.item_id > transaction_item.item_id
Ref: item_modification.modification_id > product_modifications.modification_id
Ref: payment_methods.reg_transaction_id > reg_transaction.reg_transaction_id
Ref: disc_transaction.reg_transaction_id > reg_transaction.reg_transaction_id
Ref: epayment_transaction.reg_transaction_id > reg_transaction.reg_transaction_id
Ref: inventory_item.inv_category_id > inventory_category.inv_category_id
Ref: inventory_item.added_by > staff_info.staff_id
Ref: inventory_item.product_id > product_details.product_id
Ref: inventory_item.category_id > category.category_id
Ref: inventory_item_logs.item_id > inventory_item.item_id
Ref: inventory_item_logs.staff_id > staff_info.staff_id
Ref: product_ingredient_ratio.product_id > product_details.product_id
Ref: product_ingredient_ratio.item_id > inventory_item.item_id
Ref: customer_feedback.reg_transaction_id > reg_transaction.reg_transaction_id
