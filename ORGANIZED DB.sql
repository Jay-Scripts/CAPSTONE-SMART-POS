	create database SMART_POS;

	USE SMART_POS;
    drop database smart_pos;
    

--       ==================================================================================================================================================================================================================================
--       =                                                                                                                                                                                                                                =
--       =                                                	                                             CREATE TABLES - STARTS HERE                                                                                                      =
--       =                                                                                                                                                                                                                                =
--       ==================================================================================================================================================================================================================================

--       = STAFF TABLE - STARTS HERE =
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

create table staff_logs(
logs_id int auto_increment primary key,
staff_id int not null,
login DATETIME DEFAULT CURRENT_TIMESTAMP,
logout DATETIME DEFAULT CURRENT_TIMESTAMP,
foreign key (staff_id) references staff_info(staff_id)
);
--       = STAFF TABLE - ENDS HERE   =





CREATE TABLE CUSTOMER_INFO (
    CUSTOMER_ID INT AUTO_INCREMENT PRIMARY KEY,     
    FIRST_NAME VARCHAR(100) NOT NULL,
    LAST_NAME VARCHAR(100) NOT NULL,
    EMAIL VARCHAR(100) UNIQUE,
    date_added DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE customer_account (
    cust_account_id INT AUTO_INCREMENT PRIMARY KEY,
    CUSTOMER_ID INT NOT NULL,
    password VARCHAR(255) NOT NULL,
    points DECIMAL(10,2) DEFAULT 0.00 CHECK (points >= 0),
    status ENUM('ACTIVE', 'INACTIVE') DEFAULT 'ACTIVE',
    created_by INT NOT NULL,  -- MANAGER WHO CREATED THE ACCOUNT
    date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (CUSTOMER_ID) REFERENCES CUSTOMER_INFO(CUSTOMER_ID) 
        ON DELETE CASCADE,
FOREIGN KEY (created_by) REFERENCES staff_info(staff_id) 
    ON DELETE RESTRICT
);

-- -- History / audit log of point changes
CREATE TABLE customer_points_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    cust_account_id INT NOT NULL,
    change_type ENUM('EARN', 'REDEEM') NOT NULL,
    points_changed DECIMAL(10,2) NOT NULL,
    balance_after DECIMAL(10,2) NOT NULL,
    remarks VARCHAR(255),
    transact_by INT not null,  -- staff_id who made the change (nullable if auto system)
    change_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cust_account_id) REFERENCES customer_account(cust_account_id)
        ON DELETE CASCADE,
    FOREIGN KEY (transact_by) REFERENCES staff_info(staff_id)
            ON UPDATE CASCADE 
    ON DELETE RESTRICT
); 

--        
--       ==========================================================================================================================================
--       =                                                       CUSTOMER TABLE - ENDS HERE                                                      =
--       ==========================================================================================================================================
--    

--          
--       ============================================================================================================================================
--       =                                                                 CATEGORY TABLE - STARTS HERE                                             =
--       ============================================================================================================================================
--    

CREATE TABLE category (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL UNIQUE,
    status ENUM('ACTIVE', 'INACTIVE') DEFAULT 'ACTIVE',
    date_added DATETIME DEFAULT CURRENT_TIMESTAMP
);
--          
--       ==========================================================================================================================================
--       =                                                            CATEGORY TABLE - ENDS HERE                                                  =
--       ==========================================================================================================================================
--    


