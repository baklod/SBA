<template>
    <Head title="Player Registration | SBA" />
    <PublicLayout>
        <div class="create-page sm:container sm:mx-auto px-4 py-6 sm:py-8">
            <Link :href="route('home')" class="back-link">
                ← Back to Home
            </Link>

            <!-- Header -->
            <div class="header mb-8">
                <div class="header-left">
                    <h1 class="page-title">Register as a Player</h1>
                    <p class="page-subtitle">
                        Join a team for the upcoming season
                    </p>
                </div>
            </div>

            <!-- Form -->
            <div class="glass-card mx-auto">
                <form @submit.prevent="submit" class="form">
                    <div class="form-group">
                        <label for="division_id" class="form-label"
                            >Division (Filter) *</label
                        >
                        <select
                            id="division_id"
                            v-model="selectedDivision"
                            class="form-select"
                            @change="onDivisionChange"
                        >
                            <option value="">All Divisions</option>
                            <option
                                v-for="division in divisions"
                                :key="division.id"
                                :value="division.id"
                            >
                                {{ division.name }}
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="team_id" class="form-label">Team *</label>
                        <select
                            id="team_id"
                            v-model="form.team_id"
                            class="form-select"
                            required
                        >
                            <option value="">Select a team</option>
                            <option
                                v-for="team in filteredTeams"
                                :key="team.id"
                                :value="team.id"
                            >
                                {{ team.name }}
                                <template v-if="team.division">
                                    ({{ team.division.name }})</template
                                >
                            </option>
                        </select>
                        <span v-if="form.errors.team_id" class="form-error">{{
                            form.errors.team_id
                        }}</span>
                    </div>

                    <div class="form-group">
                        <label for="name" class="form-label"
                            >Player Name *</label
                        >
                        <input
                            type="text"
                            id="name"
                            v-model="form.name"
                            class="form-input"
                            placeholder="Enter your full name"
                            required
                        />
                        <span v-if="form.errors.name" class="form-error">{{
                            form.errors.name
                        }}</span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="jersey_number" class="form-label"
                                >Jersey Number</label
                            >
                            <input
                                type="text"
                                id="jersey_number"
                                v-model="form.jersey_number"
                                class="form-input"
                                placeholder="e.g., 23"
                            />
                            <span
                                v-if="form.errors.jersey_number"
                                class="form-error"
                                >{{ form.errors.jersey_number }}</span
                            >
                        </div>

                        <div class="form-group">
                            <label for="position" class="form-label"
                                >Position</label
                            >
                            <select
                                id="position"
                                v-model="form.position"
                                class="form-select"
                            >
                                <option value="">Select position</option>
                                <option value="PG">PG - Point Guard</option>
                                <option value="SG">SG - Shooting Guard</option>
                                <option value="SF">SF - Small Forward</option>
                                <option value="PF">PF - Power Forward</option>
                                <option value="C">C - Center</option>
                            </select>
                            <span
                                v-if="form.errors.position"
                                class="form-error"
                                >{{ form.errors.position }}</span
                            >
                        </div>
                    </div>

                    <!-- Photo Upload -->
                    <div class="form-group">
                        <label class="form-label"
                            >Player Photo (Optional)</label
                        >
                        <div class="photo-upload-area">
                            <div v-if="photoPreview" class="photo-preview">
                                <img
                                    :src="photoPreview"
                                    alt="Preview"
                                    class="preview-image"
                                />
                                <button
                                    type="button"
                                    @click="removePhoto"
                                    class="remove-photo-btn"
                                >
                                    <svg
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        ></path>
                                    </svg>
                                </button>
                            </div>
                            <label v-else class="upload-label">
                                <input
                                    type="file"
                                    @change="handlePhotoChange"
                                    accept="image/*"
                                    class="hidden-input"
                                />
                                <div class="upload-placeholder">
                                    <svg
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        ></path>
                                    </svg>
                                    <span>Click to upload photo</span>
                                    <span class="upload-hint"
                                        >PNG, JPG up to 5MB</span
                                    >
                                </div>
                            </label>
                        </div>
                        <span v-if="form.errors.photo" class="form-error">{{
                            form.errors.photo
                        }}</span>
                    </div>

                    <div class="form-actions">
                        <button
                            type="submit"
                            class="btn btn-primary w-full sm:w-auto"
                            :disabled="form.processing || showLoader"
                        >
                            {{
                                form.processing || showLoader
                                    ? "Registering..."
                                    : "Register Now"
                            }}
                        </button>
                    </div>
                </form>
            </div>

            <SubmitLoader :show="showLoader" />
        </div>
    </PublicLayout>
</template>

