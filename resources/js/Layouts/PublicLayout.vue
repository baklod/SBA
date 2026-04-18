<template>
    <div class="public-layout" :data-theme="theme">
        <!-- Navigation -->
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-left">
                    <Link :href="route('home')" class="nav-brand">
                        <span class="brand-text">
                            <span class="brand-court">Court</span>
                            <span class="brand-vision">Vision</span>
                        </span>
                    </Link>
                </div>

                <!-- Desktop Navigation -->
                <div class="nav-links">
                    <Link
                        :href="route('home')"
                        class="nav-link"
                        :class="{ active: isActiveDashboard }"
                    >
                        Dashboard
                    </Link>
                    <Link
                        :href="route('home', { section: 'schedule' })"
                        class="nav-link"
                        :class="{ active: isActiveSection('schedule') }"
                    >
                        Schedule
                    </Link>
                    <Link
                        :href="route('home', { section: 'rankings' })"
                        class="nav-link"
                        :class="{ active: isActiveSection('rankings') }"
                    >
                        Rankings
                    </Link>
                    <Link
                        :href="route('home', { section: 'team-stats' })"
                        class="nav-link"
                        :class="{ active: isActiveSection('team-stats') }"
                    >
                        Team Stats
                    </Link>
                    <Link
                        :href="route('home', { section: 'individual-stats' })"
                        class="nav-link"
                        :class="{ active: isActiveSection('individual-stats') }"
                    >
                        Player Stats
                    </Link>
                    <Link
                        :href="route('home', { section: 'highest-points' })"
                        class="nav-link"
                        :class="{ active: isActiveSection('highest-points') }"
                    >
                        Top Scorers
                    </Link>
                    <Link
                        :href="route('home', { section: 'standings' })"
                        class="nav-link"
                        :class="{ active: isActiveSection('standings') }"
                    >
                        Standings
                    </Link>
                    <Link
                        :href="route('announcements')"
                        class="nav-link"
                        :class="{ active: route().current('announcements') }"
                    >
                        News
                    </Link>
                    <Link
                        :href="route('about')"
                        class="nav-link"
                        :class="{ active: route().current('about') }"
                    >
                        About
                    </Link>
                </div>

                <div class="nav-actions">
                    <button
                        type="button"
                        class="theme-toggle"
                        :class="{ 'is-dark': theme === 'dark' }"
                        :aria-label="
                            theme === 'dark'
                                ? 'Switch to light mode'
                                : 'Switch to dark mode'
                        "
                        :aria-pressed="theme === 'light'"
                        @click="toggleTheme"
                    >
                        <span class="theme-thumb" aria-hidden="true">
                            <svg
                                v-if="theme === 'light'"
                                class="theme-icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m0-11.314L7.05 7.05m9.9 9.9l1.414 1.414M12 8a4 4 0 100 8 4 4 0 000-8z"
                                />
                            </svg>
                            <svg
                                v-else
                                class="theme-icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z"
                                />
                            </svg>
                        </span>
                    </button>

                    <!-- Auth Buttons -->
                    <div class="nav-right">
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="route('login')"
                            class="btn-login"
                        >
                            <svg
                                class="btn-icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                                ></path>
                            </svg>
                            Login
                        </Link>
                        <div v-else class="auth-menu">
                            <div class="user-info">
                                <div class="user-avatar">
                                    {{ $page.props.auth.user.name.charAt(0) }}
                                </div>
                                <span class="user-name">{{
                                    $page.props.auth.user.name
                                }}</span>
                            </div>
                            <Link
                                v-if="$page.props.auth.user.role === 'admin'"
                                :href="route('admin.dashboard')"
                                class="btn-dashboard btn-admin"
                            >
                                Admin
                            </Link>
                            <Link
                                v-if="
                                    $page.props.auth.user.role === 'committee'
                                "
                                :href="route('committee.dashboard')"
                                class="btn-dashboard btn-committee"
                            >
                                Committee
                            </Link>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="btn-logout"
                            >
                                <svg
                                    class="btn-icon"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                    ></path>
                                </svg>
                            </Link>
                        </div>
                    </div>

                    <!-- Mobile Menu Toggle -->
                    <button
                        class="mobile-toggle"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                    >
                        <svg
                            v-if="!mobileMenuOpen"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            ></path>
                        </svg>
                        <svg
                            v-else
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            ></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div v-if="mobileMenuOpen" class="mobile-menu">
                <Link
                    :href="route('home')"
                    class="mobile-link"
                    :class="{ active: isActiveDashboard }"
                    >Dashboard</Link
                >
                <Link
                    :href="route('home', { section: 'schedule' })"
                    class="mobile-link"
                    :class="{ active: isActiveSection('schedule') }"
                    >Schedule</Link
                >
                <Link
                    :href="route('home', { section: 'rankings' })"
                    class="mobile-link"
                    :class="{ active: isActiveSection('rankings') }"
                    >Rankings</Link
                >
                <Link
                    :href="route('home', { section: 'team-stats' })"
                    class="mobile-link"
                    :class="{ active: isActiveSection('team-stats') }"
                    >Team Stats</Link
                >
                <Link
                    :href="route('home', { section: 'individual-stats' })"
                    class="mobile-link"
                    :class="{ active: isActiveSection('individual-stats') }"
                    >Player Stats</Link
                >
                <Link
                    :href="route('home', { section: 'highest-points' })"
                    class="mobile-link"
                    :class="{ active: isActiveSection('highest-points') }"
                    >Top Scorers</Link
                >
                <Link
                    :href="route('home', { section: 'standings' })"
                    class="mobile-link"
                    :class="{ active: isActiveSection('standings') }"
                    >Standings</Link
                >
                <Link
                    :href="route('announcements')"
                    class="mobile-link"
                    :class="{ active: route().current('announcements') }"
                    >News</Link
                >
                <Link
                    :href="route('about')"
                    class="mobile-link"
                    :class="{ active: route().current('about') }"
                    >About</Link
                >
                <template v-if="$page.props.auth.user">
                    <div class="mobile-divider"></div>
                    <Link
                        v-if="$page.props.auth.user.role === 'admin'"
                        :href="route('admin.dashboard')"
                        class="mobile-link"
                        >Admin Dashboard</Link
                    >
                    <Link
                        v-if="$page.props.auth.user.role === 'committee'"
                        :href="route('committee.dashboard')"
                        class="mobile-link"
                        >Committee Dashboard</Link
                    >
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="mobile-link mobile-logout"
                        >Logout</Link
                    >
                </template>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="main-content">
            <div class="content-wrapper">
                <section
                    v-if="shouldShowOffSeasonGate"
                    class="offseason-gate"
                    role="status"
                    aria-label="Off-season notice"
                >
                    <div class="offseason-center">
                        <img
                            src="/watermark/Gemini_Generated_Image_9h5v5w9h5v5w9h5v-removebg-preview.png"
                            alt="SBA off-season watermark"
                            class="offseason-image"
                            loading="lazy"
                            decoding="async"
                        />
                        <div class="offseason-top">
                            <h1 class="offseason-title">Off Season</h1>
                            <p class="offseason-subtitle">
                                The league is currently in the off-season. For
                                the latest updates, visit the
                                <Link
                                    :href="route('announcements')"
                                    class="offseason-link"
                                    >News page</Link
                                >.
                            </p>
                        </div>
                    </div>
                </section>

                <slot v-else />
            </div>
        </main>

        <Toaster position="top-right" :theme="theme" richColors />

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-container">
                <div class="footer-content">
                    <div class="footer-brand">
                        <span class="footer-icon">🏀</span>
                        <span>Basketball Stats System</span>
                    </div>
                    <p class="footer-text">
                        © 2026 Basketball Match Announcement System. All rights
                        reserved.
                    </p>
                </div>
                <div class="footer-glow"></div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref, watch, onMounted, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { Toaster, toast } from "vue-sonner";