--          
--       ==========================================================================================================================================
--       =                                                              PRODUCT TABLE - STARTS HERE                                               =
--       ==========================================================================================================================================
--    
	CREATE TABLE product_details (
		product_id INT AUTO_INCREMENT PRIMARY KEY,
		product_name VARCHAR(50)  ,
		category_id INT NOT NULL,
		thumbnail_path VARCHAR(150) NOT NULL,
		date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
		status ENUM('active', 'inactive') DEFAULT 'active',
		FOREIGN KEY (category_id) REFERENCES category(category_id) ON DELETE CASCADE
	);

	CREATE TABLE product_sizes (
		size_id INT AUTO_INCREMENT PRIMARY KEY,
		product_id INT NOT NULL,
		size ENUM('medio', 'grande', 'promo') DEFAULT 'medio',
		regular_price DECIMAL(6,2) DEFAULT 0.00,
		promo_price DECIMAL(6,2) DEFAULT 0.00,
		FOREIGN KEY (product_id) REFERENCES product_details(product_id) ON DELETE CASCADE
	);
    
            CREATE TABLE PRODUCT_ADD_ONS(
    ADD_ONS_ID INT AUTO_INCREMENT PRIMARY KEY,
    ADD_ONS_NAME VARCHAR(50) NOT NULL,
	  thumbnail_path VARCHAR(150) NOT NULL,
    PRICE  DECIMAL(6,2) DEFAULT 0.00,
	  date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
	  status ENUM('active', 'inactive') DEFAULT 'active',
    ); 
    
    CREATE TABLE PRODUCT_MODIFICATIONS(
    MODIFICATION_ID INT AUTO_INCREMENT PRIMARY KEY,
    MODIFICATION_NAME VARCHAR(50) NOT NULL,
	  thumbnail_path VARCHAR(150) NOT NULL,
	  date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
	  status ENUM('active', 'inactive') DEFAULT 'active',
    );


--          
--       ==========================================================================================================================================
--       =                                                        PRODUCT TABLE - ENDS HERE                                                       =
--       ==========================================================================================================================================
--    
--          
--       ================================================================================================================================================
--       =                                                     TRANSACTION TABLE - STARTS HERE                                                          =
--       ================================================================================================================================================
--    


 -- THIS WILL HOLD THE TRANSACTION SUMARY PER CUSTOMER 
      CREATE TABLE REG_TRANSACTION (
      REG_TRANSACTION_ID INT AUTO_INCREMENT PRIMARY KEY,
      cust_account_id INT NULL, -- CAN BE NULL FOR WAILK IN CUST AND WE THE CASHHIER WILL SCAN THE BREW REWARDS CARD QR TO STORE THE CUST ACCOUNT ID HERE, IF THEY HAVE ONE 
      STAFF_ID INT NOT NULL,
      product_id int null,
      PAYMENT_TYPE ENUM('CASH', 'CASHLESS') NOT NULL,
      ORDERED_BY ENUM('KIOSK', 'POS', 'REWARDS APP') NOT NULL,
      TOTAL_AMOUNT DECIMAL(6,2) DEFAULT 0.00,
      STATUS ENUM('PENDING', 'PAID', 'PREPARING', 'NOW SERVING', 'COMPLETED', 'REFUNDED', 'WASTE') NOT NULL,
      date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (cust_account_id) REFERENCES CUSTOMER_ACCOUNT(cust_account_id) ON DELETE SET NULL,
      FOREIGN KEY (product_id) REFERENCES product_details(product_id) ON DELETE SET NULL,
      FOREIGN KEY (STAFF_ID) REFERENCES STAFF_INFO(STAFF_ID) ON DELETE CASCADE
    );

    
--          
--       ===================================================================================================================================================
--       =                                                               PRODUCT Modification - starts HERE                                                =
--       ===================================================================================================================================================
--    


-- FOR EACH IDENTIFICATION OF PRODUCT INCASE WE HAVE 2 PRODUCT 1  WITH MODIFIACTION OR ADDONS WE CAN GRAB THE PREFERED PRODUCT THE CUSTOMER WANT TO BE ADJUSTED
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
    FOREIGN KEY(add_ons_id) REFERENCES product_add_ons(add_ons_id) ON DELETE CASCADE,
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
--          
--       =================================================================================================================================================
--       =                                                     PRODUCT Modification - ENDS HERE                                                          =
--       =================================================================================================================================================
--    







 -- THIS table will hold of  the discounted trans details
  CREATE TABLE DISC_TRANSACTION (
  DISC_TRANSACTION_ID INT AUTO_INCREMENT PRIMARY KEY,
  REG_TRANSACTION_ID INT NOT NULL,
  ID_TYPE ENUM ('PWD', 'SC') NOT NULL,
  ID_NUM INT NOT NULL,
  FIRST_NAME VARCHAR(50) NOT NULL, 
  LAST_NAME VARCHAR(50) NOT NULL, 
  DISC_TOTAL_AMOUNT  DECIMAL(6,2) DEFAULT 0.00,
  TRANSACTION_TIME DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(REG_TRANSACTION_ID) REFERENCES REG_TRANSACTION(REG_TRANSACTION_ID)
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
  FOREIGN KEY(REG_TRANSACTION_ID) REFERENCES REG_TRANSACTION(REG_TRANSACTION_ID)
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
  FOREIGN KEY (REG_TRANSACTION_ID) REFERENCES REG_TRANSACTION(REG_TRANSACTION_ID)
);

