@extends('layouts.sw')

@section('title', 'Sign In to StockWitty — Unlisted & Pre-IPO Shares')
@section('description', 'Sign in to your StockWitty account to track unlisted share prices, view your watchlist and follow up on open orders.')

@section('content')
<x-sw.auth-layout crumbLabel="Sign In" eyebrow="Welcome back" title="Sign in to StockWitty."
                   subtitle="Your watchlist, price alerts and order history — in one place."
                   :points="[
                       'Watchlist across 250+ unlisted companies',
                       'Price-change alerts on names you follow',
                       'Order and demat-transfer status in one view',
                   ]">
    <form class="space-y-4" x-data="{ show: false, done: false }" @submit.prevent="done = true">
        <label class="block">
            <span class="text-sm font-bold text-foreground">Email</span>
            <span class="mt-2 flex items-center gap-2 rounded-xl border border-border bg-background px-3.5 focus-within:border-primary">
                <x-sw.icon name="mail" class="size-4 text-muted-foreground" />
                <input type="email" required autocomplete="email" placeholder="you@example.com"
                       class="w-full bg-transparent py-3 text-sm outline-none" />
            </span>
        </label>

        <label class="block">
            <span class="text-sm font-bold text-foreground">Password</span>
            <span class="mt-2 flex items-center gap-2 rounded-xl border border-border bg-background px-3.5 focus-within:border-primary">
                <x-sw.icon name="lock" class="size-4 text-muted-foreground" />
                <input :type="show ? 'text' : 'password'" required autocomplete="current-password" placeholder="••••••••"
                       class="w-full bg-transparent py-3 text-sm outline-none" />
                <button type="button" :aria-label="show ? 'Hide password' : 'Show password'" @click="show = !show"
                        class="text-muted-foreground hover:text-primary">
                    <x-sw.icon name="eye-off" class="size-4" x-show="show" x-cloak />
                    <x-sw.icon name="eye" class="size-4" x-show="!show" />
                </button>
            </span>
        </label>

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 font-medium text-muted-foreground">
                <input type="checkbox" class="size-4 accent-[var(--brand)]" /> Keep me signed in
            </label>
            <a href="/login-new/" class="font-semibold text-primary hover:underline">Forgot password?</a>
        </div>

        <button type="submit" class="bg-cta w-full rounded-xl px-5 py-3.5 text-sm font-bold text-white">Sign in</button>

        <p x-show="done" x-cloak class="rounded-xl border border-border bg-green-50 px-4 py-3 text-xs text-muted-foreground">
            This is a demo form — accounts aren't live yet. Nothing was submitted or stored.
        </p>

        <p class="flex items-center gap-2 text-xs text-muted-foreground">
            <x-sw.icon name="shield-check" class="size-4 text-primary" />
            We never ask for your demat password or OTP.
        </p>

        <p class="border-t border-border pt-4 text-sm text-muted-foreground">
            New to StockWitty?
            <a href="/signup-new/" class="font-bold text-primary hover:underline">Create an account</a>
        </p>
    </form>
</x-sw.auth-layout>
@endsection
