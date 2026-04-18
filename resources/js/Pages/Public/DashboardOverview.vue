<template>
    <div class="dashboard-shell">
        <div class="dashboard-grid">
            <main class="dashboard-main">
                <section
                    v-if="isOffSeason"
                    class="dash-block dash-block-offseason"
                    role="status"
                    aria-label="Off-season notice"
                >
                    <div class="dash-block-header dash-block-header-tight">
                        <div>
                            <h2 class="dash-title dash-title-sm">Off Season</h2>
                            <div class="dash-subtitle">
                                The league is currently in the off-season. Games
                                and stats will appear here when play resumes.
                            </div>
                        </div>
                    </div>
                </section>

                <template v-else>
                    <section class="dash-block">
                        <div class="dash-block-header">
                            <div>
                                <h1 class="dash-title">Tonight’s Games</h1>
                                <div class="dash-subtitle">
                                    {{ todayLabel }}
                                </div>
                            </div>
                            <div class="dash-header-actions">
                                <Link
                                    :href="
                                        route('home', { section: 'schedule' })
                                    "
                                    class="dash-link"
                                    >Full Schedule →</Link
                                >
                            </div>
                        </div>

                        <div v-if="dashboardMatches.length" class="games-grid">
                            <Link
                                v-for="match in dashboardMatches"
                                :key="match.id"
                                :href="route('match.details', match.id)"
                                class="game-card"
                                :class="{
                                    live: isLiveStatus(match.status),
                                    finished: match.status === 'finished',
                                }"
                            >
                                <div class="game-meta">
                                    <span
                                        class="status-pill"
                                        :class="
                                            'status-' + statusTone(match.status)
                                        "
                                    >
                                        <span
                                            v-if="isLiveStatus(match.status)"
                                            class="live-dot"
                                            aria-hidden="true"
                                        />
                                        {{ statusLabel(match.status) }}
                                        <span
                                            v-if="
                                                isLiveStatus(match.status) &&
                                                match.quarter
                                            "
                                        >
                                            • {{ match.quarter }}
                                        </span>
                                    </span>
                                    <span class="game-time">{{
                                        matchStartLabel(match)
                                    }}</span>
                                </div>

                                <div class="game-teams">
                                    <div class="team-row">
                                        <div class="team-left">
                                            <div class="team-avatar">
                                                <img
                                                    v-if="
                                                        resolveAssetUrl(
                                                            match.team_a?.logo,
                                                        )
                                                    "
                                                    :src="
                                                        resolveAssetUrl(
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
                                            <span class="team-name">{{
                                                match.team_a?.name || "Team A"
                                            }}</span>
                                        </div>
                                        <span class="team-score">
                                            {{
                                                displayScore(
                                                    match.team_a_score,
                                                    match.status,
                                                )
                                            }}
                                        </span>
                                    </div>

                                    <div class="team-row">
                                        <div class="team-left">
                                            <div
                                                class="team-avatar team-avatar-alt"
                                            >
                                                <img
                                                    v-if="
                                                        resolveAssetUrl(
                                                            match.team_b?.logo,
                                                        )
                                                    "
                                                    :src="
                                                        resolveAssetUrl(
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
                                            <span class="team-name">{{
                                                match.team_b?.name || "Team B"
                                            }}</span>
                                        </div>
                                        <span class="team-score">
                                            {{
                                                displayScore(
                                                    match.team_b_score,
                                                    match.status,
                                                )
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </Link>
                        </div>

                        <div v-else class="dash-empty">
                            <div class="dash-empty-title">
                                No games scheduled
                            </div>
                            <div class="dash-empty-sub">
                                Check the schedule for upcoming matchups.
                            </div>
                        </div>
                    </section>

                    <section class="dash-block">
                        <div class="dash-block-header">
                            <div>
                                <h2 class="dash-title dash-title-sm">
                                    Live Now
                                </h2>
                                <div class="dash-subtitle">
                                    Matches currently in progress
                                </div>
                            </div>
                        </div>

                        <div v-if="liveNowMatches.length" class="games-grid">
                            <Link
                                v-for="match in liveNowMatches"
                                :key="match.id"
                                :href="route('match.details', match.id)"
                                class="game-card"
                                :class="{
                                    live: isLiveStatus(match.status),
                                    finished: match.status === 'finished',
                                }"
                            >
                                <div class="game-meta">
                                    <span
                                        class="status-pill"
                                        :class="
                                            'status-' + statusTone(match.status)
                                        "
                                    >
                                        <span
                                            v-if="isLiveStatus(match.status)"
                                            class="live-dot"
                                            aria-hidden="true"
                                        />
                                        {{ statusLabel(match.status) }}
                                        <span
                                            v-if="
                                                isLiveStatus(match.status) &&
                                                match.quarter
                                            "
                                        >
                                            • {{ match.quarter }}
                                        </span>
                                    </span>
                                    <span class="game-time">{{
                                        matchStartLabel(match)
                                    }}</span>
                                </div>

                                <div class="game-teams">
                                    <div class="team-row">
                                        <div class="team-left">
                                            <div class="team-avatar">
                                                <img
                                                    v-if="
                                                        resolveAssetUrl(
                                                            match.team_a?.logo,
                                                        )
                                                    "
                                                    :src="
                                                        resolveAssetUrl(
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
                                            <span class="team-name">{{
                                                match.team_a?.name || "Team A"
                                            }}</span>
                                        </div>
                                        <span class="team-score">
                                            {{
                                                displayScore(
                                                    match.team_a_score,
                                                    match.status,
                                                )
                                            }}
                                        </span>
                                    </div>

                                    <div class="team-row">
                                        <div class="team-left">
                                            <div
                                                class="team-avatar team-avatar-alt"
                                            >
                                                <img
                                                    v-if="
                                                        resolveAssetUrl(
                                                            match.team_b?.logo,
                                                        )
                                                    "
                                                    :src="
                                                        resolveAssetUrl(
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
                                            <span class="team-name">{{
                                                match.team_b?.name || "Team B"
                                            }}</span>
                                        </div>
                                        <span class="team-score">
                                            {{
                                                displayScore(
                                                    match.team_b_score,
                                                    match.status,
                                                )
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </Link>
                        </div>

                        <div v-else class="dash-empty">
                            <div class="dash-empty-title">No live games</div>
                            <div class="dash-empty-sub">
                                Live matches will appear here when games start.
                            </div>
                        </div>
                    </section>

                    <section class="dash-block">
                        <div class="dash-block-header">
                            <div>
                                <h2 class="dash-title dash-title-sm">
                                    League Leaders
                                </h2>
                                <div class="dash-subtitle">
                                    Per game leaders
                                </div>
                            </div>
                        </div>

                        <div v-if="leagueLeaders.length" class="leaders-grid">
                            <div
                                v-for="leader in leagueLeaders"
                                :key="leader.key"
                                class="leader-card"
                                :class="'tone-' + (leader.tone || 'gold')"
                            >
                                <div class="leader-top">
                                    <div class="leader-label">
                                        {{ leader.label }}
                                    </div>
                                    <div class="leader-value">
                                        {{ leader.value }}
                                    </div>
                                </div>
                                <div class="leader-person">
                                    <div class="leader-avatar">
                                        <img
                                            v-if="
                                                resolveAssetUrl(
                                                    leader.player?.photo,
                                                )
                                            "
                                            :src="
                                                resolveAssetUrl(
                                                    leader.player?.photo,
                                                )
                                            "
                                            :alt="
                                                (leader.player?.name ||
                                                    'Leader') + ' photo'
                                            "
                                            class="leader-avatar-image"
                                            loading="lazy"
                                        />
                                        <span
                                            v-else
                                            class="leader-avatar-fallback"
                                        >
                                            {{
                                                leader.player?.name?.charAt(
                                                    0,
                                                ) || "P"
                                            }}
                                        </span>
                                    </div>
                                    <div class="leader-player">
                                        <div class="leader-name">
                                            {{ leader.player?.name || "—" }}
                                        </div>
                                        <div class="leader-team">
                                            {{ leader.player?.team || "" }}
                                        </div>
                                    </div>
                                </div>

                                <div
                                    v-if="
                                        Array.isArray(leader.sparkline) &&
                                        leader.sparkline.length
                                    "
                                    class="sparkline"
                                    aria-hidden="true"
                                >
                                    <span
                                        v-for="(v, i) in leader.sparkline"
                                        :key="i"
                                        class="spark-bar"
                                        :style="{
                                            height:
                                                sparkHeight(
                                                    leader.sparkline,
                                                    v,
                                                ) + '%',
                                        }"
                                    />
                                </div>
                            </div>
                        </div>

                        <div v-else class="dash-empty">
                            <div class="dash-empty-title">No leaders yet</div>
                            <div class="dash-empty-sub">
                                Player stats will appear once games are
                                recorded.
                            </div>
                        </div>

                        <div
                            v-if="standingsTeams.length"
                            class="teams-marquee"
                            aria-label="Teams carousel"
                        >
                            <div class="teams-marquee-track">
                                <div class="teams-marquee-group">
                                    <div
                                        v-for="(t, idx) in standingsTeams"
                                        :key="t.id ?? idx"
                                        class="team-chip"
                                    >
                                        <div class="team-chip-logo">
                                            <img
                                                v-if="resolveAssetUrl(t.logo)"
                                                :src="resolveAssetUrl(t.logo)"
                                                :alt="
                                                    (t.name || 'Team') + ' logo'
                                                "
                                                class="team-chip-logo-img"
                                                loading="lazy"
                                            />
                                            <span
                                                v-else
                                                class="team-chip-logo-fallback"
                                            >
                                                {{ t.name?.charAt(0) || "T" }}
                                            </span>
                                        </div>
                                        <span class="team-chip-name">
                                            {{ t.name || "Team" }}
                                        </span>
                                    </div>
                                </div>

                                <div
                                    class="teams-marquee-group"
                                    aria-hidden="true"
                                >
                                    <div
                                        v-for="(t, idx) in standingsTeams"
                                        :key="`dup-${t.id ?? idx}`"
                                        class="team-chip"
                                    >
                                        <div class="team-chip-logo">
                                            <img
                                                v-if="resolveAssetUrl(t.logo)"
                                                :src="resolveAssetUrl(t.logo)"
                                                :alt="
                                                    (t.name || 'Team') + ' logo'
                                                "
                                                class="team-chip-logo-img"
                                                loading="lazy"
                                            />
                                            <span
                                                v-else
                                                class="team-chip-logo-fallback"
                                            >
                                                {{ t.name?.charAt(0) || "T" }}
                                            </span>
                                        </div>
                                        <span class="team-chip-name">
                                            {{ t.name || "Team" }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="dash-block">
                        <div class="dash-block-header dash-block-header-tight">
                            <div>
                                <h2 class="dash-title dash-title-sm">
                                    Player Stats
                                </h2>
                                <div class="dash-subtitle">Top performers</div>
                            </div>
                            <div class="stat-tabs">
                                <button
                                    v-for="tab in statTabs"
                                    :key="tab.key"
                                    type="button"
                                    class="stat-tab"
                                    :class="{
                                        active: selectedStatKey === tab.key,
                                    }"
                                    @click="selectedStatKey = tab.key"
                                >
                                    {{ tab.label }}
                                </button>
                            </div>
                        </div>

                        <div
                            v-if="divisions && divisions.length"
                            class="division-pills"
                        >
                            <button
                                type="button"
                                class="division-pill"
                                :class="{ active: selectedDivisionId === null }"
                                @click="selectedDivisionId = null"
                            >
                                All Teams
                            </button>
                            <button
                                v-for="d in divisions"
                                :key="d.id"
                                type="button"
                                class="division-pill"
                                :class="{ active: selectedDivisionId === d.id }"
                                @click="selectedDivisionId = d.id"
                            >
                                {{ d.name }}
                            </button>
                        </div>

                        <div class="stats-table-wrap">
                            <template v-if="topPlayers.length">
                                <table class="stats-table stats-table-desktop">
                                    <thead>
                                        <tr>
                                            <th class="col-rank">#</th>
                                            <th class="col-player">Player</th>
                                            <th class="col-gp">GP</th>
                                            <th class="col-num">PTS</th>
                                            <th class="col-num">REB</th>
                                            <th class="col-num">AST</th>
                                            <th class="col-num">EFF</th>
                                            <th class="col-trend">Trend</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(p, idx) in topPlayers"
                                            :key="p.id"
                                        >
                                            <td class="cell-rank">
                                                {{ idx + 1 }}
                                            </td>
                                            <td class="cell-player">
                                                <div class="player-cell">
                                                    <div class="player-avatar">
                                                        <img
                                                            v-if="
                                                                resolveAssetUrl(
                                                                    p.photo,
                                                                )
                                                            "
                                                            :src="
                                                                resolveAssetUrl(
                                                                    p.photo,
                                                                )
                                                            "
                                                            :alt="
                                                                (p.name ||
                                                                    'Player') +
                                                                ' photo'
                                                            "
                                                            class="player-avatar-image"
                                                            loading="lazy"
                                                        />
                                                        <span
                                                            v-else
                                                            class="player-avatar-fallback"
                                                        >
                                                            {{
                                                                p.name?.charAt(
                                                                    0,
                                                                ) || "P"
                                                            }}
                                                        </span>
                                                    </div>
                                                    <div class="player-meta">
                                                        <div
                                                            class="player-name"
                                                        >
                                                            {{ p.name }}
                                                        </div>
                                                        <div
                                                            class="player-team"
                                                        >
                                                            {{ p.team }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="cell-gp">
                                                {{ p.games_played }}
                                            </td>
                                            <td
                                                class="cell-num"
                                                :class="{
                                                    highlight:
                                                        selectedStatKey ===
                                                        'ppg',
                                                }"
                                            >
                                                {{ formatOneDecimal(p.ppg) }}
                                            </td>
                                            <td
                                                class="cell-num"
                                                :class="{
                                                    highlight:
                                                        selectedStatKey ===
                                                        'rpg',
                                                }"
                                            >
                                                {{ formatOneDecimal(p.rpg) }}
                                            </td>
                                            <td
                                                class="cell-num"
                                                :class="{
                                                    highlight:
                                                        selectedStatKey ===
                                                        'apg',
                                                }"
                                            >
                                                {{ formatOneDecimal(p.apg) }}
                                            </td>
                                            <td
                                                class="cell-num"
                                                :class="{
                                                    highlight:
                                                        selectedStatKey ===
                                                        'eff',
                                                }"
                                            >
                                                {{ formatOneDecimal(p.eff) }}
                                            </td>
                                            <td class="cell-trend">
                                                <div class="trend-bar">
                                                    <div
                                                        class="trend-fill"
                                                        :style="{
                                                            width:
                                                                metricPercent(
                                                                    p,
                                                                ) + '%',
                                                        }"
                                                    />
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div
                                    class="stats-cards"
                                    aria-label="Top players"
                                >
                                    <div
                                        v-for="(p, idx) in topPlayers"
                                        :key="p.id"
                                        class="stats-card"
                                    >
                                        <div class="stats-card-head">
                                            <div class="stats-card-rank">
                                                {{ idx + 1 }}
                                            </div>
                                            <div class="stats-card-player">
                                                <div class="player-cell">
                                                    <div class="player-avatar">
                                                        <img
                                                            v-if="
                                                                resolveAssetUrl(
                                                                    p.photo,
                                                                )
                                                            "
                                                            :src="
                                                                resolveAssetUrl(
                                                                    p.photo,
                                                                )
                                                            "
                                                            :alt="
                                                                (p.name ||
                                                                    'Player') +
                                                                ' photo'
                                                            "
                                                            class="player-avatar-image"
                                                            loading="lazy"
                                                        />
                                                        <span
                                                            v-else
                                                            class="player-avatar-fallback"
                                                        >
                                                            {{
                                                                p.name?.charAt(
                                                                    0,
                                                                ) || "P"
                                                            }}
                                                        </span>
                                                    </div>
                                                    <div class="player-meta">
                                                        <div
                                                            class="player-name"
                                                        >
                                                            {{ p.name }}
                                                        </div>
                                                        <div
                                                            class="player-team"
                                                        >
                                                            {{ p.team }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="stats-card-grid">
                                            <div class="stats-card-item">
                                                <span class="stats-card-k"
                                                    >GP</span
                                                >
                                                <span class="stats-card-v">{{
                                                    p.games_played
                                                }}</span>
                                            </div>
                                            <div
                                                class="stats-card-item"
                                                :class="{
                                                    highlight:
                                                        selectedStatKey ===
                                                        'ppg',
                                                }"
                                            >
                                                <span class="stats-card-k"
                                                    >PTS</span
                                                >
                                                <span class="stats-card-v">
                                                    {{
                                                        formatOneDecimal(p.ppg)
                                                    }}
                                                </span>
                                            </div>
                                            <div
                                                class="stats-card-item"
                                                :class="{
                                                    highlight:
                                                        selectedStatKey ===
                                                        'rpg',
                                                }"
                                            >
                                                <span class="stats-card-k"
                                                    >REB</span
                                                >
                                                <span class="stats-card-v">
                                                    {{
                                                        formatOneDecimal(p.rpg)
                                                    }}
                                                </span>
                                            </div>
                                            <div
                                                class="stats-card-item"
                                                :class="{
                                                    highlight:
                                                        selectedStatKey ===
                                                        'apg',
                                                }"
                                            >
                                                <span class="stats-card-k"
                                                    >AST</span
                                                >
                                                <span class="stats-card-v">
                                                    {{
                                                        formatOneDecimal(p.apg)
                                                    }}
                                                </span>
                                            </div>
                                            <div
                                                class="stats-card-item wide"
                                                :class="{
                                                    highlight:
                                                        selectedStatKey ===
                                                        'eff',
                                                }"
                                            >
                                                <span class="stats-card-k"
                                                    >EFF</span
                                                >
                                                <span class="stats-card-v">
                                                    {{
                                                        formatOneDecimal(p.eff)
                                                    }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="stats-card-trend">
                                            <div class="trend-bar">
                                                <div
                                                    class="trend-fill"
                                                    :style="{
                                                        width:
                                                            metricPercent(p) +
                                                            '%',
                                                    }"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div v-else class="dash-empty dash-empty-compact">
                                <div class="dash-empty-title">
                                    No player stats yet
                                </div>
                                <div class="dash-empty-sub">
                                    This table populates after matches finish.
                                </div>
                            </div>
                        </div>
                    </section>
                </template>
            </main>

            <aside v-if="!isOffSeason" class="dashboard-sidebar">
                <section class="side-card">
                    <div class="side-card-header">
                        <h3 class="side-title">Standings</h3>
                        <Link
                            :href="route('home', { section: 'standings' })"
                            class="side-link"
                            >View →</Link
                        >
                    </div>

                    <div v-if="standingsTeams.length" class="standings-list">
                        <div
                            v-for="(t, idx) in standingsTeams.slice(0, 8)"
                            :key="t.id"
                            class="standing-row"
                        >
                            <div class="standing-rank">{{ idx + 1 }}</div>
                            <div class="standing-team">
                                <div class="standing-avatar">
                                    <img
                                        v-if="resolveAssetUrl(t.logo)"
                                        :src="resolveAssetUrl(t.logo)"
                                        :alt="(t.name || 'Team') + ' logo'"
                                        class="standing-avatar-image"
                                        loading="lazy"
                                    />
                                    <span
                                        v-else
                                        class="standing-avatar-fallback"
                                    >
                                        {{ t.name?.charAt(0) || "T" }}
                                    </span>
                                </div>
                                <div class="standing-meta">
                                    <div class="standing-name">
                                        {{ t.name }}
                                    </div>
                                    <div class="standing-record">
                                        {{ t.wins }}-{{ t.losses }}
                                        <span
                                            v-if="
                                                t.win_percentage !== undefined
                                            "
                                            >• {{ t.win_percentage }}%</span
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="standing-points">{{ t.points }}</div>
                        </div>
                    </div>
                    <div v-else class="dash-empty dash-empty-compact">
                        <div class="dash-empty-title">No standings yet</div>
                        <div class="dash-empty-sub">
                            Teams will appear once games are played.
                        </div>
                    </div>
                </section>

                <section class="side-card">
                    <div class="side-card-header">
                        <h3 class="side-title">Schedule</h3>
                        <Link
                            :href="route('home', { section: 'schedule' })"
                            class="side-link"
                            >View →</Link
                        >
                    </div>

                    <div v-if="upcomingMatches.length" class="schedule-list">
                        <Link
                            v-for="match in upcomingMatches.slice(0, 6)"
                            :key="match.id"
                            :href="route('match.details', match.id)"
                            class="schedule-row"
                        >
                            <div class="schedule-date">
                                <div class="schedule-day">
                                    {{ dayOfMonth(match.match_date) }}
                                </div>
                                <div class="schedule-mon">
                                    {{ shortMonth(match.match_date) }}
                                </div>
                            </div>
                            <div class="schedule-meta">
                                <div class="schedule-teams">
                                    {{ match.team_a?.name || "Team A" }}
                                    <span class="schedule-vs">vs</span>
                                    {{ match.team_b?.name || "Team B" }}
                                </div>
                                <div class="schedule-time">
                                    {{ matchTimeOnly(match) }}
                                </div>
                            </div>
                        </Link>
                    </div>
                    <div v-else class="dash-empty dash-empty-compact">
                        <div class="dash-empty-title">No upcoming games</div>
                        <div class="dash-empty-sub">
                            Check back soon for the next fixtures.
                        </div>
                    </div>
                </section>

                <section class="side-card">
                    <div class="side-card-header">
                        <h3 class="side-title">Registrations</h3>
                    </div>

                    <div class="reg-list">
                        <Link :href="route('register')" class="reg-item">
                            <span class="reg-text">
                                <span class="reg-label"
                                    >Player Registration</span
                                >
                                <span
                                    class="reg-meta"
                                    :class="{
                                        'is-closed':
                                            playerRegistrationMeta.isClosed,
                                    }"
                                    :title="
                                        playerRegistrationMeta.title ||
                                        undefined
                                    "
                                >
                                    {{ playerRegistrationMeta.text }}
                                </span>
                            </span>
                            <span class="reg-arrow" aria-hidden="true">→</span>
                        </Link>
                        <Link :href="route('team-register')" class="reg-item">
                            <span class="reg-text">
                                <span class="reg-label">Team Registration</span>
                                <span
                                    class="reg-meta"
                                    :class="{
                                        'is-closed':
                                            teamRegistrationMeta.isClosed,
                                    }"
                                    :title="
                                        teamRegistrationMeta.title || undefined
                                    "
                                >
                                    {{ teamRegistrationMeta.text }}
                                </span>
                            </span>
                            <span class="reg-arrow" aria-hidden="true">→</span>
                        </Link>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { computed, onBeforeUnmount, onMounted, ref } from "vue";

