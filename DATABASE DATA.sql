


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
--       ==========================
--       = ADD-ONS STARTS HERE    =
--       ==========================
--    


INSERT INTO PRODUCT_ADD_ONS(ADD_ONS_NAME, PRICE)
VALUES
('CHEESE CAKE', 10.00),
('PEARL', 10.00),
('CREAM CHEESE', 10.00),
('COFFEE JELLY', 10.00),
('CRUSHED OREO', 10.00),
('CHIA SEED', 10.00);

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
--       =                                                                                                                                                                                                                                =
--       =                                                	                                             INSERTION OF TABLES DATA - Ends HERE                                                                                             =
--       =                                                                                                                                                                                                                                =
--       ==================================================================================================================================================================================================================================


