<template>
    <Head title="Announcements - Committee | SBA" />
    <CommitteeLayout>
        <div class="announcements-page">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1 class="page-title">Announcements</h1>
                    <p class="page-subtitle">Manage news and announcements</p>
                </div>
                <Link
                    :href="route('committee.announcements.create')"
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
                    Create Announcement
                </Link>
            </div>

            <!-- Announcements List -->
            <div class="glass-card">
                <div
                    v-if="announcements && announcements.length > 0"
                    class="announcements-list"
                >
                    <div
                        v-for="announcement in announcements"
                        :key="announcement.id"
                        class="announcement-card"
                    >
                        <div class="announcement-content">
                            <div class="announcement-header">
                                <h3 class="announcement-title">
                                    {{ announcement.title }}
                                </h3>
                                <span
                                    :class="[
                                        'status-badge',
                                        announcement.is_published
                                            ? 'status-published'
                                            : 'status-draft',
                                    ]"
                                >
                                    {{
                                        announcement.is_published
                                            ? "Published"
                                            : "Draft"
                                    }}
                                </span>
                            </div>
                            <p class="announcement-excerpt">
                                {{ truncate(announcement.content, 150) }}
                            </p>
                            <div class="announcement-meta">
                                <span class="author"
                                    >By
                                    {{
                                        announcement.author?.name || "Unknown"
                                    }}</span
                                >
                                <span class="date">{{
                                    formatDate(announcement.created_at)
                                }}</span>
                            </div>
                        </div>
                        <div class="announcement-actions">
                            <Link
                                :href="
                                    route(
                                        'committee.announcements.edit',
                                        announcement.id,
                                    )
                                "
                                class="btn-action btn-edit"
                            >
                                Edit
                            </Link>
                            <Link
                                :href="
                                    route(
                                        'committee.announcements.destroy',
                                        announcement.id,
                                    )
                                "
                                method="delete"
                                as="button"
                                class="btn-action btn-delete"
                                @click="confirmDelete"
                            >
                                Delete
                            </Link>
                        </div>
                    </div>
                </div>
                <div v-else class="empty-state">
                    <p>No announcements yet. Create one to get started!</p>
                </div>
            </div>
        </div>
    </CommitteeLayout>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import CommitteeLayout from "@/Layouts/CommitteeLayout.vue";

defineProps({
    announcements: {
        type: Array,
        default: () => [],
    },
});

const formatDate = (date) => {
    if (!date) return "";
    return new Date(date).toLocaleDateString("en-US", {
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

const confirmDelete = (e) => {
    if (!confirm("Are you sure you want to delete this announcement?")) {
        e.preventDefault();
    }
};
</script>

<style scoped>
.announcements-page {
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

.announcements-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.announcement-card {
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

.announcement-card:hover {
    border-color: rgba(168, 85, 247, 0.3);
}

.announcement-content {
    flex: 1;
}

.announcement-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
    flex-wrap: wrap;
}

.announcement-title {
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
}

.status-published {
    background: rgba(34, 197, 94, 0.2);
    color: #4ade80;
}

.status-draft {
    background: rgba(251, 146, 60, 0.2);
    color: #fb923c;
}

.announcement-excerpt {
    color: #9ca3af;
    margin: 0 0 0.75rem 0;
    line-height: 1.5;
}

.announcement-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: #6b7280;
}

.announcement-actions {
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
    background: rgba(59, 130, 246, 0.2);
    color: #60a5fa;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.btn-edit:hover {
    background: rgba(59, 130, 246, 0.3);
}

.btn-delete {
    background: rgba(239, 68, 68, 0.2);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.btn-delete:hover {
    background: rgba(239, 68, 68, 0.3);
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #6b7280;
}
</style>
