<template>
    <Head title="Announcements | SBA" />
    <PublicLayout>
        <div class="announcements-page">
            <div class="page-header">
                <div class="header-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"
                        ></path>
                    </svg>
                </div>
                <h1 class="page-title">Announcements</h1>
            </div>

            <div v-if="announcements.length > 0" class="announcements-list">
                <article
                    v-for="announcement in announcements"
                    :key="announcement.id"
                    class="announcement-card"
                >
                    <div class="announcement-accent"></div>
                    <div class="announcement-content">
                        <div class="announcement-header">
                            <h2 class="announcement-title">
                                {{ announcement.title }}
                            </h2>
                            <span class="announcement-badge">ANNOUNCEMENT</span>
                        </div>
                        <p class="announcement-body">
                            {{ announcement.content }}
                        </p>
                        <div class="announcement-footer">
                            <div class="author-info">
                                <div class="author-avatar">
                                    {{
                                        announcement.author?.name?.charAt(0) ||
                                        "A"
                                    }}
                                </div>
                                <span class="author-name">{{
                                    announcement.author?.name
                                }}</span>
                            </div>
                            <span class="announcement-date">{{
                                formatDate(announcement.created_at)
                            }}</span>
                        </div>
                    </div>
                </article>
            </div>

            <div v-else class="empty-state">
                <div class="empty-icon">📢</div>
                <p>No announcements available yet.</p>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";

defineProps({
    announcements: Array,
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};
</script>

<style scoped>
.announcements-page {
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

.announcements-list {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.announcement-card {
    display: flex;
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.announcement-card:hover {
    border-color: rgba(251, 191, 36, 0.35);
    transform: translateX(4px);
}

.announcement-accent {
    width: 4px;
    background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
    flex-shrink: 0;
}

.announcement-content {
    flex: 1;
    padding: 1.5rem;
}

.announcement-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1rem;
}

.announcement-title {
    font-size: 1.375rem;
    font-weight: 700;
    color: var(--cv-text-1);
    margin: 0;
}

.announcement-badge {
    padding: 0.25rem 0.75rem;
    background: rgba(245, 158, 11, 0.18);
    color: rgba(251, 191, 36, 0.95);
    font-size: 0.625rem;
    font-weight: 700;
    border-radius: 0.375rem;
    letter-spacing: 0.05em;
    white-space: nowrap;
}

.announcement-body {
    color: var(--cv-muted);
    line-height: 1.75;
    margin: 0 0 1.5rem 0;
    white-space: pre-line;
}

.announcement-footer {
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

.announcement-date {
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
    font-size: 3rem;
    margin-bottom: 1rem;
}

.empty-state p {
    color: var(--cv-muted-2);
    margin: 0;
}

/* Mobile Responsive */
@media (max-width: 640px) {
    .announcements-page {
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

    .announcements-list {
        gap: 0.75rem;
    }

    .announcement-content {
        padding: 1rem;
    }

    .announcement-header {
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .announcement-title {
        font-size: 1.0625rem;
    }

    .announcement-badge {
        align-self: flex-start;
        font-size: 0.5625rem;
        padding: 0.1875rem 0.5rem;
    }

    .announcement-body {
        font-size: 0.875rem;
        line-height: 1.65;
        margin-bottom: 1rem;
    }

    .announcement-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        padding-top: 0.75rem;
    }

    .author-avatar {
        width: 1.75rem;
        height: 1.75rem;
        font-size: 0.625rem;
    }

    .author-name {
        font-size: 0.8125rem;
    }

    .announcement-date {
        font-size: 0.6875rem;
    }

    .empty-state {
        padding: 2.5rem 1.25rem;
    }

    .empty-icon {
        font-size: 2.5rem;
    }
}
</style>
