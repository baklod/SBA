<template>
    <Head title="Edit Player Stats - Committee | SBA" />
    <CommitteeLayout>
        <div class="edit-stats-page">
            <!-- Header -->
            <div class="header">
                <Link
                    :href="route('committee.player-stats.index', match.id)"
                    class="back-btn"
                >
                    ← Back to Stats
                </Link>
                <h1 class="page-title">Edit Player Stats</h1>
                <p class="page-subtitle">
                    {{ stat.player?.name }} - {{ match.team_a?.name }} vs
                    {{ match.team_b?.name }}
                </p>
            </div>

            <!-- Form -->
            <div class="glass-card">
                <form @submit.prevent="submit">
                    <!-- Player Info (Read Only) -->
                    <div class="form-group">
                        <label class="form-label">Player</label>
                        <div class="player-display">
                            {{ stat.player?.name }} (#{{
                                stat.player?.jersey_number
                            }})
                        </div>
                    </div>

                    <!-- Stats Grid -->
                    <div class="stats-grid">
                        <div class="form-group">
                            <label class="form-label">Points</label>
                            <input
                                type="number"
                                v-model="form.points"
                                class="form-input"
                                min="0"
                                required
                            />
                            <span
                                v-if="form.errors.points"
                                class="error-text"
                                >{{ form.errors.points }}</span
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">Assists</label>
                            <input
                                type="number"
                                v-model="form.assists"
                                class="form-input"
                                min="0"
                                required
                            />
                            <span
                                v-if="form.errors.assists"
                                class="error-text"
                                >{{ form.errors.assists }}</span
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">Rebounds</label>
                            <input
                                type="number"
                                v-model="form.rebounds"
                                class="form-input"
                                min="0"
                                required
                            />
                            <span
                                v-if="form.errors.rebounds"
                                class="error-text"
                                >{{ form.errors.rebounds }}</span
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">Blocks</label>
                            <input
                                type="number"
                                v-model="form.blocks"
                                class="form-input"
                                min="0"
                            />
                            <span
                                v-if="form.errors.blocks"
                                class="error-text"
                                >{{ form.errors.blocks }}</span
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">Steals</label>
                            <input
                                type="number"
                                v-model="form.steals"
                                class="form-input"
                                min="0"
                            />
                            <span
                                v-if="form.errors.steals"
                                class="error-text"
                                >{{ form.errors.steals }}</span
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">Fouls</label>
                            <input
                                type="number"
                                v-model="form.fouls"
                                class="form-input"
                                min="0"
                                max="6"
                            />
                            <span v-if="form.errors.fouls" class="error-text">{{
                                form.errors.fouls
                            }}</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-actions">
                        <Link
                            :href="
                                route('committee.player-stats.index', match.id)
                            "
                            class="cancel-btn"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            class="submit-btn"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? "Saving..." : "Update Stats" }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </CommitteeLayout>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import CommitteeLayout from "@/Layouts/CommitteeLayout.vue";

const props = defineProps({
    stat: {
        type: Object,
        required: true,
    },
    match: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    points: props.stat.points || 0,
    assists: props.stat.assists || 0,
    rebounds: props.stat.rebounds || 0,
    blocks: props.stat.blocks || 0,
    steals: props.stat.steals || 0,
    fouls: props.stat.fouls || 0,
});

const submit = () => {
    form.put(
        route("committee.player-stats.update", [props.match.id, props.stat.id]),
    );
};
</script>

<style scoped>
.edit-stats-page {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.header {
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

.glass-card {
    background: rgba(30, 41, 59, 0.5);
    backdrop-filter: blur(4px);
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #d1d5db;
    margin-bottom: 0.5rem;
}

.player-display {
    padding: 0.75rem 1rem;
    background: rgba(51, 65, 85, 0.3);
    border: 1px solid rgba(71, 85, 105, 0.3);
    border-radius: 0.5rem;
    color: #ffffff;
    font-size: 0.875rem;
}

.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    background: rgba(51, 65, 85, 0.5);
    border: 1px solid rgba(71, 85, 105, 0.5);
    border-radius: 0.5rem;
    color: #ffffff;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: #a855f7;
    box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.2);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.error-text {
    color: #ef4444;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
}

.cancel-btn {
    padding: 0.75rem 1.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #9ca3af;
    background: transparent;
    border: 1px solid rgba(71, 85, 105, 0.5);
    border-radius: 0.5rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.cancel-btn:hover {
    border-color: #9ca3af;
    color: #ffffff;
}

.submit-btn {
    padding: 0.75rem 1.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #ffffff;
    background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.submit-btn:hover:not(:disabled) {
    box-shadow: 0 5px 15px rgba(168, 85, 247, 0.4);
}

.submit-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
