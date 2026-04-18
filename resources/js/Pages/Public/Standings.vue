<template>
    <Head v-if="!embedded" title="Standings | SBA" />
    <component :is="embedded ? 'div' : PublicLayout">
        <div class="standings-page">
            <!-- Page Header -->
            <section class="page-header">
                <div class="header-bg"></div>
                <div class="header-content">
                    <div class="header-badge">TOURNAMENT BRACKETS</div>
                    <h1 class="header-title">
                        <span class="header-icon">🏆</span>
                        Standings
                    </h1>
                    <p class="header-subtitle">
                        View the tournament brackets for every division
                    </p>
                </div>
            </section>

            <!-- No Brackets Message -->
            <div v-if="bracketsByDivision.length === 0" class="no-brackets">
                <div class="empty-icon">🏀</div>
                <h3>No Brackets Available Yet</h3>
                <p>
                    Tournament brackets have not been set up yet. Check back
                    later!
                </p>
            </div>

            <!-- Division Brackets -->
            <div
                v-for="entry in bracketsByDivision"
                :key="entry.division.id"
                class="division-bracket"
            >
                <div class="division-header">
                    <span class="division-icon">🏅</span>
                    <h2 class="division-name">{{ entry.division.name }}</h2>
                </div>

                <div class="glass-card bracket-wrapper">
                    <!-- Tournament Title -->
                    <div class="de-title-bar">
                        <span class="de-title"
                            >{{ entry.division.name }} — TOURNAMENT
                            BRACKET</span
                        >
                    </div>

                    <div class="double-elim-visualization">
                        <div class="de-container">
                            <!-- Upper Bracket Section -->
                            <div class="de-section upper-section">
                                <div class="de-section-header">
                                    <span class="section-icon">▲</span>
                                    <span class="section-label"
                                        >Upper Bracket</span
                                    >
                                    <span class="section-tag winners-tag"
                                        >Winners</span
                                    >
                                </div>

                                <div
                                    v-if="entry.upperBracket.length === 0"
                                    class="de-empty"
                                >
                                    <p>No teams in upper bracket yet.</p>
                                </div>

                                <div v-else class="bracket-tree">
                                    <div
                                        v-for="round in getUniqueRounds(
                                            entry.upperBracket,
                                        )"
                                        :key="
                                            'ub-' +
                                            entry.division.id +
                                            '-round-' +
                                            round
                                        "
                                        class="bracket-round"
                                        :class="{
                                            'bracket-round-last':
                                                round ===
                                                getTotalRounds(
                                                    entry.upperBracket,
                                                ),
                                            'bracket-round-first': round === 1,
                                        }"
                                    >
                                        <div class="bracket-round-header">
                                            <span class="de-round-label"
                                                >Round {{ round }}</span
                                            >
                                        </div>
                                        <div class="bracket-games">
                                            <div
                                                v-for="(
                                                    pair, pairIndex
                                                ) in getMatchupPairs(
                                                    entry.upperBracket,
                                                    round,
                                                )"
                                                :key="
                                                    'ub-game-' +
                                                    entry.division.id +
                                                    '-' +
                                                    round +
                                                    '-' +
                                                    pairIndex
                                                "
                                                class="bracket-game-wrapper"
                                                :class="
                                                    getGameWrapperClass(
                                                        pairIndex,
                                                        round,
                                                        entry.upperBracket,
                                                    )
                                                "
                                            >
                                                <div
                                                    class="bracket-game bracket-game-upper"
                                                >
                                                    <div class="de-match-label">
                                                        Match
                                                        {{ pairIndex + 1 }}
                                                    </div>
                                                    <div class="de-teams">
                                                        <div
                                                            v-for="(
                                                                slot, slotIndex
                                                            ) in pair"
                                                            :key="slot.id"
                                                            class="de-team-row"
                                                            :class="{
                                                                'has-team':
                                                                    slot.team_id,
                                                            }"
                                                        >
                                                            <span
                                                                class="de-team-name"
                                                            >
                                                                {{
                                                                    slot.team
                                                                        ?.name ||
                                                                    `Team ${String.fromCharCode(65 + slotIndex)}`
                                                                }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="de-divider"></div>

                            <!-- Lower Bracket Section -->
                            <div class="de-section lower-section">
                                <div class="de-section-header">
                                    <span class="section-icon">▼</span>
                                    <span class="section-label"
                                        >Lower Bracket</span
                                    >
                                    <span class="section-tag losers-tag"
                                        >Losers</span
                                    >
                                </div>

                                <div
                                    v-if="entry.lowerBracket.length === 0"
                                    class="de-empty"
                                >
                                    <p>No teams in lower bracket yet.</p>
                                </div>

                                <div
                                    v-else
                                    class="bracket-tree bracket-tree-lower"
                                >
                                    <div
                                        v-for="round in getUniqueRounds(
                                            entry.lowerBracket,
                                        )"
                                        :key="
                                            'lb-' +
                                            entry.division.id +
                                            '-round-' +
                                            round
                                        "
                                        class="bracket-round bracket-round-lower"
                                        :class="{
                                            'bracket-round-last':
                                                round ===
                                                getTotalRounds(
                                                    entry.lowerBracket,
                                                ),
                                            'bracket-round-first': round === 1,
                                        }"
                                    >
                                        <div class="bracket-round-header">
                                            <span class="de-round-label"
                                                >LB Round {{ round }}</span
                                            >
                                        </div>
                                        <div class="bracket-games">
                                            <div
                                                v-for="(
                                                    pair, pairIndex
                                                ) in getMatchupPairs(
                                                    entry.lowerBracket,
                                                    round,
                                                )"
                                                :key="
                                                    'lb-game-' +
                                                    entry.division.id +
                                                    '-' +
                                                    round +
                                                    '-' +
                                                    pairIndex
                                                "
                                                class="bracket-game-wrapper"
                                                :class="
                                                    getGameWrapperClass(
                                                        pairIndex,
                                                        round,
                                                        entry.lowerBracket,
                                                    )
                                                "
                                            >
                                                <div
                                                    class="bracket-game bracket-game-lower"
                                                >
                                                    <div class="de-match-label">
                                                        {{
                                                            round === 1
                                                                ? `Loser Match ${pairIndex + 1}`
                                                                : `LB Match ${pairIndex + 1}`
                                                        }}
                                                    </div>
                                                    <div class="de-teams">
                                                        <div
                                                            v-for="(
                                                                slot, slotIndex
                                                            ) in pair"
                                                            :key="slot.id"
                                                            class="de-team-row de-team-row-lower"
                                                            :class="{
                                                                'has-team':
                                                                    slot.team_id,
                                                            }"
                                                        >
                                                            <span
                                                                class="de-team-name"
                                                            >
                                                                {{
                                                                    slot.team
                                                                        ?.name ||
                                                                    (round === 1
                                                                        ? `Loser ${slotIndex + 1}`
                                                                        : `LB Team ${slotIndex + 1}`)
                                                                }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="de-divider"></div>
                        </div>
                    </div>
                    <div class="scroll-hint">← Scroll →</div>
                </div>

                <!-- Grand Finals Section (separate card for mobile) -->
                <div class="glass-card finals-card">
                    <div class="de-section-header finals-header">
                        <span class="section-icon">🏆</span>
                        <span class="section-label">Grand Finals</span>
                        <span class="section-tag finals-tag">Championship</span>
                    </div>

                    <div class="grand-finals-match">
                        <div class="gf-contestant gf-upper">
                            <span class="gf-label">Upper Bracket Winner</span>
                            <div class="gf-team">
                                {{ getFinalist(entry, "upper") || "TBD" }}
                            </div>
                        </div>
                        <div class="gf-vs">
                            <span class="vs-text">VS</span>
                        </div>
                        <div class="gf-contestant gf-lower">
                            <span class="gf-label">Lower Bracket Winner</span>
                            <div class="gf-team">
                                {{ getFinalist(entry, "lower") || "TBD" }}
                            </div>
                        </div>
                    </div>

                    <div class="gf-champion">
                        <div class="champion-crown">👑</div>
                        <span class="champion-label">CHAMPION</span>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";

const props = defineProps({
    bracketsByDivision: {
        type: Array,
        default: () => [],
    },
    embedded: {
        type: Boolean,
        default: false,
    },
});

const getUniqueRounds = (bracket) => {
    const rounds = [...new Set(bracket.map((s) => s.round))];
    return rounds.sort((a, b) => a - b);
};

const getTotalRounds = (bracket) => {
    if (bracket.length === 0) return 0;
    return Math.max(...bracket.map((s) => s.round));
};

const getSlotsForRound = (bracket, round) => {
    return bracket
        .filter((s) => s.round === round)
        .sort((a, b) => a.position - b.position);
};

const getMatchupPairs = (bracket, round) => {
    const slots = getSlotsForRound(bracket, round);
    const pairs = [];
    for (let i = 0; i < slots.length; i += 2) {
        if (slots[i + 1]) {
            pairs.push([slots[i], slots[i + 1]]);
        } else {
            pairs.push([slots[i]]);
        }
    }
    return pairs;
};

const getFinalist = (entry, side) => {
    const bracket = side === "upper" ? entry.upperBracket : entry.lowerBracket;
    const totalRounds = getTotalRounds(bracket);
    if (totalRounds === 0) return null;
    const finalsSlots = bracket.filter((s) => s.round === totalRounds);
    if (finalsSlots.length > 0 && finalsSlots[0].team) {
        return finalsSlots[0].team.name;
    }
    return null;
};

const getGameWrapperClass = (pairIndex, round, bracket) => {
    const totalRounds = getTotalRounds(bracket);
    const matchCount = getMatchupPairs(bracket, round).length;
    const isLast = round === totalRounds;

    if (isLast) return {};
    if (matchCount === 1) return { "game-single": true };

    return {
        "game-top": pairIndex % 2 === 0,
        "game-bottom": pairIndex % 2 === 1,
    };
};
</script>

<style scoped>
.standings-page {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

/* Page Header */
.page-header {
    position: relative;
    padding: 3rem 2rem;
    border-radius: 1rem;
    overflow: hidden;
    background: linear-gradient(
        135deg,
        rgba(15, 23, 42, 0.9) 0%,
        rgba(30, 41, 59, 0.9) 100%
    );
    border: 1px solid var(--cv-border-1);
}

.header-bg {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(
            circle at 20% 50%,
            rgba(251, 191, 36, 0.14) 0%,
            transparent 50%
        ),
        radial-gradient(
            circle at 80% 50%,
            rgba(245, 158, 11, 0.12) 0%,
            transparent 50%
        );
}

.header-content {
    position: relative;
    text-align: center;
    z-index: 1;
}

.header-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: linear-gradient(
        135deg,
        rgba(251, 191, 36, 0.2) 0%,
        rgba(245, 158, 11, 0.2) 100%
    );
    border: 1px solid rgba(251, 191, 36, 0.35);
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    color: rgba(251, 191, 36, 0.95);
    letter-spacing: 0.1em;
    margin-bottom: 1rem;
}

.header-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 0.75rem 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.header-icon {
    font-size: 2.5rem;
}

.header-subtitle {
    font-size: 1.125rem;
    color: #9ca3af;
    margin: 0;
}

/* No Brackets */
.no-brackets {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--cv-surface-1);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
}

