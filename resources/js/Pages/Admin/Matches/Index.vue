<template>
    <Head title="Matches - Admin | SBA" />
    <AdminLayout>
        <div class="matches-page">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1 class="page-title">Matches Management</h1>
                    <p class="page-subtitle">
                        Schedule and manage basketball matches
                    </p>
                </div>
                <Link
                    :href="route('admin.matches.create')"
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
                    Schedule Match
                </Link>
            </div>

            <!-- Matches Table -->
            <div class="glass-card">
                <div v-if="matches && matches.length > 0" class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Teams</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Venue</th>
                                <th>Score</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="match in matches" :key="match.id">
                                <td class="font-bold">
                                    {{ match.team_a?.name }} vs
                                    {{ match.team_b?.name }}
                                </td>
                                <td>{{ formatDate(match.match_date) }}</td>
                                <td>{{ match.match_time || "TBD" }}</td>
                                <td>{{ match.venue || "-" }}</td>
                                <td class="score-cell">
                                    <span
                                        v-if="
                                            match.team_a_score !== null &&
                                            match.team_b_score !== null
                                        "
                                        class="score"
                                    >
                                        {{ match.team_a_score }} -
                                        {{ match.team_b_score }}
                                    </span>
                                    <span v-else class="no-score">-</span>
                                </td>
                                <td>
                                    <span
                                        :class="[
                                            'status-badge',
                                            getStatusClass(match.status),
                                        ]"
                                    >
                                        {{ match.status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <Link
                                            :href="
                                                route(
                                                    'admin.matches.edit',
                                                    match.id,
                                                )
                                            "
                                            class="btn-action btn-edit"
                                        >
                                            Edit
                                        </Link>
                                        <button
                                            class="btn-action btn-delete"
                                            @click="openDeleteModal(match)"
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
                    <p>No matches scheduled yet. Create one to get started!</p>
                </div>
            </div>
        </div>
        <ConfirmModal
            :show="showDeleteModal"
            title="Delete Match"
            :message="`Are you sure you want to delete this match? This action cannot be undone.`"
            confirmText="Delete"
            @confirm="executeDelete"
            @cancel="showDeleteModal = false"
        />
    </AdminLayout>
</template>

<script setup>
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ConfirmModal from "@/Components/ConfirmModal.vue";

defineProps({
    matches: {
        type: Array,
        default: () => [],
    },
});

const showDeleteModal = ref(false);
const deleteTarget = ref(null);

const openDeleteModal = (match) => {
    deleteTarget.value = match;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    router.delete(route("admin.matches.destroy", deleteTarget.value.id));
    showDeleteModal.value = false;
};

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
.matches-page {
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

.score-cell {
    text-align: center;
}

.score {
    font-weight: 700;
    color: rgba(var(--cv-accent-1-rgb), 0.95);
    font-size: 1.125rem;
}

.no-score {
    color: #6b7280;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 9999px;
    text-transform: capitalize;
}

.status-upcoming {
    background: rgba(var(--cv-accent-1-rgb), 0.12);
    color: rgba(var(--cv-accent-1-rgb), 0.95);
}

.status-ongoing {
    background: rgba(34, 197, 94, 0.2);
    color: #4ade80;
}

.status-finished {
    background: rgba(100, 116, 139, 0.2);
    color: #94a3b8;
}

.status-default {
    background: rgba(100, 116, 139, 0.2);
    color: #94a3b8;
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
