<template>
    <Head title="Match Details - Committee | SBA" />
    <CommitteeLayout>
        <div class="match-page">
            <!-- Header -->
            <div class="header">
                <Link
                    :href="route('committee.matches.index')"
                    class="back-link"
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
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        ></path>
                    </svg>
                    Back to Matches
                </Link>
                <h1 class="page-title">Match Details</h1>
            </div>

            <!-- Match Info Card -->
            <div class="glass-card match-card">
                <div class="match-header">
                    <div class="team team-a">
                        <span class="team-name">{{ match.team_a?.name }}</span>
                        <span class="team-score">{{
                            match.team_a_score ?? "-"
                        }}</span>
                    </div>
                    <div class="vs-badge">VS</div>
                    <div class="team team-b">
                        <span class="team-score">{{
                            match.team_b_score ?? "-"
                        }}</span>
                        <span class="team-name">{{ match.team_b?.name }}</span>
                    </div>
                </div>

                <div class="match-meta">
                    <div class="meta-item">
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
                        <span>{{ formatDate(match.match_date) }}</span>
                    </div>
                    <div class="meta-item" v-if="match.venue">
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
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                            ></path>
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                            ></path>
                        </svg>
                        <span>{{ match.venue }}</span>
                    </div>
                    <span
                        :class="['status-badge', getStatusClass(match.status)]"
                    >
                        {{ match.status }}
                    </span>
                </div>

                <div class="match-actions">
                    <Link
                        :href="route('committee.matches.edit', match.id)"
                        class="btn btn-primary"
                    >
                        Update Score
                    </Link>
                    <Link
                        :href="route('committee.player-stats.index', match.id)"
                        class="btn btn-secondary"
                    >
                        Player Stats
                    </Link>
                </div>
            </div>

            <!-- Player Stats -->
            <div
                class="glass-card"
                v-if="match.player_stats && match.player_stats.length > 0"
            >
                <div class="card-header">
                    <h2 class="card-title">Player Statistics</h2>
                </div>
                <div class="stats-table-wrapper">
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>Player</th>
                                <th>Team</th>
                                <th>PTS</th>
                                <th>REB</th>
                                <th>AST</th>
                                <th>STL</th>
                                <th>BLK</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="stat in match.player_stats"
                                :key="stat.id"
                            >
                                <td>{{ stat.player?.name }}</td>
                                <td>{{ stat.player?.team?.name }}</td>
                                <td>{{ stat.points }}</td>
                                <td>{{ stat.rebounds }}</td>
                                <td>{{ stat.assists }}</td>
                                <td>{{ stat.steals }}</td>
                                <td>{{ stat.blocks }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </CommitteeLayout>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import CommitteeLayout from "@/Layouts/CommitteeLayout.vue";

defineProps({
    match: {
        type: Object,
        required: true,
    },
});

const formatDate = (date) => {
    if (!date) return "TBD";
    return new Date(date).toLocaleDateString("en-US", {
        weekday: "long",
        month: "long",
        day: "numeric",
        year: "numeric",
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
.match-page {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.header {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.back-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #9ca3af;
    text-decoration: none;
    font-size: 0.875rem;
    transition: color 0.3s ease;
}

.back-link:hover {
    color: #a855f7;
}

.page-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
}

.icon {
    width: 1.25rem;
    height: 1.25rem;
}

.glass-card {
    background: rgba(30, 41, 59, 0.5);
    backdrop-filter: blur(4px);
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
}

.match-card {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.match-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 2rem;
}

.team {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.team-a {
    flex-direction: row;
}

.team-b {
    flex-direction: row-reverse;
}

.team-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: #ffffff;
}

.team-score {
    font-size: 2.5rem;
    font-weight: 700;
    color: #a855f7;
}

.vs-badge {
    padding: 0.5rem 1rem;
    background: rgba(168, 85, 247, 0.2);
    border: 1px solid rgba(168, 85, 247, 0.3);
    border-radius: 0.5rem;
    color: #c084fc;
    font-weight: 600;
    font-size: 0.875rem;
}

.match-meta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #9ca3af;
    font-size: 0.875rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 0.5rem;
    border: 1px solid;
}

.status-upcoming {
    background: rgba(59, 130, 246, 0.2);
    color: #60a5fa;
    border-color: rgba(59, 130, 246, 0.3);
}

.status-ongoing {
    background: rgba(34, 197, 94, 0.2);
    color: #4ade80;
    border-color: rgba(34, 197, 94, 0.3);
}

.status-finished {
    background: rgba(100, 116, 139, 0.2);
    color: #94a3b8;
    border-color: rgba(100, 116, 139, 0.3);
}

.status-default {
    background: rgba(100, 116, 139, 0.2);
    color: #94a3b8;
    border-color: rgba(100, 116, 139, 0.3);
}

.match-actions {
    display: flex;
    justify-content: center;
    gap: 1rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
    color: #ffffff;
}

.btn-primary:hover {
    box-shadow: 0 5px 15px rgba(168, 85, 247, 0.4);
}

.btn-secondary {
    background: rgba(51, 65, 85, 0.5);
    border: 1px solid rgba(71, 85, 105, 0.5);
    color: #d1d5db;
}

.btn-secondary:hover {
    border-color: rgba(168, 85, 247, 0.3);
    color: #ffffff;
}

.card-header {
    margin-bottom: 1rem;
}

.card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #ffffff;
    margin: 0;
}

.stats-table-wrapper {
    overflow-x: auto;
}

.stats-table {
    width: 100%;
    border-collapse: collapse;
}

.stats-table th,
.stats-table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid rgba(51, 65, 85, 0.5);
}

.stats-table th {
    color: #9ca3af;
    font-weight: 500;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stats-table td {
    color: #d1d5db;
}

.stats-table tbody tr:hover {
    background: rgba(168, 85, 247, 0.05);
}
</style>
