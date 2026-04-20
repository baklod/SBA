<template>
    <Head v-if="!embedded" title="Schedule | SBA" />
    <component :is="embedded ? 'div' : PublicLayout">
        <div class="schedule-page">
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
                <h1 class="page-title">Match Schedule</h1>
            </div>

            <div v-if="groupedSections.length > 0" class="schedule-sections">
                <section
                    v-for="group in groupedSections"
                    :key="group.key"
                    class="schedule-section"
                >
                    <div class="section-header">
                        <h2 class="section-title">{{ group.title }}</h2>
                        <span class="section-count">{{
                            group.matches.length
                        }}</span>
                    </div>

                    <div class="matches-list">
                        <Link
                            v-for="match in group.matches"
                            :key="match.id"
                            :href="route('match.details', match.id)"
                            class="match-card"
                        >
                            <div class="match-content">
                                <div class="teams-section">
                                    <div class="team">
                                        <div class="team-avatar">
                                            <img
                                                v-if="
                                                    resolveTeamLogoUrl(
                                                        match.team_a?.logo,
                                                    )
                                                "
                                                :src="
                                                    resolveTeamLogoUrl(
                                                        match.team_a?.logo,
                                                    )
                                                "
                                                :alt="
                                                    (match.team_a?.name ||
                                                        'Team A') + ' logo'
                                                "
                                                class="team-avatar-image"
                                                loading="lazy"
                                            />
                                            <span
                                                v-else
                                                class="team-avatar-fallback"
                                            >
                                                {{
                                                    match.team_a?.name?.charAt(
                                                        0,
                                                    ) || "A"
                                                }}
                                            </span>
                                        </div>
                                        <div class="team-info">
                                            <span class="team-name">{{
                                                match.team_a?.name
                                            }}</span>
                                            <span
                                                v-if="
                                                    match.status === 'finished'
                                                "
                                                class="team-score"
                                                >{{ match.team_a_score }}</span
                                            >
                                        </div>
                                    </div>

                                    <div class="vs-section">
                                        <span class="vs-badge">VS</span>
                                    </div>

                                    <div class="team team-right">
                                        <div
                                            class="team-avatar team-avatar-alt"
                                        >
                                            <img
                                                v-if="
                                                    resolveTeamLogoUrl(
                                                        match.team_b?.logo,
                                                    )
                                                "
                                                :src="
                                                    resolveTeamLogoUrl(
                                                        match.team_b?.logo,
                                                    )
                                                "
                                                :alt="
                                                    (match.team_b?.name ||
                                                        'Team B') + ' logo'
                                                "
                                                class="team-avatar-image"
                                                loading="lazy"
                                            />
                                            <span
                                                v-else
                                                class="team-avatar-fallback"
                                            >
                                                {{
                                                    match.team_b?.name?.charAt(
                                                        0,
                                                    ) || "B"
                                                }}
                                            </span>
                                        </div>
                                        <div class="team-info">
                                            <span class="team-name">{{
                                                match.team_b?.name
                                            }}</span>
                                            <span
                                                v-if="
                                                    match.status === 'finished'
                                                "
                                                class="team-score"
                                                >{{ match.team_b_score }}</span
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div class="match-details">
                                    <div class="detail-item">
                                        <svg
                                            class="detail-icon"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                            ></path>
                                        </svg>
                                        {{ formatDate(match.match_date) }}
                                    </div>
                                    <div
                                        v-if="match.match_time"
                                        class="detail-item"
                                    >
                                        <svg
                                            class="detail-icon"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                            ></path>
                                        </svg>
                                        {{ formatTime(match.match_time) }}
                                    </div>
                                    <div v-if="match.venue" class="detail-item">
                                        <svg
                                            class="detail-icon"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                            ></path>
                                        </svg>
                                        {{ match.venue }}
                                    </div>
                                    <span
                                        :class="[
                                            'status-badge',
                                            'status-' + match.status,
                                        ]"
                                    >
                                        {{ match.status.toUpperCase() }}
                                    </span>
                                    <span class="view-details">
                                        View Details
                                        <svg
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 5l7 7-7 7"
                                            ></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </Link>
                    </div>
                </section>
            </div>

            <div v-else class="empty-state">
                <div class="empty-icon">📅</div>
                <p>No matches scheduled yet.</p>
            </div>
        </div>
    </component>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { computed } from "vue";

