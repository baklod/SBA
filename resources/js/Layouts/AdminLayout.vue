<template>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar" :class="{ 'sidebar-open': sidebarOpen }">
            <!-- Logo -->
            <div class="sidebar-logo">
                <Link :href="route('admin.dashboard')" class="logo-link">
                    <div class="logo-icon">
                        <span>🏀</span>
                    </div>
                    <span class="logo-text">Admin Panel</span>
                </Link>
            </div>

            <!-- Navigation -->
            <nav class="sidebar-nav">
                <Link
                    :href="route('admin.dashboard')"
                    :class="[
                        'nav-item',
                        { 'nav-active': route().current('admin.dashboard') },
                    ]"
                >
                    <div class="nav-icon">
                        <svg
                            class="icon"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                            ></path>
                        </svg>
                    </div>
                    <span>Dashboard</span>
                    <div class="nav-glow"></div>
                </Link>

                <Link
                    :href="route('admin.divisions.index')"
                    :class="[
                        'nav-item',
                        { 'nav-active': route().current('admin.divisions.*') },
                    ]"
                >
                    <div class="nav-icon">
                        <svg
                            class="icon"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                            ></path>
                        </svg>
                    </div>
                    <span>Divisions</span>
                    <div class="nav-glow"></div>
                </Link>

                <Link
                    :href="route('admin.teams.index')"
                    :class="[
                        'nav-item',
                        { 'nav-active': route().current('admin.teams.*') },
                    ]"
                >
                    <div class="nav-icon">
                        <svg
                            class="icon"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                            ></path>
                        </svg>
                    </div>
                    <span>Teams</span>
                    <div class="nav-glow"></div>
                </Link>

                <Link
                    :href="route('admin.team-requests.index')"
                    :class="[
                        'nav-item',
                        {
                            'nav-active': route().current(
                                'admin.team-requests.*',
                            ),
                        },
                    ]"
                >
                    <div class="nav-icon">
                        <svg
                            class="icon"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                            ></path>
                        </svg>
                    </div>
                    <span>Requesting Teams</span>
                    <div class="nav-glow"></div>
                </Link>

                <Link
                    :href="route('admin.players.index')"
                    :class="[
                        'nav-item',
                        { 'nav-active': route().current('admin.players.*') },
                    ]"
                >
                    <div class="nav-icon">
                        <svg
                            class="icon"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                            ></path>
                        </svg>
                    </div>
                    <span>Players</span>
                    <div class="nav-glow"></div>
                </Link>

                <Link
                    :href="route('admin.matches.index')"
                    :class="[
                        'nav-item',
                        { 'nav-active': route().current('admin.matches.*') },
                    ]"
                >
                    <div class="nav-icon">
                        <svg
                            class="icon"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            ></path>
                        </svg>
                    </div>
                    <span>Matches</span>
                    <div class="nav-glow"></div>
                </Link>

                <Link
                    :href="route('admin.announcements.index')"
                    :class="[
                        'nav-item',
                        {
                            'nav-active': route().current(
                                'admin.announcements.*',
                            ),
                        },
                    ]"
                >
                    <div class="nav-icon">
                        <svg
                            class="icon"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"
                            ></path>
                        </svg>
                    </div>
                    <span>Announcements</span>
                    <div class="nav-glow"></div>
                </Link>

                <Link
                    :href="route('admin.standings.index')"
                    :class="[
                        'nav-item',
                        {
                            'nav-active': route().current('admin.standings.*'),
                        },
                    ]"
                >
                    <div class="nav-icon">
                        <svg
                            class="icon"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                            ></path>
                        </svg>
                    </div>
                    <span>Standings</span>
                    <div class="nav-glow"></div>
                </Link>

                <Link
                    :href="route('admin.users.index')"
                    :class="[
                        'nav-item',
                        { 'nav-active': route().current('admin.users.*') },
                    ]"
                >
                    <div class="nav-icon">
                        <svg
                            class="icon"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                            ></path>
                        </svg>
                    </div>
                    <span>Users</span>
                    <div class="nav-glow"></div>
                </Link>

                <Link
                    :href="route('admin.settings.index')"
                    :class="[
                        'nav-item',
                        { 'nav-active': route().current('admin.settings.*') },
                    ]"
                >
                    <div class="nav-icon">
                        <svg
                            class="icon"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                            ></path>
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            ></path>
                        </svg>
                    </div>
                    <span>Settings</span>
                    <div class="nav-glow"></div>
                </Link>

                <div class="nav-divider">
                    <Link :href="route('home')" class="nav-item nav-external">
                        <div class="nav-icon">
                            <svg
                                class="icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                ></path>
                            </svg>
                        </div>
                        <span>Public Site</span>
                    </Link>
                </div>
            </nav>

            <!-- User Info -->
            <div class="sidebar-user">
                <div class="user-avatar">
                    {{
                        $page.props.auth.user?.name?.charAt(0)?.toUpperCase() ||
                        "A"
                    }}
                </div>
                <div class="user-info">
                    <p class="user-name">{{ $page.props.auth.user?.name }}</p>
                    <p class="user-role">Administrator</p>
                </div>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="logout-btn"
                >
                    <svg
                        class="icon"
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
        </aside>

        <!-- Mobile Sidebar Overlay -->
        <div
            v-if="sidebarOpen"
            class="sidebar-overlay"
            @click="sidebarOpen = false"
        ></div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <header class="topbar">
                <button @click="sidebarOpen = !sidebarOpen" class="menu-btn">
                    <svg
                        class="icon"
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
                </button>

                <div class="topbar-spacer"></div>

                <div class="system-status">
                    <div class="status-dot"></div>
                    <span>System Online</span>
                </div>
            </header>

            <!-- Page Content -->
            <main class="page-content">
                <!-- Flash Messages -->
                <transition name="flash-fade">
                    <div
                        v-if="showFlashSuccess"
                        class="flash-message flash-success"
                    >
                        <svg
                            class="icon"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            ></path>
                        </svg>
                        <span>{{ $page.props.flash.success }}</span>
                    </div>
                </transition>
                <transition name="flash-fade">
                    <div
                        v-if="showFlashError"
                        class="flash-message flash-error"
                    >
                        <svg
                            class="icon"
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
                        <span>{{ $page.props.flash.error }}</span>
                    </div>
                </transition>

                <slot />
            </main>
        </div>

        <!-- Decorative Elements -->
        <div class="decor-circle decor-top"></div>
        <div class="decor-circle decor-bottom"></div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const sidebarOpen = ref(false);
