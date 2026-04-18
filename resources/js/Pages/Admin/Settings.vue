<template>
    <Head title="Settings - Admin | SBA" />
    <AdminLayout>
        <div class="settings-page">
            <div class="header">
                <h1 class="page-title">Settings</h1>
                <p class="page-subtitle">
                    Manage administrative settings and league operations
                </p>
            </div>

            <div class="settings-grid">
                <!-- Change Password -->
                <div class="glass-card">
                    <div class="card-header">
                        <div class="header-icon icon-cyan">
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
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                ></path>
                            </svg>
                        </div>
                        <h2 class="card-title">Change Password</h2>
                    </div>

                    <form
                        @submit.prevent="submitPassword"
                        class="settings-form"
                    >
                        <div class="form-group">
                            <label for="current_password"
                                >Current Password</label
                            >
                            <input
                                id="current_password"
                                v-model="passwordForm.current_password"
                                type="password"
                                class="form-input"
                                placeholder="Enter current password"
                                required
                            />
                            <div
                                v-if="passwordForm.errors.current_password"
                                class="error-text"
                            >
                                {{ passwordForm.errors.current_password }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input
                                id="password"
                                v-model="passwordForm.password"
                                type="password"
                                class="form-input"
                                placeholder="Enter new password"
                                required
                            />
                            <div
                                v-if="passwordForm.errors.password"
                                class="error-text"
                            >
                                {{ passwordForm.errors.password }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation"
                                >Confirm New Password</label
                            >
                            <input
                                id="password_confirmation"
                                v-model="passwordForm.password_confirmation"
                                type="password"
                                class="form-input"
                                placeholder="Confirm new password"
                                required
                            />
                        </div>

                        <button
                            type="submit"
                            class="btn-primary"
                            :disabled="passwordForm.processing"
                        >
                            {{
                                passwordForm.processing
                                    ? "Updating..."
                                    : "Update Password"
                            }}
                        </button>
                    </form>
                </div>

                <!-- League Operations -->
                <div class="glass-card">
                    <div class="card-header">
                        <div class="header-icon icon-purple">
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
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                        </div>
                        <h2 class="card-title">League Operations</h2>
                    </div>

                    <div class="settings-section">
                        <div
                            class="toggle-group"
                            style="
                                padding-bottom: 1.5rem;
                                border-bottom: 1px solid rgba(71, 85, 105, 0.5);
                                margin-bottom: 1.5rem;
                            "
                        >
                            <div class="toggle-info">
                                <h3>Off-Season Mode</h3>
                                <p>
                                    When enabled, an off-season notification
                                    will be shown on the public home page.
                                </p>
                            </div>
                            <label class="toggle-switch">
                                <input
                                    type="checkbox"
                                    v-model="offSeasonForm.off_season"
                                    @change="submitOffSeason"
                                    :disabled="offSeasonForm.processing"
                                />
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="registration-settings">
                            <div
                                class="toggle-group"
                                style="margin-bottom: 1rem"
                            >
                                <div class="toggle-info">
                                    <h3>Team Registration</h3>
                                    <p>
                                        When disabled, users cannot register new
                                        teams in the public site.
                                    </p>
                                </div>
                                <label class="toggle-switch">
                                    <input
                                        type="checkbox"
                                        v-model="
                                            teamRegistrationForm.team_registration_open
                                        "
                                        @change="submitTeamRegistration"
                                        :disabled="
                                            teamRegistrationForm.processing
                                        "
                                    />
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="form-group" style="max-width: 300px">
                                <label
                                    for="team_registration_closing_date"
                                    style="
                                        font-size: 0.875rem;
                                        color: #9ca3af;
                                        margin-bottom: 0.25rem;
                                        display: block;
                                    "
                                    >Automatic Closing Date (Optional)</label
                                >
                                <input
                                    id="team_registration_closing_date"
                                    v-model="
                                        teamRegistrationForm.team_registration_closing_date
                                    "
                                    type="datetime-local"
                                    class="form-input"
                                    @change="submitTeamRegistration"
                                    :disabled="teamRegistrationForm.processing"
                                />
                                <button
                                    type="button"
                                    class="btn-secondary btn-reset"
                                    @click="resetTeamRegistrationClosingDate"
                                    :disabled="
                                        teamRegistrationForm.processing ||
                                        !teamRegistrationForm.team_registration_closing_date
                                    "
                                >
                                    Reset Date
                                </button>
                                <p
                                    style="
                                        font-size: 0.75rem;
                                        color: #6b7280;
                                        margin-top: 0.25rem;
                                    "
                                >
                                    If set, team registration closes
                                    automatically at this time.
                                </p>
                            </div>

                            <div
                                class="toggle-group"
                                style="margin-bottom: 1rem"
                            >
                                <div class="toggle-info">
                                    <h3>Player Registration</h3>
                                    <p>
                                        When disabled, users cannot register as
                                        players in the public site.
                                    </p>
                                </div>
                                <label class="toggle-switch">
                                    <input
                                        type="checkbox"
                                        v-model="
                                            registrationForm.registration_open
                                        "
                                        @change="submitRegistration"
                                        :disabled="registrationForm.processing"
                                    />
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="form-group" style="max-width: 300px">
                                <label
                                    for="registration_closing_date"
                                    style="
                                        font-size: 0.875rem;
                                        color: #9ca3af;
                                        margin-bottom: 0.25rem;
                                        display: block;
                                    "
                                    >Automatic Closing Date (Optional)</label
                                >
                                <input
                                    id="registration_closing_date"
                                    v-model="
                                        registrationForm.registration_closing_date
                                    "
                                    type="datetime-local"
                                    class="form-input"
                                    @change="submitRegistration"
                                    :disabled="registrationForm.processing"
                                />
                                <button
                                    type="button"
                                    class="btn-secondary btn-reset"
                                    @click="resetRegistrationClosingDate"
                                    :disabled="
                                        registrationForm.processing ||
                                        !registrationForm.registration_closing_date
                                    "
                                >
                                    Reset Date
                                </button>
                                <p
                                    style="
                                        font-size: 0.75rem;
                                        color: #6b7280;
                                        margin-top: 0.25rem;
                                    "
                                >
                                    If set, registration closes automatically at
                                    this time.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="glass-card card-danger">
                    <div class="card-header">
                        <div class="header-icon icon-red">
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
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                ></path>
                            </svg>
                        </div>
                        <h2 class="card-title text-red">Danger Zone</h2>
                    </div>

                    <div class="settings-section">
                        <div class="danger-info">
                            <h3>Wipe Season Data</h3>
                            <p>
                                This action will permanently delete all
                                Divisions, Teams, Players, Matches, Stats,
                                Playoff Slots, and Announcements.
                                <strong
                                    >Only User Accounts (Admin/Committee) will
                                    remain.</strong
                                >
                            </p>
                        </div>

                        <button @click="confirmWipeData" class="btn-danger">
                            Wipe All Season Data
                        </button>
                    </div>
                </div>
            </div>

            <!-- Wipe Data Modal -->
            <div
                v-if="showWipeModal"
                class="modal-overlay"
                @click.self="closeWipeModal"
            >
                <div class="modal-content">
                    <h3 class="modal-title">Confirm Data Wipe</h3>
                    <p class="modal-desc">
                        Are you absolutely sure you want to wipe all season
                        data? This action cannot be undone. To proceed, please
                        enter your password.
                    </p>

                    <form @submit.prevent="submitWipeData">
                        <div class="form-group">
                            <label for="wipe_password"
                                >Administrator Password</label
                            >
                            <input
                                id="wipe_password"
                                v-model="wipeDataForm.password"
                                type="password"
                                class="form-input"
                                placeholder="Enter password to confirm"
                                required
                            />
                            <div
                                v-if="wipeDataForm.errors.password"
                                class="error-text"
                            >
                                {{ wipeDataForm.errors.password }}
                            </div>
                        </div>

                        <div class="modal-actions">
                            <button
                                type="button"
                                @click="closeWipeModal"
                                class="btn-secondary"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="btn-danger"
                                :disabled="wipeDataForm.processing"
                            >
                                {{
                                    wipeDataForm.processing
                                        ? "Wiping Data..."
                                        : "Confirm Wipe"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    offSeason: {
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
});

// Password Form
const passwordForm = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const submitPassword = () => {
    passwordForm.put(route("admin.settings.password"), {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
    });
};

// Off-Season Form
const offSeasonForm = useForm({
    off_season: props.offSeason,
});

const submitOffSeason = () => {
    offSeasonForm.put(route("admin.settings.off-season"), {
        preserveScroll: true,
    });
};

// Registration Settings Form
const registrationForm = useForm({
    registration_open: props.registrationOpen,
    registration_closing_date: props.registrationClosingDate || "",
});

const submitRegistration = () => {
    registrationForm.put(route("admin.settings.registration"), {
        preserveScroll: true,
    });
};

const resetRegistrationClosingDate = () => {
    registrationForm.registration_closing_date = "";
    registrationForm.clearErrors();
    submitRegistration();
};

// Team Registration Settings Form
const teamRegistrationForm = useForm({
    team_registration_open: props.teamRegistrationOpen,
    team_registration_closing_date: props.teamRegistrationClosingDate || "",
});

const submitTeamRegistration = () => {
    teamRegistrationForm.put(route("admin.settings.team-registration"), {
        preserveScroll: true,
    });
};

const resetTeamRegistrationClosingDate = () => {
    teamRegistrationForm.team_registration_closing_date = "";
    teamRegistrationForm.clearErrors();
    submitTeamRegistration();
};

// Wipe Data Form & Modal
const showWipeModal = ref(false);
const wipeDataForm = useForm({
    password: "",
});

const confirmWipeData = () => {
    showWipeModal.value = true;
};

const closeWipeModal = () => {
    showWipeModal.value = false;
    wipeDataForm.reset();
    wipeDataForm.clearErrors();
};

const submitWipeData = () => {
    wipeDataForm.post(route("admin.settings.wipe-data"), {
        preserveScroll: true,
        onSuccess: () => closeWipeModal(),
    });
};
</script>

<style scoped>
.settings-page {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.header {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.page-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
}

.page-subtitle {
    color: #9ca3af;
    margin: 0;
}

.settings-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

@media (min-width: 1024px) {
    .settings-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.glass-card {
    background: var(--cv-surface-1);
    backdrop-filter: none;
    border-radius: 0.75rem;
    padding: 1.25rem;
    border: 1px solid var(--cv-border-1);
}

.card-danger {
    border: 1px solid var(--cv-border-1);
}

.card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.header-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #e5e7eb;
    background: var(--cv-surface-3);
    border: 1px solid var(--cv-border-1);
}

.icon-cyan {
    background: var(--cv-surface-3);
}
.icon-purple {
    background: var(--cv-surface-3);
}
.icon-red {
    background: var(--cv-surface-3);
}

.header-icon .icon {
    width: 1.25rem;
    height: 1.25rem;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #ffffff;
    margin: 0;
}

.text-red {
    color: #e5e7eb;
}

/* Forms */
.settings-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-group label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #d1d5db;
}

.form-input {
    background: var(--cv-surface-1);
    border: 1px solid var(--cv-border-1);
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    color: #ffffff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: var(--cv-border-2);
    box-shadow: none;
}

.error-text {
    color: #e5e7eb;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

/* Toggle */
.toggle-group {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem;
    background: var(--cv-surface-1);
    border-radius: 0.5rem;
    border: 1px solid var(--cv-border-1);
}

.toggle-info h3 {
    margin: 0 0 0.5rem 0;
    color: #ffffff;
    font-size: 1rem;
}

.toggle-info p {
    margin: 0;
    color: #9ca3af;
    font-size: 0.875rem;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 3.5rem;
    height: 2rem;
    flex-shrink: 0;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: var(--cv-surface-3);
    transition: 0.4s;
    border-radius: 2rem;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 1.5rem;
    width: 1.5rem;
    left: 0.25rem;
    bottom: 0.25rem;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background: #e5e7eb;
}

input:checked + .toggle-slider:before {
    background-color: #09090b;
    transform: translateX(1.5rem);
}

/* Danger Zone */
.danger-info {
    margin-bottom: 1.5rem;
}

.danger-info h3 {
    margin: 0 0 0.5rem 0;
    color: #ffffff;
    font-size: 1rem;
}

.danger-info p {
    margin: 0;
    color: #9ca3af;
    font-size: 0.875rem;
    line-height: 1.5;
}

/* Buttons */
.btn-primary {
    background: #fafafa;
    color: #09090b;
    border: 1px solid transparent;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary:hover:not(:disabled) {
    background: #e5e7eb;
    box-shadow: none;
}

.btn-primary:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.btn-danger {
    background: var(--cv-surface-2);
    color: #e5e7eb;
    border: 1px solid var(--cv-border-1);
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
}

.btn-danger:hover:not(:disabled) {
    background: var(--cv-surface-3);
    border-color: var(--cv-border-2);
}

.btn-secondary {
    background: var(--cv-surface-2);
    color: #e5e7eb;
    border: 1px solid var(--cv-border-1);
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-reset {
    margin-top: 0.5rem;
    width: fit-content;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.btn-secondary:hover {
    background: var(--cv-surface-3);
    border-color: var(--cv-border-2);
}

/* Modal */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: none;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 100;
    padding: 1rem;
}

.modal-content {
    background: var(--cv-bg-top);
    border: 1px solid var(--cv-border-1);
    border-radius: 0.75rem;
    padding: 2rem;
    width: 100%;
    max-width: 28rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #ef4444;
    margin: 0 0 1rem 0;
}

.modal-desc {
    color: #9ca3af;
    margin: 0 0 1.5rem 0;
    line-height: 1.5;
}

.modal-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-top: 2rem;
}
</style>
