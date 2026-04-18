<template>
    <Head v-if="!embedded" title="Individual Stats | SBA" />
    <component :is="embedded ? 'div' : PublicLayout">
        <div class="individual-stats-page">
            <div class="page-header">
                <div class="header-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                        ></path>
                    </svg>
                </div>
                <h1 class="page-title">Individual Player Statistics</h1>
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

            <!-- Filters + Search -->
            <div class="filters-bar">
                <div class="filters-row">
                    <input
                        v-model="searchQuery"
                        type="text"
                        class="search-input"
                        placeholder="Search player, team, jersey, position..."
                    />

                    <div class="filters-selects">
                        <select v-model="selectedTeam" class="filter-select">
                            <option value="">All Teams</option>
                            <option
                                v-for="team in availableTeams"
                                :key="team"
                                :value="team"
                            >
                                {{ team }}
                            </option>
                        </select>

                        <select
                            v-model="selectedPosition"
                            class="filter-select"
                        >
                            <option value="">All Positions</option>
                            <option
                                v-for="position in availablePositions"
                                :key="position"
                                :value="position"
                            >
                                {{ position }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div v-if="filteredPlayers.length > 0" class="stats-table-wrapper">
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>Player</th>
                            <th>Team</th>
                            <th>Jersey #</th>
                            <th>Position</th>
                            <th>Games</th>
                            <th>Points</th>
                            <th>Assists</th>
                            <th>Rebounds</th>
                            <th>Best</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template
                            v-for="player in filteredPlayers"
                            :key="player.id"
                        >
                            <tr>
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
                                            <span
                                                v-else
                                                class="avatar-placeholder"
                                            >
                                                {{
                                                    player.name
                                                        .charAt(0)
                                                        .toUpperCase()
                                                }}
                                            </span>
                                        </div>
                                        <div class="player-text">
                                            <span class="player-name">{{
                                                player.name
                                            }}</span>
                                            <Link
                                                :href="
                                                    route(
                                                        'player-stats',
                                                        player.id,
                                                    )
                                                "
                                                class="stats-link"
                                            >
                                                Statistics
                                            </Link>
                                        </div>
                                    </div>
                                </td>
                                <td class="team-cell">{{ player.team }}</td>
                                <td class="jersey-cell">
                                    <span class="jersey-badge">{{
                                        player.jersey_number || "-"
                                    }}</span>
                                </td>
                                <td class="position-cell">
                                    <span class="position-badge">{{
                                        player.position || "-"
                                    }}</span>
                                </td>
                                <td class="games-cell">
                                    {{ player.games_played }}
                                </td>
                                <td class="points-cell">
                                    {{ player.total_points }}
                                </td>
                                <td class="assists-cell">
                                    {{ player.total_assists }}
                                </td>
                                <td class="rebounds-cell">
                                    {{ player.total_rebounds }}
                                </td>
                                <td class="best-cell">
                                    {{ player.highest_points }}
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div v-if="filteredPlayers.length > 0" class="stats-cards">
                <div
                    v-for="player in filteredPlayers"
                    :key="`card-${player.id}`"
                    class="stats-card"
                >
                    <div class="stats-card-header">
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
                        <Link
                            :href="route('player-stats', player.id)"
                            class="stats-link"
                        >
                            Statistics
                        </Link>
                    </div>

                    <div class="stats-card-grid">
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
                            <span class="stat-label">Position</span>
                            <span class="position-badge">{{
                                player.position || "-"
                            }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Games</span>
                            <span class="games-cell">{{
                                player.games_played
                            }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Points</span>
                            <span class="points-cell">{{
                                player.total_points
                            }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Assists</span>
                            <span class="assists-cell">{{
                                player.total_assists
                            }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Rebounds</span>
                            <span class="rebounds-cell">{{
                                player.total_rebounds
                            }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Best</span>
                            <span class="best-cell">{{
                                player.highest_points
                            }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="empty-state">
                <div class="empty-icon">👤</div>
                <p v-if="hasActiveFilters">
                    No players match your search/filters.
                </p>
                <p v-else>
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
import { Head, Link } from "@inertiajs/vue3";
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
const searchQuery = ref("");
const selectedTeam = ref("");
const selectedPosition = ref("");

const availableTeams = computed(() => {
    const teams = new Set(
        (props.players || []).map((player) => player?.team).filter(Boolean),
    );
    return Array.from(teams).sort((a, b) => String(a).localeCompare(String(b)));
});

const availablePositions = computed(() => {
    const positions = new Set(
        (props.players || []).map((player) => player?.position).filter(Boolean),
    );
    return Array.from(positions).sort((a, b) =>
        String(a).localeCompare(String(b)),
    );
});

const hasActiveFilters = computed(() => {
    return (
        selectedDivision.value !== null ||
        searchQuery.value.trim().length > 0 ||
        selectedTeam.value.length > 0 ||
        selectedPosition.value.length > 0
    );
});

const filteredPlayers = computed(() => {
    let players = props.players || [];

    if (selectedDivision.value !== null) {
        players = players.filter(
            (player) => player.division_id === selectedDivision.value,
        );
    }

    if (selectedTeam.value) {
        players = players.filter(
            (player) => player.team === selectedTeam.value,
        );
    }

    if (selectedPosition.value) {
        players = players.filter(
            (player) => player.position === selectedPosition.value,
        );
    }

    const q = searchQuery.value.trim().toLowerCase();
    if (q.length > 0) {
        players = players.filter((player) => {
            const name = String(player?.name ?? "").toLowerCase();
            const team = String(player?.team ?? "").toLowerCase();
            const jersey = String(player?.jersey_number ?? "").toLowerCase();
            const position = String(player?.position ?? "").toLowerCase();

            return (
                name.includes(q) ||
                team.includes(q) ||
                jersey.includes(q) ||
                position.includes(q)
            );
        });
    }

    return players;
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
.individual-stats-page {
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
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-color: rgba(251, 191, 36, 0.55);
    color: #ffffff;
}

.filters-bar {
    padding: 1rem;
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
}

.filters-row {
    display: flex;
    gap: 0.75rem;
    flex-direction: column;
    align-items: center;
}

.filters-selects {
    width: 100%;
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.75rem;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem;
    background: var(--cv-surface-3);
    border: 1px solid var(--cv-border-1);
    border-radius: 0.5rem;
    color: var(--cv-text-2);
    font-size: 0.875rem;
}

.search-input::placeholder {
    color: var(--cv-muted-2);
}

.filter-select {
    width: 100%;
    min-width: 0;
    padding: 0.75rem 1rem;
    background: var(--cv-surface-3);
    border: 1px solid var(--cv-border-1);
    border-radius: 0.5rem;
    color: var(--cv-text-2);
    font-size: 0.875rem;
}

.search-input:focus,
.filter-select:focus {
    outline: none;
    border-color: rgba(251, 191, 36, 0.55);
}

.stats-table-wrapper {
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
    overflow-x: auto;
}

.stats-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

.stats-table thead {
    background: var(--cv-surface-3);
}

.stats-table th {
    padding: 1rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--cv-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stats-table tbody tr {
    border-top: 1px solid var(--cv-border-1);
    transition: background 0.3s ease;
}

.stats-table tbody tr:hover {
    background: var(--cv-surface-2);
}

.stats-table td {
    padding: 1rem;
    color: var(--cv-text-2);
}

.player-cell {
    padding: 0.75rem 1rem;
}

.player-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.player-text {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
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
    border: 2px solid rgba(34, 197, 94, 0.3);
}

.avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    font-size: 1rem;
    font-weight: 600;
    color: #22c55e;
}

.player-name {
    font-weight: 600;
    color: var(--cv-text-1);
}

.stats-link {
    background: transparent;
    border: none;
    padding: 0;
    text-align: left;
    color: #22c55e;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    text-decoration: underline;
}

.stats-link:hover {
    color: #4ade80;
}

.stats-cards {
    display: none;
    gap: 1rem;
}

.stats-card {
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
    padding: 1rem;
}

.stats-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1rem;
}

.stats-card-grid {
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

.position-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.25rem 0.5rem;
    background: rgba(245, 158, 11, 0.18);
    color: rgba(251, 191, 36, 0.95);
    border-radius: 0.375rem;
    font-weight: 600;
    font-size: 0.75rem;
}

.games-cell {
    text-align: center;
}

.points-cell {
    color: rgba(251, 191, 36, 0.95);
    font-weight: 700;
}

.assists-cell {
    color: #4ade80;
    text-align: center;
}

.rebounds-cell {
    color: #fb923c;
    text-align: center;
}

.best-cell {
    color: rgba(251, 191, 36, 0.85);
    font-weight: 700;
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

    .filters-bar {
        padding: 0.75rem;
    }

    .filters-row {
        align-items: stretch;
    }

    .filters-selects {
        grid-template-columns: 1fr;
    }

    .stats-card {
        padding: 0.9rem;
    }

    .stat-item {
        padding: 0.65rem;
        border-radius: 0.65rem;
    }

    .empty-state {
        padding: 2.5rem 1.25rem;
    }
}

@media (max-width: 768px) {
    .stats-table-wrapper {
        display: none;
    }

    .stats-cards {
        display: grid;
    }
}
</style>
