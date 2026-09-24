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

// Seamless marquee. Each strip duplicates its content so the loop wraps
// without a visible jump. data-marquee-speed sets direction and pace.
const initMarquee = () => {
    gsap.utils.toArray("[data-marquee]").forEach((marquee) => {
        const track = marquee.querySelector("[data-marquee-track]");
        if (!track) return;

        const half = track.scrollWidth / 2;
        if (!half || reducedMotion) return;

        const speed = parseFloat(marquee.dataset.marqueeSpeed || "1");
        const pace = Math.abs(speed);
        const duration = half / (26 * pace);

        const tween = gsap.fromTo(
            track,
            { x: speed >= 0 ? 0 : -half },
            {
                x: speed >= 0 ? -half : 0,
                duration,
                ease: "none",
                repeat: -1,
            }
        );

        marquee._marqueeTween = tween;

        // Melambat saat kursor di atas strip supaya bisa dilihat.
        marquee.addEventListener("pointerenter", () => gsap.to(tween, { timeScale: 0.2, duration: 0.4 }));
        marquee.addEventListener("pointerleave", () => gsap.to(tween, { timeScale: 1, duration: 0.4 }));
    });
};

// Kalender tahunan: tahun muncul berurutan, lalu tahun berdirinya disorot.
const initCalendar = () => {
    const calendar = document.querySelector("[data-calendar]");
    if (!calendar) return;

    const cells = gsap.utils.toArray("[data-cal-year-cell]", calendar);
    if (!cells.length) return;

    const highlight = cells.find((el) => el.dataset.year === "2017");

    if (reducedMotion) {
        if (highlight) {
            highlight.style.background = "var(--accent-solid)";
            highlight.style.color = "var(--on-accent)";
        }
        return;
    }

    const tl = gsap.timeline({
        scrollTrigger: { trigger: calendar, start: "top 85%", once: true },
    });

    tl.fromTo(
        cells,
        { opacity: 0, y: 10 },
        { opacity: 1, y: 0, duration: 0.4, stagger: 0.07, ease: "power2.out" }
    );

    if (highlight) {
        tl.to(
            highlight,
            {
                backgroundColor: "var(--accent-solid)",
                color: "var(--on-accent)",
                scale: 1.12,
                duration: 0.5,
                ease: "back.out(1.8)",
            },
            "+=0.05"
        ).to(highlight, { scale: 1, duration: 0.3, ease: "power2.out" });
    }
};

// Garis timeline yang tumbuh mengikuti scroll.
const initTimeline = () => {
    const timeline = document.querySelector("[data-timeline]");
    if (!timeline || reducedMotion) return;

    const fill = timeline.querySelector("[data-timeline-fill]");
    if (!fill) return;

    gsap.to(fill, {
        scaleY: 1,
        ease: "none",
        scrollTrigger: {
            trigger: timeline,
            start: "top 75%",
            end: "bottom 60%",
            scrub: 0.4,
        },
    });
};

// Strip foto hero: kecepatan gerak ikut menyesuaikan arah dan laju scroll.
// Saat pengunjung menggulir, marquee berakselerasi atau mundur sementara.
const initStripVelocity = () => {
    const strip = document.querySelector("[data-photo-strip]");
    if (!strip || reducedMotion) return;

    const marquees = strip.querySelectorAll("[data-marquee]");
    if (!marquees.length) return;

    ScrollTrigger.create({
        trigger: strip,
        start: "top bottom",
        end: "bottom top",
        onUpdate: (self) => {
            const velocity = gsap.utils.clamp(-2400, 2400, self.getVelocity());
            // 1 = kecepatan dasar, memanjang sesuai arah gulir.
            const boost = gsap.utils.clamp(0.35, 3.2, 1 + Math.abs(velocity) / 1400);
            const forward = velocity >= 0;

            marquees.forEach((marquee) => {
                const tween = marquee._marqueeTween;
                if (!tween) return;
                const base = parseFloat(marquee.dataset.marqueeSpeed || "1") >= 0 ? 1 : -1;
                tween.timeScale(forward ? boost : -boost * 0.6);
                marquee.dataset.baseDirection = base;
            });
        },
    });

    // Kembali ke kecepatan normal saat gulir berhenti.
    ScrollTrigger.addEventListener("scrollEnd", () => {
        marquees.forEach((marquee) => {
            const tween = marquee._marqueeTween;
            if (tween) gsap.to(tween, { timeScale: 1, duration: 0.6, overwrite: true });
        });
    });
};

// Daftar bernomor: nomor besar bergerak saat barisnya masuk viewport.
const initNumberedList = () => {
    if (reducedMotion) return;

    gsap.utils.toArray("[data-numbered-item]").forEach((item) => {
        const number = item.querySelector("[data-item-number]");
        const content = item.querySelector("[data-item-content]");
        if (!number || !content) return;

        gsap.fromTo(
            number,
            { x: -40, opacity: 0 },
            {
                x: 0,
                opacity: 1,
                duration: 0.9,
                ease: "power3.out",
                scrollTrigger: { trigger: item, start: "top 82%", once: true },
            }
        );

        gsap.fromTo(
            content,
            { x: 40, opacity: 0 },
            {
                x: 0,
                opacity: 1,
                duration: 0.9,
                delay: 0.08,
                ease: "power3.out",
                scrollTrigger: { trigger: item, start: "top 82%", once: true },
            }
        );
    });
};

// Judul hero muncul baris demi baris lewat mask, bukan fade.
const initLineReveal = () => {
    const title = document.querySelector("[data-hero-title]");
    if (!title) return;

    const lines = title.querySelectorAll(".line-mask");

    if (reducedMotion) {
        lines.forEach((line) => line.classList.add("is-in"));
        return;
    }

    lines.forEach((line, index) => {
        gsap.delayedCall(0.1 + index * 0.1, () => line.classList.add("is-in"));
    });
};

// Start animations as soon as the DOM is ready (avoids waiting for all
// hero images to finish loading, which made the landing feel slow).
const init = () => {
    document.documentElement.classList.add("js-ready");
    initLineReveal();
    runReveal();
    initParallax();
    initSectionProgress();
    initDeepParallax();
    initSliders();
    initMarquee();
    initStripVelocity();
    initCalendar();
    initTimeline();
    initNumberedList();
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
