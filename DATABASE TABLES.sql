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
    PRICE  DECIMAL(6,2) DEFAULT 0.00,
	  date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
	  status ENUM('active', 'inactive') DEFAULT 'active'
    ); 
    
    CREATE TABLE PRODUCT_MODIFICATIONS(
    MODIFICATION_ID INT AUTO_INCREMENT PRIMARY KEY,
    MODIFICATION_NAME VARCHAR(50) NOT NULL,
	  date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
	  status ENUM('active', 'inactive') DEFAULT 'active'
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
      product_id int not null,
      ORDERED_BY ENUM('KIOSK', 'POS', 'REWARDS APP') DEFAULT 'POS',
      TOTAL_AMOUNT DECIMAL(6,2) DEFAULT 0.00,
      VAT_AMOUNT DECIMAL(6,2) NOT NULL DEFAULT 0.00,
      STATUS ENUM('PENDING', 'PAID', 'WAITING', 'PREPARING', 'NOW SERVING', 'COMPLETED', 'REFUNDED', 'WASTE') DEFAULT 'PENDING',
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
    CREATE TABLE PAYMENT_METHODS(
  PAYMENT_ID INT AUTO_INCREMENT PRIMARY KEY,
  REG_TRANSACTION_ID INT NOT NULL,
  TYPE ENUM ('CASH', 'E-PAYMENT') DEFAULT 'CASH',
  AMOUNT_SENT DECIMAL(6,2) DEFAULT 0.00,
  CHANGE_AMOUNT DECIMAL(6,2) DEFAULT 0.00,
  FOREIGN KEY (REG_TRANSACTION_ID) REFERENCES REG_TRANSACTION(REG_TRANSACTION_ID) ON DELETE CASCADE
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





