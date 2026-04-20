<template>
    <Head v-if="!embedded" title="Team Rankings | SBA" />
    <component :is="embedded ? 'div' : PublicLayout">
        <div class="rankings-page">
            <div class="page-header">
                <div class="header-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
                        ></path>
                    </svg>
                </div>
                <h1 class="page-title">TEAM RANKINGS</h1>
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
                v-if="topTeam"
                class="featured-top-card"
                aria-label="Top team"
            >
                <div class="featured-head">
                    <span class="featured-pill">TOP 1</span>
                    <span class="featured-rank">
                        Rank #{{ resolveRank(topTeam, 1) }}
                    </span>
                </div>

                <div class="featured-main">
                    <div class="featured-team">
                        <img
                            v-if="resolveTeamLogoUrl(topTeam.logo)"
                            :src="resolveTeamLogoUrl(topTeam.logo)"
                            :alt="topTeam.name + ' logo'"
                            class="featured-logo"
                            loading="lazy"
                        />
                        <div
                            v-else
                            class="featured-logo-fallback"
                            aria-hidden="true"
                        >
                            {{ topTeam.name?.charAt(0) || "T" }}
                        </div>

                        <div class="featured-team-meta">
                            <h2 class="featured-team-name">
                                {{ topTeam.name }}
                            </h2>
                            <p class="featured-team-sub">
                                {{ topTeam.division?.name || "All Divisions" }}
                                <span class="featured-dot">•</span>
                                {{ topTeam.wins }}-{{ topTeam.losses }} record
                            </p>
                        </div>
                    </div>

                    <div class="featured-stats" aria-label="Top team stats">
                        <div class="featured-stat">
                            <span class="featured-stat-k">Points</span>
                            <span class="featured-stat-v">{{
                                topTeam.points
                            }}</span>
                        </div>
                        <div class="featured-stat">
                            <span class="featured-stat-k">Wins</span>
                            <span class="featured-stat-v win">{{
                                topTeam.wins
                            }}</span>
                        </div>
                        <div class="featured-stat">
                            <span class="featured-stat-k">Losses</span>
                            <span class="featured-stat-v loss">{{
                                topTeam.losses
                            }}</span>
                        </div>
                        <div class="featured-stat">
                            <span class="featured-stat-k">Win %</span>
                            <span class="featured-stat-v">
                                {{ topTeam.win_percentage }}%
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <div
                v-if="remainingTeams.length > 0"
                class="rankings-table-wrapper"
            >
                <table class="rankings-table">
                    <thead>
                        <tr>
                            <th>
                                <span class="th-label th-label-full">Rank</span>
                                <span class="th-label th-label-short">#</span>
                            </th>
                            <th>
                                <span class="th-label th-label-full"
                                    >Team Name</span
                                >
                                <span class="th-label th-label-short"
                                    >Team</span
                                >
                            </th>
                            <th>
                                <span class="th-label th-label-full">Wins</span>
                                <span class="th-label th-label-short">W</span>
                            </th>
                            <th>
                                <span class="th-label th-label-full"
                                    >Losses</span
                                >
                                <span class="th-label th-label-short">L</span>
                            </th>
                            <th>
                                <span class="th-label th-label-full"
                                    >Points</span
                                >
                                <span class="th-label th-label-short">PTS</span>
                            </th>
                            <th>
                                <span class="th-label th-label-full"
                                    >Win %</span
                                >
                                <span class="th-label th-label-short"
                                    >WIN%</span
                                >
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(team, index) in remainingTeams"
                            :key="team.id"
                            :class="{
                                'top-three': resolveRank(team, index + 2) <= 3,
                            }"
                        >
                            <td>
                                <span
                                    class="rank-badge"
                                    :class="
                                        'rank-' + resolveRank(team, index + 2)
                                    "
                                >
                                    <span
                                        v-if="
                                            resolveRank(team, index + 2) === 1
                                        "
                                        >🥇</span
                                    >
                                    <span
                                        v-else-if="
                                            resolveRank(team, index + 2) === 2
                                        "
                                        >🥈</span
                                    >
                                    <span
                                        v-else-if="
                                            resolveRank(team, index + 2) === 3
                                        "
                                        >🥉</span
                                    >
                                    <span v-else>
                                        {{ resolveRank(team, index + 2) }}
                                    </span>
                                </span>
                            </td>
                            <td class="team-name-cell">
                                <div class="team-name">
                                    <img
                                        v-if="resolveTeamLogoUrl(team.logo)"
                                        :src="resolveTeamLogoUrl(team.logo)"
                                        :alt="team.name + ' logo'"
                                        class="team-logo"
                                        loading="lazy"
                                    />
                                    <div
                                        v-else
                                        class="team-logo-fallback"
                                        aria-hidden="true"
                                    >
                                        {{ team.name?.charAt(0) || "T" }}
                                    </div>
                                    <span class="team-name-text">{{
                                        team.name
                                    }}</span>
                                </div>
                            </td>
                            <td class="wins-cell">{{ team.wins }}</td>
                            <td class="losses-cell">{{ team.losses }}</td>
                            <td class="points-cell">{{ team.points }}</td>
                            <td class="percentage-cell">
                                {{ team.win_percentage }}%
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-else class="empty-state">
                <div class="empty-icon">🏆</div>
                <p>
                    No teams available{{
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

const filteredTeams = computed(() => {
    const teams = Array.isArray(props.teams) ? props.teams : [];

    if (selectedDivision.value === null) {
        return teams;
    }
    return teams.filter((team) => team.division_id === selectedDivision.value);
});

const topTeam = computed(() => filteredTeams.value[0] ?? null);

const remainingTeams = computed(() => filteredTeams.value.slice(1));

const resolveRank = (team, fallbackRank) => {
    const rank = Number(team?.rank);
    if (Number.isFinite(rank) && rank > 0) return rank;
    return fallbackRank;
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
.rankings-page {
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
    border-color: #f59e0b;
    color: #ffffff;
}

.featured-top-card {
    border-radius: 1rem;
    border: 1px solid rgba(251, 191, 36, 0.35);
    background:
        linear-gradient(
            135deg,
            rgba(251, 191, 36, 0.16) 0%,
            rgba(245, 158, 11, 0.1) 55%,
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
    color: #78350f;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
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

.featured-team {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    min-width: 0;
}

.featured-logo,
.featured-logo-fallback {
    width: 4rem;
    height: 4rem;
    border-radius: 1rem;
    flex-shrink: 0;
}

.featured-logo {
    object-fit: cover;
    border: 1px solid rgba(251, 191, 36, 0.4);
    background: var(--cv-surface-3);
}

.featured-logo-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 800;
    color: #78350f;
    border: 1px solid rgba(251, 191, 36, 0.4);
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
}

.featured-team-meta {
    min-width: 0;
}

.featured-team-name {
    margin: 0;
    font-size: 1.35rem;
    line-height: 1.15;
    color: var(--cv-text-1);
}

.featured-team-sub {
    margin: 0.32rem 0 0;
    color: var(--cv-text-2);
    font-size: 0.9rem;
}

.featured-dot {
    margin: 0 0.35rem;
    color: var(--cv-muted);
}

.featured-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(6rem, 1fr));
    gap: 0.55rem;
    width: 100%;
    max-width: 34rem;
}

.featured-stat {
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
    background: rgba(0, 0, 0, 0.22);
    padding: 0.55rem 0.65rem;
    display: flex;
    flex-direction: column;
    gap: 0.18rem;
}

.featured-stat-k {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--cv-muted);
}

.featured-stat-v {
    font-size: 1.05rem;
    font-weight: 800;
    color: var(--cv-text-1);
}

.featured-stat-v.win {
    color: #4ade80;
}

.featured-stat-v.loss {
    color: #f87171;
}

.rankings-table-wrapper {
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
    overflow-x: auto;
}

.rankings-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 640px;
}