--          
--       ==============================================================================================================================================
--       =                                                          TRANSACTION TABLE - ENDS HERE                                                     =
--       ==============================================================================================================================================
--    


--       ==================================================================================================================================================================================================================================
--       =                                                                                                                                                                                                                                =
--       =                                                	                                             CREATE TABLES - Ends HERE                                                                                                         =
--       =                                                                                                                                                                                                                                =
--       ==================================================================================================================================================================================================================================







--       ==================================================================================================================================================================================================================================
--       =                                                                                                                                                                                                                                =
--       =                                                	                                             INSERTION OF TABLES DATA - STARTS HERE                                                                                           =
--       =                                                                                                                                                                                                                                =
--       ==================================================================================================================================================================================================================================

--          
--       ==========================================================================================================================================
--       =                                                             STAFF TABLE - STARTS HERE                                                  =
--       ==========================================================================================================================================
--    
insert INTO STAFF_INFO(STAFF_NAME) 
VALUES
('JDM')
;

insert into staff_roles(staff_id, role)
values
(1, 'BARISTA'),
(1, 'CASHIER')
;

SELECT si.staff_name, sr.role
FROM staff_info si
INNER JOIN staff_roles sr
  ON si.staff_id = sr.staff_id
WHERE si.staff_id = 1;

--          
--       ==========================================================================================================================================
--       =                                                             STAFF TABLE - Ends HERE                                                  =
--       ==========================================================================================================================================
--    



--          
--       =============================================================================================================================================
--       =                                                        CATEGORY TABLE - STARTS HERE                                                       =
--       =============================================================================================================================================
--    

	INSERT INTO category (category_name) 
	VALUES 
	('MILK TEA'),  -- DONE
	('FRUIT TEA'), -- DONE
	('HOT BREW'),  -- DONE
	('PRAF'), -- DONE
	('BROSTY'), -- DONE
	('ICED COFFEE'), -- DONE
	('PROMOS'); -- DONE

--          
--       ===========================================================================================================================================
--       =                                                     CATEGORY TABLE - ENDS HERE                                                          =
--       ===========================================================================================================================================
--    

--          
--       =============================================================================================================================================
--       =                                                      PRODUCT TABLE - STARTS HERE                                                          =
--       =============================================================================================================================================
--    
	--    
	--       =======================
	--       = MILKTEA STARTS HERE =
	--       =======================
	--    
	INSERT INTO product_details (product_name, category_id, thumbnail_path)
	VALUES
	  ('Winter  ', 1, '../assets/IMAGES/MENU IMAGES/MILKTEA_MENU/winter melon.png'),
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
--       =======================
--       =  MILKTEA ENDS HERE  =
--       =======================
--    

--    
--       =========================
--       = FRUIT TEA STARTS HERE =
--       =========================
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
--       =======================
--       = FRUIT TEA ENDS HERE =
--       =======================
--    

--    
--       =============================
--       = ICED	COFEE TEA START HERE =
--       =============================
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
  (19, 'medio', 49.00),
  (20, 'medio', 49.00),
  (21, 'medio', 49.00),
  (22, 'medio', 49.00);

-- For grande (₱49.00)
  INSERT INTO product_sizes (product_id, size, regular_price)
VALUES
  (19, 'grande', 39.00),
  (20, 'grande', 39.00),
  (21, 'grande', 39.00),
  (22, 'grande', 39.00);

--    
--       ============================
--       = ICED	COFEE TEA ENDS HERE =
--       ============================
--    

--    
--       ====================
--       = PRAF STARTS HERE =
--       ====================
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
--       ==================
--       = PRAF ENDS HERE =
--       ==================
--    



--    
--       ======================
--       = BROSTY STARTS HERE =
--       ======================
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
--       ====================
--       = BROSTY ENDS HERE =
--       ====================
--    


