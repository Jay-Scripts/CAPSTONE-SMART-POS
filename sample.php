can i add realtime deduction to ing items ? for every paid items entered if = medio - 250 in milktea and grander - 350 in base ing

tbl



-- ==================================================================================================================================================================================================================================
-- = =
-- = CREATE TABLES - STARTS HERE =
-- = =
-- ==================================================================================================================================================================================================================================

-- = STAFF TABLE - STARTS HERE =
CREATE TABLE staff_info (
staff_id INT AUTO_INCREMENT PRIMARY KEY,
staff_name VARCHAR(30) NOT NULL unique,
added_by INT,
status ENUM('ACTIVE', 'INACTIVE') DEFAULT 'ACTIVE',
date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT fk_added_by FOREIGN KEY (added_by) REFERENCES staff_info(staff_id)
);

create table staff_roles(
role_id int auto_increment primary key,
staff_id int not null,
role ENUM('BARISTA', 'CASHIER', 'MANAGER') not null,
date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
foreign key(staff_id) references staff_info(staff_id)
);

CREATE TABLE staff_logs (
logs_id INT AUTO_INCREMENT PRIMARY KEY,
staff_id INT NOT NULL,
log_type ENUM('IN', 'OUT') DEFAULT 'OUT',
log_time DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (staff_id) REFERENCES staff_info(staff_id)
);


-- = STAFF TABLE - ENDS HERE =




