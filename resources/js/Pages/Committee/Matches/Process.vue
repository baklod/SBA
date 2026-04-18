<template>
    <Head title="Process Match - Committee | SBA" />
    <CommitteeLayout>
        <div class="process-page">
            <!-- Header -->
            <div class="header">
                <Link
                    :href="route('committee.matches.index')"
                    class="back-link"
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
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        ></path>
                    </svg>
                    Back to Matches
                </Link>
                <h1 class="page-title">Live Scoring</h1>
            </div>

            <!-- Scoreboard -->
            <div class="glass-card scoreboard">
                <div class="scoreboard-content">
                    <div class="team-score team-a-side">
                        <div class="team-avatar">
                            {{ match.team_a?.name?.charAt(0) }}
                        </div>
                        <span class="team-name">{{ match.team_a?.name }}</span>
                        <span class="score">{{ teamAScore }}</span>
                    </div>
                    <div class="vs-section">
                        <div class="quarter-display">
                            <select
                                v-model="currentQuarter"
                                @change="updateQuarter"
                                class="quarter-select"
                            >
                                <option value="1st">1st Quarter</option>
                                <option value="2nd">2nd Quarter</option>
                                <option value="3rd">3rd Quarter</option>
                                <option value="4th">4th Quarter</option>
                                <option value="OT">Overtime</option>
                            </select>
                        </div>
                        <span class="vs-badge">VS</span>
                        <span
                            :class="[
                                'status-badge',
                                getStatusClass(currentStatus),
                            ]"
                            >{{ currentStatus }}</span
                        >
                    </div>
                    <div class="team-score team-b-side">
                        <span class="score">{{ teamBScore }}</span>
                        <span class="team-name">{{ match.team_b?.name }}</span>
                        <div class="team-avatar team-avatar-alt">
                            {{ match.team_b?.name?.charAt(0) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teams Grid -->
            <div class="teams-grid">
                <!-- Team A Players -->
                <div class="glass-card team-card">
                    <div class="team-header team-a-header">
                        <h2 class="team-title">{{ match.team_a?.name }}</h2>
                        <span class="team-total">{{ teamAScore }} PTS</span>
                    </div>
                    <div class="players-list">
                        <div
                            v-for="player in teamAPlayersData"
                            :key="player.id"
                            class="player-row"
                        >
                            <div class="player-info">
                                <span class="player-jersey"
                                    >#{{ player.jersey_number }}</span
                                >
                                <span class="player-name">{{
                                    player.name
                                }}</span>
                                <span class="player-position">{{
                                    player.position
                                }}</span>
                                <label class="played-toggle">
                                    <input
                                        type="checkbox"
                                        v-model="player.current_played"
                                        :disabled="
                                            isLoading ||
                                            presenceSaving[player.id]
                                        "
                                        @change="
                                            onPlayedChange(
                                                player.id,
                                                player.current_played,
                                            )
                                        "
                                    />
                                    <span>Played</span>
                                </label>
                            </div>
                            <div class="player-stats-display">
                                <div class="stats-row">
                                    <span class="player-points"
                                        >{{ player.current_points }} PTS</span
                                    >
                                    <span
                                        :class="[
                                            'player-fouls',
                                            {
                                                'foul-warning':
                                                    player.current_fouls >= 4,
                                                'fouled-out':
                                                    player.current_fouls >= 5,
                                            },
                                        ]"
                                    >
                                        {{ player.current_fouls }} F
                                    </span>
                                </div>
                                <div class="stats-row secondary">
                                    <span class="stat-item"
                                        >{{ player.current_assists }} AST</span
                                    >
                                    <span class="stat-item"
                                        >{{ player.current_rebounds }} REB</span
                                    >
                                    <span class="stat-item"
                                        >{{ player.current_blocks }} BLK</span
                                    >
                                    <span class="stat-item"
                                        >{{ player.current_steals }} STL</span
                                    >
                                </div>
                            </div>
                            <div class="buttons-container">
                                <div class="score-buttons">
                                    <div class="btn-pair">
                                        <button
                                            @click="addPoints(player.id, 1)"
                                            class="score-btn score-btn-1"
                                            :disabled="isLoading"
                                        >
                                            +1
                                        </button>
                                        <button
                                            @click="addPoints(player.id, -1)"
                                            class="score-btn score-btn-minus"
                                            :disabled="
                                                isLoading ||
                                                player.current_points < 1
                                            "
                                        >
                                            -1
                                        </button>
                                    </div>
                                    <div class="btn-pair">
                                        <button
                                            @click="addPoints(player.id, 2)"
                                            class="score-btn score-btn-2"
                                            :disabled="isLoading"
                                        >
                                            +2
                                        </button>
                                        <button
                                            @click="addPoints(player.id, -2)"
                                            class="score-btn score-btn-minus"
                                            :disabled="
                                                isLoading ||
                                                player.current_points < 2
                                            "
                                        >
                                            -2
                                        </button>
                                    </div>
                                    <div class="btn-pair">
                                        <button
                                            @click="addPoints(player.id, 3)"
                                            class="score-btn score-btn-3"
                                            :disabled="isLoading"
                                        >
                                            +3
                                        </button>
                                        <button
                                            @click="addPoints(player.id, -3)"
                                            class="score-btn score-btn-minus"
                                            :disabled="
                                                isLoading ||
                                                player.current_points < 3
                                            "
                                        >
                                            -3
                                        </button>
                                    </div>
                                    <div class="btn-pair">
                                        <button
                                            @click="addFoul(player.id, 'add')"
                                            class="score-btn score-btn-foul"
                                            :disabled="
                                                isLoading ||
                                                player.current_fouls >= 5
                                            "
                                        >
                                            +F
                                        </button>
                                        <button
                                            @click="
                                                addFoul(player.id, 'subtract')
                                            "
                                            class="score-btn score-btn-minus"
                                            :disabled="
                                                isLoading ||
                                                player.current_fouls <= 0
                                            "
                                        >
                                            -F
                                        </button>
                                    </div>
                                </div>
                                <div class="stat-buttons">
                                    <div class="btn-pair">
                                        <button
                                            @click="
                                                addStat(
                                                    player.id,
                                                    'assists',
                                                    'add',
                                                )
                                            "
                                            class="stat-btn stat-btn-ast"
                                            :disabled="isLoading"
                                        >
                                            +AST
                                        </button>
                                        <button
                                            @click="
                                                addStat(
                                                    player.id,
                                                    'assists',
                                                    'subtract',
                                                )
                                            "
                                            class="stat-btn stat-btn-minus"
                                            :disabled="
                                                isLoading ||
                                                player.current_assists <= 0
                                            "
                                        >
                                            -AST
                                        </button>
                                    </div>
                                    <div class="btn-pair">
                                        <button
                                            @click="
                                                addStat(
                                                    player.id,
                                                    'rebounds',
                                                    'add',
                                                )
                                            "
                                            class="stat-btn stat-btn-reb"
                                            :disabled="isLoading"
                                        >
                                            +REB
                                        </button>
                                        <button
                                            @click="
                                                addStat(
                                                    player.id,
                                                    'rebounds',
                                                    'subtract',
                                                )
                                            "
                                            class="stat-btn stat-btn-minus"
                                            :disabled="
                                                isLoading ||
                                                player.current_rebounds <= 0
                                            "
                                        >
                                            -REB
                                        </button>
                                    </div>
                                    <div class="btn-pair">
                                        <button
                                            @click="
                                                addStat(
                                                    player.id,
                                                    'blocks',
                                                    'add',
                                                )
                                            "
                                            class="stat-btn stat-btn-blk"
                                            :disabled="isLoading"
                                        >
                                            +BLK
                                        </button>
                                        <button
                                            @click="
                                                addStat(
                                                    player.id,
                                                    'blocks',
                                                    'subtract',
                                                )
                                            "
                                            class="stat-btn stat-btn-minus"
                                            :disabled="
                                                isLoading ||
                                                player.current_blocks <= 0
                                            "
                                        >
                                            -BLK
                                        </button>
                                    </div>
                                    <div class="btn-pair">
                                        <button
                                            @click="
                                                addStat(
                                                    player.id,
                                                    'steals',
                                                    'add',
                                                )
                                            "
                                            class="stat-btn stat-btn-stl"
                                            :disabled="isLoading"
                                        >
                                            +STL
                                        </button>
                                        <button
                                            @click="
                                                addStat(
                                                    player.id,
                                                    'steals',
                                                    'subtract',
                                                )
                                            "
                                            class="stat-btn stat-btn-minus"
                                            :disabled="
                                                isLoading ||
                                                player.current_steals <= 0
                                            "
                                        >
                                            -STL
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team B Players -->
                <div class="glass-card team-card">
                    <div class="team-header team-b-header">
                        <h2 class="team-title">{{ match.team_b?.name }}</h2>
                        <span class="team-total">{{ teamBScore }} PTS</span>
                    </div>
                    <div class="players-list">
                        <div
                            v-for="player in teamBPlayersData"
                            :key="player.id"
                            class="player-row"
                        >
                            <div class="player-info">
                                <span class="player-jersey"
                                    >#{{ player.jersey_number }}</span
                                >
                                <span class="player-name">{{
                                    player.name
                                }}</span>
                                <span class="player-position">{{
                                    player.position
                                }}</span>
                                <label class="played-toggle">
                                    <input
                                        type="checkbox"
                                        v-model="player.current_played"
                                        :disabled="
                                            isLoading ||
                                            presenceSaving[player.id]
                                        "
                                        @change="
                                            onPlayedChange(
                                                player.id,
                                                player.current_played,
                                            )
                                        "
                                    />
                                    <span>Played</span>
                                </label>
                            </div>
                            <div class="player-stats-display">
                                <div class="stats-row">
                                    <span class="player-points"
                                        >{{ player.current_points }} PTS</span
                                    >
                                    <span
                                        :class="[
                                            'player-fouls',
                                            {
                                                'foul-warning':
                                                    player.current_fouls >= 4,
                                                'fouled-out':
                                                    player.current_fouls >= 5,
                                            },
                                        ]"
                                    >
                                        {{ player.current_fouls }} F
                                    </span>
                                </div>
                                <div class="stats-row secondary">
                                    <span class="stat-item"
                                        >{{ player.current_assists }} AST</span
                                    >
                                    <span class="stat-item"
                                        >{{ player.current_rebounds }} REB</span
                                    >
                                    <span class="stat-item"
                                        >{{ player.current_blocks }} BLK</span
                                    >
                                    <span class="stat-item"
                                        >{{ player.current_steals }} STL</span
                                    >
                                </div>
                            </div>
                            <div class="buttons-container">
                                <div class="score-buttons">
                                    <div class="btn-pair">
                                        <button
                                            @click="addPoints(player.id, 1)"
                                            class="score-btn score-btn-1"
                                            :disabled="isLoading"
                                        >
                                            +1
                                        </button>
                                        <button
                                            @click="addPoints(player.id, -1)"
                                            class="score-btn score-btn-minus"
                                            :disabled="
                                                isLoading ||
                                                player.current_points < 1
                                            "
                                        >
                                            -1
                                        </button>
                                    </div>
                                    <div class="btn-pair">
                                        <button
                                            @click="addPoints(player.id, 2)"
                                            class="score-btn score-btn-2"
                                            :disabled="isLoading"
                                        >
                                            +2
                                        </button>
                                        <button
                                            @click="addPoints(player.id, -2)"
                                            class="score-btn score-btn-minus"
                                            :disabled="
                                                isLoading ||
                                                player.current_points < 2
                                            "
                                        >
                                            -2
                                        </button>
                                    </div>
                                    <div class="btn-pair">
                                        <button
                                            @click="addPoints(player.id, 3)"
                                            class="score-btn score-btn-3"
                                            :disabled="isLoading"
                                        >
                                            +3
                                        </button>
                                        <button
                                            @click="addPoints(player.id, -3)"
                                            class="score-btn score-btn-minus"
                                            :disabled="
                                                isLoading ||
                                                player.current_points < 3
                                            "
                                        >
                                            -3
                                        </button>
                                    </div>
                                    <div class="btn-pair">
                                        <button
                                            @click="addFoul(player.id, 'add')"
                                            class="score-btn score-btn-foul"
                                            :disabled="
                                                isLoading ||
                                                player.current_fouls >= 5
                                            "
                                        >
                                            +F
                                        </button>
                                        <button
                                            @click="
                                                addFoul(player.id, 'subtract')
                                            "
                                            class="score-btn score-btn-minus"
                                            :disabled="
                                                isLoading ||
                                                player.current_fouls <= 0
                                            "
                                        >
                                            -F
                                        </button>
                                    </div>
                                </div>
                                <div class="stat-buttons">
                                    <div class="btn-pair">
                                        <button
                                            @click="
                                                addStat(
                                                    player.id,
                                                    'assists',
                                                    'add',
                                                )
                                            "
                                            class="stat-btn stat-btn-ast"
                                            :disabled="isLoading"
                                        >
                                            +AST
                                        </button>
                                        <button
                                            @click="
                                                addStat(
                                                    player.id,
                                                    'assists',
                                                    'subtract',
                                                )
                                            "
                                            class="stat-btn stat-btn-minus"
                                            :disabled="
                                                isLoading ||
                                                player.current_assists <= 0
                                            "
                                        >
                                            -AST
                                        </button>
                                    </div>
                                    <div class="btn-pair">
                                        <button
                                            @click="
                                                addStat(
                                                    player.id,
                                                    'rebounds',
                                                    'add',
                                                )
                                            "
                                            class="stat-btn stat-btn-reb"
                                            :disabled="isLoading"
                                        >
                                            +REB
                                        </button>
                                        <button
                                            @click="
                                                addStat(
                                                    player.id,
                                                    'rebounds',
                                                    'subtract',
                                                )
                                            "
                                            class="stat-btn stat-btn-minus"
                                            :disabled="
                                                isLoading ||
                                                player.current_rebounds <= 0
                                            "
                                        >
                                            -REB
                                        </button>
                                    </div>
                                    <div class="btn-pair">
                                        <button
                                            @click="
                                                addStat(
                                                    player.id,
                                                    'blocks',
                                                    'add',
                                                )
                                            "
                                            class="stat-btn stat-btn-blk"
                                            :disabled="isLoading"
                                        >
                                            +BLK
                                        </button>
                                        <button
                                            @click="
                                                addStat(
                                                    player.id,
                                                    'blocks',
                                                    'subtract',
                                                )
                                            "
                                            class="stat-btn stat-btn-minus"
                                            :disabled="
                                                isLoading ||
                                                player.current_blocks <= 0
                                            "
                                        >
                                            -BLK
                                        </button>
                                    </div>
                                    <div class="btn-pair">
                                        <button
                                            @click="
                                                addStat(
                                                    player.id,
                                                    'steals',
                                                    'add',
                                                )
                                            "
                                            class="stat-btn stat-btn-stl"
                                            :disabled="isLoading"
                                        >
                                            +STL
                                        </button>
                                        <button
                                            @click="
                                                addStat(
                                                    player.id,
                                                    'steals',
                                                    'subtract',
                                                )
                                            "
                                            class="stat-btn stat-btn-minus"
                                            :disabled="
                                                isLoading ||
                                                player.current_steals <= 0
                                            "
                                        >
                                            -STL
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Actions -->
            <div class="glass-card actions-card">
                <div class="action-buttons">
                    <Link
                        :href="route('committee.matches.index')"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </Link>
                    <button
                        @click="saveMatch"
                        class="btn btn-success"
                        :disabled="isSaving"
                    >
                        {{ isSaving ? "Saving..." : "Save Match" }}
                    </button>
                </div>
            </div>
        </div>
    </CommitteeLayout>
