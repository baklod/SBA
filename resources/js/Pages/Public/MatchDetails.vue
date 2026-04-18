<template>
    <Head title="Match Details | SBA" />
    <PublicLayout>
        <div class="match-details-page">
            <!-- Back Link -->
            <Link :href="route('schedule')" class="back-link">
                ← Back to Schedule
            </Link>

            <!-- Match Header -->
            <div class="match-header">
                <div class="teams-display">
                    <div class="team-block">
                        <div class="team-avatar">
                            <img
                                v-if="resolveTeamLogoUrl(match.team_a?.logo)"
                                :src="resolveTeamLogoUrl(match.team_a?.logo)"
                                :alt="
                                    (match.team_a?.name || 'Team A') + ' logo'
                                "
                                class="team-avatar-image"
                                loading="lazy"
                            />
                            <span v-else class="team-avatar-fallback">
                                {{ match.team_a?.name?.charAt(0) || "A" }}
                            </span>
                        </div>
                        <span class="team-name">{{ match.team_a?.name }}</span>
                        <span
                            class="team-score"
                            :class="{ winner: isTeamAWinner }"
                        >
                            {{ match.team_a_score ?? 0 }}
                        </span>
                    </div>

                    <div class="vs-divider">
                        <span class="vs-text">VS</span>
                        <span
                            :class="['status-badge', 'status-' + match.status]"
                        >
                            {{ match.status?.toUpperCase() }}
                        </span>
                    </div>

                    <div class="team-block">
                        <div class="team-avatar team-avatar-alt">
                            <img
                                v-if="resolveTeamLogoUrl(match.team_b?.logo)"
                                :src="resolveTeamLogoUrl(match.team_b?.logo)"
                                :alt="
                                    (match.team_b?.name || 'Team B') + ' logo'
                                "
                                class="team-avatar-image"
                                loading="lazy"
                            />
                            <span v-else class="team-avatar-fallback">
                                {{ match.team_b?.name?.charAt(0) || "B" }}
                            </span>
                        </div>
                        <span class="team-name">{{ match.team_b?.name }}</span>
                        <span
                            class="team-score"
                            :class="{ winner: isTeamBWinner }"
                        >
                            {{ match.team_b_score ?? 0 }}
                        </span>
                    </div>
                </div>

                <div class="match-info">
                    <div class="info-item" v-if="match.match_date">
                        <svg
                            class="info-icon"
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
                        {{ formatDate(match.match_date) }}
                    </div>
                    <div class="info-item" v-if="match.match_time">
                        <svg
                            class="info-icon"
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
                        {{ formatTime(match.match_time) }}
                    </div>
                    <div class="info-item" v-if="match.venue">
                        <svg
                            class="info-icon"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                            ></path>
                        </svg>
                        {{ match.venue }}
                    </div>
                    <div class="info-item" v-if="match.quarter">
                        <span class="quarter-badge">Q{{ match.quarter }}</span>
                    </div>
                </div>
            </div>

            <!-- MVP Section -->
            <div v-if="mvp && match.status === 'finished'" class="mvp-section">
                <div class="mvp-card">
                    <div class="mvp-badge">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                            ></path>
                        </svg>
                        MVP
                    </div>
                    <div class="mvp-info">
                        <img
                            v-if="mvp.player?.photo"
                            :src="resolvePlayerPhotoUrl(mvp.player.photo)"
                            :alt="mvp.player?.name"
                            class="mvp-photo"
                        />
                        <div v-else class="mvp-avatar">
                            {{ mvp.player?.name?.charAt(0) || "M" }}
                        </div>
                        <div class="mvp-details">
                            <span class="mvp-name">{{ mvp.player?.name }}</span>
                            <span class="mvp-team"
                                >{{ mvp.player?.team?.name }} - #{{
                                    mvp.player?.jersey_number
                                }}</span
                            >
                        </div>
                    </div>
                    <div class="mvp-stats">
                        <div class="stat-item">
                            <span class="stat-value">{{ mvp.points }}</span>
                            <span class="stat-label">PTS</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">{{ mvp.assists }}</span>
                            <span class="stat-label">AST</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">{{ mvp.rebounds }}</span>
                            <span class="stat-label">REB</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">{{ mvp.blocks }}</span>
                            <span class="stat-label">BLK</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">{{ mvp.steals }}</span>
                            <span class="stat-label">STL</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Player Stats -->
            <div class="stats-section">
                <!-- Team A Stats -->
                <div class="team-stats-card">
                    <h2 class="card-title">
                        <span class="team-indicator team-a"></span>
                        {{ match.team_a?.name }} Stats
                    </h2>
                    <div
                        class="stats-table-container"
                        v-if="teamAStats.length > 0"
                    >
                        <table class="stats-table">
                            <thead>
                                <tr>
                                    <th>Player</th>
                                    <th>PTS</th>
                                    <th>AST</th>
                                    <th>REB</th>
                                    <th>BLK</th>
                                    <th>STL</th>
                                    <th>FLS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="stat in teamAStats" :key="stat.id">
                                    <td class="player-cell">
                                        <span class="player-number"
                                            >#{{
                                                stat.player?.jersey_number
                                            }}</span
                                        >
                                        {{ stat.player?.name }}
                                    </td>
                                    <td class="highlight">{{ stat.points }}</td>
                                    <td>{{ stat.assists }}</td>
                                    <td>{{ stat.rebounds }}</td>
                                    <td>{{ stat.blocks }}</td>
                                    <td>{{ stat.steals }}</td>
                                    <td>{{ stat.fouls }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="no-stats">No player stats recorded</div>
                </div>

                <!-- Team B Stats -->
                <div class="team-stats-card">
                    <h2 class="card-title">
                        <span class="team-indicator team-b"></span>
                        {{ match.team_b?.name }} Stats
                    </h2>
                    <div
                        class="stats-table-container"
                        v-if="teamBStats.length > 0"
                    >
                        <table class="stats-table">
                            <thead>
                                <tr>
                                    <th>Player</th>
                                    <th>PTS</th>
                                    <th>AST</th>
                                    <th>REB</th>
                                    <th>BLK</th>
                                    <th>STL</th>
                                    <th>FLS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="stat in teamBStats" :key="stat.id">
                                    <td class="player-cell">
                                        <span class="player-number"
                                            >#{{
                                                stat.player?.jersey_number
                                            }}</span
                                        >
                                        {{ stat.player?.name }}
                                    </td>
                                    <td class="highlight">{{ stat.points }}</td>
                                    <td>{{ stat.assists }}</td>
                                    <td>{{ stat.rebounds }}</td>
                                    <td>{{ stat.blocks }}</td>
                                    <td>{{ stat.steals }}</td>
                                    <td>{{ stat.fouls }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="no-stats">No player stats recorded</div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";

const props = defineProps({
    match: {
        type: Object,
        required: true,
    },
    teamAStats: {
        type: Array,
        default: () => [],
    },
    teamBStats: {
        type: Array,
        default: () => [],
    },
    mvp: {
        type: Object,
        default: null,
    },
});

const isTeamAWinner = computed(() => {
    return (
        props.match.status === "finished" &&
        props.match.team_a_score > props.match.team_b_score
    );
});

const isTeamBWinner = computed(() => {
    return (
        props.match.status === "finished" &&
        props.match.team_b_score > props.match.team_a_score
    );
});

const formatDate = (date) => {
    if (!date) return "TBD";
    const d = new Date(date);
    if (isNaN(d.getTime())) return "TBD";
    return d.toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const formatTime = (time) => {
    if (!time) return "";
    // Handle full datetime string (e.g. "2026-02-21T15:30:00.000000Z")
    if (time.includes("T") || time.includes(" ")) {
        const d = new Date(time);
        if (!isNaN(d.getTime())) {
            return d.toLocaleTimeString("en-US", {
                hour: "2-digit",
                minute: "2-digit",
            });
        }
    }
    // Handle plain time string (e.g. "15:30:00")
    const d = new Date(`2000-01-01T${time}`);
    if (isNaN(d.getTime())) return "";
    return d.toLocaleTimeString("en-US", {
        hour: "2-digit",
        minute: "2-digit",
    });
};

const resolvePlayerPhotoUrl = (photoPath) => {
    if (!photoPath || typeof photoPath !== "string") return null;
    if (photoPath.startsWith("http://") || photoPath.startsWith("https://")) {
        return photoPath;
    }
    if (photoPath.startsWith("/")) return photoPath;
    return `/storage/${photoPath}`;
};

const resolveTeamLogoUrl = (logoPath) => {
    if (!logoPath || typeof logoPath !== "string") return null;
    if (logoPath.startsWith("http://") || logoPath.startsWith("https://")) {
        return logoPath;
    }
    if (logoPath.startsWith("/")) return logoPath;
    if (logoPath.startsWith("uploads/")) return `/${logoPath}`;
    return `/storage/${logoPath}`;
};
</script>

<style scoped>
.match-details-page {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.back-link {
    color: rgba(251, 191, 36, 0.9);
    text-decoration: none;
    font-size: 0.875rem;
    transition: color 0.3s ease;
}

.back-link:hover {
    color: rgba(251, 191, 36, 0.95);
}

/* Match Header */
.match-header {
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
    padding: 1.5rem;
}

.teams-display {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}

.team-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    flex: 1;
    min-width: 100px;
}

.team-avatar {
    width: 4rem;
    height: 4rem;
    border-radius: 1rem;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #ffffff;
    font-size: 1.5rem;
}

.team-avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: inherit;
}

.team-avatar-fallback {
    line-height: 1;
}

.team-avatar-alt {
    background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);
}

