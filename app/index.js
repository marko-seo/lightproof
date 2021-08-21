import Alert from './components/Alert.js';
import Modal from './components/Modal.js';
import Preloader from './components/Preloader.js';
import ListResult from './components/ListResult.js';

document.addEventListener("DOMContentLoaded", function () {

    // Main rest route
    const route = "/wp-json/wp/v2";
    const formAddToCart = document.getElementById('lp-product__form-add-to-cart');
    
    // Custom js
    const search = Vue.createApp({
        data() {
            return {
                posts: [],
                searchLine: '',
                isLoading: false
            }
        },

        components: {
            Preloader,
            ListResult
        },

        computed: {
            searchResultInfo() {

                const data = {
                    types: {},
                    message: ''
                };

                if (this.posts.length > 0 && this.searchLine.length > 2) {

                    for (const post of this.posts) {
                        if (!(post.subtype in data.types)) {
                            data['types'][post.subtype] = 1;
                        } else {
                            data['types'][post.subtype] += 1;
                        }
                    }

                    data.message = "Найдено: ";

                    const tmpArr = [];

                    for (const key in data.types) {
                        tmpArr.push(`${data.types[key]} ${this.defineType(key, data.types[key])}`);
                    }

                    data.message += tmpArr.join(', ');

                }

                return data;
            },
        },

        methods: {
            defineType(type, count = 1) {
                let res = '';

                switch (type) {
                    case 'page':
                        if (count > 1) {
                            res = count < 5 ? 'страницы' : 'страниц';
                        } else {
                            res = 'страница';
                        }
                        break;
                    case 'product':
                        if (count > 1) {
                            res = count < 5 ? 'товара' : 'товаров';
                        } else {
                            res = 'товар';
                        }
                        break;
                    case 'post':
                        if (count > 1) {
                            res = count < 5 ? 'статьи' : 'статей';
                        } else {
                            res = 'статья';
                        }
                        break;
                    default:
                        break;
                }

                return res;
            },
            onHandleInput(e) {
                const el = e.target;

                if (el.name == 'input-search') {
                    if (this.searchLine.length > 2) {
                        this.isLoading = true;
                        fetch(`${route}/search/?search=${this.searchLine}`)
                            .then(res => res.json())
                            .then(async (data) => {

                                if (data.length > 0) {
                                    for (const item of data) {
                                        if (item.subtype == "product") {
                                            let response = await fetch(`${wpRoute.url}?action=get_product&id=${item.id}`);

                                            if (response.ok) {
                                                let json = await response.json();
                                                item['product'] = json;
                                            } else {
                                                console.error(`Error ${response.status}`);
                                            }

                                        }
                                    }
                                }

                                this.posts = data;

                                setTimeout(() => this.isLoading = false, 2000);
                            })
                            .catch(err => console.error(err));
                    } else {
                        return;
                    }
                }
            },
        },
    }).mount("#search");

    // Order form
    const orderForm = Vue.createApp({
        data() {
            return {
                message: 'Hello vue',
                cssClass: null,
                showAlert: false,
                isLoading: false
            }
        },
        components: {
            'alert': Alert
        },
        methods: {
            onHandleSubmit(typeForm, e) {
                this.showAlert = !this.showAlert;
                const el = e.target;
                const formData = new FormData(el);

                if (typeForm === 'measurer') {

                    this.isLoading = true;
                    formData.append('typeForm', typeForm);

                    fetch('/send.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(res => res.json())
                        .then(data => {
                            console.log(data);
                            this.showAlert = true;
                            this.isLoading = false;
                            if ('success' in data) {
                                this.message = data.success;
                                this.cssClass = 'btn--alert-success';
                            } else {
                                this.message = data.error;
                                this.cssClass = 'btn--alert-danger';
                            }
                        })
                        .catch(err => console.error(err));
                }
            }
        }
    });
    orderForm.mount('#order');

    // Modal
    const modal = Vue.createApp({
        data() {
            return {
                info: {
                    type: '',
                    subject: '',
                },
                alertInfo: {
                    text: '',
                    show: false,
                    variant: 'btn--alert-success'
                },
                show: false,
            }
        },
        components: {
            'Modal': Modal
        },
        methods: {
            onHandleClick(e) {
                const el = e.target;

                if (el.classList.contains('overlay')
                    || el.classList.contains('modal__close')) {
                    this.show = false;
                    this.alertInfo.show = false;
                }
            },
            onHandleSubmit(e) {
                const formData = new FormData(e.target);

                if (this.info.type === 'byonclick') {

                    if (typeof this.info.product === 'object') {
                        let product = '';
                        for (const prop in this.info.product) {
                            formData.append(prop, this.info.product[prop]);
                        }
                    } else {
                        formData.append('message', this.info.product);
                    }
                }

                fetch('/send.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {

                        if ('success' in data) {
                            this.alertInfo.text = data.success;
                            this.alertInfo.variant = 'btn--alert-success';
                            setTimeout(() => {
                                this.show = false;
                                this.alertInfo.show = false;
                            }, 2000);
                        } else {
                            this.alertInfo.text = data.error;
                            this.alertInfo.variant = 'btn--alert-danger';
                        }

                        this.alertInfo.show = true;
                        setTimeout(() => {
                            this.alertInfo.show = false;
                        }, 1000);

                        console.log(data);
                    })
                    .catch(err => console.error(err));
            }
        },
    }).mount('#hide-element');


    // Global handle click
    this.addEventListener("click", e => {
        const el = e.target;

        if (el.classList.contains('lp-card__boc')) {
            let product = '';

            try {
                product = JSON.parse(el.dataset.product);
            } catch (e) {
                product = "Произошла ошибка уточните детали у клиента !";
                console.error(e, 'Error');
            }

            modal.info = {
                type: 'byonclick',
                subject: 'Купить в один клик',
                product: product
            };

            modal.show = true;
        } else if (el.classList.contains('btn-feedback')) {
            modal.info = {
                type: 'feedback',
                subject: 'Заказать звонок',
            };
            modal.show = true;
        }

        if (el.closest('.si-count-control')) {
            const elInput = el.closest('.si-count-control').querySelector('.si-count-control__si-field');

            switch (true) {
                case el.classList.contains('si-count-control__btn--prev'):
                    if (Number(elInput.value) !== 1) {
                        elInput.value = Number(elInput.value) - 1;
                    }
                    break;
                case el.classList.contains('si-count-control__btn--next'):
                    elInput.value = Number(elInput.value) + 1;
                    break;
                default:
                    break;
            }
        }
    });


    // Custom add to cart
    if (formAddToCart) {    

        formAddToCart.addEventListener('submit', async (e) => {
            e.preventDefault();

            const btn = formAddToCart.querySelector('.si-btn-add-to-cart');

            btn.disabled = true;

            const res = await fetch(`${wpRoute.url}?action=add_to_cart`, {
                method: "POST",
                body: new FormData(formAddToCart),
            });

            if (res.ok) {
                const json = await res.json();
                let cssClass = '';  
                
                if ('error' in json) {
                    cssClass = 'btn--alert-danger';
                } else {
                    btn.disabled = false;
                    cssClass = 'btn--alert-success';
                    formAddToCart.querySelector('.si-price__num').innerHTML = json['new_price'];
                }

                formAddToCart.insertAdjacentHTML('beforeend', `<div class="btn btn--alert ${cssClass}">${json.message}</div>`);

                setTimeout(() => {
                    formAddToCart.querySelector('.btn.btn--alert').remove();
                }, 3000);

            } else {
                console.error(`Error ${res.status}`);
            }

        });

    }

    // Tmp for checkout
    if (document.querySelector('.woocommerce #customer_details')) {
        document.querySelector('.woocommerce #customer_details .col-1').classList.add('col-lg-6', 'col-12');
        document.querySelector('.woocommerce #customer_details .col-2').classList.add('col-lg-6', 'col-12');
    }

});