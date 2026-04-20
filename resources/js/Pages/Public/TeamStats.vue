<template>
    <Head v-if="!embedded" title="Team Stats | SBA" />
    <component :is="embedded ? 'div' : PublicLayout">
        <div class="team-stats-page">
            <div class="page-header">
                <div class="header-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                        ></path>
                    </svg>
                </div>
                <h1 class="page-title">Team Statistics</h1>
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

            <div class="filters-bar">
                <div class="filters-row">
                    <input
                        v-model="searchQuery"
                        type="text"
                        class="search-input"
                        placeholder="Search team or division..."
                    />

                    <div class="filters-selects">
                        <select
                            v-model="selectedRecordFilter"
                            class="filter-select"
                        >
                            <option value="all">All Records</option>
                            <option value="winning">Winning Teams</option>
                            <option value="even">Even Record</option>
                            <option value="below500">Below .500</option>
                        </select>

                        <select v-model="selectedSort" class="filter-select">
                            <option value="points_desc">
                                Sort: Points (High to Low)
                            </option>
                            <option value="wins_desc">
                                Sort: Wins (High to Low)
                            </option>
                            <option value="winpct_desc">
                                Sort: Win % (High to Low)
                            </option>
                            <option value="name_asc">Sort: Name (A-Z)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div v-if="filteredTeams.length > 0" class="teams-grid">
                <div
                    v-for="team in filteredTeams"
                    :key="team.id"
                    class="team-card"
                >
                    <div class="team-header">
                        <div class="team-avatar">
                            <img
                                v-if="resolveTeamLogoUrl(team.logo)"
                                :src="resolveTeamLogoUrl(team.logo)"
                                :alt="team.name + ' logo'"
                                class="team-avatar-image"
                                loading="lazy"
                            />
                            <span v-else class="team-avatar-fallback">
                                {{ team.name?.charAt(0) || "T" }}
                            </span>
                        </div>
                        <h2 class="team-name">{{ team.name }}</h2>
                    </div>

                    <div class="team-radar-block">
                        <div class="team-radar-wrapper">
                            <svg
                                class="team-radar"
                                viewBox="0 0 100 100"
                                aria-hidden="true"
                            >
                                <polygon
                                    class="team-radar-grid"
                                    :points="radarGridPoints(40)"
                                />
                                <polygon
                                    class="team-radar-grid"
                                    :points="radarGridPoints(30)"
                                />
                                <polygon
                                    class="team-radar-grid"
                                    :points="radarGridPoints(20)"
                                />
                                <polygon
                                    class="team-radar-grid"
                                    :points="radarGridPoints(10)"
                                />

                                <line
                                    v-for="(point, i) in radarAxisEndpoints"
                                    :key="`axis-${i}`"
                                    class="team-radar-axis"
                                    x1="50"
                                    y1="50"
                                    :x2="point.x"
                                    :y2="point.y"
                                />

                                <polygon
                                    class="team-radar-data"
                                    :points="teamRadarDataPoints(team)"
                                />

                                <text
                                    v-for="(label, i) in radarAxisLabels"
                                    :key="`label-${label}`"
                                    class="team-radar-label"
                                    :x="radarLabelPositions[i].x"
                                    :y="radarLabelPositions[i].y"
                                    :text-anchor="radarLabelPositions[i].anchor"
                                    dominant-baseline="middle"
                                >
                                    {{ label }}
                                </text>
                            </svg>
                        </div>

                        <div
                            class="team-radar-legend"
                            aria-label="Team radar labels"
                        >
                            <span
                                v-for="label in radarAxisLabelsLong"
                                :key="`legend-${label}`"
                                class="team-radar-legend-item"
                            >
                                {{ label }}
                            </span>
                        </div>

                        <p class="team-radar-note">
                            This chart shows how this team compares with the
                            league's best teams in each stat.
                        </p>
                    </div>

                    <div class="stats-list">
                        <div class="stat-item">
                            <span class="stat-label">Total Players</span>
                            <span class="stat-value">{{
                                team.players_count
                            }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Wins</span>
                            <span class="stat-value stat-wins">{{
                                team.wins
                            }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Losses</span>
                            <span class="stat-value stat-losses">{{
                                team.losses
                            }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Total Points</span>
                            <span class="stat-value stat-points">{{
                                team.points
                            }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Win Percentage</span>
                            <span class="stat-value stat-percentage"
                                >{{ team.win_percentage }}%</span
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="empty-state">
                <div class="empty-icon">📊</div>
                <p v-if="hasActiveFilters">
                    No teams match your search/filters.
                </p>
                <p v-else>
                    No team statistics available{{
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
    teams: Array,
    divisions: Array,
    embedded: {
        type: Boolean,
        default: false,
    },
});

const selectedDivision = ref(null);
const searchQuery = ref("");
const selectedRecordFilter = ref("all");
const selectedSort = ref("points_desc");

const radarMetricKeys = [
    "wins",
    "points",
    "win_percentage",
    "players_count",
    "games_played",
];

const radarAxisLabels = ["W", "PTS", "WIN%", "PLY", "GMS"];
const radarAxisLabelsLong = ["Wins", "Points", "Win %", "Players", "Games"];

const allTeams = computed(() => {
    return Array.isArray(props.teams) ? props.teams : [];
});

const divisionFilteredTeams = computed(() => {
    if (selectedDivision.value === null) {
        return allTeams.value;
    }
    return allTeams.value.filter(
        (team) => team.division_id === selectedDivision.value,
    );
});

const toNumber = (value) => {
    if (typeof value === "number") {
        return Number.isFinite(value) ? value : 0;
    }

    if (typeof value === "string") {
        const normalized = value.replace("%", "").trim();
        const n = Number(normalized);
        return Number.isFinite(n) ? n : 0;
    }

    const n = Number(value ?? 0);
    return Number.isFinite(n) ? n : 0;
};

const teamGamesPlayed = (team) => {
    const wins = toNumber(team?.wins);
    const losses = toNumber(team?.losses);
    return Math.max(0, wins + losses);
};

const teamWinPercentage = (team) => {
    const explicit = toNumber(team?.win_percentage);
    if (explicit > 0) return explicit;

    const wins = toNumber(team?.wins);
    const games = teamGamesPlayed(team);
    if (games <= 0) return 0;
    return (wins / games) * 100;
};

const hasActiveFilters = computed(() => {
    return (
        selectedDivision.value !== null ||
        searchQuery.value.trim().length > 0 ||
        selectedRecordFilter.value !== "all" ||
        selectedSort.value !== "points_desc"
    );
});

const filteredTeams = computed(() => {
    let teams = [...divisionFilteredTeams.value];

    const q = searchQuery.value.trim().toLowerCase();
    if (q.length > 0) {
        teams = teams.filter((team) => {
            const name = String(team?.name ?? "").toLowerCase();
            const division = String(team?.division?.name ?? "").toLowerCase();
            return name.includes(q) || division.includes(q);
        });
    }

    if (selectedRecordFilter.value !== "all") {
        teams = teams.filter((team) => {
            const wins = toNumber(team?.wins);
            const losses = toNumber(team?.losses);
            const winPct = teamWinPercentage(team);

            switch (selectedRecordFilter.value) {
                case "winning":
                    return winPct >= 50;
                case "even":
                    return wins === losses;
                case "below500":
                    return winPct < 50;
                default:
                    return true;
            }
        });
    }

    const sorted = [...teams];
    switch (selectedSort.value) {
        case "wins_desc":
            sorted.sort((a, b) => toNumber(b?.wins) - toNumber(a?.wins));
            break;
        case "winpct_desc":
            sorted.sort((a, b) => teamWinPercentage(b) - teamWinPercentage(a));
            break;
        case "name_asc":
            sorted.sort((a, b) =>
                String(a?.name ?? "").localeCompare(String(b?.name ?? "")),
            );
            break;
        case "points_desc":
        default:
            sorted.sort((a, b) => toNumber(b?.points) - toNumber(a?.points));
            break;
    }

    return sorted;
});

const teamRadarValue = (team, key) => {
    switch (key) {
        case "wins":
            return Math.max(0, toNumber(team?.wins));
        case "points":
            return Math.max(0, toNumber(team?.points));
        case "win_percentage":
            return Math.max(0, toNumber(team?.win_percentage));
        case "players_count":
            return Math.max(0, toNumber(team?.players_count));
        case "games_played":
            return teamGamesPlayed(team);
        default:
            return 0;
    }
};

const radarMetricMaxes = computed(() => {
    const maxes = {};

    for (const key of radarMetricKeys) {
        const max = Math.max(
            0,
            ...allTeams.value.map((team) => teamRadarValue(team, key)),
        );
        maxes[key] = max > 0 ? max : 1;
    }

    return maxes;
});

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

const teamRadarDataPoints = (team) => {
    const center = 50;
    const radius = 40;
    const step = (2 * Math.PI) / 5;
    const start = -Math.PI / 2;

    const points = [];

    radarMetricKeys.forEach((key, i) => {
        const max = Number(radarMetricMaxes.value?.[key] ?? 1);
        const value = teamRadarValue(team, key);
        const ratio = max > 0 ? Math.min(value / max, 1) : 0;
        const r = radius * ratio;

        const angle = start + i * step;
        const x = center + r * Math.cos(angle);
        const y = center + r * Math.sin(angle);
        points.push(`${x},${y}`);
    });

    return points.join(" ");
};

const resolveTeamLogoUrl = (logoPath) => {
    if (!logoPath || typeof logoPath !== "string") return null;
    if (logoPath.startsWith("http://") || logoPath.startsWith("https://")) {
        return logoPath;
    }
    if (logoPath.startsWith("/")) return logoPath;
    return `/storage/${logoPath}`;
};
</script>

<style scoped>
.team-stats-page {
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
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-color: rgba(251, 191, 36, 0.55);
    color: #ffffff;
}

.filters-bar {
    padding: 0;
}

.filters-row {
    display: flex;
    gap: 0.75rem;
    flex-direction: column;
    align-items: stretch;
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

.teams-grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 1.5rem;
}

@media (min-width: 768px) {
    .teams-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .teams-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.team-card {
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.team-card:hover {
    border-color: var(--cv-border-2);
    transform: translateY(-4px);
}

.team-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--cv-border-1);
}

.team-avatar {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #ffffff;
    font-size: 1.25rem;
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

.team-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: rgba(251, 191, 36, 0.95);
    margin: 0;
}

.team-radar-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--cv-border-1);
}

.team-radar-wrapper {
    width: 170px;
    aspect-ratio: 1 / 1;
    max-width: 100%;
}

.team-radar {
    width: 100%;
    height: 100%;
    overflow: visible;
}

.team-radar-grid {
    fill: none;
    stroke: rgba(71, 85, 105, 0.65);
    stroke-width: 1;
}

.team-radar-axis {
    stroke: rgba(71, 85, 105, 0.65);
    stroke-width: 1;
}

.team-radar-data {
    fill: rgba(245, 158, 11, 0.22);
    stroke: rgba(245, 158, 11, 0.9);
    stroke-width: 2;
}

.team-radar-label {
    fill: var(--cv-muted);
    font-size: 6px;
    font-weight: 700;
}

.team-radar-legend {
    margin-top: 0.5rem;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.3rem;
}

.team-radar-legend-item {
    font-size: 0.62rem;
    font-weight: 700;
    color: var(--cv-text-2);
    border: 1px solid var(--cv-border-1);
    border-radius: 999px;
    padding: 0.15rem 0.45rem;
    background: var(--cv-surface-3);
}

.team-radar-note {
    margin: 0.45rem 0 0;
    text-align: center;
    font-size: 0.72rem;
    color: var(--cv-muted-2);
}

.stats-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--cv-border-1);
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-label {
    color: var(--cv-muted);
    font-size: 0.875rem;
}

.stat-value {
    font-weight: 700;
    font-size: 1.125rem;
    color: var(--cv-text-1);
}

.stat-wins {
    color: #4ade80;
}

.stat-losses {
    color: #f87171;
}

.stat-points {
    color: rgba(251, 191, 36, 0.95);
}

.stat-percentage {
    color: rgba(251, 191, 36, 0.85);
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
        padding: 0;
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
        padding: 0;
        gap: 0.375rem;
    }

    .filters-bar {
        padding: 0;
    }

    .filters-selects {
        grid-template-columns: 1fr;
    }

    .division-tab {
        padding: 0.4rem 0.75rem;
        font-size: 0.8125rem;
    }

    .teams-grid {
        gap: 1rem;
    }

    .team-card {
        padding: 1.125rem;
    }

    .team-header {
        gap: 0.75rem;
        margin-bottom: 1.125rem;
        padding-bottom: 0.75rem;
    }

    .team-avatar {
        width: 2.75rem;
        height: 2.75rem;
        font-size: 1.125rem;
        border-radius: 0.625rem;
    }

    .team-name {
        font-size: 1.1rem;
    }

    .team-radar-wrapper {
        width: 150px;
    }

    .team-radar-legend-item {
        font-size: 0.58rem;
        padding: 0.12rem 0.38rem;
    }

    .team-radar-note {
        font-size: 0.66rem;
    }

    .stat-label {
        font-size: 0.8125rem;
    }

    .stat-value {
        font-size: 1rem;
    }

    .empty-state {
        padding: 2.5rem 1.25rem;
    }
}
</style>