const showFlashSuccess = ref(false);
const showFlashError = ref(false);

let successTimer = null;
let errorTimer = null;

const startFlashTimers = () => {
    const page = usePage();
    if (page.props.flash?.success) {
        showFlashSuccess.value = true;
        clearTimeout(successTimer);
        successTimer = setTimeout(() => {
            showFlashSuccess.value = false;
        }, 3000);
    } else {
        showFlashSuccess.value = false;
    }
    if (page.props.flash?.error) {
        showFlashError.value = true;
        clearTimeout(errorTimer);
        errorTimer = setTimeout(() => {
            showFlashError.value = false;
        }, 5000);
    } else {
        showFlashError.value = false;
    }
};

onMounted(startFlashTimers);

watch(
    () => usePage().props.flash,
    () => startFlashTimers(),
    { deep: true },
);
</script>

<style scoped>
/* Layout Base */
.admin-layout {
    --cv-bg-top: #0b0d12;
    --cv-bg-bottom: #05060a;
    --cv-surface-1: rgba(255, 255, 255, 0.03);
    --cv-surface-2: rgba(255, 255, 255, 0.05);
    --cv-surface-3: rgba(255, 255, 255, 0.08);
    --cv-border-1: rgba(255, 255, 255, 0.08);
    --cv-border-2: rgba(255, 255, 255, 0.12);

    --cv-accent-1: #fbbf24;
    --cv-accent-2: #f97316;
    --cv-accent-1-rgb: 251, 191, 36;
    --cv-accent-2-rgb: 249, 115, 22;

    min-height: 100vh;
    background: linear-gradient(
        180deg,
        var(--cv-bg-top) 0%,
        var(--cv-bg-bottom) 100%
    );
}