import "vue-sonner/style.css";

const mobileMenuOpen = ref(false);
const theme = ref("dark");

const THEME_STORAGE_KEY = "cv_public_theme";

const setTheme = (nextTheme) => {
    theme.value = nextTheme;

    try {
        window.localStorage.setItem(THEME_STORAGE_KEY, nextTheme);
    } catch {
        // ignore
    }
};

const toggleTheme = () => {
    setTheme(theme.value === "dark" ? "light" : "dark");
};

const page = usePage();

const currentSection = computed(() => {
    const section = page.props.section;
    return typeof section === "string" && section.length > 0
        ? section
        : "dashboard";
});

const isActiveDashboard = computed(() => {
    return route().current("home") && currentSection.value === "dashboard";
});

const isActiveSection = (section) => {
    return route().current("home") && currentSection.value === section;
};

const showFlashMessage = () => {
    if (page.props.flash?.success) {
        toast.success(page.props.flash.success);
    }
    if (page.props.flash?.error) {
        toast.error(page.props.flash.error);
    }
};

const isOffSeason = computed(() => Boolean(page.props.isOffSeason));
const isNewsPage = computed(() => route().current("announcements"));
const shouldShowOffSeasonGate = computed(
    () => isOffSeason.value && !isNewsPage.value,
);