</template>

<script setup>
import { ref, reactive } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import CommitteeLayout from "@/Layouts/CommitteeLayout.vue";
import axios from "axios";

const props = defineProps({
    match: {
        type: Object,
        required: true,
    },
    teamAPlayers: {
        type: Array,
        default: () => [],
    },
    teamBPlayers: {
        type: Array,
        default: () => [],
    },
});

// Reactive data
const teamAScore = ref(props.match.team_a_score ?? 0);
const teamBScore = ref(props.match.team_b_score ?? 0);
const currentStatus = ref(props.match.status ?? "ongoing");
const currentQuarter = ref(props.match.quarter ?? "1st");
const isLoading = ref(false);
const isSaving = ref(false);
const presenceSaving = reactive({});

// Make players reactive
const teamAPlayersData = ref(
    props.teamAPlayers.map((p) => ({
        ...p,
        current_points: p.current_points ?? 0,
        current_fouls: p.current_fouls ?? 0,
        current_assists: p.current_assists ?? 0,
        current_rebounds: p.current_rebounds ?? 0,
        current_blocks: p.current_blocks ?? 0,
        current_steals: p.current_steals ?? 0,
        current_played: p.current_played ?? false,
    })),
);

const teamBPlayersData = ref(
    props.teamBPlayers.map((p) => ({
        ...p,
        current_points: p.current_points ?? 0,
        current_fouls: p.current_fouls ?? 0,
        current_assists: p.current_assists ?? 0,
        current_rebounds: p.current_rebounds ?? 0,
        current_blocks: p.current_blocks ?? 0,
        current_steals: p.current_steals ?? 0,
        current_played: p.current_played ?? false,
    })),
);

