<template>
    <Head title="Matches - Committee | SBA" />
    <CommitteeLayout>
        <div class="matches-page">
            <!-- Header -->
            <div class="header">
                <h1 class="page-title">Matches</h1>
                <p class="page-subtitle">View and manage match scores</p>
            </div>

            <!-- Matches List -->
            <div class="glass-card">
                <div class="matches-list" v-if="matches && matches.length > 0">
                    <div
                        v-for="match in matches"
                        :key="match.id"
                        class="match-card"
                    >
                        <div class="match-info">
                            <div class="match-teams">
                                <span class="team-name">{{
                                    match.team_a?.name
                                }}</span>
                                <span
                                    class="score"
                                    v-if="match.team_a_score !== null"
                                    >{{ match.team_a_score }}</span
                                >
                                <span class="vs-text">vs</span>
                                <span
                                    class="score"
                                    v-if="match.team_b_score !== null"
                                    >{{ match.team_b_score }}</span
                                >
                                <span class="team-name">{{
                                    match.team_b?.name
                                }}</span>
                            </div>
                            <div class="match-meta">
                                <span class="match-date">{{
                                    formatDate(match.match_date)
                                }}</span>
                                <span v-if="match.venue" class="match-venue">{{
                                    match.venue
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
                            <Link
                                :href="
                                    route('committee.matches.show', match.id)
                                "
                                class="btn btn-secondary"
                            >
                                View
                            </Link>
                            <Link
                                v-if="
                                    match.status === 'ongoing' ||
                                    match.status === 'paused'
                                "
                                :href="
                                    route('committee.matches.process', match.id)
                                "
                                class="btn btn-success"
                            >
                                Process
                            </Link>
                            <span v-else class="btn btn-success btn-disabled">
                                Process
                            </span>
                            <Link
                                :href="
                                    route('committee.matches.edit', match.id)
                                "
                                class="btn btn-primary"
                            >
                                Update
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
        type: Array,
        default: () => [],
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
        case "paused":
            return "status-paused";
        case "finished":
            return "status-finished";
        default:
            return "status-default";
    }
};
</script>

<style scoped>
.matches-page {
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
    flex-wrap: wrap;
    gap: 1rem;
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

.score {
    font-weight: 700;
    color: #a855f7;
    font-size: 1.25rem;
}

.vs-text {
    color: #6b7280;
    font-size: 0.75rem;
    margin: 0 0.25rem;
}

.match-meta {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.match-date {
    font-size: 0.875rem;
    color: #9ca3af;
}

.match-venue {
    font-size: 0.875rem;
    color: #6b7280;
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

.status-paused {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
    border-color: rgba(251, 191, 36, 0.3);
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
    gap: 0.75rem;
}

.btn {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
    color: #ffffff;
}

.btn-primary:hover {
    box-shadow: 0 5px 15px rgba(168, 85, 247, 0.4);
}

.btn-secondary {
    background: rgba(51, 65, 85, 0.5);
    border: 1px solid rgba(71, 85, 105, 0.5);
    color: #d1d5db;
}

.btn-secondary:hover {
    border-color: rgba(168, 85, 247, 0.3);
    color: #ffffff;
}

.btn-success {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    color: #ffffff;
}

.btn-success:hover {
    box-shadow: 0 5px 15px rgba(34, 197, 94, 0.4);
}

.btn-disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

.empty-state {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}
</style>