onMounted(() => {
    try {
        const savedTheme = window.localStorage.getItem(THEME_STORAGE_KEY);
        if (savedTheme === "light" || savedTheme === "dark") {
            theme.value = savedTheme;
        }
    } catch {
        // ignore
    }

    showFlashMessage();
});

watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) toast.success(flash.success);
        if (flash?.error) toast.error(flash.error);
    },
    { deep: true },
);
</script>

<style scoped>
.public-layout {
    --cv-bg-top: #0b0d12;
    --cv-bg-bottom: #05060a;
    --cv-surface-1: rgba(255, 255, 255, 0.035);
    --cv-surface-2: rgba(255, 255, 255, 0.055);
    --cv-surface-3: rgba(0, 0, 0, 0.35);
    --cv-border-1: rgba(255, 255, 255, 0.08);
    --cv-border-2: rgba(255, 255, 255, 0.12);

    --cv-text-1: #ffffff;
    --cv-text-2: #d1d5db;
    --cv-muted: #9ca3af;
    --cv-muted-2: #6b7280;

    --cv-nav-bg: rgba(0, 0, 0, 0.65);
    --cv-nav-bg-mobile: rgba(0, 0, 0, 0.78);
    --cv-footer-bg: rgba(0, 0, 0, 0.6);

    min-height: 100vh;
    display: flex;
    flex-direction: column;
    color: var(--cv-text-1);
    background: linear-gradient(
        180deg,
        var(--cv-bg-top) 0%,
        var(--cv-bg-bottom) 100%
    );
}

.public-layout[data-theme="light"] {
    --cv-bg-top: #ffffff;
    --cv-bg-bottom: #ffffff;
    --cv-surface-1: rgba(11, 13, 18, 0.03);
    --cv-surface-2: rgba(11, 13, 18, 0.05);
    --cv-surface-3: rgba(11, 13, 18, 0.08);
    --cv-border-1: rgba(11, 13, 18, 0.1);
    --cv-border-2: rgba(11, 13, 18, 0.14);

    --cv-text-1: #0b0d12;
    --cv-text-2: rgba(11, 13, 18, 0.82);
    --cv-muted: rgba(11, 13, 18, 0.6);
    --cv-muted-2: rgba(11, 13, 18, 0.45);

    --cv-nav-bg: rgba(255, 255, 255, 0.85);
    --cv-nav-bg-mobile: rgba(255, 255, 255, 0.92);
    --cv-footer-bg: rgba(255, 255, 255, 0.75);
}

/* Navbar */
.navbar {
    position: sticky;
    top: 0;
    z-index: 100;
    background: var(--cv-nav-bg);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--cv-border-1);
}

.nav-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1.5rem;
    height: 4rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.nav-left {
    display: flex;
    align-items: center;
}

.nav-brand {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    transition: opacity 0.3s ease;
}

.nav-brand:hover {
    opacity: 0.8;
}

.brand-icon {
    font-size: 1.75rem;
}

.brand-text {
    font-size: 1.25rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    white-space: nowrap;
}