.no-brackets .empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.no-brackets h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--cv-text-1);
    margin: 0 0 0.5rem 0;
}

.no-brackets p {
    color: var(--cv-muted);
    margin: 0;
}

/* Division Bracket */
.division-bracket {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.division-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.division-icon {
    font-size: 1.5rem;
}

.division-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--cv-text-1);
    margin: 0;
}

/* Glass Card */
.glass-card {
    background: var(--cv-surface-1);
    backdrop-filter: blur(4px);
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
}

/* Double Elimination Styles */
.scroll-hint {
    display: none;
}

.de-container {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.de-title-bar {
    background: linear-gradient(
        135deg,
        rgba(251, 191, 36, 0.2) 0%,
        rgba(245, 158, 11, 0.18) 100%
    );
    border: 2px solid var(--cv-border-2);
    border-radius: 0.5rem 0.5rem 0 0;
    padding: 1rem;
    text-align: center;
}

.de-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--cv-text-1);
    letter-spacing: 0.1em;
}

.de-section {
    background: var(--cv-surface-1);
    border: 2px solid var(--cv-border-1);
    border-top: none;
    padding: 1.5rem;
}

.de-section-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px dashed var(--cv-border-1);
}

.section-icon {
    font-size: 1rem;
}

.section-label {
    font-size: 1rem;
    font-weight: 600;
    color: var(--cv-text-1);
}

