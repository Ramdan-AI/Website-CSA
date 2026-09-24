
import {
    ArrowLeft,
    ArrowRight,
    AtSign,
    Bell,
    BellOff,
    Briefcase,
    Building2,
    CalendarDays,
    CalendarCheck,
    CalendarX,
    Camera,
    ChartLine,
    Check,
    ChevronDown,
    ChevronLeft,
    ChevronRight,
    CircleCheck,
    CircleX,
    ClipboardCheck,
    ClipboardList,
    Clock,
    Code,
    Download,
    ExternalLink,
    Eye,
    FileSignature,
    FileText,
    FolderOpen,
    GraduationCap,
    Image,
    Images,
    Info,
    KeyRound,
    LayoutDashboard,
    Link,
    Globe,
    List,
    LogOut,
    Lock,
    Mail,
    MapPin,
    Megaphone,
    Menu,
    Minus,
    Moon,
    Newspaper,
    NotebookPen,
    PanelLeft,
    Pen,
    Phone,
    Plus,
    QrCode,
    Rocket,
    Save,
    Search,
    ScrollText,
    Settings,
    Shield,
    Star,
    Key,
    Sun,
    Tags,
    Thermometer,
    TrendingDown,
    TrendingUp,
    TriangleAlert,
    UserCheck,
    UserMinus,
    UserPlus,
    UserRound,
    Upload,
    Users,
    Trash2,
} from "lucide";
import Chart from "chart.js/auto";

const icons = {
    ArrowLeft,
    ArrowRight,
    AtSign,
    Bell,
    BellOff,
    Briefcase,
    Building2,
    CalendarDays,
    CalendarCheck,
    Camera,
    CalendarX,
    ChartLine,
    Check,
    ChevronDown,
    ChevronLeft,
    ChevronRight,
    CircleCheck,
    CircleX,
    ClipboardCheck,
    ClipboardList,
    Clock,
    Code,
    Download,
    ExternalLink,
    Eye,
    FileSignature,
    FileText,
    FolderOpen,
    GraduationCap,
    Image,
    Images,
    Info,
    KeyRound,
    LayoutDashboard,
    Link,
    Globe,
    List,
    LogOut,
    Lock,
    Mail,
    MapPin,
    Megaphone,
    Menu,
    Minus,
    Moon,
    Newspaper,
    NotebookPen,
    PanelLeft,
    Pen,
    Phone,
    Plus,
    QrCode,
    Rocket,
    Save,
    Search,
    ScrollText,
    Settings,
    Shield,
    Star,
    Key,
    Sun,
    Tags,
    Thermometer,
    TrendingDown,
    TrendingUp,
    TriangleAlert,
    UserCheck,
    UserMinus,
    UserPlus,
    UserRound,
    Upload,
    Users,
    Trash2,
    X,
    "arrow-left": ArrowLeft,
    "arrow-right": ArrowRight,
    "at-sign": AtSign,
    bell: Bell,
    "bell-off": BellOff,
    briefcase: Briefcase,
    "building-2": Building2,
    "calendar-days": CalendarDays,
    "calendar-check": CalendarCheck,
    camera: Camera,
    check: Check,
    "calendar-x": CalendarX,
    "chart-line": ChartLine,
    "chevron-down": ChevronDown,
    "chevron-left": ChevronLeft,
    "chevron-right": ChevronRight,
    "circle-check": CircleCheck,
    "circle-x": CircleX,
    "clipboard-check": ClipboardCheck,
    "clipboard-list": ClipboardList,
    clock: Clock,
    code: Code,
    download: Download,
    "external-link": ExternalLink,
    eye: Eye,
    "file-signature": FileSignature,
    "file-text": FileText,
    "folder-open": FolderOpen,
    "graduation-cap": GraduationCap,
    image: Image,
    images: Images,
    info: Info,
    key: Key,
    "key-round": KeyRound,
    "layout-dashboard": LayoutDashboard,
    link: Link,
    globe: Globe,
    list: List,
    "log-out": LogOut,
    "map-pin": MapPin,
    mail: Mail,
    megaphone: Megaphone,
    menu: Menu,
    minus: Minus,
    moon: Moon,
    newspaper: Newspaper,
    "notebook-pen": NotebookPen,
    lock: Lock,
    "panel-left": PanelLeft,
    pen: Pen,
    phone: Phone,
    plus: Plus,
    "qr-code": QrCode,
    rocket: Rocket,
    save: Save,
    "scroll-text": ScrollText,
    search: Search,
    settings: Settings,
    shield: Shield,
    star: Star,
    sun: Sun,
    tags: Tags,
    thermometer: Thermometer,
    "trending-down": TrendingDown,
    "trending-up": TrendingUp,
    "user-check": UserCheck,
    "user-minus": UserMinus,
    "user-plus": UserPlus,
    "user-round": UserRound,
    upload: Upload,
    users: Users,
    "trash-2": Trash2,
    "triangle-alert": TriangleAlert,
    x: X,
};

