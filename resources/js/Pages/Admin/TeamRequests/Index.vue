<template>
    <Head title="Requesting Teams - Admin | SBA" />
    <AdminLayout>
        <div class="requests-page">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1 class="page-title">Requesting Teams</h1>
                    <p class="page-subtitle">
                        Review team registration requests from the public site
                    </p>
                </div>
            </div>

            <!-- Requests Table -->
            <div class="glass-card">
                <div
                    v-if="requests && requests.length > 0"
                    class="table-wrapper"
                >
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Team Name</th>
                                <th>Division</th>
                                <th>Logo</th>
                                <th>Requested</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="req in requests" :key="req.id">
                                <td class="font-bold">{{ req.name }}</td>
                                <td>
                                    <span
                                        v-if="req.division"
                                        class="division-badge"
                                    >
                                        {{ req.division.name }}
                                    </span>
                                    <span v-else class="no-division">-</span>
                                </td>
                                <td>
                                    <div class="logo-cell">
                                        <img
                                            v-if="req.logo"
                                            :src="req.logo"
                                            :alt="req.name + ' logo'"
                                            class="logo-img"
                                            loading="lazy"
                                        />
                                        <span v-else class="no-logo">-</span>
                                    </div>
                                </td>
                                <td class="muted">
                                    {{ formatDate(req.created_at) }}
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button
                                            class="btn-action btn-approve"
                                            @click="approve(req)"
                                        >
                                            Approve
                                        </button>
                                        <button
                                            class="btn-action btn-reject"
                                            @click="reject(req)"
                                        >
                                            Disapprove
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="empty-state">
                    <p>No pending team requests.</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineProps({
    requests: {
        type: Array,
        default: () => [],
    },
});

const formatDate = (date) => {
    if (!date) return "";
    return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

const approve = (req) => {
    router.post(route("admin.team-requests.approve", req.id));
};

const reject = (req) => {
    router.post(route("admin.team-requests.reject", req.id));
};
</script>

<style scoped>
.requests-page {
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

.muted {
    color: #9ca3af;
}

.logo-cell {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.logo-img {
    width: 2.25rem;
    height: 2.25rem;
    object-fit: cover;
    border-radius: 0.5rem;
    border: 1px solid rgba(71, 85, 105, 0.5);
}

.no-logo {
    color: #6b7280;
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

.btn-approve {
    background: rgba(34, 197, 94, 0.2);
    color: #4ade80;
    border: 1px solid rgba(34, 197, 94, 0.3);
}

.btn-approve:hover {
    background: rgba(34, 197, 94, 0.3);
}

.btn-reject {
    background: rgba(239, 68, 68, 0.2);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.btn-reject:hover {
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
