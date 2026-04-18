<template>
    <Head title="Dashboard - Committee | SBA" />
    <CommitteeLayout>
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
                <!-- Assigned Matches -->
                <div class="stat-card">
                    <div class="stat-card-bg stat-bg-purple"></div>
                    <div class="stat-card-content">
                        <div class="stat-row">
                            <div>
                                <p class="stat-label">Assigned Matches</p>
                                <p class="stat-value">
                                    {{ stats.assigned_matches }}
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
                            <span class="stat-highlight-purple">Your</span>
                            <span class="stat-muted">assigned games</span>
                        </div>
                    </div>
                </div>

                <!-- Ongoing -->
                <div class="stat-card">
                    <div class="stat-card-bg stat-bg-green"></div>
                    <div class="stat-card-content">
                        <div class="stat-row">
                            <div>
                                <p class="stat-label">Ongoing</p>
                                <p class="stat-value">
                                    {{ stats.ongoing_matches }}
                                </p>
                            </div>
                            <div class="stat-icon stat-icon-green">
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
                            <span class="stat-highlight-green">Live</span>
                            <span class="stat-muted">right now</span>
                        </div>
                    </div>
                </div>

                <!-- Pending Stats -->
                <div class="stat-card">
                    <div class="stat-card-bg stat-bg-orange"></div>
                    <div class="stat-card-content">
                        <div class="stat-row">
                            <div>
                                <p class="stat-label">Pending Stats</p>
                                <p class="stat-value">
                                    {{ stats.pending_stats }}
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
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="stat-footer">
                            <span class="stat-highlight-orange">Needs</span>
                            <span class="stat-muted">input</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Upcoming Matches -->
                <div class="glass-card">
                    <div class="card-header">
                        <h2 class="card-title">Your Upcoming Matches</h2>
                        <Link
                            :href="route('committee.matches.index')"
                            class="card-link"
                            >View All</Link
                        >
                    </div>
                    <div class="matches-list" v-if="upcomingMatches.length > 0">
                        <div
                            v-for="match in upcomingMatches"
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
                                <Link
                                    :href="
                                        route(
                                            'committee.matches.show',
                                            match.id,
                                        )
                                    "
                                    class="update-btn"
                                >
                                    Update
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div v-else class="empty-state">
                        <p>No upcoming matches assigned</p>
                    </div>
                </div>

                <!-- Quick Actions & Tips -->
                <div class="sidebar-cards">
                    <!-- Quick Actions -->
                    <div class="glass-card">
                        <div class="card-header">
                            <h2 class="card-title">Quick Actions</h2>
                        </div>
                        <div class="actions-list">
                            <Link
                                :href="route('committee.matches.index')"
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
                                <span>View Matches</span>
                            </Link>

                            <Link
                                :href="route('committee.stats.index')"
                                class="action-btn"
                            >
                                <div class="action-icon action-icon-pink">
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
                                <span>Enter Stats</span>
                            </Link>
                        </div>
                    </div>

                    <!-- Tips Card -->
                    <div class="glass-card tips-card">
                        <div class="card-header">
                            <h2 class="card-title">Tips</h2>
                        </div>
                        <ul class="tips-list">
                            <li>Update scores in real-time during matches</li>
                            <li>Enter player statistics after each game</li>
                            <li>Review standings to verify accuracy</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </CommitteeLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import CommitteeLayout from "@/Layouts/CommitteeLayout.vue";

