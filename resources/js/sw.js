import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
import { initReveal, initCountUp } from './sw/reveal';
import { navBar } from './sw/nav';
import { showcase } from './sw/showcase';
import { companySelectorWidget } from './sw/company-selector';
import { nsePricePage } from './sw/nse-price-page';
import { tocSpy } from './sw/toc';
import { unlistedShares } from './sw/unlisted-shares';
import { nsePriceChart } from './sw/nse-price-chart';
import { caseStudyChart } from './sw/case-study-chart';
import { registerCountupDirective } from './sw/countup-directive';
import { reliancePage } from './sw/reliance-page';
import { fixedDeposits } from './sw/fixed-deposits';
import { screener } from './sw/screener';
import { sipCalculator } from './sw/sip-calculator';
import { downloadGate } from './sw/download-gate';

Alpine.plugin(intersect);
Alpine.data('navBar', navBar);
Alpine.data('showcase', showcase);
Alpine.data('companySelectorWidget', companySelectorWidget);
Alpine.data('nsePricePage', nsePricePage);
Alpine.data('tocSpy', tocSpy);
Alpine.data('unlistedShares', unlistedShares);
Alpine.data('nsePriceChart', nsePriceChart);
Alpine.data('caseStudyChart', caseStudyChart);
Alpine.data('reliancePage', reliancePage);
Alpine.data('fixedDeposits', fixedDeposits);
Alpine.data('screener', screener);
Alpine.data('sipCalculator', sipCalculator);
Alpine.data('downloadGate', downloadGate);
registerCountupDirective(Alpine);

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    initReveal();
    initCountUp();
});
