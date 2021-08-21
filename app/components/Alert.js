export default {
    props: ['variant'],
    template: `
        <div :class="variant" class="btn btn--alert animated">
            <slot></slot>
        </div>
    `
}