const props = defineProps({
    isOffSeason: {
        type: Boolean,
        default: false,
    },
    dashboardMatches: {
        type: Array,
        default: () => [],
    },
    liveMatches: {
        type: Array,
        default: () => [],
    },
    standingsTeams: {
        type: Array,
        default: () => [],
    },
    upcomingMatches: {
        type: Array,
        default: () => [],
    },
    divisions: {
        type: Array,
        default: () => [],
    },
    playerStats: {
        type: Array,
        default: () => [],
    },
    leagueLeaders: {
        type: Array,
        default: () => [],
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
});

const selectedStatKey = ref("ppg");
const selectedDivisionId = ref(null);

const isLiveStatus = (status) => status === "live" || status === "ongoing";

const statusTone = (status) => {
    if (isLiveStatus(status)) return "live";
    if (status === "finished") return "finished";
    return status || "upcoming";
};

const liveNowMatches = computed(() => {
    const base = Array.isArray(props.liveMatches) ? props.liveMatches : [];
    return base.filter((m) => isLiveStatus(m?.status));
});

const now = ref(Date.now());
let nowIntervalId = null;

onMounted(() => {
    nowIntervalId = setInterval(() => {
        now.value = Date.now();
    }, 1000);
});

onBeforeUnmount(() => {
    if (nowIntervalId) {
        clearInterval(nowIntervalId);
    }
});

const parseDateTime = (value) => {
    if (!value || typeof value !== "string") return null;

    const direct = new Date(value);
    if (!isNaN(direct.getTime())) return direct;

    const normalized = value.includes(" ") ? value.replace(" ", "T") : value;
    const fallback = new Date(normalized);
    if (!isNaN(fallback.getTime())) return fallback;

    return null;
};

const pad2 = (n) => String(n).padStart(2, "0");

const formatCountdown = (ms) => {
    const totalSeconds = Math.max(0, Math.ceil(ms / 1000));
    const days = Math.floor(totalSeconds / 86400);
    const hours = Math.floor((totalSeconds % 86400) / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;

    const hms = `${pad2(hours)}:${pad2(minutes)}:${pad2(seconds)}`;
    return days > 0 ? `${days}d ${hms}` : hms;
};

const formatClosingDateTime = (d) => {
    if (!(d instanceof Date) || isNaN(d.getTime())) return "";

    return d.toLocaleString("en-US", {
        month: "short",
        day: "numeric",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const buildRegistrationMeta = (isOpen, closingDate) => {
    if (!isOpen) {
        return {
            text: "Closed",
            title: null,
            isClosed: true,
        };
    }

    const closing = parseDateTime(closingDate);
    if (!closing) {
        return {
            text: "Open",
            title: null,
            isClosed: false,
        };
    }

    const remainingMs = closing.getTime() - now.value;
    const title = `Closes ${formatClosingDateTime(closing)}`;

    if (remainingMs <= 0) {
        return {
            text: "Closed",
            title,
            isClosed: true,
        };
    }

    return {
        text: `Closes in ${formatCountdown(remainingMs)}`,
        title,
        isClosed: false,
    };
};

const playerRegistrationMeta = computed(() =>
    buildRegistrationMeta(
        props.registrationOpen,
        props.registrationClosingDate,
    ),
);

const teamRegistrationMeta = computed(() =>
    buildRegistrationMeta(
        props.teamRegistrationOpen,
        props.teamRegistrationClosingDate,
    ),
);

const statTabs = [
    { key: "ppg", label: "PTS" },
    { key: "rpg", label: "REB" },
    { key: "apg", label: "AST" },
    { key: "eff", label: "EFF" },
];

const todayLabel = computed(() => {
    const d = new Date();
    if (isNaN(d.getTime())) return "";
    return d.toLocaleDateString("en-US", {
        weekday: "long",
        month: "long",
        day: "numeric",
        year: "numeric",
    });
});

const filteredPlayers = computed(() => {
    const base = Array.isArray(props.playerStats) ? props.playerStats : [];
    if (selectedDivisionId.value == null) return base;
    return base.filter((p) => p?.division_id === selectedDivisionId.value);
});

const sortedPlayers = computed(() => {
    const key = selectedStatKey.value;
    return [...filteredPlayers.value].sort((a, b) => {
        const av = Number(a?.[key] ?? 0);
        const bv = Number(b?.[key] ?? 0);
        return bv - av;
    });
});

const topPlayers = computed(() => sortedPlayers.value.slice(0, 10));

const currentMetricMax = computed(() => {
    const key = selectedStatKey.value;
    const max = Math.max(
        0,
        ...topPlayers.value.map((p) => Number(p?.[key] ?? 0)),
    );
    return max || 1;
});

const metricPercent = (player) => {
    const key = selectedStatKey.value;
    const v = Number(player?.[key] ?? 0);
    const pct = Math.round((v / currentMetricMax.value) * 100);
    return Math.max(0, Math.min(100, pct));
};

const formatOneDecimal = (v) => {
    const n = Number(v);
    if (Number.isNaN(n)) return "0.0";
    return n.toFixed(1);
};

const resolveAssetUrl = (path) => {
    if (!path || typeof path !== "string") return null;
    if (path.startsWith("http://") || path.startsWith("https://")) return path;
    if (path.startsWith("/")) return path;
    if (path.startsWith("uploads/")) return `/${path}`;
    return `/storage/${path}`;
};

const statusLabel = (status) => {
    switch (status) {
        case "live":
            return "LIVE";
        case "ongoing":
            return "LIVE";
        case "finished":
            return "FINAL";
        case "upcoming":
            return "UPCOMING";
        default:
            return (status || "UPCOMING").toString().toUpperCase();
    }
};

const displayScore = (score, status) => {
    if (!isLiveStatus(status) && status !== "finished") return "—";
    if (score === null || score === undefined) return "—";
    return String(score);
};

const formatShortDate = (date) => {
    if (!date) return "";
    const d = new Date(date);
    if (isNaN(d.getTime())) return "";
    return d.toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
    });
};

const formatTime = (time) => {
    if (!time) return "";
    if (
        typeof time === "string" &&
        (time.includes("T") || time.includes(" "))
    ) {
        const d = new Date(time);
        if (!isNaN(d.getTime())) {
            return d.toLocaleTimeString("en-US", {
                hour: "2-digit",
                minute: "2-digit",
            });
        }
    }
    const d = new Date(`2000-01-01T${time}`);
    if (isNaN(d.getTime())) return "";
    return d.toLocaleTimeString("en-US", {
        hour: "2-digit",
        minute: "2-digit",
    });
};

const matchStartLabel = (match) => {
    const date = formatShortDate(match?.match_date);
    const time = formatTime(match?.match_time || match?.match_date);
    if (date && time) return `${date} • ${time}`;
    return date || time || "";
};

const matchTimeOnly = (match) => {
    const time = formatTime(match?.match_time || match?.match_date);
    return time || "TBD";
};

const dayOfMonth = (date) => {
    const d = new Date(date);
    if (isNaN(d.getTime())) return "—";
    return d.getDate();
};

const shortMonth = (date) => {
    const d = new Date(date);
    if (isNaN(d.getTime())) return "—";
    return d.toLocaleDateString("en-US", { month: "short" }).toUpperCase();
};

const teamAbbr = (name) => {
    if (!name) return "—";
    const parts = String(name).trim().split(/\s+/).filter(Boolean);
    if (parts.length === 1) return parts[0].slice(0, 3).toUpperCase();
    return parts
        .slice(0, 3)
        .map((p) => p.charAt(0))
        .join("")
        .toUpperCase();
};

const sparkHeight = (arr, v) => {
    const nums = (Array.isArray(arr) ? arr : []).map((n) => Number(n ?? 0));
    const max = Math.max(1, ...nums);
    const val = Number(v ?? 0);
    const pct = Math.round((val / max) * 100);
    return Math.max(12, Math.min(100, pct));
};
</script>

<style scoped>
.dashboard-shell {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 1.25rem;
}

.dashboard-grid > * {
    min-width: 0;
}

@media (min-width: 1024px) {
    .dashboard-grid {
        grid-template-columns: minmax(0, 1fr) 360px;
        align-items: start;
    }
}

.dashboard-main {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    min-width: 0;
}

.dashboard-main > * {
    min-width: 0;
}

.dashboard-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    min-width: 0;
}

.dashboard-sidebar > * {
    min-width: 0;
}

.dash-block {
    background: var(--cv-surface-2);
    border: 1px solid var(--cv-border-1);
    border-radius: 1rem;
    padding: 1rem;
    overflow: hidden;
    min-width: 0;
}

.dash-block-offseason {
    border-color: rgba(251, 191, 36, 0.35);
    background: rgba(251, 191, 36, 0.06);
}

.dash-block-offseason .dash-title {
    color: rgba(251, 191, 36, 0.95);
}

@media (min-width: 640px) {
    .dash-block {
        padding: 1.25rem;
    }
}

.dash-block-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1rem;
}

.dash-block-header-tight {
    align-items: center;
}

.dash-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    letter-spacing: -0.02em;
    color: var(--cv-text-1);
}

