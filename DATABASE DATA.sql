


--       ==================================================================================================================================================================================================================================
--       =                                                	                                             INSERTION OF TABLES DATA - STARTS HERE                                                                                           =
--       ==================================================================================================================================================================================================================================

--          
--       ==========================================================================================================================================
--       =                                                             STAFF TABLE - STARTS HERE                                                  =
--       ==========================================================================================================================================
--    
insert INTO STAFF_INFO(STAFF_NAME) 
VALUES
('Bautista'),
('Montanez'),
('Tolentino'),
('Lastra'),
('Gatbonton')
;

insert into staff_roles(staff_id, role)
values
(1, 'MANAGER'), -- KIAN
(2, 'BARISTA'), -- JOSH
(3, 'CASHIER'), -- JEKOY
(4, 'CASHIER'), -- REIGN
(5, 'MANAGER') -- CORNETO
;

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
	('PROMOS'), -- DONE
	('ADD-ONS'); -- DONE

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

-- For grande (₱49.00)
  INSERT INTO product_sizes (product_id, size, regular_price)
VALUES
  (44, 'medio', 39.00),
  (45, 'medio', 39.00),
  (46, 'medio', 39.00),
  (47, 'medio', 39.00),
  (48, 'medio', 39.00);
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
--       ==========================
--       = ADD-ONS STARTS HERE    =
--       ==========================
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
--       ======================
--       = ADD-ONS ENDS HERE  =
--       ======================
--    



--   
--       ==============================
--       = MODIFICATIONS STARTS HERE  =
--       ==============================
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


--   
--       ============================
--       = MODIFICATIONS ENDS HERE  =
--       ============================
--    
--          
--       ===========================================================================================================================================
--       =                                                             PRODUCT TABLE - ENDS HERE                                                   =
--       ===========================================================================================================================================
--    



--       ==================================================================================================================================================================================================================================
--       =                                                	                                             INSERTION OF TABLES DATA - Ends HERE                                                                                             =
--       ==================================================================================================================================================================================================================================


INSERT INTO inventory_category (category_name)
VALUES
('Ingredients'),
('Materials'),
('Base');


--       ==================================================================================================================================================================================================================================
--       =                                                	                                             INSERTION OF Product Ingredients - Starts HERE                                                                                             =
--       ==================================================================================================================================================================================================================================


--       ============================
--       = MILKTEA INV START HERE  =
--       ============================
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


--       ============================
--       = MILKTEA INV ENDS HERE  =
--       ============================
--  



