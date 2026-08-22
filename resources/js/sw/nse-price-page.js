import { companySelectorState } from './company-selector';

// See the comment in company-selector.js: `company`/`filteredCompanies` must
// be defined directly here, not spread in as getters from another factory.
export function nsePricePage(initialSlug) {
    return {
        ...companySelectorState(initialSlug),
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
