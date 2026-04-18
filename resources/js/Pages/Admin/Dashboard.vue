<template>
    <Head title="Dashboard - Admin | SBA" />
    <AdminLayout>
        <div class="dashboard">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1 class="page-title">Dashboard</h1>
                    <p class="page-subtitle">
                        Welcome back, {{ $page.props.auth.user?.name }}
                    </p>
                </div>
                <div class="header-right">
                    <p class="date-text">{{ currentDate }}</p>
                    <p class="time-text">{{ currentTime }}</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <!-- Total Teams -->
                <div class="stat-card">
                    <div class="stat-card-bg stat-bg-cyan"></div>
                    <div class="stat-card-content">
                        <div class="stat-row">
                            <div>
                                <p class="stat-label">Total Teams</p>
                                <p class="stat-value">
                                    {{ stats.total_teams }}
                                </p>
                            </div>
                            <div class="stat-icon stat-icon-cyan">
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
                        </div>
                        <div class="stat-footer">
                            <span class="stat-highlight-cyan">Active</span>
                            <span class="stat-muted">teams in league</span>
                        </div>
                    </div>
                </div>

                <!-- Total Players -->
                <div class="stat-card">
                    <div class="stat-card-bg stat-bg-green"></div>
                    <div class="stat-card-content">
                        <div class="stat-row">
                            <div>
                                <p class="stat-label">Total Players</p>
                                <p class="stat-value">
                                    {{ stats.total_players }}
                                </p>
                            </div>
                            <div class="stat-icon stat-icon-green">
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
                        </div>
                        <div class="stat-footer">
                            <span class="stat-highlight-green">Registered</span>
                            <span class="stat-muted">players</span>
                        </div>
                    </div>
                </div>

                <!-- Total Matches -->
                <div class="stat-card">
                    <div class="stat-card-bg stat-bg-purple"></div>
                    <div class="stat-card-content">
                        <div class="stat-row">
                            <div>
                                <p class="stat-label">Total Matches</p>
                                <p class="stat-value">
                                    {{ stats.total_matches }}
                                </p>
                            </div>
                            <div class="stat-icon stat-icon-purple">
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
                        </div>
                        <div class="stat-footer">
                            <span class="stat-highlight-purple">Scheduled</span>
                            <span class="stat-muted">games</span>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Matches -->
                <div class="stat-card">
                    <div class="stat-card-bg stat-bg-orange"></div>
                    <div class="stat-card-content">
                        <div class="stat-row">
                            <div>
                                <p class="stat-label">Upcoming</p>
                                <p class="stat-value">
                                    {{ stats.upcoming_matches }}
                                </p>
                            </div>
                            <div class="stat-icon stat-icon-orange">
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
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="stat-footer">
                            <span class="stat-highlight-orange">Pending</span>
                            <span class="stat-muted">matches</span>
                        </div>
                    </div>
                </div>

                <!-- Ongoing Matches -->
                <div class="stat-card">
                    <div class="stat-card-bg stat-bg-red"></div>
                    <div class="stat-card-content">
                        <div class="stat-row">
                            <div>
                                <p class="stat-label">Ongoing</p>
                                <p class="stat-value">
                                    {{ stats.ongoing_matches }}
                                </p>
                            </div>
                            <div class="stat-icon stat-icon-red">
                                <div class="pulse-dot"></div>
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
                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"
                                    ></path>
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="stat-footer">
                            <span class="stat-highlight-red">Live</span>
                            <span class="stat-muted">right now</span>
                        </div>
                    </div>
                </div>

                <!-- Finished Matches -->
                <div class="stat-card">
                    <div class="stat-card-bg stat-bg-slate"></div>
                    <div class="stat-card-content">
                        <div class="stat-row">
                            <div>
                                <p class="stat-label">Finished</p>
                                <p class="stat-value">
                                    {{ stats.finished_matches }}
                                </p>
                            </div>
                            <div class="stat-icon stat-icon-slate">
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
                            </div>
                        </div>
                        <div class="stat-footer">
                            <span class="stat-highlight-slate">Completed</span>
                            <span class="stat-muted">games</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Recent Matches -->
                <div class="glass-card">
                    <div class="card-header">
                        <h2 class="card-title">Recent Matches</h2>
                        <Link
                            :href="route('admin.matches.index')"
                            class="card-link"
                            >View All</Link
                        >
                    </div>
                    <div class="matches-list" v-if="recentMatches.length > 0">
                        <div
                            v-for="match in recentMatches"
                            :key="match.id"
                            class="match-card"
                        >
                            <div class="match-teams">
                                <span class="team-name">{{
                                    match.team_a?.name
                                }}</span>
                                <span class="vs-text">vs</span>
                                <span class="team-name">{{
                                    match.team_b?.name
                                }}</span>
                            </div>
                            <div class="match-info">
                                <span class="match-date">{{
                                    formatDate(match.scheduled_at)
                                }}</span>
                                <span
                                    :class="[
                                        'status-badge',
                                        getStatusClass(match.status),
                                    ]"
                                >
                                    {{ match.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div v-else class="empty-state">
                        <p>No matches scheduled yet</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="glass-card">
                    <div class="card-header">
                        <h2 class="card-title">Quick Actions</h2>
                    </div>
                    <div class="actions-grid">
                        <Link
                            :href="route('admin.teams.create')"
                            class="action-btn"
                        >
                            <div class="action-icon action-icon-cyan">
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
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                    ></path>
                                </svg>
                            </div>
                            <span>Add Team</span>
                        </Link>

                        <Link
                            :href="route('admin.players.create')"
                            class="action-btn"
                        >
                            <div class="action-icon action-icon-green">
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
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                                    ></path>
                                </svg>
                            </div>
                            <span>Add Player</span>
                        </Link>

                        <Link
                            :href="route('admin.matches.create')"
                            class="action-btn"
                        >
                            <div class="action-icon action-icon-purple">
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
                            <span>Schedule Match</span>
                        </Link>

                        <Link
                            :href="route('admin.announcements.create')"
                            class="action-btn"
                        >
                            <div class="action-icon action-icon-orange">
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
                            <span>Announcement</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineProps({
    stats: {
        type: Object,
        default: () => ({
            total_teams: 0,
            total_players: 0,
            total_matches: 0,
            upcoming_matches: 0,
            ongoing_matches: 0,
            finished_matches: 0,
        }),
    },
    recentMatches: {
        type: Array,
        default: () => [],
    },
});

const currentTime = ref("");
const currentDate = ref("");
let timeInterval = null;

const updateTime = () => {
    const now = new Date();
    currentTime.value = now.toLocaleTimeString("en-US", {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        hour12: true,
    });
    currentDate.value = now.toLocaleDateString("en-US", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

onMounted(() => {
    updateTime();
    timeInterval = setInterval(updateTime, 1000);
});

onUnmounted(() => {
    if (timeInterval) {
        clearInterval(timeInterval);
    }
});

const formatDate = (date) => {
    if (!date) return "TBD";
    return new Date(date).toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const getStatusClass = (status) => {
    switch (status) {
        case "upcoming":
            return "status-upcoming";
        case "ongoing":
            return "status-ongoing";
        case "finished":
            return "status-finished";
        default:
            return "status-default";
    }
};
</script>

<style scoped>
/* Dashboard Container */
.dashboard {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

/* Header */
.header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.header-left {
    display: flex;
    flex-direction: column;
}

.page-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
}

.page-subtitle {
    color: #9ca3af;
    margin: 0.25rem 0 0 0;
}

.header-right {
    text-align: right;
}

.date-text {
    font-size: 0.875rem;
    color: #9ca3af;
    margin: 0;
}

.time-text {
    font-size: 1.5rem;
    font-family: monospace;
    color: #e5e7eb;
    margin: 0;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 1.5rem;
}

@media (min-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

/* Stat Card */
.stat-card {
    position: relative;
    border-radius: 0.75rem;
    overflow: hidden;
    border: 1px solid var(--cv-border-1);
    background: var(--cv-surface-1);
    transition: all 0.2s ease;
}

.stat-card:hover {
    border-color: var(--cv-border-2);
    background: var(--cv-surface-2);
}

.stat-card-bg {
    display: none;
}

.stat-bg-cyan {
    background: linear-gradient(
        135deg,
        rgba(var(--cv-accent-1-rgb), 0.18) 0%,
        rgba(var(--cv-accent-2-rgb), 0.18) 100%
    );
}
.stat-bg-green {
    background: linear-gradient(
        135deg,
        rgba(34, 197, 94, 0.2) 0%,
        rgba(16, 185, 129, 0.2) 100%
    );
}
.stat-bg-purple {
    background: linear-gradient(
        135deg,
        rgba(168, 85, 247, 0.2) 0%,
        rgba(139, 92, 246, 0.2) 100%
    );
}
.stat-bg-orange {
    background: linear-gradient(
        135deg,
        rgba(249, 115, 22, 0.2) 0%,
        rgba(251, 146, 60, 0.2) 100%
    );
}
.stat-bg-red {
    background: linear-gradient(
        135deg,
        rgba(239, 68, 68, 0.2) 0%,
        rgba(248, 113, 113, 0.2) 100%
    );
}
.stat-bg-slate {
    background: linear-gradient(
        135deg,
        rgba(100, 116, 139, 0.2) 0%,
        rgba(148, 163, 184, 0.2) 100%
    );
}

.stat-card-content {
    position: relative;
    padding: 1rem;
}

.stat-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.stat-label {
    font-size: 0.875rem;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 0;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0.5rem 0 0 0;
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #e5e7eb;
    background: var(--cv-surface-3);
    border: 1px solid var(--cv-border-1);
    box-shadow: none;
    position: relative;
}

.stat-icon-cyan {
    background: var(--cv-surface-3);
}
.stat-icon-green {
    background: var(--cv-surface-3);
}
.stat-icon-purple {
    background: var(--cv-surface-3);
}
.stat-icon-orange {
    background: var(--cv-surface-3);
}
.stat-icon-red {
    background: var(--cv-surface-3);
}
.stat-icon-slate {
    background: var(--cv-surface-3);
}

.stat-icon .icon {
    width: 1.5rem;
    height: 1.5rem;
}

.pulse-dot {
    display: none;
}

@keyframes pulse {
    0%,
    100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.5;
        transform: scale(1.1);
    }
}

.stat-footer {
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.stat-highlight-cyan {
    color: #e5e7eb;
}
.stat-highlight-green {
    color: #e5e7eb;
}
.stat-highlight-purple {
    color: #e5e7eb;
}
.stat-highlight-orange {
    color: #e5e7eb;
}
.stat-highlight-red {
    color: #e5e7eb;
}
.stat-highlight-slate {
    color: #e5e7eb;
}

.stat-muted {
    color: #6b7280;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

@media (min-width: 1024px) {
    .content-grid {
        grid-template-columns: 2fr 1fr;
    }
}

/* Glass Card */
.glass-card {
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    padding: 1.25rem;
    border: 1px solid var(--cv-border-1);
}

.card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}

.card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #ffffff;
    margin: 0;
}

.card-link {
    font-size: 0.875rem;
    color: #d1d5db;
    text-decoration: none;
    transition: color 0.3s ease;
}

.card-link:hover {
    color: #ffffff;
}

/* Matches List */
.matches-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.match-card {
    padding: 1rem;
    background: var(--cv-surface-2);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
    transition: all 0.3s ease;
}

.match-card:hover {
    border-color: var(--cv-border-2);
}

.match-teams {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.team-name {
    font-weight: 500;
    color: #ffffff;
}

.vs-text {
    color: #6b7280;
    font-size: 0.75rem;
}

.match-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.match-date {
    font-size: 0.875rem;
    color: #9ca3af;
}

.status-badge {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 0.5rem;
    background: var(--cv-surface-2);
    color: #e5e7eb;
    border: 1px solid var(--cv-border-1);
}

.status-upcoming {
    background: var(--cv-surface-2);
    color: #e5e7eb;
    border-color: var(--cv-border-1);
}

.status-ongoing {
    background: var(--cv-surface-2);
    color: #e5e7eb;
    border-color: var(--cv-border-1);
}

.status-finished {
    background: var(--cv-surface-2);
    color: #e5e7eb;
    border-color: var(--cv-border-1);
}

.status-default {
    background: var(--cv-surface-2);
    color: #e5e7eb;
    border-color: var(--cv-border-1);
}

.empty-state {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}

/* Actions Grid */
.actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.625rem;
    border-radius: 0.75rem;
    background: var(--cv-surface-1);
    border: 1px solid var(--cv-border-1);
    color: #d1d5db;
    text-decoration: none;
    transition: all 0.3s ease;
}

.action-btn:hover {
    color: #ffffff;
    border-color: var(--cv-border-2);
    background: var(--cv-surface-2);
}

.action-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #e5e7eb;
    background: var(--cv-surface-3);
    border: 1px solid var(--cv-border-1);
}

.action-icon-cyan {
    background: var(--cv-surface-3);
}
.action-icon-green {
    background: var(--cv-surface-3);
}
.action-icon-purple {
    background: var(--cv-surface-3);
}
.action-icon-orange {
    background: var(--cv-surface-3);
}

.action-icon .icon {
    width: 1.25rem;
    height: 1.25rem;
}

/* General Icons */
.icon {
    width: 1.25rem;
    height: 1.25rem;
}
</style>
