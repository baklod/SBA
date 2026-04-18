<template>
    <Head title="Add User - Admin | SBA" />
    <AdminLayout>
        <div class="create-page">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1 class="page-title">Add New User</h1>
                    <p class="page-subtitle">
                        Create a new admin or committee account
                    </p>
                </div>
                <Link
                    :href="route('admin.users.index')"
                    class="btn btn-secondary"
                >
                    ← Back to Users
                </Link>
            </div>

            <!-- Form -->
            <div class="glass-card">
                <form @submit.prevent="submit" class="form">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name *</label>
                        <input
                            type="text"
                            id="name"
                            v-model="form.name"
                            class="form-input"
                            placeholder="Enter full name"
                            required
                        />
                        <span v-if="form.errors.name" class="form-error">
                            {{ form.errors.name }}
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label"
                            >Email Address *</label
                        >
                        <input
                            type="email"
                            id="email"
                            v-model="form.email"
                            class="form-input"
                            placeholder="Enter email address"
                            required
                        />
                        <span v-if="form.errors.email" class="form-error">
                            {{ form.errors.email }}
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="role" class="form-label">Role *</label>
                        <select
                            id="role"
                            v-model="form.role"
                            class="form-select"
                            required
                        >
                            <option value="">Select a role</option>
                            <option value="admin">Admin</option>
                            <option value="committee">Committee</option>
                        </select>
                        <span v-if="form.errors.role" class="form-error">
                            {{ form.errors.role }}
                        </span>
                        <p class="form-hint">
                            <strong>Admin:</strong> Full access to all
                            features<br />
                            <strong>Committee:</strong> Can manage matches and
                            player stats
                        </p>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password" class="form-label"
                                >Password *</label
                            >
                            <input
                                type="password"
                                id="password"
                                v-model="form.password"
                                class="form-input"
                                placeholder="Enter password"
                                required
                            />
                            <span
                                v-if="form.errors.password"
                                class="form-error"
                            >
                                {{ form.errors.password }}
                            </span>
                        </div>

                        <div class="form-group">
                            <label
                                for="password_confirmation"
                                class="form-label"
                                >Confirm Password *</label
                            >
                            <input
                                type="password"
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                class="form-input"
                                placeholder="Confirm password"
                                required
                            />
                        </div>
                    </div>

                    <div class="form-actions">
                        <Link
                            :href="route('admin.users.index')"
                            class="btn btn-secondary"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Creating...</span>
                            <span v-else>Create User</span>
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

const form = useForm({
    name: "",
    email: "",
    role: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("admin.users.store"));
};
</script>

<style scoped>
.create-page {
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

.btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-secondary {
    background: rgba(51, 65, 85, 0.5);
    color: #9ca3af;
    border: 1px solid rgba(51, 65, 85, 0.5);
}

.btn-secondary:hover {
    background: rgba(51, 65, 85, 0.8);
    color: #ffffff;
}

.glass-card {
    background: rgba(30, 41, 59, 0.5);
    backdrop-filter: blur(4px);
    border-radius: 0.75rem;
    padding: 2rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
    max-width: 600px;
}

.form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
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
    color: #e2e8f0;
}

.form-input,
.form-select {
    padding: 0.75rem 1rem;
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid rgba(51, 65, 85, 0.5);
    border-radius: 0.5rem;
    color: #ffffff;
    font-size: 0.875rem;
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
    color: #e2e8f0;
}

.form-error {
    font-size: 0.75rem;
    color: #f87171;
}

.form-hint {
    font-size: 0.75rem;
    color: #6b7280;
    margin: 0;
    line-height: 1.5;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding-top: 1rem;
    border-top: 1px solid rgba(51, 65, 85, 0.5);
}
</style>
