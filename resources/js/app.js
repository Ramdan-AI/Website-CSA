import "./bootstrap";

import Lenis from "lenis";
import "lenis/dist/lenis.css";

import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);
window.ScrollTrigger = ScrollTrigger;

const lenis = new Lenis({
    autoRaf: true,
    lerp: 0.1,
    smoothWheel: true,
    wheelMultiplier: 1,
    syncTouch: true,
    syncTouchLerp: 0.08,
    touchMultiplier: 1,
    anchors: true,
    autoResize: true,
    respectReducedMotion: true,
});

// Keep GSAP in sync with Lenis' virtual scroll position.
lenis.on("scroll", ScrollTrigger.update);

const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

// ---------------------------------------------------------------
// Reveal-on-scroll animations (data-reveal + data-reveal-delay).
// Variants:
//   fade      — opacity only
//   up        — fade + rise (default)
//   down      — fade + drop in
//   left      — fade + slide from left
//   right     — fade + slide from right
//   zoom      — fade + scale up (springy pop)
//   blur      — fade + de-blur
//   blurUp    — fade + rise + de-blur (hero heading feel)
//   curtain   — clip-path sweep reveal (big headings)
// ---------------------------------------------------------------
const variantConfig = {
    fade: { opacity: 0 },
    up: { opacity: 0, y: 40 },
    down: { opacity: 0, y: -32 },
    left: { opacity: 0, x: -48 },
    right: { opacity: 0, x: 48 },
    zoom: { opacity: 0, scale: 0.9 },
    blur: { opacity: 0, filter: "blur(8px)" },
    blurUp: { opacity: 0, y: 56, filter: "blur(10px)" },
    curtain: { clipPath: "inset(0 100% 0 0)" },
};

const toConfig = {
    opacity: 1,
    x: 0,
    y: 0,
    scale: 1,
    filter: "blur(0px)",
    clipPath: "inset(0 0% 0 0)",
};

const runReveal = () => {
    if (reducedMotion) return;

    gsap.utils.toArray("[data-reveal]").forEach((el) => {
        const variant = el.dataset.reveal || "up";
        const delay = parseFloat(el.dataset.revealDelay || 0);
        const from = variantConfig[variant] || variantConfig.up;
        const isCurtain = variant === "curtain";

        gsap.fromTo(
            el,
            { ...from, willChange: "transform, opacity, filter, clip-path" },
            {
                ...toConfig,
                duration: isCurtain ? 1.1 : 0.9,
                delay,
                ease: isCurtain ? "power4.inOut" : "power3.out",
                clearProps: "transform,opacity,filter,clip-path",
                scrollTrigger: {
                    trigger: el,
                    start: "top 85%",
                    once: true,
                },
            }
        );
    });

    // Staggered children inside [data-reveal-stagger].
    gsap.utils.toArray("[data-reveal-stagger]").forEach((group) => {
        const children = group.children;
        if (!children.length) return;

        gsap.fromTo(
            children,
            { opacity: 0, y: 36 },
            {
                opacity: 1,
                y: 0,
                duration: 0.7,
                stagger: 0.1,
                ease: "power3.out",
                clearProps: "transform,opacity",
                scrollTrigger: {
                    trigger: group,
                    start: "top 85%",
                    once: true,
                },
            }
        );
    });
};

// Gentle parallax for [data-parallax] elements (e.g. the Tentang image).
const initParallax = () => {
    if (reducedMotion) return;

    gsap.utils.toArray("[data-parallax]").forEach((el) => {
        gsap.fromTo(
            el,
            { y: 0 },
            {
                y: parseFloat(el.dataset.parallax || "-48"),
                ease: "none",
                scrollTrigger: {
                    trigger: el,
                    start: "top bottom",
                    end: "bottom top",
                    scrub: 0.5,
                },
            }
        );
    });
};

// Reading progress hairline: a 2px bar that fills as the visitor scrolls
// through its enclosing section (used on the "Mengapa CSA" bands).
const initSectionProgress = () => {
    if (reducedMotion) return;

    gsap.utils.toArray("[data-section-progress]").forEach((el) => {
        gsap.fromTo(
            el,
            { scaleX: 0 },
            {
                scaleX: 1,
                ease: "none",
                transformOrigin: "left center",
                scrollTrigger: {
                    trigger: el.closest("section"),
                    start: "top 70%",
                    end: "bottom bottom",
                    scrub: 0.6,
                },
            }
        );
    });
};

