<template>
    <Head :title="headTitle" />
    <PublicLayout>
        <Schedule v-if="section === 'schedule'" :matches="matches" embedded />
        <Rankings
            v-else-if="section === 'rankings'"
            :teams="rankingsTeams"
            :divisions="divisions"
            embedded
        />
        <TeamStats
            v-else-if="section === 'team-stats'"
            :teams="teamStatsTeams"
            :divisions="divisions"
            embedded
        />
        <IndividualStats
            v-else-if="section === 'individual-stats'"
            :players="individualStatsPlayers"
            :divisions="divisions"
            embedded
        />
        <HighestPoints
            v-else-if="section === 'highest-points'"
            :players="highestPointsPlayers"
            :divisions="divisions"
            embedded
        />
        <Standings
            v-else-if="section === 'standings'"
            :bracketsByDivision="bracketsByDivision"
            embedded
        />
        <DashboardOverview
            v-else
            :isOffSeason="isOffSeason"
            :dashboardMatches="dashboardMatches"
            :liveMatches="dashboardLiveMatches"
            :standingsTeams="dashboardStandingsTeams"
            :upcomingMatches="upcomingMatches"
            :divisions="divisions"
            :playerStats="dashboardPlayerStats"
            :leagueLeaders="dashboardLeagueLeaders"
            :registrationOpen="registrationOpen"
            :registrationClosingDate="registrationClosingDate"
            :teamRegistrationOpen="teamRegistrationOpen"
            :teamRegistrationClosingDate="teamRegistrationClosingDate"
        />
    </PublicLayout>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import DashboardOverview from "@/Pages/Public/DashboardOverview.vue";
import Schedule from "@/Pages/Public/Schedule.vue";
import Rankings from "@/Pages/Public/Rankings.vue";
import TeamStats from "@/Pages/Public/TeamStats.vue";
import IndividualStats from "@/Pages/Public/IndividualStats.vue";
import HighestPoints from "@/Pages/Public/HighestPoints.vue";
import Standings from "@/Pages/Public/Standings.vue";
import { computed } from "vue";

const props = defineProps({
    section: {
        type: String,
        default: "dashboard",
    },
    isOffSeason: {
        type: Boolean,
        default: false,
    },
    registrationOpen: {
        type: Boolean,
        default: true,
    },
    registrationClosingDate: {
        type: String,
        default: null,
    },
    teamRegistrationOpen: {
        type: Boolean,
        default: true,
    },
    teamRegistrationClosingDate: {
        type: String,
        default: null,
    },
    upcomingMatches: {
        type: Array,
        default: () => [],
    },
    divisions: {
        type: Array,
        default: () => [],
    },
    matches: {
        type: Array,
        default: () => [],
    },
    rankingsTeams: {
        type: Array,
        default: () => [],
    },
    teamStatsTeams: {
        type: Array,
        default: () => [],
    },
    individualStatsPlayers: {
        type: Array,
        default: () => [],
    },
    highestPointsPlayers: {
        type: Array,
        default: () => [],
    },
    bracketsByDivision: {
        type: Array,
        default: () => [],
    },
    dashboardMatches: {
        type: Array,
        default: () => [],
    },
    dashboardLiveMatches: {
        type: Array,
        default: () => [],
    },
    dashboardStandingsTeams: {
        type: Array,
        default: () => [],
    },
    dashboardLeagueLeaders: {
        type: Array,
        default: () => [],
    },
    dashboardPlayerStats: {
        type: Array,
        default: () => [],
    },
});

const headTitle = computed(() => {
    switch (props.section) {
        case "schedule":
            return "Schedule | SBA";
        case "rankings":
            return "Team Rankings | SBA";
        case "team-stats":
            return "Team Stats | SBA";
        case "individual-stats":
            return "Player Stats | SBA";
        case "highest-points":
            return "Player Rankings | SBA";
        case "standings":
            return "Standings | SBA";
        default:
            return "Dashboard | SBA";
    }
});
</script>