/* Sidebar */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    width: 16rem;
    background: linear-gradient(
        180deg,
        var(--cv-bg-top) 0%,
        var(--cv-bg-bottom) 100%
    );
    border-right: 1px solid var(--cv-border-1);
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    z-index: 50;
    display: flex;
    flex-direction: column;
}

.sidebar-open {
    transform: translateX(0);
}

@media (min-width: 1024px) {
    .sidebar {
        transform: translateX(0);
    }
}

/* Logo */
.sidebar-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 5rem;
    border-bottom: 1px solid var(--cv-border-1);
}

.logo-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
}

.logo-icon {
    width: 2.5rem;
    height: 2.5rem;
    background: var(--cv-surface-2);
    border: 1px solid var(--cv-border-1);
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: none;
    font-size: 1.5rem;
}

.logo-text {
    font-size: 1.25rem;
    font-weight: 700;
    color: #ffffff;
}

/* Navigation */
.sidebar-nav {
    margin-top: 1rem;
    padding: 0 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    flex: 1;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    text-decoration: none;
    color: #9ca3af;
    font-size: 0.875rem;
}

.nav-item:hover {
    color: #ffffff;
}

.nav-icon {
    width: 1.75rem;
    height: 1.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.375rem;
    background: var(--cv-surface-1);
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.nav-item:hover .nav-icon {
    background: var(--cv-surface-2);
}

.nav-active {
    color: #ffffff;
    background: var(--cv-surface-2);
    border: 1px solid var(--cv-border-2);
}

.nav-active .nav-icon {
    background: var(--cv-surface-3);
    border: 1px solid var(--cv-border-2);
    color: #ffffff;
    box-shadow: none;
}

.nav-glow {
    display: none;
}

.nav-divider {
    padding-top: 0.75rem;
    margin-top: 0.75rem;
    border-top: 1px solid var(--cv-border-1);
}

.nav-external:hover {
    color: #ffffff;
}

/* User Section */
.sidebar-user {
    padding: 1rem;
    border-top: 1px solid var(--cv-border-1);
    background: var(--cv-bg-top);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 2.5rem;
    height: 2.5rem;
    background: var(--cv-surface-2);
    border: 1px solid var(--cv-border-1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-weight: 700;
    box-shadow: none;
}

.user-info {
    flex: 1;
    min-width: 0;
}

.user-name {
    font-size: 0.875rem;
    font-weight: 500;
    color: #ffffff;
    margin: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.user-role {
    font-size: 0.75rem;
    color: #9ca3af;
    margin: 0;
}

.logout-btn {
    padding: 0.5rem;
    color: #9ca3af;
    background: transparent;
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.logout-btn:hover {
    color: #ffffff;
    background: var(--cv-surface-2);
}

/* Sidebar Overlay */
.sidebar-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    z-index: 40;
}

@media (min-width: 1024px) {
    .sidebar-overlay {
        display: none;
    }
}

/* Main Content */
.main-content {
    min-height: 100vh;
}

@media (min-width: 1024px) {
    .main-content {
        margin-left: 16rem;
    }
}

/* Topbar */
.topbar {
    position: sticky;
    top: 0;
    z-index: 30;
    background: linear-gradient(
        180deg,
        var(--cv-bg-top) 0%,
        var(--cv-bg-bottom) 100%
    );
    border-bottom: 1px solid var(--cv-border-1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 4rem;
    padding: 0 1rem;
}

@media (min-width: 640px) {
    .topbar {
        padding: 0 1.5rem;
    }
}

@media (min-width: 1024px) {
    .topbar {
        padding: 0 2rem;
    }
}

.menu-btn {
    padding: 0.5rem;
    color: #9ca3af;
    background: transparent;
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.menu-btn:hover {
    color: #ffffff;
    background: var(--cv-surface-2);
}

@media (min-width: 1024px) {
    .menu-btn {
        display: none;
    }
}

.topbar-spacer {
    flex: 1;
}

@media (min-width: 1024px) {
    .topbar-spacer {
        flex: none;
    }
}

.system-status {
    display: none;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #9ca3af;
}

@media (min-width: 640px) {
    .system-status {
        display: flex;
    }
}

.status-dot {
    width: 0.5rem;
    height: 0.5rem;
    background: #9ca3af;
    border-radius: 50%;
    animation: none;
    box-shadow: none;
}

@keyframes pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

/* Page Content */
.page-content {
    padding: 1rem;
}

@media (min-width: 640px) {
    .page-content {
        padding: 1.5rem;
    }
}

@media (min-width: 1024px) {
    .page-content {
        padding: 2rem;
    }
}

/* Flash Messages */
.flash-message {
    margin-bottom: 1.5rem;
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    backdrop-filter: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.flash-fade-enter-active {
    transition:
        opacity 0.3s ease,
        transform 0.3s ease;
}

.flash-fade-leave-active {
    transition:
        opacity 0.5s ease,
        transform 0.5s ease;
}

.flash-fade-enter-from {
    opacity: 0;
    transform: translateY(-10px);
}

.flash-fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

.flash-success {
    background: var(--cv-surface-1);
    border: 1px solid var(--cv-border-1);
    color: #e5e7eb;
}

.flash-error {
    background: var(--cv-surface-1);
    border: 1px solid var(--cv-border-1);
    color: #e5e7eb;
}

/* Shared "shadcn-like" overrides for slotted admin pages */
.admin-layout :deep(.glass-card),
.admin-layout :deep(.stat-card) {
    background: var(--cv-surface-1) !important;
    border: 1px solid var(--cv-border-1) !important;
    backdrop-filter: none !important;
    box-shadow: none !important;
}

.admin-layout :deep(.glass-card) {
    padding: 1.25rem !important;
}

.admin-layout :deep(.btn-primary) {
    background: linear-gradient(
        135deg,
        var(--cv-accent-1) 0%,
        var(--cv-accent-2) 100%
    ) !important;
    color: #09090b !important;
    border: 1px solid rgba(var(--cv-accent-1-rgb), 0.35) !important;
    box-shadow: none !important;
}

.admin-layout :deep(.btn-primary:hover:not(:disabled)) {
    box-shadow: 0 10px 22px rgba(0, 0, 0, 0.35) !important;
}

.admin-layout :deep(.btn-primary:disabled) {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
}

.admin-layout :deep(.btn-secondary),
.admin-layout :deep(.action-btn),
.admin-layout :deep(.btn-action),
.admin-layout :deep(.btn-secondary-alt) {
    background: var(--cv-surface-2) !important;
    color: #e5e7eb !important;
    border: 1px solid var(--cv-border-1) !important;
    box-shadow: none !important;
}

.admin-layout :deep(.btn-secondary:hover),
.admin-layout :deep(.action-btn:hover),
.admin-layout :deep(.btn-action:hover),
.admin-layout :deep(.btn-secondary-alt:hover) {
    background: var(--cv-surface-3) !important;
    border-color: var(--cv-border-2) !important;
    color: #ffffff !important;
}

.admin-layout :deep(.btn-edit) {
    background: rgba(var(--cv-accent-1-rgb), 0.12) !important;
    color: rgba(var(--cv-accent-1-rgb), 0.95) !important;
    border: 1px solid rgba(var(--cv-accent-1-rgb), 0.28) !important;
    box-shadow: none !important;
}

.admin-layout :deep(.btn-edit:hover) {
    background: rgba(var(--cv-accent-1-rgb), 0.18) !important;
    color: rgba(var(--cv-accent-1-rgb), 0.98) !important;
    border-color: rgba(var(--cv-accent-1-rgb), 0.35) !important;
}

.admin-layout :deep(.btn-success),
.admin-layout :deep(.btn-approve) {
    background: rgba(34, 197, 94, 0.12) !important;
    color: #4ade80 !important;
    border: 1px solid rgba(34, 197, 94, 0.28) !important;
    box-shadow: none !important;
}

.admin-layout :deep(.btn-success:hover),
.admin-layout :deep(.btn-approve:hover) {
    background: rgba(34, 197, 94, 0.18) !important;
    color: #86efac !important;
    border-color: rgba(34, 197, 94, 0.35) !important;
}

.admin-layout :deep(.btn-warning) {
    background: rgba(245, 158, 11, 0.12) !important;
    color: #fbbf24 !important;
    border: 1px solid rgba(245, 158, 11, 0.28) !important;
    box-shadow: none !important;
}

.admin-layout :deep(.btn-warning:hover) {
    background: rgba(245, 158, 11, 0.18) !important;
    color: #fde68a !important;
    border-color: rgba(245, 158, 11, 0.35) !important;
}

.admin-layout :deep(.btn-danger),
.admin-layout :deep(.btn-delete),
.admin-layout :deep(.btn-reject),
.admin-layout :deep(.de-remove-btn) {
    background: rgba(239, 68, 68, 0.12) !important;
    color: #fca5a5 !important;
    border: 1px solid rgba(239, 68, 68, 0.28) !important;
    box-shadow: none !important;
}

.admin-layout :deep(.btn-danger:hover),
.admin-layout :deep(.btn-delete:hover),
.admin-layout :deep(.btn-reject:hover),
.admin-layout :deep(.de-remove-btn:hover) {
    background: rgba(239, 68, 68, 0.18) !important;
    color: #fecaca !important;
    border-color: rgba(239, 68, 68, 0.35) !important;
}

.admin-layout :deep(.action-icon) {
    background: linear-gradient(
        135deg,
        var(--cv-accent-1) 0%,
        var(--cv-accent-2) 100%
    ) !important;
    border: 1px solid rgba(var(--cv-accent-1-rgb), 0.35) !important;
    color: #09090b !important;
}

.admin-layout :deep(.status-badge),
.admin-layout :deep(.role-badge),
.admin-layout :deep(.division-badge),
.admin-layout :deep(.status-upcoming),
.admin-layout :deep(.status-ongoing),
.admin-layout :deep(.status-finished),
.admin-layout :deep(.status-default),
.admin-layout :deep(.status-published),
.admin-layout :deep(.status-draft),
.admin-layout :deep(.role-admin),
.admin-layout :deep(.role-committee) {
    background: var(--cv-surface-2) !important;
    color: #e5e7eb !important;
    border: 1px solid var(--cv-border-1) !important;
}

.admin-layout :deep(.icon-purple),
.admin-layout :deep(.icon-red),
.admin-layout :deep(.icon-cyan) {
    background: var(--cv-surface-3) !important;
    color: #e5e7eb !important;
    border: 1px solid var(--cv-border-1) !important;
}

.admin-layout :deep(.de-title-bar),
.admin-layout :deep(.de-section) {
    background: var(--cv-surface-1) !important;
    border: 1px solid var(--cv-border-1) !important;
}

/* Decorative Elements */
.decor-circle {
    display: none;
}

/* Icons */
.icon {
    width: 1.25rem;
    height: 1.25rem;
}

.menu-btn .icon,
.logout-btn .icon {
    width: 1.5rem;
    height: 1.5rem;
}
</style>
