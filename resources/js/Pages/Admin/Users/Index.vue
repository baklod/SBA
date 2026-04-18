<template>
    <Head title="Users - Admin | SBA" />
    <AdminLayout>
        <div class="index-page">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1 class="page-title">Users Management</h1>
                    <p class="page-subtitle">
                        Manage admin and committee accounts
                    </p>
                </div>
                <Link
                    :href="route('admin.users.create')"
                    class="btn btn-primary"
                >
                    + Add User
                </Link>
            </div>

            <!-- Users Table -->
            <div class="glass-card">
                <div v-if="users.length === 0" class="empty-state">
                    <p>No users found. Create your first user!</p>
                </div>

                <table v-else class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id">
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <span class="user-name">{{
                                        user.name
                                    }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="email">{{ user.email }}</span>
                            </td>
                            <td>
                                <span
                                    class="role-badge"
                                    :class="'role-' + user.role"
                                >
                                    {{ user.role }}
                                </span>
                            </td>
                            <td>
                                <span class="date">{{
                                    formatDate(user.created_at)
                                }}</span>
                            </td>
                            <td>
                                <div class="actions">
                                    <Link
                                        :href="
                                            route('admin.users.edit', user.id)
                                        "
                                        class="btn btn-sm btn-secondary"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        v-if="user.id !== currentUserId"
                                        @click="openDeleteModal(user)"
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
            title="Delete User"
            :message="`Are you sure you want to delete '${deleteTarget?.name}'? This action cannot be undone.`"
            confirmText="Delete"
            @confirm="executeDelete"
            @cancel="showDeleteModal = false"
        />
    </AdminLayout>
</template>

<script setup>
import { ref } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ConfirmModal from "@/Components/ConfirmModal.vue";

defineProps({
    users: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const currentUserId = page.props.auth.user?.id;

const showDeleteModal = ref(false);
const deleteTarget = ref(null);

const openDeleteModal = (user) => {
    deleteTarget.value = user;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    router.delete(route("admin.users.destroy", deleteTarget.value.id));
    showDeleteModal.value = false;
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};
</script>

<style scoped>
.index-page {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.header-left {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
}

.page-subtitle {
    font-size: 0.875rem;
    color: #9ca3af;
    margin: 0;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    border: none;
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

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.8125rem;
}

.btn-secondary {
    background: rgba(var(--cv-accent-1-rgb), 0.12);
    color: rgba(var(--cv-accent-1-rgb), 0.95);
    border: 1px solid rgba(var(--cv-accent-1-rgb), 0.28);
}

.btn-secondary:hover {
    background: rgba(var(--cv-accent-1-rgb), 0.18);
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

.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 50%;
    background: linear-gradient(
        135deg,
        var(--cv-accent-1) 0%,
        var(--cv-accent-2) 100%
    );
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #ffffff;
    font-size: 0.875rem;
}

.user-name {
    font-weight: 600;
    color: #ffffff;
}

.email {
    color: #9ca3af;
}

.role-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
}

.role-admin {
    background: rgba(139, 92, 246, 0.2);
    color: #a78bfa;
    border: 1px solid rgba(139, 92, 246, 0.3);
}

.role-committee {
    background: rgba(34, 197, 94, 0.2);
    color: #4ade80;
    border: 1px solid rgba(34, 197, 94, 0.3);
}

.date {
    color: #9ca3af;
    font-size: 0.875rem;
}

.actions {
    display: flex;
    gap: 0.5rem;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #6b7280;
}
</style>