const props = defineProps({
    matches: Array,
    embedded: {
        type: Boolean,
        default: false,
    },
});

const statusGroupOrder = ["upcoming", "ongoing", "finished"];

const statusGroupLabels = {
    upcoming: "Upcoming",
    ongoing: "Ongoing",
    finished: "Finished",
};

const normalizeStatusGroup = (status) => {
    const normalized = String(status || "").toLowerCase();

    if (normalized === "upcoming") return "upcoming";
    if (["live", "ongoing", "paused"].includes(normalized)) return "ongoing";
    if (normalized === "finished") return "finished";

    return "upcoming";
};

const toMatchTimestamp = (match) => {
    const datePart = String(match?.match_date ?? "").slice(0, 10);
    if (!datePart) return Number.MAX_SAFE_INTEGER;

    const timeRaw = String(match?.match_time ?? "").trim();
    const timePart = timeRaw ? timeRaw.slice(0, 8) : "00:00:00";

    const parsed = new Date(`${datePart}T${timePart}`);
    if (Number.isNaN(parsed.getTime())) return Number.MAX_SAFE_INTEGER;

    return parsed.getTime();
};

const groupedSections = computed(() => {
    const groups = {
        upcoming: [],
        ongoing: [],
        finished: [],
    };

    (props.matches ?? []).forEach((match) => {
        const key = normalizeStatusGroup(match?.status);
        groups[key].push(match);
    });

    groups.upcoming.sort((a, b) => toMatchTimestamp(a) - toMatchTimestamp(b));
    groups.ongoing.sort((a, b) => toMatchTimestamp(a) - toMatchTimestamp(b));
    groups.finished.sort((a, b) => toMatchTimestamp(b) - toMatchTimestamp(a));

    return statusGroupOrder
        .map((key) => ({
            key,
            title: statusGroupLabels[key],
            matches: groups[key],
        }))
        .filter((group) => group.matches.length > 0);
});

const resolveTeamLogoUrl = (logoPath) => {
    if (!logoPath || typeof logoPath !== "string") return null;
    if (logoPath.startsWith("http://") || logoPath.startsWith("https://")) {
        return logoPath;
    }
    if (logoPath.startsWith("/")) return logoPath;
    if (logoPath.startsWith("uploads/")) return `/${logoPath}`;
    return `/storage/${logoPath}`;
};

const formatDate = (date) => {
    if (!date) return "TBD";
    const d = new Date(date);
    if (isNaN(d.getTime())) return "TBD";
    return d.toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const formatTime = (time) => {
    if (!time) return "";
    // Handle full datetime string (e.g. "2026-02-21T15:30:00.000000Z")
    if (time.includes("T") || time.includes(" ")) {
        const d = new Date(time);
        if (!isNaN(d.getTime())) {
            return d.toLocaleTimeString("en-US", {
                hour: "2-digit",
                minute: "2-digit",
            });
        }
    }
    // Handle plain time string (e.g. "15:30:00")
    const d = new Date(`2000-01-01T${time}`);
    if (isNaN(d.getTime())) return "";
    return d.toLocaleTimeString("en-US", {
        hour: "2-digit",
        minute: "2-digit",
    });
};
</script>

<style scoped>
.schedule-page {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.page-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
}

@media (min-width: 480px) {
    .page-header {
        gap: 1rem;
        padding: 1.5rem;
    }
}

.header-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

@media (min-width: 480px) {
    .header-icon {
        flex-shrink: 0;
    }
}

.header-icon svg {
    width: 1.25rem;
    height: 1.25rem;
    color: var(--cv-text-1);
}

@media (min-width: 480px) {
    .header-icon svg {
        width: 1.5rem;
        height: 1.5rem;
    }
}

.page-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--cv-text-1);
    margin: 0;
}