const onPlayedChange = async (playerId, played) => {
    const previous = !played;
    presenceSaving[playerId] = true;

    try {
        const response = await axios.post(
            route("committee.matches.set-player-played", props.match.id),
            {
                player_id: playerId,
                played: played,
            },
        );

        if (response.data?.success) {
            const nextPlayed = !!response.data.played;

            const playerA = teamAPlayersData.value.find(
                (p) => p.id === playerId,
            );
            if (playerA) playerA.current_played = nextPlayed;

            const playerB = teamBPlayersData.value.find(
                (p) => p.id === playerId,
            );
            if (playerB) playerB.current_played = nextPlayed;
        }
    } catch (error) {
        console.error("Error setting played status:", error);

        const playerA = teamAPlayersData.value.find((p) => p.id === playerId);
        if (playerA) playerA.current_played = previous;

        const playerB = teamBPlayersData.value.find((p) => p.id === playerId);
        if (playerB) playerB.current_played = previous;

        alert("Failed to update played status. Please try again.");
    } finally {
        presenceSaving[playerId] = false;
    }
};

const addPoints = async (playerId, points) => {
    isLoading.value = true;

    try {
        const response = await axios.post(
            route("committee.matches.add-score", props.match.id),
            {
                player_id: playerId,
                points: points,
            },
        );

        if (response.data.success) {
            // Update scores
            teamAScore.value = response.data.team_a_score;
            teamBScore.value = response.data.team_b_score;

            // Update player points
            const playerA = teamAPlayersData.value.find(
                (p) => p.id === playerId,
            );
            if (playerA) {
                playerA.current_points = response.data.player_points;
                playerA.current_played = true;
            }

            const playerB = teamBPlayersData.value.find(
                (p) => p.id === playerId,
            );
            if (playerB) {
                playerB.current_points = response.data.player_points;
                playerB.current_played = true;
            }

            // Auto-set to ongoing if it was upcoming
            if (currentStatus.value === "upcoming") {
                currentStatus.value = "ongoing";
            }
        }
    } catch (error) {
        console.error("Error adding points:", error);
        alert("Failed to add points. Please try again.");
    } finally {
        isLoading.value = false;
    }
};

