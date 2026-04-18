<template>
    <Head title="Player Stats - Committee | SBA" />
    <CommitteeLayout>
        <div class="player-stats-page">
            <!-- Header -->
            <div class="header">
                <div class="header-content">
                    <Link
                        :href="route('committee.stats.index')"
                        class="back-btn"
                    >
                        ← Back to Matches
                    </Link>
                    <h1 class="page-title">Player Statistics</h1>
                    <p class="page-subtitle">
                        {{ match.team_a?.name }} vs {{ match.team_b?.name }}
                    </p>
                </div>
                <Link
                    :href="route('committee.player-stats.create', match.id)"
                    class="add-btn"
                >
                    + Add Stats
                </Link>
            </div>

            <!-- Team A Stats -->
            <div class="glass-card">
                <div class="card-header">
                    <h2 class="card-title">{{ match.team_a?.name }}</h2>
                </div>

                <div class="stats-table-container" v-if="teamAStats.length > 0">
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="stat in teamAStats" :key="stat.id">
                                <td class="player-name">
                                    {{ stat.player?.name }}
                                </td>
                                <td>{{ stat.points }}</td>
                                <td>{{ stat.assists }}</td>
                                <td>{{ stat.rebounds }}</td>
                                <td>{{ stat.blocks }}</td>
                                <td>{{ stat.steals }}</td>
                                <td>{{ stat.fouls }}</td>
                                <td class="actions-cell">
                                    <Link
                                        :href="
                                            route(
                                                'committee.player-stats.edit',
                                                [match.id, stat.id],
                                            )
                                        "
                                        class="edit-btn"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        @click="deleteStat(stat.id)"
                                        class="delete-btn"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="empty-state">
                    <p>No stats recorded for this team</p>
                </div>
            </div>

            <!-- Team B Stats -->
            <div class="glass-card">
                <div class="card-header">
                    <h2 class="card-title">{{ match.team_b?.name }}</h2>
                </div>

                <div class="stats-table-container" v-if="teamBStats.length > 0">
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="stat in teamBStats" :key="stat.id">
                                <td class="player-name">
                                    {{ stat.player?.name }}
                                </td>
                                <td>{{ stat.points }}</td>
                                <td>{{ stat.assists }}</td>
                                <td>{{ stat.rebounds }}</td>
                                <td>{{ stat.blocks }}</td>
                                <td>{{ stat.steals }}</td>
                                <td>{{ stat.fouls }}</td>
                                <td class="actions-cell">
                                    <Link
                                        :href="
                                            route(
                                                'committee.player-stats.edit',
                                                [match.id, stat.id],
                                            )
                                        "
                                        class="edit-btn"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        @click="deleteStat(stat.id)"
                                        class="delete-btn"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="empty-state">
                    <p>No stats recorded for this team</p>
                </div>
            </div>
        </div>
    </CommitteeLayout>
</template>

<script setup>
import { computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import CommitteeLayout from "@/Layouts/CommitteeLayout.vue";

const props = defineProps({
    stats: {
        type: Array,
        default: () => [],
    },
    match: {
        type: Object,
        required: true,
    },
});

const teamAStats = computed(() => {
    return props.stats.filter(
        (stat) => stat.player?.team_id === props.match.team_a_id,
    );
});

const teamBStats = computed(() => {
    return props.stats.filter(
        (stat) => stat.player?.team_id === props.match.team_b_id,
    );
});

const deleteStat = (statId) => {
    if (confirm("Are you sure you want to delete this stat?")) {
        router.delete(
            route("committee.player-stats.destroy", [props.match.id, statId]),
        );
    }
};
</script>

<style scoped>
.player-stats-page {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.header-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.back-btn {
    color: #a855f7;
    text-decoration: none;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    transition: color 0.3s ease;
}

.back-btn:hover {
    color: #c084fc;
}

.page-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
}

.page-subtitle {
    color: #9ca3af;
    margin: 0;
}

.add-btn {
    padding: 0.75rem 1.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #ffffff;
    background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
    border-radius: 0.5rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.add-btn:hover {
    box-shadow: 0 5px 15px rgba(168, 85, 247, 0.4);
}

.glass-card {
    background: rgba(30, 41, 59, 0.5);
    backdrop-filter: blur(4px);
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
}

.card-header {
    margin-bottom: 1.5rem;
}

.card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #ffffff;
    margin: 0;
}

.stats-table-container {
    overflow-x: auto;
}

.stats-table {
    width: 100%;
    border-collapse: collapse;
}

.stats-table th,
.stats-table td {
    padding: 0.75rem;
    text-align: center;
    border-bottom: 1px solid rgba(51, 65, 85, 0.5);
}

.stats-table th {
    color: #9ca3af;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stats-table td {
    color: #ffffff;
    font-size: 0.875rem;
}

.player-name {
    text-align: left;
    font-weight: 500;
}

.actions-cell {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.edit-btn {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: #ffffff;
    background: rgba(59, 130, 246, 0.3);
    border-radius: 0.375rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.edit-btn:hover {
    background: rgba(59, 130, 246, 0.5);
}

.delete-btn {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: #ffffff;
    background: rgba(239, 68, 68, 0.3);
    border: none;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.delete-btn:hover {
    background: rgba(239, 68, 68, 0.5);
}

.empty-state {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}
</style>