<script setup>
import { computed, nextTick, ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import SubmitLoader from "@/Components/SubmitLoader.vue";

const props = defineProps({
    teams: {
        type: Array,
        default: () => [],
    },
    divisions: {
        type: Array,
        default: () => [],
    },
});

const selectedDivision = ref("");

const filteredTeams = computed(() => {
    if (!selectedDivision.value) {
        return props.teams;
    }
    return props.teams.filter(
        (team) => team.division_id === selectedDivision.value,
    );
});

const onDivisionChange = () => {
    form.team_id = "";
};

const form = useForm({
    team_id: "",
    name: "",
    jersey_number: "",
    position: "",
    photo: null,
});

const photoPreview = ref(null);
const showLoader = ref(false);

const handlePhotoChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.photo = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            photoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const removePhoto = () => {
    form.photo = null;
    photoPreview.value = null;
};

const submit = async () => {
    if (showLoader.value || form.processing) return;

    showLoader.value = true;
    await nextTick();
    await new Promise((resolve) => setTimeout(resolve, 1500));

    form.post(route("register"), {
        onSuccess: () => {
            form.reset();
            removePhoto();
        },
        onFinish: () => {
            showLoader.value = false;
        },
    });
};
</script>

<style scoped>
.create-page {
    display: flex;
    flex-direction: column;
    gap: 2rem;
    color: var(--cv-text-1);
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
    color: var(--cv-text-1);
    margin: 0;
}

.page-subtitle {
    color: var(--cv-muted);
    margin: 0.25rem 0 0 0;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    width: fit-content;
    color: var(--cv-muted);
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
}

.back-link:hover {
    color: var(--cv-text-1);
    text-decoration: underline;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
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
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #ffffff;
}

.btn-primary:hover {
    box-shadow: 0 10px 25px rgba(245, 158, 11, 0.25);
}

.btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-secondary {
    background: var(--cv-surface-3);
    color: var(--cv-text-2);
    border: 1px solid var(--cv-border-1);
}

.btn-secondary:hover {
    background: var(--cv-surface-2);
    color: var(--cv-text-1);
}

.glass-card {
    background: var(--cv-surface-1);
    backdrop-filter: blur(4px);
    border-radius: 0.75rem;
    padding: 2rem;
    border: 1px solid var(--cv-border-1);
    width: 100%;
    max-width: 600px;
}

.form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

@media (max-width: 640px) {
    .form-row {
        grid-template-columns: 1fr;
    }

    .create-page {
        gap: 1.5rem;
    }

    .header {
        flex-direction: column;
        align-items: stretch;
        margin-bottom: 1.5rem;
    }

    .page-title {
        font-size: 1.5rem;
    }

    .glass-card {
        background: transparent;
        backdrop-filter: none;
        border: none;
        border-radius: 0;
        padding: 0;
        max-width: 100%;
    }

    .form {
        gap: 1.25rem;
    }

    .upload-placeholder {
        padding: 1.5rem;
    }
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--cv-text-2);
}

.form-input,
.form-select {
    padding: 0.75rem 1rem;
    background: var(--cv-surface-3);
    border: 1px solid var(--cv-border-1);
    border-radius: 0.5rem;
    color: var(--cv-text-1);
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: rgba(251, 191, 36, 0.55);
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.12);
}

.form-input::placeholder {
    color: var(--cv-muted-2);
}

.form-select option {
    background: var(--cv-bg-top);
    color: var(--cv-text-1);
}

.form-error {
    font-size: 0.75rem;
    color: #f87171;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 1rem;
}

/* Photo Upload Styles */
.photo-upload-area {
    width: 100%;
}

.photo-preview {
    position: relative;
    width: 150px;
    height: 150px;
}

.preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 0.75rem;
    border: 2px solid rgba(251, 191, 36, 0.45);
}

.remove-photo-btn {
    position: absolute;
    top: -0.5rem;
    right: -0.5rem;
    width: 1.75rem;
    height: 1.75rem;
    background: #ef4444;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.remove-photo-btn:hover {
    background: #dc2626;
    transform: scale(1.1);
}

.remove-photo-btn svg {
    width: 1rem;
    height: 1rem;
    color: #ffffff;
}

.upload-label {
    display: block;
    cursor: pointer;
}

.hidden-input {
    display: none;
}

.upload-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 2rem;
    background: var(--cv-surface-3);
    border: 2px dashed var(--cv-border-1);
    border-radius: 0.75rem;
    transition: all 0.3s ease;
}

.upload-placeholder:hover {
    border-color: rgba(251, 191, 36, 0.55);
    background: rgba(245, 158, 11, 0.08);
}

.upload-placeholder svg {
    width: 2.5rem;
    height: 2.5rem;
    color: var(--cv-muted-2);
}

.upload-placeholder span {
    font-size: 0.875rem;
    color: var(--cv-muted);
}

.upload-hint {
    font-size: 0.75rem;
    color: var(--cv-muted-2);
}
</style>
