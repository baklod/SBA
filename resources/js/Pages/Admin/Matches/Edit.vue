<template>
    <Head title="Edit Match - Admin | SBA" />
    <AdminLayout>
        <div class="edit-page">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1 class="page-title">Edit Match</h1>
                    <p class="page-subtitle">Update match details</p>
                </div>
                <Link
                    :href="route('admin.matches.index')"
                    class="btn btn-secondary"
                >
                    ← Back to Matches
                </Link>
            </div>

            <!-- Form -->
            <div class="glass-card">
                <form @submit.prevent="submit" class="form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="team_a_id" class="form-label"
                                >Team A *</label
                            >
                            <select
                                id="team_a_id"
                                v-model="form.team_a_id"
                                class="form-select"
                                required
                            >
                                <option value="">Select team</option>
                                <option
                                    v-for="team in teams"
                                    :key="team.id"
                                    :value="team.id"
                                >
                                    {{ team.name }}
                                </option>
                            </select>
                            <span
                                v-if="form.errors.team_a_id"
                                class="form-error"
                                >{{ form.errors.team_a_id }}</span
                            >
                        </div>

                        <div class="form-group">
                            <label for="team_b_id" class="form-label"
                                >Team B *</label
                            >
                            <select
                                id="team_b_id"
                                v-model="form.team_b_id"
                                class="form-select"
                                required
                            >
                                <option value="">Select team</option>
                                <option
                                    v-for="team in teams"
                                    :key="team.id"
                                    :value="team.id"
                                    :disabled="team.id === form.team_a_id"
                                >
                                    {{ team.name }}
                                </option>
                            </select>
                            <span
                                v-if="form.errors.team_b_id"
                                class="form-error"
                                >{{ form.errors.team_b_id }}</span
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="match_date" class="form-label"
                                >Match Date *</label
                            >
                            <input
                                type="date"
                                id="match_date"
                                v-model="form.match_date"
                                class="form-input"
                                required
                            />
                            <span
                                v-if="form.errors.match_date"
                                class="form-error"
                                >{{ form.errors.match_date }}</span
                            >
                        </div>

                        <div class="form-group">
                            <label for="match_time" class="form-label"
                                >Match Time</label
                            >
                            <input
                                type="time"
                                id="match_time"
                                v-model="form.match_time"
                                class="form-input"
                            />
                            <span
                                v-if="form.errors.match_time"
                                class="form-error"
                                >{{ form.errors.match_time }}</span
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="venue" class="form-label">Venue</label>
                        <input
                            type="text"
                            id="venue"
                            v-model="form.venue"
                            class="form-input"
                            placeholder="Enter venue location"
                        />
                        <span v-if="form.errors.venue" class="form-error">{{
                            form.errors.venue
                        }}</span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="team_a_score" class="form-label"
                                >Team A Score</label
                            >
                            <input
                                type="number"
                                id="team_a_score"
                                v-model="form.team_a_score"
                                class="form-input"
                                min="0"
                                placeholder="0"
                            />
                            <span
                                v-if="form.errors.team_a_score"
                                class="form-error"
                                >{{ form.errors.team_a_score }}</span
                            >
                        </div>

                        <div class="form-group">
                            <label for="team_b_score" class="form-label"
                                >Team B Score</label
                            >
                            <input
                                type="number"
                                id="team_b_score"
                                v-model="form.team_b_score"
                                class="form-input"
                                min="0"
                                placeholder="0"
                            />
                            <span
                                v-if="form.errors.team_b_score"
                                class="form-error"
                                >{{ form.errors.team_b_score }}</span
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">Status *</label>
                        <select
                            id="status"
                            v-model="form.status"
                            class="form-select"
                            required
                        >
                            <option value="upcoming">Upcoming</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="finished">Finished</option>
                        </select>
                        <span v-if="form.errors.status" class="form-error">{{
                            form.errors.status
                        }}</span>
                    </div>

                    <div class="form-actions">
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="form.processing"
                        >
                            {{
                                form.processing ? "Updating..." : "Update Match"
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    match: {
        type: Object,
        required: true,
    },
    teams: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    team_a_id: props.match.team_a_id,
    team_b_id: props.match.team_b_id,
    match_date: props.match.match_date,
    match_time: props.match.match_time || "",
    venue: props.match.venue || "",
    team_a_score: props.match.team_a_score,
    team_b_score: props.match.team_b_score,
    status: props.match.status,
});

const submit = () => {
    form.put(route("admin.matches.update", props.match.id));
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
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.header-left {
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

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border-radius: 0.5rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(
        135deg,
        var(--cv-accent-1) 0%,
        var(--cv-accent-2) 100%
    );
    color: #ffffff;
}

.btn-primary:hover {
    box-shadow: 0 10px 25px rgba(var(--cv-accent-1-rgb), 0.25);
}

.btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-secondary {
    background: rgba(51, 65, 85, 0.5);
    color: #d1d5db;
    border: 1px solid rgba(71, 85, 105, 0.5);
}

.btn-secondary:hover {
    background: rgba(51, 65, 85, 0.7);
    color: #ffffff;
}

.glass-card {
    background: rgba(30, 41, 59, 0.5);
    backdrop-filter: blur(4px);
    border-radius: 0.75rem;
    padding: 2rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
    max-width: 700px;
}

.form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

@media (max-width: 640px) {
    .form-row {
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

.form-input,
.form-select {
    padding: 0.75rem 1rem;
    background: rgba(51, 65, 85, 0.5);
    border: 1px solid rgba(71, 85, 105, 0.5);
    border-radius: 0.5rem;
    color: #ffffff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: var(--cv-accent-1);
    box-shadow: 0 0 0 3px rgba(var(--cv-accent-1-rgb), 0.1);
}

.form-input::placeholder {
    color: #6b7280;
}

.form-select option {
    background: #1e293b;
    color: #ffffff;
}

.form-select option:disabled {
    color: #6b7280;
}

.form-error {
    font-size: 0.75rem;
    color: #f87171;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 1rem;
}
</style>
