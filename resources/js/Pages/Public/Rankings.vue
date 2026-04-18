<template>
    <Head v-if="!embedded" title="Rankings | SBA" />
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
                <h1 class="page-title">Team Rankings</h1>
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

            <div v-if="filteredTeams.length > 0" class="rankings-table-wrapper">
                <table class="rankings-table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Team Name</th>
                            <th>Wins</th>
                            <th>Losses</th>
                            <th>Points</th>
                            <th>Win %</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(team, index) in filteredTeams"
                            :key="team.id"
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

            <div v-if="filteredTeams.length > 0" class="rankings-cards">
                <div
                    v-for="(team, index) in filteredTeams"
                    :key="`card-${team.id}`"
                    class="ranking-card"
                    :class="{ 'top-three-card': index < 3 }"
                >
                    <div class="ranking-card-header">
                        <span class="rank-badge" :class="'rank-' + (index + 1)">
                            <span v-if="index === 0">🥇</span>
                            <span v-else-if="index === 1">🥈</span>
                            <span v-else-if="index === 2">🥉</span>
                            <span v-else>{{ index + 1 }}</span>
                        </span>

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
                            <span class="team-name-text">{{ team.name }}</span>
                        </div>
                    </div>

                    <div class="ranking-card-stats">
                        <div class="stat-item">
                            <span class="stat-label">Wins</span>
                            <span class="wins-cell">{{ team.wins }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Losses</span>
                            <span class="losses-cell">{{ team.losses }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Points</span>
                            <span class="points-cell">{{ team.points }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Win %</span>
                            <span class="percentage-cell"
                                >{{ team.win_percentage }}%</span
                            >
                        </div>
                    </div>
                </div>
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
    if (selectedDivision.value === null) {
        return props.teams;
    }
    return props.teams.filter(
        (team) => team.division_id === selectedDivision.value,
    );
});

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
    border-color: #f59e0b;
    color: #ffffff;
}

.rankings-table-wrapper {
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
    overflow: hidden;
}

.rankings-table {
    width: 100%;
    border-collapse: collapse;
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

.rankings-cards {
    display: none;
    gap: 1rem;
}

@media (max-width: 768px) {
    .rankings-table-wrapper {
        display: none;
    }

    .rankings-cards {
        display: grid;
    }
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

    .ranking-card-stats {
        grid-template-columns: 1fr;
    }
}

.ranking-card {
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
    padding: 1rem;
}

.ranking-card.top-three-card {
    background: rgba(251, 191, 36, 0.05);
}

.ranking-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.ranking-card-stats {
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
</style>
