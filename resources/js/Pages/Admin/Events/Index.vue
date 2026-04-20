<template>
    <Head title="Events - Admin | SBA" />
    <AdminLayout>
        <div class="events-page">
            <div class="header">
                <div class="header-left">
                    <h1 class="page-title">Events</h1>
                    <p class="page-subtitle">Manage upcoming and past events</p>
                </div>
                <Link
                    :href="route('admin.events.create')"
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
                    Create Event
                </Link>
            </div>

            <div class="glass-card">
                <div v-if="events && events.length > 0" class="events-list">
                    <article
                        v-for="event in events"
                        :key="event.id"
                        class="event-card"
                    >
                        <div class="event-content">
                            <div class="event-header">
                                <h3 class="event-title">{{ event.title }}</h3>
                                <span
                                    :class="[
                                        'status-badge',
                                        event.is_published
                                            ? 'status-published'
                                            : 'status-draft',
                                    ]"
                                >
                                    {{
                                        event.is_published
                                            ? "Published"
                                            : "Draft"
                                    }}
                                </span>
                            </div>

                            <div class="event-meta">
                                <span>{{
                                    formatEventDate(event.event_date)
                                }}</span>
                                <span v-if="event.event_time">{{
                                    formatEventTime(event.event_time)
                                }}</span>
                                <span v-if="event.location">{{
                                    event.location
                                }}</span>
                            </div>

                            <p class="event-excerpt">
                                {{ truncate(event.description, 180) }}
                            </p>

                            <div class="event-footer">
                                <span class="author"
                                    >By
                                    {{ event.author?.name || "Unknown" }}</span
                                >
                                <span class="posted-date"
                                    >Posted
                                    {{
                                        formatPostedDate(event.created_at)
                                    }}</span
                                >
                            </div>
                        </div>

                        <div class="event-actions">
                            <Link
                                :href="route('admin.events.edit', event.id)"
                                class="btn-action btn-edit"
                            >
                                Edit
                            </Link>
                            <button
                                class="btn-action btn-delete"
                                @click="openDeleteModal(event)"
                            >
                                Delete
                            </button>
                        </div>
                    </article>
                </div>

                <div v-else class="empty-state">
                    <p>No events yet. Create one to get started.</p>
                </div>
            </div>
        </div>

        <ConfirmModal
            :show="showDeleteModal"
            title="Delete Event"
            :message="'Are you sure you want to delete this event? This action cannot be undone.'"
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
    events: {
        type: Array,
        default: () => [],
    },
});

const showDeleteModal = ref(false);
const deleteTarget = ref(null);

const openDeleteModal = (event) => {
    deleteTarget.value = event;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    if (!deleteTarget.value) return;

    router.delete(route("admin.events.destroy", deleteTarget.value.id));
    showDeleteModal.value = false;
};

const formatEventDate = (dateValue) => {
    if (!dateValue) return "Date TBA";

    const dateText = String(dateValue).slice(0, 10);
    const parts = dateText.split("-");
    if (parts.length !== 3) return "Date TBA";

    const year = Number(parts[0]);
    const month = Number(parts[1]) - 1;
    const day = Number(parts[2]);
    const date = new Date(year, month, day);

    if (Number.isNaN(date.getTime())) return "Date TBA";

    return date.toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        year: "numeric",
    });
};

const formatEventTime = (timeValue) => {
    if (!timeValue) return "";

    const normalizedTime = String(timeValue).slice(0, 5);
    const date = new Date(`2000-01-01T${normalizedTime}`);
    if (Number.isNaN(date.getTime())) return normalizedTime;

    return date.toLocaleTimeString("en-US", {
        hour: "2-digit",
        minute: "2-digit",
    });
};

const formatPostedDate = (dateValue) => {
    if (!dateValue) return "";

    const date = new Date(dateValue);
    if (Number.isNaN(date.getTime())) return "";

    return date.toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        year: "numeric",
    });
};

const truncate = (text, length) => {
    if (!text) return "";
    if (text.length <= length) return text;
    return text.substring(0, length) + "...";
};
</script>

<style scoped>
.events-page {
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

.events-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.event-card {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem;
    background: rgba(51, 65, 85, 0.3);
    border-radius: 0.75rem;
    border: 1px solid rgba(71, 85, 105, 0.3);
    transition: all 0.3s ease;
}

.event-card:hover {
    border-color: rgba(var(--cv-accent-1-rgb), 0.28);
}

.event-content {
    flex: 1;
}

.event-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
    flex-wrap: wrap;
}

.event-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #ffffff;
    margin: 0;
}

.status-badge {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 0.375rem;
    background: var(--cv-surface-2);
    color: #e5e7eb;
    border: 1px solid var(--cv-border-1);
}

.status-published,
.status-draft {
    background: var(--cv-surface-2);
    color: #e5e7eb;
}

.event-meta {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    color: #9ca3af;
    font-size: 0.8rem;
    margin-bottom: 0.6rem;
}

.event-excerpt {
    color: #9ca3af;
    margin: 0 0 0.75rem 0;
    line-height: 1.5;
    white-space: pre-line;
}

.event-footer {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: #6b7280;
}

.event-actions {
    display: flex;
    gap: 0.5rem;
    flex-shrink: 0;
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
    color: var(--cv-accent-1);
    border: 1px solid rgba(var(--cv-accent-1-rgb), 0.2);
}

.btn-edit:hover {
    background: rgba(var(--cv-accent-1-rgb), 0.2);
}

.btn-delete {
    background: rgba(239, 68, 68, 0.15);
    color: #fca5a5;
    border: 1px solid rgba(239, 68, 68, 0.25);
}

.btn-delete:hover {
    background: rgba(239, 68, 68, 0.22);
}

.empty-state {
    text-align: center;
    padding: 2rem;
    color: #9ca3af;
}

@media (max-width: 768px) {
    .event-card {
        flex-direction: column;
    }

    .event-actions {
        width: 100%;
    }
}
</style>
