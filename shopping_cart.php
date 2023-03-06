<div class="cart">
    <div class="close-btn" @click="hideCart"></div>
    <h2 class="cart-header">Shopping cart</h2>

    <shopping-cart
        :SESSION="SESSION"
        :img-name="imgName"
        :return-data="returnData"
        :change-quantity="changeQuantity"
        :remove-product="removeProduct">
    </shopping-cart>

    <div>
        <a href='/order' class='shopping-cart-submit' v-if="SESSION['cart'] != null && SESSION['cart'].length != 0">Place order</a>
    </div>
</div>