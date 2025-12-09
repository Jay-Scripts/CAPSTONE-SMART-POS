
      

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

CREATE TABLE staff_logs (
  logs_id INT AUTO_INCREMENT PRIMARY KEY,
  staff_id INT NOT NULL,
  log_type ENUM('IN', 'OUT') DEFAULT 'OUT',
  log_time DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (staff_id) REFERENCES staff_info(staff_id)
);


  --       = STAFF TABLE - ENDS HERE   =


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
      PRICE  DECIMAL(6,2) DEFAULT 0.00,
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


  --          
  --       ==========================================================================================================================================
  --       =                                                        PRODUCT TABLE - ENDS HERE                                                       =
  --       ==========================================================================================================================================
  --    



  --          
  --       ================================================================================================================================================
  --       =                                                     Kiosk - STARTS HERE                                                                      =
  --       ================================================================================================================================================
  --    

-- ========================================================================
--                    MAIN KIOSK TRANSACTION TABLE                        =
-- ========================================================================
CREATE TABLE kiosk_transaction (
  kiosk_transaction_id INT AUTO_INCREMENT PRIMARY KEY,
  total_amount DECIMAL(10,2) DEFAULT 0.00,
  vat_amount DECIMAL(10,2) DEFAULT 0.00,
 status ENUM('PENDING', 'PAID', 'VOID') DEFAULT 'PENDING',
  date_added DATETIME DEFAULT CURRENT_TIMESTAMP
);


-- ========================================================================
--                      EACH PRODUCT PER TRANSACTION                      =
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
--              PRODUCT ADD-ONS (like pearl, extra cheese, etc.)          =
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
--            PRODUCT MODIFICATIONS (like less sugar, no ice, etc.)       =
-- ========================================================================
CREATE TABLE kiosk_item_modification (
  kiosk_item_modification_id INT AUTO_INCREMENT PRIMARY KEY,
  kiosk_item_id INT NOT NULL,
  modification_id INT NOT NULL,
  date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (kiosk_item_id) REFERENCES kiosk_transaction_item(item_id) ON DELETE CASCADE,
  FOREIGN KEY (modification_id) REFERENCES product_modifications(modification_id) ON DELETE CASCADE
);


  --          
  --       ================================================================================================================================================
  --       =                                                     Kiosk - Ends HERE                                                          =
  --       ================================================================================================================================================
  --    
  --          
  --       ================================================================================================================================================
  --       =                                                     TRANSACTION TABLE - STARTS HERE                                                          =
  --       ================================================================================================================================================
  --    


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
    FOREIGN KEY(REG_TRANSACTION_ID) REFERENCES REG_TRANSACTION(REG_TRANSACTION_ID) ON DELETE CASCADE
  );

CREATE TABLE EPAYMENT_TRANSACTION (
    EPAY_TRANSACTION_ID INT AUTO_INCREMENT PRIMARY KEY,
    REG_TRANSACTION_ID INT NOT NULL,
    AMOUNT DECIMAL(6,2) NOT NULL,
    REFERENCES_NUM INT NOT NULL,  -- added column
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

  --          
  --       ================================================================================================================================================
  --       =                                                     Inventory Tables - STARTS HERE                                                          =
  --       ================================================================================================================================================
  --    


-- ========================================================================
--                      EACH CATEGORIES OF INVENTORY ITEMS                =
-- ========================================================================
  CREATE TABLE inventory_category (
    inv_category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL UNIQUE,
    date_added DATETIME DEFAULT CURRENT_TIMESTAMP
  );


-- ========================================================================
--                      EACH INVENTORY ITEMS PER CATEGORIES               =
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
   status ENUM('IN STOCK', 'LOW STOCK', 'OUT OF STOCK', 'UNAVAILABLE') DEFAULT 'IN STOCK',
    date_made DATE NOT NULL,
    date_expiry DATE NOT NULL,
    expiry_status ENUM('FRESH', 'SOON TO EXPIRE', 'EXPIRED' , 'UNAVAILABLE') DEFAULT "FRESH",
    date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (inv_category_id) REFERENCES inventory_category(inv_category_id) ON DELETE CASCADE,
    FOREIGN KEY (added_by) REFERENCES staff_info(staff_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product_details(product_id) ON DELETE CASCADE,
    foreign key (category_id) references category(category_id) on DELETE cascade
  );

-- ========================================================================
--                      FOR ACCOUNTABILITY IN INVENTORY ADJUSTMENTS       =
-- ========================================================================
CREATE TABLE inventory_item_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    staff_id INT NOT NULL, -- who performed the action
    action_type ENUM('RESTOCK', 'ADJUSTMENT', 'EXPIRED', 'DAMAGED', 'INVENTORY') NOT NULL,
    last_quantity DECIMAL(10,2) NOT NULL,     -- 🔹 Quantity before the action
    quantity_adjusted DECIMAL(10,2) NOT NULL, -- 🔹 Quantity added or removed
    total_after DECIMAL(10,2) NOT NULL,       -- 🔹 Quantity after the action
    remarks VARCHAR(255) NULL,
    date_logged DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES inventory_item(item_id) ON DELETE CASCADE,
    FOREIGN KEY (staff_id) REFERENCES staff_info(staff_id) ON DELETE CASCADE
);

-- ========================================================================
--                      EACH PRODUCT PER Deductions                       =
-- ========================================================================



  --          
  --       ================================================================================================================================================
  --       =                                                     Inventory Tables - Ends HERE                                                          =
  --       ================================================================================================================================================
  --    


-- ========================================================================
--                      CUSTOMER FEEDBACK / SURVEY TABLE
-- ========================================================================
CREATE TABLE customer_feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    reg_transaction_id INT NOT NULL,       -- Link to a specific transaction
    staff_attitude TINYINT NOT NULL,       -- Rating 1-5
    product_accuracy TINYINT NOT NULL,     -- Rating 1-5
    cleanliness TINYINT NOT NULL,          -- Rating 1-5
    speed_of_service TINYINT NOT NULL,     -- Rating 1-5
    overall_satisfaction TINYINT NOT NULL, -- Rating 1-5
    feedback_text TEXT NULL,               -- Optional feedback
    date_submitted DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reg_transaction_id) REFERENCES REG_TRANSACTION(REG_TRANSACTION_ID) 
        ON DELETE CASCADE
);




  CREATE TABLE product_ingredient_ratio ( 
    ratio_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity_needed DECIMAL(10,2) NOT NULL, -- ratio per serving
    FOREIGN KEY (product_id) REFERENCES product_details(product_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES inventory_item(item_id) ON DELETE CASCADE
  );