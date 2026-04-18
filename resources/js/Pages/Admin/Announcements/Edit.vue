<template>
    <Head title="Edit Announcement - Admin | SBA" />
    <AdminLayout>
        <div class="edit-page">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1 class="page-title">Edit Announcement</h1>
                    <p class="page-subtitle">Update announcement details</p>
                </div>
                <Link
                    :href="route('admin.announcements.index')"
                    class="btn btn-secondary"
                >
                    ← Back to Announcements
                </Link>
            </div>

            <!-- Form -->
            <div class="glass-card">
                <form @submit.prevent="submit" class="form">
                    <div class="form-group">
                        <label for="title" class="form-label">Title *</label>
                        <input
                            type="text"
                            id="title"
                            v-model="form.title"
                            class="form-input"
                            placeholder="Enter announcement title"
                            required
                        />
                        <span v-if="form.errors.title" class="form-error">{{
                            form.errors.title
                        }}</span>
                    </div>

                    <div class="form-group">
                        <label for="content" class="form-label"
                            >Content *</label
                        >
                        <textarea
                            id="content"
                            v-model="form.content"
                            class="form-textarea"
                            rows="8"
                            placeholder="Write your announcement content here..."
                            required
                        ></textarea>
                        <span v-if="form.errors.content" class="form-error">{{
                            form.errors.content
                        }}</span>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input
                                type="checkbox"
                                v-model="form.is_published"
                                class="form-checkbox"
                            />
                            <span>Published</span>
                        </label>
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
                                    : "Update Announcement"
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
    announcement: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    title: props.announcement.title,
    content: props.announcement.content,
    is_published: props.announcement.is_published,
});

const submit = () => {
    form.put(route("admin.announcements.update", props.announcement.id));
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
    max-width: 800px;
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
}

.form-textarea {
    resize: vertical;
    min-height: 150px;
    font-family: inherit;
    line-height: 1.6;
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

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    color: #d1d5db;
}

.form-checkbox {
    width: 1.25rem;
    height: 1.25rem;
    accent-color: var(--cv-accent-1);
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
