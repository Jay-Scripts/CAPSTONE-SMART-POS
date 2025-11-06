    <section class="flex justify-center items-center h-screen">
        <div class="w-full h-screen p-4">
            <div
                id="menuContainer"
                class="border-2 border-[var(--container-border)] p-4 rounded-lg col-span-3 landscape:col-span- portrait:active:cursor-grabbing">
                <fieldset
                    id="orderCategory"
                    class="flex flex-wrap justify-around items-center"
                    aria-label="Order Categories">
                    <legend class="sr-only">Choose a Category</legend>

                    <div class="categoryButtons">
                        <input
                            type="radio"
                            id="milktea_module"
                            name="module"
                            class="hidden peer"
                            checked
                            onclick="showModuleDisableProduct('milktea')" />
                        <label
                            for="milktea_module"
                            class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                            <svg
                                class="w-8 h-8 mb-2"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M14 2l-4 2" />
                                <path d="M12 2v3" />
                                <path d="M5 7h14" />
                                <path
                                    d="M6 7l1.2 11.2A2 2 0 0 0 9.19 20h5.62a2 2 0 0 0 1.99-1.8L18 7" />
                                <path d="M7 12h10" />
                                <circle
                                    cx="9"
                                    cy="16.5"
                                    r="1"
                                    fill="currentColor"
                                    stroke="none" />
                                <circle
                                    cx="12"
                                    cy="17.5"
                                    r="1"
                                    fill="currentColor"
                                    stroke="none" />
                                <circle
                                    cx="15"
                                    cy="16.5"
                                    r="1"
                                    fill="currentColor"
                                    stroke="none" />
                            </svg>

                            <p class="font-semibold text-xs sm:text-sm">MILK TEA</p>
                        </label>
                    </div>

                    <div class="categoryButtons">
                        <input
                            type="radio"
                            id="fruittea_module"
                            name="module"
                            class="hidden peer"
                            onclick="showModuleDisableProduct('fruittea')" />
                        <label
                            for="fruittea_module"
                            class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                            <svg
                                class="w-8 h-8 mb-2"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M6 7h12l-1 11a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 7z" />
                                <path d="M5 7h14" />
                                <path d="M12 2v5" />
                                <path d="M7 12h10" />
                                <circle cx="16.5" cy="15.5" r="2" />
                                <path d="M16.5 13.5v4" />
                                <path d="M14.5 15.5h4" />
                            </svg>

                            <p class="font-semibold text-xs sm:text-sm">
                                FRUIT TEA
                            </p>
                        </label>
                    </div>

                    <div class="categoryButtons">
                        <input
                            type="radio"
                            id="hotbrew_module"
                            name="module"
                            class="hidden peer"
                            onclick="showModuleDisableProduct('hotbrew')" />
                        <label
                            for="hotbrew_module"
                            class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                            <svg
                                class="w-8 h-8 mb-2"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M4 8h12v8a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4V8z" />
                                <path d="M16 10h1a3 3 0 0 1 0 6h-1" />
                                <path d="M9 2v3" />
                                <path d="M13 2v3" />
                            </svg>

                            <p class="font-semibold text-xs sm:text-sm">HOT BREW</p>
                        </label>
                    </div>

                    <div class="categoryButtons">
                        <input
                            type="radio"
                            id="icedcoffee_module"
                            name="module"
                            class="hidden peer"
                            onclick="showModuleDisableProduct('icedcoffee')" />
                        <label
                            for="icedcoffee_module"
                            class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                            <svg
                                class="w-8 h-8 mb-2"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M7 7h10l-1.2 11.2A2 2 0 0 1 13.8 20H10.2a2 2 0 0 1-2-1.8L7 7z" />
                                <path d="M6 7h12" />
                                <path d="M12 2v5" />
                                <rect x="9" y="11" width="2.5" height="2.5" />
                                <rect x="12.5" y="14" width="2.5" height="2.5" />
                            </svg>

                            <p class="font-semibold text-xs sm:text-sm">
                                ICED COFFEE
                            </p>
                        </label>
                    </div>

                    <div class="categoryButtons">
                        <input
                            type="radio"
                            id="praf_module"
                            name="module"
                            class="hidden peer"
                            onclick="showModuleDisableProduct('praf')" />
                        <label
                            for="praf_module"
                            class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                            <svg
                                class="w-8 h-8 mb-2"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M6 9h12l-1.2 9.2A2 2 0 0 1 14.8 20H9.2a2 2 0 0 1-2-1.8L6 9z" />
                                <path d="M6 9c0-3 3-5 6-5s6 2 6 5" />
                                <path d="M12 4V2" />
                                <path d="M9 9c.5-1 1.5-1.5 3-1.5s2.5.5 3 1.5" />
                            </svg>

                            <p class="font-semibold text-xs sm:text-sm">PRAF</p>
                        </label>
                    </div>

                    <div class="categoryButtons">
                        <input
                            type="radio"
                            id="promos_module"
                            name="module"
                            class="hidden peer"
                            onclick="showModuleDisableProduct('promos')" />
                        <label
                            for="promos_module"
                            class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                            <svg
                                class="w-8 h-8 mb-2"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M7 7h10l-1.2 10.5A2 2 0 0 1 13.8 20H10.2a2 2 0 0 1-2-1.8L7 7z" />
                                <path d="M6 7h12" />
                                <path d="M12 2v5" />
                                <polygon
                                    points="16 10 17 12 19 12 17.5 13.5 18 15.5 16 14.5 14 15.5 14.5 13.5 13 12 15 12 16 10" />
                            </svg>

                            <p class="font-semibold text-xs sm:text-sm">PROMOS</p>
                        </label>
                    </div>

                    <div class="categoryButtons">
                        <input
                            type="radio"
                            id="brosty_module"
                            name="module"
                            class="hidden peer"
                            onclick="showModuleDisableProduct('brosty')" />
                        <label
                            for="brosty_module"
                            class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-8 h-8 mb-2"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 10h10l-1.5 8.5a2 2 0 01-2 1.5h-3a2 2 0 01-2-1.5L7 10z" />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7.5 10a3.5 3.5 0 013-2 3.5 3.5 0 013 2 3.5 3.5 0 013-2 3.5 3.5 0 013 2" />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 5l2 4" />
                            </svg>

                            <p class="font-semibold text-xs sm:text-sm">BROSTY</p>
                        </label>
                    </div>
                </fieldset>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Milktea Section - Starts Here                                                        -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="milktea" class="hidden">
                    <div class="titleContainer">
                        <hr class="border border-[var(--border-color)] my-5" />

                        <h1
                            id="menuTitle"
                            class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                            Milk Tea Menu
                        </h1>
                        <hr class="border border-[var(--border-color)] my-5" />
                    </div>
                    <div
                        class="gap-1 mt-2 justify-center items-center text-black "
                        id="milkteaMenu">
                        <?php
                        $category_id = 1; // MilkTea
                        include "../../app/includes/managerModule/managersFetchEnabledProducts.php";
                        ?>
                    </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Milktea Section - Ends Here                                                          -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Fruity Section - Starts Here                                                         -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="fruittea" class="hidden">
                    <div class="titleContainer">
                        <hr class="border border-[var(--border-color)] my-5" />

                        <h1
                            id="menuTitle"
                            class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                            Fruit Tea Menu
                        </h1>
                        <hr class="border border-[var(--border-color)] my-5" />
                    </div>
                    <div
                        class="gap-1 mt-2 justify-center items-center text-black "
                        id="fruitTeaMenu">
                        <?php
                        $category_id = 2; // Fruit Tea
                        include "../../app/includes/managerModule/managersFetchEnabledProducts.php";
                        ?>
                    </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Fruity Section - Ends Here                                                           -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Hot Brew Section - Starts Here                                                       -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="hotbrew" class="hidden">
                    <div class="titleContainer">
                        <hr class="border border-[var(--border-color)] my-5" />

                        <h1
                            id="menuTitle"
                            class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                            Hot Brew Menu
                        </h1>
                        <hr class="border border-[var(--border-color)] my-5" />
                    </div>
                    <div
                        class="gap-1 mt-2 justify-center items-center text-black "
                        id="hotBrewMenu">
                        <?php
                        $category_id = 3; // Hotbrew
                        include "../../app/includes/managerModule/managersFetchEnabledProducts.php";
                        ?>
                    </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Hot Brew Section - Ends Here                                                         -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Praf Section - Starts Here                                                           -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="praf" class="hidden">
                    <hr class="border border-[var(--border-color)] my-5" />

                    <h1
                        id="menuTitle"
                        class="text-center text-[1.5rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                        Praf Menu
                    </h1>
                    <hr class="border border-[var(--border-color)] my-5" />
                    <div
                        class="gap-1 mt-2 justify-center items-center text-black "
                        id="prafMenu">
                        <?php
                        $category_id = 4; // Praf
                        include "../../app/includes/managerModule/managersFetchEnabledProducts.php";
                        ?>
                    </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Praf Section - Ends Here                                                             -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Iced Coffee Section - Starts Here                                                    -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section
                    id="icedcoffee"
                    class="hidden"
                    aria-labelledby="icedcoffeeTitle">
                    <div class="titleContainer">
                        <hr class="border border-[var(--border-color)] my-5" />

                        <h1
                            id="menuTitle"
                            class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                            Iced Coffee Menu
                        </h1>
                        <hr class="border border-[var(--border-color)] my-5" />
                    </div>
                    <div
                        class="gap-1 mt-2 justify-center items-center text-black "
                        id="icedCoffeeMenu">
                        <?php
                        $category_id = 6; // Iced Coffee
                        include "../../app/includes/managerModule/managersFetchEnabledProducts.php";
                        ?>
                    </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Iced Coffee Section - Ends Here                                                      -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Promos Section - Starts Here                                                         -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="promos" class="hidden">
                    <div class="titleContainer">
                        <hr class="border border-[var(--border-color)] my-5" />

                        <h1
                            id="menuTitle"
                            class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                            Promo Menu
                        </h1>
                        <hr class="border border-[var(--border-color)] my-5" />
                    </div>
                    <div
                        class="gap-1 mt-2 justify-center items-center text-black "
                        id="promosMenu">
                        <?php
                        $category_id = 7; // Promos
                        include "../../app/includes/managerModule/managersFetchEnabledProducts.php";
                        ?>
                    </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Promos Section - Ends Here                                                           -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Brosty Section - Starts Here                                                         -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="brosty" class="hidden">
                    <div class="titleContainer">
                        <hr class="border border-[var(--border-color)] my-5" />

                        <h1
                            id="menuTitle"
                            class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                            Brosty Menu
                        </h1>
                        <hr class="border border-[var(--border-color)] my-5" />
                    </div>
                    <div
                        class="gap-1 mt-2 justify-center items-center text-black "
                        id="brostyMenu">
                        <?php
                        $category_id = 5; // Brosty
                        include "../../app/includes/managerModule/managersFetchEnabledProducts.php";
                        ?>
                    </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Brosty Section - Ends Here                                                           -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Modify Section - Starts Here                                                         -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="modify" class="hidden">
                    <div class="titleContainer">
                        <hr class="border border-[var(--border-color)] my-5" />

                        <h1
                            id="menuTitle"
                            class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                            Modify
                        </h1>
                        <hr class="border border-[var(--border-color)] my-5" />
                    </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Modify Section - Ends Here                                                           -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Add-ons Section - Starts Here                                                        -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="addOns" class="hidden">
                    <div class="titleContainer">
                        <hr class="border border-[var(--border-color)] my-5" />

                        <h1
                            id="menuTitle"
                            class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                            Add-ons
                        </h1>
                        <hr class="border border-[var(--border-color)] my-5" />
                    </div>
                </section>
            </div>
        </div>
    </section>
    <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Add-ons Section - Ends Here                                                          -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->