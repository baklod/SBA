<template>
    <Head :title="`${player?.name || 'Player'} | Player Stats | SBA`" />
    <PublicLayout>
        <div class="player-stats-page">
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
                <div class="header-text">
                    <h1 class="page-title">Player Statistics</h1>
                    <p v-if="player?.team" class="page-subtitle">
                        {{ player.team }}
                        <span v-if="player?.division"
                            >• {{ player.division }}</span
                        >
                    </p>
                </div>
            </div>

            <div class="player-profile">
                <div class="profile-top">
                    <div class="profile-left">
                        <div class="profile-photo">
                            <img
                                v-if="player?.photo"
                                :src="resolvePlayerPhotoUrl(player.photo)"
                                :alt="player?.name || 'Player photo'"
                                loading="lazy"
                            />
                            <div
                                v-else
                                class="photo-placeholder"
                                aria-hidden="true"
                            >
                                {{
                                    (player?.name || "P")
                                        .charAt(0)
                                        .toUpperCase()
                                }}
                            </div>
                        </div>
                        <div class="profile-name">{{ player?.name }}</div>
                    </div>

                    <div class="profile-right">
                        <div class="radar-wrapper">
                            <svg
                                class="radar"
                                viewBox="0 0 100 100"
                                aria-hidden="true"
                            >
                                <polygon
                                    class="radar-grid"
                                    :points="radarGridPoints(40)"
                                />
                                <polygon
                                    class="radar-grid"
                                    :points="radarGridPoints(30)"
                                />
                                <polygon
                                    class="radar-grid"
                                    :points="radarGridPoints(20)"
                                />
                                <polygon
                                    class="radar-grid"
                                    :points="radarGridPoints(10)"
                                />
                                <line
                                    v-for="(point, i) in radarAxisEndpoints"
                                    :key="i"
                                    class="radar-axis"
                                    x1="50"
                                    y1="50"
                                    :x2="point.x"
                                    :y2="point.y"
                                />
                                <polygon
                                    class="radar-data"
                                    :points="radarDataPoints(player)"
                                />

                                <text
                                    v-for="(label, i) in radarAxisLabels"
                                    :key="label"
                                    class="radar-label"
                                    :x="radarLabelPositions[i].x"
                                    :y="radarLabelPositions[i].y"
                                    :text-anchor="radarLabelPositions[i].anchor"
                                    dominant-baseline="middle"
                                >
                                    {{ label }}
                                </text>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="profile-meta">
                    <div class="stat-item">
                        <span class="stat-label">Team</span>
                        <span class="team-cell">{{ player?.team || "-" }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Jersey #</span>
                        <span class="jersey-badge">{{
                            player?.jersey_number || "-"
                        }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Position</span>
                        <span class="position-badge">{{
                            player?.position || "-"
                        }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Games</span>
                        <span class="games-cell">{{
                            player?.games_played ?? 0
                        }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">PPG</span>
                        <span class="points-cell">{{
                            formatOneDecimal(
                                radarStatValue(player, "points_per_game"),
                            )
                        }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">APG</span>
                        <span class="assists-cell">{{
                            formatOneDecimal(
                                radarStatValue(player, "assists_per_game"),
                            )
                        }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">RPG</span>
                        <span class="rebounds-cell">{{
                            formatOneDecimal(
                                radarStatValue(player, "rebounds_per_game"),
                            )
                        }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">SPG</span>
                        <span class="steals-cell">{{
                            formatOneDecimal(
                                radarStatValue(player, "steals_per_game"),
                            )
                        }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">BPG</span>
                        <span class="blocks-cell">{{
                            formatOneDecimal(
                                radarStatValue(player, "blocks_per_game"),
                            )
                        }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Best</span>
                        <span class="best-cell">{{
                            player?.highest_points ?? 0
                        }}</span>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import { computed } from "vue";
import PublicLayout from "@/Layouts/PublicLayout.vue";

const props = defineProps({
    player: Object,
    radarMaxes: Object,
});

const radarKeys = [
    "points_per_game",
    "assists_per_game",
    "rebounds_per_game",
    "steals_per_game",
    "blocks_per_game",
];

const radarAxisLabels = ["PTS", "AST", "REB", "STL", "BLK"];

const maxes = computed(() => props.radarMaxes || {});

// Scale the radar based on the league's current per-game leaders (from
// `radarMaxes`) with headroom so it doesn't visually peg at 100%.
//
// Headroom is dynamic based on how many games the player has played:
// fewer games -> more headroom (more conservative), more games -> less headroom.
const RADAR_BASE_HEADROOM_FACTOR = 1.5;

const radarHeadroomFactorForGames = (gamesPlayed) => {
    const games = Number(gamesPlayed ?? 0);
    if (!Number.isFinite(games) || games <= 0)
        return RADAR_BASE_HEADROOM_FACTOR;
    if (games <= 2) return 2.0;
    if (games <= 5) return 1.75;
    if (games <= 10) return 1.5;
    if (games <= 20) return 1.35;
    return 1.25;
};

// Absolute fallback caps (only used if `radarMaxes` is missing/zero).
const RADAR_FALLBACK_CAPS = {
    points_per_game: 25,
    assists_per_game: 8,
    rebounds_per_game: 12,
    steals_per_game: 4,
    blocks_per_game: 3,
};

const radarTotalsByPerGameKey = {
    points_per_game: "total_points",
    assists_per_game: "total_assists",
    rebounds_per_game: "total_rebounds",
    steals_per_game: "total_steals",
    blocks_per_game: "total_blocks",
};

const formatOneDecimal = (value) => {
    const n = Number(value);
    if (Number.isNaN(n)) return "0.0";
    return n.toFixed(1);
};

const radarStatValue = (player, perGameKey) => {
    const gamesPlayed = Number(player?.games_played ?? 0);
    if (!Number.isFinite(gamesPlayed) || gamesPlayed <= 0) return 0;

    const totalKey = radarTotalsByPerGameKey?.[perGameKey];
    const totalRaw = Number(player?.[totalKey] ?? 0);
    const total = Number.isFinite(totalRaw) ? totalRaw : 0;

    return total / gamesPlayed;
};

const radarGridPoints = (radius) => {
    const center = 50;
    const step = (2 * Math.PI) / 5;
    const start = -Math.PI / 2;
    const points = [];

    for (let i = 0; i < 5; i++) {
        const angle = start + i * step;
        const x = center + radius * Math.cos(angle);
        const y = center + radius * Math.sin(angle);
        points.push(`${x},${y}`);
    }

    return points.join(" ");
};

const radarAxisEndpoints = computed(() => {
    const center = 50;
    const radius = 40;
    const step = (2 * Math.PI) / 5;
    const start = -Math.PI / 2;

    const points = [];
    for (let i = 0; i < 5; i++) {
        const angle = start + i * step;
        points.push({
            x: center + radius * Math.cos(angle),
            y: center + radius * Math.sin(angle),
        });
    }
    return points;
});

const radarLabelPositions = computed(() => {
    const center = 50;
    const radius = 48;
    const step = (2 * Math.PI) / 5;
    const start = -Math.PI / 2;

    const positions = [];
    for (let i = 0; i < 5; i++) {
        const angle = start + i * step;
        const x = center + radius * Math.cos(angle);
        const y = center + radius * Math.sin(angle);

        const anchor =
            x < center - 1 ? "end" : x > center + 1 ? "start" : "middle";

        positions.push({ x, y, anchor });
    }

    return positions;
});

const radarDataPoints = (player) => {
    const center = 50;
    const radius = 40;
    const step = (2 * Math.PI) / 5;
    const start = -Math.PI / 2;

    const points = [];

    radarKeys.forEach((key, i) => {
        const datasetMax = Number(maxes.value?.[key] ?? 0);
        const fallbackCap = Number(RADAR_FALLBACK_CAPS?.[key] ?? 0);
        const headroom = radarHeadroomFactorForGames(player?.games_played);
        const max =
            datasetMax > 0 ? datasetMax * headroom : fallbackCap * headroom;
        const value = radarStatValue(player, key);
        const ratio = max > 0 ? Math.min(value / max, 1) : 0;
        const r = radius * ratio;

        const angle = start + i * step;
        const x = center + r * Math.cos(angle);
        const y = center + r * Math.sin(angle);
        points.push(`${x},${y}`);
    });

    return points.join(" ");
};

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
.player-stats-page {
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
    flex-shrink: 0;
}

.header-icon svg {
    width: 1.5rem;
    height: 1.5rem;
    color: var(--cv-text-1);
}

.header-text {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    min-width: 0;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--cv-text-1);
    margin: 0;
}

.page-subtitle {
    margin: 0;
    color: var(--cv-muted);
    font-weight: 600;
    font-size: 0.95rem;
}

.player-profile {
    padding: 1rem;
    background: var(--cv-surface-1);
    border: 1px solid var(--cv-border-1);
    border-radius: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.profile-top {
    display: grid;
    grid-template-columns: minmax(0, 160px) minmax(0, 1fr);
    gap: 1rem;
    align-items: start;
}

.profile-photo {
    width: 100%;
    height: 200px;
    border-radius: 0.75rem;
    overflow: hidden;
    background: var(--cv-surface-1);
    border: 1px solid var(--cv-border-1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-placeholder {
    font-size: 2rem;
    font-weight: 700;
    color: #22c55e;
}

.profile-name {
    margin-top: 0.5rem;
    text-align: center;
    font-weight: 700;
    color: var(--cv-text-1);
}

.profile-right {
    display: flex;
    justify-content: center;
    padding-top: 0.75rem;
}

.radar-wrapper {
    width: 190px;
    aspect-ratio: 1 / 1;
    max-width: 100%;
}

.radar {
    width: 100%;
    height: 100%;
    overflow: visible;
}

.radar-grid {
    fill: none;
    stroke: rgba(71, 85, 105, 0.65);
    stroke-width: 1;
}

.radar-axis {
    stroke: rgba(71, 85, 105, 0.65);
    stroke-width: 1;
}

.radar-data {
    fill: rgba(34, 197, 94, 0.22);
    stroke: rgba(34, 197, 94, 0.85);
    stroke-width: 2;
}

.radar-label {
    fill: var(--cv-muted);
    font-size: 6px;
    font-weight: 700;
}

.profile-meta {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.75rem;
}

.stat-item {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.25rem 0;
    background: transparent;
    border: none;
    border-radius: 0;
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
    display: inline;
    min-width: 0;
    padding: 0;
    background: transparent;
    color: rgba(251, 191, 36, 0.95);
    border-radius: 0;
    font-weight: 700;
    font-size: 0.875rem;
}

.position-badge {
    display: inline;
    padding: 0;
    background: transparent;
    color: rgba(251, 191, 36, 0.95);
    border-radius: 0;
    font-weight: 700;
    font-size: 0.75rem;
}

.games-cell {
    color: var(--cv-text-1);
    font-weight: 700;
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

.steals-cell {
    color: #4ade80;
    text-align: center;
}

.rebounds-cell {
    color: #fb923c;
    text-align: center;
}

.blocks-cell {
    color: rgba(251, 191, 36, 0.85);
    text-align: center;
}

.best-cell {
    color: rgba(251, 191, 36, 0.85);
    font-weight: 700;
}

@media (max-width: 768px) {
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

    .page-subtitle {
        font-size: 0.875rem;
    }

    .player-profile {
        padding: 0.875rem;
    }

    .profile-top {
        grid-template-columns: minmax(0, 140px) minmax(0, 1fr);
        align-items: center;
    }

    .profile-photo {
        height: 160px;
    }

    .profile-name {
        margin-top: 0.35rem;
        font-size: 0.95rem;
    }

    .profile-right {
        justify-content: center;
        padding-top: 0;
    }

    .radar-wrapper {
        width: 160px;
    }

    .profile-meta {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.6rem;
    }

    .stat-item {
        gap: 0.5rem;
        font-size: 0.875rem;
    }

    .stat-item .stat-label {
        font-size: 0.65rem;
    }

    .jersey-badge {
        font-size: 0.8rem;
    }

    .position-badge {
        font-size: 0.7rem;
    }
}

@media (max-width: 480px) {
    .radar-wrapper {
        width: 145px;
    }

    .profile-photo {
        height: 150px;
    }

    .page-title {
        font-size: 1.1rem;
    }

    .page-subtitle {
        font-size: 0.8rem;
    }

    .stat-item {
        font-size: 0.84rem;
    }
}

@media (max-width: 360px) {
    .profile-top {
        grid-template-columns: 1fr;
    }

    .profile-meta {
        grid-template-columns: 1fr;
    }
}
</style>
