<template>
    <div class="committee-layout">
        <!-- Sidebar -->
        <aside
            class="sidebar"
            :class="{
                'sidebar-open': sidebarOpen,
                'sidebar-collapsed': sidebarCollapsed,
            }"
        >
            <!-- Logo -->
            <div class="sidebar-logo">
                <Link :href="route('committee.dashboard')" class="logo-link">
                    <div class="logo-icon">
                        <span>🏀</span>
                    </div>
                    <span
                        class="logo-text"
                        :class="{ 'hidden-collapsed': sidebarCollapsed }"
                        >Committee</span
                    >
                </Link>
                <!-- Collapse Toggle (Desktop) -->
                <button
                    @click="sidebarCollapsed = !sidebarCollapsed"
                    class="collapse-toggle"
                >
                    <svg
                        v-if="!sidebarCollapsed"
                        class="icon"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7"
                        ></path>
                    </svg>
                    <svg
                        v-else
                        class="icon"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 5l7 7-7 7M5 5l7 7-7 7"
                        ></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="sidebar-nav">
                <Link
                    :href="route('committee.dashboard')"
                    :class="[
                        'nav-item',
                        {
                            'nav-active': route().current(
                                'committee.dashboard',
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
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                            ></path>
                        </svg>
                    </div>
                    <span :class="{ 'hidden-collapsed': sidebarCollapsed }"
                        >Dashboard</span
                    >
                    <div class="nav-glow"></div>
                </Link>

                <Link
                    :href="route('committee.matches.index')"
                    :class="[
                        'nav-item',
                        {
                            'nav-active': route().current(
                                'committee.matches.*',
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
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            ></path>
                        </svg>
                    </div>
                    <span :class="{ 'hidden-collapsed': sidebarCollapsed }"
                        >Matches</span
                    >
                    <div class="nav-glow"></div>
                </Link>

                <Link
                    :href="route('committee.stats.index')"
                    :class="[
                        'nav-item',
                        { 'nav-active': route().current('committee.stats.*') },
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
                    <span :class="{ 'hidden-collapsed': sidebarCollapsed }"
                        >Statistics</span
                    >
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
                        <span :class="{ 'hidden-collapsed': sidebarCollapsed }"
                            >Public Site</span
                        >
                    </Link>
                </div>
            </nav>

            <!-- User Info -->
            <div class="sidebar-user">
                <div class="user-avatar">
                    {{
                        $page.props.auth.user?.name?.charAt(0)?.toUpperCase() ||
                        "C"
                    }}
                </div>
                <div
                    class="user-info"
                    :class="{ 'hidden-collapsed': sidebarCollapsed }"
                >
                    <p class="user-name">{{ $page.props.auth.user?.name }}</p>
                    <p class="user-role">Committee Member</p>
                </div>
                <Link
                    v-if="!sidebarCollapsed"
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
        <div
            class="main-content"
            :class="{ 'main-collapsed': sidebarCollapsed }"
        >
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
                <div
                    v-if="$page.props.flash?.success"
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
                <div
                    v-if="$page.props.flash?.error"
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

                <slot />
            </main>
        </div>

        <!-- Decorative Elements -->
        <div class="decor-circle decor-top"></div>
        <div class="decor-circle decor-bottom"></div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

const sidebarOpen = ref(false);
const sidebarCollapsed = ref(false);
</script>

<style scoped>
/* Layout Base */
.committee-layout {
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
    transition: all 0.3s ease;
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

    .sidebar-collapsed {
        width: 5rem;
    }
}

/* Collapse Toggle Button */
.collapse-toggle {
    display: none;
    position: absolute;
    top: 50%;
    right: 0.5rem;
    transform: translateY(-50%);
    width: 1.5rem;
    height: 1.5rem;
    background: var(--cv-surface-2);
    border: 1px solid var(--cv-border-1);
    border-radius: 50%;
    color: #e5e7eb;
    cursor: pointer;
    z-index: 60;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: none;
}

.collapse-toggle:hover {
    background: var(--cv-surface-3);
    border-color: var(--cv-border-2);
    color: #ffffff;
    transform: translateY(-50%);
}

.collapse-toggle .icon {
    width: 0.875rem;
    height: 0.875rem;
}

@media (min-width: 1024px) {
    .collapse-toggle {
        display: flex;
    }

    .sidebar-collapsed .collapse-toggle {
        right: 50%;
        transform: translateY(-50%) translateX(50%);
    }

    .sidebar-collapsed .collapse-toggle:hover {
        transform: translateY(-50%) translateX(50%) scale(1.1);
    }

    .sidebar-collapsed .sidebar-logo {
        padding-right: 0;
    }
}

/* Hidden when collapsed */
.hidden-collapsed {
    opacity: 1;
    transition: opacity 0.2s ease;
}

@media (min-width: 1024px) {
    .sidebar-collapsed .hidden-collapsed {
        opacity: 0;
        width: 0;
        overflow: hidden;
        position: absolute;
    }
}

/* Logo */
.sidebar-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 5rem;
    border-bottom: 1px solid var(--cv-border-1);
    position: relative;
    padding-right: 2rem;
}

@media (max-width: 1023px) {
    .sidebar-logo {
        padding-right: 0;
    }
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
    transition: margin-left 0.3s ease;
}

@media (min-width: 1024px) {
    .main-content {
        margin-left: 16rem;
    }

    .main-collapsed {
        margin-left: 5rem;
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

/* Shared "shadcn-like" overrides for slotted committee pages */
.committee-layout :deep(.glass-card),
.committee-layout :deep(.stat-card) {
    background: var(--cv-surface-1) !important;
    border: 1px solid var(--cv-border-1) !important;
    backdrop-filter: none !important;
    box-shadow: none !important;
}

.committee-layout :deep(.glass-card) {
    padding: 1.25rem !important;
}

.committee-layout :deep(.btn-primary) {
    background: linear-gradient(
        135deg,
        var(--cv-accent-1) 0%,
        var(--cv-accent-2) 100%
    ) !important;
    color: #09090b !important;
    border: 1px solid rgba(var(--cv-accent-1-rgb), 0.35) !important;
    box-shadow: none !important;
}

.committee-layout :deep(.btn-primary:hover:not(:disabled)) {
    box-shadow: 0 10px 22px rgba(0, 0, 0, 0.35) !important;
}

.committee-layout :deep(.btn-primary:disabled) {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
}

.committee-layout :deep(.btn-secondary),
.committee-layout :deep(.action-btn),
.committee-layout :deep(.btn-action),
.committee-layout :deep(.btn-secondary-alt) {
    background: var(--cv-surface-2) !important;
    color: #e5e7eb !important;
    border: 1px solid var(--cv-border-1) !important;
    box-shadow: none !important;
}

.committee-layout :deep(.btn-secondary:hover),
.committee-layout :deep(.action-btn:hover),
.committee-layout :deep(.btn-action:hover),
.committee-layout :deep(.btn-secondary-alt:hover) {
    background: var(--cv-surface-3) !important;
    border-color: var(--cv-border-2) !important;
    color: #ffffff !important;
}

.committee-layout :deep(.btn-edit) {
    background: rgba(var(--cv-accent-1-rgb), 0.12) !important;
    color: rgba(var(--cv-accent-1-rgb), 0.95) !important;
    border: 1px solid rgba(var(--cv-accent-1-rgb), 0.28) !important;
    box-shadow: none !important;
}

.committee-layout :deep(.btn-edit:hover) {
    background: rgba(var(--cv-accent-1-rgb), 0.18) !important;
    color: rgba(var(--cv-accent-1-rgb), 0.98) !important;
    border-color: rgba(var(--cv-accent-1-rgb), 0.35) !important;
}

.committee-layout :deep(.btn-success),
.committee-layout :deep(.btn-approve) {
    background: rgba(34, 197, 94, 0.12) !important;
    color: #4ade80 !important;
    border: 1px solid rgba(34, 197, 94, 0.28) !important;
    box-shadow: none !important;
}

.committee-layout :deep(.btn-success:hover),
.committee-layout :deep(.btn-approve:hover) {
    background: rgba(34, 197, 94, 0.18) !important;
    color: #86efac !important;
    border-color: rgba(34, 197, 94, 0.35) !important;
}

.committee-layout :deep(.btn-warning) {
    background: rgba(245, 158, 11, 0.12) !important;
    color: #fbbf24 !important;
    border: 1px solid rgba(245, 158, 11, 0.28) !important;
    box-shadow: none !important;
}

.committee-layout :deep(.btn-warning:hover) {
    background: rgba(245, 158, 11, 0.18) !important;
    color: #fde68a !important;
    border-color: rgba(245, 158, 11, 0.35) !important;
}

.committee-layout :deep(.btn-danger),
.committee-layout :deep(.btn-delete),
.committee-layout :deep(.btn-reject),
.committee-layout :deep(.de-remove-btn) {
    background: rgba(239, 68, 68, 0.12) !important;
    color: #fca5a5 !important;
    border: 1px solid rgba(239, 68, 68, 0.28) !important;
    box-shadow: none !important;
}

.committee-layout :deep(.btn-danger:hover),
.committee-layout :deep(.btn-delete:hover),
.committee-layout :deep(.btn-reject:hover),
.committee-layout :deep(.de-remove-btn:hover) {
    background: rgba(239, 68, 68, 0.18) !important;
    color: #fecaca !important;
    border-color: rgba(239, 68, 68, 0.35) !important;
}

.committee-layout :deep(.action-icon) {
    background: linear-gradient(
        135deg,
        var(--cv-accent-1) 0%,
        var(--cv-accent-2) 100%
    ) !important;
    border: 1px solid rgba(var(--cv-accent-1-rgb), 0.35) !important;
    color: #09090b !important;
}

.committee-layout :deep(.status-badge),
.committee-layout :deep(.role-badge),
.committee-layout :deep(.division-badge),
.committee-layout :deep(.status-upcoming),
.committee-layout :deep(.status-ongoing),
.committee-layout :deep(.status-paused),
.committee-layout :deep(.status-finished),
.committee-layout :deep(.status-default),
.committee-layout :deep(.status-published),
.committee-layout :deep(.status-draft),
.committee-layout :deep(.role-admin),
.committee-layout :deep(.role-committee) {
    background: var(--cv-surface-2) !important;
    color: #e5e7eb !important;
    border: 1px solid var(--cv-border-1) !important;
}

.committee-layout :deep(.icon-purple),
.committee-layout :deep(.icon-red),
.committee-layout :deep(.icon-cyan) {
    background: var(--cv-surface-3) !important;
    color: #e5e7eb !important;
    border: 1px solid var(--cv-border-1) !important;
}

.committee-layout :deep(.de-title-bar),
.committee-layout :deep(.de-section) {
    background: var(--cv-surface-1) !important;
    border: 1px solid var(--cv-border-1) !important;
}

.committee-layout :deep(.card-link) {
    color: #9ca3af !important;
    text-decoration: none !important;
    transition: color 0.3s ease;
}

.committee-layout :deep(.card-link:hover) {
    color: #ffffff !important;
}

.committee-layout :deep(.match-card),
.committee-layout :deep(.announcement-card) {
    background: var(--cv-surface-2) !important;
    border: 1px solid var(--cv-border-1) !important;
    box-shadow: none !important;
}

.committee-layout :deep(.match-card:hover),
.committee-layout :deep(.announcement-card:hover) {
    border-color: var(--cv-border-2) !important;
}

.committee-layout :deep(.update-btn) {
    background: var(--cv-surface-2) !important;
    color: #e5e7eb !important;
    border: 1px solid var(--cv-border-1) !important;
    box-shadow: none !important;
}

.committee-layout :deep(.update-btn:hover) {
    background: var(--cv-surface-3) !important;
    border-color: var(--cv-border-2) !important;
    color: #ffffff !important;
    box-shadow: none !important;
}

.committee-layout :deep(.score) {
    color: rgba(var(--cv-accent-1-rgb), 0.95) !important;
}

.committee-layout :deep(.time-text) {
    color: #e5e7eb !important;
}

.committee-layout :deep(.stat-icon) {
    background: var(--cv-surface-3) !important;
    color: #e5e7eb !important;
    border: 1px solid var(--cv-border-1) !important;
    box-shadow: none !important;
}

.committee-layout :deep(.pulse-dot) {
    display: none !important;
}

.committee-layout :deep(.stat-highlight-purple),
.committee-layout :deep(.stat-highlight-green),
.committee-layout :deep(.stat-highlight-orange) {
    color: #e5e7eb !important;
}

/* Decorative Elements */
.decor-circle {
    display: none;
}

/* Decorative Elements */
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