CREATE TABLE category (
category_id INT AUTO_INCREMENT PRIMARY KEY,
category_name VARCHAR(50) NOT NULL UNIQUE,
status ENUM('ACTIVE', 'INACTIVE') DEFAULT 'ACTIVE',
date_added DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE product_details (
product_id INT AUTO_INCREMENT PRIMARY KEY,
product_name VARCHAR(50) ,
category_id INT NOT NULL,
thumbnail_path VARCHAR(150) NOT NULL,
date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
status ENUM('active', 'inactive') DEFAULT 'inactive',
FOREIGN KEY (category_id) REFERENCES category(category_id) ON DELETE CASCADE
);

CREATE TABLE product_sizes (
size_id INT AUTO_INCREMENT PRIMARY KEY,
product_id INT NOT NULL,
size ENUM('medio', 'grande', 'promo', 'hot brew') DEFAULT 'medio',
regular_price DECIMAL(6,2) DEFAULT 0.00,
promo_price DECIMAL(6,2) DEFAULT 0.00,
status ENUM('active', 'inactive') DEFAULT 'active',
FOREIGN KEY (product_id) REFERENCES product_details(product_id) ON DELETE CASCADE
);

CREATE TABLE PRODUCT_ADD_ONS(
ADD_ONS_ID INT AUTO_INCREMENT PRIMARY KEY,
category_id INT not null,
ADD_ONS_NAME VARCHAR(50) NOT NULL,
PRICE DECIMAL(6,2) DEFAULT 0.00,
date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
status ENUM('active', 'inactive') DEFAULT 'active',
foreign key (category_id) references category(category_id)
);

CREATE TABLE PRODUCT_MODIFICATIONS(
MODIFICATION_ID INT AUTO_INCREMENT PRIMARY KEY,
MODIFICATION_NAME VARCHAR(50) NOT NULL,
date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
status ENUM('active', 'inactive') DEFAULT 'active'
);




-- ========================================================================
-- MAIN KIOSK TRANSACTION TABLE =
-- ========================================================================
CREATE TABLE kiosk_transaction (
kiosk_transaction_id INT AUTO_INCREMENT PRIMARY KEY,
total_amount DECIMAL(10,2) DEFAULT 0.00,
vat_amount DECIMAL(10,2) DEFAULT 0.00,
status ENUM('PENDING', 'PAID', 'VOID') DEFAULT 'PENDING',
date_added DATETIME DEFAULT CURRENT_TIMESTAMP
);


-- ========================================================================
-- EACH PRODUCT PER TRANSACTION =
-- ========================================================================
CREATE TABLE kiosk_transaction_item (
item_id INT AUTO_INCREMENT PRIMARY KEY,
kiosk_transaction_id INT NOT NULL,
product_id INT NOT NULL,
size_id INT NOT NULL,
quantity INT NOT NULL DEFAULT 1,
price DECIMAL(10,2) NOT NULL,
date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (kiosk_transaction_id) REFERENCES kiosk_transaction(kiosk_transaction_id) ON DELETE CASCADE,
FOREIGN KEY (product_id) REFERENCES product_details(product_id) ON DELETE CASCADE,
FOREIGN KEY (size_id) REFERENCES product_sizes(size_id) ON DELETE CASCADE
);


-- ========================================================================
-- PRODUCT ADD-ONS (like pearl, extra cheese, etc.) =
-- ========================================================================
CREATE TABLE kiosk_item_addons (
kiosk_item_addon_id INT AUTO_INCREMENT PRIMARY KEY,
add_ons_id INT NOT NULL,
kiosk_item_id INT NOT NULL,
date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (add_ons_id) REFERENCES product_add_ons(add_ons_id) ON DELETE CASCADE,
FOREIGN KEY (kiosk_item_id) REFERENCES kiosk_transaction_item(item_id) ON DELETE CASCADE
);


-- ========================================================================
-- PRODUCT MODIFICATIONS (like less sugar, no ice, etc.) =
-- ========================================================================
CREATE TABLE kiosk_item_modification (
kiosk_item_modification_id INT AUTO_INCREMENT PRIMARY KEY,
kiosk_item_id INT NOT NULL,
modification_id INT NOT NULL,
date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (kiosk_item_id) REFERENCES kiosk_transaction_item(item_id) ON DELETE CASCADE,
FOREIGN KEY (modification_id) REFERENCES product_modifications(modification_id) ON DELETE CASCADE
);




-- THIS WILL HOLD THE TRANSACTION SUMARY PER CUSTOMER
CREATE TABLE REG_TRANSACTION (
REG_TRANSACTION_ID INT AUTO_INCREMENT PRIMARY KEY,
kiosk_transaction_id int null, -- add id if comes from kiosk
STAFF_ID INT NOT NULL,
ORDERED_BY ENUM('KIOSK', 'POS', 'REWARDS APP') DEFAULT 'POS',
vatable_sales DECIMAL(10,2) NOT NULL DEFAULT 0.00,
VAT_AMOUNT DECIMAL(6,2) NOT NULL DEFAULT 0.00,
TOTAL_AMOUNT DECIMAL(6,2) DEFAULT 0.00,
amount_tendered DECIMAL(10,2) NOT NULL DEFAULT 0.00,
change_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
STATUS ENUM('PENDING', 'PAID', 'NOW SERVING', 'COMPLETED', 'REFUNDED', 'WASTE', 'VOID') DEFAULT 'PENDING',
date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (kiosk_transaction_id) REFERENCES kiosk_transaction(kiosk_transaction_id) ON DELETE SET NULL,
FOREIGN KEY (STAFF_ID) REFERENCES STAFF_INFO(STAFF_ID) ON DELETE CASCADE
);




--
-- ===================================================================================================================================================
-- = PRODUCT Modification - starts HERE =
-- ===================================================================================================================================================
--


-- FOR EACH IDENTIFICATION OF PRODUCT INCASE WE HAVE 2 PRODUCT 1 WITH MODIFIACTION OR ADDONS WE CAN GRAB THE PREFERED PRODUCT THE CUSTOMER WANT TO BE ADJUSTED
CREATE TABLE TRANSACTION_ITEM(
ITEM_ID INT AUTO_INCREMENT PRIMARY KEY,
REG_TRANSACTION_ID INT NOT NULL,
PRODUCT_ID INT NOT NULL,
SIZE_ID INT NOT NULL,
QUANTITY INT NOT NULL DEFAULT 1,
PRICE DECIMAL(10,2) NOT NULL,
date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (REG_TRANSACTION_ID) REFERENCES REG_TRANSACTION(REG_TRANSACTION_ID) ON DELETE CASCADE,
FOREIGN KEY (PRODUCT_ID) REFERENCES PRODUCT_DETAILS(PRODUCT_ID) ON DELETE CASCADE,
FOREIGN KEY (SIZE_ID) REFERENCES PRODUCT_SIZES(SIZE_ID) ON DELETE CASCADE
);
-- FOR PRODUCT THAT HAS BEEN MODIFIED LIKE MILKTEA ADD PEARL ON MILKTEA
CREATE TABLE item_add_ons (
item_add_on_id INT AUTO_INCREMENT PRIMARY KEY,
add_ons_id INT NOT NULL,
item_id INT NOT NULL,
date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY(item_id) REFERENCES transaction_item(item_id) ON DELETE CASCADE,
FOREIGN KEY(ADD_ONS_ID) REFERENCES PRODUCT_ADD_ONS(ADD_ONS_ID) ON DELETE CASCADE
);
-- FOR PRODUCT THAT HAS BEEN MODIFIED LIKE MILKTEA WITH LESS ICE
CREATE TABLE item_modification (
item_modification_id INT AUTO_INCREMENT PRIMARY KEY,
item_id INT NOT NULL,
modification_id INT NOT NULL,
date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY(item_id) REFERENCES transaction_item(item_id) ON DELETE CASCADE,
FOREIGN KEY(modification_id) REFERENCES product_modifications(modification_id) ON DELETE CASCADE
);
CREATE TABLE PAYMENT_METHODS(
PAYMENT_ID INT AUTO_INCREMENT PRIMARY KEY,
REG_TRANSACTION_ID INT NOT NULL,
TYPE ENUM ('CASH', 'E-PAYMENT') DEFAULT 'CASH',
AMOUNT_SENT DECIMAL(6,2) DEFAULT 0.00,
CHANGE_AMOUNT DECIMAL(6,2) DEFAULT 0.00,
FOREIGN KEY (REG_TRANSACTION_ID) REFERENCES REG_TRANSACTION(REG_TRANSACTION_ID) ON DELETE CASCADE
);

--
-- =================================================================================================================================================
-- = PRODUCT Modification - ENDS HERE =
-- =================================================================================================================================================
--







-- THIS table will hold of the discounted trans details
CREATE TABLE DISC_TRANSACTION (
DISC_TRANSACTION_ID INT AUTO_INCREMENT PRIMARY KEY,
REG_TRANSACTION_ID INT NOT NULL,
ID_TYPE ENUM ('PWD', 'SC') NOT NULL,
ID_NUM INT NOT NULL,
FIRST_NAME VARCHAR(50) NOT NULL,
LAST_NAME VARCHAR(50) NOT NULL,
DISC_TOTAL_AMOUNT DECIMAL(6,2) DEFAULT 0.00,
TRANSACTION_TIME DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY(REG_TRANSACTION_ID) REFERENCES REG_TRANSACTION(REG_TRANSACTION_ID) ON DELETE CASCADE
);

CREATE TABLE EPAYMENT_TRANSACTION (
EPAY_TRANSACTION_ID INT AUTO_INCREMENT PRIMARY KEY,
REG_TRANSACTION_ID INT NOT NULL,
AMOUNT DECIMAL(6,2) NOT NULL,
REFERENCES_NUM INT NOT NULL, -- added column
TRANSACTION_TIME DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (REG_TRANSACTION_ID)
REFERENCES REG_TRANSACTION(REG_TRANSACTION_ID)
ON DELETE CASCADE
);


-- THIS WILL HOLD THE WASTE PRODUCTS
CREATE TABLE WASTE_transactions (
waste_id INT AUTO_INCREMENT PRIMARY KEY,
REG_TRANSACTION_ID INT NOT NULL,
reason ENUM(
'Wrong Order',
'Customer Cancelled',
'Staff Error',
'Expired',
'Free Drink / Complimentary',
'Product Test',
'Others'
) not null,
notes text null,
TRANSACTION_TIME DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY(REG_TRANSACTION_ID) REFERENCES REG_TRANSACTION(REG_TRANSACTION_ID) ON DELETE CASCADE
);

CREATE TABLE refund_transactions (
refund_id INT AUTO_INCREMENT PRIMARY KEY,
REG_TRANSACTION_ID INT NOT NULL,
reason ENUM(
'Customer Cancelled',
'Wrong Order',
'Product Defect',
'Overcharge',
'Staff Error',
'Expired Item',
'Others'
) NOT NULL,
notes TEXT NULL,
TRANSACTION_TIME DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (REG_TRANSACTION_ID) REFERENCES REG_TRANSACTION(REG_TRANSACTION_ID) ON DELETE CASCADE
);


-- ========================================================================
-- EACH CATEGORIES OF INVENTORY ITEMS =
-- ========================================================================
CREATE TABLE inventory_category (
inv_category_id INT AUTO_INCREMENT PRIMARY KEY,
category_name VARCHAR(50) NOT NULL UNIQUE,
date_added DATETIME DEFAULT CURRENT_TIMESTAMP
);


-- ========================================================================
-- EACH INVENTORY ITEMS PER CATEGORIES =
-- ========================================================================
CREATE TABLE inventory_item (
item_id INT AUTO_INCREMENT PRIMARY KEY,
inv_category_id INT NOT NULL,
item_name VARCHAR(100) NULL,
added_by INT NOT NULL, -- 🔹 Manager ID who added the item
product_id INT NULL, -- only if tied to an actual POS product for specific product ing
category_id INT NULL, -- only if tied to an actual POS category so for per category control
unit ENUM('pcs','ml','g') NOT NULL,
quantity DECIMAL(10,2) NOT NULL,
status ENUM('IN STOCK', 'LOW STOCK', 'OUT OF STOCK', 'SOON TO EXPIRE', 'EXPIRED', 'UNAVAILABLE') DEFAULT 'IN STOCK',
date_made DATE NOT NULL,
date_expiry DATE NOT NULL,
date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (inv_category_id) REFERENCES inventory_category(inv_category_id) ON DELETE CASCADE,
FOREIGN KEY (added_by) REFERENCES staff_info(staff_id) ON DELETE CASCADE,
FOREIGN KEY (product_id) REFERENCES product_details(product_id) ON DELETE CASCADE,
foreign key (category_id) references category(category_id) on DELETE cascade
);

-- ========================================================================
-- FOR ACCOUNTABILITY IN INVENTORY ADJUSTMENTS =
-- ========================================================================
CREATE TABLE inventory_item_logs (
log_id INT AUTO_INCREMENT PRIMARY KEY,
item_id INT NOT NULL,
staff_id INT NOT NULL, -- who performed the action
action_type ENUM('RESTOCK', 'ADJUSTMENT', 'EXPIRED', 'DAMAGED', 'INVENTORY') NOT NULL,
last_quantity DECIMAL(10,2) NOT NULL, -- 🔹 Quantity before the action
quantity_adjusted DECIMAL(10,2) NOT NULL, -- 🔹 Quantity added or removed
total_after DECIMAL(10,2) NOT NULL, -- 🔹 Quantity after the action
remarks VARCHAR(255) NULL,
date_logged DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (item_id) REFERENCES inventory_item(item_id) ON DELETE CASCADE,
FOREIGN KEY (staff_id) REFERENCES staff_info(staff_id) ON DELETE CASCADE
);

-- ========================================================================
-- EACH PRODUCT PER Deductions =
-- ========================================================================

CREATE TABLE product_ingredient_ratio (
ratio_id INT AUTO_INCREMENT PRIMARY KEY,
product_id INT NOT NULL,
item_id INT NOT NULL,
quantity_needed DECIMAL(10,2) NOT NULL, -- ratio per serving
FOREIGN KEY (product_id) REFERENCES product_details(product_id) ON DELETE CASCADE,
FOREIGN KEY (item_id) REFERENCES inventory_item(item_id) ON DELETE CASCADE
);


CREATE TABLE customer_feedback (
feedback_id INT AUTO_INCREMENT PRIMARY KEY,
reg_transaction_id INT NOT NULL, -- Link to a specific transaction
staff_attitude TINYINT NOT NULL, -- Rating 1-5
product_accuracy TINYINT NOT NULL, -- Rating 1-5
cleanliness TINYINT NOT NULL, -- Rating 1-5
speed_of_service TINYINT NOT NULL, -- Rating 1-5
overall_satisfaction TINYINT NOT NULL, -- Rating 1-5
feedback_text TEXT NULL, -- Optional feedback
date_submitted DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (reg_transaction_id) REFERENCES REG_TRANSACTION(REG_TRANSACTION_ID)
ON DELETE CASCADE
);

INSERT INTO category (category_name)
VALUES
('MILK TEA'), -- DONE
('FRUIT TEA'), -- DONE
('HOT BREW'), -- DONE
('PRAF'), -- DONE
('BROSTY'), -- DONE
('ICED COFFEE'), -- DONE
('PROMOS'), -- DONE
('ADD-ONS'); -- DONE

INSERT INTO product_details (product_name, category_id, thumbnail_path)
VALUES
('Winter ', 1, '../assets/IMAGES/MENU IMAGES/MILKTEA_MENU/winter melon.png'),
('Taro', 1, '../assets/IMAGES/MENU IMAGES/MILKTEA_MENU/taro.png'),
('Strawberry', 1, '../assets/IMAGES/MENU IMAGES/MILKTEA_MENU/strawberry.png'),
('Salted Caramel', 1, '../assets/IMAGES/MENU IMAGES/MILKTEA_MENU/salted caramel.png'),
('Red Velvet', 1, '../assets/IMAGES/MENU IMAGES/MILKTEA_MENU/red velvet.png'),
('Matcha', 1, '../assets/IMAGES/MENU IMAGES/MILKTEA_MENU/matcha.png'),
('Double Dutch', 1, '../assets/IMAGES/MENU IMAGES/MILKTEA_MENU/double dutch.png'),
('Dark Choco', 1, '../assets/IMAGES/MENU IMAGES/MILKTEA_MENU/dark choco.png'),
('Choco Hazelnut', 1, '../assets/IMAGES/MENU IMAGES/MILKTEA_MENU/choco hazelnut.png'),
('Cookies & Cream', 1, '../assets/IMAGES/MENU IMAGES/MILKTEA_MENU/cookies & cream.png'),
('Choco Kisses', 1, '../assets/IMAGES/MENU IMAGES/MILKTEA_MENU/choco kisses.png'),
('Brown Sugar', 1, '../assets/IMAGES/MENU IMAGES/MILKTEA_MENU/brown sugar.png');


-- For medio (₱39.00)
INSERT INTO product_sizes (product_id, size, regular_price)
VALUES
(1, 'medio', 39.00),
(2, 'medio', 39.00),
(3, 'medio', 39.00),
(4, 'medio', 39.00),
(5, 'medio', 39.00),
(6, 'medio', 39.00),
(7, 'medio', 39.00),
(8, 'medio', 39.00),
(9, 'medio', 39.00),
(10, 'medio', 39.00),
(11, 'medio', 39.00),
(12, 'medio', 39.00);


-- For grande (₱49.00)
INSERT INTO product_sizes (product_id, size, regular_price)
VALUES
(1, 'grande', 49.00),
(2, 'grande', 49.00),
(3, 'grande', 49.00),
(4, 'grande', 49.00),
(5, 'grande', 49.00),
(6, 'grande', 49.00),
(7, 'grande', 49.00),
(8, 'grande', 49.00),
(9, 'grande', 49.00),
(10, 'grande', 49.00),
(11, 'grande', 49.00),
(12, 'grande', 49.00);
--
-- =======================
-- = MILKTEA ENDS HERE =
-- =======================
--

--
-- =========================
-- = FRUIT TEA STARTS HERE =
-- =========================
--
INSERT INTO product_details (product_name, category_id, thumbnail_path)
VALUES
('Green Apple', 2, '../assets/IMAGES/MENU IMAGES/FRUITTEA_MENU/GREEN APPLE.png'),
('Kiwi', 2, '../assets/IMAGES/MENU IMAGES/FRUITTEA_MENU/KIWI.png'),
('Lemon', 2, '../assets/IMAGES/MENU IMAGES/FRUITTEA_MENU/LEMON.png'),
('Passion Fruit', 2, '../assets/IMAGES/MENU IMAGES/FRUITTEA_MENU/PASSION FRUIT.png'),
('Strawberry', 2, '../assets/IMAGES/MENU IMAGES/FRUITTEA_MENU/STRAWBERRY.png'),
('Watermelon', 2, '../assets/IMAGES/MENU IMAGES/FRUITTEA_MENU/WATERMELON.png');

(SELECT product_id FROM product_details WHERE product_name = 'Green Apple');
-- For medio (₱39.00)
INSERT INTO product_sizes (product_id, size, regular_price)
VALUES
(13, 'medio', 39.00),
(14, 'medio', 39.00),
(15, 'medio', 39.00),
(16, 'medio', 39.00),
(17, 'medio', 39.00),
(18, 'medio', 39.00);

-- For grande (₱49.00)
INSERT INTO product_sizes (product_id, size, regular_price)
VALUES
(13, 'grande', 49.00),
(14, 'grande', 49.00),
(15, 'grande', 49.00),
(16, 'grande', 49.00),
(17, 'grande', 49.00),
(18, 'grande', 49.00);
--
-- =======================
-- = FRUIT TEA ENDS HERE =
-- =======================
--

--
-- =============================
-- = ICED COFEE TEA START HERE =
-- =============================
--
INSERT INTO product_details (product_name, category_id, thumbnail_path)
VALUES
('KAPE BRUSKO', 6, '../assets/IMAGES/MENU IMAGES/ICEDCOFFEE_MENU/KAPE BRUSKO.png'),
('KAPE KARAMEL', 6, '../assets/IMAGES/MENU IMAGES/ICEDCOFFEE_MENU/KAPE KARAMEL.png'),
('KAPE MACCH', 6, '../assets/IMAGES/MENU IMAGES/ICEDCOFFEE_MENU/KAPE MACCH.png'),
('KAPE VANILLA', 6, '../assets/IMAGES/MENU IMAGES/ICEDCOFFEE_MENU/KAPE VANILLA.png');

-- For medio (₱39.00)
INSERT INTO product_sizes (product_id, size, regular_price)
VALUES
(19, 'medio', 39.00),
(20, 'medio', 39.00),
(21, 'medio', 39.00),
(22, 'medio', 39.00);

-- For grande (₱49.00)
INSERT INTO product_sizes (product_id, size, regular_price)
VALUES
(19, 'grande', 49.00),
(20, 'grande', 49.00),
(21, 'grande', 49.00),
(22, 'grande', 49.00);

--
-- ============================
-- = ICED COFEE TEA ENDS HERE =
-- ============================
--

--
-- ====================
-- = PRAF STARTS HERE =
-- ====================
--

INSERT INTO product_details (product_name, category_id, thumbnail_path)
VALUES
('CARAMEL MATCCH', 4, '../assets/IMAGES/MENU IMAGES/PRAF_MENU/CARAMEL MATCCH.png'),
('CHEESE CAKE', 4, '../assets/IMAGES/MENU IMAGES/PRAF_MENU/CHEESECAKE.png'),
('CHOCO CREAM', 4, '../assets/IMAGES/MENU IMAGES/PRAF_MENU/CHOCO CREAM.png'),
('COFFEE JELLY', 4, '../assets/IMAGES/MENU IMAGES/PRAF_MENU/COFFEE JELLY.png'),
('COOKIES & CREAM', 4, '../assets/IMAGES/MENU IMAGES/PRAF_MENU/COOKIES & CREAM.png'),
('CREAMY AVOCADO', 4, '../assets/IMAGES/MENU IMAGES/PRAF_MENU/CREAMY AVOCADO.png'),
('MATCHA', 4, '../assets/IMAGES/MENU IMAGES/PRAF_MENU/MATCHA.png'),
('MELON', 4, '../assets/IMAGES/MENU IMAGES/PRAF_MENU/MELON.png'),
('MOCHA', 4, '../assets/IMAGES/MENU IMAGES/PRAF_MENU/MOCHA.png'),
('STRAWBERRY', 4, '../assets/IMAGES/MENU IMAGES/PRAF_MENU/STRAWBERRY.png'),
('VANILLA COFFEE', 4, '../assets/IMAGES/MENU IMAGES/PRAF_MENU/VANILLA COFFEE.png');

-- For medio (₱39.00)
INSERT INTO product_sizes (product_id, size, regular_price)
VALUES
(23, 'medio', 39.00),
(24, 'medio', 39.00),
(25, 'medio', 39.00),
(26, 'medio', 39.00),
(27, 'medio', 39.00),
(28, 'medio', 39.00),
(29, 'medio', 39.00),
(30, 'medio', 39.00),
(31, 'medio', 39.00),
(32, 'medio', 39.00),
(33, 'medio', 39.00);

-- For grande (₱49.00)
INSERT INTO product_sizes (product_id, size, regular_price)
VALUES
(23, 'grande', 49.00),
(24, 'grande', 49.00),
(25, 'grande', 49.00),
(26, 'grande', 49.00),
(27, 'grande', 49.00),
(28, 'grande', 49.00),
(29, 'grande', 49.00),
(30, 'grande', 49.00),
(31, 'grande', 49.00),
(32, 'grande', 49.00),
(33, 'grande', 49.00);
--
-- ==================
-- = PRAF ENDS HERE =
-- ==================
--



--
-- ======================
-- = BROSTY STARTS HERE =
-- ======================
--

INSERT INTO product_details (product_name, category_id, thumbnail_path)
VALUES
('BLUE BERRY', 5, '../assets/IMAGES/MENU IMAGES/BROSTY/BLUE BERRY.png'),
('GREEN APPLE', 5, '../assets/IMAGES/MENU IMAGES/BROSTY/GREEN APPLE.png'),
('HONEY PEACH', 5, '../assets/IMAGES/MENU IMAGES/BROSTY/HONEY PEACH.png'),
('KIWI', 5, '../assets/IMAGES/MENU IMAGES/BROSTY/KIWI.png'),
('LEMON', 5, '../assets/IMAGES/MENU IMAGES/BROSTY/LEMON.png'),
('LYCHEE', 5, '../assets/IMAGES/MENU IMAGES/BROSTY/LYCHEE.png'),
('MANGO', 5, '../assets/IMAGES/MENU IMAGES/BROSTY/MANGO.png'),
('PASSION FRUIT', 5, '../assets/IMAGES/MENU IMAGES/BROSTY/PASSION FRUIT.png'),
('STRAWBERRY', 5, '../assets/IMAGES/MENU IMAGES/BROSTY/STRAWBERRY.png'),
('WATERMELON', 5, '../assets/IMAGES/MENU IMAGES/BROSTY/WATERMELON.png');

-- For medio (₱39.00)
INSERT INTO product_sizes (product_id, size, regular_price)
VALUES
(33, 'medio', 39.00),
(34, 'medio', 39.00),
(35, 'medio', 39.00),
(36, 'medio', 39.00),
(37, 'medio', 39.00),
(38, 'medio', 39.00),
(39, 'medio', 39.00),
(40, 'medio', 39.00),
(41, 'medio', 39.00),
(42, 'medio', 39.00),
(43, 'medio', 39.00);

-- For grande (₱49.00)
INSERT INTO product_sizes (product_id, size, regular_price)
VALUES
(33, 'grande', 49.00),
(34, 'grande', 49.00),
(35, 'grande', 49.00),
(36, 'grande', 49.00),
(37, 'grande', 49.00),
(38, 'grande', 49.00),
(39, 'grande', 49.00),
(40, 'grande', 49.00),
(41, 'grande', 49.00),
(42, 'grande', 49.00),
(43, 'grande', 49.00);
--
-- ====================
-- = BROSTY ENDS HERE =
-- ====================
--


--
-- ========================
-- = HOT BREW STARTS HERE =
-- ========================
--

INSERT INTO product_details (product_name, category_id, thumbnail_path)
VALUES

('HOT BRUSKO', 3, '../assets/IMAGES/MENU IMAGES/HOT_BREW/HOT BRUSKO.png'),
('HOT CHOCO', 3, '../assets/IMAGES/MENU IMAGES/HOT_BREW/HOT CHOCO.png'),
('HOT MOCA', 3, '../assets/IMAGES/MENU IMAGES/HOT_BREW/HOT MOCA.png'),
('HOT MATCHA', 3, '../assets/IMAGES/MENU IMAGES/HOT_BREW/HOT MATCHA.png'),
('HOT KARAMEL', 3, '../assets/IMAGES/MENU IMAGES/HOT_BREW/HOT KARAMEL.png');

-- For grande (₱49.00)
INSERT INTO product_sizes (product_id, size, regular_price)
VALUES
(44, 'medio', 39.00),
(45, 'medio', 39.00),
(46, 'medio', 39.00),
(47, 'medio', 39.00),
(48, 'medio', 39.00);
--
-- ======================
-- = HOT BREW ENDS HERE =
-- ======================
--

--
-- ========================
-- = PROMO STARTS HERE =
-- ========================
--

INSERT INTO product_details (product_name, category_id, thumbnail_path)
VALUES

('BLACKPINK', 7, '../assets/IMAGES/MENU IMAGES/PROMOS_MENU/BLACKPINK.png'),
('BOSS BREW', 7, '../assets/IMAGES/MENU IMAGES/PROMOS_MENU/BOSS BREW.png'),
('SUPER CHOCO', 7, '../assets/IMAGES/MENU IMAGES/PROMOS_MENU/DARK CHOCO.png'),
('KAPE KMJS', 7, '../assets/IMAGES/MENU IMAGES/PROMOS_MENU/KAPE KMJS.png'),
('KARA VAN', 7, '../assets/IMAGES/MENU IMAGES/PROMOS_MENU/KARA VAN.png'),
('SUPREME MOCA', 7, '../assets/IMAGES/MENU IMAGES/PROMOS_MENU/SUPREME MOCA.png');

-- For PROMO (₱39.00)
INSERT INTO product_sizes (product_id, size, regular_price)
VALUES
(49, 'promo', 66.00),
(50, 'promo', 70.00),
(51, 'promo', 49.00),
(52, 'promo', 60.00),
(53, 'promo', 70.00),
(54, 'promo', 52.00);


--
-- ======================
-- = PROMO ENDS HERE =
-- ======================
--


--
-- ==========================
-- = ADD-ONS STARTS HERE =
-- ==========================
--


INSERT INTO PRODUCT_ADD_ONS(category_id, ADD_ONS_NAME, PRICE)
VALUES
(8, 'CHEESE CAKE', 10.00),
(8, 'PEARL', 10.00),
(8, 'CREAM CHEESE', 10.00),
(8, 'COFFEE JELLY', 10.00),
(8, 'CRUSHED OREO', 10.00),
(8, 'CHIA SEED', 10.00),
(8, 'Crystal', 10.00),
(8, 'Cream Puff', 10.00);

--
-- ======================
-- = ADD-ONS ENDS HERE =
-- ======================
--



--
-- ==============================
-- = MODIFICATIONS STARTS HERE =
-- ==============================
--

INSERT INTO product_modifications (MODIFICATION_NAME)
VALUES
('LESS ICE'),
('MORE ICE'),
('25% SUGAR'),
('50% SUGAR'),
('75% SUGAR'),
('100% SUGAR')
;



INSERT INTO inventory_category (category_name)
VALUES
('Ingredients'),
('Materials'),
('Base');





-- ============================
-- = MILKTEA INV START HERE =
-- ============================
--
INSERT INTO inventory_item (
inv_category_id, item_name, added_by, product_id, category_id, unit, quantity, date_made, date_expiry
)
VALUES
(1, 'Wintermelon Syrup', 1, 1, 1, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Taro Syrup', 1, 2, 1, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Strawberry Syrup', 1, 3, 1, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Salted Caramel Syrup', 1, 4, 1, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Red Velvet Syrup', 1, 5, 1, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Matcha Syrup', 1, 6, 1, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Double Dutch Syrup', 1, 7, 1, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Dark Choco Syrup', 1, 1, 1, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Choco Hazelnut Syrup', 1, 9, 1, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Cookies & Cream Syrup', 1, 10, 1, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Choco Kisses Syrup', 1, 11, 1, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Brown Sugar Syrup', 1, 12, 1, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));


-- ============================
-- = MILKTEA INV ENDS HERE =
-- ============================
--



-- ============================
-- = FRUIT TEA INV STARTS HERE =
-- ============================
--
INSERT INTO inventory_item (
inv_category_id, item_name, added_by, product_id, category_id, unit, quantity, date_made, date_expiry
)
VALUES
(1, 'Green Apple Syrup', 1, 13, 2, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Kiwi Syrup', 1, 14, 2, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Lemon Syrup', 1, 15, 2, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Passion Fruit Syrup', 1, 16, 2, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Strawberry Syrup', 1, 17, 2, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Watermelon Syrup', 1, 18, 2, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));
-- ============================
-- = FRUIT TEA INV ENDS HERE =
-- ============================
--


-- ============================
-- = ICED COFFEE INV ENDS HERE =
-- ============================
--
INSERT INTO inventory_item (
inv_category_id, item_name, added_by, product_id, category_id, unit, quantity, date_made, date_expiry
)
VALUES
(1, 'Kape Brusko Syrup', 1, 19, 6, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Kape Karamel Syrup', 1, 20, 6, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Kape Macch Syrup', 1, 21, 6, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Kape Vanilla Syrup', 1, 22, 6, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));
-- ============================
-- = ICED COFFEE INV ENDS HERE =
-- ============================
--

-- ============================
-- = PRAF INV STARRT HERE =
-- ============================
--
INSERT INTO inventory_item (
inv_category_id, item_name, added_by, product_id, category_id, unit, quantity, date_made, date_expiry
)
VALUES
(1, 'Caramel Matcch Syrup', 1, 23, 4, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Cheese Cake Syrup', 1, 24, 4, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Choco Cream Syrup', 1, 25, 4, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Coffee Jelly Syrup', 1, 26, 4, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Cookies & Cream Syrup', 1, 27, 4, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Creamy Avocado Syrup', 1, 28, 4, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Matcha Syrup', 1, 29, 4, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Melon Syrup', 1, 30, 4, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Mocha Syrup', 1, 31, 4, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Strawberry Syrup', 1, 32, 4, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Vanilla Coffee Syrup', 1, 33, 4, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));
-- ============================
-- = PRAF INV ENDS HERE =
-- ============================
--

-- ============================
-- = BROSTY INV STARTS HERE =
-- ============================
--

INSERT INTO inventory_item (
inv_category_id, item_name, added_by, product_id, category_id, unit, quantity, date_made, date_expiry
)
VALUES
(1, 'Blue Berry Syrup', 1, 33, 5, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Green Apple Syrup', 1, 34, 5, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Honey Peach Syrup', 1, 35, 5, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Kiwi Syrup', 1, 36, 5, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Lemon Syrup', 1, 37, 5, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Lychee Syrup', 1, 38, 5, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Mango Syrup', 1, 39, 5, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Passion Fruit Syrup', 1, 40, 5, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Strawberry Syrup', 1, 41, 5, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Watermelon Syrup', 1, 42, 5, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));
-- ============================
-- = BROSTY INV ENDS HERE =
-- ============================
--

-- ============================
-- = HOT BREW INV START HERE =
-- ============================
--
INSERT INTO inventory_item (
inv_category_id, item_name, added_by, product_id, category_id, unit, quantity, date_made, date_expiry
)
VALUES
(1, 'Hot Brusko Syrup', 1, 44, 3, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Hot Choco Syrup', 1, 45, 3, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Hot Moca Syrup', 1, 46, 3, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Hot Matcha Syrup', 1, 47, 3, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Hot Karamel Syrup', 1, 48, 3, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));
-- ============================
-- = HOT BREW INV ENDS HERE =
-- ============================
--

-- ============================
-- = PROMOS INV START HERE =
-- ============================
--
INSERT INTO inventory_item (
inv_category_id, item_name, added_by, product_id, category_id, unit, quantity, date_made, date_expiry
)
VALUES
(1, 'Blackpink Syrup', 1, 49, 7, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Boss Brew Syrup', 1, 50, 7, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Super Choco Syrup', 1, 51, 7, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Kape KMJS Syrup', 1, 52, 7, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Kara Van Syrup', 1, 53, 7, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Supreme Moca Syrup', 1, 54, 7, 'ml', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));
-- ============================
-- = PROMOS INV ENDS HERE =
-- ============================
--


-- ============================
-- = ADDONS INV STARTS HERE =
-- ============================
--
INSERT INTO inventory_item (
inv_category_id, item_name, added_by, product_id, category_id, unit, quantity, date_made, date_expiry
)
VALUES
(1, 'Cheese Cake AddOn', 1, NULL, 8, 'g', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Pearl AddOn', 1, NULL, 8, 'g', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Cream Cheese AddOn', 1, NULL, 8, 'g', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Coffee Jelly AddOn', 1, NULL, 8, 'g', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Crushed Oreo AddOn', 1, NULL, 8, 'g', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Chia Seed AddOn', 1, NULL, 8, 'g', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Crystal', 1, NULL, 8, 'g', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(1, 'Cream Puff', 1, NULL, 8, 'g', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));
-- ============================
-- = ADDONS INV ENDS HERE =
-- ============================
--

-- ============================
-- = MATERIALS INV ENDS HERE =
-- ============================
--

-- ==============================
-- = MATERIALS INV STARTS HERE =
-- ==============================
--

INSERT INTO inventory_item (
inv_category_id, item_name, added_by, unit, quantity, date_made, date_expiry
)
VALUES
(2, 'Cup G 22oz', 1, 'pcs', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(2, 'Cup M 16oz', 1, 'pcs', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(2, 'Hot Brew', 1, 'pcs', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(2, 'Straw', 1, 'pcs', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(2, 'Sealing Film', 1, 'pcs', 1000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(2, 'Domelid', 1, 'pcs', 2000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));

-- ============================
-- = MATERIALS INV ENDS HERE =
-- ============================
--





INSERT INTO inventory_item (
inv_category_id, item_name, added_by, category_id, unit, quantity, date_made, date_expiry
)
VALUES
(3, 'Tea', 1, 1, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)), -- 85g per 10 L.
(3, 'Coffee', 1, 1, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),-- around 200 g per 10 L.
(3, 'Fluctose', 1, 1, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
(3, 'Creamer', 1, 1, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));