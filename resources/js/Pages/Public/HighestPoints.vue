<template>
    <Head v-if="!embedded" title="Player Rankings | SBA" />
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
                <div class="header-content">
                    <h1 class="page-title">Player Rankings Leaderboard</h1>
                    <p class="page-subtitle">
                        Weighted by total assists (35%), total points (25%),
                        total rebounds (20%), total steals (12%), and total
                        blocks (8%).
                    </p>
                </div>
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

            <section
                v-if="topPlayer"
                class="featured-top-card"
                aria-label="Top ranked player"
            >
                <div class="featured-head">
                    <span class="featured-pill">TOP 1 PLAYER</span>
                    <span class="featured-rank">
                        Rank #{{ resolveRank(topPlayer, 1) }}
                    </span>
                </div>

                <div class="featured-main">
                    <div class="featured-player">
                        <img
                            v-if="topPlayer.photo"
                            :src="resolvePlayerPhotoUrl(topPlayer.photo)"
                            :alt="topPlayer.name"
                            class="featured-avatar"
                            loading="lazy"
                        />
                        <div
                            v-else
                            class="featured-avatar-fallback"
                            aria-hidden="true"
                        >
                            {{
                                topPlayer.name?.charAt(0)?.toUpperCase() || "P"
                            }}
                        </div>

                        <div class="featured-player-meta">
                            <h2 class="featured-player-name">
                                {{ topPlayer.name }}
                            </h2>
                            <p class="featured-player-sub">
                                {{ topPlayer.team || "N/A" }}
                                <span class="featured-dot">•</span>
                                {{ resolveDivisionName(topPlayer) }}
                                <span class="featured-dot">•</span>
                                Jersey #{{ topPlayer.jersey_number || "-" }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="featured-stats"
                        aria-label="Top player ranking stats"
                    >
                        <div class="featured-stat">
                            <span class="featured-stat-k">Score</span>
                            <span class="featured-stat-v high">
                                {{ topPlayerRankingScore }}
                            </span>
                        </div>
                        <div class="featured-stat">
                            <span class="featured-stat-k">Total AST</span>
                            <span class="featured-stat-v assist">
                                {{ topPlayerAssistsTotal }}
                            </span>
                        </div>
                        <div class="featured-stat">
                            <span class="featured-stat-k">Total PTS</span>
                            <span class="featured-stat-v total">
                                {{ topPlayerPointsTotal }}
                            </span>
                        </div>
                        <div class="featured-stat">
                            <span class="featured-stat-k">Total REB</span>
                            <span class="featured-stat-v rebound">
                                {{ topPlayerReboundsTotal }}
                            </span>
                        </div>
                        <div class="featured-stat">
                            <span class="featured-stat-k">Total STL</span>
                            <span class="featured-stat-v steal">
                                {{ topPlayerStealsTotal }}
                            </span>
                        </div>
                        <div class="featured-stat">
                            <span class="featured-stat-k">Total BLK</span>
                            <span class="featured-stat-v block">
                                {{ topPlayerBlocksTotal }}
                            </span>
                        </div>
                        <div class="featured-stat">
                            <span class="featured-stat-k">Games</span>
                            <span class="featured-stat-v games">
                                {{ topPlayer.games_played }}
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <div
                v-if="remainingPlayers.length > 0"
                class="leaderboard-table-wrapper"
            >
                <table class="leaderboard-table">
                    <thead>
                        <tr>
                            <th>
                                <span class="th-label th-label-full">Rank</span>
                                <span class="th-label th-label-short">#</span>
                            </th>
                            <th>
                                <span class="th-label th-label-full"
                                    >Player</span
                                >
                                <span class="th-label th-label-short"
                                    >Player</span
                                >
                            </th>
                            <th>
                                <span class="th-label th-label-full">Team</span>
                                <span class="th-label th-label-short">TM</span>
                            </th>
                            <th>
                                <span class="th-label th-label-full"
                                    >Jersey #</span
                                >
                                <span class="th-label th-label-short">No</span>
                            </th>
                            <th>
                                <span class="th-label th-label-full"
                                    >Rank Score</span
                                >
                                <span class="th-label th-label-short">SCR</span>
                            </th>
                            <th>
                                <span class="th-label th-label-full"
                                    >Total AST</span
                                >
                                <span class="th-label th-label-short">AST</span>
                            </th>
                            <th>
                                <span class="th-label th-label-full"
                                    >Total PTS</span
                                >
                                <span class="th-label th-label-short">PTS</span>
                            </th>
                            <th>
                                <span class="th-label th-label-full"
                                    >Total REB</span
                                >
                                <span class="th-label th-label-short">REB</span>
                            </th>
                            <th>
                                <span class="th-label th-label-full"
                                    >Total STL</span
                                >
                                <span class="th-label th-label-short">STL</span>
                            </th>
                            <th>
                                <span class="th-label th-label-full"
                                    >Total BLK</span
                                >
                                <span class="th-label th-label-short">BLK</span>
                            </th>
                            <th>
                                <span class="th-label th-label-full"
                                    >Games</span
                                >
                                <span class="th-label th-label-short">G</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(player, index) in remainingPlayers"
                            :key="player.id"
                            :class="{
                                'top-three':
                                    resolveRank(player, index + 2) <= 3,
                            }"
                        >
                            <td>
                                <span
                                    class="rank-badge"
                                    :class="
                                        'rank-' + resolveRank(player, index + 2)
                                    "
                                >
                                    <span
                                        v-if="
                                            resolveRank(player, index + 2) === 1
                                        "
                                        >🥇</span
                                    >
                                    <span
                                        v-else-if="
                                            resolveRank(player, index + 2) === 2
                                        "
                                        >🥈</span
                                    >
                                    <span
                                        v-else-if="
                                            resolveRank(player, index + 2) === 3
                                        "
                                        >🥉</span
                                    >
                                    <span v-else>
                                        {{ resolveRank(player, index + 2) }}
                                    </span>
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
                                    formatScore(player.ranking_score)
                                }}</span>
                            </td>
                            <td class="total-cell">
                                {{ formatTotal(player.total_assists) }}
                            </td>
                            <td class="games-cell">
                                {{ formatTotal(player.total_points) }}
                            </td>
                            <td class="rebounds-cell">
                                {{ formatTotal(player.total_rebounds) }}
                            </td>
                            <td class="steals-cell">
                                {{ formatTotal(player.total_steals) }}
                            </td>
                            <td class="blocks-cell">
                                {{ formatTotal(player.total_blocks) }}
                            </td>
                            <td class="games-played-cell">
                                {{ player.games_played }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-else-if="!topPlayer" class="empty-state">
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
    const players = Array.isArray(props.players) ? props.players : [];

    if (selectedDivision.value === null) {
        return players;
    }
    return players.filter(
        (player) => player.division_id === selectedDivision.value,
    );
});

