<template>
    <Head title="Add Event - Admin | SBA" />
    <AdminLayout>
        <div class="create-page">
            <div class="header">
                <div class="header-left">
                    <h1 class="page-title">Create Event</h1>
                    <p class="page-subtitle">Add a new public event</p>
                </div>
                <Link
                    :href="route('admin.events.index')"
                    class="btn btn-secondary"
                >
                    Back to Events
                </Link>
            </div>

            <div class="glass-card">
                <form @submit.prevent="submit" class="form">
                    <div class="form-group">
                        <label for="title" class="form-label">Title *</label>
                        <input
                            id="title"
                            v-model="form.title"
                            type="text"
                            class="form-input"
                            placeholder="Enter event title"
                            required
                        />
                        <span v-if="form.errors.title" class="form-error">{{
                            form.errors.title
                        }}</span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="event_date" class="form-label"
                                >Event Date *</label
                            >
                            <input
                                id="event_date"
                                v-model="form.event_date"
                                type="date"
                                class="form-input"
                                required
                            />
                            <span
                                v-if="form.errors.event_date"
                                class="form-error"
                                >{{ form.errors.event_date }}</span
                            >
                        </div>

                        <div class="form-group">
                            <label for="event_time" class="form-label"
                                >Event Time</label
                            >
                            <input
                                id="event_time"
                                v-model="form.event_time"
                                type="time"
                                class="form-input"
                            />
                            <span
                                v-if="form.errors.event_time"
                                class="form-error"
                                >{{ form.errors.event_time }}</span
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="location" class="form-label"
                            >Location</label
                        >
                        <input
                            id="location"
                            v-model="form.location"
                            type="text"
                            class="form-input"
                            placeholder="Enter event location"
                        />
                        <span v-if="form.errors.location" class="form-error">{{
                            form.errors.location
                        }}</span>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label"
                            >Description *</label
                        >
                        <textarea
                            id="description"
                            v-model="form.description"
                            class="form-textarea"
                            rows="8"
                            placeholder="Write event details here..."
                            required
                        ></textarea>
                        <span
                            v-if="form.errors.description"
                            class="form-error"
                            >{{ form.errors.description }}</span
                        >
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input
                                v-model="form.is_published"
                                type="checkbox"
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
                                form.processing ? "Creating..." : "Create Event"
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

const form = useForm({
    title: "",
    description: "",
    event_date: "",
    event_time: "",
    location: "",
    is_published: true,
});

const submit = () => {
    form.post(route("admin.events.store"));
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
    max-width: 880px;
}

.form {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
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

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--cv-accent-1);
    box-shadow: 0 0 0 3px rgba(var(--cv-accent-1-rgb), 0.1);
}

.form-textarea {
    resize: vertical;
    min-height: 150px;
    font-family: inherit;
    line-height: 1.6;
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
    margin-top: 0.5rem;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