.brand-court {
    background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.brand-vision {
    color: var(--cv-text-1);
}

.nav-links {
    display: none;
    gap: 0.25rem;
}

@media (min-width: 1024px) {
    .nav-links {
        display: flex;
    }
}

.nav-link {
    padding: 0.5rem 0.875rem;
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--cv-muted);
    text-decoration: none;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.nav-link:hover {
    color: var(--cv-text-1);
    background: var(--cv-surface-1);
}

.nav-link.active {
    color: #fbbf24;
    background: rgba(251, 191, 36, 0.12);
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.theme-toggle {
    --toggle-border: rgba(251, 191, 36, 0.95);
    display: inline-flex;
    align-items: center;
    justify-content: flex-start;
    width: 3.25rem;
    height: 1.75rem;
    padding: 0.25rem;
    border-radius: 9999px;
    background: transparent;
    border: 1px solid var(--toggle-border);
    color: var(--toggle-border);
    cursor: pointer;
    transition: all 0.3s ease;
}

.theme-toggle:hover {
    background: var(--cv-surface-1);
}

.theme-thumb {
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 9999px;
    border: 1px solid var(--toggle-border);
    background: transparent;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s ease;
}

.theme-toggle.is-dark .theme-thumb {
    transform: translateX(1.5rem);
}

.theme-icon {
    width: 0.95rem;
    height: 0.95rem;
}

.nav-right {
    display: none;
    align-items: center;
    gap: 0.75rem;
}

@media (min-width: 768px) {
    .nav-right {
        display: flex;
    }
}

.btn-login {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);
    color: #ffffff;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-login:hover {
    box-shadow: 0 4px 20px rgba(251, 191, 36, 0.25);
}

.btn-icon {
    width: 1rem;
    height: 1rem;
}

.auth-menu {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.user-avatar {
    width: 2rem;
    height: 2rem;
    border-radius: 0.5rem;
    background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #ffffff;
    font-size: 0.875rem;
}

.user-name {
    font-size: 0.875rem;
    color: var(--cv-text-2);
    font-weight: 500;
}

.btn-dashboard {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 0.375rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-admin {
    background: linear-gradient(135deg, #06b6d4 0%, #2563eb 100%);
    color: #ffffff;
}

.btn-committee {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    color: #ffffff;
}

.btn-logout {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem;
    background: rgba(239, 68, 68, 0.2);
    color: #f87171;
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-logout:hover {
    background: rgba(239, 68, 68, 0.3);
}

.btn-logout .btn-icon {
    width: 1.125rem;
    height: 1.125rem;
}

/* Mobile Toggle */
.mobile-toggle {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem;
    background: transparent;
    border: none;
    color: var(--cv-muted);
    cursor: pointer;
    transition: color 0.3s ease;
}

.mobile-toggle:hover {
    color: var(--cv-text-1);
}

.mobile-toggle svg {
    width: 1.5rem;
    height: 1.5rem;
}

@media (min-width: 1024px) {
    .mobile-toggle {
        display: none;
    }
}

/* Mobile Menu */
.mobile-menu {
    display: flex;
    flex-direction: column;
    padding: 1rem;
    border-top: 1px solid var(--cv-border-1);
    background: var(--cv-nav-bg-mobile);
}

@media (min-width: 1024px) {
    .mobile-menu {
        display: none;
    }
}

.mobile-link {
    padding: 0.75rem 1rem;
    color: var(--cv-muted);
    text-decoration: none;
    border-radius: 0.5rem;
    font-size: 0.9375rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    transition: all 0.3s ease;
}

.mobile-link:hover,
.mobile-link.active {
    color: var(--cv-text-1);
    background: var(--cv-surface-1);
}

.mobile-link.active {
    color: #fbbf24;
}

.mobile-divider {
    height: 1px;
    background: var(--cv-border-1);
    margin: 0.5rem 0;
}

.mobile-logout {
    color: #f87171;
    background: transparent;
    border: none;
    text-align: left;
    cursor: pointer;
    width: 100%;
    font-family: inherit;
}

.mobile-logout:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #f87171;
}

/* Main Content */
.main-content {
    flex: 1;
    min-width: 0;
}

.content-wrapper {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
    min-width: 0;
}

.offseason-gate {
    border-radius: 1rem;
    padding: 1.25rem;
    border: 1px solid rgba(251, 191, 36, 0.35);
    background: rgba(251, 191, 36, 0.06);
    min-height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.offseason-center {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    text-align: center;
    max-width: 720px;
}

.offseason-image {
    width: min(420px, 100%);
    height: auto;
    opacity: 0.9;
    border-radius: 1rem;
}

.offseason-top {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.offseason-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 900;
    letter-spacing: -0.02em;
    color: rgba(251, 191, 36, 0.95);
}

.offseason-subtitle {
    margin: 0;
    font-size: 0.95rem;
    color: var(--cv-text-2);
    line-height: 1.45;
}

.offseason-link {
    color: rgba(251, 191, 36, 0.95);
    font-weight: 800;
    text-decoration: none;
}

.offseason-link:hover {
    text-decoration: underline;
}

@media (max-width: 640px) {
    .nav-container {
        padding: 0 1rem;
    }

    .content-wrapper {
        padding: 1.25rem 1rem;
    }

    .footer-container {
        padding: 1.5rem 1rem;
    }
}

/* Footer */
.footer {
    margin-top: auto;
    background: var(--cv-footer-bg);
    border-top: 1px solid var(--cv-border-1);
    overflow: hidden;
}

.footer-container {
    position: relative;
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
}

.footer-content {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
}

.footer-brand {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--cv-text-2);
}

.footer-icon {
    font-size: 1.25rem;
}

.footer-text {
    color: var(--cv-muted-2);
    font-size: 0.875rem;
    margin: 0;
}

.footer-glow {
    position: absolute;
    bottom: -50%;
    left: 50%;
    transform: translateX(-50%);
    width: 60%;
    height: 150%;
    background: radial-gradient(
        ellipse,
        rgba(6, 182, 212, 0.05) 0%,
        transparent 60%
    );
    pointer-events: none;
}
</style>
