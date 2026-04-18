<template>
    <Head title="Standings & Playoffs - Admin | SBA" />
    <AdminLayout>
        <div class="standings-page">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1 class="page-title">Tournament Bracket</h1>
                    <p class="page-subtitle">Manage playoff bracket slots</p>
                </div>
            </div>

            <!-- Division Selector -->
            <div class="glass-card division-selector-section">
                <h2 class="section-title">Select Division</h2>
                <div class="division-tabs">
                    <button
                        v-for="division in divisions"
                        :key="division.id"
                        @click="selectDivision(division.id)"
                        :class="[
                            'division-tab',
                            { active: selectedDivision === division.id },
                        ]"
                    >
                        <span class="division-tab-name">{{
                            division.name
                        }}</span>
                        <span class="division-tab-count"
                            >{{ division.teams?.length || 0 }} teams</span
                        >
                    </button>
                </div>
                <div v-if="!selectedDivision" class="division-prompt">
                    <span class="prompt-icon">📋</span>
                    <p>
                        Please select a division above to manage its tournament
                        bracket.
                    </p>
                </div>
            </div>

            <!-- Double Elimination Controls -->
            <div v-if="selectedDivision" class="glass-card controls-section">
                <h2 class="section-title">Configure Double Elimination</h2>
                <div class="controls-grid">
                    <div class="control-group">
                        <h3 class="bracket-label upper-bracket-label">
                            <span class="bracket-icon">▲</span> Upper Bracket
                            (Winners)
                        </h3>
                        <div class="generator-form">
                            <select
                                v-model="upperForm.num_teams"
                                class="form-select"
                            >
                                <option value="2">2 Teams</option>
                                <option value="4">4 Teams</option>
                                <option value="8">8 Teams</option>
                                <option value="16">16 Teams</option>
                            </select>
                            <button
                                @click="generateBracket('upper')"
                                class="btn btn-success"
                            >
                                Generate Upper
                            </button>
                            <button
                                @click="clearBracket('upper')"
                                class="btn btn-danger btn-sm"
                            >
                                Clear
                            </button>
                        </div>
                    </div>
                    <div class="control-group">
                        <h3 class="bracket-label lower-bracket-label">
                            <span class="bracket-icon">▼</span> Lower Bracket
                            (Losers)
                        </h3>
                        <div class="generator-form">
                            <select
                                v-model="lowerForm.num_teams"
                                class="form-select"
                            >
                                <option value="2">
                                    2 Teams (for 4-team Upper)
                                </option>
                                <option value="4">
                                    4 Teams (for 8-team Upper)
                                </option>
                                <option value="8">
                                    8 Teams (for 16-team Upper)
                                </option>
                            </select>
                            <button
                                @click="generateBracket('lower')"
                                class="btn btn-warning"
                            >
                                Generate Lower
                            </button>
                            <button
                                @click="clearBracket('lower')"
                                class="btn btn-danger btn-sm"
                            >
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Double Elimination Visualization -->
            <div v-if="selectedDivision" class="glass-card">
                <!-- Tournament Title -->
                <div class="de-title-bar">
                    <span class="de-title">TOURNAMENT STANDINGS</span>
                </div>

                <div class="double-elim-visualization">
                    <div class="de-container">
                        <!-- Upper Bracket Section -->
                        <div class="de-section upper-section">
                            <div class="de-section-header">
                                <span class="section-icon">▲</span>
                                <span class="section-label">Upper Bracket</span>
                                <span class="section-tag winners-tag"
                                    >Winners</span
                                >
                            </div>

                            <div
                                v-if="upperBracket.length === 0"
                                class="de-empty"
                            >
                                <p>
                                    No teams in upper bracket. Generate to add
                                    teams.
                                </p>
                            </div>

                            <div v-else class="bracket-tree">
                                <div
                                    v-for="round in getUniqueRounds(
                                        upperBracket,
                                    )"
                                    :key="'ub-round-' + round"
                                    class="bracket-round"
                                    :class="{
                                        'bracket-round-last':
                                            round ===
                                            getTotalRounds(upperBracket),
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
                                                upperBracket,
                                                round,
                                            )"
                                            :key="
                                                'ub-game-' +
                                                round +
                                                '-' +
                                                pairIndex
                                            "
                                            class="bracket-game-wrapper"
                                            :class="
                                                getGameWrapperClass(
                                                    pairIndex,
                                                    round,
                                                    upperBracket,
                                                )
                                            "
                                        >
                                            <div
                                                class="bracket-game bracket-game-upper"
                                            >
                                                <div class="de-match-label">
                                                    Match {{ pairIndex + 1 }}
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
                                                        <select
                                                            v-model="
                                                                slot.team_id
                                                            "
                                                            @change="
                                                                updateSlot(slot)
                                                            "
                                                            class="de-team-select"
                                                        >
                                                            <option
                                                                :value="null"
                                                            >
                                                                Team
                                                                {{
                                                                    String.fromCharCode(
                                                                        65 +
                                                                            slotIndex,
                                                                    )
                                                                }}
                                                            </option>
                                                            <option
                                                                v-for="team in teams"
                                                                :key="team.id"
                                                                :value="team.id"
                                                            >
                                                                {{
                                                                    getTeamDisplayName(
                                                                        team,
                                                                    )
                                                                }}
                                                            </option>
                                                        </select>
                                                        <button
                                                            @click="
                                                                removeSlot(slot)
                                                            "
                                                            class="de-remove-btn"
                                                        >
                                                            ×
                                                        </button>
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
                                <span class="section-label">Lower Bracket</span>
                                <span class="section-tag losers-tag"
                                    >Losers</span
                                >
                            </div>

                            <div
                                v-if="lowerBracket.length === 0"
                                class="de-empty"
                            >
                                <p>
                                    Losers from upper bracket matches will
                                    appear here.
                                </p>
                            </div>

                            <div v-else class="bracket-tree bracket-tree-lower">
                                <div
                                    v-for="round in getUniqueRounds(
                                        lowerBracket,
                                    )"
                                    :key="'lb-round-' + round"
                                    class="bracket-round bracket-round-lower"
                                    :class="{
                                        'bracket-round-last':
                                            round ===
                                            getTotalRounds(lowerBracket),
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
                                                lowerBracket,
                                                round,
                                            )"
                                            :key="
                                                'lb-game-' +
                                                round +
                                                '-' +
                                                pairIndex
                                            "
                                            class="bracket-game-wrapper"
                                            :class="
                                                getGameWrapperClass(
                                                    pairIndex,
                                                    round,
                                                    lowerBracket,
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
                                                        <select
                                                            v-model="
                                                                slot.team_id
                                                            "
                                                            @change="
                                                                updateSlot(slot)
                                                            "
                                                            class="de-team-select de-team-select-lower"
                                                        >
                                                            <option
                                                                :value="null"
                                                            >
                                                                {{
                                                                    round === 1
                                                                        ? `Loser ${slotIndex + 1}`
                                                                        : `LB Team ${slotIndex + 1}`
                                                                }}
                                                            </option>
                                                            <option
                                                                v-for="team in teams"
                                                                :key="team.id"
                                                                :value="team.id"
                                                            >
                                                                {{
                                                                    getTeamDisplayName(
                                                                        team,
                                                                    )
                                                                }}
                                                            </option>
                                                        </select>
                                                        <button
                                                            @click="
                                                                removeSlot(slot)
                                                            "
                                                            class="de-remove-btn"
                                                        >
                                                            ×
                                                        </button>
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

                        <!-- Grand Finals Section -->
                        <div class="de-section finals-section">
                            <div class="de-section-header finals-header">
                                <span class="section-icon">🏆</span>
                                <span class="section-label">Grand Finals</span>
                                <span class="section-tag finals-tag"
                                    >Championship</span
                                >
                            </div>

                            <div class="grand-finals-match">
                                <div class="gf-contestant gf-upper">
                                    <span class="gf-label"
                                        >Upper Bracket Winner</span
                                    >
                                    <div class="gf-team">
                                        {{ getFinalist("upper") || "TBD" }}
                                    </div>
                                </div>
                                <div class="gf-vs">
                                    <span class="vs-text">VS</span>
                                </div>
                                <div class="gf-contestant gf-lower">
                                    <span class="gf-label"
                                        >Lower Bracket Winner</span
                                    >
                                    <div class="gf-team">
                                        {{ getFinalist("lower") || "TBD" }}
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
            </div>

            <!-- Quick Add Section -->
            <div v-if="selectedDivision" class="glass-card quick-actions">
                <h3 class="section-title">Quick Actions</h3>
                <div class="quick-buttons">
                    <button
                        @click="showAddSlotModal('upper')"
                        class="btn btn-outline"
                    >
                        + Add Slot to Upper Bracket
                    </button>
                    <button
                        @click="showAddSlotModal('lower')"
                        class="btn btn-outline"
                    >
                        + Add Slot to Lower Bracket
                    </button>
                </div>
            </div>

            <!-- Add Slot Modal -->
            <div
                v-if="showModal"
                class="modal-overlay"
                @click.self="closeModal"
            >
                <div class="modal-content">
                    <h3 class="modal-title">
                        Add Slot to
                        {{
                            modalBracketSide === "upper"
                                ? "Upper Bracket"
                                : "Lower Bracket"
                        }}
                    </h3>
                    <form @submit.prevent="addSlot">
                        <div class="form-group">
                            <label class="form-label">Round</label>
                            <input
                                v-model="newSlot.round"
                                type="number"
                                min="1"
                                class="form-input"
                                required
                            />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Position (Seed)</label>
                            <input
                                v-model="newSlot.position"
                                type="number"
                                min="1"
                                class="form-input"
                                required
                            />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Label (Optional)</label>
                            <input
                                v-model="newSlot.label"
                                type="text"
                                class="form-input"
                                placeholder="e.g., Seed 1, Wild Card"
                            />
                        </div>
                        <div class="modal-actions">
                            <button
                                type="button"
                                @click="closeModal"
                                class="btn btn-secondary"
                            >
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Add Slot
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <ConfirmModal
            :show="showConfirmModal"
            :title="confirmModalTitle"
            :message="confirmModalMessage"
            :confirmText="confirmModalAction"
            @confirm="executeConfirmAction"
            @cancel="showConfirmModal = false"
        />
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed } from "vue";
import { Head, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ConfirmModal from "@/Components/ConfirmModal.vue";

const props = defineProps({
    divisions: { type: Array, default: () => [] },
    upperBracket: { type: Array, default: () => [] },
    lowerBracket: { type: Array, default: () => [] },
    teams: { type: Array, default: () => [] },
    selectedDivisionId: { type: Number, default: null },
});

// Selected division
const selectedDivision = ref(props.selectedDivisionId);

const selectDivision = (divisionId) => {
    selectedDivision.value = divisionId;
    router.get(
        route("admin.standings.index"),
        { division_id: divisionId },
        { preserveState: false },
    );
};

// Form data for generating brackets
const upperForm = reactive({ num_teams: 4 });
const lowerForm = reactive({ num_teams: 4 });

// Confirm modal state
const showConfirmModal = ref(false);
const confirmModalTitle = ref("");
const confirmModalMessage = ref("");
const confirmModalAction = ref("Delete");
const confirmCallback = ref(null);

const openConfirmModal = (title, message, action, callback) => {
    confirmModalTitle.value = title;
    confirmModalMessage.value = message;
    confirmModalAction.value = action;
    confirmCallback.value = callback;
    showConfirmModal.value = true;
};

const executeConfirmAction = () => {
    if (confirmCallback.value) confirmCallback.value();
    showConfirmModal.value = false;
};

// Modal state
const showModal = ref(false);
const modalBracketSide = ref("upper");
const newSlot = reactive({
    round: 1,
    position: 1,
    label: "",
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

const getFinalist = (side) => {
    const bracket = side === "upper" ? props.upperBracket : props.lowerBracket;
    const totalRounds = getTotalRounds(bracket);
    if (totalRounds === 0) return null;
    const finalsSlots = bracket.filter((s) => s.round === totalRounds);
    if (finalsSlots.length > 0 && finalsSlots[0].team) {
        return finalsSlots[0].team.name;
    }
    return null;
};

// Get team IDs that are in the lower bracket (loser teams)
const lowerBracketTeamIds = computed(() => {
    return new Set(
        props.lowerBracket.filter((s) => s.team_id).map((s) => s.team_id),
    );
});

// Get team IDs that are in the upper bracket
const upperBracketTeamIds = computed(() => {
    return new Set(
        props.upperBracket.filter((s) => s.team_id).map((s) => s.team_id),
    );
});

// Check if a team is in the lower bracket
const isLoserTeam = (teamId) => {
    return lowerBracketTeamIds.value.has(teamId);
};

// Check if a team is in the upper bracket
const isWinnerTeam = (teamId) => {
    return upperBracketTeamIds.value.has(teamId);
};

// Get team display name with bracket indicator
const getTeamDisplayName = (team) => {
    let name = team.name;
    if (isLoserTeam(team.id)) {
        name = `[L] ${name}`;
    } else if (isWinnerTeam(team.id)) {
        name = `[W] ${name}`;
    }
    return name;
};

// Get CSS classes for game wrapper (bracket connector logic)
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

const generateBracket = (side) => {
    const form = side === "upper" ? upperForm : lowerForm;
    router.post(route("admin.standings.generate-bracket"), {
        bracket_side: side,
        num_teams: form.num_teams,
        division_id: selectedDivision.value,
    });
};

const clearBracket = (side) => {
    const label = side === "upper" ? "Upper Bracket" : "Lower Bracket";
    openConfirmModal(
        `Clear ${label}`,
        `Are you sure you want to clear the ${label}? All slots will be removed.`,
        "Clear",
        () => {
            router.post(route("admin.standings.clear-bracket"), {
                bracket_side: side,
                division_id: selectedDivision.value,
            });
        },
    );
};

const updateSlot = (slot) => {
    router.put(route("admin.standings.update-slot", slot.id), {
        team_id: slot.team_id,
        label: slot.label,
    });
};

const removeSlot = (slot) => {
    openConfirmModal(
        "Remove Slot",
        "Are you sure you want to remove this slot?",
        "Remove",
        () => {
            router.delete(route("admin.standings.remove-slot", slot.id));
        },
    );
};

const showAddSlotModal = (side) => {
    modalBracketSide.value = side;
    newSlot.round = 1;
    newSlot.position = 1;
    newSlot.label = "";
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const addSlot = () => {
    router.post(route("admin.standings.add-slot"), {
        bracket_side: modalBracketSide.value,
        round: newSlot.round,
        position: newSlot.position,
        label: newSlot.label || null,
        division_id: selectedDivision.value,
    });
    closeModal();
};
</script>

<style scoped>
.standings-page {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.header {
    display: flex;
    align-items: center;
    justify-content: space-between;
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

.glass-card {
    background: rgba(30, 41, 59, 0.5);
    backdrop-filter: blur(4px);
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
}

.section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #ffffff;
    margin: 0 0 1rem 0;
}

/* Division Selector */
.division-selector-section {
    background: linear-gradient(
        135deg,
        rgba(30, 41, 59, 0.8) 0%,
        rgba(51, 65, 85, 0.5) 100%
    );
}

.division-tabs {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.division-tab {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
    padding: 1rem 1.5rem;
    background: rgba(30, 41, 59, 0.6);
    border: 2px solid rgba(71, 85, 105, 0.5);
    border-radius: 0.75rem;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 120px;
}

.division-tab:hover {
    border-color: rgba(var(--cv-accent-1-rgb), 0.5);
    background: rgba(var(--cv-accent-1-rgb), 0.1);
}

.division-tab.active {
    border-color: var(--cv-accent-1);
    background: rgba(var(--cv-accent-1-rgb), 0.15);
    box-shadow: 0 0 20px rgba(var(--cv-accent-1-rgb), 0.2);
}

.division-tab-name {
    font-size: 1rem;
    font-weight: 600;
    color: #ffffff;
}

.division-tab.active .division-tab-name {
    color: rgba(var(--cv-accent-1-rgb), 0.95);
}

.division-tab-count {
    font-size: 0.75rem;
    color: #9ca3af;
}

.division-prompt {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 2rem;
    margin-top: 1rem;
    text-align: center;
    color: #9ca3af;
    background: rgba(30, 41, 59, 0.4);
    border-radius: 0.5rem;
    border: 1px dashed rgba(71, 85, 105, 0.5);
}

.prompt-icon {
    font-size: 2rem;
    opacity: 0.5;
}

.division-prompt p {
    margin: 0;
    font-size: 0.875rem;
}

/* Controls Section */
.controls-section {
    background: var(--cv-surface-1);
}

.controls-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

@media (max-width: 768px) {
    .controls-grid {
        grid-template-columns: 1fr;
    }
}

.control-group {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.bracket-label {
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.upper-bracket-label {
    color: #e5e7eb;
}
.lower-bracket-label {
    color: #e5e7eb;
}

.bracket-icon {
    font-size: 0.75rem;
}

.generator-form {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
}

.form-select {
    padding: 0.5rem 1rem;
    background: rgba(30, 41, 59, 0.8);
    border: 1px solid rgba(71, 85, 105, 0.5);
    border-radius: 0.5rem;
    color: #ffffff;
    font-size: 0.875rem;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
}

.btn-primary {
    background: linear-gradient(
        135deg,
        var(--cv-accent-1) 0%,
        var(--cv-accent-2) 100%
    );
    color: #ffffff;
}

.btn-secondary {
    background: rgba(51, 65, 85, 0.5);
    color: #d1d5db;
    border: 1px solid rgba(71, 85, 105, 0.5);
}

.btn-secondary-alt {
    background: var(--cv-surface-2);
    color: #e5e7eb;
    border: 1px solid var(--cv-border-1);
}

.btn-success {
    background: var(--cv-surface-2);
    color: #e5e7eb;
    border: 1px solid var(--cv-border-1);
}

.btn-warning {
    background: var(--cv-surface-2);
    color: #e5e7eb;
    border: 1px solid var(--cv-border-1);
}

.btn-danger {
    background: rgba(239, 68, 68, 0.2);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.btn-outline {
    background: transparent;
    color: #d1d5db;
    border: 1px dashed rgba(71, 85, 105, 0.5);
}

.btn-outline:hover {
    background: rgba(51, 65, 85, 0.3);
    border-style: solid;
}

/* Double Elimination Styles */
.de-container {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.de-title-bar {
    background: var(--cv-surface-1);
    border: 1px solid var(--cv-border-1);
    border-radius: 0.5rem 0.5rem 0 0;
    padding: 1rem;
    text-align: center;
}

.de-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #ffffff;
    letter-spacing: 0.1em;
}

.de-section {
    background: rgba(30, 41, 59, 0.6);
    border: 2px solid rgba(71, 85, 105, 0.5);
    border-top: none;
    padding: 1.5rem;
}

.de-section-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px dashed rgba(71, 85, 105, 0.5);
}

.section-icon {
    font-size: 1rem;
}

.section-label {
    font-size: 1rem;
    font-weight: 600;
    color: #ffffff;
}

.section-tag {
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.625rem;
    font-weight: 600;
    text-transform: uppercase;
}

.winners-tag {
    background: var(--cv-surface-2);
    color: #e5e7eb;
}

.losers-tag {
    background: var(--cv-surface-2);
    color: #e5e7eb;
}

.finals-tag {
    background: var(--cv-surface-2);
    color: #e5e7eb;
}

.de-empty {
    text-align: center;
    padding: 2rem;
    color: #9ca3af;
    font-style: italic;
}

/* Bracket Tree Layout */
.bracket-tree {
    display: flex;
    overflow-x: auto;
    padding: 0.5rem 0;
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
    color: #9ca3af;
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
    border: 1px solid var(--cv-border-1);
    border-radius: 0.5rem;
    padding: 0.375rem 0.5rem;
    min-width: 140px;
    max-width: 160px;
    z-index: 2;
    position: relative;
}

.bracket-game-lower {
    border-color: var(--cv-border-1);
}

/* === Bracket Connector Lines (CSS pseudo-elements) === */

/* Right bracket arm: TOP game of pair → goes down-right */
.bracket-round:not(.bracket-round-last) .game-top::after {
    content: "";
    position: absolute;
    right: 0;
    top: 50%;
    bottom: 0;
    width: 1.5rem;
    border-top: 2px solid var(--cv-border-2);
    border-right: 2px solid var(--cv-border-2);
    border-radius: 0 6px 0 0;
}

/* Right bracket arm: BOTTOM game of pair → goes up-right */
.bracket-round:not(.bracket-round-last) .game-bottom::after {
    content: "";
    position: absolute;
    right: 0;
    top: 0;
    bottom: 50%;
    width: 1.5rem;
    border-bottom: 2px solid var(--cv-border-2);
    border-right: 2px solid var(--cv-border-2);
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
    background: var(--cv-border-2);
}

/* Left connector: horizontal line from previous round merge point */
.bracket-round:not(.bracket-round-first) .bracket-game-wrapper::before {
    content: "";
    position: absolute;
    left: 0;
    top: calc(50% - 1px);
    width: 1.5rem;
    height: 2px;
    background: var(--cv-border-2);
}

/* Lower bracket connector colors */
.bracket-round-lower:not(.bracket-round-last) .game-top::after {
    border-color: var(--cv-border-2);
}

.bracket-round-lower:not(.bracket-round-last) .game-bottom::after {
    border-color: var(--cv-border-2);
}

.bracket-round-lower:not(.bracket-round-last) .game-single::after {
    background: var(--cv-border-2);
}

.bracket-round-lower:not(.bracket-round-first) .bracket-game-wrapper::before {
    background: var(--cv-border-2);
}

.de-match-label {
    font-size: 0.625rem;
    color: #9ca3af;
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
    background: var(--cv-surface-2);
    border: 1px solid var(--cv-border-1);
    border-radius: 0.25rem;
    transition: all 0.2s ease;
}

.de-team-row.has-team {
    background: var(--cv-surface-2);
    border-color: var(--cv-border-2);
}

.de-team-row-lower.has-team {
    background: var(--cv-surface-2);
    border-color: var(--cv-border-2);
}

.de-team-select {
    flex: 1;
    padding: 0.25rem;
    background: transparent;
    border: none;
    color: #ffffff;
    font-size: 0.75rem;
    font-weight: 500;
    cursor: pointer;
}

.de-team-select option {
    background: #1e293b;
}

.de-remove-btn {
    width: 1.75rem;
    height: 1.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(239, 68, 68, 0.2);
    border: none;
    border-radius: 0.375rem;
    color: #f87171;
    font-size: 1.125rem;
    cursor: pointer;
    opacity: 0;
    transition: all 0.2s ease;
}

.de-remove-btn:hover {
    background: rgba(239, 68, 68, 0.4);
    color: #fca5a5;
}

.de-team-row:hover .de-remove-btn {
    opacity: 1;
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

/* Finals Section */
.finals-section {
    border-radius: 0 0 0.5rem 0.5rem;
}

.finals-header {
    border-bottom-color: var(--cv-border-1);
}

.grand-finals-match {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: var(--cv-surface-1);
    border: 1px solid var(--cv-border-1);
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
    color: #9ca3af;
    text-transform: uppercase;
}

.gf-team {
    padding: 0.75rem 1rem;
    background: var(--cv-surface-2);
    border: 1px solid var(--cv-border-1);
    border-radius: 0.5rem;
    font-weight: 600;
    color: #ffffff;
    text-align: center;
    width: 100%;
}

.gf-upper .gf-team {
    border-color: var(--cv-border-2);
}

.gf-lower .gf-team {
    border-color: var(--cv-border-2);
}

.gf-vs {
    padding: 0.5rem 1rem;
    background: var(--cv-surface-2);
    border: 1px solid var(--cv-border-1);
    border-radius: 9999px;
}

.vs-text {
    font-size: 1rem;
    font-weight: 700;
    color: #e5e7eb;
}

.gf-champion {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: var(--cv-surface-1);
    border: 1px dashed var(--cv-border-2);
    border-radius: 0.75rem;
}

.champion-crown {
    font-size: 2rem;
}

.champion-label {
    font-size: 0.875rem;
    font-weight: 700;
    color: #e5e7eb;
    letter-spacing: 0.2em;
}

/* Empty State */
.empty-bracket-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem;
    text-align: center;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-bracket-state h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #ffffff;
    margin: 0 0 0.5rem 0;
}

.empty-bracket-state p {
    color: #9ca3af;
    margin: 0;
}

/* Quick Actions */
.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.quick-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Modal */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: #1e293b;
    border-radius: 0.75rem;
    padding: 1.5rem;
    width: 90%;
    max-width: 400px;
    border: 1px solid rgba(51, 65, 85, 0.5);
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 1.5rem 0;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #d1d5db;
    margin-bottom: 0.5rem;
}

.form-input {
    width: 100%;
    padding: 0.75rem;
    background: rgba(30, 41, 59, 0.8);
    border: 1px solid rgba(71, 85, 105, 0.5);
    border-radius: 0.5rem;
    color: #ffffff;
    font-size: 1rem;
}

.form-input:focus {
    outline: none;
    border-color: rgba(var(--cv-accent-1-rgb), 0.5);
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    margin-top: 1.5rem;
}
</style>
