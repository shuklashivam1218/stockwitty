export function navBar() {
    return {
        scrolled: false,
        open: false,
        mega: false,
        mobileProducts: false,

        init() {
            const onScroll = () => {
                this.scrolled = window.scrollY > 12;
            };
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });
        },

        closeAll() {
            this.open = false;
            this.mega = false;
            this.mobileProducts = false;
        },
    };
}