const topPlayer = computed(() => filteredPlayers.value[0] ?? null);

const remainingPlayers = computed(() => filteredPlayers.value.slice(1));

const resolveRank = (player, fallbackRank) => {
    const rank = Number(player?.rank);
    if (Number.isFinite(rank) && rank > 0) return rank;
    return fallbackRank;
};

const resolveDivisionName = (player) => {
    const divisionId = Number(player?.division_id);
    if (!Number.isFinite(divisionId)) return "All Divisions";

    const divisions = Array.isArray(props.divisions) ? props.divisions : [];
    const division = divisions.find((d) => Number(d?.id) === divisionId);
    return division?.name || "All Divisions";
};

const formatTotal = (value) => {
    const numericValue = Number(value ?? 0);
    if (!Number.isFinite(numericValue)) return "0";
    return Math.round(numericValue).toString();
};

const formatScore = (value) => {
    const numericValue = Number(value ?? 0);
    if (!Number.isFinite(numericValue)) return "0.00";
    return numericValue.toFixed(2);
};

const topPlayerRankingScore = computed(() => {
    return formatScore(topPlayer.value?.ranking_score);
});

const topPlayerAssistsTotal = computed(() => {
    return formatTotal(topPlayer.value?.total_assists);
});

const topPlayerPointsTotal = computed(() => {
    return formatTotal(topPlayer.value?.total_points);
});

const topPlayerReboundsTotal = computed(() => {
    return formatTotal(topPlayer.value?.total_rebounds);
});

const topPlayerStealsTotal = computed(() => {
    return formatTotal(topPlayer.value?.total_steals);
});