const saveMatch = async () => {
    isSaving.value = true;

    try {
        await axios.post(route("committee.matches.save", props.match.id), {
            status: currentStatus.value,
        });

        router.visit(route("committee.matches.index"));
    } catch (error) {
        console.error("Error saving match:", error);
        alert("Failed to save match. Please try again.");
        isSaving.value = false;
    }
};

const addFoul = async (playerId, action = "add") => {
    isLoading.value = true;

    try {
        const response = await axios.post(
            route("committee.matches.add-foul", props.match.id),
            {
                player_id: playerId,
                action: action,
            },
        );

        if (response.data.success) {
            // Update player fouls
            const playerA = teamAPlayersData.value.find(
                (p) => p.id === playerId,
            );
            if (playerA) {
                playerA.current_fouls = response.data.player_fouls;
                playerA.current_played = true;
            }

            const playerB = teamBPlayersData.value.find(
                (p) => p.id === playerId,
            );
            if (playerB) {
                playerB.current_fouls = response.data.player_fouls;
                playerB.current_played = true;
            }
        }
    } catch (error) {
        console.error("Error updating foul:", error);
        alert("Failed to update foul. Please try again.");
    } finally {
        isLoading.value = false;
    }
};

const addStat = async (playerId, statType, action = "add") => {
    isLoading.value = true;

    try {
        const response = await axios.post(
            route("committee.matches.add-stat", props.match.id),
            {
                player_id: playerId,
                stat_type: statType,
                action: action,
            },
        );

        if (response.data.success) {
            const statKey = `current_${statType}`;

            const playerA = teamAPlayersData.value.find(
                (p) => p.id === playerId,
            );
            if (playerA) {
                playerA[statKey] = response.data.stat_value;
                playerA.current_played = true;
            }

            const playerB = teamBPlayersData.value.find(
                (p) => p.id === playerId,
            );
            if (playerB) {
                playerB[statKey] = response.data.stat_value;
                playerB.current_played = true;
            }
        }
    } catch (error) {
        console.error("Error updating stat:", error);
        alert("Failed to update stat. Please try again.");
    } finally {
        isLoading.value = false;
    }
};