--       ============================
--       = FRUIT TEA INV STARTS HERE  =
--       ============================
--  
INSERT INTO inventory_item (
    inv_category_id, item_name, added_by, product_id, category_id, unit, quantity, date_made, date_expiry
)
VALUES
    (1, 'Green Apple Syrup', 1, 13, 2, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Kiwi Syrup', 1, 14, 2, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Lemon Syrup', 1, 15, 2, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Passion Fruit Syrup', 1, 16, 2, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Strawberry Syrup', 1, 17, 2, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Watermelon Syrup', 1, 18, 2, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));
--       ============================
--       = FRUIT TEA INV ENDS HERE  =
--       ============================
--  


--       ============================
--       = ICED COFFEE INV STARTS HERE  =
--       ============================
--  
INSERT INTO inventory_item (
    inv_category_id, item_name, added_by, product_id, category_id, unit, quantity, date_made, date_expiry
)
VALUES
    (1, 'Kape Brusko Syrup', 1, 19, 6, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Kape Karamel Syrup', 1, 20, 6, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Kape Macch Syrup', 1, 21, 6, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Kape Vanilla Syrup', 1, 22, 6, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));
--       ============================
--       = ICED COFFEE INV ENDS HERE  =
--       ============================
--  

--       ============================
--       = PRAF INV STARRT HERE  =
--       ============================
--  
INSERT INTO inventory_item (
    inv_category_id, item_name, added_by, product_id, category_id, unit, quantity, date_made, date_expiry
)
VALUES
    (1, 'Caramel Matcch Syrup', 1, 23, 4, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Cheese Cake Syrup', 1, 24, 4, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Choco Cream Syrup', 1, 25, 4, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Coffee Jelly Syrup', 1, 26, 4, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Cookies & Cream Syrup', 1, 27, 4, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Creamy Avocado Syrup', 1, 28, 4, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Matcha Syrup', 1, 29, 4, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Melon Syrup', 1, 30, 4, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Mocha Syrup', 1, 31, 4, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Strawberry Syrup', 1, 32, 4, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Vanilla Coffee Syrup', 1, 33, 4, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));
--       ============================
--       = PRAF INV ENDS HERE  =
--       ============================
--  

--       ============================
--       = BROSTY INV STARTS HERE  =
--       ============================
--  

INSERT INTO inventory_item (
    inv_category_id, item_name, added_by, product_id, category_id, unit, quantity, date_made, date_expiry
)
VALUES
    (1, 'Blue Berry Syrup', 1, 33, 5, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Green Apple Syrup', 1, 34, 5, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Honey Peach Syrup', 1, 35, 5, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Kiwi Syrup', 1, 36, 5, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Lemon Syrup', 1, 37, 5, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Lychee Syrup', 1, 38, 5, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Mango Syrup', 1, 39, 5, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Passion Fruit Syrup', 1, 40, 5, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Strawberry Syrup', 1, 41, 5, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Watermelon Syrup', 1, 42, 5, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));
--       ============================
--       = BROSTY INV ENDS HERE  =
--       ============================
--  

--       ============================
--       = HOT BREW INV START HERE  =
--       ============================
--  
INSERT INTO inventory_item (
    inv_category_id, item_name, added_by, product_id, category_id, unit, quantity, date_made, date_expiry
)
VALUES
    (1, 'Hot Brusko Syrup', 1, 44, 3, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Hot Choco Syrup', 1, 45, 3, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Hot Moca Syrup', 1, 46, 3, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Hot Matcha Syrup', 1, 47, 3, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Hot Karamel Syrup', 1, 48, 3, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));
--       ============================
--       = HOT BREW INV ENDS HERE  =
--       ============================
--

--       ============================
--       = PROMOS INV START HERE  =
--       ============================
--
INSERT INTO inventory_item (
    inv_category_id, item_name, added_by, product_id, category_id, unit, quantity, date_made, date_expiry
)
VALUES
    (1, 'Blackpink Syrup', 1, 49, 7, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Boss Brew Syrup', 1, 50, 7, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Super Choco Syrup', 1, 51, 7, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Kape KMJS Syrup', 1, 52, 7, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Kara Van Syrup', 1, 53, 7, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Supreme Moca Syrup', 1, 54, 7, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));
--       ============================
--       = PROMOS INV ENDS HERE  =
--       ============================
--


--       ============================
--       = ADDONS INV STARTS HERE  =
--       ============================
--
INSERT INTO inventory_item (
    inv_category_id, item_name, added_by, product_id, category_id, unit, quantity, date_made, date_expiry
)
VALUES
    (1, 'Cheese Cake AddOn', 1, NULL, 8, 'g', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Pearl AddOn', 1, NULL, 8, 'g', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Cream Cheese AddOn', 1, NULL, 8, 'g', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Coffee Jelly AddOn', 1, NULL, 8, 'g', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Crushed Oreo AddOn', 1, NULL, 8, 'g', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (1, 'Chia Seed AddOn', 1, NULL, 8, 'g', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
      (1, 'Crystal', 1, NULL, 8, 'g', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
          (1, 'Cream Puff', 1, NULL, 8, 'g', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));
--       ============================
--       = ADDONS INV ENDS HERE  =
--       ============================
--

--       ============================
--       = MATERIALS INV ENDS HERE  =
--       ============================
--

--       ==============================
--       = MATERIALS INV STARTS HERE  =
--       ==============================
--

INSERT INTO inventory_item (
    inv_category_id, item_name, added_by, unit, quantity, date_made, date_expiry
)
VALUES
    (2, 'Cup G 22oz', 1, 'pcs', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (2, 'Cup M 16oz', 1, 'pcs', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (2, 'Hot Brew', 1, 'pcs', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (2, 'Sealing Film', 1, 'pcs', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (2, 'Domelid', 1, 'pcs', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)),
    (2, 'Straw', 1, 'pcs', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));

--       ============================
--       = MATERIALS INV ENDS HERE  =
--       ============================
--





    INSERT INTO inventory_item (
    inv_category_id, item_name, added_by, category_id, unit, quantity, date_made, date_expiry
)
VALUES
    (3, 'Tea', 1, 1, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR)), -- 85g per 10 L.
    (3, 'Coffee', 1, 1, 'ml', 5000, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 1 YEAR));


-- ============================


--       ==================================================================================================================================================================================================================================
--       =                                                	                                             INSERTION OF Product Ingredients - Ends HERE                                                                                             =
--       ==================================================================================================================================================================================================================================

--       ==================================================================================================================================================================================================================================
--       =                                                	                                             INSERTION OF Materials Inventory - Starts HERE                                                                                             =
--       ==================================================================================================================================================================================================================================

--       ==================================================================================================================================================================================================================================
--       =                                                	                                             INSERTION OF Materials Inventory - Ends HERE                                                                                             =
--       ==================================================================================================================================================================================================================================


-- =========================
-- = MILK TEA INGREDIENT RATIOS =
-- =========================

-- MEDIO SIZE
INSERT INTO product_ingredient_ratio (product_id, ingredient_name, ingredient_ratio, size)
VALUES
    -- Winter
    (1, 'tea', 250, 'medio'),
    (1, 'wintermelon syrup', 40, 'medio'),
    (1, 'pearl', 10, 'medio'),
    (1, 'cup', 1, 'medio'),
    (1, 'sealing film', 1, 'medio'),
    (1, 'straw', 1, 'medio'),

    -- Taro
    (2, 'tea', 250, 'medio'),
    (2, 'taro syrup', 40, 'medio'),
    (2, 'pearl', 10, 'medio'),
    (2, 'cup', 1, 'medio'),
    (2, 'sealing film', 1, 'medio'),
    (2, 'straw', 1, 'medio'),

    -- Strawberry
    (3, 'tea', 250, 'medio'),
    (3, 'strawberry syrup', 40, 'medio'),
    (3, 'pearl', 10, 'medio'),
    (3, 'cup', 1, 'medio'),
    (3, 'sealing film', 1, 'medio'),
    (3, 'straw', 1, 'medio'),

    -- Salted Caramel
    (4, 'tea', 250, 'medio'),
    (4, 'salted caramel syrup', 40, 'medio'),
    (4, 'pearl', 10, 'medio'),
    (4, 'cup', 1, 'medio'),
    (4, 'sealing film', 1, 'medio'),
    (4, 'straw', 1, 'medio'),

    -- Red Velvet
    (5, 'tea', 250, 'medio'),
    (5, 'red velvet syrup', 40, 'medio'),
    (5, 'pearl', 10, 'medio'),
    (5, 'cup', 1, 'medio'),
    (5, 'sealing film', 1, 'medio'),
    (5, 'straw', 1, 'medio'),

    -- Matcha
    (6, 'tea', 250, 'medio'),
    (6, 'matcha syrup', 40, 'medio'),
    (6, 'pearl', 10, 'medio'),
    (6, 'cup', 1, 'medio'),
    (6, 'sealing film', 1, 'medio'),
    (6, 'straw', 1, 'medio'),

    -- Double Dutch
    (7, 'tea', 250, 'medio'),
    (7, 'double dutch syrup', 40, 'medio'),
    (7, 'pearl', 10, 'medio'),
    (7, 'cup', 1, 'medio'),
    (7, 'sealing film', 1, 'medio'),
    (7, 'straw', 1, 'medio'),

    -- Dark Choco
    (8, 'tea', 250, 'medio'),
    (8, 'dark choco syrup', 40, 'medio'),
    (8, 'pearl', 10, 'medio'),
    (8, 'cup', 1, 'medio'),
    (8, 'sealing film', 1, 'medio'),
    (8, 'straw', 1, 'medio'),

    -- Choco Hazelnut
    (9, 'tea', 250, 'medio'),
    (9, 'choco hazelnut syrup', 40, 'medio'),
    (9, 'pearl', 10, 'medio'),
    (9, 'cup', 1, 'medio'),
    (9, 'sealing film', 1, 'medio'),
    (9, 'straw', 1, 'medio'),

    -- Cookies & Cream
    (10, 'tea', 250, 'medio'),
    (10, 'cookies & cream syrup', 40, 'medio'),
    (10, 'pearl', 10, 'medio'),
    (10, 'cup', 1, 'medio'),
    (10, 'sealing film', 1, 'medio'),
    (10, 'straw', 1, 'medio'),

    -- Choco Kisses
    (11, 'tea', 250, 'medio'),
    (11, 'choco kisses syrup', 40, 'medio'),
    (11, 'pearl', 10, 'medio'),
    (11, 'cup', 1, 'medio'),
    (11, 'sealing film', 1, 'medio'),
    (11, 'straw', 1, 'medio'),

    -- Brown Sugar
    (12, 'tea', 250, 'medio'),
    (12, 'brown sugar syrup', 40, 'medio'),
    (12, 'pearl', 10, 'medio'),
    (12, 'cup', 1, 'medio'),
    (12, 'sealing film', 1, 'medio'),
    (12, 'straw', 1, 'medio');

-- GRANDE SIZE
INSERT INTO product_ingredient_ratio (product_id, ingredient_name, ingredient_ratio, size)
VALUES
    -- Winter
    (1, 'tea', 350, 'grande'),
    (1, 'wintermelon syrup', 80, 'grande'),
    (1, 'pearl', 15, 'grande'),
    (1, 'cup', 1, 'grande'),
    (1, 'sealing film', 1, 'grande'),
    (1, 'straw', 1, 'grande'),

    -- Taro
    (2, 'tea', 350, 'grande'),
    (2, 'taro syrup', 80, 'grande'),
    (2, 'pearl', 15, 'grande'),
    (2, 'cup', 1, 'grande'),
    (2, 'sealing film', 1, 'grande'),
    (2, 'straw', 1, 'grande'),

    -- Strawberry
    (3, 'tea', 350, 'grande'),
    (3, 'strawberry syrup', 80, 'grande'),
    (3, 'pearl', 15, 'grande'),
    (3, 'cup', 1, 'grande'),
    (3, 'sealing film', 1, 'grande'),
    (3, 'straw', 1, 'grande'),

    -- Salted Caramel
    (4, 'tea', 350, 'grande'),
    (4, 'salted caramel syrup', 80, 'grande'),
    (4, 'pearl', 15, 'grande'),
    (4, 'cup', 1, 'grande'),
    (4, 'sealing film', 1, 'grande'),
    (4, 'straw', 1, 'grande'),

    -- Red Velvet
    (5, 'tea', 350, 'grande'),
    (5, 'red velvet syrup', 80, 'grande'),
    (5, 'pearl', 15, 'grande'),
    (5, 'cup', 1, 'grande'),
    (5, 'sealing film', 1, 'grande'),
    (5, 'straw', 1, 'grande'),

    -- Matcha
    (6, 'tea', 350, 'grande'),
    (6, 'matcha syrup', 80, 'grande'),
    (6, 'pearl', 15, 'grande'),
    (6, 'cup', 1, 'grande'),
    (6, 'sealing film', 1, 'grande'),
    (6, 'straw', 1, 'grande'),

    -- Double Dutch
    (7, 'tea', 350, 'grande'),
    (7, 'double dutch syrup', 80, 'grande'),
    (7, 'pearl', 15, 'grande'),
    (7, 'cup', 1, 'grande'),
    (7, 'sealing film', 1, 'grande'),
    (7, 'straw', 1, 'grande'),

    -- Dark Choco
    (8, 'tea', 350, 'grande'),
    (8, 'dark choco syrup', 80, 'grande'),
    (8, 'pearl', 15, 'grande'),
    (8, 'cup', 1, 'grande'),
    (8, 'sealing film', 1, 'grande'),
    (8, 'straw', 1, 'grande'),

    -- Choco Hazelnut
    (9, 'tea', 350, 'grande'),
    (9, 'choco hazelnut syrup', 80, 'grande'),
    (9, 'pearl', 15, 'grande'),
    (9, 'cup', 1, 'grande'),
    (9, 'sealing film', 1, 'grande'),
    (9, 'straw', 1, 'grande'),

    -- Cookies & Cream
    (10, 'tea', 350, 'grande'),
    (10, 'cookies & cream syrup', 80, 'grande'),
    (10, 'pearl', 15, 'grande'),
    (10, 'cup', 1, 'grande'),
    (10, 'sealing film', 1, 'grande'),
    (10, 'straw', 1, 'grande'),

    -- Choco Kisses
    (11, 'tea', 350, 'grande'),
    (11, 'choco kisses syrup', 80, 'grande'),
    (11, 'pearl', 15, 'grande'),
    (11, 'cup', 1, 'grande'),
    (11, 'sealing film', 1, 'grande'),
    (11, 'straw', 1, 'grande'),

    -- Brown Sugar
    (12, 'tea', 350, 'grande'),
    (12, 'brown sugar syrup', 80, 'grande'),
    (12, 'pearl', 15, 'grande'),
    (12, 'cup', 1, 'grande'),
    (12, 'sealing film', 1, 'grande'),
    (12, 'straw', 1, 'grande');


-- =========================
-- = FRUIT TEA INGREDIENT RATIOS =
-- =========================

-- MEDIO SIZE
INSERT INTO product_ingredient_ratio (product_id, ingredient_name, ingredient_ratio, size)
VALUES
    -- Green Apple
    (13, 'tea', 250, 'medio'),
    (13, 'green apple syrup', 40, 'medio'),
    (13, 'pearl', 10, 'medio'),
    (13, 'cup', 1, 'medio'),
    (13, 'sealing film', 1, 'medio'),
    (13, 'straw', 1, 'medio'),

    -- Kiwi
    (14, 'tea', 250, 'medio'),
    (14, 'kiwi syrup', 40, 'medio'),
    (14, 'pearl', 10, 'medio'),
    (14, 'cup', 1, 'medio'),
    (14, 'sealing film', 1, 'medio'),
    (14, 'straw', 1, 'medio'),

    -- Lemon
    (15, 'tea', 250, 'medio'),
    (15, 'lemon syrup', 40, 'medio'),
    (15, 'pearl', 10, 'medio'),
    (15, 'cup', 1, 'medio'),
    (15, 'sealing film', 1, 'medio'),
    (15, 'straw', 1, 'medio'),

    -- Passion Fruit
    (16, 'tea', 250, 'medio'),
    (16, 'passion fruit syrup', 40, 'medio'),
    (16, 'pearl', 10, 'medio'),
    (16, 'cup', 1, 'medio'),
    (16, 'sealing film', 1, 'medio'),
    (16, 'straw', 1, 'medio'),

    -- Strawberry
    (17, 'tea', 250, 'medio'),
    (17, 'strawberry syrup', 40, 'medio'),
    (17, 'pearl', 10, 'medio'),
    (17, 'cup', 1, 'medio'),
    (17, 'sealing film', 1, 'medio'),
    (17, 'straw', 1, 'medio'),

    -- Watermelon
    (18, 'tea', 250, 'medio'),
    (18, 'watermelon syrup', 40, 'medio'),
    (18, 'pearl', 10, 'medio'),
    (18, 'cup', 1, 'medio'),
    (18, 'sealing film', 1, 'medio'),
    (18, 'straw', 1, 'medio');

-- GRANDE SIZE
INSERT INTO product_ingredient_ratio (product_id, ingredient_name, ingredient_ratio, size)
VALUES
    -- Green Apple
    (13, 'tea', 350, 'grande'),
    (13, 'green apple syrup', 80, 'grande'),
    (13, 'pearl', 15, 'grande'),
    (13, 'cup', 1, 'grande'),
    (13, 'sealing film', 1, 'grande'),
    (13, 'straw', 1, 'grande'),

    -- Kiwi
    (14, 'tea', 350, 'grande'),
    (14, 'kiwi syrup', 80, 'grande'),
    (14, 'pearl', 15, 'grande'),
    (14, 'cup', 1, 'grande'),
    (14, 'sealing film', 1, 'grande'),
    (14, 'straw', 1, 'grande'),

    -- Lemon
    (15, 'tea', 350, 'grande'),
    (15, 'lemon syrup', 80, 'grande'),
    (15, 'pearl', 15, 'grande'),
    (15, 'cup', 1, 'grande'),
    (15, 'sealing film', 1, 'grande'),
    (15, 'straw', 1, 'grande'),

    -- Passion Fruit
    (16, 'tea', 350, 'grande'),
    (16, 'passion fruit syrup', 80, 'grande'),
    (16, 'pearl', 15, 'grande'),
    (16, 'cup', 1, 'grande'),
    (16, 'sealing film', 1, 'grande'),
    (16, 'straw', 1, 'grande'),

    -- Strawberry
    (17, 'tea', 350, 'grande'),
    (17, 'strawberry syrup', 80, 'grande'),
    (17, 'pearl', 15, 'grande'),
    (17, 'cup', 1, 'grande'),
    (17, 'sealing film', 1, 'grande'),
    (17, 'straw', 1, 'grande'),

    -- Watermelon
    (18, 'tea', 350, 'grande'),
    (18, 'watermelon syrup', 80, 'grande'),
    (18, 'pearl', 15, 'grande'),
    (18, 'cup', 1, 'grande'),
    (18, 'sealing film', 1, 'grande'),
    (18, 'straw', 1, 'grande');


-- =========================
-- = HOT BREW INGREDIENT RATIOS =
-- =========================

INSERT INTO product_ingredient_ratio (product_id, ingredient_name, ingredient_ratio, size)
VALUES
    -- Hot Brusko
    (44, 'coffee', 350, 'hot brew'),
    (44, 'hot brusko syrup', 80, 'hot brew'),
    (44, 'cup', 1, 'hot brew'),

    -- Hot Choco
    (45, 'coffee', 350, 'hot brew'),
    (45, 'hot choco syrup', 80, 'hot brew'),
    (45, 'cup', 1, 'hot brew'),

    -- Hot Moca
    (46, 'coffee', 350, 'hot brew'),
    (46, 'hot moca syrup', 80, 'hot brew'),
    (46, 'cup', 1, 'hot brew'),

    -- Hot Matcha
    (47, 'coffee', 350, 'hot brew'),
    (47, 'hot matcha syrup', 80, 'hot brew'),
    (47, 'cup', 1, 'hot brew'),

    -- Hot Karamel
    (48, 'coffee', 350, 'hot brew'),
    (48, 'hot karamel syrup', 80, 'hot brew'),
    (48, 'cup', 1, 'hot brew');



-- =========================
-- = ICED COFFEE INGREDIENT RATIOS =
-- =========================

INSERT INTO product_ingredient_ratio (product_id, ingredient_name, ingredient_ratio, size)
VALUES
    -- Kape Brusko
    (19, 'coffee', 250, 'medio'),
    (19, 'kape brusko syrup', 40, 'medio'),
    (19, 'cup', 1, 'medio'),
    (19, 'straw', 1, 'medio'),
    (19, 'sealing film', 1, 'medio'),

    (19, 'coffee', 350, 'grande'),
    (19, 'kape brusko syrup', 80, 'grande'),
    (19, 'cup', 1, 'grande'),
    (19, 'straw', 1, 'grande'),
    (19, 'sealing film', 1, 'grande'),

    -- Kape Karamel
    (20, 'coffee', 250, 'medio'),
    (20, 'kape karamel syrup', 40, 'medio'),
    (20, 'cup', 1, 'medio'),
    (20, 'straw', 1, 'medio'),
    (20, 'sealing film', 1, 'medio'),

    (20, 'coffee', 350, 'grande'),
    (20, 'kape karamel syrup', 80, 'grande'),
    (20, 'cup', 1, 'grande'),
    (20, 'straw', 1, 'grande'),
    (20, 'sealing film', 1, 'grande'),

    -- Kape Macch
    (21, 'coffee', 250, 'medio'),
    (21, 'kape macch syrup', 40, 'medio'),
    (21, 'cup', 1, 'medio'),
    (21, 'straw', 1, 'medio'),
    (21, 'sealing film', 1, 'medio'),

    (21, 'coffee', 350, 'grande'),
    (21, 'kape macch syrup', 80, 'grande'),
    (21, 'cup', 1, 'grande'),
    (21, 'straw', 1, 'grande'),
    (21, 'sealing film', 1, 'grande'),

    -- Kape Vanilla
    (22, 'coffee', 250, 'medio'),
    (22, 'kape vanilla syrup', 40, 'medio'),
    (22, 'cup', 1, 'medio'),
    (22, 'straw', 1, 'medio'),
    (22, 'sealing film', 1, 'medio'),

    (22, 'coffee', 350, 'grande'),
    (22, 'kape vanilla syrup', 80, 'grande'),
    (22, 'cup', 1, 'grande'),
    (22, 'straw', 1, 'grande'),
    (22, 'sealing film', 1, 'grande');