const topPlayerBlocksTotal = computed(() => {
    return formatTotal(topPlayer.value?.total_blocks);
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
    padding: 0;
}

.header-content {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
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

.page-subtitle {
    margin: 0;
    font-size: 0.9rem;
    color: var(--cv-muted);
}

.division-tabs {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    padding: 0;
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

.featured-top-card {
    border-radius: 1rem;
    border: 1px solid rgba(251, 146, 60, 0.35);
    background:
        linear-gradient(
            135deg,
            rgba(249, 115, 22, 0.2) 0%,
            rgba(251, 146, 60, 0.12) 55%,
            rgba(2, 6, 23, 0.2) 100%
        ),
        var(--cv-surface-1);
    padding: 1rem;
}

.featured-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 0.9rem;
}

.featured-pill {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 0.32rem 0.65rem;
    font-size: 0.7rem;
    font-weight: 800;
    letter-spacing: 0.11em;
    text-transform: uppercase;
    color: #7c2d12;
    background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
}

.featured-rank {
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--cv-text-2);
}

.featured-main {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}

.featured-player {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    min-width: 0;
}

.featured-avatar,
.featured-avatar-fallback {
    width: 4rem;
    height: 4rem;
    border-radius: 1rem;
    flex-shrink: 0;
}

.featured-avatar {
    object-fit: cover;
    border: 1px solid rgba(251, 146, 60, 0.4);
    background: var(--cv-surface-3);
}

.featured-avatar-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 800;
    color: #7c2d12;
    border: 1px solid rgba(251, 146, 60, 0.4);
    background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
}

.featured-player-meta {
    min-width: 0;
}

.featured-player-name {
    margin: 0;
    font-size: 1.35rem;
    line-height: 1.15;
    color: var(--cv-text-1);
}

.featured-player-sub {
    margin: 0.32rem 0 0;
    color: var(--cv-text-2);
    font-size: 0.9rem;
}

.featured-dot {
    margin: 0 0.35rem;
    color: var(--cv-muted);
}

.featured-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 0.45rem 1rem;
    width: 100%;
    max-width: none;
}

.featured-stat {
    display: flex;
    align-items: baseline;
    gap: 0.35rem;
}

.featured-stat-k {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--cv-muted);
}

.featured-stat-k::after {
    content: ":";
    margin-left: 0.1rem;
}

.featured-stat-v {
    font-size: 1rem;
    font-weight: 800;
    color: var(--cv-text-1);
}

.featured-stat-v.high {
    color: #fb923c;
}

.featured-stat-v.total {
    color: rgba(251, 191, 36, 0.95);
}

.featured-stat-v.assist {
    color: #22c55e;
}

.featured-stat-v.rebound {
    color: #38bdf8;
}

.featured-stat-v.steal {
    color: #a78bfa;
}

.featured-stat-v.block {
    color: #f97316;
}

