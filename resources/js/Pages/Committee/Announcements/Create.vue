<template>
    <Head title="Add Announcement - Committee | SBA" />
    <CommitteeLayout>
        <div class="create-page">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1 class="page-title">Create Announcement</h1>
                    <p class="page-subtitle">Write a new announcement</p>
                </div>
                <Link
                    :href="route('committee.announcements.index')"
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
                            <span>Publish immediately</span>
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
                                    ? "Creating..."
                                    : "Create Announcement"
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </CommitteeLayout>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import CommitteeLayout from "@/Layouts/CommitteeLayout.vue";

const form = useForm({
    title: "",
    content: "",
    is_published: true,
});

const submit = () => {
    form.post(route("committee.announcements.store"));
};
</script>

<style scoped>
.create-page {
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
    background: linear-gradient(135deg, #a855f7 0%, #8b5cf6 100%);
    color: #ffffff;
}

.btn-primary:hover {
    box-shadow: 0 10px 25px rgba(168, 85, 247, 0.3);
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
    border-color: #a855f7;
    box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.1);
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
    accent-color: #a855f7;
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
