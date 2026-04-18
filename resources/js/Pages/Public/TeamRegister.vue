<template>
    <Head title="Team Registration | SBA" />
    <PublicLayout>
        <div class="create-page sm:container sm:mx-auto px-4 py-6 sm:py-8">
            <Link :href="route('home')" class="back-link">
                ← Back to Home
            </Link>

            <!-- Header -->
            <div class="header mb-8">
                <div class="header-left">
                    <h1 class="page-title">Register a Team</h1>
                    <p class="page-subtitle">
                        Submit your team for the upcoming season
                    </p>
                </div>
            </div>

            <!-- Form -->
            <div class="glass-card mx-auto">
                <form @submit.prevent="submit" class="form">
                    <div class="form-group">
                        <label for="division_id" class="form-label"
                            >Division (Optional)</label
                        >
                        <select
                            id="division_id"
                            v-model="form.division_id"
                            class="form-select"
                        >
                            <option value="">Select a division</option>
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
                                    alt="Preview"
                                    class="preview-image"
                                />
                                <button
                                    type="button"
                                    @click="removeLogo"
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
                                    @change="handleLogoChange"
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
                                    <span>Click to upload logo</span>
                                    <span class="upload-hint"
                                        >PNG, JPG up to 5MB</span
                                    >
                                </div>
                            </label>
                        </div>
                        <span v-if="form.errors.logo" class="form-error">{{
                            form.errors.logo
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
                                    : "Register Team"
                            }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Request Submitted Modal -->
            <Teleport to="body">
                <transition name="fade">
                    <div
                        v-if="showSubmittedModal"
                        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm"
                        @click.self="showSubmittedModal = false"
                    >
                        <div
                            class="bg-gray-800 border border-gray-700 rounded-xl shadow-2xl p-6 max-w-md w-full mx-4 transform transition-all"
                        >
                            <div
                                class="flex items-center justify-center w-12 h-12 mx-auto bg-cyan-500/20 rounded-full mb-4"
                            >
                                <svg
                                    class="w-6 h-6 text-cyan-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"
                                    ></path>
                                </svg>
                            </div>
                            <h3
                                class="text-xl font-bold text-white text-center mb-2"
                            >
                                Team Request Submitted
                            </h3>
                            <p class="text-gray-300 text-center mb-6">
                                Your team registration was submitted for review.
                                An admin will review and approve it first before
                                it is listed on the public site.
                            </p>
                            <div class="flex justify-center">
                                <button
                                    @click="showSubmittedModal = false"
                                    class="px-6 py-2.5 bg-gray-700 text-white font-medium rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-colors w-full sm:w-auto"
                                >
                                    Got it
                                </button>
                            </div>
                        </div>
                    </div>
                </transition>
            </Teleport>

            <SubmitLoader :show="showLoader" />
        </div>
    </PublicLayout>
</template>

<script setup>
import { nextTick, ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import SubmitLoader from "@/Components/SubmitLoader.vue";

const props = defineProps({
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
const showSubmittedModal = ref(false);
const showLoader = ref(false);

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
};

const submit = async () => {
    if (showLoader.value || form.processing) return;

    showLoader.value = true;
    await nextTick();
    await new Promise((resolve) => setTimeout(resolve, 1500));

    form.post(route("team-register"), {
        onSuccess: () => {
            form.reset();
            removeLogo();
            showSubmittedModal.value = true;
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

/* Logo Upload Styles */
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

@media (max-width: 640px) {
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
</style>