.team-name {
    font-weight: 600;
    color: var(--cv-text-1);
    font-size: 1rem;
    text-align: center;
}

.team-score {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--cv-muted-2);
}

.team-score.winner {
    color: rgba(251, 191, 36, 0.95);
    text-shadow: 0 0 20px rgba(251, 191, 36, 0.35);
}

.vs-divider {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.vs-text {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--cv-muted-2);
}

.status-badge {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 9999px;
}

.status-upcoming {
    background: rgba(251, 191, 36, 0.18);
    color: #fbbf24;
}

.status-ongoing {
    background: rgba(248, 113, 113, 0.18);
    color: #fca5a5;
}

.status-paused {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
}

.status-finished {
    background: rgba(100, 116, 139, 0.2);
    color: #94a3b8;
}

.match-info {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1.5rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--cv-border-1);
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: var(--cv-muted);
}

.info-icon {
    width: 1rem;
    height: 1rem;
}

.quarter-badge {
    padding: 0.25rem 0.75rem;
    background: rgba(251, 191, 36, 0.18);
    color: rgba(251, 191, 36, 0.95);
    border-radius: 0.375rem;
    font-weight: 600;
}

/* MVP Section */
.mvp-section {
    background: linear-gradient(
        135deg,
        rgba(251, 191, 36, 0.1) 0%,
        rgba(245, 158, 11, 0.08) 100%
    );
    border-radius: 0.75rem;
    border: 1px solid rgba(251, 191, 36, 0.3);
    padding: 1.5rem;
}

