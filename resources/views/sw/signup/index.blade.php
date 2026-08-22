@extends('layouts.sw')

@section('title', 'Create Your Free StockWitty Account — Get Started')
@section('description', 'Open a free StockWitty account to research unlisted and pre-IPO shares, build a watchlist and get help with off-market demat transfers.')

@section('content')
<x-sw.auth-layout crumbLabel="Get Started" eyebrow="Get started" title="Create your free account."
                   subtitle="No brokerage account switch, no lock-in. KYC is only needed when you place your first order."
                   :points="[
                       'Free forever — research and watchlists cost nothing',
                       'One-time KYC before your first order, not before signup',
                       'A real person walks you through the demat transfer',
                   ]">
    <form class="space-y-4" x-data="{ show: false, done: false }" @submit.prevent="done = true">
        <label class="block">
            <span class="text-sm font-bold text-foreground">Full name</span>
            <span class="mt-2 flex items-center gap-2 rounded-xl border border-border bg-background px-3.5 focus-within:border-primary">
                <x-sw.icon name="user" class="size-4 text-muted-foreground" />
                <input type="text" required autocomplete="name" placeholder="As per your PAN"
                       class="w-full bg-transparent py-3 text-sm outline-none" />
            </span>
        </label>

        <label class="block">
            <span class="text-sm font-bold text-foreground">Email</span>
            <span class="mt-2 flex items-center gap-2 rounded-xl border border-border bg-background px-3.5 focus-within:border-primary">
                <x-sw.icon name="mail" class="size-4 text-muted-foreground" />
                <input type="email" required autocomplete="email" placeholder="you@example.com"
                       class="w-full bg-transparent py-3 text-sm outline-none" />
            </span>
        </label>

        <label class="block">
            <span class="text-sm font-bold text-foreground">Mobile</span>
            <span class="mt-2 flex items-center gap-2 rounded-xl border border-border bg-background px-3.5 focus-within:border-primary">
                <x-sw.icon name="phone" class="size-4 text-muted-foreground" />
                <span class="text-sm font-semibold text-muted-foreground">+91</span>
                <input type="tel" required inputmode="numeric" autocomplete="tel" placeholder="98765 43210"
                       class="w-full bg-transparent py-3 text-sm outline-none" />
            </span>
        </label>

        <label class="block">
            <span class="text-sm font-bold text-foreground">Password</span>
            <span class="mt-2 flex items-center gap-2 rounded-xl border border-border bg-background px-3.5 focus-within:border-primary">
                <x-sw.icon name="lock" class="size-4 text-muted-foreground" />
                <input :type="show ? 'text' : 'password'" required minlength="8" autocomplete="new-password"
                       placeholder="At least 8 characters" class="w-full bg-transparent py-3 text-sm outline-none" />
                <button type="button" :aria-label="show ? 'Hide password' : 'Show password'" @click="show = !show"
                        class="text-muted-foreground hover:text-primary">
                    <x-sw.icon name="eye-off" class="size-4" x-show="show" x-cloak />
                    <x-sw.icon name="eye" class="size-4" x-show="!show" />
                </button>
            </span>
        </label>

        <label class="flex items-start gap-2.5 text-sm text-muted-foreground">
            <input type="checkbox" required class="mt-0.5 size-4 accent-[var(--brand)]" />
            <span>
                I understand unlisted shares are illiquid, dealer-priced and high-risk, and that
                StockWitty is a distributor — not an investment adviser.
            </span>
        </label>

        <button type="submit" class="bg-cta w-full rounded-xl px-5 py-3.5 text-sm font-bold text-white">Create free account</button>

        <p x-show="done" x-cloak class="rounded-xl border border-border bg-green-50 px-4 py-3 text-xs text-muted-foreground">
            This is a demo form — signups aren't live yet. Nothing was submitted or stored.
        </p>

        <p class="flex items-center gap-2 text-xs text-muted-foreground">
            <x-sw.icon name="shield-check" class="size-4 text-primary" />
            We never ask for your demat password or OTP.
        </p>

        <p class="border-t border-border pt-4 text-sm text-muted-foreground">
            Already have an account?
            <a href="/login-new/" class="font-bold text-primary hover:underline">Sign in</a>
        </p>
    </form>
</x-sw.auth-layout>
@endsection