// Deep scroll-scrubbed parallax (data-parallax-deep): the element travels
// from -v to +v across its section while scaling down to 1 (cinematic pull).
// Optional data-parallax-deep-scale sets the starting scale (default 1.15).
const initDeepParallax = () => {
    if (reducedMotion) return;

    gsap.utils.toArray("[data-parallax-deep]").forEach((el) => {
        const dist = parseFloat(el.dataset.parallaxDeep || 140);
        const scale = parseFloat(el.dataset.parallaxDeepScale || "1.15");

        gsap.fromTo(
            el,
            { y: -dist, scale },
            {
                y: dist,
                scale: 1,
                ease: "none",
                willChange: "transform",
                scrollTrigger: {
                    trigger: el.closest("section") || el,
                    start: "top bottom",
                    end: "bottom top",
                    scrub: 0.8,
                },
            }
        );
    });
};

// Drag-to-scroll + arrow navigation for horizontal card sliders ([data-slider]).
const initSliders = () => {
    gsap.utils.toArray("[data-slider]").forEach((slider) => {
        const track = slider.querySelector("[data-slider-track]");
        if (!track) return;

        let down = false;
        let startX = 0;
        let startScroll = 0;
        let moved = false;

        track.addEventListener("pointerdown", (e) => {
            if (e.pointerType === "mouse" && e.button !== 0) return;
            down = true;
            moved = false;
            startX = e.clientX;
            startScroll = track.scrollLeft;
            track.classList.add("is-dragging");
        });

        track.addEventListener("pointermove", (e) => {
            if (!down) return;
            const delta = e.clientX - startX;
            if (Math.abs(delta) > 4) moved = true;
            track.scrollLeft = startScroll - delta;
        });

        const end = () => {
            down = false;
            track.classList.remove("is-dragging");
        };

        track.addEventListener("pointerup", end);
        track.addEventListener("pointerleave", end);
        track.addEventListener("pointercancel", end);
        track.addEventListener("click", (e) => {
            if (moved) {
                e.preventDefault();
                e.stopPropagation();
            }
        }, true);

        // Arrow buttons: scroll by one card width, smoothly.
        const scrollByCard = (direction) => {
            const firstCard = track.querySelector("article, a, div");
            const step = (firstCard?.getBoundingClientRect().width ?? 240) + 16;
            track.scrollBy({ left: direction * step, behavior: "smooth" });
        };

        const container = slider.closest("div")?.parentElement ?? slider.parentElement;
        container?.querySelectorAll("[data-slider-prev]").forEach((btn) =>
            btn.addEventListener("click", () => scrollByCard(-1))
        );
        container?.querySelectorAll("[data-slider-next]").forEach((btn) =>
            btn.addEventListener("click", () => scrollByCard(1))
        );
    });
};

// Continuous marquee: photos slide horizontally in a seamless loop.
// Duplicated content in the markup lets it wrap without a visible jump.
const initMarquee = () => {
    gsap.utils.toArray("[data-marquee]").forEach((marquee) => {
        const track = marquee.querySelector("[data-marquee-track]");
        if (!track) return;

        const half = track.scrollWidth / 2;
        if (!half || reducedMotion) return;

        const tween = gsap.fromTo(
            track,
            { x: 0 },
            {
                x: -half,
                duration: half / 60,
                ease: "none",
                repeat: -1,
            }
        );

        // Only the marquee slows down while the pointer is over it.
        marquee.addEventListener("pointerenter", () => gsap.to(tween, { timeScale: 0.2, duration: 0.3 }));
        marquee.addEventListener("pointerleave", () => gsap.to(tween, { timeScale: 1, duration: 0.3 }));
    });
};

// Start animations as soon as the DOM is ready (avoids waiting for all
// hero images to finish loading, which made the landing feel slow).
const init = () => {
    runReveal();
    initParallax();
    initSectionProgress();
    initDeepParallax();
    initSliders();
    initMarquee();
};

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
} else {
    init();
}

// Re-measure trigger positions once every asset (image/font) has loaded.
window.addEventListener("load", () => {
    ScrollTrigger.refresh();
});
