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
                <p>
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
.team-stats-page {
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
