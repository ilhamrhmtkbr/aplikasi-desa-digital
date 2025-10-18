# 🏘️ Sistem Manajemen Desa Digital

Platform manajemen desa full-stack modern yang dibangun dengan Laravel 12 dan Vue 3.

## 📋 Daftar Isi

- [Gambaran Umum](#gambaran-umum)
- [Fitur](#fitur)
- [Tech Stack](#tech-stack)
- [Kebutuhan Sistem](#kebutuhan-sistem)
- [Instalasi](#instalasi)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Pengujian](#pengujian)
- [Dokumentasi API](#dokumentasi-api)

## 🎯 Gambaran Umum

Proyek ini merupakan sistem manajemen desa digital yang komprehensif, dirancang untuk menyederhanakan proses administratif dalam tata kelola desa. Platform ini menyediakan tools untuk mengelola data desa, informasi penduduk, dokumen administratif, dan layanan masyarakat.

**Highlight Utama:**
- 🚀 Backend berperforma tinggi dengan Laravel Octane + Swoole
- ⚡ Frontend modern reaktif dengan Vue 3
- 🐳 Fully containerized menggunakan Docker
- 🧪 Integration testing
- 🔄 CI/CD pipeline otomatis

## ✨ Fitur

### Fungsionalitas Inti
- **Manajemen Data Desa**: Profil desa lengkap dan data administratif
- **Registrasi Penduduk**: Database penduduk digital dengan pencarian dan filter
- **Autentikasi Pengguna**: Sistem autentikasi berbasis Sanctum
- **Role-Based Access Control**: Hak akses pengguna multi-level (admin dan head-of-family)

### Fitur Teknis
- Arsitektur RESTful API
- Desain responsif untuk semua perangkat
- Database migration dan seeding
- Request validation dan error handling

## 🛠 Tech Stack

### Backend
- **Framework**: Laravel 12
- **Server**: Laravel Octane dengan Swoole
- **Database**: MySQL 8.0
- **Authentication**: Sanctum
- **API**: RESTful API design
- **Testing**: PHPUnit, Integration Tests

### Frontend
- **Framework**: Vue 3 (Composition API)
- **State Management**: Pinia
- **Routing**: Vue Router
- **HTTP Client**: Axios
- **UI Components**: Tailwind CSS
- **Build Tool**: Vite

### DevOps & Tools
- **Containerization**: Docker & Docker Compose
- **CI/CD**: GitHub Actions
- **Version Control**: Git

## 💻 Kebutuhan Sistem

- Docker
- Git

**ATAU tanpa Docker:**
- PHP 8.2+
- Composer 2.0+
- Node.js 18+ & NPM
- MySQL 8.0+

## 📦 Instalasi

### Menggunakan Docker (Direkomendasikan)

1. **Clone repository**
```bash
git clone https://github.com/ilhamrhmtkbr/aplikasi-desa-digital.git
cd village-management-system
```

2. **Copy file environment**
```bash
# Backend
cp backend/.env.example backend/.env
```

3. **Build dan jalankan container**
```bash
# Docker
cd docker
docker-compose up -d
```

4. **Install dependencies dan setup database**
```bash
# Backend setup
docker-compose exec backend composer install
docker-compose exec backend php artisan key:generate
docker-compose exec backend php artisan migrate --seed

# Frontend setup
docker-compose exec frontend npm install
```

## 🚀 Menjalankan Aplikasi

### Dengan Docker
```bash
# Jalankan semua services
docker-compose up -d

# Lihat logs
docker-compose logs -f

# Stop services
docker-compose down
```

**Akses aplikasi:**
- Frontend: http://localhost:5173
- Backend API: http://localhost:8000

## 🧪 Pengujian

### Backend Tests
```bash
# Jalankan semua tests
docker-compose exec backend php artisan test
```

## 📚 Dokumentasi API

Dokumentasi API lengkap tersedia menggunakan OpenAPI/Swagger specification di: **http://localhost:8000/api/documentation**

## 👨‍💻 Author

**Nama Anda**
- GitHub: [@yourusername](https://github.com/ilhamrhmtkbr)
- LinkedIn: [LinkedIn Anda](https://linkedin.com/in/ilhamrhmtkbr)
- Email: ilhamrhmtkbr@gmail.com

## 🙏 Acknowledgments

- Dibangun sebagai bagian dari perjalanan belajar dengan kursus Full Stack Laravel & Vue
- Ditingkatkan secara inisiatif diri sendiri dengan menambahkan setup Docker, Swoole, dan CI/CD

---

**Catatan**: Proyek ini awalnya dikembangkan sebagai bagian dari kursus pembelajaran terstruktur dan kemudian ditingkatkan dengan fitur-fitur production-ready tambahan termasuk containerization, optimasi performa, pengujian komprehensif, dan automated deployment pipeline.