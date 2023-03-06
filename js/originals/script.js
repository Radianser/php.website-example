const app = Vue.createApp({
    data() {
        return {
            SESSION: {
                cart: [],
                quantity: 0,
                total: 0,
                user: {
                    login: null,
                    id: null,
                    nickname: null,
                    age: null
                }
            },
            min: 500,
            mobile: null
        }
    },
    methods: {
        showPhoneMenu() {
            let html = document.querySelector('html');
            let menu = document.querySelector('div.phone-sidebar');
            let screen = document.querySelector('.screen');
            screen.addEventListener('click', this.hidePhoneMenu);

            menu.style.left = "0";
            screen.style.display = "block";
            html.style.overflow = "hidden";
        },
        hidePhoneMenu(e) {
            let html = document.querySelector('html');
            let menu = document.querySelector('div.phone-sidebar');

            menu.style.left = "-70vw";
            e.target.style.display = "none";
            html.style.overflow = "visible";

            e.target.removeEventListener('click', this.hidePhoneMenu);
        },
        showCart() {
            let html = document.querySelector('html');
            let cart = document.querySelector('div.cart');
            let screen = document.querySelector('.screen');
            screen.addEventListener('click', this.hideCart);
            
            cart.style.display = "block";
            screen.style.display = "block";
            html.style.overflow = "hidden";
        },
        hideCart(e) {
            let html = document.querySelector('html');
            let cart = document.querySelector('div.cart');
            let screen = document.querySelector('.screen');

            cart.style.display = "none";
            screen.style.display = "none";
            html.style.overflow = "visible";

            e.target.removeEventListener('click', this.hideCart);
        },
        addProduct(e) {
            let id = e.target.dataset.id;
            let url = 'http://localhost/cart.php';
            let data = {action: 'add', id: id};
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify(data),
            }).then(
                response => {
                    return response.json();
                }
            ).then(
                result => {
                    if (result[1] !== true) {
                        alert(result[1]);
                    } else {
                        for (let elem in result[0]) {
                            this.SESSION[elem] = result[0][elem];
                        }
                    }
                }
            );
        },
        changeQuantity (e) {
            let id = e.target.dataset.id;
            let action = e.target.dataset.action;
            let url = 'http://localhost/cart.php';
            let data = {action: action, id: id};

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify(data),
            }).then(
                response => {
                    return response.json();
                }
            ).then(
                result => {
                    let discount = document.querySelector('.discount');
                    if (discount) {
                        discount.value = "";
                    }

                    for (let elem in result) {
                        this.SESSION[elem] = result[elem];
                    }

                    let order_page_switch = document.querySelector("input.self-pickup");
                    if (order_page_switch) {
                        order_page_switch.checked = true;
                        this.offAddresses();
                    }
                }
            );
        },
        removeProduct (e) {
            let id = e.target.dataset.id;
            let url = 'http://localhost/cart.php';
            let data = {action: 'remove', id: id};
            let product = e.target.closest('div.cart-product-line');
            product.remove();

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify(data),
            }).then(
                response => {
                    return response.json();
                }
            ).then(
                result => {
                    let discount = document.querySelector('.discount');
                    if (discount) {
                        discount.value = "";
                    }

                    for (let elem in result) {
                        this.SESSION[elem] = result[elem];
                    }
                }
            );
        },
        usePoints(e) {
            let points = e.target.value;
            let url = 'http://localhost/cart.php';
            let data = {action: 'use_points', points: points};
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify(data)
            }).then(
                response => {
                    return response.json();
                }
            ).then(
                result => {
                    let total = document.querySelector('div.total');

                    if (result['check'] == false) {
                        e.target.value = '';
                        total.innerHTML = result['total'];
                        alert('Points entered incorrectly');
                    } else {
                        total.innerHTML = result['total'] - e.target.value;
                    }
                }
            );
        },
        showEditForm(e) {
            let nickname = document.querySelector('.edit-form.nickname');
            let phone = document.querySelector('.edit-form.phone');
            let img = document.querySelector('.edit-form.img');

            let phone_input = document.querySelector('.phone-input');
            if (phone_input) {
                phone_input.addEventListener('keydown', this.inputDigits);
                phone_input.addEventListener('blur', this.checkInput);
            }
            
            let choice = e.target.dataset.form;
            let screen = document.querySelector('.screen');
            screen.addEventListener('click', this.hideEditForm);
            screen.style.display = 'block';

            if (choice == 'nickname') {
                nickname.classList.remove('hidden');
                nickname.classList.add('active');
            } else if (choice == 'phone') {
                phone.classList.remove('hidden');
                phone.classList.add('active');
            } else {
                img.classList.remove('hidden');
                img.classList.add('active');
            }
        },
        hideEditForm(e) {
            let screen = e.target;
            let form = document.querySelector('.edit-form.active');
            if (form) {
                form.classList.remove('active');
                form.classList.add('hidden');
            }
            screen.style.display = 'none';
            screen.removeEventListener('click', this.hideEditForm);
        },
        inputDigits(e) {
            if (e.shiftKey == false) {
                if (e.keyCode < 8 || (e.keyCode > 8 && e.keyCode < 48) || (e.keyCode > 57 && e.keyCode < 96) || e.keyCode > 105) {
                    e.preventDefault();
                }
            } else {
                e.preventDefault();
            }
        },
        checkInput(e) {
            if (e.target.value != '') {
                if (e.target.value[0] != '+') {
                    if (e.target.value[0] == 8 || e.target.value[0] == 7) {
                        let str = e.target.value;
                        e.target.value = '+7' + str.substring(1);
                    } else {
                        e.target.value = '+7' + e.target.value;
                    }
                }
            }
        },
        offAddresses() {
            let addresses = document.querySelector('.shopping-cart-addresses');
            let hidden_radio = document.querySelector('.hidden');
            hidden_radio.checked = true;
            addresses.classList.add('hidden');
        },
        onAddresses() {
            let addresses = document.querySelector('.shopping-cart-addresses');
            let hidden_radio = document.querySelector('.hidden');
            hidden_radio.checked = true;
            addresses.classList.remove('hidden');
        },
        checkFormData(e) {
            e.preventDefault();

            let order_form = document.querySelector('#order-form');
            let formData = new FormData(order_form);
            let arr = Array.from(formData);
            
            if (arr.length == 6) {
                if (arr[2][1] == 'no_address' && arr[1][1] == 'delivery') {
                    alert('Address not selected!');
                } else {
                    this.makeOrder(arr);
                }
            } else {
                this.makeOrder(arr);
            }
        },
        makeOrder(arr) {
            let order_details = arr;
            let url = 'http://localhost/cart.php';
            let data = {action: 'order', order_details: order_details};
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify(data)
            }).then(
                response => {
                    return response.json();
                }
            ).then(
                result => {
                    if (result == 1) {
                        location.href = "/success";
                    } else {
                        alert('An error has occurred. Please try again later.');
                    }
                }
            );
        },
        glideEffect(e) {
            let div = e.target.closest('div.main-img');
            let style = getComputedStyle(div);
            let width = Math.round(e.clientX - Number(style.width.slice(0, -2))/2);
            let height = Math.round(e.clientY - 100 - Number(style.height.slice(0, -2))/2);
            
            div.style.cssText += `--coordinate-x: ${50 - width/100}%; --coordinate-y: ${50 - height/100}%`;
        },
        serverDataSync() {
            let url = 'http://localhost/cart.php';
            let data = {action: 'sync'};
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify(data),
            }).then(
                response => {
                    return response.json();
                }
            ).then(
                result => {
                    for (let elem in result) {
                        this.SESSION[elem] = result[elem];
                    }
                }
            );
        },
        returnData(data) {
            return data;
        },
        imgName(data) {
            let str = "/images/products/";
            let name;
            if (data.indexOf(' ') > -1) {
                name = str + data.split(' ').join('_') + '.webp';
            } else {
                name = str + data + '.webp';
            }
            return name;
        }
    },
    created() {
        window.addEventListener('resize' , () => {
            if (window.innerWidth < 1000) {
                this.mobile = true;
            } else {
                this.mobile = false;
            }
        });
        window.addEventListener('scroll' , () => {
            let header = document.querySelector(".header");
            let main = document.querySelector(".main");
            
            if (window.scrollY > 0) {
                header.style.height = "70px";
                main.style.marginTop = "70px";
            } else {
                header.style.height = "100px";
                main.style.marginTop = "100px";
            }

            let parallax = document.querySelector('.parallax');
            if (parallax && window.scrollY > 1470) {
                parallax.style.cssText += `--coordinate-y: ${110 - Math.round(window.scrollY/50)}%`;
            }
        });
    },
    mounted() {
        this.serverDataSync();
        if (window.innerWidth < 1000) {
            this.mobile = true;
        } else {
            this.mobile = false;
        }
    }
});