.dash-title-sm {
    font-size: 1.05rem;
    letter-spacing: -0.01em;
}

.dash-subtitle {
    margin-top: 0.25rem;
    font-size: 0.85rem;
    color: var(--cv-muted);
}

.dash-link {
    font-size: 0.85rem;
    font-weight: 700;
    color: rgba(251, 191, 36, 0.95);
    text-decoration: none;
    padding: 0.35rem 0.6rem;
    border-radius: 999px;
    border: 1px solid rgba(251, 191, 36, 0.25);
    background: var(--cv-surface-1);
}

.dash-link:hover {
    background: rgba(251, 191, 36, 0.1);
}

.games-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.75rem;
}

@media (min-width: 720px) {
    .games-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

.game-card {
    display: block;
    text-decoration: none;
    color: inherit;
    padding: 0.9rem;
    border-radius: 0.9rem;
    background: var(--cv-surface-3);
    border: 1px solid var(--cv-border-1);
    transition:
        transform 120ms ease,
        border-color 120ms ease,
        background 120ms ease;
}

.game-card:hover {
    transform: translateY(-1px);
    border-color: rgba(251, 191, 36, 0.35);
    background: var(--cv-surface-3);
}

.game-card.live {
    border-color: rgba(239, 68, 68, 0.35);
}

.game-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.6rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 900;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    border: 1px solid var(--cv-border-1);
    background: var(--cv-surface-1);
    color: var(--cv-text-2);
}