defineProps({
    stats: {
        type: Object,
        default: () => ({
            assigned_matches: 0,
            ongoing_matches: 0,
            pending_stats: 0,
        }),
    },
    upcomingMatches: {
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
    color: #a855f7;
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
        grid-template-columns: repeat(3, 1fr);
    }
}

/* Stat Card */
.stat-card {
    position: relative;
    border-radius: 0.75rem;
    overflow: hidden;
    border: 1px solid rgba(51, 65, 85, 0.5);
    backdrop-filter: blur(4px);
    transition: all 0.3s ease;
}

.stat-card:hover {
    border-color: rgba(168, 85, 247, 0.3);
    box-shadow: 0 10px 40px rgba(168, 85, 247, 0.1);
}

.stat-card-bg {
    display: none;
}

.stat-bg-purple {
    background: linear-gradient(
        135deg,
        rgba(168, 85, 247, 0.2) 0%,
        rgba(236, 72, 153, 0.2) 100%
    );
}
.stat-bg-green {
    background: linear-gradient(
        135deg,
        rgba(34, 197, 94, 0.2) 0%,
        rgba(16, 185, 129, 0.2) 100%
    );
}
.stat-bg-orange {
    background: linear-gradient(
        135deg,
        rgba(249, 115, 22, 0.2) 0%,
        rgba(251, 146, 60, 0.2) 100%
    );
}

.stat-card-content {
    position: relative;
    padding: 1.5rem;
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
    font-size: 2.25rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0.5rem 0 0 0;
}

.stat-icon {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    position: relative;
}

.stat-icon-purple {
    background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
}
.stat-icon-green {
    background: linear-gradient(135deg, #22c55e 0%, #10b981 100%);
}
.stat-icon-orange {
    background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
}

.stat-icon .icon {
    width: 1.5rem;
    height: 1.5rem;
}

.pulse-dot {
    position: absolute;
    top: -0.25rem;
    right: -0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    background: #22c55e;
    border-radius: 50%;
    animation: pulse 2s infinite;
    box-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
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
    margin-top: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.stat-highlight-purple {
    color: #c084fc;
}
.stat-highlight-green {
    color: #4ade80;
}
.stat-highlight-orange {
    color: #fb923c;
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
    background: rgba(30, 41, 59, 0.5);
    backdrop-filter: blur(4px);
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
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
    color: #a855f7;
    text-decoration: none;
    transition: color 0.3s ease;
}

.card-link:hover {
    color: #c084fc;
}

/* Sidebar Cards */
.sidebar-cards {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Matches List */
.matches-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.match-card {
    padding: 1rem;
    background: rgba(51, 65, 85, 0.3);
    border-radius: 0.75rem;
    border: 1px solid rgba(71, 85, 105, 0.3);
    transition: all 0.3s ease;
}

.match-card:hover {
    border-color: rgba(168, 85, 247, 0.3);
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

.update-btn {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: #ffffff;
    background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
    border-radius: 0.5rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.update-btn:hover {
    box-shadow: 0 5px 15px rgba(168, 85, 247, 0.4);
}

.empty-state {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}

/* Actions List */
.actions-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    border-radius: 0.75rem;
    background: rgba(51, 65, 85, 0.3);
    border: 1px solid rgba(71, 85, 105, 0.3);
    color: #d1d5db;
    text-decoration: none;
    transition: all 0.3s ease;
}

.action-btn:hover {
    color: #ffffff;
    border-color: rgba(168, 85, 247, 0.3);
    background: rgba(51, 65, 85, 0.5);
}

.action-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
}

.action-icon-purple {
    background: linear-gradient(135deg, #a855f7 0%, #8b5cf6 100%);
}
.action-icon-pink {
    background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
}

.action-icon .icon {
    width: 1.25rem;
    height: 1.25rem;
}

/* Tips Card */
.tips-card {
    background: linear-gradient(
        135deg,
        rgba(168, 85, 247, 0.1) 0%,
        rgba(236, 72, 153, 0.1) 100%
    );
    border-color: rgba(168, 85, 247, 0.3);
}

.tips-list {
    margin: 0;
    padding: 0;
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.tips-list li {
    position: relative;
    padding-left: 1.5rem;
    color: #d1d5db;
    font-size: 0.875rem;
}

.tips-list li::before {
    content: "•";
    position: absolute;
    left: 0;
    color: #a855f7;
    font-weight: bold;
}

/* General Icons */
.icon {
    width: 1.25rem;
    height: 1.25rem;
}
</style>
