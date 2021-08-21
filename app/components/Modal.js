import Alert from './Alert.js';

export default {
    props: ['info', 'alertInfo'],
    emits: ['handleSubmit'],
    components: {
        'alert': Alert
    },
    template: `
        <div class="modal animated">
            <svg class="modal__close">
                <use href="/wp-content/themes/lightproof/images/icons-close.svg#icon"></use>
            </svg>
            <form @submit.prevent="$emit('handleSubmit', $event)" class="modal__form">
                <legend class="modal__title">{{ info.subject }}</legend>
                <p class="modal__subtitle">Введите ваши данные, чтобы<br>оформить заказ</p>
                <label class="modal__form-group">
                    <input class="modal__form-control" name="name" type="text" placeholder="Имя">
                </label>
                <label class="modal__form-group">
                    <input class="modal__form-control" name="phone" type="text" placeholder="Email">
                </label>
                <input :value="info.subject" name="subject" type="hidden">
                <button class="modal__btn">Заказать</button>
                <transition enter-active-class="headShake" leave-active-class="fadeOut">
                    <alert v-if="alertInfo.show" :class="alertInfo.variant" class="btn btn--alert animated">{{ alertInfo.text }}</alert>
                </transition>
            </form>
        </div>        
    `
}