.section-tag {
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.625rem;
    font-weight: 600;
    text-transform: uppercase;
}

.winners-tag {
    background: rgba(34, 197, 94, 0.2);
    color: #4ade80;
}

.losers-tag {
    background: rgba(245, 158, 11, 0.2);
    color: #fbbf24;
}

.finals-tag {
    background: rgba(251, 191, 36, 0.2);
    color: rgba(251, 191, 36, 0.95);
}

.de-empty {
    text-align: center;
    padding: 2rem;
    color: var(--cv-muted);
    font-style: italic;
}

/* Bracket Tree Layout */
.bracket-tree {
    display: flex;
    padding: 0.5rem 0;
    overflow-x: auto;
}

.bracket-round {
    display: flex;
    flex-direction: column;
    min-width: 180px;
    flex-shrink: 0;
}

.bracket-round-header {
    text-align: center;
    margin-bottom: 0.5rem;
}

.de-round-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--cv-muted);
    text-transform: uppercase;
    margin-bottom: 0.75rem;
    text-align: center;
}

.bracket-games {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.bracket-game-wrapper {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    padding: 0.25rem 1.5rem;
    min-height: 5.5rem;
}

.bracket-game {
    background: var(--cv-surface-2);
    border: 1px solid rgba(34, 197, 94, 0.3);
    border-radius: 0.5rem;
    padding: 0.375rem 0.5rem;
    min-width: 140px;
    max-width: 160px;
    z-index: 2;
    position: relative;
}

.bracket-game-lower {
    border-color: rgba(245, 158, 11, 0.3);
}

/* === Bracket Connector Lines === */

/* Right bracket arm: TOP game of pair */
.bracket-round:not(.bracket-round-last) .game-top::after {
    content: "";
    position: absolute;
    right: 0;
    top: 50%;
    bottom: 0;
    width: 1.5rem;
    border-top: 2px solid rgba(34, 197, 94, 0.6);
    border-right: 2px solid rgba(34, 197, 94, 0.6);
    border-radius: 0 6px 0 0;
}

/* Right bracket arm: BOTTOM game of pair */
.bracket-round:not(.bracket-round-last) .game-bottom::after {
    content: "";
    position: absolute;
    right: 0;
    top: 0;
    bottom: 50%;
    width: 1.5rem;
    border-bottom: 2px solid rgba(34, 197, 94, 0.6);
    border-right: 2px solid rgba(34, 197, 94, 0.6);
    border-radius: 0 0 6px 0;
}

/* Right connector for a single game (horizontal line) */
.bracket-round:not(.bracket-round-last) .game-single::after {
    content: "";
    position: absolute;
    right: 0;
    top: calc(50% - 1px);
    width: 1.5rem;
    height: 2px;
    background: rgba(34, 197, 94, 0.6);
}

/* Left connector: horizontal line from previous round merge point */
.bracket-round:not(.bracket-round-first) .bracket-game-wrapper::before {
    content: "";
    position: absolute;
    left: 0;
    top: calc(50% - 1px);
    width: 1.5rem;
    height: 2px;
    background: rgba(34, 197, 94, 0.6);
}

/* Lower bracket connector colors */
.bracket-round-lower:not(.bracket-round-last) .game-top::after {
    border-color: rgba(245, 158, 11, 0.6);
}

.bracket-round-lower:not(.bracket-round-last) .game-bottom::after {
    border-color: rgba(245, 158, 11, 0.6);
}

.bracket-round-lower:not(.bracket-round-last) .game-single::after {
    background: rgba(245, 158, 11, 0.6);
}

.bracket-round-lower:not(.bracket-round-first) .bracket-game-wrapper::before {
    background: rgba(245, 158, 11, 0.6);
}

.de-match-label {
    font-size: 0.625rem;
    color: var(--cv-muted);
    margin-bottom: 0.5rem;
    text-transform: uppercase;
}

.de-teams {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.de-team-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.375rem 0.5rem;
    background: var(--cv-surface-3);
    border: 1px solid var(--cv-border-1);
    border-radius: 0.25rem;
    transition: all 0.2s ease;
}

.de-team-row.has-team {
    background: rgba(34, 197, 94, 0.15);
    border-color: rgba(34, 197, 94, 0.5);
}

.de-team-row-lower.has-team {
    background: rgba(245, 158, 11, 0.15);
    border-color: rgba(245, 158, 11, 0.5);
}

.de-team-name {
    flex: 1;
    color: var(--cv-text-1);
    font-size: 0.75rem;
    font-weight: 500;
}

.de-team-row:not(.has-team) .de-team-name {
    color: var(--cv-muted-2);
    font-style: italic;
}

/* Divider */
.de-divider {
    height: 2px;
    background: linear-gradient(
        90deg,
        transparent 0%,
        rgba(71, 85, 105, 0.5) 20%,
        rgba(71, 85, 105, 0.5) 80%,
        transparent 100%
    );
}

/* Finals Card (separate from scrollable bracket) */
.finals-card {
    padding: 1.5rem;
    border-top: 3px solid rgba(251, 191, 36, 0.4);
}

.finals-header {
    border-bottom-color: rgba(251, 191, 36, 0.5);
}

.grand-finals-match {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: linear-gradient(
        135deg,
        rgba(251, 191, 36, 0.12) 0%,
        rgba(245, 158, 11, 0.08) 100%
    );
    border: 2px solid rgba(251, 191, 36, 0.3);
    border-radius: 0.75rem;
    margin-bottom: 1rem;
}

.gf-contestant {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    flex: 1;
    max-width: 200px;
}

.gf-label {
    font-size: 0.625rem;
    color: var(--cv-muted);
    text-transform: uppercase;
}

.gf-team {
    padding: 0.75rem 1rem;
    background: var(--cv-surface-3);
    border-radius: 0.5rem;
    font-weight: 600;
    color: var(--cv-text-1);
    text-align: center;
    width: 100%;
}

.gf-upper .gf-team {
    border: 2px solid rgba(34, 197, 94, 0.5);
}

.gf-lower .gf-team {
    border: 2px solid rgba(245, 158, 11, 0.5);
}

.gf-vs {
    padding: 0.5rem 1rem;
    background: rgba(251, 191, 36, 0.18);
    border-radius: 9999px;
}

.vs-text {
    font-size: 1rem;
    font-weight: 700;
    color: rgba(251, 191, 36, 0.95);
}

.gf-champion {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: linear-gradient(
        135deg,
        rgba(251, 191, 36, 0.2) 0%,
        rgba(245, 158, 11, 0.1) 100%
    );
    border: 2px dashed rgba(251, 191, 36, 0.4);
    border-radius: 0.75rem;
}

.champion-crown {
    font-size: 2rem;
}

.champion-label {
    font-size: 0.875rem;
    font-weight: 700;
    color: #fbbf24;
    letter-spacing: 0.2em;
}

/* Responsive */
@media (max-width: 768px) {
    .standings-page {
        gap: 1.5rem;
    }

    .page-header {
        padding: 2rem 1.25rem;
    }

    .header-badge {
        font-size: 0.625rem;
        padding: 0.375rem 0.75rem;
    }

    .header-title {
        font-size: 1.75rem;
        flex-direction: column;
        gap: 0.5rem;
    }

    .header-icon {
        font-size: 2rem;
    }

    .header-subtitle {
        font-size: 1rem;
    }

    .division-name {
        font-size: 1.25rem;
    }

    .de-title {
        font-size: 1rem;
        letter-spacing: 0.05em;
    }

    .de-title-bar {
        padding: 0.75rem;
    }

    .de-section {
        padding: 1rem;
    }

    .de-section-header {
        gap: 0.5rem;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
    }

    .section-label {
        font-size: 0.875rem;
    }

    /* Scroll hint outside the scrollable area */
    .scroll-hint {
        display: block;
        text-align: center;
        padding: 0.5rem;
        font-size: 0.675rem;
        color: var(--cv-muted-2);
        letter-spacing: 0.05em;
        background: var(--cv-surface-2);
        border-top: 1px solid var(--cv-border-1);
    }

    .bracket-round {
        min-width: 150px;
    }

    .bracket-game-wrapper {
        padding: 0.25rem 1rem;
        min-height: 4.5rem;
    }

    .bracket-game {
        min-width: 120px;
        max-width: 140px;
        padding: 0.25rem 0.375rem;
    }

    .de-team-row {
        padding: 0.25rem 0.375rem;
    }

    .de-team-name {
        font-size: 0.675rem;
    }

    .de-match-label {
        font-size: 0.5625rem;
        margin-bottom: 0.375rem;
    }

    /* Grand Finals mobile */
    .finals-card {
        padding: 1rem;
    }

    .grand-finals-match {
        flex-direction: column;
        gap: 1rem;
        padding: 1rem;
    }

    .gf-contestant {
        max-width: 100%;
        width: 100%;
    }

    .gf-team {
        padding: 0.625rem 0.75rem;
        font-size: 0.875rem;
    }

    .gf-label {
        font-size: 0.5625rem;
    }

    .gf-vs {
        padding: 0.375rem 0.75rem;
    }

    .vs-text {
        font-size: 0.875rem;
    }

    .gf-champion {
        padding: 0.75rem;
    }

    .champion-crown {
        font-size: 1.5rem;
    }

    .champion-label {
        font-size: 0.75rem;
        letter-spacing: 0.15em;
    }

    /* Connector lines thinner on mobile */
    .bracket-round:not(.bracket-round-last) .game-top::after,
    .bracket-round:not(.bracket-round-last) .game-bottom::after {
        width: 1rem;
    }

    .bracket-round:not(.bracket-round-last) .game-single::after {
        width: 1rem;
    }

    .bracket-round:not(.bracket-round-first) .bracket-game-wrapper::before {
        width: 1rem;
    }
}

@media (max-width: 480px) {
    .page-header {
        padding: 1.5rem 1rem;
    }

    .header-title {
        font-size: 1.5rem;
    }

    .header-icon {
        font-size: 1.75rem;
    }

    .header-subtitle {
        font-size: 0.875rem;
    }

    .no-brackets {
        padding: 2.5rem 1.25rem;
    }

    .no-brackets .empty-icon {
        font-size: 2.5rem;
    }

    .no-brackets h3 {
        font-size: 1.1rem;
    }

    .division-header {
        gap: 0.5rem;
    }

    .division-icon {
        font-size: 1.25rem;
    }

    .division-name {
        font-size: 1.1rem;
    }

    .de-title {
        font-size: 0.8rem;
    }

    .bracket-round {
        min-width: 130px;
    }

    .bracket-game {
        min-width: 105px;
        max-width: 120px;
    }

    .bracket-game-wrapper {
        padding: 0.2rem 0.75rem;
        min-height: 4rem;
    }

    .de-team-name {
        font-size: 0.625rem;
    }

    .de-round-label {
        font-size: 0.625rem;
    }

    .grand-finals-match {
        padding: 0.75rem;
        gap: 0.75rem;
    }

    .gf-team {
        padding: 0.5rem 0.625rem;
        font-size: 0.8rem;
    }
}
</style>