--    
--       ========================
--       = HOT BREW STARTS HERE =
--       ========================
--    

INSERT INTO product_details (product_name, category_id, thumbnail_path)
VALUES

  ('HOT BRUSKO', 3, '../assets/IMAGES/MENU IMAGES/HOT_BREW/HOT BRUSKO.png'),
  ('HOT CHOCO', 3, '../assets/IMAGES/MENU IMAGES/HOT_BREW/HOT CHOCO.png'),
  ('HOT MOCA', 3, '../assets/IMAGES/MENU IMAGES/HOT_BREW/HOT MOCA.png'),
  ('HOT MATCHA', 3, '../assets/IMAGES/MENU IMAGES/HOT_BREW/HOT MATCHA.png'),
  ('HOT KARAMEL', 3, '../assets/IMAGES/MENU IMAGES/HOT_BREW/HOT KARAMEL.png');

-- For medio (₱39.00)  
  INSERT INTO product_sizes (product_id, size, regular_price)
VALUES
  (44, 'medio', 39.00),
  (45, 'medio', 39.00),
  (46, 'medio', 39.00),
  (47, 'medio', 39.00),
  (48, 'medio', 39.00);

-- For grande (₱49.00)
  INSERT INTO product_sizes (product_id, size, regular_price)
VALUES
  (44, 'grande', 39.00),
  (45, 'grande', 39.00),
  (46, 'grande', 39.00),
  (47, 'grande', 39.00),
  (48, 'grande', 39.00);
--   
--       ======================
--       = HOT BREW ENDS HERE =
--       ======================
--    

--    
--       ========================
--       = PROMO STARTS HERE    =
--       ========================
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
--       ======================
--       = PROMO ENDS HERE    =
--       ======================
--    


--          
--       ===========================================================================================================================================
--       =                                                             PRODUCT TABLE - ENDS HERE                                                   =
--       ===========================================================================================================================================
--    

--       ==================================================================================================================================================================================================================================
--       =                                                                                                                                                                                                                                =
--       =                                                	                                             INSERTION OF TABLES DATA - Ends HERE                                                                                             =
--       =                                                                                                                                                                                                                                =
--       ==================================================================================================================================================================================================================================





--          
--       ==================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================
--       =                                                                                                                                        =
--       =                                          SELECT STATEMENTS FOR POS - STARTS HERE                                                       =
--       =                                                                                                                                        =
--       ==================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================
--    

--    
--       ===========================================================================================================================================================
--       =  FOR MILKTEA MENU ORDER FETCHING STARTS HERE  =
--       ===========================================================================================================================================================
--    
SELECT pd.product_id, pd.product_name, pd.thumbnail_path, ps.size, ps.regular_price
            FROM product_details pd
            JOIN product_sizes ps ON pd.product_id = ps.product_id
            WHERE pd.category_id = 1 -- EQUAL TO MILKTEA
            AND pd.status = 'active'
			AND ps.size = 'medio' -- SET THIS TO GRANDE FOR GRANDE SIZE 
            ORDER BY pd.product_name ASC;

--    
--       =========================================================================================================================================================
--       =  FOR MILKTEA MENU ORDER FETCHING ENDS HERE  =
--       =========================================================================================================================================================
--    

--    
--       =============================================================================================================================================================
--       =  FOR FRUIT TEA MENU ORDER FETCHING STARTS HERE  =
--       =============================================================================================================================================================
--    
SELECT pd.product_id, pd.product_name, pd.thumbnail_path, ps.size, ps.regular_price
            FROM product_details pd
            JOIN product_sizes ps ON pd.product_id = ps.product_id
            WHERE pd.category_id = 2 -- EQUAL TO FRUIT TEA
            AND pd.status = 'active'
			AND ps.size = 'medio' -- SET THIS TO GRANDE FOR GRANDE SIZE 
            ORDER BY pd.product_name ASC;

--    
--       ===========================================================================================================================================================
--       =  FOR FRUIT TEA MENU ORDER FETCHING ENDS HERE  =
--       ===========================================================================================================================================================
--    

