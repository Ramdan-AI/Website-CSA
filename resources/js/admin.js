
import Lenis from "lenis";
import "lenis/dist/lenis.css";

const lenis = new Lenis({
    autoRaf: true,
    lerp: 0.18,
    smoothWheel: true,
    wheelMultiplier: 1,
    syncTouch: false,
    anchors: false,
    autoResize: true,
    respectReducedMotion: true,
});