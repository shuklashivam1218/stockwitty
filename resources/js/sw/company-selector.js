export function companySelectorState(initialSlug) {
    return {
        companies: [],
        open: false,
        search: '',
        slug: initialSlug,

        initCompanySelector() {
            this.companies = JSON.parse(this.$el.dataset.companies || '[]');
        },

        selectCompany(slug) {
            this.slug = slug;
            this.open = false;
            this.search = '';
        },
    };
}

// `company`/`filteredCompanies` must be defined directly on each factory's
// returned object (not spread in from companySelectorState) — object spread
// evaluates getters immediately and copies the resulting value, not a live
// getter, which would freeze `company` to its value before init() ever runs.
export function companySelectorWidget(slug) {
    return {
        ...companySelectorState(slug),
        init() {
            this.initCompanySelector();
        },
        get company() {
            return this.companies.find((c) => c.slug === this.slug) || this.companies[0] || {};
        },
        get filteredCompanies() {
            const q = this.search.trim().toLowerCase();
            if (!q) return this.companies;
            return this.companies.filter(
                (c) => c.name.toLowerCase().includes(q) || c.initials.toLowerCase().includes(q)
            );
        },
    };
}
