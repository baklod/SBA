<template>
    <Head title="Login | SBA" />
    <div
        class="auth-shell min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
    >
        <div class="auth-card max-w-md w-full space-y-8 p-10">
            <div>
                <h2 class="text-center text-4xl font-extrabold">
                    <span class="auth-brand-court">Court</span
                    ><span class="auth-brand-vision">Vision</span>
                </h2>
                <p class="auth-subtitle mt-2 text-center text-sm">
                    Sign in to your account
                </p>
            </div>
            <form class="mt-8 space-y-6" @submit.prevent="submit">
                <div class="rounded-md shadow-sm space-y-4">
                    <div>
                        <label
                            for="email"
                            class="auth-label block text-sm font-medium mb-1"
                        >
                            Email address
                        </label>
                        <input
                            id="email"
                            v-model="form.email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            class="auth-input appearance-none rounded-lg relative block w-full px-3 py-2 border focus:z-10 sm:text-sm"
                            placeholder="Enter your email"
                        />
                        <div
                            v-if="form.errors.email"
                            class="mt-1 text-sm text-red-400"
                        >
                            {{ form.errors.email }}
                        </div>
                    </div>
                    <div>
                        <label
                            for="password"
                            class="auth-label block text-sm font-medium mb-1"
                        >
                            Password
                        </label>
                        <input
                            id="password"
                            v-model="form.password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            class="auth-input appearance-none rounded-lg relative block w-full px-3 py-2 border focus:z-10 sm:text-sm"
                            placeholder="Enter your password"
                        />
                        <div
                            v-if="form.errors.password"
                            class="mt-1 text-sm text-red-400"
                        >
                            {{ form.errors.password }}
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            id="remember"
                            v-model="form.remember"
                            name="remember"
                            type="checkbox"
                            class="auth-checkbox h-4 w-4 rounded border"
                        />
                        <label
                            for="remember"
                            class="auth-label ml-2 block text-sm"
                        >
                            Remember me
                        </label>
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="auth-submit group relative w-full flex justify-center py-2 px-4 text-sm font-semibold rounded-md disabled:opacity-50"
                    >
                        <span v-if="form.processing">Signing in...</span>
                        <span v-else>Sign in</span>
                    </button>
                </div>

                <div class="text-center">
                    <Link
                        :href="route('home')"
                        class="auth-muted-link text-sm font-semibold"
                    >
                        ← Back to Home
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<style scoped>
.auth-shell {
    --cv-bg-top: #0b0d12;
    --cv-bg-bottom: #05060a;
    --cv-surface-1: rgba(255, 255, 255, 0.035);
    --cv-surface-2: rgba(255, 255, 255, 0.055);
    --cv-surface-3: rgba(0, 0, 0, 0.35);
    --cv-border-1: rgba(255, 255, 255, 0.08);
    --cv-border-2: rgba(255, 255, 255, 0.12);

    background: linear-gradient(
        180deg,
        var(--cv-bg-top) 0%,
        var(--cv-bg-bottom) 100%
    );
}

.auth-card {
    background: var(--cv-surface-2);
    border: 1px solid var(--cv-border-1);
    border-radius: 1rem;
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.55);
    backdrop-filter: blur(10px);
}

.auth-brand-court {
    background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.auth-brand-vision {
    color: #ffffff;
}

.auth-subtitle {
    color: rgba(226, 232, 240, 0.72);
}

.auth-label {
    color: rgba(226, 232, 240, 0.78);
}

.auth-input {
    background: var(--cv-surface-3);
    border-color: var(--cv-border-1);
    color: #ffffff;
    outline: none;
    transition:
        border-color 120ms ease,
        box-shadow 120ms ease;
}

.auth-input::placeholder {
    color: rgba(226, 232, 240, 0.48);
}

.auth-input:focus {
    border-color: rgba(251, 191, 36, 0.45);
    box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.14);
}

.auth-checkbox {
    background: var(--cv-surface-3);
    border-color: var(--cv-border-1);
    accent-color: #fbbf24;
    outline: none;
    transition: box-shadow 120ms ease;
}

.auth-checkbox:focus {
    box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.14);
}

.auth-submit {
    border: 1px solid rgba(251, 191, 36, 0.25);
    background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);
    color: #ffffff;
    outline: none;
    transition:
        transform 120ms ease,
        box-shadow 120ms ease,
        filter 120ms ease;
}

.auth-submit:hover {
    filter: brightness(1.03);
    transform: translateY(-1px);
    box-shadow: 0 16px 36px rgba(251, 191, 36, 0.18);
}

.auth-submit:focus-visible {
    box-shadow:
        0 0 0 3px rgba(251, 191, 36, 0.18),
        0 16px 36px rgba(251, 191, 36, 0.18);
}

.auth-submit:disabled {
    cursor: not-allowed;
    filter: none;
    transform: none;
    box-shadow: none;
}

.auth-muted-link {
    color: rgba(226, 232, 240, 0.68);
    text-decoration: none;
}

.auth-muted-link:hover {
    color: rgba(226, 232, 240, 0.88);
}
</style>