.featured-stat-v.games {
    color: var(--cv-text-1);
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
    min-width: 980px;
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

.th-label-short {
    display: none;
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
    color: #22c55e;
    font-weight: 700;
    text-align: center;
}

.games-cell {
    color: rgba(251, 191, 36, 0.95);
    font-weight: 600;
    text-align: center;
}

.rebounds-cell,
.steals-cell,
.blocks-cell {
    font-weight: 600;
    text-align: center;
}

.rebounds-cell {
    color: #38bdf8;
}

.steals-cell {
    color: #a78bfa;
}

.blocks-cell {
    color: #f97316;
}

.games-played-cell {
    color: var(--cv-muted);
    font-weight: 600;
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
@media (max-width: 768px) {
    .leaderboard-table th,
    .leaderboard-table td {
        padding: 0.75rem;
    }
}

@media (max-width: 640px) {
    .page-header {
        padding: 1rem;
        gap: 0.75rem;
    }

    .leaderboard-table-wrapper {
        overflow-x: hidden;
        border: none;
        border-radius: 0;
        background: transparent;
    }

    .leaderboard-table {
        min-width: 0;
        width: 100%;
        table-layout: fixed;
    }

    .leaderboard-table th,
    .leaderboard-table td {
        padding: 0.3rem 0.16rem;
        font-size: 0.56rem;
        letter-spacing: 0.02em;
        line-height: 1.15;
        white-space: nowrap;
    }

    .leaderboard-table th:nth-child(1),
    .leaderboard-table td:nth-child(1) {
        width: 6%;
        text-align: center;
    }

    .leaderboard-table th:nth-child(2),
    .leaderboard-table td:nth-child(2) {
        width: 22%;
    }

    .leaderboard-table th:nth-child(3),
    .leaderboard-table td:nth-child(3) {
        width: 10%;
    }

    .leaderboard-table th:nth-child(4),
    .leaderboard-table td:nth-child(4) {
        width: 6%;
        text-align: center;
    }

    .leaderboard-table th:nth-child(5),
    .leaderboard-table td:nth-child(5) {
        width: 8%;
        text-align: center;
    }

    .leaderboard-table th:nth-child(6),
    .leaderboard-table td:nth-child(6),
    .leaderboard-table th:nth-child(7),
    .leaderboard-table td:nth-child(7),
    .leaderboard-table th:nth-child(8),
    .leaderboard-table td:nth-child(8),
    .leaderboard-table th:nth-child(9),
    .leaderboard-table td:nth-child(9),
    .leaderboard-table th:nth-child(10),
    .leaderboard-table td:nth-child(10),
    .leaderboard-table th:nth-child(11),
    .leaderboard-table td:nth-child(11) {
        width: 8%;
        text-align: center;
    }

    .th-label-full {
        display: none;
    }

    .th-label-short {
        display: inline;
    }

    .header-icon svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    .page-title {
        font-size: 1.25rem;
    }

    .page-subtitle {
        font-size: 0.75rem;
    }

    .division-tabs {
        gap: 0.375rem;
    }

    .division-tab {
        padding: 0.4rem 0.75rem;
        font-size: 0.8125rem;
    }

    .player-info {
        gap: 0.18rem;
    }

    .player-avatar {
        display: inline-flex;
        width: 0.78rem;
        height: 0.78rem;
        border-width: 1px;
        flex-shrink: 0;
    }

    .avatar-placeholder {
        font-size: 0.42rem;
    }

    .player-name {
        display: block;
        font-size: 0.56rem;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .team-cell {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 0.56rem;
    }

    .jersey-badge {
        min-width: 0;
        padding: 0;
        border-radius: 0;
        font-size: inherit;
        background: transparent;
        color: inherit;
    }

    .highest-value {
        padding: 0;
        border-radius: 0;
        font-size: inherit;
        background: transparent;
        color: inherit;
    }

    .total-cell,
    .games-cell,
    .rebounds-cell,
    .steals-cell,
    .blocks-cell,
    .games-played-cell {
        font-size: 0.56rem;
    }

    .rank-badge {
        width: auto;
        height: auto;
        border-radius: 0;
        font-size: inherit;
        padding: 0;
        min-width: 0;
        background: transparent;
        color: inherit;
    }

    .rank-1,
    .rank-2,
    .rank-3 {
        background: transparent;
        color: inherit;
    }

    .featured-top-card {
        padding: 0.85rem;
    }

    .featured-head {
        margin-bottom: 0.75rem;
    }

    .featured-avatar,
    .featured-avatar-fallback {
        width: 4.25rem;
        height: 4.25rem;
        border-radius: 1rem;
    }

    .featured-player-name {
        font-size: 1.08rem;
    }

    .featured-player-sub {
        font-size: 0.82rem;
    }

    .featured-stats {
        gap: 0.35rem 0.75rem;
    }

    .featured-stat-v {
        font-size: 0.95rem;
    }
}

@media (max-width: 420px) {
    .leaderboard-table th,
    .leaderboard-table td {
        padding: 0.24rem 0.12rem;
        font-size: 0.5rem;
    }

    .rank-badge {
        font-size: 0.5rem;
    }

    .player-name {
        font-size: 0.5rem;
    }

    .player-avatar {
        width: 0.66rem;
        height: 0.66rem;
    }

    .avatar-placeholder {
        font-size: 0.36rem;
    }

    .team-cell {
        font-size: 0.52rem;
    }

    .highest-value {
        font-size: 0.5rem;
    }

    .total-cell,
    .games-cell,
    .rebounds-cell,
    .steals-cell,
    .blocks-cell,
    .games-played-cell {
        font-size: 0.5rem;
    }

    .featured-player-sub {
        font-size: 0.75rem;
    }

    .featured-avatar,
    .featured-avatar-fallback {
        width: 3.75rem;
        height: 3.75rem;
    }
}
</style>