.status-pill.status-live {
    border-color: rgba(239, 68, 68, 0.4);
    color: rgba(239, 68, 68, 0.95);
    background: rgba(239, 68, 68, 0.08);
}

.status-pill.status-finished {
    border-color: rgba(34, 197, 94, 0.35);
    color: rgba(34, 197, 94, 0.95);
    background: rgba(34, 197, 94, 0.08);
}

.live-dot {
    width: 0.5rem;
    height: 0.5rem;
    border-radius: 999px;
    background: rgba(239, 68, 68, 0.95);
    box-shadow: 0 0 0 6px rgba(239, 68, 68, 0.12);
}

.game-time {
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--cv-muted);
}

.game-teams {
    display: flex;
    flex-direction: column;
    gap: 0.55rem;
}

.team-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
}

.team-left {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    min-width: 0;
}

.team-avatar {
    width: 2.1rem;
    height: 2.1rem;
    border-radius: 0.75rem;
    background: var(--cv-surface-2);
    border: 1px solid var(--cv-border-1);
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.team-avatar-alt {
    background: var(--cv-surface-1);
}

.team-avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.team-avatar-fallback {
    font-weight: 900;
    color: rgba(251, 191, 36, 0.9);
}

.team-name {
    font-weight: 600;
    color: var(--cv-text-1);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.team-score {
    font-size: 1.15rem;
    font-weight: 900;
    color: var(--cv-text-1);
}

.dash-empty {
    border-radius: 0.9rem;
    padding: 1.1rem;
    background: var(--cv-surface-3);
    border: 1px dashed var(--cv-border-1);
    text-align: center;
}

.dash-empty-compact {
    padding: 0.9rem;
}

.dash-empty-title {
    font-weight: 600;
    color: var(--cv-text-1);
}

.dash-empty-sub {
    margin-top: 0.25rem;
    font-size: 0.85rem;
    color: var(--cv-muted);
}

.leaders-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.75rem;
}

@media (min-width: 720px) {
    .leaders-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

.leader-card {
    padding: 0.9rem;
    border-radius: 0.9rem;
    border: 1px solid var(--cv-border-1);
    background: var(--cv-surface-3);
    overflow: hidden;
}

.leader-card.tone-gold {
    border-color: rgba(251, 191, 36, 0.3);
}

.leader-card.tone-blue {
    border-color: rgba(59, 130, 246, 0.28);
}

.leader-card.tone-green {
    border-color: rgba(34, 197, 94, 0.28);
}

.leader-top {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
}

.leader-label {
    font-size: 0.72rem;
    font-weight: 900;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--cv-muted);
}

.leader-value {
    font-size: 1.35rem;
    font-weight: 900;
    color: var(--cv-text-1);
}

.leader-player {
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
}

.leader-person {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-top: 0.25rem;
}

.leader-avatar {
    width: 2.35rem;
    height: 2.35rem;
    border-radius: 0.9rem;
    border: 1px solid var(--cv-border-1);
    background: var(--cv-surface-2);
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.leader-avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.leader-avatar-fallback {
    font-weight: 900;
    color: rgba(251, 191, 36, 0.9);
}

.leader-name {
    font-weight: 600;
    color: var(--cv-text-1);
}

.leader-team {
    font-size: 0.85rem;
    color: var(--cv-muted);
}

.sparkline {
    display: flex;
    align-items: flex-end;
    gap: 0.25rem;
    height: 2.1rem;
    margin-top: 0.75rem;
}

.spark-bar {
    width: 0.35rem;
    border-radius: 999px;
    background: rgba(251, 191, 36, 0.55);
}

.leader-card.tone-blue .spark-bar {
    background: rgba(59, 130, 246, 0.55);
}

.leader-card.tone-green .spark-bar {
    background: rgba(34, 197, 94, 0.55);
}

.teams-marquee {
    margin-top: 0.9rem;
    border-radius: 0.9rem;
    border: 1px solid var(--cv-border-1);
    background: var(--cv-surface-3);
    overflow: hidden;
    width: 100%;
    min-width: 0;
}

.teams-marquee-track {
    display: flex;
    width: max-content;
    animation: teams-marquee 28s linear infinite;
    will-change: transform;
}

.teams-marquee-group {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.65rem;
}

.team-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    padding: 0.4rem 0.55rem;
    border-radius: 999px;
    border: 1px solid var(--cv-border-1);
    background: var(--cv-surface-2);
    flex-shrink: 0;
}

.team-chip-logo {
    width: 1.6rem;
    height: 1.6rem;
    border-radius: 999px;
    border: 1px solid var(--cv-border-1);
    background: var(--cv-surface-1);
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.team-chip-logo-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.team-chip-logo-fallback {
    font-weight: 900;
    font-size: 0.75rem;
    color: rgba(251, 191, 36, 0.9);
}

.team-chip-name {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--cv-text-1);
    max-width: 10rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

@keyframes teams-marquee {
    from {
        transform: translateX(0);
    }

    to {
        transform: translateX(-50%);
    }
}

@media (prefers-reduced-motion: reduce) {
    .teams-marquee-track {
        animation: none;
    }
}

.stat-tabs {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.stat-tab {
    appearance: none;
    border: 1px solid var(--cv-border-1);
    background: var(--cv-surface-3);
    color: var(--cv-text-2);
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 0.35rem 0.6rem;
    border-radius: 999px;
    cursor: pointer;
    transition:
        border-color 120ms ease,
        background 120ms ease,
        color 120ms ease;
}

.stat-tab:hover {
    border-color: rgba(251, 191, 36, 0.35);
}

.stat-tab.active {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-color: rgba(251, 191, 36, 0.6);
    color: rgba(15, 23, 42, 0.98);
}

.division-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 0.9rem;
}

.division-pill {
    appearance: none;
    border: 1px solid var(--cv-border-1);
    background: var(--cv-surface-3);
    color: var(--cv-text-2);
    font-weight: 600;
    padding: 0.35rem 0.65rem;
    border-radius: 999px;
    cursor: pointer;
    transition:
        border-color 120ms ease,
        background 120ms ease,
        color 120ms ease;
}

.division-pill:hover {
    border-color: rgba(251, 191, 36, 0.28);
}

.division-pill.active {
    border-color: rgba(251, 191, 36, 0.55);
    background: rgba(251, 191, 36, 0.12);
    color: var(--cv-text-1);
}

.stats-table-wrap {
    overflow-x: auto;
    border-radius: 0.9rem;
    border: 1px solid var(--cv-border-1);
    background: var(--cv-surface-3);
}

.stats-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    min-width: 760px;
}

.stats-table thead th {
    text-align: left;
    padding: 0.85rem 0.85rem;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--cv-muted);
    border-bottom: 1px solid var(--cv-border-1);
}

