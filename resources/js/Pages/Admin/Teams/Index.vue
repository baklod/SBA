<template>
    <Head title="Teams - Admin | SBA" />
    <AdminLayout>
        <div class="teams-page">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1 class="page-title">Teams Management</h1>
                    <p class="page-subtitle">Manage all registered teams</p>
                </div>
                <Link
                    :href="route('admin.teams.create')"
                    class="btn btn-primary"
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
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                        ></path>
                    </svg>
                    Add Team
                </Link>
            </div>

            <!-- Filters -->
            <div class="filters-card glass-card">
                <div class="filters-row">
                    <div class="filter-group">
                        <label class="filter-label">Search</label>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search by team name..."
                            class="filter-input"
                        />
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Division</label>
                        <select
                            v-model="selectedDivision"
                            class="filter-select"
                        >
                            <option :value="null">All Divisions</option>
                            <option
                                v-for="division in availableDivisions"
                                :key="division.id"
                                :value="division.id"
                            >
                                {{ division.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="filter-summary">
                    Showing {{ filteredTeams.length }} of {{ teams.length }}
                    teams
                </div>
            </div>

            <!-- Teams Table -->
            <div class="glass-card">
                <div
                    v-if="filteredTeams && filteredTeams.length > 0"
                    class="table-wrapper"
                >
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Team Name</th>
                                <th>Division</th>
                                <th>Players</th>
                                <th>Wins</th>
                                <th>Losses</th>
                                <th>Points</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="team in filteredTeams" :key="team.id">
                                <td class="font-bold">{{ team.name }}</td>
                                <td>
                                    <span
                                        v-if="team.division"
                                        class="division-badge"
                                    >
                                        {{ team.division.name }}
                                    </span>
                                    <span v-else class="no-division">-</span>
                                </td>
                                <td class="text-center">
                                    {{ team.players_count || 0 }}
                                </td>
                                <td class="text-center text-green">
                                    {{ team.wins || 0 }}
                                </td>
                                <td class="text-center text-red">
                                    {{ team.losses || 0 }}
                                </td>
                                <td class="text-center font-bold">
                                    {{ team.points || 0 }}
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <Link
                                            :href="
                                                route(
                                                    'admin.teams.edit',
                                                    team.id,
                                                )
                                            "
                                            class="btn-action btn-edit"
                                        >
                                            Edit
                                        </Link>
                                        <button
                                            class="btn-action btn-delete"
                                            @click="openDeleteModal(team)"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="empty-state">
                    <p v-if="teams.length === 0">
                        No teams available. Create one to get started!
                    </p>
                    <p v-else>No teams match your filters.</p>
                </div>
            </div>
        </div>
        <ConfirmModal
            :show="showDeleteModal"
            title="Delete Team"
            :message="`Are you sure you want to delete '${deleteTarget?.name}'? This action cannot be undone.`"
            confirmText="Delete"
            @confirm="executeDelete"
            @cancel="showDeleteModal = false"
        />
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ConfirmModal from "@/Components/ConfirmModal.vue";

const props = defineProps({
    teams: {
        type: Array,
        default: () => [],
    },
});

// Filters
const searchQuery = ref("");
const selectedDivision = ref(null);

const availableDivisions = computed(() => {
    const divisionMap = new Map();
    for (const team of props.teams) {
        if (!team?.division?.id) continue;
        divisionMap.set(team.division.id, team.division);
    }
    return Array.from(divisionMap.values()).sort((a, b) =>
        String(a.name || "").localeCompare(String(b.name || "")),
    );
});

const filteredTeams = computed(() => {
    let result = props.teams;

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter((team) =>
            String(team.name || "")
                .toLowerCase()
                .includes(query),
        );
    }

    if (selectedDivision.value !== null) {
        result = result.filter((team) => {
            const divisionId = team?.division_id ?? team?.division?.id;
            return divisionId === selectedDivision.value;
        });
    }

    return result;
});

const showDeleteModal = ref(false);
const deleteTarget = ref(null);

const openDeleteModal = (team) => {
    deleteTarget.value = team;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    router.delete(route("admin.teams.destroy", deleteTarget.value.id));
    showDeleteModal.value = false;
};
</script>

<style scoped>
.teams-page {
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

.icon {
    width: 1.25rem;
    height: 1.25rem;
}

/* Filters */
.filters-card {
    border-radius: 0.75rem;
}

.filters-row {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 1rem;
}

@media (min-width: 640px) {
    .filters-row {
        grid-template-columns: repeat(2, 1fr);
    }
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.filter-input,
.filter-select {
    padding: 0.625rem 0.875rem;
    background: var(--cv-surface-2);
    border: 1px solid var(--cv-border-1);
    border-radius: 0.5rem;
    color: #e5e7eb;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.filter-input:focus,
.filter-select:focus {
    outline: none;
    border-color: var(--cv-accent-1);
    box-shadow: 0 0 0 3px rgba(var(--cv-accent-1-rgb), 0.1);
}

.filter-input::placeholder {
    color: #6b7280;
}

.filter-select option {
    background: var(--cv-bg-top);
    color: #e5e7eb;
}

.filter-summary {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--cv-border-1);
    font-size: 0.875rem;
    color: #9ca3af;
}

.glass-card {
    background: rgba(30, 41, 59, 0.5);
    backdrop-filter: blur(4px);
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
}

.table-wrapper {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid rgba(51, 65, 85, 0.5);
}

.data-table th {
    font-size: 0.75rem;
    font-weight: 600;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    background: rgba(51, 65, 85, 0.3);
}

.data-table td {
    color: #e2e8f0;
}

.data-table tbody tr {
    transition: background 0.3s ease;
}

.data-table tbody tr:hover {
    background: rgba(51, 65, 85, 0.3);
}

.font-bold {
    font-weight: 600;
    color: #ffffff;
}

.text-center {
    text-align: center;
}

.text-green {
    color: #e5e7eb;
    font-weight: 700;
}

.text-red {
    color: #e5e7eb;
    font-weight: 700;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 0.375rem;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-edit {
    background: rgba(var(--cv-accent-1-rgb), 0.12);
    color: rgba(var(--cv-accent-1-rgb), 0.95);
    border: 1px solid rgba(var(--cv-accent-1-rgb), 0.28);
}

.btn-edit:hover {
    background: rgba(var(--cv-accent-1-rgb), 0.18);
}

.btn-delete {
    background: rgba(239, 68, 68, 0.2);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.btn-delete:hover {
    background: rgba(239, 68, 68, 0.3);
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #6b7280;
}

.division-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: rgba(var(--cv-accent-1-rgb), 0.12);
    color: rgba(var(--cv-accent-1-rgb), 0.95);
    border: 1px solid rgba(var(--cv-accent-1-rgb), 0.28);
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.no-division {
    color: #6b7280;
}
</style>
