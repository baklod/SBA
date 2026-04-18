<template>
    <Head title="Edit Player - Admin | SBA" />
    <AdminLayout>
        <div class="edit-page">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1 class="page-title">Edit Player</h1>
                    <p class="page-subtitle">Update player information</p>
                </div>
                <Link
                    :href="route('admin.players.index')"
                    class="btn btn-secondary"
                >
                    ← Back to Players
                </Link>
            </div>

            <!-- Form -->
            <div class="glass-card">
                <form @submit.prevent="submit" class="form">
                    <div class="form-group">
                        <label for="division_id" class="form-label"
                            >Division (Filter)</label
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
                            placeholder="Enter player name"
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
                            <div v-if="currentPhoto" class="photo-preview">
                                <img
                                    :src="currentPhoto"
                                    alt="Player photo"
                                    class="preview-image"
                                />
                                <button
                                    type="button"
                                    @click="removePhoto"
                                    class="remove-photo-btn"
                                >
                                    Remove
                                </button>
                            </div>
                            <div v-else class="upload-placeholder">
                                <input
                                    type="file"
                                    id="photo"
                                    @change="handlePhotoChange"
                                    accept="image/jpeg,image/png,image/jpg,image/gif"
                                    class="photo-input"
                                />
                                <label for="photo" class="upload-label">
                                    <span class="upload-icon">+</span>
                                    <span>Click to upload photo</span>
                                    <span class="upload-hint"
                                        >JPG, PNG, GIF up to 5MB</span
                                    >
                                </label>
                            </div>
                        </div>
                        <span v-if="form.errors.photo" class="form-error">{{
                            form.errors.photo
                        }}</span>
                    </div>

                    <div class="form-actions">
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="form.processing"
                        >
                            {{
                                form.processing
                                    ? "Updating..."
                                    : "Update Player"
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    player: {
        type: Object,
        required: true,
    },
    teams: {
        type: Array,
        default: () => [],
    },
    divisions: {
        type: Array,
        default: () => [],
    },
});

// Initialize selectedDivision based on current player's team division
const selectedDivision = ref(props.player.team?.division_id || "");

const filteredTeams = computed(() => {
    if (!selectedDivision.value) {
        return props.teams;
    }
    return props.teams.filter(
        (team) => team.division_id === selectedDivision.value,
    );
});

const onDivisionChange = () => {
    // Only reset if current team is not in the filtered list
    const teamInFiltered = filteredTeams.value.some(
        (t) => t.id === form.team_id,
    );
    if (!teamInFiltered) {
        form.team_id = "";
    }
};

const form = useForm({
    team_id: props.player.team_id,
    name: props.player.name,
    jersey_number: props.player.jersey_number || "",
    position: props.player.position || "",
    photo: null,
    remove_photo: false,
});

const photoPreview = ref(null);

const resolvePlayerPhotoUrl = (photoPath) => {
    if (!photoPath || typeof photoPath !== "string") return null;
    if (photoPath.startsWith("http://") || photoPath.startsWith("https://")) {
        return photoPath;
    }
    if (photoPath.startsWith("/")) return photoPath;
    return `/storage/${photoPath}`;
};

const currentPhoto = computed(() => {
    if (form.remove_photo) return null;
    if (photoPreview.value) return photoPreview.value;
    if (props.player.photo) return resolvePlayerPhotoUrl(props.player.photo);
    return null;
});

const handlePhotoChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.photo = file;
        form.remove_photo = false;
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
    form.remove_photo = true;
    const fileInput = document.getElementById("photo");
    if (fileInput) fileInput.value = "";
};

const submit = () => {
    router.post(
        route("admin.players.update", props.player.id),
        {
            _method: "put",
            team_id: form.team_id,
            name: form.name,
            jersey_number: form.jersey_number,
            position: form.position,
            photo: form.photo,
            remove_photo: form.remove_photo,
        },
        {
            forceFormData: true,
            onStart: () => (form.processing = true),
            onFinish: () => (form.processing = false),
            onError: (errors) => (form.errors = errors),
        },
    );
};
</script>

<style scoped>
.edit-page {
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
    background: linear-gradient(
        135deg,
        var(--cv-accent-1) 0%,
        var(--cv-accent-2) 100%
    );
    color: #ffffff;
}

.btn-primary:hover {
    box-shadow: 0 10px 25px rgba(var(--cv-accent-1-rgb), 0.25);
}

.btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-secondary {
    background: rgba(51, 65, 85, 0.5);
    color: #d1d5db;
    border: 1px solid rgba(71, 85, 105, 0.5);
}

.btn-secondary:hover {
    background: rgba(51, 65, 85, 0.7);
    color: #ffffff;
}

.glass-card {
    background: rgba(30, 41, 59, 0.5);
    backdrop-filter: blur(4px);
    border-radius: 0.75rem;
    padding: 2rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
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
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #d1d5db;
}

.form-input,
.form-select {
    padding: 0.75rem 1rem;
    background: rgba(51, 65, 85, 0.5);
    border: 1px solid rgba(71, 85, 105, 0.5);
    border-radius: 0.5rem;
    color: #ffffff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: var(--cv-accent-1);
    box-shadow: 0 0 0 3px rgba(var(--cv-accent-1-rgb), 0.1);
}

.form-input::placeholder {
    color: #6b7280;
}

.form-select option {
    background: #1e293b;
    color: #ffffff;
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

.photo-upload-area {
    border: 2px dashed rgba(71, 85, 105, 0.5);
    border-radius: 0.5rem;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
}

.photo-upload-area:hover {
    border-color: var(--cv-accent-1);
}

.upload-placeholder {
    position: relative;
}

.photo-input {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    opacity: 0;
    cursor: pointer;
}

.upload-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    color: #9ca3af;
}

.upload-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(var(--cv-accent-1-rgb), 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--cv-accent-1);
}

.upload-hint {
    font-size: 0.75rem;
    color: #6b7280;
}

.photo-preview {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.preview-image {
    width: 128px;
    height: 128px;
    object-fit: cover;
    border-radius: 0.5rem;
    border: 2px solid rgba(var(--cv-accent-1-rgb), 0.3);
}

.remove-photo-btn {
    background: rgba(239, 68, 68, 0.2);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.3);
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    cursor: pointer;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.remove-photo-btn:hover {
    background: rgba(239, 68, 68, 0.3);
}
</style>
