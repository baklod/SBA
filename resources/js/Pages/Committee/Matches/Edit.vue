<template>
    <CommitteeLayout>
        <div class="edit-page">
            <!-- Header -->
            <div class="header">
                <Link
                    :href="route('committee.matches.index')"
                    class="back-link"
                >
                    <svg
                        class="icon"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        ></path>
                    </svg>
                    Back to Matches
                </Link>
                <h1 class="page-title">Update Match Status</h1>
            </div>

            <!-- Match Info -->
            <div class="glass-card match-info">
                <div class="teams-display">
                    <span class="team-name">{{ match.team_a?.name }}</span>
                    <span class="vs-text">VS</span>
                    <span class="team-name">{{ match.team_b?.name }}</span>
                </div>
                <div class="match-meta">
                    <span>{{ formatDate(match.match_date) }}</span>
                    <span v-if="match.venue">• {{ match.venue }}</span>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="glass-card">
                <form @submit.prevent="submit" class="form">
                    <!-- Current Score Display (Read Only) -->
                    <div class="score-display">
                        <div class="score-team">
                            <span class="score-team-name">{{
                                match.team_a?.name
                            }}</span>
                            <span class="score-value">{{
                                match.team_a_score ?? 0
                            }}</span>
                        </div>
                        <span class="score-separator">-</span>
                        <div class="score-team">
                            <span class="score-team-name">{{
                                match.team_b?.name
                            }}</span>
                            <span class="score-value">{{
                                match.team_b_score ?? 0
                            }}</span>
                        </div>
                    </div>
                    <p class="score-note">
                        Use the "Process" button to update scores during live
                        games
                    </p>

                    <!-- Status -->
                    <div class="form-group">
                        <label class="form-label">Match Status</label>
                        <select v-model="form.status" class="form-input">
                            <option value="upcoming">Upcoming</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="paused">Paused</option>
                            <option value="finished">Finished</option>
                        </select>
                        <span v-if="form.errors.status" class="form-error">{{
                            form.errors.status
                        }}</span>
                    </div>

                    <!-- Actions -->
                    <div class="form-actions">
                        <Link
                            :href="route('committee.matches.index')"
                            class="btn btn-secondary"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="form.processing"
                        >
                            {{
                                form.processing ? "Saving..." : "Update Status"
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </CommitteeLayout>
</template>

<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import CommitteeLayout from "@/Layouts/CommitteeLayout.vue";

const props = defineProps({
    match: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    team_a_id: props.match.team_a_id,
    team_b_id: props.match.team_b_id,
    match_date: props.match.match_date,
    venue: props.match.venue,
    team_a_score: props.match.team_a_score ?? 0,
    team_b_score: props.match.team_b_score ?? 0,
    status: props.match.status,
});

const submit = () => {
    form.put(route("committee.matches.update", props.match.id));
};

const formatDate = (date) => {
    if (!date) return "TBD";
    return new Date(date).toLocaleDateString("en-US", {
        weekday: "long",
        month: "long",
        day: "numeric",
        year: "numeric",
    });
};
</script>

<style scoped>
.edit-page {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.header {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.back-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #9ca3af;
    text-decoration: none;
    font-size: 0.875rem;
    transition: color 0.3s ease;
}

.back-link:hover {
    color: #a855f7;
}

.page-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
}

.icon {
    width: 1.25rem;
    height: 1.25rem;
}

.glass-card {
    background: rgba(30, 41, 59, 0.5);
    backdrop-filter: blur(4px);
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
}

.match-info {
    text-align: center;
}

.teams-display {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.team-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: #ffffff;
}

.vs-text {
    padding: 0.25rem 0.75rem;
    background: rgba(168, 85, 247, 0.2);
    border-radius: 0.5rem;
    color: #c084fc;
    font-weight: 600;
    font-size: 0.875rem;
}

.match-meta {
    color: #9ca3af;
    font-size: 0.875rem;
}

.form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.score-display {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 2rem;
    padding: 1.5rem;
    background: rgba(15, 23, 42, 0.6);
    border-radius: 0.75rem;
    border: 1px solid rgba(71, 85, 105, 0.3);
}

.score-team {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.score-team-name {
    font-size: 0.875rem;
    color: #9ca3af;
    font-weight: 500;
}

.score-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: #ffffff;
    background: linear-gradient(135deg, #06b6d4 0%, #a855f7 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.score-separator {
    font-size: 2rem;
    font-weight: 700;
    color: #475569;
}

.score-note {
    text-align: center;
    font-size: 0.75rem;
    color: #6b7280;
    margin: 0;
    font-style: italic;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

@media (max-width: 640px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #d1d5db;
}

.form-input {
    padding: 0.75rem 1rem;
    background: rgba(30, 41, 59, 0.8);
    border: 1px solid rgba(71, 85, 105, 0.5);
    border-radius: 0.5rem;
    color: #ffffff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: rgba(168, 85, 247, 0.5);
    box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.1);
}

.form-error {
    font-size: 0.75rem;
    color: #f87171;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(51, 65, 85, 0.5);
}

.btn {
    padding: 0.75rem 1.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: 0.5rem;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary {
    background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
    color: #ffffff;
}

.btn-primary:hover:not(:disabled) {
    box-shadow: 0 5px 15px rgba(168, 85, 247, 0.4);
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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
</style>
