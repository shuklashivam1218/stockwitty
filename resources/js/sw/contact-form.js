// Contact form on /contact/ (resources/views/sw/contact/index.blade.php).
// There is no contact backend yet, so a valid submit opens the visitor's
// email app with the enquiry already written out to our inbox. Nothing is
// lost and nothing pretends to have been "received" by a server. When a
// backend exists, replace openEmailDraft() with a POST.

const FIELDS = ['name', 'email', 'mobile', 'topic', 'message'];

export function contactForm({ email }) {
    return {
        valid: false,
        opened: false,

        check() {
            this.valid = this.$refs.form.checkValidity();
        },

        submit() {
            if (!this.$refs.form.reportValidity()) return;

            const data = new FormData(this.$refs.form);
            const values = Object.fromEntries(FIELDS.map((f) => [f, String(data.get(f) ?? '').trim()]));

            this.openEmailDraft(values);
            this.opened = true;
        },

        openEmailDraft({ name, email: from, mobile, topic, message }) {
            const subject = `Website enquiry: ${topic} — ${name}`;
            const body = [message, '', '—', `Name: ${name}`, `Email: ${from}`, `Mobile: ${mobile}`, `Topic: ${topic}`].join('\n');

            window.location.href = `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
        },
    };
}
