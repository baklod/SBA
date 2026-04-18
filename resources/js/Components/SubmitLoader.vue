<template>
    <Teleport to="body">
        <div
            class="fixed inset-0 z-[200] flex items-center justify-center bg-black/60 backdrop-blur-sm"
            v-show="show"
            aria-live="polite"
            :aria-busy="show ? 'true' : 'false'"
        >
            <div class="w-48 h-48">
                <div ref="container" class="w-full h-full"></div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from "vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    path: {
        type: String,
        default: "/animations/Ripple%20loading%20animation.json",
    },
});

const container = ref(null);
let animation;
let pendingPlay = false;

const play = () => {
    if (animation?.goToAndPlay) {
        animation.goToAndPlay(0, true);
        return;
    }

    if (animation?.play) {
        animation.play();
        return;
    }

    pendingPlay = true;
};

const stop = () => {
    if (animation?.stop) {
        animation.stop();
    }
    pendingPlay = false;
};

watch(
    () => props.show,
    (value) => {
        if (value) play();
        else stop();
    },
    { immediate: true },
);

onMounted(() => {
    if (!container.value) return;

    import("lottie-web")
        .then((mod) => {
            const lottie = mod.default;
            animation = lottie.loadAnimation({
                container: container.value,
                renderer: "svg",
                loop: true,
                autoplay: false,
                path: props.path,
            });

            if (props.show || pendingPlay) {
                play();
                pendingPlay = false;
            }
        })
        .catch(() => {
            // Loader is non-critical; fail silently.
        });
});

onBeforeUnmount(() => {
    animation?.destroy?.();
    animation = undefined;
});
</script>
