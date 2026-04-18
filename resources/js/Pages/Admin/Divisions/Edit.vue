<template>
    <Head title="Edit Division - Admin | SBA" />
    <AdminLayout>
        <div class="edit-page">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1 class="page-title">Edit Division</h1>
                    <p class="page-subtitle">Update division information</p>
                </div>
                <Link
                    :href="route('admin.divisions.index')"
                    class="btn btn-secondary"
                >
                    ← Back to Divisions
                </Link>
            </div>

            <!-- Form -->
            <div class="glass-card">
                <form @submit.prevent="submit" class="form">
                    <div class="form-group">
                        <label for="name" class="form-label"
                            >Division Name *</label
                        >
                        <input
                            type="text"
                            id="name"
                            v-model="form.name"
                            class="form-input"
                            placeholder="e.g., Junior Division, Senior Division"
                            required
                        />
                        <span v-if="form.errors.name" class="form-error">{{
                            form.errors.name
                        }}</span>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label"
                            >Description (Optional)</label
                        >
                        <textarea
                            id="description"
                            v-model="form.description"
                            class="form-textarea"
                            placeholder="Enter division description"
                            rows="3"
                        ></textarea>
                        <span
                            v-if="form.errors.description"
                            class="form-error"
                            >{{ form.errors.description }}</span
                        >
                    </div>

                    <div class="form-actions">
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="form.processing"
                        >
                            {{
                                form.processing
                                    ? "Updating..."
                                    : "Update Division"
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
    division: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    name: props.division.name,
    description: props.division.description || "",
});

const submit = () => {
    form.put(route("admin.divisions.update", props.division.id));
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
    max-width: 600px;
}

.form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
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
.form-textarea {
    padding: 0.75rem 1rem;
    background: rgba(51, 65, 85, 0.5);
    border: 1px solid rgba(71, 85, 105, 0.5);
    border-radius: 0.5rem;
    color: #ffffff;
    font-size: 1rem;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-textarea {
    resize: vertical;
    min-height: 80px;
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--cv-accent-1);
    box-shadow: 0 0 0 3px rgba(var(--cv-accent-1-rgb), 0.1);
}

.form-input::placeholder,
.form-textarea::placeholder {
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
