<template>
    <Head title="Register | SBA" />
    <div
        class="min-h-screen bg-gradient-to-br from-slate-900 to-slate-800 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
    >
        <div
            class="max-w-md w-full space-y-8 bg-slate-800 p-10 rounded-xl shadow-2xl border border-slate-700"
        >
            <div>
                <h2 class="text-center text-4xl font-extrabold text-white">
                    🏀 Basketball Stats
                </h2>
                <p class="mt-2 text-center text-sm text-gray-400">
                    Create a new account
                </p>
            </div>
            <form class="mt-8 space-y-6" @submit.prevent="submit">
                <div class="rounded-md shadow-sm space-y-4">
                    <div>
                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-300 mb-1"
                        >
                            Full Name
                        </label>
                        <input
                            id="name"
                            v-model="form.name"
                            name="name"
                            type="text"
                            autocomplete="name"
                            required
                            class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-slate-600 placeholder-gray-500 text-white bg-slate-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                            placeholder="Enter your full name"
                        />
                        <div
                            v-if="form.errors.name"
                            class="mt-1 text-sm text-red-400"
                        >
                            {{ form.errors.name }}
                        </div>
                    </div>
                    <div>
                        <label
                            for="email"
                            class="block text-sm font-medium text-gray-300 mb-1"
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
                            class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-slate-600 placeholder-gray-500 text-white bg-slate-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
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
                            class="block text-sm font-medium text-gray-300 mb-1"
                        >
                            Password
                        </label>
                        <input
                            id="password"
                            v-model="form.password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-slate-600 placeholder-gray-500 text-white bg-slate-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                            placeholder="Enter your password"
                        />
                        <div
                            v-if="form.errors.password"
                            class="mt-1 text-sm text-red-400"
                        >
                            {{ form.errors.password }}
                        </div>
                    </div>
                    <div>
                        <label
                            for="password_confirmation"
                            class="block text-sm font-medium text-gray-300 mb-1"
                        >
                            Confirm Password
                        </label>
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-slate-600 placeholder-gray-500 text-white bg-slate-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                            placeholder="Confirm your password"
                        />
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                    >
                        <span v-if="form.processing">Creating account...</span>
                        <span v-else>Register</span>
                    </button>
                </div>

                <div class="text-center">
                    <Link
                        :href="route('login')"
                        class="font-medium text-blue-400 hover:text-blue-300"
                    >
                        Already have an account? Sign in
                    </Link>
                    <br />
                    <Link
                        :href="route('home')"
                        class="font-medium text-gray-400 hover:text-gray-300 text-sm"
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
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>