<style scoped>
.home-page {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

/* Hero Section */
.hero-section {
    position: relative;
    padding: 4rem 2rem;
    border-radius: 1rem;
    overflow: hidden;
    background: linear-gradient(
        135deg,
        rgba(15, 23, 42, 0.9) 0%,
        rgba(30, 41, 59, 0.9) 100%
    );
    border: 1px solid rgba(51, 65, 85, 0.5);
}

.hero-bg {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(
            circle at 20% 50%,
            rgba(6, 182, 212, 0.15) 0%,
            transparent 50%
        ),
        radial-gradient(
            circle at 80% 50%,
            rgba(139, 92, 246, 0.15) 0%,
            transparent 50%
        );
}

.hero-content {
    position: relative;
    text-align: center;
    z-index: 1;
}

.hero-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: linear-gradient(
        135deg,
        rgba(6, 182, 212, 0.2) 0%,
        rgba(59, 130, 246, 0.2) 100%
    );
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    color: #22d3ee;
    letter-spacing: 0.1em;
    margin-bottom: 1.5rem;
}

.hero-title {
    font-size: 3rem;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 1rem 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.hero-icon {
    width: 7rem;
    height: 7rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.hero-lottie {
    width: 100%;
    height: 100%;
    display: inline-block;
    pointer-events: none;
}

.hero-lottie :deep(svg) {
    width: 100%;
    height: 100%;
}

.hero-subtitle {
    font-size: 1.25rem;
    color: #9ca3af;
    margin: 0 0 2rem 0;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.hero-stats {
    display: flex;
    justify-content: center;
    gap: 3rem;
    margin-bottom: 2rem;
}

.hero-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: #22d3ee;
}

.stat-label {
    font-size: 0.875rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.hero-actions {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-hero-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.75rem;
    background: linear-gradient(135deg, #06b6d4 0%, #2563eb 100%);
    color: #ffffff;
    font-weight: 600;
    border-radius: 0.75rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-hero-primary:hover {
    box-shadow: 0 10px 30px rgba(6, 182, 212, 0.3);
    transform: translateY(-2px);
}

.btn-hero-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.75rem;
    background: rgba(51, 65, 85, 0.5);
    color: #d1d5db;
    font-weight: 600;
    border: 1px solid rgba(71, 85, 105, 0.5);
    border-radius: 0.75rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-hero-secondary:hover {
    background: rgba(51, 65, 85, 0.8);
    color: #ffffff;
}

.btn-icon {
    width: 1.25rem;
    height: 1.25rem;
}

.hero-glow {
    position: absolute;
    bottom: -50%;
    left: 50%;
    transform: translateX(-50%);
    width: 80%;
    height: 100%;
    background: radial-gradient(
        ellipse,
        rgba(6, 182, 212, 0.1) 0%,
        transparent 60%
    );
    pointer-events: none;
}

/* Off-Season Banner */
.off-season-banner {
    background: linear-gradient(
        135deg,
        rgba(15, 23, 42, 0.9) 0%,
        rgba(30, 41, 59, 0.9) 100%
    );
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 0.75rem;
    padding: 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(4px);
}

.banner-content {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.banner-icon {
    font-size: 3rem;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-10px) rotate(5deg);
    }
    100% {
        transform: translateY(0px) rotate(0deg);
    }
}

.banner-text h2 {
    font-size: 1.75rem;
    color: #22d3ee;
    margin: 0 0 0.5rem 0;
    font-weight: 700;
}

.banner-text p {
    color: #9ca3af;
    margin: 0;
    font-size: 1.125rem;
    max-width: 600px;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

@media (min-width: 1024px) {
    .content-grid {
        grid-template-columns: 1.5fr 1fr;
    }
}

/* Glass Card */
.glass-card {
    background: rgba(30, 41, 59, 0.5);
    backdrop-filter: blur(4px);
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
}

/* Section Header */
.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}

.section-title-group {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.section-icon svg {
    width: 1.25rem;
    height: 1.25rem;
    color: #ffffff;
}

.section-icon-cyan {
    background: linear-gradient(135deg, #06b6d4 0%, #2563eb 100%);
}

.section-icon-gold {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.section-icon-purple {
    background: linear-gradient(135deg, #a855f7 0%, #8b5cf6 100%);
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #ffffff;
    margin: 0;
}

.section-link {
    font-size: 0.875rem;
    color: #22d3ee;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.section-link:hover {
    color: #67e8f9;
}

/* Matches List */
.matches-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.match-card {
    padding: 1.25rem;
    background: rgba(51, 65, 85, 0.3);
    border-radius: 0.75rem;
    border: 1px solid rgba(71, 85, 105, 0.3);
    transition: all 0.3s ease;
}

.match-card:hover {
    border-color: rgba(6, 182, 212, 0.3);
    background: rgba(51, 65, 85, 0.5);
}

.match-teams {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.team {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.team:last-child {
    flex-direction: row-reverse;
}

.team-avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    background: linear-gradient(135deg, #06b6d4 0%, #2563eb 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #ffffff;
    font-size: 1rem;
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

.team-avatar-alt {
    background: linear-gradient(135deg, #a855f7 0%, #8b5cf6 100%);
}

.team-name {
    font-weight: 600;
    color: #ffffff;
}

.vs-badge {
    padding: 0.375rem 0.75rem;
    background: rgba(51, 65, 85, 0.5);
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 700;
    color: #6b7280;
}

.match-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.match-date,
.match-venue {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.875rem;
    color: #9ca3af;
}

.info-icon {
    width: 1rem;
    height: 1rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 9999px;
    text-transform: capitalize;
}

.status-upcoming {
    background: rgba(59, 130, 246, 0.2);
    color: #60a5fa;
}

.status-ongoing {
    background: rgba(34, 197, 94, 0.2);
    color: #4ade80;
}

.status-paused {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
}

.status-finished {
    background: rgba(100, 116, 139, 0.2);
    color: #94a3b8;
}

/* Rankings List */
.rankings-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.ranking-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.875rem;
    background: rgba(51, 65, 85, 0.3);
    border-radius: 0.75rem;
    transition: all 0.3s ease;
}

.ranking-item:hover {
    background: rgba(51, 65, 85, 0.5);
}

.rank-badge {
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    font-weight: 700;
    color: #ffffff;
    background: rgba(51, 65, 85, 0.5);
    border-radius: 0.5rem;
}

.rank-1 {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
}
.rank-2 {
    background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
}
.rank-3 {
    background: linear-gradient(135deg, #cd7f32 0%, #a0522d 100%);
}

.team-info {
    flex: 1;
}

.team-info .team-name {
    display: block;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 0.25rem;
}

.team-record {
    font-size: 0.875rem;
}

.wins {
    color: #4ade80;
}
.losses {
    color: #f87171;
}
.separator {
    color: #6b7280;
    margin: 0 0.25rem;
}

.team-points {
    text-align: right;
}

.points-value {
    display: block;
    font-size: 1.25rem;
    font-weight: 700;
    color: #22d3ee;
}

.points-label {
    font-size: 0.625rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Announcements Section */
.announcements-section {
    margin-top: 0.5rem;
}

.announcements-grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 1rem;
}

@media (min-width: 768px) {
    .announcements-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .announcements-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.announcement-card {
    padding: 1.25rem;
    background: rgba(51, 65, 85, 0.3);
    border-radius: 0.75rem;
    border: 1px solid rgba(71, 85, 105, 0.3);
    transition: all 0.3s ease;
}

.announcement-card:hover {
    border-color: rgba(168, 85, 247, 0.3);
}

.announcement-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.75rem;
}

.announcement-date {
    font-size: 0.75rem;
    color: #6b7280;
}

.announcement-badge {
    padding: 0.125rem 0.5rem;
    background: rgba(168, 85, 247, 0.2);
    color: #c084fc;
    font-size: 0.625rem;
    font-weight: 700;
    border-radius: 0.25rem;
}

.announcement-title {
    font-size: 1rem;
    font-weight: 600;
    color: #ffffff;
    margin: 0 0 0.5rem 0;
}

.announcement-excerpt {
    font-size: 0.875rem;
    color: #9ca3af;
    line-height: 1.5;
    margin: 0 0 1rem 0;
}

.announcement-footer {
    display: flex;
    justify-content: flex-end;
}

.read-more {
    font-size: 0.875rem;
    color: #a855f7;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.read-more:hover {
    color: #c084fc;
}

/* Quick Links */
.quick-links {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

@media (min-width: 768px) {
    .quick-links {
        grid-template-columns: repeat(5, 1fr);
    }
}

.quick-link-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    padding: 1.5rem;
    background: rgba(30, 41, 59, 0.5);
    border: 1px solid rgba(51, 65, 85, 0.5);
    border-radius: 0.75rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.quick-link-card:hover {
    background: rgba(51, 65, 85, 0.5);
    border-color: rgba(6, 182, 212, 0.3);
    transform: translateY(-4px);
}

.quick-link-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quick-link-icon svg {
    width: 1.5rem;
    height: 1.5rem;
    color: #ffffff;
}

.quick-link-cyan {
    background: linear-gradient(135deg, #06b6d4 0%, #2563eb 100%);
}
.quick-link-green {
    background: linear-gradient(135deg, #22c55e 0%, #10b981 100%);
}
.quick-link-orange {
    background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
}
.quick-link-purple {
    background: linear-gradient(135deg, #a855f7 0%, #8b5cf6 100%);
}
.quick-link-gold {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.quick-link-text {
    font-weight: 600;
    color: #d1d5db;
    font-size: 0.875rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 2rem;
}

.empty-icon {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #6b7280;
    margin: 0;
}

/* Responsive */
@media (max-width: 640px) {
    .home-page {
        gap: 1.25rem;
        margin-left: -1.5rem;
        margin-right: -1.5rem;
    }

    .hero-section {
        padding: 2.5rem 1.25rem;
        border-radius: 0;
        border: none;
    }

    .off-season-banner {
        border-radius: 0;
        border: none;
    }

    .hero-title {
        font-size: 2rem;
        flex-direction: column;
        gap: 0.5rem;
    }

    .hero-icon {
        width: 7rem;
        height: 6rem;
    }

    .hero-subtitle {
        font-size: 1rem;
    }

    .hero-stats {
        gap: 1.5rem;
    }

    .stat-value {
        font-size: 1.75rem;
    }

    .hero-actions {
        flex-direction: column;
    }

    .btn-hero-primary,
    .btn-hero-secondary {
        width: 100%;
        justify-content: center;
    }

    .content-grid {
        gap: 1rem;
    }

    .glass-card {
        padding: 1rem;
        background: transparent;
        border: none;
        border-radius: 0;
        backdrop-filter: none;
    }

    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .section-title {
        font-size: 1.1rem;
    }

    .match-card {
        padding: 1rem;
    }

    .match-teams {
        flex-direction: row;
        gap: 0.75rem;
    }

    .team,
    .team:last-child {
        flex-direction: column;
        text-align: center;
    }

    .match-info {
        justify-content: center;
    }

    .ranking-item {
        padding: 0.625rem;
        gap: 0.75rem;
    }

    .rank-badge {
        width: 2rem;
        height: 2rem;
        font-size: 1rem;
    }

    .points-value {
        font-size: 1rem;
    }

    .team-info .team-name {
        font-size: 0.875rem;
    }

    .announcements-grid {
        gap: 0.75rem;
    }

    .announcement-card {
        padding: 1rem;
    }

    .quick-links {
        gap: 0.75rem;
        padding: 0 1rem;
    }

    .quick-link-card {
        padding: 1rem;
    }

    .quick-link-icon {
        width: 2.5rem;
        height: 2.5rem;
    }

    .quick-link-icon svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    .quick-link-text {
        font-size: 0.75rem;
    }
}
</style>