app.component('shopping-cart', {
    props: ['session', 'imgName', 'returnData', 'changeQuantity', 'removeProduct'],
    data() {
        return {}
    },
    template: `
        <div class='cart-header' v-if="session['cart'] != null && session['cart'].length != 0">
            <div class='cart-product-header product-id'>#id</div>
            <div class='cart-product-header product-img'>image</div>
            <div class='cart-product-header'>name</div>
            <div class='cart-product-header'>count</div>
            <div class='cart-product-header'>price,₽</div>
            <div class='cart-product-header'>cost,₽</div>
            <div class='cart-product-header'></div>
        </div>
        <div class='cart-header' v-else></div>
        <div class='cart-products' v-if="session['cart'] != null && session['cart'].length != 0">
        <div class='cart-product-line' v-for="(item, key) in session['cart']" :key="key">
            <div class='cart-product-info product-id'>{{ item.id }}</div>
            <div class='cart-product-info product-img'><img :src='imgName(item.name)' :alt='returnData(item.name)' loading='lazy'/></div>
            <div class='cart-product-info'>{{ item.name }}</div>
            <div class='cart-product-info'>
                <div class='counter-btn decrement' :data-id='returnData(item.id)' data-action='decrement' @click='changeQuantity'>-</div>
                <div class='product-counter'>{{ item.count }}</div>
                <div class='counter-btn increment' :data-id='returnData(item.id)' data-action='increment' @click='changeQuantity'>+</div>
            </div>
            <div class='cart-product-info'>{{ item.price }}</div>
            <div class='cart-product-info product-cost'>{{ item.cost }}</div>
            <div class='cart-product-info remove-btn' :data-id="returnData(item.id)" @click='removeProduct'></div>
        </div>
        </div>
        <div class='cart-products' v-else>The shopping cart is empty</div>
        <div class='cart-footer'>
            <div class='cart-product-footer product-id'></div>
            <div class='cart-product-footer product-img'></div>
            <div class='cart-product-footer'></div>
            <div class='cart-product-footer'></div>
            <div class='cart-product-footer'></div>
            <div class='cart-product-footer total' v-if="session['cart'] != null && session['cart'].length != 0">{{ session['total'] }}</div>
            <div class='cart-product-footer total' v-else></div>
            <div class='cart-product-footer'></div>
        </div>
    `
});

app.mount('#app');