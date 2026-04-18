<template>
    <Teleport to="body">
        <transition name="confirm-modal">
            <div
                v-if="show"
                class="confirm-overlay"
                @click.self="$emit('cancel')"
            >
                <div class="confirm-box">
                    <div class="confirm-icon">⚠️</div>
                    <h3 class="confirm-title">{{ title }}</h3>
                    <p class="confirm-message">{{ message }}</p>
                    <div class="confirm-actions">
                        <button
                            class="confirm-btn confirm-btn-cancel"
                            @click="$emit('cancel')"
                        >
                            Cancel
                        </button>
                        <button
                            class="confirm-btn confirm-btn-danger"
                            @click="$emit('confirm')"
                        >
                            {{ confirmText }}
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </Teleport>
</template>

<script setup>
defineProps({
    show: { type: Boolean, default: false },
    title: { type: String, default: "Are you sure?" },
    message: { type: String, default: "This action cannot be undone." },
    confirmText: { type: String, default: "Delete" },
});

defineEmits(["confirm", "cancel"]);
</script>

<style scoped>
.confirm-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    backdrop-filter: blur(2px);
}

.confirm-box {
    background: #1e293b;
    border-radius: 0.75rem;
    padding: 2rem;
    width: 90%;
    max-width: 400px;
    border: 1px solid rgba(239, 68, 68, 0.3);
    text-align: center;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
}

.confirm-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.confirm-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 0.5rem 0;
}

.confirm-message {
    font-size: 0.875rem;
    color: #9ca3af;
    margin: 0 0 1.5rem 0;
    line-height: 1.5;
}

.confirm-actions {
    display: flex;
    justify-content: center;
    gap: 0.75rem;
}

.confirm-btn {
    padding: 0.625rem 1.5rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.2s ease;
}

.confirm-btn-cancel {
    background: rgba(51, 65, 85, 0.8);
    color: #d1d5db;
    border: 1px solid rgba(71, 85, 105, 0.5);
}

.confirm-btn-cancel:hover {
    background: rgba(71, 85, 105, 0.8);
    color: #ffffff;
}

.confirm-btn-danger {
    background: rgba(239, 68, 68, 0.8);
    color: #ffffff;
}

.confirm-btn-danger:hover {
    background: rgba(239, 68, 68, 1);
}

/* Transition */
.confirm-modal-enter-active,
.confirm-modal-leave-active {
    transition: opacity 0.2s ease;
}

.confirm-modal-enter-from,
.confirm-modal-leave-to {
    opacity: 0;
}

.confirm-modal-enter-active .confirm-box,
.confirm-modal-leave-active .confirm-box {
    transition: transform 0.2s ease;
}

.confirm-modal-enter-from .confirm-box {
    transform: scale(0.95);
}

.confirm-modal-leave-to .confirm-box {
    transform: scale(0.95);
}
</style>