const updateQuarter = async () => {
    try {
        const response = await axios.post(
            route("committee.matches.update-quarter", props.match.id),
            {
                quarter: currentQuarter.value,
            },
        );

        if (response.data.success) {
            currentStatus.value = response.data.status;
        }
    } catch (error) {
        console.error("Error updating quarter:", error);
        alert("Failed to update quarter. Please try again.");
    }
};

const getStatusClass = (status) => {
    switch (status) {
        case "upcoming":
            return "status-upcoming";
        case "ongoing":
            return "status-ongoing";
        case "paused":
            return "status-paused";
        case "finished":
            return "status-finished";
        default:
            return "status-default";
    }
};
</script>

<style scoped>
.process-page {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.header {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.back-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #9ca3af;
    text-decoration: none;
    font-size: 0.875rem;
    transition: color 0.3s ease;
}

.back-link:hover {
    color: #22c55e;
}

.page-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
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

/* Scoreboard */
.scoreboard {
    background: linear-gradient(
        135deg,
        rgba(30, 41, 59, 0.8) 0%,
        rgba(51, 65, 85, 0.5) 100%
    );
}

.scoreboard-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.team-score {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.team-a-side {
    justify-content: flex-start;
}

.team-b-side {
    justify-content: flex-end;
}

.team-avatar {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    background: linear-gradient(135deg, #06b6d4 0%, #2563eb 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #ffffff;
    font-size: 1.25rem;
}

.team-avatar-alt {
    background: linear-gradient(135deg, #a855f7 0%, #8b5cf6 100%);
}

.team-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: #ffffff;
}

.score {
    font-size: 3rem;
    font-weight: 800;
    color: #22d3ee;
}

.vs-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.vs-badge {
    padding: 0.5rem 1rem;
    background: rgba(51, 65, 85, 0.5);
    border-radius: 0.5rem;
    font-size: 1rem;
    font-weight: 700;
    color: #6b7280;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 9999px;
    text-transform: uppercase;
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

/* Teams Grid */
.teams-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

@media (min-width: 1024px) {
    .teams-grid {
        grid-template-columns: 1fr 1fr;
    }
}

.team-card {
    padding: 0;
    overflow: hidden;
}

.team-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
}

.team-a-header {
    background: linear-gradient(
        135deg,
        rgba(6, 182, 212, 0.2) 0%,
        rgba(37, 99, 235, 0.2) 100%
    );
    border-bottom: 1px solid rgba(6, 182, 212, 0.3);
}

.team-b-header {
    background: linear-gradient(
        135deg,
        rgba(168, 85, 247, 0.2) 0%,
        rgba(139, 92, 246, 0.2) 100%
    );
    border-bottom: 1px solid rgba(168, 85, 247, 0.3);
}

.team-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
}

.team-total {
    font-size: 1.5rem;
    font-weight: 700;
    color: #22d3ee;
}

.players-list {
    padding: 0.5rem;
}

.player-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    transition: background 0.3s ease;
}

.player-row:hover {
    background: rgba(51, 65, 85, 0.3);
}

.player-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
    min-width: 0;
}

