<template>
    <Head title="Events | SBA" />
    <PublicLayout>
        <div class="events-page">
            <div class="page-header">
                <div class="header-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        ></path>
                    </svg>
                </div>
                <h1 class="page-title">Events</h1>
            </div>

            <div v-if="events.length > 0" class="events-list">
                <article
                    v-for="event in events"
                    :key="event.id"
                    class="event-card"
                >
                    <div class="event-accent"></div>
                    <div class="event-content">
                        <div class="event-header">
                            <h2 class="event-title">{{ event.title }}</h2>
                            <span class="event-badge">EVENT</span>
                        </div>

                        <div class="event-meta">
                            <span class="event-meta-item">
                                {{ formatEventDate(event.event_date) }}
                            </span>
                            <span
                                v-if="event.event_time"
                                class="event-meta-item"
                            >
                                {{ formatEventTime(event.event_time) }}
                            </span>
                            <span v-if="event.location" class="event-meta-item">
                                {{ event.location }}
                            </span>
                        </div>

                        <p class="event-body">{{ event.description }}</p>

                        <div class="event-footer">
                            <div class="author-info">
                                <div class="author-avatar">
                                    {{ event.author?.name?.charAt(0) || "A" }}
                                </div>
                                <span class="author-name">{{
                                    event.author?.name || "Admin"
                                }}</span>
                            </div>
                            <span class="event-date"
                                >Posted
                                {{ formatPostedDate(event.created_at) }}</span
                            >
                        </div>
                    </div>
                </article>
            </div>

            <div v-else class="empty-state">
                <div class="empty-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        ></path>
                    </svg>
                </div>
                <p>No events available yet.</p>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";

defineProps({
    events: {
        type: Array,
        default: () => [],
    },
});

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
        year: "numeric",
        month: "long",
        day: "numeric",
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
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};
</script>

<style scoped>
.events-page {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.page-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
}

.header-icon {
    display: flex;
    align-items: center;
    justify-content: center;
}

.header-icon svg {
    width: 1.5rem;
    height: 1.5rem;
    color: var(--cv-text-1);
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--cv-text-1);
    margin: 0;
}

.events-list {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.event-card {
    display: flex;
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.event-card:hover {
    border-color: rgba(245, 158, 11, 0.4);
    transform: translateX(4px);
}

.event-accent {
    width: 4px;
    background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
    flex-shrink: 0;
}

.event-content {
    flex: 1;
    padding: 1.5rem;
}

.event-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 0.75rem;
}

.event-title {
    font-size: 1.375rem;
    font-weight: 700;
    color: var(--cv-text-1);
    margin: 0;
}

.event-badge {
    padding: 0.25rem 0.75rem;
    background: rgba(245, 158, 11, 0.16);
    color: #fcd34d;
    font-size: 0.625rem;
    font-weight: 700;
    border-radius: 0.375rem;
    letter-spacing: 0.05em;
    white-space: nowrap;
}

.event-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.event-meta-item {
    font-size: 0.75rem;
    color: var(--cv-text-2);
    border: 1px solid var(--cv-border-1);
    background: var(--cv-surface-3);
    border-radius: 0.35rem;
    padding: 0.2rem 0.5rem;
}

.event-body {
    color: var(--cv-muted);
    line-height: 1.75;
    margin: 0 0 1.25rem 0;
    white-space: pre-line;
}

.event-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 1rem;
    border-top: 1px solid var(--cv-border-1);
}

.author-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.author-avatar {
    width: 2rem;
    height: 2rem;
    border-radius: 9999px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: #ffffff;
    font-size: 0.75rem;
}

.author-name {
    color: var(--cv-text-2);
    font-size: 0.875rem;
    font-weight: 500;
}

.event-date {
    color: var(--cv-muted-2);
    font-size: 0.75rem;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
}

.empty-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 3rem;
    height: 3rem;
    margin-bottom: 1rem;
    color: #fcd34d;
}

.empty-icon svg {
    width: 100%;
    height: 100%;
}

.empty-state p {
    color: var(--cv-muted-2);
    margin: 0;
}

@media (max-width: 640px) {
    .events-page {
        gap: 1rem;
    }

    .page-header {
        padding: 1rem;
        gap: 0.75rem;
    }

    .header-icon svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    .page-title {
        font-size: 1.25rem;
    }

    .events-list {
        gap: 0.75rem;
    }

    .event-content {
        padding: 1rem;
    }

    .event-header {
        flex-direction: column;
        gap: 0.5rem;
    }

    .event-title {
        font-size: 1.0625rem;
    }

    .event-body {
        font-size: 0.875rem;
        line-height: 1.65;
    }

    .event-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style>