--    
--       =============================================================================================================================================================
--       =   FOR HOT BREW MENU ORDER FETCHING STARTS HERE  =
--       =============================================================================================================================================================
--    
SELECT pd.product_id, pd.product_name, pd.thumbnail_path, ps.size, ps.regular_price
            FROM product_details pd
            JOIN product_sizes ps ON pd.product_id = ps.product_id
            WHERE pd.category_id = 3 -- EQUAL TO HOT BREW
            AND pd.status = 'active'
			AND ps.size = 'medio' -- SET THIS TO GRANDE FOR GRANDE SIZE 
            ORDER BY pd.product_name ASC;

--    
--       ===========================================================================================================================================================
--       =   FOR HOT BREW MENU ORDER FETCHING ENDS HERE  =
--       ===========================================================================================================================================================
--    
--    
--       =============================================================================================================================================================
--       =  FOR PRAF MENU ORDER FETCHING STARTS HERE       =
--       =============================================================================================================================================================
--    
SELECT pd.product_id, pd.product_name, pd.thumbnail_path, ps.size, ps.regular_price
            FROM product_details pd
            JOIN product_sizes ps ON pd.product_id = ps.product_id
            WHERE pd.category_id = 4 -- EQUAL TO PRAF
            AND pd.status = 'active'
			AND ps.size = 'medio' -- SET THIS TO GRANDE FOR GRANDE SIZE 
            ORDER BY pd.product_name ASC;

--    
--       ===========================================================================================================================================================
--       =  FOR PRAF MENU ORDER FETCHING ENDS HERE       =
--       ===========================================================================================================================================================
--    

--    
--       =============================================================================================================================================================
--       =  FOR BROSTY MENU ORDER FETCHING STARTS HERE    =
--       =============================================================================================================================================================
--    
SELECT pd.product_id, pd.product_name, pd.thumbnail_path, ps.size, ps.regular_price
            FROM product_details pd
            JOIN product_sizes ps ON pd.product_id = ps.product_id
            WHERE pd.category_id = 5 -- EQUAL TO BROSTY
            AND pd.status = 'active'
			AND ps.size = 'medio' -- SET THIS TO GRANDE FOR GRANDE SIZE 
            ORDER BY pd.product_name ASC;

--    
--       ===========================================================================================================================================================
--       =  FOR BROSTY MENU ORDER FETCHING ENDS HERE     =
--       ===========================================================================================================================================================
--    

--    
--       =============================================================================================================================================================
--       = FOR ICED COFFEE MENU ORDER FETCHING STARTS HERE =
--       =============================================================================================================================================================
--    
SELECT pd.product_id, pd.product_name, pd.thumbnail_path, ps.size, ps.regular_price
            FROM product_details pd
            JOIN product_sizes ps ON pd.product_id = ps.product_id
            WHERE pd.category_id = 6 -- EQUAL TO ICED COFFEE
            AND pd.status = 'active'
			AND ps.size = 'medio' -- SET THIS TO GRANDE FOR GRANDE SIZE 
            ORDER BY pd.product_name ASC;

--    
--       =============================================================================================================================================================
--       =  FOR ICED COFFEE MENU ORDER FETCHING ENDS HERE  =
--       =============================================================================================================================================================
--    

--    
--       =============================================================================================================================================================
--       =  FOR PROMO MENU ORDER FETCHING STARTS HERE      =
--       =============================================================================================================================================================
--    
SELECT pd.product_id, pd.product_name, pd.thumbnail_path, ps.size, ps.regular_price
            FROM product_details pd
            JOIN product_sizes ps ON pd.product_id = ps.product_id
            WHERE pd.category_id = 7 -- EQUAL TO PROMO
            AND pd.status = 'active'
			AND ps.size = 'medio' -- SET THIS TO GRANDE FOR GRANDE SIZE 
            ORDER BY pd.product_name ASC;
--    
--       ===========================================================================================================================================================
--       =  FOR PROMO MENU ORDER FETCHING ENDS HERE      =
--       ===========================================================================================================================================================
--    
-- for staff viewing of roles
SELECT si.staff_name, sr.role
FROM staff_info si
INNER JOIN staff_roles sr
  ON si.staff_id = sr.staff_id

    --          
--       ==================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================
--       =                                                                                                                                        =
--       =                                            SELECT STATEMENTS FOR POS - ENDS HERE                                                       =
--       =                                                                                                                                        =
--       ==================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================
--    