window.Chart = Chart;
window.renderLucide = () => {
    if (window.lucide?.createIcons) {
        window.lucide.createIcons();
    } else {
        createIcons({ icons });
    }
};

const bootIcons = () => window.renderLucide();
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", bootIcons);
} else {
    bootIcons();
}
document.addEventListener("alpine:initialized", bootIcons);
window.addEventListener("load", bootIcons);

// Panel admin memakai smooth scrolling native browser. Sebelumnya Lenis
// membungkus seluruh halaman dan menganimasikan pemulihan posisi scroll saat
// pindah halaman, sehingga terasa seperti halaman bergeser. Dinonaktifkan
// agar navigasi antar halaman terasa instan.

// ---------------------------------------------------------------
// Navigasi filter tanpa reload halaman: form ber-atribut
// [data-ajax-filter] dan tautan [data-ajax-link] dimuat lewat fetch,
// lalu konten .page-content diganti dan URL diperbarui.
// ---------------------------------------------------------------
(() => {
    const container = () => document.querySelector(".page-content");

    const swap = (html, url) => {
        const target = container();
        if (!target) {
            window.location.assign(url);
            return;
        }

        const parsed = new DOMParser().parseFromString(html, "text/html");
        const fresh = parsed.querySelector(".page-content");
        if (!fresh) {
            window.location.assign(url);
            return;
        }

        // Hancurkan instance Alpine lama agar tidak ada listener menggantung,
        // lalu inisialisasi ulang konten baru.
        if (window.Alpine) {
            window.Alpine.destroyTree(target);
        }

        target.replaceChildren(...fresh.childNodes);
        window.history.pushState({ ajax: true }, "", url);

        if (window.Alpine) {
            window.Alpine.initTree(target);
        }
        window.renderLucide?.();
        window.scrollTo(0, 0);
    };

    const load = (url) => {
        const target = container();
        if (target) target.style.opacity = "0.5";

        fetch(url, {
            headers: { "X-Requested-With": "XMLHttpRequest", Accept: "text/html" },
        })
            .then((res) => res.text())
            .then((html) => swap(html, url))
            .catch(() => { window.location.assign(url); })
            .finally(() => {
                const t = container();
                if (t) t.style.opacity = "";
            });
    };

    // Dipakai oleh komponen select/search/tab agar tidak perlu reload.
    window.adminAjaxLoad = load;

    const submitToUrl = (form) => {
        const params = new URLSearchParams(new FormData(form));
        for (const [k, v] of [...params.entries()]) {
            if (v === "") params.delete(k);
        }
        const base = form.getAttribute("action") || window.location.pathname;
        load(`${base}?${params.toString()}`);
    };

    window.adminAjaxSubmit = (form) => submitToUrl(form);

    document.addEventListener(
        "submit",
        (e) => {
            const form = e.target.closest("form[data-ajax-filter]");
            if (!form) return;
            e.preventDefault();
            submitToUrl(form);
        },
        true,
    );

    document.addEventListener("click", (e) => {
        const link = e.target.closest("a[data-ajax-link]");
        if (!link) return;
        e.preventDefault();
        load(link.href);
    });
})();
