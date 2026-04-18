<template>
    <Head title="Statistics - Committee | SBA" />
    <CommitteeLayout>
        <div class="stats-page">
            <!-- Header -->
            <div class="header">
                <h1 class="page-title">Statistics Management</h1>
                <p class="page-subtitle">
                    Enter and manage player statistics for matches
                </p>
            </div>

            <!-- Matches List -->
            <div class="glass-card">
                <div class="card-header">
                    <h2 class="card-title">Matches</h2>
                </div>

                <div
                    class="matches-list"
                    v-if="matches.data && matches.data.length > 0"
                >
                    <div
                        v-for="match in matches.data"
                        :key="match.id"
                        class="match-card"
                    >
                        <div class="match-info">
                            <div class="match-teams">
                                <span class="team-name">{{
                                    match.team_a?.name
                                }}</span>
                                <span class="vs-text">vs</span>
                                <span class="team-name">{{
                                    match.team_b?.name
                                }}</span>
                            </div>
                            <div class="match-meta">
                                <span class="match-date">{{
                                    formatDate(match.match_date)
                                }}</span>
                                <span
                                    :class="[
                                        'status-badge',
                                        getStatusClass(match.status),
                                    ]"
                                >
                                    {{ match.status }}
                                </span>
                            </div>
                        </div>
                        <div class="match-actions">
                            <span class="stats-count"
                                >{{ match.player_stats_count }} stats</span
                            >
                            <Link
                                :href="
                                    route(
                                        'committee.player-stats.index',
                                        match.id,
                                    )
                                "
                                class="action-btn"
                            >
                                Manage Stats
                            </Link>
                        </div>
                    </div>
                </div>

                <div v-else class="empty-state">
                    <p>No matches found</p>
                </div>
            </div>
        </div>
    </CommitteeLayout>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import CommitteeLayout from "@/Layouts/CommitteeLayout.vue";

defineProps({
    matches: {
        type: Object,
        default: () => ({ data: [] }),
    },
});

const formatDate = (date) => {
    if (!date) return "TBD";
    return new Date(date).toLocaleDateString("en-US", {
        month: "short",
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
.stats-page {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.header {
    display: flex;
    flex-direction: column;
}

.page-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
}

.page-subtitle {
    color: #9ca3af;
    margin: 0.25rem 0 0 0;
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

.matches-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.match-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    background: rgba(51, 65, 85, 0.3);
    border-radius: 0.75rem;
    border: 1px solid rgba(71, 85, 105, 0.3);
    transition: all 0.3s ease;
}

.match-card:hover {
    border-color: rgba(168, 85, 247, 0.3);
}

.match-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.match-teams {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.team-name {
    font-weight: 500;
    color: #ffffff;
}

.vs-text {
    color: #6b7280;
    font-size: 0.75rem;
}

.match-meta {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.match-date {
    font-size: 0.875rem;
    color: #9ca3af;
}

.status-badge {
    padding: 0.25rem 0.5rem;
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
    align-items: center;
    gap: 1rem;
}

.stats-count {
    font-size: 0.875rem;
    color: #9ca3af;
}

.action-btn {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #ffffff;
    background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
    border-radius: 0.5rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.action-btn:hover {
    box-shadow: 0 5px 15px rgba(168, 85, 247, 0.4);
}

.empty-state {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}
</style>