.player-jersey {
    font-size: 0.75rem;
    font-weight: 600;
    color: #a78bfa;
    min-width: 2.5rem;
}

.player-name {
    font-weight: 600;
    color: #ffffff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.player-position {
    font-size: 0.75rem;
    color: #6b7280;
    padding: 0.125rem 0.5rem;
    background: rgba(51, 65, 85, 0.5);
    border-radius: 0.25rem;
}

.played-toggle {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.125rem 0.5rem;
    background: rgba(51, 65, 85, 0.5);
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: #9ca3af;
    user-select: none;
    flex-shrink: 0;
}

.played-toggle input {
    width: 0.95rem;
    height: 0.95rem;
    cursor: pointer;
    accent-color: #22c55e;
}

.played-toggle input:checked {
    filter: drop-shadow(0 0 4px rgba(34, 197, 94, 0.45));
}

.player-stats-display {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
    min-width: 4rem;
}

.player-points {
    font-weight: 700;
    color: #22d3ee;
    text-align: center;
}

.player-fouls {
    font-size: 0.75rem;
    font-weight: 600;
    color: #9ca3af;
    padding: 0.125rem 0.5rem;
    background: rgba(51, 65, 85, 0.5);
    border-radius: 0.25rem;
}

.player-fouls.foul-warning {
    background: rgba(245, 158, 11, 0.3);
    color: #fbbf24;
}

