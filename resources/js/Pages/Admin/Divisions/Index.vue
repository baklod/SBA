<template>
    <Head title="Divisions - Admin | SBA" />
    <AdminLayout>
        <div class="index-page">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1 class="page-title">Divisions</h1>
                    <p class="page-subtitle">Manage league divisions</p>
                </div>
                <Link
                    :href="route('admin.divisions.create')"
                    class="btn btn-primary"
                >
                    + Add Division
                </Link>
            </div>

            <!-- Divisions Table -->
            <div class="glass-card">
                <div v-if="divisions.length === 0" class="empty-state">
                    <p>No divisions found. Create your first division!</p>
                </div>

                <table v-else class="data-table">
                    <thead>
                        <tr>
                            <th>Division Name</th>
                            <th>Description</th>
                            <th>Teams</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="division in divisions" :key="division.id">
                            <td>
                                <span class="division-name">{{
                                    division.name
                                }}</span>
                            </td>
                            <td>
                                <span class="description">{{
                                    division.description || "-"
                                }}</span>
                            </td>
                            <td>
                                <span class="team-count">{{
                                    division.teams_count
                                }}</span>
                            </td>
                            <td>
                                <div class="actions">
                                    <Link
                                        :href="
                                            route(
                                                'admin.divisions.edit',
                                                division.id,
                                            )
                                        "
                                        class="btn btn-sm btn-secondary"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        @click="openDeleteModal(division)"
                                        class="btn btn-sm btn-danger"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <ConfirmModal
            :show="showDeleteModal"
            title="Delete Division"
            :message="`Are you sure you want to delete '${deleteTarget?.name}'? This action cannot be undone.`"
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
    divisions: {
        type: Array,
        default: () => [],
    },
});

const showDeleteModal = ref(false);
const deleteTarget = ref(null);

const openDeleteModal = (division) => {
    deleteTarget.value = division;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    router.delete(route("admin.divisions.destroy", deleteTarget.value.id));
    showDeleteModal.value = false;
};
</script>

<style scoped>
.index-page {
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

.btn-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
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

.btn-secondary {
    background: rgba(51, 65, 85, 0.5);
    color: #d1d5db;
    border: 1px solid rgba(71, 85, 105, 0.5);
}

.btn-secondary:hover {
    background: rgba(51, 65, 85, 0.7);
    color: #ffffff;
}

.btn-danger {
    background: rgba(239, 68, 68, 0.2);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.btn-danger:hover {
    background: rgba(239, 68, 68, 0.3);
}

.glass-card {
    background: rgba(30, 41, 59, 0.5);
    backdrop-filter: blur(4px);
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
    overflow-x: auto;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #9ca3af;
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
    font-weight: 600;
    color: #9ca3af;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.data-table td {
    color: #ffffff;
}

.data-table tbody tr:hover {
    background: rgba(51, 65, 85, 0.3);
}

.division-name {
    font-weight: 600;
    color: var(--cv-accent-1);
}

.description {
    color: #9ca3af;
}

.team-count {
    font-weight: 600;
}

.actions {
    display: flex;
    gap: 0.5rem;
}

@media (max-width: 768px) {
    .data-table th:nth-child(2),
    .data-table td:nth-child(2) {
        display: none;
    }
}
</style>
