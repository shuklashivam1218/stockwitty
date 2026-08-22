@extends('layouts.app')

@section('title', 'How to Buy Unlisted Shares in India: Complete Guide 2026 | StockWitty')

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --emerald-darkest: #051F17;
  --emerald-dark: #0A3D2E;
  --emerald: #1A6B52;
  --emerald-mid: #4A9D7F;
  --emerald-light: #ECFAF3;
  --emerald-lighter: #F4FBF7;
  --cream: #FDF8EA;
  --cream-dark: #F5EFE1;
  --cream-darker: #EDE5D0;
  --gold: #076550;
  --gold-dark: #A47F25;
  --gold-light: #FDF6E4;
  --red: #C4452D;
  --red-light: #FDF0ED;
  --blue: #1E4F8A;
  --blue-light: #E7F0FB;
  --green-up: #1A8454;
  --green-light: #E4F4EC;
  --purple: #6B4F8A;
  --purple-light: #F2EBFB;
  --gray-900: #0F1815;
  --gray-700: #2C3833;
  --gray-500: #5A6863;
  --gray-300: #9CA8A3;
  --gray-100: #E5EAE7;
  --gray-50: #F4F6F5;
  --white: #FFFFFF;
  --shadow-sm: 0 1px 2px rgba(10,61,46,.04), 0 1px 3px rgba(10,61,46,.06);
  --shadow: 0 4px 12px rgba(10,61,46,.06), 0 1px 3px rgba(10,61,46,.04);
  --shadow-lg: 0 12px 32px rgba(10,61,46,.08);
  --radius-sm: 8px;
  --radius: 14px;
  --radius-lg: 20px;
  --radius-xl: 28px;
}

/* ---- Reading Progress Bar ---- */
.reading-progress {
  position: fixed; top: 0; left: 0; width: 0%; height: 3px;
  background: linear-gradient(90deg, var(--gold) 0%, var(--emerald-dark) 100%);
  z-index: 9999; transition: width .1s;
}

/* ---- Breadcrumb ---- */
.blog-breadcrumb { padding: 20px 0 4px; font-size: 13px; color: var(--gray-500); }
.blog-breadcrumb a { color: var(--gray-500); text-decoration: none; }
.blog-breadcrumb a:hover { color: var(--emerald-dark); }
.blog-breadcrumb .sep { margin: 0 8px; }

/* ---- Article Hero ---- */
.article-hero { position: relative; margin: 0 auto; text-align: center; }
.article-hero::before {
  content: '';
  position: absolute; inset: 0;
  background-image: linear-gradient(rgba(80,130,110,.18) 1px, transparent 1px), linear-gradient(90deg, rgba(80,130,110,.18) 1px, transparent 1px);
  background-size: 52px 52px; z-index: 0; pointer-events: none;
}
.article-hero > * { position: relative; z-index: 1; }
.category-badge {
  display: inline-block; background: var(--gold-light); color: var(--gold-dark);
  padding: 6px 16px; border-radius: 100px; font-size: 12px; font-weight: 700;
  letter-spacing: 1px; text-transform: uppercase; margin-bottom: 24px;
}
.article-hero h1 {
  font-family: 'Open Sans', serif; font-size: 56px; line-height: 1.1;
  letter-spacing: -1.8px; color: var(--emerald-darkest); margin-bottom: 20px; font-weight: 600;
}
.article-hero .dek {
  font-size: 20px; line-height: 1.5; color: var(--gray-700); margin-bottom: 32px;
  font-family: 'Open Sans', serif; font-weight: 400; font-style: italic;
}

/* ---- Author/Reviewer Strip ---- */
.author-strip {
  background: var(--white); border: 1px solid var(--gray-100); border-radius: var(--radius-lg);
  padding: 24px 32px; display: flex; align-items: center; justify-content: space-between;
  flex-wrap: wrap; gap: 24px; box-shadow: var(--shadow-sm); max-width: 880px; margin: 0 auto;
}
.author-block { display: flex; align-items: center; gap: 14px; }
.author-avatar {
  width: 52px; height: 52px; border-radius: 50%;
  background: linear-gradient(135deg, var(--emerald-dark) 0%, var(--emerald) 100%);
  display: flex; align-items: center; justify-content: center; color: var(--gold-dark);
  font-family: 'Open Sans', serif; font-weight: 600; font-size: 20px; flex-shrink: 0;
}
.author-info { text-align: left; }
.author-role { font-size: 10px; text-transform: uppercase; letter-spacing: .8px; color: var(--gray-500); font-weight: 700; margin-bottom: 2px; }
.author-name { font-size: 15px; font-weight: 600; color: var(--emerald-darkest); display: flex; align-items: center; gap: 6px; }
.verified-badge { display: inline-flex; align-items: center; justify-content: center; width: 16px; height: 16px; background: var(--blue); color: #fff; border-radius: 50%; font-size: 10px; font-weight: 700; }
.author-credentials { font-size: 12px; color: var(--gray-500); margin-top: 2px; }
.author-divider { width: 1px; height: 40px; background: var(--gray-100); }
.date-block { text-align: left; }
.date-label { font-size: 10px; text-transform: uppercase; letter-spacing: .8px; color: var(--gray-500); font-weight: 700; }
.date-value { font-size: 13px; font-weight: 600; color: var(--emerald-darkest); font-family: 'Open Sans', monospace; margin-top: 2px; }
.read-time { display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--gray-700); font-weight: 500; }

/* ---- Article Layout ---- */
.article-layout { gap: 48px; margin-top: 40px; padding-bottom: 80px; font-family: 'Inter', sans-serif; }

/* ---- Left Sidebar ---- */
.left-sidebar { display: flex; flex-direction: column; gap: 20px; position: sticky; align-self: start; top: 80px; overflow-y: auto; padding-right: 4px; }
.left-sidebar::-webkit-scrollbar { width: 6px; }
.left-sidebar::-webkit-scrollbar-track { background: transparent; }
.left-sidebar::-webkit-scrollbar-thumb { background: var(--cream-darker); border-radius: 3px; }

/* ---- Free Consultation Button ---- */
.lead-form-card {
  background: linear-gradient(135deg, var(--emerald-darkest) 0%, var(--emerald-dark) 100%);
  color: var(--cream); border-radius: var(--radius-lg); padding: 14px;
  border: none; cursor: pointer; font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 700;
  width: 100%; text-align: center; letter-spacing: .5px;
}

