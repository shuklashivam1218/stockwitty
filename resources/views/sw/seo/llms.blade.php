# StockWitty

> StockWitty is an investment platform for unlisted and pre-IPO shares in India — with honest research, transparent pricing and human support. Each company carries a WittyScore rating, a full profile and an investment thesis. StockWitty is a distributor of unlisted shares, not a SEBI-registered investment adviser.

## Core pages
- [Home](https://www.stockswitty.com/)
- [Unlisted Shares](https://www.stockswitty.com/unlisted-shares/): directory of unlisted & pre-IPO companies
- [WittyScore](https://www.stockswitty.com/wittyscore/): our 0–10 rating methodology (5 pillars)
- [Screener](https://www.stockswitty.com/screener/): filter unlisted companies
- [Compare](https://www.stockswitty.com/compare/): compare two unlisted shares
- [Calculators](https://www.stockswitty.com/calculators/): investment calculators
- [Why StockWitty](https://www.stockswitty.com/why-witty/)

## Unlisted company research ({{ $companies->count() }} companies, each with price, profile /about/ and thesis /thesis/)
@foreach ($companies as $company)
- [{{ $company->UL_STOCKS_COMPNAME }}](https://www.stockswitty.com/unlisted-shares/{{ $company->UL_STOCKS_SLUG }}/)
@endforeach

## Products
- [Listed stocks](https://www.stockswitty.com/listed/)
- [Mutual funds](https://www.stockswitty.com/mutual-funds/)
- [PMS](https://www.stockswitty.com/pms/)
- [Fixed deposits](https://www.stockswitty.com/fixed-deposits/)
- [Digital gold](https://www.stockswitty.com/digital-gold/) · [Digital silver](https://www.stockswitty.com/digital-silver/)
- [ETFs](https://www.stockswitty.com/etf/)

## Learn
- [Blog](https://www.stockswitty.com/blog/): guides on unlisted shares, tax & investing
- [What are unlisted shares?](https://www.stockswitty.com/blog/what-are-unlisted-shares/)
- [How to buy unlisted shares](https://www.stockswitty.com/blog/how-to-buy-unlisted-shares/)
- [How to sell unlisted shares](https://www.stockswitty.com/blog/how-to-sell-unlisted-shares/)
- [Tax on unlisted shares](https://www.stockswitty.com/blog/tax-on-unlisted-shares/)
- [Unlisted vs listed shares](https://www.stockswitty.com/blog/unlisted-shares-vs-listed-shares/)
- [Is it safe to buy unlisted shares?](https://www.stockswitty.com/blog/is-it-safe-to-buy-unlisted-shares/)
- [Risks of investing in unlisted shares](https://www.stockswitty.com/blog/risks-of-investing-in-unlisted-shares/)
- [News](https://www.stockswitty.com/news/): IPO, unlisted & startup-funding updates
- [Case Studies](https://www.stockswitty.com/case-studies/): honest investor journeys

## How buying works
Complete KYC (PAN, CML copy, cancelled cheque, Aadhaar), pay only into a verified company account (never a personal one), and receive shares in your own CDSL/NSDL demat — usually the same day, with independent ISIN verification.

## Important
Unlisted shares are illiquid and high-risk, with no guarantee of any IPO, listing or exit. All content is information only, not investment advice. StockWitty is a distributor of unlisted shares, not a SEBI-registered investment adviser.
