<template>
    <Head title="Add Team - Admin | SBA" />
    <AdminLayout>
        <div class="create-page">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1 class="page-title">Create New Team</h1>
                    <p class="page-subtitle">Add a new team to the league</p>
                </div>
                <Link
                    :href="route('admin.teams.index')"
                    class="btn btn-secondary"
                >
                    ← Back to Teams
                </Link>
            </div>

            <!-- Form -->
            <div class="glass-card">
                <form @submit.prevent="submit" class="form">
                    <div class="form-group">
                        <label for="division_id" class="form-label"
                            >Division</label
                        >
                        <select
                            id="division_id"
                            v-model="form.division_id"
                            class="form-select"
                        >
                            <option value="">No Division</option>
                            <option
                                v-for="division in divisions"
                                :key="division.id"
                                :value="division.id"
                            >
                                {{ division.name }}
                            </option>
                        </select>
                        <span
                            v-if="form.errors.division_id"
                            class="form-error"
                            >{{ form.errors.division_id }}</span
                        >
                    </div>

                    <div class="form-group">
                        <label for="name" class="form-label">Team Name *</label>
                        <input
                            type="text"
                            id="name"
                            v-model="form.name"
                            class="form-input"
                            placeholder="Enter team name"
                            required
                        />
                        <span v-if="form.errors.name" class="form-error">{{
                            form.errors.name
                        }}</span>
                    </div>

                    <!-- Logo Upload -->
                    <div class="form-group">
                        <label class="form-label">Team Logo (Optional)</label>
                        <div class="photo-upload-area">
                            <div v-if="logoPreview" class="photo-preview">
                                <img
                                    :src="logoPreview"
                                    alt="Team logo"
                                    class="preview-image"
                                />
                                <button
                                    type="button"
                                    @click="removeLogo"
                                    class="remove-photo-btn"
                                >
                                    Remove
                                </button>
                            </div>
                            <div v-else class="upload-placeholder">
                                <input
                                    type="file"
                                    id="logo"
                                    @change="handleLogoChange"
                                    accept="image/jpeg,image/png,image/jpg,image/gif"
                                    class="photo-input"
                                />
                                <label for="logo" class="upload-label">
                                    <span class="upload-icon">+</span>
                                    <span>Click to upload logo</span>
                                    <span class="upload-hint"
                                        >JPG, PNG, GIF up to 5MB</span
                                    >
                                </label>
                            </div>
                        </div>
                        <span v-if="form.errors.logo" class="form-error">{{
                            form.errors.logo
                        }}</span>
                    </div>

                    <div class="form-actions">
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="form.processing"
                        >
                            {{
                                form.processing ? "Creating..." : "Create Team"
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineProps({
    divisions: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    name: "",
    division_id: "",
    logo: null,
});

const logoPreview = ref(null);

const handleLogoChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.logo = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            logoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const removeLogo = () => {
    form.logo = null;
    logoPreview.value = null;
    const fileInput = document.getElementById("logo");
    if (fileInput) fileInput.value = "";
};

const submit = () => {
    form.post(route("admin.teams.store"));
};
</script>

<style scoped>
.create-page {
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

.form-input {
    padding: 0.75rem 1rem;
    background: rgba(51, 65, 85, 0.5);
    border: 1px solid rgba(71, 85, 105, 0.5);
    border-radius: 0.5rem;
    color: #ffffff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: var(--cv-accent-1);
    box-shadow: 0 0 0 3px rgba(var(--cv-accent-1-rgb), 0.1);
}

.form-input::placeholder {
    color: #6b7280;
}

.form-select {
    padding: 0.75rem 1rem;
    background: rgba(51, 65, 85, 0.5);
    border: 1px solid rgba(71, 85, 105, 0.5);
    border-radius: 0.5rem;
    color: #ffffff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-select:focus {
    outline: none;
    border-color: var(--cv-accent-1);
    box-shadow: 0 0 0 3px rgba(var(--cv-accent-1-rgb), 0.1);
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