.player-fouls.fouled-out {
    background: rgba(239, 68, 68, 0.3);
    color: #f87171;
}

.quarter-display {
    margin-bottom: 0.5rem;
}

.quarter-select {
    padding: 0.5rem 1rem;
    background: #1e293b;
    border: 2px solid #a855f7;
    border-radius: 0.5rem;
    color: #ffffff;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    text-align: center;
}

.quarter-select option {
    background: #1e293b;
    color: #ffffff;
    padding: 0.5rem;
}

.quarter-select:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(168, 85, 247, 0.5);
}

.stats-row {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.stats-row.secondary {
    flex-wrap: wrap;
    justify-content: center;
}

.stat-item {
    font-size: 0.625rem;
    color: #9ca3af;
    padding: 0.125rem 0.25rem;
    background: rgba(51, 65, 85, 0.5);
    border-radius: 0.25rem;
}

.buttons-container {
    display: flex;
    flex-direction: row;
    gap: 0.5rem;
    flex-wrap: wrap;
    align-items: flex-start;
}

.score-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: nowrap;
}

.stat-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: nowrap;
}

.btn-pair {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.stat-btn {
    width: 2.5rem;
    height: 1.875rem;
    border: none;
    border-radius: 0.375rem;
    font-weight: 700;
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.stat-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.stat-btn-ast {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    color: #ffffff;
}

.stat-btn-ast:hover:not(:disabled) {
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4);
}

.stat-btn-reb {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: #ffffff;
}

.stat-btn-reb:hover:not(:disabled) {
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
}

.stat-btn-blk {
    background: linear-gradient(135deg, #3730a3 0%, #4338ca 100%);
    color: #ffffff;
}

.stat-btn-blk:hover:not(:disabled) {
    box-shadow: 0 4px 12px rgba(67, 56, 202, 0.4);
}

.stat-btn-stl {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #ffffff;
}

.stat-btn-stl:hover:not(:disabled) {
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.stat-btn-minus {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    color: #ffffff;
}

.stat-btn-minus:hover:not(:disabled) {
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
}

.stat-btn:hover:not(:disabled) {
    transform: scale(1.1);
}

.score-btn {
    width: 2.5rem;
    height: 1.875rem;
    border: none;
    border-radius: 0.375rem;
    font-weight: 700;
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.score-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.score-btn-1 {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    color: #ffffff;
}

.score-btn-1:hover:not(:disabled) {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);
}

.score-btn-2 {
    background: linear-gradient(135deg, #06b6d4 0%, #2563eb 100%);
    color: #ffffff;
}

.score-btn-2:hover:not(:disabled) {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4);
}

.score-btn-minus {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    color: #ffffff;
}

.score-btn-minus:hover:not(:disabled) {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
}

.score-btn-3 {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #ffffff;
}

.score-btn-3:hover:not(:disabled) {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
}

.score-btn-foul {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #ffffff;
}

.score-btn-foul:hover:not(:disabled) {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

/* Actions Card */
.actions-card {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #d1d5db;
}

.form-input {
    padding: 0.75rem 1rem;
    background: rgba(30, 41, 59, 0.8);
    border: 1px solid rgba(71, 85, 105, 0.5);
    border-radius: 0.5rem;
    color: #ffffff;
    font-size: 1rem;
    min-width: 150px;
}

.form-input:focus {
    outline: none;
    border-color: rgba(34, 197, 94, 0.5);
}

.action-buttons {
    display: flex;
    gap: 1rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: 0.5rem;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-secondary {
    background: rgba(51, 65, 85, 0.5);
    color: #d1d5db;
    border: 1px solid rgba(71, 85, 105, 0.5);
}

.btn-secondary:hover {
    background: rgba(51, 65, 85, 0.8);
}

.btn-success {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    color: #ffffff;
}

.btn-success:hover:not(:disabled) {
    box-shadow: 0 4px 20px rgba(34, 197, 94, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .scoreboard-content {
        flex-direction: column;
        text-align: center;
    }

    .team-score {
        justify-content: center;
    }

    .team-b-side {
        flex-direction: row-reverse;
    }

    .score {
        font-size: 2rem;
    }

    .player-row {
        flex-wrap: wrap;
    }

    .player-info {
        flex: 1 1 100%;
        margin-bottom: 0.5rem;
    }

    .player-points {
        flex: 1;
    }

    .actions-card {
        flex-direction: column;
        align-items: stretch;
    }

    .action-buttons {
        justify-content: flex-end;
    }
}
</style>
