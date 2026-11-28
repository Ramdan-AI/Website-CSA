<x-ui.error-page
    code="419"
    title="Halaman kedaluwarsa"
    message="Sesi formulir sudah habis, biasanya karena halaman terlalu lama terbuka. Muat ulang formulir lalu coba lagi."
    primary-label="Muat ulang formulir"
    :primary-href="url()->previous()"
    secondary-label="Beranda" />
