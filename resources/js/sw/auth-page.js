async function postForm(url, data) {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const res = await fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            Accept: 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(data).toString(),
    });
    let json = {};
    try {
        json = await res.json();
    } catch {
        json = {};
    }
    return { status: res.status, json };
}

export function authPage() {
    return {
        mode: 'login',
        loginUrl: '',
        registerUrl: '',

        login: { email: '', password: '' },
        loginBusy: false,
        loginError: '',
        loginErrors: {},
        showLoginPassword: false,

        signup: { name: '', email: '', phone: '', password: '' },
        signupConsent: false,
        signupBusy: false,
        signupError: '',
        signupErrors: {},
        showSignupPassword: false,

        init() {
            this.loginUrl = this.$el.dataset.loginUrl;
            this.registerUrl = this.$el.dataset.registerUrl;
            const initial = this.$el.dataset.initialMode;
            if (initial === 'signup' || initial === 'forgot') this.mode = initial;
            const hash = window.location.hash.replace('#', '');
            if (hash === 'signup' || hash === 'forgot' || hash === 'login') this.mode = hash;
        },

        setMode(mode) {
            this.mode = mode;
            history.replaceState(null, '', mode === 'login' ? window.location.pathname : `#${mode}`);
        },

        async submitLogin() {
            if (this.loginBusy) return;
            this.loginBusy = true;
            this.loginError = '';
            this.loginErrors = {};

            const { status, json } = await postForm(this.loginUrl, {
                login_type: 'email',
                email: this.login.email,
                password: this.login.password,
            });

            if (json.success) {
                window.location.href = json.redirect || '/';
                return;
            }

            this.loginBusy = false;
            if (status === 422 && json.errors) this.loginErrors = json.errors;
            this.loginError = json.message || 'Something went wrong. Please try again.';
        },

        async submitSignup() {
            if (this.signupBusy) return;
            if (!this.signupConsent) {
                this.signupError = 'Please confirm you understand the risks before continuing.';
                return;
            }
            this.signupBusy = true;
            this.signupError = '';
            this.signupErrors = {};

            const { status, json } = await postForm(this.registerUrl, {
                unlisted_user_type: 'unlisted',
                name: this.signup.name,
                email: this.signup.email,
                phone: this.signup.phone,
                password: this.signup.password,
            });

            if (json.success) {
                window.location.href = json.redirect || '/';
                return;
            }

            this.signupBusy = false;
            if (status === 422 && json.errors) this.signupErrors = json.errors;
            this.signupError = json.message || 'Something went wrong. Please try again.';
        },
    };
}