.stats-table tbody td {
    padding: 0.75rem 0.85rem;
    border-bottom: 1px solid var(--cv-border-1);
    color: var(--cv-text-1);
}

.stats-table tbody tr:hover td {
    background: var(--cv-surface-1);
}

.col-rank,
.cell-rank {
    width: 3rem;
    text-align: center;
}

.col-gp,
.cell-gp {
    width: 4rem;
    text-align: center;
    color: var(--cv-text-2);
    font-weight: 600;
}

.col-num,
.cell-num {
    width: 5rem;
    text-align: right;
    font-variant-numeric: tabular-nums;
}

.cell-num {
    font-weight: 600;
}

.cell-num.highlight {
    color: rgba(251, 191, 36, 0.95);
}

.col-trend,
.cell-trend {
    width: 10rem;
}

.player-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 0;
}

.player-avatar {
    width: 2.2rem;
    height: 2.2rem;
    border-radius: 0.85rem;
    border: 1px solid var(--cv-border-1);
    background: var(--cv-surface-2);
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.player-avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.player-avatar-fallback {
    font-weight: 900;
    color: rgba(251, 191, 36, 0.9);
}

.player-meta {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.player-name {
    font-weight: 600;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.player-team {
    font-size: 0.85rem;
    color: var(--cv-muted);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.trend-bar {
    height: 0.45rem;
    border-radius: 999px;
    background: var(--cv-surface-2);
    overflow: hidden;
}

.trend-fill {
    height: 100%;
    border-radius: 999px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.side-card {
    background: var(--cv-surface-2);
    border: 1px solid var(--cv-border-1);
    border-radius: 1rem;
    padding: 1rem;
    min-width: 0;
}

.side-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 0.85rem;
}

.side-title {
    margin: 0;
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--cv-text-1);
}

.side-pill {
    font-size: 0.75rem;
    font-weight: 900;
    letter-spacing: 0.12em;
    color: rgba(254, 202, 202, 0.95);
    border: 1px solid rgba(239, 68, 68, 0.35);
    background: rgba(239, 68, 68, 0.08);
    border-radius: 999px;
    padding: 0.25rem 0.55rem;
}

.side-link {
    font-size: 0.85rem;
    font-weight: 800;
    color: var(--cv-text-2);
    text-decoration: none;
    padding: 0.25rem 0.55rem;
    border-radius: 999px;
    border: 1px solid var(--cv-border-1);
}

.side-link:hover {
    background: var(--cv-surface-3);
}

.live-list,
.standings-list,
.schedule-list,
.reg-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.live-item,
.schedule-row,
.reg-item {
    text-decoration: none;
    color: inherit;
    border-radius: 0.85rem;
    border: 1px solid var(--cv-border-1);
    background: var(--cv-surface-3);
    padding: 0.75rem;
    transition:
        border-color 120ms ease,
        background 120ms ease;
}

.live-item:hover,
.schedule-row:hover,
.reg-item:hover {
    border-color: rgba(251, 191, 36, 0.28);
    background: var(--cv-surface-3);
}

.reg-item {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
}

.reg-text {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    min-width: 0;
}

.reg-label {
    font-weight: 600;
    color: var(--cv-text-1);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    min-width: 0;
}

.reg-meta {
    font-size: 0.75rem;
    font-weight: 800;
    color: var(--cv-muted);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.reg-meta.is-closed {
    color: rgba(254, 202, 202, 0.95);
}

.reg-arrow {
    flex-shrink: 0;
    font-weight: 900;
    color: rgba(251, 191, 36, 0.95);
}

.live-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
}

.live-teams {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    font-weight: 600;
}

.abbr {
    color: var(--cv-text-1);
}

.live-vs {
    font-size: 0.85rem;
    color: var(--cv-muted-2);
    font-weight: 800;
}

.live-score {
    font-weight: 900;
    color: rgba(254, 202, 202, 0.95);
    font-variant-numeric: tabular-nums;
}

.live-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.live-meta-text {
    font-size: 0.85rem;
    color: var(--cv-muted);
    font-weight: 800;
}

.standing-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.6rem 0.65rem;
    border-radius: 0.85rem;
    border: 1px solid var(--cv-border-1);
    background: var(--cv-surface-3);
}

.standing-rank {
    width: 1.6rem;
    text-align: center;
    font-weight: 900;
    color: var(--cv-muted);
}

.standing-team {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    min-width: 0;
    flex: 1;
}

.standing-avatar {
    width: 2.05rem;
    height: 2.05rem;
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
    background: var(--cv-surface-2);
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.standing-avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.standing-avatar-fallback {
    font-weight: 900;
    color: rgba(251, 191, 36, 0.9);
}

.standing-meta {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.standing-name {
    font-weight: 600;
    color: var(--cv-text-1);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.standing-record {
    font-size: 0.82rem;
    color: var(--cv-muted);
    font-weight: 800;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.standing-points {
    font-weight: 900;
    color: rgba(251, 191, 36, 0.95);
    font-variant-numeric: tabular-nums;
}

.schedule-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.schedule-date {
    width: 3.1rem;
    border-radius: 0.8rem;
    border: 1px solid var(--cv-border-1);
    background: var(--cv-surface-2);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 0.35rem 0.25rem;
    flex-shrink: 0;
}

.schedule-day {
    font-size: 1.05rem;
    font-weight: 900;
    color: var(--cv-text-1);
}

.schedule-mon {
    font-size: 0.72rem;
    font-weight: 900;
    letter-spacing: 0.14em;
    color: var(--cv-muted);
}

.schedule-meta {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    min-width: 0;
}

.schedule-teams {
    font-weight: 600;
    color: var(--cv-text-1);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.schedule-vs {
    margin: 0 0.35rem;
    color: var(--cv-muted-2);
    font-weight: 800;
}

.schedule-time {
    font-size: 0.85rem;
    color: var(--cv-muted);
    font-weight: 800;
}

/* Mobile tweaks */
@media (max-width: 640px) {
    .dash-block-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .dash-block-header-tight {
        align-items: flex-start;
    }

    .dash-header-actions {
        width: 100%;
        display: flex;
        justify-content: flex-start;
    }

    .stat-tabs {
        width: 100%;
        justify-content: flex-start;
    }

    .game-meta {
        flex-direction: column;
        align-items: flex-start;
    }

    .stat-tab {
        font-size: 0.72rem;
        padding: 0.3rem 0.55rem;
    }

    .division-pill {
        font-size: 0.85rem;
        padding: 0.3rem 0.6rem;
    }

    .dash-link {
        padding: 0.3rem 0.55rem;
    }

    .team-score {
        font-size: 1.05rem;
    }
}

/* Mobile stats cards (replace wide table) */
.stats-cards {
    display: none;
}

.stats-table-desktop {
    width: 100%;
}

@media (max-width: 768px) {
    .stats-table-wrap {
        overflow: hidden;
        padding: 0.75rem;
    }

    .stats-table {
        display: none;
    }

    .stats-cards {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
}

.stats-card {
    border: 1px solid var(--cv-border-1);
    background: var(--cv-surface-1);
    color: var(--cv-text-1);
    border-radius: 0.9rem;
    padding: 0.85rem;
}

.stats-card-head {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.stats-card-rank {
    width: 2rem;
    text-align: center;
    font-weight: 900;
    color: var(--cv-muted);
    flex-shrink: 0;
}

.stats-card-player {
    min-width: 0;
    flex: 1;
}

.stats-card-grid {
    margin-top: 0.75rem;
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.5rem 0.75rem;
}

.stats-card-item {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.55rem 0.6rem;
    border-radius: 0.75rem;
    border: 1px solid var(--cv-border-1);
    background: var(--cv-surface-3);
}

.stats-card-item.wide {
    grid-column: 1 / -1;
}

.stats-card-k {
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--cv-muted);
}

.stats-card-v {
    font-weight: 600;
    font-variant-numeric: tabular-nums;
    color: var(--cv-text-1);
}

.stats-card-item.highlight .stats-card-v {
    color: rgba(251, 191, 36, 0.95);
}

.stats-card-trend {
    margin-top: 0.65rem;
}
</style>