.mvp-card {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    position: relative;
}

@media (min-width: 640px) {
    .mvp-card {
        flex-direction: row;
        align-items: center;
    }
}

.mvp-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.25rem;
    font-weight: 700;
    color: #fbbf24;
    position: absolute;
    top: 0;
    right: 0;
}

.mvp-badge svg {
    width: 1.5rem;
    height: 1.5rem;
}

.mvp-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.mvp-photo {
    width: 7rem;
    height: 8rem;
    border-radius: 0.5rem;
    object-fit: cover;
    border: 2px solid rgba(251, 191, 36, 0.5);
}

.mvp-avatar {
    width: 7rem;
    height: 8rem;
    border-radius: 0.5rem;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #1e293b;
    font-size: 1.75rem;
}

.mvp-details {
    display: flex;
    flex-direction: column;
}

.mvp-name {
    font-weight: 600;
    color: var(--cv-text-1);
    font-size: 1.125rem;
}

.mvp-team {
    font-size: 0.875rem;
    color: var(--cv-muted);
}

.mvp-stats {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0.5rem 0.75rem;
    background: var(--cv-surface-3);
    border-radius: 0.5rem;
}

.stat-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--cv-text-1);
}

.stat-label {
    font-size: 0.625rem;
    color: var(--cv-muted);
    font-weight: 600;
    letter-spacing: 0.05em;
}

/* Stats Section */
.stats-section {
    display: grid;
    gap: 1.5rem;
}

@media (min-width: 1024px) {
    .stats-section {
        grid-template-columns: repeat(2, 1fr);
    }
}

.team-stats-card {
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
    padding: 1.5rem;
}

.card-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--cv-text-1);
    margin: 0 0 1rem 0;
}

.team-indicator {
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
}

.team-indicator.team-a {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.team-indicator.team-b {
    background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);
}

.stats-table-container {
    overflow-x: auto;
}