.schedule-sections {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.schedule-section {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0 0.25rem;
}

.section-title {
    margin: 0;
    color: var(--cv-text-1);
    font-size: 0.95rem;
    font-weight: 700;
    letter-spacing: 0.02em;
    text-transform: uppercase;
}

.section-count {
    min-width: 1.75rem;
    height: 1.75rem;
    border-radius: 9999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    color: rgba(251, 191, 36, 0.95);
    background: rgba(245, 158, 11, 0.16);
    border: 1px solid rgba(245, 158, 11, 0.34);
}

@media (min-width: 480px) {
    .page-title {
        font-size: 1.75rem;
    }

    .section-title {
        font-size: 1.05rem;
    }
}

.matches-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.match-card {
    display: block;
    text-decoration: none;
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
    padding: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

@media (min-width: 480px) {
    .match-card {
        padding: 1.5rem;
    }
}

.match-card:hover {
    border-color: var(--cv-border-2);
    background: var(--cv-surface-2);
}

.match-content {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

@media (min-width: 768px) {
    .match-content {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
    }
}

.teams-section {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex: 1;
    justify-content: space-between;
}

@media (max-width: 480px) {
    .teams-section {
        gap: 0.25rem;
    }
}

.team {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex: 1;
    min-width: 0;
}

.team-right {
    flex-direction: row-reverse;
    text-align: right;
}

.team-avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #ffffff;
    font-size: 1rem;
    flex-shrink: 0;
}

.team-avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: inherit;
}

.team-avatar-fallback {
    line-height: 1;
}

@media (min-width: 480px) {
    .team-avatar {
        width: 3rem;
        height: 3rem;
        font-size: 1.25rem;
        border-radius: 0.75rem;
    }
}

.team-avatar-alt {
    background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);
}

.team-info {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.team-name {
    font-weight: 600;
    color: var(--cv-text-1);
    font-size: 0.875rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

@media (min-width: 480px) {
    .team-name {
        font-size: 1rem;
    }
}

@media (min-width: 768px) {
    .team-name {
        font-size: 1.125rem;
    }
}

.team-score {
    font-size: 1.25rem;
    font-weight: 700;
    color: rgba(251, 191, 36, 0.95);
}

@media (min-width: 480px) {
    .team-score {
        font-size: 1.5rem;
    }
}

.vs-section {
    flex-shrink: 0;
}

.vs-badge {
    padding: 0.25rem 0.5rem;
    background: var(--cv-surface-3);
    border-radius: 0.375rem;
    font-size: 0.625rem;
    font-weight: 700;
    color: var(--cv-muted-2);
}

@media (min-width: 480px) {
    .vs-badge {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        border-radius: 0.5rem;
    }
}

.match-details {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.5rem;
}

@media (min-width: 480px) {
    .match-details {
        gap: 1rem;
    }
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    color: var(--cv-muted);
}

@media (min-width: 480px) {
    .detail-item {
        font-size: 0.875rem;
        gap: 0.375rem;
    }
}

.detail-icon {
    width: 0.875rem;
    height: 0.875rem;
}

@media (min-width: 480px) {
    .detail-icon {
        width: 1rem;
        height: 1rem;
    }
}

.status-badge {
    padding: 0.25rem 0.5rem;
    font-size: 0.625rem;
    font-weight: 600;
    border-radius: 9999px;
}

@media (min-width: 480px) {
    .status-badge {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
    }
}

.view-details {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: rgba(251, 191, 36, 0.95);
    transition: color 0.3s ease;
}

.view-details svg {
    width: 0.875rem;
    height: 0.875rem;
    transition: transform 0.3s ease;
}

.match-card:hover .view-details {
    color: rgba(251, 191, 36, 0.98);
}

.match-card:hover .view-details svg {
    transform: translateX(3px);
}

@media (max-width: 480px) {
    .view-details {
        font-size: 0;
        gap: 0;
    }

    .view-details svg {
        width: 1rem;
        height: 1rem;
    }
}

.status-upcoming {
    background: #b45309;
    color: #ffffff;
}

.status-ongoing {
    background: #dc2626;
    color: #ffffff;
}

.status-live {
    background: #dc2626;
    color: #ffffff;
}

.status-paused {
    background: #c2410c;
    color: #ffffff;
}

.status-finished {
    background: #475569;
    color: #ffffff;
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
</style>
