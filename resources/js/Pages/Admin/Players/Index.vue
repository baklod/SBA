<template>
    <Head title="Players - Admin | SBA" />
    <AdminLayout>
        <div class="players-page">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1 class="page-title">Players Management</h1>
                    <p class="page-subtitle">Manage all registered players</p>
                </div>
                <div class="header-actions">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        :disabled="selectedTeam === null"
                        @click="exportPlayersPdf"
                        title="Select a team to export"
                    >
                        Export PDF
                    </button>
                    <Link
                        :href="route('admin.players.create')"
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
                        Add Player
                    </Link>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-card glass-card">
                <div class="filters-row">
                    <div class="filter-group">
                        <label class="filter-label">Search</label>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search by name..."
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
                                v-for="division in divisions"
                                :key="division.id"
                                :value="division.id"
                            >
                                {{ division.name }}
                            </option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Team</label>
                        <select v-model="selectedTeam" class="filter-select">
                            <option :value="null">All Teams</option>
                            <option
                                v-for="team in filteredTeams"
                                :key="team.id"
                                :value="team.id"
                            >
                                {{ team.name }}
                            </option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Position</label>
                        <select
                            v-model="selectedPosition"
                            class="filter-select"
                        >
                            <option :value="null">All Positions</option>
                            <option value="PG">PG</option>
                            <option value="SG">SG</option>
                            <option value="SF">SF</option>
                            <option value="PF">PF</option>
                            <option value="C">C</option>
                        </select>
                    </div>
                </div>
                <div class="filter-summary">
                    <template v-if="filteredPlayers.length > 0">
                        Showing {{ displayStart }}-{{ displayEnd }} of
                        {{ filteredPlayers.length }}
                        <span v-if="filteredPlayers.length !== players.length">
                            ({{ players.length }} total)
                        </span>
                        players
                    </template>
                    <template v-else>
                        Showing 0 of {{ players.length }} players
                    </template>
                </div>
            </div>

            <!-- Players Table -->
            <div class="glass-card">
                <div
                    v-if="filteredPlayers && filteredPlayers.length > 0"
                    class="table-wrapper"
                >
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Team</th>
                                <th>Jersey #</th>
                                <th>Position</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="player in paginatedPlayers"
                                :key="player.id"
                            >
                                <td>
                                    <div class="player-avatar">
                                        <img
                                            v-if="player.photo"
                                            :src="
                                                resolvePlayerPhotoUrl(
                                                    player.photo,
                                                )
                                            "
                                            :alt="player.name"
                                            class="avatar-image"
                                        />
                                        <span v-else class="avatar-placeholder">
                                            {{
                                                player.name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </span>
                                    </div>
                                </td>
                                <td class="font-bold">{{ player.name }}</td>
                                <td>{{ player.team?.name || "No Team" }}</td>
                                <td class="text-center">
                                    {{ player.jersey_number || "-" }}
                                </td>
                                <td>{{ player.position || "-" }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <Link
                                            :href="
                                                route(
                                                    'admin.players.edit',
                                                    player.id,
                                                )
                                            "
                                            class="btn-action btn-edit"
                                        >
                                            Edit
                                        </Link>
                                        <button
                                            class="btn-action btn-delete"
                                            @click="openDeleteModal(player)"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="totalPages > 1" class="pagination">
                        <button
                            type="button"
                            class="btn-action pagination-btn"
                            :disabled="currentPage === 1"
                            @click="prevPage"
                        >
                            Previous
                        </button>

                        <div class="pagination-info">
                            Page {{ currentPage }} of {{ totalPages }}
                        </div>

                        <button
                            type="button"
                            class="btn-action pagination-btn"
                            :disabled="currentPage === totalPages"
                            @click="nextPage"
                        >
                            Next
                        </button>
                    </div>
                </div>
                <div v-else class="empty-state">
                    <p v-if="players.length === 0">
                        No players registered yet. Add one to get started!
                    </p>
                    <p v-else>No players match your filters.</p>
                </div>
            </div>
        </div>
        <ConfirmModal
            :show="showDeleteModal"
            title="Delete Player"
            :message="`Are you sure you want to delete '${deleteTarget?.name}'? This action cannot be undone.`"
            confirmText="Delete"
            @confirm="executeDelete"
            @cancel="showDeleteModal = false"
        />
    </AdminLayout>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ConfirmModal from "@/Components/ConfirmModal.vue";

const props = defineProps({
    players: {
        type: Array,
        default: () => [],
    },
    teams: {
        type: Array,
        default: () => [],
    },
    divisions: {
        type: Array,
        default: () => [],
    },
});

// Filter state
const searchQuery = ref("");
const selectedDivision = ref(null);
const selectedTeam = ref(null);
const selectedPosition = ref(null);

// Pagination
const PAGE_SIZE = 10;
const currentPage = ref(1);

// Filter teams by selected division
const filteredTeams = computed(() => {
    if (selectedDivision.value === null) {
        return props.teams;
    }
    return props.teams.filter(
        (team) => team.division_id === selectedDivision.value,
    );
});

watch(selectedDivision, () => {
    selectedTeam.value = null;
    currentPage.value = 1;
});

watch([searchQuery, selectedTeam, selectedPosition], () => {
    currentPage.value = 1;
});

// Filter players
const filteredPlayers = computed(() => {
    let result = props.players;

    // Filter by search query
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter((player) =>
            player.name.toLowerCase().includes(query),
        );
    }

    // Filter by division
    if (selectedDivision.value !== null) {
        result = result.filter(
            (player) => player.team?.division_id === selectedDivision.value,
        );
    }

    // Filter by team
    if (selectedTeam.value !== null) {
        result = result.filter(
            (player) => player.team_id === selectedTeam.value,
        );
    }

    // Filter by position
    if (selectedPosition.value !== null) {
        result = result.filter(
            (player) => player.position === selectedPosition.value,
        );
    }

    return result;
});

const totalPages = computed(() =>
    Math.max(1, Math.ceil(filteredPlayers.value.length / PAGE_SIZE)),
);

watch(totalPages, (newTotal) => {
    if (currentPage.value > newTotal) {
        currentPage.value = newTotal;
    }
});

const paginatedPlayers = computed(() => {
    const startIndex = (currentPage.value - 1) * PAGE_SIZE;
    return filteredPlayers.value.slice(startIndex, startIndex + PAGE_SIZE);
});

const displayStart = computed(() => {
    if (filteredPlayers.value.length === 0) return 0;
    return (currentPage.value - 1) * PAGE_SIZE + 1;
});

const displayEnd = computed(() => {
    if (filteredPlayers.value.length === 0) return 0;
    return Math.min(
        currentPage.value * PAGE_SIZE,
        filteredPlayers.value.length,
    );
});

const goToPage = (pageNumber) => {
    const target = Math.min(Math.max(pageNumber, 1), totalPages.value);
    currentPage.value = target;
};

const nextPage = () => {
    goToPage(currentPage.value + 1);
};

const prevPage = () => {
    goToPage(currentPage.value - 1);
};

const showDeleteModal = ref(false);
const deleteTarget = ref(null);

const openDeleteModal = (player) => {
    deleteTarget.value = player;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    router.delete(route("admin.players.destroy", deleteTarget.value.id));
    showDeleteModal.value = false;
};

const exportPlayersPdf = () => {
    if (selectedTeam.value === null) return;
    const url = route("admin.players.export-pdf", {
        team_id: selectedTeam.value,
    });
    window.open(url, "_blank", "noopener,noreferrer");
};

const resolvePlayerPhotoUrl = (photoPath) => {
    if (!photoPath || typeof photoPath !== "string") return null;
    if (photoPath.startsWith("http://") || photoPath.startsWith("https://")) {
        return photoPath;
    }
    if (photoPath.startsWith("/")) return photoPath;
    return `/storage/${photoPath}`;
};
</script>

<style scoped>
.players-page {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
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

.header-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
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

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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

@media (min-width: 1024px) {
    .filters-row {
        grid-template-columns: repeat(4, 1fr);
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

/* Table */
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

/* Pagination */
.pagination {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--cv-border-1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
}

.pagination-info {
    font-size: 0.875rem;
    color: #9ca3af;
}

.pagination-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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

/* Player Avatar */
.player-avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(
        135deg,
        var(--cv-accent-1) 0%,
        var(--cv-accent-2) 100%
    );
    flex-shrink: 0;
}

.avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    font-weight: 700;
    font-size: 1rem;
    color: #ffffff;
}

.font-bold {
    font-weight: 600;
    color: #ffffff;
}

.text-center {
    text-align: center;
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
</style>
