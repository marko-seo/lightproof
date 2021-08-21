<section id="order" class="section-order section-order-bottom" id="order-bottom">
    <div class="container">
        <transition enter-active-class="headShake" leave-active-class="fadeOut">
            <alert v-if="showAlert" :variant="cssClass">
                {{ message }}
            </alert>
        </transition>
        <div class="order__title wow fadeIn">Оставьте заявку на <span>бесплатный</span> выезд
            замерщика (дизайнера) и расчёт стоимости
        </div>
        <div class="order__icons">
            <div class="order__icons-item wow fadeIn" data-wow-delay="0.1s">
                <img src="<?= get_template_directory_uri() ?>/images/icon-7.svg">
                <span>Наш специалист произведёт замер Вашего окна</span>
            </div>
            <div class="order__icons-item wow fadeIn" data-wow-delay="0.3s">
                <img src="<?= get_template_directory_uri() ?>/images/icon-8.svg">
                <span>Рассчитает стоимость заказа и проконсультирует</span>
            </div>
            <div class="order__icons-item wow fadeIn" data-wow-delay="0.5s">
                <img src="<?= get_template_directory_uri() ?>/images/icon-9.svg">
                <span>Заключит договор и отправит Ваш заказ в производство</span>
            </div>
        </div>
        <form @submit.prevent="onHandleSubmit('measurer', $event)" class="order__form wow fadeIn" data-wow-delay="0.7s">
            <input name="name" type="text" class="inputbox" placeholder="Ваше имя">
            <input name="phone" class="inputbox" placeholder="Ваш номер телефона">
            <input name="subject" type="hidden" value="Выезд замерщика">
            <input type="submit" :disabled="isLoading" class="btn btn--full" value="Отправить заявку">
        </form>
    </div>
</section>