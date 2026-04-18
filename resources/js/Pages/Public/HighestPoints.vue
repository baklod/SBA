<template>
    <Head v-if="!embedded" title="Highest Points | SBA" />
    <component :is="embedded ? 'div' : PublicLayout">
        <div class="highest-points-page">
            <div class="page-header">
                <div class="header-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                        ></path>
                    </svg>
                </div>
                <h1 class="page-title">Highest Points Leaderboard</h1>
            </div>

            <!-- Division Tabs -->
            <div v-if="divisions.length > 0" class="division-tabs">
                <button
                    class="division-tab"
                    :class="{ active: selectedDivision === null }"
                    @click="selectedDivision = null"
                >
                    All Divisions
                </button>
                <button
                    v-for="division in divisions"
                    :key="division.id"
                    class="division-tab"
                    :class="{ active: selectedDivision === division.id }"
                    @click="selectedDivision = division.id"
                >
                    {{ division.name }}
                </button>
            </div>

            <div
                v-if="filteredPlayers.length > 0"
                class="leaderboard-table-wrapper"
            >
                <table class="leaderboard-table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Player</th>
                            <th>Team</th>
                            <th>Jersey #</th>
                            <th>Highest Pts</th>
                            <th>Total Pts</th>
                            <th>Games</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(player, index) in filteredPlayers"
                            :key="player.id"
                            :class="{ 'top-three': index < 3 }"
                        >
                            <td>
                                <span
                                    class="rank-badge"
                                    :class="'rank-' + (index + 1)"
                                >
                                    <span v-if="index === 0">🥇</span>
                                    <span v-else-if="index === 1">🥈</span>
                                    <span v-else-if="index === 2">🥉</span>
                                    <span v-else>{{ index + 1 }}</span>
                                </span>
                            </td>
                            <td class="player-cell">
                                <div class="player-info">
                                    <div class="player-avatar">
                                        <img
                                            v-if="player.photo"
                                            :src="
                                                resolvePlayerPhotoUrl(
                                                    player.photo,
                                                )
                                            "
                                            :alt="player.name"
                                            class="avatar-image"
                                        />
                                        <span v-else class="avatar-placeholder">
                                            {{
                                                player.name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </span>
                                    </div>
                                    <span class="player-name">{{
                                        player.name
                                    }}</span>
                                </div>
                            </td>
                            <td class="team-cell">{{ player.team }}</td>
                            <td class="jersey-cell">
                                <span class="jersey-badge">{{
                                    player.jersey_number || "-"
                                }}</span>
                            </td>
                            <td class="highest-cell">
                                <span class="highest-value">{{
                                    player.highest_points
                                }}</span>
                            </td>
                            <td class="total-cell">
                                {{ player.total_points }}
                            </td>
                            <td class="games-cell">
                                {{ player.games_played }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="filteredPlayers.length > 0" class="leaderboard-cards">
                <div
                    v-for="(player, index) in filteredPlayers"
                    :key="`card-${player.id}`"
                    class="leaderboard-card"
                    :class="{ 'top-three-card': index < 3 }"
                >
                    <div class="leaderboard-card-header">
                        <span class="rank-badge" :class="'rank-' + (index + 1)">
                            <span v-if="index === 0">🥇</span>
                            <span v-else-if="index === 1">🥈</span>
                            <span v-else-if="index === 2">🥉</span>
                            <span v-else>{{ index + 1 }}</span>
                        </span>

                        <div class="player-info">
                            <div class="player-avatar">
                                <img
                                    v-if="player.photo"
                                    :src="resolvePlayerPhotoUrl(player.photo)"
                                    :alt="player.name"
                                    class="avatar-image"
                                    loading="lazy"
                                />
                                <span v-else class="avatar-placeholder">
                                    {{ player.name.charAt(0).toUpperCase() }}
                                </span>
                            </div>
                            <span class="player-name">{{ player.name }}</span>
                        </div>
                    </div>

                    <div class="leaderboard-card-stats">
                        <div class="stat-item">
                            <span class="stat-label">Team</span>
                            <span class="team-cell">{{ player.team }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Jersey #</span>
                            <span class="jersey-badge">{{
                                player.jersey_number || "-"
                            }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Highest Pts</span>
                            <span class="highest-value">{{
                                player.highest_points
                            }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Total Pts</span>
                            <span class="total-cell">{{
                                player.total_points
                            }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Games</span>
                            <span class="games-cell">{{
                                player.games_played
                            }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="empty-state">
                <div class="empty-icon">🏅</div>
                <p>
                    No player statistics available{{
                        selectedDivision ? " in this division" : ""
                    }}
                    yet.
                </p>
            </div>
        </div>
    </component>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import PublicLayout from "@/Layouts/PublicLayout.vue";

const props = defineProps({
    players: Array,
    divisions: Array,
    embedded: {
        type: Boolean,
        default: false,
    },
});

const selectedDivision = ref(null);

const filteredPlayers = computed(() => {
    if (selectedDivision.value === null) {
        return props.players;
    }
    return props.players.filter(
        (player) => player.division_id === selectedDivision.value,
    );
});

const resolvePlayerPhotoUrl = (photoPath) => {
    if (!photoPath || typeof photoPath !== "string") return null;
    if (photoPath.startsWith("http://") || photoPath.startsWith("https://")) {
        return photoPath;
    }
    if (photoPath.startsWith("/")) return photoPath;
    return `/storage/${photoPath}`;
};
</script>

<style scoped>
.highest-points-page {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.page-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
}

.header-icon {
    display: flex;
    align-items: center;
    justify-content: center;
}

.header-icon svg {
    width: 1.5rem;
    height: 1.5rem;
    color: var(--cv-text-1);
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--cv-text-1);
    margin: 0;
}

.division-tabs {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    padding: 1rem;
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
}

.division-tab {
    padding: 0.5rem 1rem;
    background: var(--cv-surface-3);
    border: 1px solid var(--cv-border-1);
    border-radius: 0.5rem;
    color: var(--cv-muted);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.division-tab:hover {
    background: var(--cv-surface-2);
    color: var(--cv-text-2);
}

.division-tab.active {
    background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
    border-color: #f97316;
    color: #ffffff;
}

.leaderboard-table-wrapper {
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
    overflow-x: auto;
}

.leaderboard-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 700px;
}

.leaderboard-table thead {
    background: linear-gradient(
        135deg,
        rgba(249, 115, 22, 0.2) 0%,
        rgba(251, 146, 60, 0.2) 100%
    );
}

.leaderboard-table th {
    padding: 1rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 600;
    color: #fb923c;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.leaderboard-table tbody tr {
    border-top: 1px solid var(--cv-border-1);
    transition: background 0.3s ease;
}

.leaderboard-table tbody tr:hover {
    background: var(--cv-surface-2);
}

.leaderboard-table tbody tr.top-three {
    background: rgba(251, 191, 36, 0.05);
}

.leaderboard-table td {
    padding: 1rem;
    color: var(--cv-text-2);
}

.rank-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    font-weight: 700;
    font-size: 1rem;
    background: var(--cv-surface-3);
    color: var(--cv-muted);
}

.rank-1 {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: #78350f;
}

.rank-2 {
    background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
    color: #1f2937;
}

.rank-3 {
    background: linear-gradient(135deg, #cd7f32 0%, #a0522d 100%);
    color: #ffffff;
}

.player-cell {
    padding: 0.75rem 1rem;
}

.player-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.player-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    background: var(--cv-surface-3);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid rgba(251, 191, 36, 0.35);
}

.avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    font-size: 1rem;
    font-weight: 600;
    color: rgba(251, 191, 36, 0.95);
}

.player-name {
    font-weight: 600;
    color: var(--cv-text-1);
    font-size: 1rem;
}

.team-cell {
    color: var(--cv-muted);
}

.jersey-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2rem;
    padding: 0.25rem 0.5rem;
    background: rgba(245, 158, 11, 0.18);
    color: rgba(251, 191, 36, 0.95);
    border-radius: 0.375rem;
    font-weight: 600;
    font-size: 0.875rem;
}

.highest-cell {
    text-align: center;
}

.highest-value {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: linear-gradient(
        135deg,
        rgba(249, 115, 22, 0.2) 0%,
        rgba(251, 146, 60, 0.2) 100%
    );
    color: #fb923c;
    font-size: 1.25rem;
    font-weight: 700;
    border-radius: 0.5rem;
}

.total-cell {
    color: rgba(251, 191, 36, 0.95);
    font-weight: 600;
}

.games-cell {
    color: var(--cv-muted);
    text-align: center;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.empty-state p {
    color: var(--cv-muted-2);
    margin: 0;
}

.leaderboard-cards {
    display: none;
    gap: 1rem;
}

.leaderboard-card {
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
    padding: 1rem;
}

.leaderboard-card.top-three-card {
    background: rgba(251, 191, 36, 0.05);
}

.leaderboard-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.leaderboard-card-stats {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.75rem;
}

.stat-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.75rem;
    border-radius: 0.75rem;
    background: var(--cv-surface-3);
    border: 1px solid var(--cv-border-1);
}

.stat-item .stat-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--cv-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

@media (max-width: 640px) {
    .page-header {
        padding: 1rem;
        gap: 0.75rem;
    }

    .header-icon svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    .page-title {
        font-size: 1.25rem;
    }

    .division-tabs {
        padding: 0.75rem;
        gap: 0.375rem;
    }

    .division-tab {
        padding: 0.4rem 0.75rem;
        font-size: 0.8125rem;
    }

    .leaderboard-card-stats {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .leaderboard-table-wrapper {
        display: none;
    }

    .leaderboard-cards {
        display: grid;
    }
}
</style>