.rankings-table thead {
    background: var(--cv-surface-3);
}

.rankings-table th {
    padding: 1rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--cv-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.th-label-short {
    display: none;
}

.rankings-table tbody tr {
    border-top: 1px solid var(--cv-border-1);
    transition: background 0.3s ease;
}

.rankings-table tbody tr:hover {
    background: var(--cv-surface-2);
}

.rankings-table tbody tr.top-three {
    background: rgba(251, 191, 36, 0.05);
}

.rankings-table td {
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

.team-name-cell {
    font-weight: 600;
    color: var(--cv-text-1);
    font-size: 1rem;
}

.team-name {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.team-logo,
.team-logo-fallback {
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 0.5rem;
    flex-shrink: 0;
}

.team-logo {
    object-fit: cover;
    background: var(--cv-surface-3);
    border: 1px solid var(--cv-border-1);
}

.team-logo-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--cv-surface-3);
    border: 1px solid var(--cv-border-1);
    color: var(--cv-text-2);
    font-weight: 700;
}

.team-name-text {
    color: var(--cv-text-1);
}

.wins-cell {
    color: #4ade80;
    font-weight: 600;
}

.losses-cell {
    color: #f87171;
    font-weight: 600;
}

.points-cell {
    color: rgba(251, 191, 36, 0.95);
    font-weight: 700;
    font-size: 1.125rem;
}

.percentage-cell {
    color: rgba(251, 191, 36, 0.85);
    font-weight: 600;
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
    .rankings-table th,
    .rankings-table td {
        padding: 0.75rem;
    }
}

@media (max-width: 640px) {
    .page-header {
        padding: 1rem;
        gap: 0.75rem;
    }

    .rankings-table-wrapper {
        overflow-x: hidden;
    }

    .rankings-table {
        min-width: 0;
        table-layout: fixed;
    }

    .rankings-table th,
    .rankings-table td {
        padding: 0.52rem 0.3rem;
        font-size: 0.68rem;
        letter-spacing: 0.02em;
        line-height: 1.2;
    }

    .th-label-full {
        display: none;
    }

    .th-label-short {
        display: inline;
    }

    .rankings-table th:nth-child(1),
    .rankings-table td:nth-child(1) {
        width: 2.3rem;
        text-align: center;
    }

    .rankings-table th:nth-child(2),
    .rankings-table td:nth-child(2) {
        width: 40%;
    }

    .rankings-table th:nth-child(n + 3),
    .rankings-table td:nth-child(n + 3) {
        width: 12%;
        text-align: center;
    }

    .header-icon svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    .page-title {
        font-size: 1.25rem;
    }

    .division-tabs {
        gap: 0.375rem;
    }

    .division-tab {
        padding: 0.4rem 0.75rem;
        font-size: 0.8125rem;
    }

    .team-name {
        gap: 0.35rem;
    }

    .team-logo,
    .team-logo-fallback {
        width: 1.4rem;
        height: 1.4rem;
        border-radius: 0.35rem;
    }

    .team-name-cell {
        font-size: 0.74rem;
    }

    .team-name-text {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .rank-badge {
        width: 1.55rem;
        height: 1.55rem;
        border-radius: 0.42rem;
        font-size: 0.72rem;
    }

    .points-cell {
        font-size: 0.78rem;
    }

    .wins-cell,
    .losses-cell,
    .percentage-cell {
        font-size: 0.72rem;
    }

    .featured-top-card {
        padding: 0.85rem;
    }

    .featured-head {
        margin-bottom: 0.75rem;
    }

    .featured-logo,
    .featured-logo-fallback {
        width: 3.2rem;
        height: 3.2rem;
        border-radius: 0.85rem;
    }

    .featured-team-name {
        font-size: 1.08rem;
    }

    .featured-team-sub {
        font-size: 0.82rem;
    }

    .featured-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        max-width: none;
    }
}

@media (max-width: 420px) {
    .rankings-table th,
    .rankings-table td {
        padding: 0.42rem 0.22rem;
        font-size: 0.62rem;
    }

    .rank-badge {
        width: 1.35rem;
        height: 1.35rem;
        font-size: 0.64rem;
    }

    .team-logo,
    .team-logo-fallback {
        width: 1.2rem;
        height: 1.2rem;
    }

    .team-name-cell {
        font-size: 0.68rem;
    }

    .points-cell {
        font-size: 0.72rem;
    }
}
</style>