.stats-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 400px;
}

.stats-table th,
.stats-table td {
    padding: 0.75rem 0.5rem;
    text-align: center;
    border-bottom: 1px solid var(--cv-border-1);
}

.stats-table th {
    color: var(--cv-muted);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stats-table td {
    color: var(--cv-text-1);
    font-size: 0.875rem;
}

.player-cell {
    text-align: left;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.player-number {
    font-size: 0.75rem;
    color: var(--cv-muted-2);
    font-weight: 600;
}

.highlight {
    font-weight: 700;
    color: rgba(251, 191, 36, 0.95);
}

.no-stats {
    text-align: center;
    padding: 2rem;
    color: var(--cv-muted-2);
}

/* Mobile Responsive */
@media (max-width: 640px) {
    .match-details-page {
        gap: 1rem;
    }

    .match-header {
        padding: 1rem;
    }

    .teams-display {
        gap: 0.5rem;
    }

    .team-block {
        min-width: 80px;
        gap: 0.375rem;
    }

    .team-avatar {
        width: 2.75rem;
        height: 2.75rem;
        font-size: 1.125rem;
        border-radius: 0.625rem;
    }

    .team-name {
        font-size: 0.8125rem;
        max-width: 80px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .team-score {
        font-size: 1.75rem;
    }

    .vs-divider {
        gap: 0.375rem;
    }

    .vs-text {
        font-size: 0.875rem;
    }

    .status-badge {
        padding: 0.25rem 0.5rem;
        font-size: 0.625rem;
    }

    .match-info {
        gap: 0.75rem;
        margin-top: 1rem;
        padding-top: 1rem;
    }

    .info-item {
        font-size: 0.75rem;
        gap: 0.375rem;
    }

    .info-icon {
        width: 0.875rem;
        height: 0.875rem;
    }

    .quarter-badge {
        padding: 0.1875rem 0.5rem;
        font-size: 0.75rem;
    }

    /* MVP Mobile */
    .mvp-section {
        padding: 1rem;
    }

    .mvp-card {
        gap: 0.75rem;
        align-items: flex-start;
    }

    .mvp-badge {
        font-size: 1rem;
    }

    .mvp-badge svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    .mvp-info {
        gap: 0.5rem;
    }

    .mvp-photo {
        width: 5.5rem;
        height: 6.5rem;
    }

    .mvp-avatar {
        width: 5.5rem;
        height: 6.5rem;
        font-size: 1.25rem;
    }

    .mvp-name {
        font-size: 1rem;
    }

    .mvp-team {
        font-size: 0.75rem;
    }

    .mvp-stats {
        gap: 0.5rem;
        width: 100%;
        justify-content: space-between;
    }

    .stat-item {
        padding: 0.375rem 0.5rem;
        flex: 1;
        min-width: 45px;
    }

    .stat-value {
        font-size: 1rem;
    }

    .stat-label {
        font-size: 0.5rem;
    }

    /* Stats Table Mobile */
    .team-stats-card {
        padding: 0.75rem;
    }

    .card-title {
        font-size: 1rem;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .team-indicator {
        width: 0.625rem;
        height: 0.625rem;
    }

    .stats-table {
        min-width: 320px;
    }

    .stats-table th,
    .stats-table td {
        padding: 0.5rem 0.25rem;
    }

    .stats-table th {
        font-size: 0.625rem;
    }

    .stats-table td {
        font-size: 0.75rem;
    }

    .player-cell {
        gap: 0.25rem;
        white-space: nowrap;
        max-width: 100px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .player-number {
        font-size: 0.625rem;
    }

    .no-stats {
        padding: 1.5rem;
        font-size: 0.875rem;
    }
}

@media (max-width: 380px) {
    .team-avatar {
        width: 2.25rem;
        height: 2.25rem;
        font-size: 1rem;
        border-radius: 0.5rem;
    }

    .team-name {
        font-size: 0.75rem;
        max-width: 65px;
    }

    .team-score {
        font-size: 1.5rem;
    }

    .vs-text {
        font-size: 0.75rem;
    }

    .mvp-stats .stat-item {
        min-width: 40px;
        padding: 0.25rem 0.375rem;
    }

    .stat-value {
        font-size: 0.875rem;
    }

    .stats-table {
        min-width: 280px;
    }

    .player-cell {
        max-width: 70px;
    }
}
</style>
