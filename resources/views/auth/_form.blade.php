@php $initialMode = $initialMode ?? 'login'; @endphp
<div class="min-h-screen bg-background"
     x-data="authPage()" x-init="init()"
     data-login-url="{{ route('login.submit') }}"
     data-register-url="{{ route('register.submit') }}"
     data-initial-mode="{{ $initialMode }}">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => $crumbLabel]]" />
    </div>

    <main>
        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid items-stretch gap-6 lg:grid-cols-2">
                    <x-sw.reveal>
                        <div class="bg-price-card h-full rounded-3xl p-8 text-white">
                            <template x-if="mode !== 'signup'">
                                <div>
                                    <p class="text-xs font-bold tracking-widest text-mint-bright uppercase">Welcome back</p>
                                    <h1 class="mt-4 text-3xl font-bold sm:text-4xl">Sign in to StockWitty.</h1>
                                    <p class="mt-3 text-sm text-white/75">Your watchlist, price alerts and order history — in one place.</p>
                                    <ul class="mt-8 space-y-3 text-sm text-white/85">
                                        <li class="flex items-start gap-3"><x-sw.icon name="check" class="mt-0.5 size-4 shrink-0 text-mint-bright" /> Watchlist across 250+ unlisted companies</li>
                                        <li class="flex items-start gap-3"><x-sw.icon name="check" class="mt-0.5 size-4 shrink-0 text-mint-bright" /> Price-change alerts on names you follow</li>
                                        <li class="flex items-start gap-3"><x-sw.icon name="check" class="mt-0.5 size-4 shrink-0 text-mint-bright" /> Order and demat-transfer status in one view</li>
                                    </ul>
                                </div>
                            </template>
                            <template x-if="mode === 'signup'">
                                <div>
                                    <p class="text-xs font-bold tracking-widest text-mint-bright uppercase">Get started</p>
                                    <h1 class="mt-4 text-3xl font-bold sm:text-4xl">Create your free account.</h1>
                                    <p class="mt-3 text-sm text-white/75">No brokerage account switch, no lock-in. KYC is only needed when you place your first order.</p>
                                    <ul class="mt-8 space-y-3 text-sm text-white/85">
                                        <li class="flex items-start gap-3"><x-sw.icon name="check" class="mt-0.5 size-4 shrink-0 text-mint-bright" /> Free forever — research and watchlists cost nothing</li>
                                        <li class="flex items-start gap-3"><x-sw.icon name="check" class="mt-0.5 size-4 shrink-0 text-mint-bright" /> One-time KYC before your first order, not before signup</li>
                                        <li class="flex items-start gap-3"><x-sw.icon name="check" class="mt-0.5 size-4 shrink-0 text-mint-bright" /> A real person walks you through the demat transfer</li>
                                    </ul>
                                </div>
                            </template>
                            <p class="mt-8 border-t border-white/10 pt-5 text-xs text-white/55">
                                StockWitty is a distributor of unlisted shares, not a SEBI-registered investment adviser. Unlisted shares are illiquid and high-risk.
                            </p>
                        </div>
                    </x-sw.reveal>

                    <x-sw.reveal :delay="0.08">
                        <div class="h-full rounded-3xl border border-border bg-card p-7 shadow-soft sm:p-8">

                            {{-- ── LOGIN ─────────────────────────────────────────── --}}
                            <form x-show="mode === 'login'" x-cloak class="space-y-4" @submit.prevent="submitLogin()">
                                <div x-show="loginError" x-cloak class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" x-text="loginError"></div>

                                <label class="block">
                                    <span class="text-sm font-bold text-foreground">Email</span>
                                    <span class="mt-2 flex items-center gap-2 rounded-xl border border-border bg-background px-3.5 focus-within:border-primary">
                                        <x-sw.icon name="mail" class="size-4 text-muted-foreground" />
                                        <input type="email" required autocomplete="email" placeholder="you@example.com" x-model="login.email"
                                               class="w-full bg-transparent py-3 text-sm outline-none" />
                                    </span>
                                    <span x-show="loginErrors.email" x-cloak class="mt-1 block text-xs text-red-600" x-text="loginErrors.email && loginErrors.email[0]"></span>
                                </label>

                                <label class="block">
                                    <span class="flex items-center justify-between">
                                        <span class="text-sm font-bold text-foreground">Password</span>
                                        <a href="#" @click.prevent="setMode('forgot')" class="text-xs font-semibold text-primary hover:underline">Forgot password?</a>
                                    </span>
                                    <span class="mt-2 flex items-center gap-2 rounded-xl border border-border bg-background px-3.5 focus-within:border-primary">
                                        <x-sw.icon name="lock" class="size-4 text-muted-foreground" />
                                        <input :type="showLoginPassword ? 'text' : 'password'" required autocomplete="current-password" placeholder="••••••••" x-model="login.password"
                                               class="w-full bg-transparent py-3 text-sm outline-none" />
                                        <button type="button" :aria-label="showLoginPassword ? 'Hide password' : 'Show password'" @click="showLoginPassword = !showLoginPassword"
                                                class="text-muted-foreground hover:text-primary">
                                            <x-sw.icon name="eye-off" class="size-4" x-show="showLoginPassword" x-cloak />
                                            <x-sw.icon name="eye" class="size-4" x-show="!showLoginPassword" />
                                        </button>
                                    </span>
                                    <span x-show="loginErrors.password" x-cloak class="mt-1 block text-xs text-red-600" x-text="loginErrors.password && loginErrors.password[0]"></span>
                                </label>

                                <button type="submit" :disabled="loginBusy" class="bg-cta w-full rounded-xl px-5 py-3.5 text-sm font-bold text-white disabled:opacity-60">
                                    <span x-text="loginBusy ? 'Signing in…' : 'Sign in'"></span>
                                </button>

                                <p class="flex items-center gap-2 text-xs text-muted-foreground">
                                    <x-sw.icon name="shield-check" class="size-4 text-primary" />
                                    We never ask for your demat password or OTP.
                                </p>

                                <p class="border-t border-border pt-4 text-sm text-muted-foreground">
                                    New to StockWitty?
                                    <a href="{{ route('signup') }}" class="font-bold text-primary hover:underline">Create an account</a>
                                </p>
                            </form>

                            {{-- ── SIGN UP ───────────────────────────────────────── --}}
                            <form x-show="mode === 'signup'" x-cloak class="space-y-4" @submit.prevent="submitSignup()">
                                <div x-show="signupError" x-cloak class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" x-text="signupError"></div>

                                <label class="block">
                                    <span class="text-sm font-bold text-foreground">Full name</span>
                                    <span class="mt-2 flex items-center gap-2 rounded-xl border border-border bg-background px-3.5 focus-within:border-primary">
                                        <x-sw.icon name="user" class="size-4 text-muted-foreground" />
                                        <input type="text" required autocomplete="name" placeholder="As per your PAN" x-model="signup.name"
                                               class="w-full bg-transparent py-3 text-sm outline-none" />
                                    </span>
                                    <span x-show="signupErrors.name" x-cloak class="mt-1 block text-xs text-red-600" x-text="signupErrors.name && signupErrors.name[0]"></span>
                                </label>

                                <label class="block">
                                    <span class="text-sm font-bold text-foreground">Email</span>
                                    <span class="mt-2 flex items-center gap-2 rounded-xl border border-border bg-background px-3.5 focus-within:border-primary">
                                        <x-sw.icon name="mail" class="size-4 text-muted-foreground" />
                                        <input type="email" required autocomplete="email" placeholder="you@example.com" x-model="signup.email"
                                               class="w-full bg-transparent py-3 text-sm outline-none" />
                                    </span>
                                    <span x-show="signupErrors.email" x-cloak class="mt-1 block text-xs text-red-600" x-text="signupErrors.email && signupErrors.email[0]"></span>
                                </label>

                                <label class="block">
                                    <span class="text-sm font-bold text-foreground">Mobile</span>
                                    <span class="mt-2 flex items-center gap-2 rounded-xl border border-border bg-background px-3.5 focus-within:border-primary">
                                        <x-sw.icon name="phone" class="size-4 text-muted-foreground" />
                                        <span class="text-sm font-semibold text-muted-foreground">+91</span>
                                        <input type="tel" required inputmode="numeric" autocomplete="tel" maxlength="10" placeholder="98765 43210" x-model="signup.phone"
                                               class="w-full bg-transparent py-3 text-sm outline-none" />
                                    </span>
                                    <span x-show="signupErrors.phone" x-cloak class="mt-1 block text-xs text-red-600" x-text="signupErrors.phone && signupErrors.phone[0]"></span>
                                </label>

                                <label class="block">
                                    <span class="text-sm font-bold text-foreground">Password</span>
                                    <span class="mt-2 flex items-center gap-2 rounded-xl border border-border bg-background px-3.5 focus-within:border-primary">
                                        <x-sw.icon name="lock" class="size-4 text-muted-foreground" />
                                        <input :type="showSignupPassword ? 'text' : 'password'" required minlength="6" autocomplete="new-password"
                                               placeholder="At least 6 characters" x-model="signup.password" class="w-full bg-transparent py-3 text-sm outline-none" />
                                        <button type="button" :aria-label="showSignupPassword ? 'Hide password' : 'Show password'" @click="showSignupPassword = !showSignupPassword"
                                                class="text-muted-foreground hover:text-primary">
                                            <x-sw.icon name="eye-off" class="size-4" x-show="showSignupPassword" x-cloak />
                                            <x-sw.icon name="eye" class="size-4" x-show="!showSignupPassword" />
                                        </button>
                                    </span>
                                    <span x-show="signupErrors.password" x-cloak class="mt-1 block text-xs text-red-600" x-text="signupErrors.password && signupErrors.password[0]"></span>
                                </label>

                                <label class="flex items-start gap-2.5 text-sm text-muted-foreground">
                                    <input type="checkbox" required class="mt-0.5 size-4 accent-[var(--brand)]" x-model="signupConsent" />
                                    <span>
                                        I understand unlisted shares are illiquid, dealer-priced and high-risk, and that
                                        StockWitty is a distributor — not an investment adviser.
                                    </span>
                                </label>

                                <button type="submit" :disabled="signupBusy" class="bg-cta w-full rounded-xl px-5 py-3.5 text-sm font-bold text-white disabled:opacity-60">
                                    <span x-text="signupBusy ? 'Creating account…' : 'Create free account'"></span>
                                </button>

                                <p class="flex items-center gap-2 text-xs text-muted-foreground">
                                    <x-sw.icon name="shield-check" class="size-4 text-primary" />
                                    We never ask for your demat password or OTP.
                                </p>

                                <p class="border-t border-border pt-4 text-sm text-muted-foreground">
                                    Already have an account?
                                    <a href="{{ route('login') }}" class="font-bold text-primary hover:underline">Sign in</a>
                                </p>
                            </form>

                            {{-- ── FORGOT PASSWORD ───────────────────────────────── --}}
                            <div x-show="mode === 'forgot'" x-cloak class="space-y-5">
                                <a href="#" @click.prevent="setMode('login')" class="flex items-center gap-2 text-sm font-semibold text-primary hover:underline">
                                    <x-sw.icon name="arrow-right" class="size-4 rotate-180" /> Back to sign in
                                </a>
                                <div>
                                    <h2 class="text-xl font-bold text-foreground">Reset your password</h2>
                                    <p class="mt-2 text-sm text-muted-foreground">
                                        Online password reset isn't live yet. Email our support team from your
                                        registered address and we'll reset it for you directly.
                                    </p>
                                </div>
                                <a href="mailto:support@stockswitty.com" class="bg-cta block w-full rounded-xl px-5 py-3.5 text-center text-sm font-bold text-white">
                                    Email support@stockswitty.com
                                </a>
                            </div>

                        </div>
                    </x-sw.reveal>
                </div>
            </div>
        </section>
    </main>
</div>