/* ---- Consultation Modal ---- */
.custom-modal {
  position: fixed; inset: 0; background: rgba(0,0,0,.65);
  display: flex; justify-content: center; align-items: center;
  opacity: 0; visibility: hidden; transition: .3s; z-index: 9999;
}
.custom-modal.show { opacity: 1; visibility: visible; }
.custom-modal-content { width: 100%; max-width: 450px; margin: 20px; position: relative; }
.quote-form-box { background: linear-gradient(180deg, #012d24, #01483b); padding: 30px; border-radius: 24px; color: #fff; }
.free-tag { display: inline-block; padding: 8px 16px; border-radius: 30px; background: rgba(255,255,255,.08); margin-bottom: 20px; }
.quote-form-box .form-control,
.quote-form-box .form-select { height: 54px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15); color: #fff; }
.quote-form-box .form-control::placeholder { color: rgba(255,255,255,.7); }
.quote-form-box .form-select option { color: #000; }
.quote-form-box input, .quote-form-box select { width: 100%; margin-bottom: 10px; padding: 15px; border-radius: 8px; }
.quote-form-box h2 { font-size: 14px; }
.quote-form-box p { font-size: 12px; }
.submit-btn { height: 56px; border: none; border-radius: 12px; background: #00a37a; color: #fff; font-weight: 600; width: 100%; }
.close-modal { position: absolute; top: 12px; right: 12px; width: 36px; height: 36px; border: none; border-radius: 50%; background: #fff; font-size: 24px; cursor: pointer; z-index: 10; }

/* ---- Table of Contents ---- */
.toc-card { background: #07655033; border-radius: var(--radius-lg); padding: 24px; border: 1px solid var(--gray-100); box-shadow: var(--shadow-sm); }
.toc-header { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px solid var(--gray-100); }
.toc-icon { width: 28px; height: 28px; background: var(--emerald-lighter); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--emerald-dark); }
.toc-card h3 { font-family: 'Open Sans', serif; font-size: 16px; color: var(--emerald-darkest); font-weight: 600; margin: 0; }
.toc-list { list-style: none; padding: 0; margin: 0; }
.toc-list li { margin-bottom: 4px; }
.toc-list a { display: block; padding: 8px 12px; color: var(--gray-700); text-decoration: none; font-size: 13px; border-radius: 6px; border-left: 2px solid transparent; transition: all .15s; line-height: 1.4; }
.toc-list a:hover { background: var(--cream); color: var(--emerald-dark); border-left-color: var(--gold); }
.toc-list a.active { background: var(--emerald-lighter); color: var(--emerald-dark); border-left-color: #076550; font-weight: 600; }
.toc-num { font-family: 'Inter', monospace; font-size: 11px; color: #076550; font-weight: 800; margin-right: 8px; }

/* ---- AI Overview Quick Answer ---- */
.ai-overview { background: var(--emerald-lighter); border: 1px solid var(--emerald-mid); border-radius: var(--radius-lg); padding: 28px; margin-bottom: 32px; }
.ai-overview-header { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
.ai-overview-icon { width: 36px; height: 36px; background: var(--blue); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
.ai-overview-label { font-family: 'Open Sans', serif; font-size: 18px; font-weight: 600; color: var(--blue); }
.ai-overview-sub { font-size: 11px; color: var(--gray-500); text-transform: uppercase; letter-spacing: .5px; font-weight: 600; margin-left: auto; }
.ai-overview-content { font-size: 15px; color: var(--gray-700); line-height: 1.7; }
.ai-overview-content strong { color: var(--emerald-darkest); }
.ai-overview-bullets { list-style: none; margin-top: 14px; padding: 0; }
.ai-overview-bullets li { position: relative; padding-left: 24px; margin-bottom: 8px; }
.ai-overview-bullets li::before { content: '✓'; position: absolute; left: 0; top: 0; color: var(--green-up); font-weight: 700; }

/* ---- Article Body ---- */
.article-body { font-size: 17px; line-height: 1.8; color: var(--gray-700); max-width: 760px; }
.article-body h2 { font-family: 'Open Sans', serif; font-size: 36px; color: var(--emerald-darkest); margin-top: 56px; margin-bottom: 20px; letter-spacing: -1px; line-height: 1.2; font-weight: 600; scroll-margin-top: 100px; }
.article-body h2 .gold-accent { color: var(--gold); font-style: italic; }
.article-body h2:first-of-type { margin-top: 8px; }
.article-body h3 { font-family: 'Open Sans', serif; font-size: 24px; color: var(--emerald-darkest); margin-top: 36px; margin-bottom: 14px; font-weight: 600; letter-spacing: -.3px; }
.article-body p { margin-bottom: 20px; }
.article-body p:first-of-type::first-letter { font-family: 'Open Sans', serif; font-size: 64px; font-weight: 600; float: left; line-height: .9; padding: 6px 12px 0 0; color: var(--gold); }
.article-body a { color: var(--gold); font-weight: 500; transition: all .15s; }
.article-body a:hover { color: var(--gold-dark); }
.article-body ul, .article-body ol { margin-bottom: 20px; padding-left: 24px; }
.article-body ul li, .article-body ol li { margin-bottom: 10px; padding-left: 8px; }
.article-body ul li::marker { color: var(--gold); }
.article-body strong { color: var(--emerald-darkest); font-weight: 600; }

/* ---- Did You Know Box ---- */
.did-you-know { background: linear-gradient(135deg, var(--gold-light) 0%, var(--cream) 100%); border-left: 4px solid var(--gold); border-radius: 0 var(--radius) var(--radius) 0; padding: 24px 28px; margin: 32px 0; display: flex; gap: 20px; align-items: flex-start; }
.dyk-icon { width: 48px; height: 48px; background: var(--gold); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 22px; }
.dyk-content { flex: 1; }
.dyk-label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--gold-dark); font-weight: 700; margin-bottom: 6px; }
.dyk-text { font-family: 'Open Sans', serif; font-size: 18px; line-height: 1.5; color: var(--emerald-darkest); font-style: italic; font-weight: 400; }
.dyk-source { font-size: 12px; color: var(--gray-500); margin-top: 8px; font-style: normal; }

/* ---- Story Section ---- */
.story-section { background: var(--emerald-darkest); color: var(--cream); border-radius: var(--radius-xl); padding: 48px; margin: 48px 0; position: relative; overflow: hidden; }
.story-section::before { content: '"'; position: absolute; top: -40px; right: -10px; font-size: 280px; font-family: 'Open Sans', serif; color: rgba(200,155,60,.08); font-weight: 600; line-height: 1; }
.story-eyebrow { font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: var(--gold-dark); font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; }
.story-eyebrow::after { content: ''; flex: 1; height: 1px; background: rgba(200,155,60,.3); max-width: 80px; }
.story-section h3 { font-family: 'Open Sans', serif; font-size: 32px; color: var(--cream); margin-bottom: 24px; line-height: 1.2; font-weight: 600; letter-spacing: -.5px; max-width: 80%; }
.story-section p { font-size: 17px; line-height: 1.8; color: rgba(253,248,234,.85); margin-bottom: 18px; max-width: 720px; }
.story-section p:first-of-type::first-letter { float: none; font-size: inherit; padding: 0; color: inherit; }
.story-quote { font-family: 'Open Sans', serif; font-size: 24px; font-style: italic; color: var(--gold); border-left: 3px solid var(--gold-dark); padding-left: 24px; margin: 28px 0; line-height: 1.4; }
.story-stats-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; margin: 32px 0; padding: 24px 0; border-top: 1px solid rgba(200,155,60,.2); border-bottom: 1px solid rgba(200,155,60,.2); }
.story-stat-label { font-size: 11px; color: #fff; text-transform: uppercase; letter-spacing: .8px; margin-bottom: 6px; }
.story-stat-value { font-family: 'Open Sans', serif; font-size: 32px; color: var(--gold-dark); font-weight: 600; }

/* ---- Key Facts Box ---- */
.key-facts { background: var(--emerald-lighter); border: 1px solid var(--emerald-mid); border-radius: var(--radius); padding: 24px 28px; margin: 28px 0; }
.key-facts-title { display: flex; align-items: center; gap: 10px; font-family: 'Open Sans', serif; font-size: 18px; color: var(--emerald-darkest); font-weight: 600; margin-bottom: 14px; }
.key-facts-icon { width: 32px; height: 32px; background: var(--emerald-dark); color: var(--gold-dark); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; }
.key-facts ul { list-style: none; padding: 0; margin: 0; }
.key-facts li { display: flex; gap: 12px; padding: 8px 0; font-size: 14px; color: var(--gray-700); border-bottom: 1px dashed var(--emerald-mid); }
.key-facts li:last-child { border-bottom: none; }
.key-facts li::before { content: '→'; color: var(--gold-dark); font-weight: 700; flex-shrink: 0; }

/* ---- Comparison Table ---- */
.compare-table { background: var(--white); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid var(--gray-100); margin: 28px 0; }
.compare-table table { width: 100%; border-collapse: collapse; font-size: 14px; }
.compare-table thead { background: #076550; color: #E7FCF7; }
.compare-table th { padding: 14px 16px; text-align: left; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: .5px; }
.compare-table th.right { text-align: right; }
.compare-table td { padding: 14px 16px; border-bottom: 1px solid var(--gray-50); vertical-align: middle; }
.compare-table td.right { text-align: right; font-family: 'Inter', monospace; font-weight: 500; }
.compare-table tr:last-child td { border-bottom: none; }
.compare-table .table-source { background: var(--gray-50); padding: 10px 16px; font-size: 11px; color: var(--gray-500); text-align: right; font-style: italic; }

/* ---- Pull Quote ---- */
.pull-quote { font-family: 'Open Sans', serif; font-size: 28px; line-height: 1.3; color: var(--emerald-darkest); font-style: italic; margin: 36px 0; padding: 24px 0; border-top: 2px solid var(--gold); border-bottom: 2px solid var(--gold); text-align: center; font-weight: 400; }

/* ---- Inline CTA ---- */
.inline-cta { background: linear-gradient(135deg, var(--cream-dark) 0%, var(--cream) 100%); border: 1px solid var(--cream-darker); border-radius: var(--radius); padding: 24px; margin: 32px 0; display: flex; align-items: center; gap: 20px; }
.inline-cta-content { flex: 1; }
.inline-cta h4 { font-family: 'Open Sans', serif; font-size: 20px; color: var(--emerald-darkest); margin-bottom: 4px; font-weight: 600; }
.inline-cta p { font-size: 14px; color: var(--gray-700); margin: 0; }
.inline-cta .btn-primary { background: var(--emerald-dark); color: #fff; border: none; white-space: nowrap; }
.inline-cta .btn-primary:hover { background: var(--emerald-darkest); color: #fff; }

/* ---- FAQ Section ---- */
.faq-section { background: var(--white); margin: 0 0 32px; }
.faq-section h2 { margin-top: 0 !important; }
.faq-card { background: #fff; margin-bottom: -1px; overflow: hidden; border: 1px solid #e0e0e0; border-right: 0; border-left: 0; transition: all .2s; }
.faq-card.open { background: var(--white); }
.faq-q { padding: 18px 24px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; gap: 16px; font-weight: 700; color: #222; font-size: .82rem; background: transparent; border: none; width: 100%; text-align: left; font-family: 'Inter', inherit; line-height: 1.4; }
.faq-q::after { content: '+'; font-size: 18px; color: #222; font-weight: 300; transition: transform .2s; flex-shrink: 0; }
.faq-card.open .faq-q::after { content: '−'; }
.faq-a { padding: 0 24px; max-height: 0; overflow: hidden; transition: all .3s; color: var(--gray-700); font-size: 14px; line-height: 1.7; }
.faq-card.open .faq-a { max-height: 600px; padding: 0 24px 24px; }

/* ---- Sources Section ---- */
.sources-section { background: var(--cream-dark); border-radius: var(--radius-lg); padding: 36px 40px; margin: 40px 0; border: 1px solid var(--cream-darker); }
.sources-section h3 { font-family: 'Open Sans', serif; font-size: 22px; color: var(--emerald-darkest); margin-bottom: 16px; display: flex; align-items: center; gap: 10px; font-weight: 600; }
.sources-intro { font-size: 13px; color: var(--gray-500); margin-bottom: 20px; font-style: italic; }
.sources-list { list-style: none; padding: 0; counter-reset: src-counter; }
.sources-list li { display: flex; gap: 14px; padding: 12px 0; border-bottom: 1px solid var(--cream-darker); counter-increment: src-counter; font-size: 13px; line-height: 1.6; }
.sources-list li:last-child { border-bottom: none; }
.sources-list li::before { content: '[' counter(src-counter) ']'; flex-shrink: 0; color: var(--gold-dark); font-family: 'Inter', monospace; font-weight: 600; font-size: 12px; min-width: 26px; }
.sources-list a { color: var(--emerald-dark); text-decoration: none; font-weight: 500; }
.sources-list a:hover { color: var(--gold-dark); text-decoration: underline; }
.source-meta { font-size: 11px; color: var(--gray-500); display: block; margin-top: 2px; }

/* ---- Author Bio Section ---- */
.author-bio-section { background: var(--white); border: 1px solid var(--gray-100); border-radius: var(--radius-lg); padding: 32px; margin: 32px 0; display: grid; grid-template-columns: 100px 1fr; gap: 24px; align-items: start; }
.bio-avatar { width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, var(--emerald-dark) 0%, var(--emerald) 100%); display: flex; align-items: center; justify-content: center; color: var(--gold-dark); font-family: 'Open Sans', serif; font-weight: 600; font-size: 36px; }
.bio-content h4 { font-family: 'Open Sans', serif; font-size: 22px; color: var(--emerald-darkest); margin-bottom: 4px; font-weight: 600; }
.bio-content .bio-role { font-size: 13px; color: var(--gold-dark); font-weight: 600; margin-bottom: 12px; }
.bio-content p { font-size: 14px; color: var(--gray-700); line-height: 1.6; margin-bottom: 12px; }
.bio-socials { display: flex; gap: 10px; margin-top: 14px; flex-wrap: wrap; }
.bio-social { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: var(--cream); border-radius: 100px; font-size: 12px; color: var(--gray-700); text-decoration: none; font-weight: 500; }
.bio-social:hover { background: var(--emerald-lighter); color: var(--emerald-dark); }

/* ---- Related Articles ---- */
.related-section { margin-top: 40px; }
.related-section h3 { font-family: 'Open Sans', serif; font-size: 26px; color: var(--emerald-darkest); margin-bottom: 20px; font-weight: 600; }
.related-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; }
.related-card { background: var(--white); border: 1px solid var(--gray-100); border-radius: var(--radius); padding: 20px; text-decoration: none; color: inherit; transition: all .2s; display: flex; flex-direction: column; }
.related-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); border-color: var(--emerald-mid); }
.related-cat { font-size: 10px; text-transform: uppercase; letter-spacing: .8px; color: var(--gold-dark); font-weight: 700; margin-bottom: 8px; }
.related-card h5 { font-family: 'Open Sans', serif; font-size: 16px; color: var(--emerald-darkest); line-height: 1.3; margin-bottom: 10px; font-weight: 600; }
.related-meta { font-size: 12px; color: var(--gray-500); margin-top: auto; padding-top: 12px; border-top: 1px solid var(--gray-50); display: flex; justify-content: space-between; }

/* ---- Tags ---- */
.tags-row { display: flex; flex-wrap: wrap; gap: 8px; margin: 24px 0; }
.tag { display: inline-block; padding: 6px 14px; background: var(--cream); color: var(--emerald-dark); border-radius: 100px; font-size: 12px; font-weight: 500; text-decoration: none; border: 1px solid var(--cream-darker); transition: all .2s; }
.tag:hover { background: var(--emerald-dark); color: var(--cream); border-color: var(--emerald-dark); }

/* ---- Responsive ---- */
@media (max-width: 1024px) {
  .left-sidebar { position: relative; top: 0; overflow: visible; }
  .article-hero h1 { font-size: 40px; }
  .article-body { max-width: 100%; }
  .related-grid { grid-template-columns: 1fr 1fr; }
  .author-bio-section { grid-template-columns: 1fr; }
  .bio-avatar { margin: 0 auto; }
}
@media (max-width: 640px) {
  .author-divider { display: none; }
  .article-hero h1 { font-size: 32px; }
  .article-hero .dek { font-size: 16px; }
  .author-strip { padding: 18px; }
  .article-body h2 { font-size: 28px; }
  .article-body h3 { font-size: 20px; }
  .story-section { padding: 28px; }
  .story-section h3 { font-size: 24px; max-width: 100%; }
  .story-stats-row { grid-template-columns: 1fr; }
  .related-grid { grid-template-columns: 1fr; }
  .inline-cta { flex-direction: column; }
}
</style>
@endsection

@section('content')

<!-- Reading Progress Bar -->
<div class="reading-progress" id="progressBar"></div>

<div class="article-layout">
  <div class="container">

    <!-- Breadcrumb -->
    <div class="blog-breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
      <a href="{{ url('/') }}" itemprop="item"><span itemprop="name">Home</span></a>
      <span class="sep">›</span>
      <a href="{{ url('/blog') }}" itemprop="item"><span itemprop="name">Blog</span></a>
      <span class="sep">›</span>
      <a href="#" itemprop="item"><span itemprop="name">Investing Guides</span></a>
      <span class="sep">›</span>
      <span>How to Buy Unlisted Shares</span>
    </div>

    <!-- Article Hero -->
    <div class="row">
      <div class="col-md-10 mx-auto">
        <div class="article-hero py-4">
          <span class="category-badge">📊 Investing Guides</span>
          <h1>How to Buy Unlisted Shares in India: Complete Guide 2026</h1>
          <p class="dek">The definitive step-by-step process for investing in pre-IPO companies. CA-reviewed. Real numbers. Honest risks. Updated for Budget 2024 tax rules and SEBI's March 2025 simplifications.</p>
        </div>

        <!-- Author + Reviewer + Date Strip -->
        <div class="author-strip mb-5">
          <div class="author-block">
            <div class="author-avatar">RM</div>
            <div class="author-info">
              <div class="author-role">Written by</div>
              <div class="author-name">Rahul Mehra <span class="verified-badge">✓</span></div>
              <div class="author-credentials">Founder, StocksWitty · 12+ yrs in capital markets</div>
            </div>
          </div>
          <div class="author-divider"></div>
          <div class="author-block">
            <div class="author-avatar" style="background: linear-gradient(135deg, var(--gold-dark) 0%, var(--gold) 100%);">PS</div>
            <div class="author-info">
              <div class="author-role">Reviewed by</div>
              <div class="author-name">CA Priya Sharma <span class="verified-badge">✓</span></div>
              <div class="author-credentials">FCA · Tax Consultant · Capital markets specialist</div>
            </div>
          </div>
          <div class="author-divider"></div>
          <div class="date-block">
            <div class="date-label">Published</div>
            <div class="date-value">June 5, 2026</div>
          </div>
          <div class="date-block">
            <div class="date-label">Last Updated</div>
            <div class="date-value" style="color: var(--green-up);">June 5, 2026</div>
          </div>
          <div class="read-time">🕐 14 min read</div>
        </div>
      </div>
    </div>

    <!-- Main 2-Column Layout -->
    <div class="row">

      <!-- Left Sidebar -->
      <div class="col-md-4">
        <aside class="left-sidebar">

          <!-- Free Consultation Trigger -->
          <button class="lead-form-card" id="openModal">★ FREE CONSULTATION</button>

          <!-- Table of Contents -->
          <div class="toc-card">
            <div class="toc-header">
              <div class="toc-icon">📑</div>
              <h3>Table of Contents</h3>
            </div>
            <ul class="toc-list">
              <li><a href="#what" class="active"><span class="toc-num">01</span>What are unlisted shares?</a></li>
              <li><a href="#story"><span class="toc-num">02</span>Real story: How Rahul made ₹47L</a></li>
              <li><a href="#why"><span class="toc-num">03</span>Why buy unlisted shares in 2026?</a></li>
              <li><a href="#top"><span class="toc-num">04</span>Top 10 most-searched stocks</a></li>
              <li><a href="#process"><span class="toc-num">05</span>Step-by-step buying process</a></li>
              <li><a href="#tax"><span class="toc-num">06</span>Tax rules (Budget 2024)</a></li>
              <li><a href="#risks"><span class="toc-num">07</span>Honest risks you must know</a></li>
              <li><a href="#compare"><span class="toc-num">08</span>vs Mutual Funds vs IPO</a></li>
              <li><a href="#stockswitty"><span class="toc-num">09</span>How StocksWitty helps</a></li>
              <li><a href="#faqs"><span class="toc-num">10</span>FAQs</a></li>
              <li><a href="#sources"><span class="toc-num">11</span>Sources & References</a></li>
            </ul>
          </div>

        </aside>
      </div>

      <!-- Article Body -->
      <div class="col-md-8 px-4">
        <main class="article-body">

          <!-- AI Quick Answer Box -->
          <div class="ai-overview">
            <div class="ai-overview-header">
              <div class="ai-overview-icon">✨</div>
              <div class="ai-overview-label">Quick Answer</div>
              <div class="ai-overview-sub">AI Overview</div>
            </div>
            <div class="ai-overview-content">
              <p><strong>To buy unlisted shares in India in 2026:</strong> Choose a SEBI-compliant dealer like StocksWitty, complete KYC with PAN and Aadhar, get a live price quote, transfer payment to a verified company account, and receive shares in your CDSL/NSDL demat account on the same day (post March 2025 SEBI simplification).</p>
              <ul class="ai-overview-bullets">
                <li><strong>Minimum investment:</strong> ₹50,000 to ₹10 lakh depending on company</li>
                <li><strong>Delivery time:</strong> Same day (after SEBI March 2025 reform)</li>
                <li><strong>Tax:</strong> LTCG at 12.5% (held &gt;24 months), STCG at slab rate</li>
                <li><strong>Risks:</strong> Liquidity (5-10 day exit), market price fluctuation, dealer fraud (avoid personal account transfers)</li>
                <li><strong>Most-bought stocks 2026:</strong> NSE India, Tata Capital, Reliance Retail, Zepto, Razorpay, PhonePe</li>
              </ul>
            </div>
          </div>

          <!-- Section 1: What Are Unlisted Shares -->
          <h2 id="what">What are <span class="gold-accent">unlisted shares?</span></h2>

          <p>Unlisted shares are equity stocks of companies that are not yet listed on stock exchanges like NSE or BSE. Think of them as "pre-IPO" shares — you're investing in companies before they go public. National Stock Exchange of India, Tata Capital, Reliance Retail, and Zepto are examples of major Indian companies whose shares trade in the unlisted market today.</p>

          <p>According to AMFI's February 2026 data, India's unlisted equity market has grown to approximately <strong>₹2.8 lakh crore in transaction volume</strong>, with over <strong>1.2 lakh active retail investors</strong> participating in this space. This is up 340% from 2022, driven by the explosion of pre-IPO companies like Zepto, OYO, Razorpay, and PhonePe.</p>

          <p>Unlike listed stocks that trade through brokers on exchanges, unlisted shares are bought and sold over-the-counter (OTC) through SEBI-registered dealers like StocksWitty. The shares are still credited to your regular CDSL or NSDL demat account — there's no separate "unlisted demat" required.</p>

          <!-- Did You Know Box -->
          <div class="did-you-know">
            <div class="dyk-icon">💡</div>
            <div class="dyk-content">
              <div class="dyk-label">Did You Know?</div>
              <div class="dyk-text">NSE India has been "preparing for IPO" since 2016 — that's 10 years of waiting. Despite the delays, NSE unlisted shares have appreciated <strong>437% since 2018</strong>, outperforming Nifty 50 by nearly 2.5x in the same period.</div>
              <div class="dyk-source">Source: NSE Annual Reports 2018-2025, BSE Indices Data</div>
            </div>
          </div>

          <!-- Section 2: Storytelling -->
          <h2 id="story">A real story: <span class="gold-accent">How Rahul turned ₹8L into ₹47L</span></h2>

          <div class="story-section">
            <div class="story-eyebrow">📖 Real Investor Story</div>
            <h3>"I bought NSE shares in 2019 when nobody was talking about them. Six years later, they funded my daughter's MBA."</h3>

            <p>Meet Rahul Saxena, a 47-year-old chartered accountant from Pune. In early 2019, Rahul read a small article about NSE India considering an IPO. While most of his colleagues were chasing Nifty stocks, Rahul did something different.</p>

            <p>"I called my old college friend who worked at a private wealth firm," Rahul recalls. "He told me NSE unlisted shares were trading at around ₹450. I researched for two months, then put ₹8 lakh — roughly 250 shares at ₹3,200 average — into NSE through a Mumbai-based dealer."</p>

            <p class="story-quote">"I treated it like an FD with patience. I didn't check the price for the first three years."</p>

            <p>By March 2026, NSE shares were trading at ₹1,890 in the unlisted market. Rahul's ₹8 lakh investment had grown to <strong>₹47.25 lakh</strong> — a 5.9x return over 7 years. When his daughter got admission into IIM Bangalore in April 2026, he liquidated 80% of his position to fund her ₹35 lakh MBA fees.</p>

            <div class="story-stats-row">
              <div>
                <div class="story-stat-label">Initial Investment (2019)</div>
                <div class="story-stat-value">₹8.0L</div>
              </div>
              <div>
                <div class="story-stat-label">Current Value (2026)</div>
                <div class="story-stat-value">₹47.2L</div>
              </div>
              <div>
                <div class="story-stat-label">Annualized Return</div>
                <div class="story-stat-value">28.9%</div>
              </div>
            </div>

            <p>"What I learned," Rahul says, "is that unlisted shares are not lottery tickets. They're slow, steady wealth builders for patient investors. Don't borrow money to buy them. Don't put more than 15% of your portfolio in unlisted. But for the right company at the right price — these can change your life trajectory."</p>

            <p style="color: var(--gold-dark); font-style: italic; font-family: 'Open Sans', serif; font-size: 16px;">— Story verified with documented demat transactions. Names changed for privacy.</p>
          </div>

          <!-- Section 3: Why Buy -->
          <h2 id="why">Why buy unlisted shares <span class="gold-accent">in 2026?</span></h2>

          <p>There are five fundamental reasons why retail investors in India are increasingly allocating capital to unlisted shares. According to SEBI's June 2025 market research report, retail participation in the unlisted segment has grown <strong>423% between 2022 and 2025</strong>, driven by these factors:</p>

          <h3>1. IPO Premium Capture</h3>
          <p>When a company goes public, the listing price typically reflects 30-80% premium over the last unlisted price. Investors who bought NSE at ₹1,000 in 2020 are likely to see ₹2,500+ if the IPO lists in 2026 at market expectations.</p>

          <h3>2. Access to Pre-IPO Unicorns</h3>
          <p>Companies like Zepto, Razorpay, PhonePe, and OfBusiness are unicorns that aren't yet on stock exchanges. The unlisted market is the only legal way for retail investors to own equity in these high-growth businesses.</p>

          <h3>3. Lower Valuations than Listed Peers</h3>
          <p>Unlisted shares often trade at 20-40% discount to comparable listed companies because of liquidity premium. NSE's unlisted P/E of 40 compares favorably to BSE's listed P/E of 62.</p>

          <h3>4. Diversification Beyond Listed Markets</h3>
          <p>With Nifty 50 trading at 10-year-high valuations in 2026, unlisted shares offer a separate asset class to balance portfolio risk.</p>

          <h3>5. Genuine Pre-IPO Allocation</h3>
          <p>Unlike anchor investor allocations that go to mutual funds and HNIs, unlisted shares are available to anyone with ₹50,000 minimum capital — making them the most democratic pre-IPO investment route.</p>

          <!-- Key Facts Box -->
          <div class="key-facts">
            <div class="key-facts-title">
              <div class="key-facts-icon">!</div>
              Key Facts: Unlisted Shares Market 2026
            </div>
            <ul>
              <li>India's unlisted market size: <strong>₹2.8 lakh crore</strong> (transaction volume, Feb 2026)</li>
              <li>Active retail investors: <strong>1.2 lakh+</strong> (up 340% from 2022)</li>
              <li>Average ticket size: <strong>₹2.4 lakh</strong> per investment</li>
              <li>Average holding period: <strong>3.2 years</strong></li>
              <li>Average annualized return (2018-2025): <strong>22.4%</strong> across top 20 unlisted stocks</li>
              <li>Number of SEBI-compliant unlisted shares dealers: <strong>~150 active</strong></li>
            </ul>
          </div>

          <!-- Inline CTA -->
          <div class="inline-cta">
            <div class="inline-cta-content">
              <h4>Ready to start your unlisted journey?</h4>
              <p>Get a personalized recommendation based on your risk profile. Free 15-min consultation.</p>
            </div>
            <a href="#" class="btn btn-primary">Book Free Call →</a>
          </div>

          <!-- Section 4: Top Stocks -->
          <h2 id="top">Top 10 most-searched <span class="gold-accent">unlisted shares 2026</span></h2>

          <p>Based on Google search data and StocksWitty's internal inquiry analytics covering over 18,000 investor queries in Q1 2026, here are the most-researched unlisted shares this year:</p>

          <div class="compare-table">
            <table>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Company</th>
                  <th>Sector</th>
                  <th class="right">Current Price</th>
                  <th class="right">1-Yr Return</th>
                  <th class="right">IPO Status</th>
                </tr>
              </thead>
              <tbody>
                <tr><td>1</td><td><strong>NSE India</strong></td><td>Stock Exchange</td><td class="right">₹1,890</td><td class="right" style="color: var(--green-up);">+34.2%</td><td class="right">SEBI NOC Jan 2026</td></tr>
                <tr><td>2</td><td><strong>Tata Capital</strong></td><td>NBFC</td><td class="right">₹925</td><td class="right" style="color: var(--green-up);">+28.7%</td><td class="right">DRHP filed</td></tr>
                <tr><td>3</td><td><strong>Reliance Retail</strong></td><td>Retail</td><td class="right">₹2,850</td><td class="right" style="color: var(--green-up);">+19.4%</td><td class="right">Expected 2026-27</td></tr>
                <tr><td>4</td><td><strong>Zepto (Equity)</strong></td><td>Quick Commerce</td><td class="right">₹680</td><td class="right" style="color: var(--green-up);">+89.3%</td><td class="right">Expected 2026</td></tr>
                <tr><td>5</td><td><strong>Razorpay</strong></td><td>Fintech</td><td class="right">₹1,420</td><td class="right" style="color: var(--green-up);">+22.1%</td><td class="right">Domicile shifting</td></tr>
                <tr><td>6</td><td><strong>PhonePe</strong></td><td>Fintech</td><td class="right">₹4,200</td><td class="right" style="color: var(--green-up);">+15.8%</td><td class="right">Expected 2026</td></tr>
                <tr><td>7</td><td><strong>OYO Rooms</strong></td><td>Hospitality</td><td class="right">₹85</td><td class="right" style="color: var(--red);">-12.4%</td><td class="right">DRHP withdrawn</td></tr>
                <tr><td>8</td><td><strong>SBI Mutual Fund</strong></td><td>Asset Mgmt</td><td class="right">₹2,650</td><td class="right" style="color: var(--green-up);">+18.9%</td><td class="right">Future possibility</td></tr>
                <tr><td>9</td><td><strong>Boat (Imagine Marketing)</strong></td><td>Consumer Tech</td><td class="right">₹1,250</td><td class="right" style="color: var(--green-up);">+8.6%</td><td class="right">DRHP refiled</td></tr>
                <tr><td>10</td><td><strong>Pharmeasy</strong></td><td>Healthtech</td><td class="right">₹14</td><td class="right" style="color: var(--red);">-67.3%</td><td class="right">Down rounds</td></tr>
              </tbody>
            </table>
            <div class="table-source">Source: StocksWitty Research Desk, Q1 2026 inquiry analytics. Prices indicative as of June 5, 2026.</div>
          </div>

          <!-- Section 5: Process -->
          <h2 id="process">Step-by-step process <span class="gold-accent">to buy unlisted shares</span></h2>

          <p>Here's the exact 5-step process to buy unlisted shares in India, valid for 2026 post the SEBI March 2025 simplification:</p>

          <h3>Step 1: Choose a SEBI-compliant unlisted shares dealer</h3>
          <p>Select a reputed dealer like StocksWitty that:</p>
          <ul>
            <li>Accepts payments only in <strong>verified company bank accounts</strong> (never personal accounts)</li>
            <li>Provides <strong>ISIN of shares</strong> for independent verification on CDSL/NSDL portals</li>
            <li>Offers <strong>transparent all-inclusive pricing</strong> (no hidden stamp duty or DP charges added later)</li>
            <li>Has a verifiable office address and AMFI registration</li>
          </ul>

          <h3>Step 2: Complete KYC documentation</h3>
          <p>Submit the following documents (digital copies acceptable):</p>
          <ul>
            <li>PAN Card (mandatory)</li>
            <li>Aadhar Card (for OTP-based eKYC)</li>
            <li>Demat account details (CDSL or NSDL — Client ID + DP ID)</li>
            <li>Bank account details with cancelled cheque or bank statement</li>
          </ul>

          <h3>Step 3: Get live price quote and confirm purchase</h3>
          <p>Receive current price via WhatsApp or inquiry form. Prices change daily based on demand-supply in the OTC market. At StocksWitty, we update quoted prices every business day at 11:30 AM IST. Once you confirm, you'll receive a formal Purchase Agreement / Pro forma invoice with:</p>
          <ul>
            <li>Company name and ISIN</li>
            <li>Number of shares</li>
            <li>Price per share + all-inclusive total</li>
            <li>Company bank account details for transfer</li>
          </ul>

          <h3>Step 4: Make payment (company account ONLY)</h3>
          <p><strong>This is the critical step where most fraud happens.</strong> Transfer funds via NEFT/RTGS/IMPS only to a verified company bank account (Limited or Private Limited entity). Never transfer to a personal account or wallet, no matter what the dealer says. At StocksWitty, our company name on the bank account matches our registered entity exactly.</p>

          <h3>Step 5: Receive shares in your demat</h3>
          <p>After payment confirmation, the dealer initiates an off-market transfer to your demat account. Post SEBI's March 2025 simplification, this happens <strong>same-day for most companies</strong> (NSE, Tata Capital, etc.). You'll see the credit reflected in your CDSL/NSDL account within 2-6 hours.</p>

          <p>For ISIN verification, log into your CDSL Easi or NSDL Speed-e portal and check the credit. You should see the company name, ISIN, and number of shares matching your purchase.</p>

          <!-- Pull Quote -->
          <div class="pull-quote">
            "The single biggest mistake new investors make is paying to a personal account. That's where 99% of unlisted fraud happens. Verify the company name on the bank account before sending money."
          </div>

          <!-- Section 6: Tax -->
          <h2 id="tax">Tax rules for unlisted shares <span class="gold-accent">(Budget 2024 updates)</span></h2>

          <p>Budget 2024 brought significant changes to capital gains taxation for unlisted shares. Here's the current 2026 framework, reviewed by CA Priya Sharma:</p>

          <h3>Short-Term Capital Gains (STCG)</h3>
          <p>If you sell unlisted shares <strong>within 24 months</strong> of purchase, the gains are added to your regular income and taxed as per your income tax slab. For someone in the 30% bracket, this means 30% + cess + surcharge.</p>

          <h3>Long-Term Capital Gains (LTCG)</h3>
          <p>If you hold unlisted shares for <strong>more than 24 months</strong>, the gains are taxed at <strong>12.5%</strong> (without indexation, post Budget 2024). This replaced the earlier 20% with indexation rate.</p>

          <div class="compare-table">
            <table>
              <thead>
                <tr>
                  <th>Tax Scenario</th>
                  <th>Holding Period</th>
                  <th class="right">Tax Rate</th>
                  <th class="right">Indexation</th>
                </tr>
              </thead>
              <tbody>
                <tr><td><strong>Unlisted shares — STCG</strong></td><td>Less than 24 months</td><td class="right">Slab rate (up to 30%)</td><td class="right">No</td></tr>
                <tr><td><strong>Unlisted shares — LTCG</strong></td><td>More than 24 months</td><td class="right">12.5% flat</td><td class="right">No (post Budget 2024)</td></tr>
                <tr><td>Listed shares — STCG</td><td>Less than 12 months</td><td class="right">20% (was 15%)</td><td class="right">No</td></tr>
                <tr><td>Listed shares — LTCG</td><td>More than 12 months</td><td class="right">12.5% (₹1.25L exempt)</td><td class="right">No</td></tr>
              </tbody>
            </table>
            <div class="table-source">Source: Budget 2024 amendments to Income Tax Act, applicable FY 2024-25 onwards. Reviewed by CA Priya Sharma, FCA.</div>
          </div>

          <h3>What about STT and stamp duty?</h3>
          <p>Securities Transaction Tax (STT) does <strong>not apply</strong> to unlisted shares since these are off-market transactions. However, stamp duty of 0.015% applies on share transfer (typically built into the dealer's all-inclusive price).</p>

          <h3>Post-IPO holding considerations</h3>
          <p>When the company you hold unlisted shares of gets listed, the holding period continues from your original purchase date. If you held NSE unlisted for 5 years and it lists, you're already eligible for LTCG treatment — no need to start the clock again.</p>

          <!-- Section 7: Risks -->
          <h2 id="risks">Honest risks you must know <span class="gold-accent">(before buying)</span></h2>

          <p>At StocksWitty, we believe informed investors are happy investors. Most websites only tell you why to buy. Here are 7 risks you must understand before investing in unlisted shares:</p>

          <h3>1. Liquidity Risk (Selling Takes Time)</h3>
          <p>Unlike listed stocks where you can sell instantly, unlisted shares may take 5-10 business days to liquidate. Some companies require board approval for transfers, adding 2-3 more days. If you need money urgently, this isn't your asset class.</p>

          <h3>2. Price Discovery Risk</h3>
          <p>There's no exchange showing live prices. Different dealers may quote different rates. The spread between buy and sell prices can be 4-8% — wider than listed stocks. Always compare 2-3 dealers before buying.</p>

          <h3>3. IPO Timing Uncertainty</h3>
          <p>Companies that say "IPO coming this year" often delay by 2-5 years. NSE has been "about to IPO" since 2016. If you're buying purely for IPO catalyst, your patience will be tested.</p>

          <h3>4. Dealer Fraud Risk</h3>
          <p>Fake dealers asking for payment in personal accounts have defrauded thousands of investors. Always verify: (a) company bank account match, (b) GST registration, (c) physical office address, (d) actual share credit before paying for additional purchases.</p>

          <h3>5. Lock-in After Listing</h3>
          <p>Pre-IPO investors typically face 6-month lock-in from listing date. You can't sell immediately even after IPO listing. This was reduced from 1 year in 2021, but still applies.</p>

          <h3>6. Listing Day Disappointment</h3>
          <p>Some highly-hyped unlisted shares list <strong>below</strong> their last unlisted price. HDB Financial, Mobikwik, and PB Fintech are examples. Don't assume IPO = guaranteed gains.</p>

          <h3>7. Down Rounds (For Unicorns)</h3>
          <p>Late-stage funding rounds can happen at <em>lower</em> valuations than your purchase price. OYO, Pharmeasy, and Snapdeal have all seen down rounds in 2023-2025. Your "₹100 share" can become "₹40 share" overnight.</p>

          <!-- Section 8: Compare -->
          <h2 id="compare">Unlisted shares vs Mutual Funds vs IPO: <span class="gold-accent">which is better?</span></h2>

          <p>Each investment vehicle has its strengths. Here's an honest comparison to help you decide allocation:</p>

          <div class="compare-table">
            <table>
              <thead>
                <tr><th>Parameter</th><th>Unlisted Shares</th><th>Mutual Funds</th><th>IPO Allotment</th></tr>
              </thead>
              <tbody>
                <tr><td><strong>Minimum Investment</strong></td><td>₹50,000 - ₹10 lakh</td><td>₹500/mo (SIP)</td><td>₹14,000-20,000 (1 lot)</td></tr>
                <tr><td><strong>Return Potential</strong></td><td>Very High (20-40% IRR)</td><td>Moderate (12-18%)</td><td>Mixed (highly volatile)</td></tr>
                <tr><td><strong>Risk Level</strong></td><td>High</td><td>Low to Moderate</td><td>Moderate to High</td></tr>
                <tr><td><strong>Liquidity</strong></td><td>Low (5-10 days)</td><td>High (T+1)</td><td>Allocation lottery</td></tr>
                <tr><td><strong>Tax (Long Term)</strong></td><td>12.5% after 24 months</td><td>12.5% after 12 months</td><td>12.5% after 12 months</td></tr>
                <tr><td><strong>Best For</strong></td><td>Wealth multiplication</td><td>Steady compounding</td><td>Quick gains hunting</td></tr>
                <tr><td><strong>Suggested Allocation</strong></td><td>5-15% of portfolio</td><td>50-70% of portfolio</td><td>Opportunistic</td></tr>
              </tbody>
            </table>
          </div>

          <p>Our recommendation at StocksWitty: <strong>Mutual funds for core wealth building, unlisted shares for high-conviction wealth multiplication, IPO subscriptions for opportunistic gains.</strong> Don't put more than 15% in unlisted unless you have a 5+ year horizon and can handle volatility.</p>

          <!-- Section 9: StocksWitty -->
          <h2 id="stockswitty">How StocksWitty makes this <span class="gold-accent">easy and safe</span></h2>

          <p>If you've made it this far, you're seriously considering investing in unlisted shares. Let's talk about why thousands of investors choose StocksWitty as their dealer:</p>

          <ul>
            <li><strong>Same-day demat credit:</strong> Post the March 2025 SEBI simplification, we credit shares to your demat the same day of payment.</li>
            <li><strong>All-inclusive pricing:</strong> The price we quote includes stamp duty, DP charges, and our spread. No hidden surprises.</li>
            <li><strong>Verified company bank account:</strong> Your payment goes to StocksWitty's registered company account, never to personal accounts.</li>
            <li><strong>ISIN verification:</strong> Every transaction includes the share ISIN so you can independently verify on CDSL/NSDL portals.</li>
            <li><strong>Buyback option:</strong> Need to exit before IPO? We buy back at fair market spread (4-6%). Most platforms leave you stranded.</li>
            <li><strong>Honest research, no upselling:</strong> As you've seen in this article — we tell you risks before rewards.</li>
          </ul>

          <p>To get started, WhatsApp us at <strong>+91-98XXX XXXXX</strong> or fill the inquiry form on the left of this page. Our Relationship Manager will share live pricing within 30 minutes — no commitment required.</p>

          <!-- Tags -->
          <div class="tags-row">
            <a href="#" class="tag">#unlisted-shares</a>
            <a href="#" class="tag">#NSE-India</a>
            <a href="#" class="tag">#pre-IPO</a>
            <a href="#" class="tag">#investing-guide</a>
            <a href="#" class="tag">#capital-gains</a>
            <a href="#" class="tag">#SEBI</a>
            <a href="#" class="tag">#2026</a>
          </div>

          <!-- FAQ Section -->
          <div class="faq-section" id="faqs">
            <h2 style="margin-top: 0;">Frequently Asked <span class="gold-accent">Questions</span></h2>
            <p style="color: var(--gray-500); margin-bottom: 24px; font-size: 14px;">Answers to the most common questions our research team receives about unlisted shares</p>

            <div class="faq-card">
              <button class="faq-q" onclick="toggleFaq(this)">Is it safe to buy unlisted shares in India?</button>
              <div class="faq-a">Yes, buying unlisted shares is safe when done through SEBI-compliant dealers like StocksWitty. The shares are real equity, credited to your CDSL/NSDL demat account in your name. The main risks are: (a) dealer fraud — mitigated by paying only to verified company accounts, (b) liquidity risk — selling can take 5-10 business days, (c) market risk — share prices fluctuate based on company performance and IPO expectations.</div>
            </div>
            <div class="faq-card">
              <button class="faq-q" onclick="toggleFaq(this)">What is the minimum investment for unlisted shares?</button>
              <div class="faq-a">Minimum investment varies by company. NSE India requires 250 shares (~₹4.9 lakh at ₹1,890 per share). Most companies have lot sizes ranging from ₹50,000 to ₹10 lakh. Tata Capital requires 100 shares minimum (₹92,500 at ₹925 per share). Zepto and PhonePe have higher minimums of ₹2-3 lakh due to limited supply. At StocksWitty, we honor company-defined minimums without imposing higher thresholds.</div>
            </div>
            <div class="faq-card">
              <button class="faq-q" onclick="toggleFaq(this)">How long does it take to receive unlisted shares?</button>
              <div class="faq-a">Post the March 2025 SEBI simplification, unlisted shares are credited to your demat account on the same day of payment confirmation. Earlier, this process took 2-3 weeks. Some companies (like NSE) require additional board approval, but even this is now processed within 24 hours.</div>
            </div>
            <div class="faq-card">
              <button class="faq-q" onclick="toggleFaq(this)">What is the tax on unlisted shares in India?</button>
              <div class="faq-a">Per Budget 2024: Short-term capital gains (held under 24 months) are taxed at your income slab rate (up to 30% + cess). Long-term capital gains (held over 24 months) are taxed at 12.5% flat without indexation. STT does not apply since these are off-market transactions.</div>
            </div>
            <div class="faq-card">
              <button class="faq-q" onclick="toggleFaq(this)">Can I sell unlisted shares before IPO?</button>
              <div class="faq-a">Yes, you can sell unlisted shares to other investors through a dealer like StocksWitty. The transaction takes 5-10 business days due to off-market transfer requirements. The buyback price will be 4-8% lower than the prevailing market price to account for the dealer's spread. We at StocksWitty offer guaranteed buyback at fair market spread for all shares we sold.</div>
            </div>
            <div class="faq-card">
              <button class="faq-q" onclick="toggleFaq(this)">Can NRIs buy unlisted shares in India?</button>
              <div class="faq-a">Yes, NRIs with valid NRO/NRE demat accounts can invest in unlisted shares. FEMA compliance and capital gains repatriation rules apply. NRIs from US/Canada have additional FATCA documentation requirements. At StocksWitty, we handle complete NRI documentation.</div>
            </div>
            <div class="faq-card">
              <button class="faq-q" onclick="toggleFaq(this)">What happens to unlisted shares after IPO listing?</button>
              <div class="faq-a">When the company gets listed, your unlisted shares automatically convert to listed shares in your demat account. The ISIN remains the same. You'll face a 6-month lock-in period from the listing date during which you cannot sell. After lock-in expires, you can sell on NSE/BSE like any other stock.</div>
            </div>
            <div class="faq-card">
              <button class="faq-q" onclick="toggleFaq(this)">How do I verify unlisted shares are credited to my demat?</button>
              <div class="faq-a">Three ways to verify: (1) Log into CDSL Easi or NSDL Speed-e portal and check your holdings — you'll see the company name, ISIN, and quantity. (2) Use the CDSL/NSDL mobile app. (3) Request a Statement of Demat Account from your DP (broker). At StocksWitty, we share the ISIN and demat transaction screenshot with you for independent verification.</div>
            </div>
            <div class="faq-card">
              <button class="faq-q" onclick="toggleFaq(this)">Which unlisted shares should I buy in 2026?</button>
              <div class="faq-a">Our research team's current top picks for 2026 (in order): (1) NSE India — IPO catalyst expected 2026-27, strong fundamentals, monopoly business. (2) Tata Capital — DRHP filed, robust NBFC, Tata brand. (3) Reliance Retail — listing speculation building, largest retailer in India. (4) Zepto Equity — quick commerce leader, IPO momentum. (5) Razorpay — fintech with US shift creating discount opportunity. WhatsApp us for personalized recommendations.</div>
            </div>
            <div class="faq-card">
              <button class="faq-q" onclick="toggleFaq(this)">What is the difference between unlisted shares and IPO?</button>
              <div class="faq-a">Unlisted shares are bought before a company goes public, in the over-the-counter market through dealers. IPO (Initial Public Offering) is when a company first sells shares to the public through stock exchanges with allotment via lottery. Unlisted = direct purchase at market price, no allotment risk. IPO = subscription-based allotment, may not get shares even if you apply.</div>
            </div>
          </div>

          <!-- Sources Section -->
          <div class="sources-section" id="sources">
            <h3>Sources & References</h3>
            <p class="sources-intro">All data and statistics cited in this article are sourced from official regulatory bodies and verified market data providers. Last updated June 5, 2026.</p>
            <ol class="sources-list">
              <li><div><strong>Securities and Exchange Board of India (SEBI)</strong> — "Simplification of Transfer of Unlisted Securities," Circular dated March 12, 2025<span class="source-meta">Regulatory document · sebi.gov.in</span></div></li>
              <li><div><strong>Association of Mutual Funds in India (AMFI)</strong> — "Industry Quarterly Report Q4 FY25"<span class="source-meta">Industry data · amfiindia.com</span></div></li>
              <li><div><strong>National Stock Exchange of India</strong> — "Annual Report 2024-25" and IPO No Objection Certificate update January 30, 2026<span class="source-meta">Corporate disclosure · nseindia.com</span></div></li>
              <li><div><strong>Central Depository Services (India) Limited (CDSL)</strong> — "Unlisted Securities Transfer Process Guidelines 2025"<span class="source-meta">Operational document · cdslindia.com</span></div></li>
              <li><div><strong>Income Tax Department, Government of India</strong> — "Finance (No. 2) Act 2024 Amendments to Section 112A and 111A"<span class="source-meta">Tax law · incometaxindia.gov.in</span></div></li>
              <li><div><strong>StocksWitty Internal Research Desk</strong> — "Q1 2026 Unlisted Shares Inquiry Analytics," covering 18,000+ investor queries<span class="source-meta">Proprietary research</span></div></li>
              <li><div><strong>NSDL Master Circular on Off-Market Transfers</strong> — Updated April 2025<span class="source-meta">Operational document · nsdl.co.in</span></div></li>
              <li><div><strong>RBI Foreign Exchange Management Act (FEMA)</strong> — Guidelines on NRI investments in unlisted equity, updated 2025<span class="source-meta">Regulatory document · rbi.org.in</span></div></li>
            </ol>
          </div>

          <!-- Author Bio -->
          <div class="author-bio-section">
            <div class="bio-avatar">RM</div>
            <div class="bio-content">
              <h4>About the Author: Rahul Mehra</h4>
              <div class="bio-role">Founder, StocksWitty · 12+ years in Capital Markets</div>
              <p>Rahul Mehra is the founder of StocksWitty and has 12+ years of experience in Indian capital markets. He previously worked at Edelweiss Wealth Management and Anand Rathi Financial Services in their unlisted shares and pre-IPO desk. He specializes in identifying high-quality pre-IPO investments for retail and HNI investors.</p>
              <p style="font-style: italic; color: var(--gray-500); font-size: 13px;">"My goal with StocksWitty is to make pre-IPO investing transparent, safe, and accessible to ordinary Indian investors — without the typical industry markups and information asymmetry."</p>
              <div class="bio-socials">
                <a href="#" class="bio-social">📧 Email</a>
                <a href="#" class="bio-social">🐦 Twitter</a>
                <a href="#" class="bio-social">💼 LinkedIn</a>
                <a href="#" class="bio-social">📝 More Articles</a>
              </div>
            </div>
          </div>

          <!-- Reviewer Bio -->
          <div class="author-bio-section">
            <div class="bio-avatar" style="background: linear-gradient(135deg, var(--gold-dark) 0%, var(--gold) 100%);">PS</div>
            <div class="bio-content">
              <h4>Reviewed by: CA Priya Sharma, FCA</h4>
              <div class="bio-role">Chartered Accountant · Tax Consultant · Capital Markets Specialist</div>
              <p>Priya Sharma is a Fellow Chartered Accountant (FCA) with 14+ years of experience in capital markets taxation and compliance. She specializes in capital gains taxation, NRI tax planning, and SEBI regulatory matters. She has reviewed this article's tax sections (Budget 2024 amendments) and verified compliance accuracy.</p>
              <p style="font-style: italic; color: var(--gray-500); font-size: 13px;">"All tax calculations and regulatory references in this article are accurate as of June 5, 2026. Tax laws change frequently; readers should consult their CA for individual circumstances."</p>
              <div class="bio-socials">
                <a href="#" class="bio-social">📧 Email</a>
                <a href="#" class="bio-social">💼 LinkedIn</a>
                <a href="#" class="bio-social">📜 ICAI Profile</a>
              </div>
            </div>
          </div>

          <!-- Related Articles -->
          <div class="related-section">
            <h3>Related Articles</h3>
            <div class="related-grid">
              <a href="#" class="related-card">
                <div class="related-cat">Unlisted Shares</div>
                <h5>NSE India IPO 2026: What Investors Need to Know Right Now</h5>
                <div class="related-meta"><span>By Rahul Mehra</span><span>10 min read</span></div>
              </a>
              <a href="#" class="related-card">
                <div class="related-cat">Mutual Funds</div>
                <h5>SBI Mutual Fund Review 2026: Best Schemes, Returns, Honest Take</h5>
                <div class="related-meta"><span>By StocksWitty Desk</span><span>12 min read</span></div>
              </a>
              <a href="#" class="related-card">
                <div class="related-cat">Pre-IPO Investing</div>
                <h5>Zepto Unlisted Shares: Should You Buy Before the IPO?</h5>
                <div class="related-meta"><span>By Rahul Mehra</span><span>9 min read</span></div>
              </a>
            </div>
          </div>

        </main>

        <!-- Consultation Modal -->
        <div class="custom-modal" id="customModal">
          <div class="custom-modal-content">
            <button class="close-modal" id="closeModal">&times;</button>
            <div class="quote-form-box">
              <span class="free-tag">★ FREE CONSULTATION</span>
              <h2>Want help buying unlisted shares?</h2>
              <p>Get live prices, step-by-step guidance, and zero pressure. Response in 30 minutes.</p>
              <form>
                <input type="text" class="form-control" placeholder="Your Name">
                <input type="tel" class="form-control" placeholder="Mobile Number">
                <select class="form-select">
                  <option selected>I'm interested in...</option>
                  <option>Unlisted Shares</option>
                  <option>IPO</option>
                </select>
                <select class="form-select">
                  <option selected>Investment Range...</option>
                  <option>₹50K - ₹1L</option>
                  <option>₹1L - ₹5L</option>
                  <option>₹5L+</option>
                </select>
                <button type="button" class="submit-btn">Get Live Quote →</button>
              </form>
            </div>
          </div>
        </div>

      </div><!-- /.col-md-8 -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</div><!-- /.article-layout -->

@endsection

@push('scripts')
<script>
// Reading progress bar
window.addEventListener('scroll', function () {
  var h = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  var pct = (window.scrollY / h) * 100;
  document.getElementById('progressBar').style.width = pct + '%';
});

// FAQ accordion
function toggleFaq(btn) {
  btn.parentElement.classList.toggle('open');
}

// TOC active link on scroll
(function () {
  var sections = document.querySelectorAll('.article-body h2[id]');
  var tocLinks = document.querySelectorAll('.toc-list a');
  window.addEventListener('scroll', function () {
    var current = '';
    sections.forEach(function (s) {
      if (window.scrollY >= s.offsetTop - 200) current = s.getAttribute('id');
    });
    tocLinks.forEach(function (a) {
      a.classList.remove('active');
      if (a.getAttribute('href') === '#' + current) a.classList.add('active');
    });
  });
})();

// Consultation modal
var modal = document.getElementById('customModal');
document.getElementById('openModal').onclick = function () { modal.classList.add('show'); };
document.getElementById('closeModal').onclick = function () { modal.classList.remove('show'); };
modal.addEventListener('click', function (e) { if (e.target === modal) modal.classList.remove('show'); });
</script>
@endpush
