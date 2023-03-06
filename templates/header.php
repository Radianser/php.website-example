<div v-if="mobile" class="header" v-cloak>
    <div class="phone-sidebar-button" @click="showPhoneMenu">
        <img src="/images/menu.webp" alt="" class="phone-sidebar-button">
    </div>
    <div class="phone-sidebar">
        <h2>GloriaJean's Coffees</h2>
        <h3>Account</h3>
        <ul class="phone-sidebar-block" v-if="SESSION['user']['id'] != null" v-cloak>
            <li><a href="/profile/0" class="phone-sidebar-link">Profile</a></li>
            <li><a href="/logout.php" class="phone-sidebar-link">Exit</a></li>
        </ul>
        <ul class="phone-sidebar-block" v-else v-cloak>
            <li><a href="/registration" class="phone-sidebar-link">Sign up</a></li>
            <li><a href="/authorization" class="phone-sidebar-link">Log in</a></li>
        </ul>
        <hr>
        <h3>Menu</h3>
        <ul class="phone-sidebar-block">
            <li><a href="/category/1/coffee" class="phone-sidebar-link">Coffee</a></li>
            <li><a href="/category/2/tea" class="phone-sidebar-link">Tea</a></li>
            <li><a href="/category/3/bakery" class="phone-sidebar-link">Bakery</a></li>
            <li><a href="/category/4/pizza" class="phone-sidebar-link">Pizza</a></li>
            <li><a href="/category/5/burgers" class="phone-sidebar-link">Burgers</a></li>
            <li><a href="/category/6/sandwiches" class="phone-sidebar-link">Sandwiches</a></li>
            <li><a href="/category/7/rolls" class="phone-sidebar-link">Rolls</a></li>
            <li><a href="/category/8/desserts" class="phone-sidebar-link">Desserts</a></li>
            <li><a href="/category/9/drinks" class="phone-sidebar-link">Drinks</a></li>
            <li><a href="/category/10/alcohol" class="phone-sidebar-link">Alcohol</a></li>
        </ul>
        <hr>
        <h3>Navigation</h3>
        <ul class="phone-sidebar-block">
            <li><a href="/" class="phone-sidebar-link">Main</a></li>
            <li><a href="/menu" class="phone-sidebar-link">Menu</a></li>
            <li><a href="/about" class="phone-sidebar-link">About</a></li>
            <li><a href="/gallery" class="phone-sidebar-link">Gallery</a></li>
            <li><a href="/delivery" class="phone-sidebar-link">Delivery</a></li>
            <li><a href="/bonuses" class="phone-sidebar-link">Bonuses</a></li>
            <li><a href="/contacts" class="phone-sidebar-link">Contacts</a></li>
        </ul>
    </div>
    <a href="/"></a>
    <div class="user-panel">
        <?php if ($_SERVER['REQUEST_URI'] !== '/order') { ?>
            <div class="cart-button" @click="showCart">
        <?php } else { ?>
            <div class="cart-button">
        <?php } ?>
                <img src="/images/cart.svg" alt="bin" content-type="image/svg+xml" class="phone-cart-img">
                <transition name="slide" v-cloak>
                    <div class="counter" v-if="SESSION['quantity'] != null && SESSION['quantity'] != 0">{{ SESSION['quantity'] }}</div>
                </transition>
            </div>
        <?php if ($_SERVER['REQUEST_URI'] !== '/order') { ?>
            <?php include 'shopping_cart.php' ?>
        <?php } ?>
    </div>
    <div class="screen"></div>
</div>

<div v-else class="header" v-cloak>
    <a href="/"></a>
    <ul class="header-menu">
        <li><a href="/menu" class="header-button">Menu</a></li>
        <li><a href="/about" class="header-button">About</a></li>
        <li><a href="/gallery" class="header-button">Gallery</a></li>
        <li><a href="/delivery" class="header-button">Delivery</a></li>
        <li><a href="/bonuses" class="header-button">Bonuses</a></li>
        <li><a href="/contacts" class="header-button">Contacts</a></li>
    </ul>
    <div class="user-panel">
        <?php if ($_SERVER['REQUEST_URI'] !== '/order') { ?>
            <div class="cart-button" @click="showCart">
        <?php } else { ?>
            <div class="cart-button">
        <?php } ?>
                <p>Shopping cart</p>
                <transition name="slide" v-cloak>
                    <div class="counter" v-if="SESSION['quantity'] != null && SESSION['quantity'] != 0">{{ SESSION['quantity'] }}</div>
                </transition>
            </div>
        <?php if ($_SERVER['REQUEST_URI'] !== '/order') { ?>
            <?php include 'shopping_cart.php' ?>
        <?php } ?>
        <ul class="entry" v-if="SESSION['user']['id'] != null" v-cloak>
            <li><a href="/profile/0" class="header-button">Profile</a></li>
            <li><a href="/logout.php" class="header-button">Exit</a></li>
        </ul>
        <ul class="entry" v-else v-cloak>
            <li><a href="/registration" class="header-button">Sign up</a></li>
            <li><a href="/authorization" class="header-button">Log in</a></li>
        </ul>
    </div>
    <div class="screen" @click="hideCart"></div>
</div>