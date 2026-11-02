<?php

namespace Database\Seeders;

use App\Enums\FocusArea;
use App\Enums\Role;
use App\Models\Biodata;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PerkaderanFlowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Kader Uji Coba (Aktif, Belum punya Biodata)
        $kader = User::updateOrCreate(
            ['username' => 'kader'],
            [
                'name' => 'Fajar Perkaderan',
                'email' => 'kader@immuinsa.org',
                'password' => Hash::make('password'),
                'role' => Role::Kader,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        // Pastikan belum ada biodata agar langsung masuk alur pengisian biodata
        $kader->biodata()->delete();
        $kader->update([
            'focus_area' => null,
            'fasilitator_id' => null,
            'mentor_id' => null,
        ]);

        // 2. Fasilitator per Fokus Bidang
        $fasilitators = [
            [
                'username' => 'fasilitator_entrepreneur',
                'email' => 'fasilitator.entrepreneur@immuinsa.org',
                'name' => 'Ahmad Fauzi, S.E.',
                'focus' => FocusArea::Entrepreneur,
                'institution' => 'IMM Fastikom / HIPMI Surabaya',
                'expertise' => 'Founder Digital Agency & Inkubasi UMKM',
            ],
            [
                'username' => 'fasilitator_politisi',
                'email' => 'fasilitator.politisi@immuinsa.org',
                'name' => 'Dian Permata, S.H.',
                'focus' => FocusArea::Politisi,
                'institution' => 'IMM FDK UINSA / Staf Kebijakan Publik',
                'expertise' => 'Advokasi Anggaran & Analisis Parlemen',
            ],
            [
                'username' => 'fasilitator_akademisi',
                'email' => 'fasilitator.akademisi@immuinsa.org',
                'name' => 'Nur Hasanah, M.Pd.',
                'focus' => FocusArea::Akademisi,
                'institution' => 'IMM Tarbiyah / LPPM UINSA',
                'expertise' => 'Metodologi Penelitian & Jurnal Terindeks',
            ],
            [
                'username' => 'fasilitator_pengabdi',
                'email' => 'fasilitator.pengabdi@immuinsa.org',
                'name' => 'Ridwan Mansyur, S.Sos.',
                'focus' => FocusArea::PengabdiPersyarikatan,
                'institution' => 'IMM Korkom / Majelis MPKSDI',
                'expertise' => 'Manajemen Keorganisasian & Ideologi Muhammadiyah',
            ],
        ];

        foreach ($fasilitators as $data) {
            $fasil = User::updateOrCreate(
                ['username' => $data['username']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make('password'),
                    'role' => Role::Fasilitator,
                    'is_active' => true,
                    'focus_area' => $data['focus'],
                    'email_verified_at' => now(),
                ]
            );

            Biodata::updateOrCreate(
                ['user_id' => $fasil->id],
                [
                    'full_name' => $data['name'],
                    'phone_number' => '08123456789'.$fasil->id,
                    'address' => 'Surabaya, Jawa Timur',
                    'date_of_birth' => '1998-04-12',
                    'gender' => 'male',
                    'institution' => $data['institution'],
                    'expertise' => $data['expertise'],
                ]
            );
        }

        // 3. Mentor per Fokus Bidang
        $mentors = [
            [
                'username' => 'mentor_entrepreneur',
                'email' => 'mentor.entrepreneur@immuinsa.org',
                'name' => 'Dr. M. Rizal, M.M.',
                'focus' => FocusArea::Entrepreneur,
                'institution' => 'Jaringan Saudagar Muhammadiyah (JSM)',
                'expertise' => 'Scale-up Bisnis, Ekosistem Venture & Angel Investor',
            ],
            [
                'username' => 'mentor_politisi',
                'email' => 'mentor.politisi@immuinsa.org',
                'name' => 'H. Syamsul Arifin, M.Si.',
                'focus' => FocusArea::Politisi,
                'institution' => 'Pimpinan Daerah / Praktisi Kebijakan Nasional',
                'expertise' => 'Strategi Elektoral, Diplomasi Politik & Tata Kelola Negara',
            ],
            [
                'username' => 'mentor_akademisi',
                'email' => 'mentor.akademisi@immuinsa.org',
                'name' => 'Prof. Dr. Siti Aminah, M.Ag.',
                'focus' => FocusArea::Akademisi,
                'institution' => 'Guru Besar UIN Sunan Ampel Surabaya',
                'expertise' => 'Filsafat Islam, Riset Post-Doktoral & Kolaborasi Internasional',
            ],
            [
                'username' => 'mentor_pengabdi',
                'email' => 'mentor.pengabdi@immuinsa.org',
                'name' => 'KH. Hamdan Makruf, M.Ud.',
                'focus' => FocusArea::PengabdiPersyarikatan,
                'institution' => 'Pimpinan Wilayah Muhammadiyah (PWM)',
                'expertise' => 'Kaderisasi Ulama Tarjih & Kepemimpinan Persyarikatan',
            ],
        ];

        foreach ($mentors as $data) {
            $mentor = User::updateOrCreate(
                ['username' => $data['username']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make('password'),
                    'role' => Role::Mentor,
                    'is_active' => true,
                    'focus_area' => $data['focus'],
                    'email_verified_at' => now(),
                ]
            );

            Biodata::updateOrCreate(
                ['user_id' => $mentor->id],
                [
                    'full_name' => $data['name'],
                    'phone_number' => '08129876543'.$mentor->id,
                    'address' => 'Surabaya, Jawa Timur',
                    'date_of_birth' => '1980-08-17',
                    'gender' => 'male',
                    'institution' => $data['institution'],
                    'expertise' => $data['expertise'],
                ]
            );
        }

        // 4. Tasks (3 Tugas untuk tiap Fokus Bidang)
        $tasks = [
            // Entrepreneur
            [
                'focus_area' => FocusArea::Entrepreneur,
                'order' => 1,
                'title' => 'Riset Pasar & Identifikasi Masalah Konsumen',
                'description' => 'Lakukan observasi pada minimal 5 calon pelanggan potensial dan petakan *pain points* serta ide solusi produk/jasa yang ingin Anda tawarkan.',
            ],
            [
                'focus_area' => FocusArea::Entrepreneur,
                'order' => 2,
                'title' => 'Penyusunan Business Model Canvas (BMC)',
                'description' => 'Susun 9 blok Business Model Canvas untuk rancangan bisnis rintisan Anda, diskusikan bersama fasilitator pendamping.',
            ],
            [
                'focus_area' => FocusArea::Entrepreneur,
                'order' => 3,
                'title' => 'Studi Lapangan Amal Usaha Muhammadiyah (AUM)',
                'description' => 'Kunjungi dan wawancarai salah satu pengelola unit usaha Muhammadiyah / saudagar ikatan, lalu buat laporan refleksi pembelajaran.',
            ],

            // Politisi
            [
                'focus_area' => FocusArea::Politisi,
                'order' => 1,
                'title' => 'Analisis Isu Publik & Kebijakan Daerah',
                'description' => 'Tuliskan kajian kritis 1-2 halaman mengenai peraturan daerah atau isu kebijakan publik terkini di kota/provinsi Anda.',
            ],
            [
                'focus_area' => FocusArea::Politisi,
                'order' => 2,
                'title' => 'Pemetaan Aktor & Stakeholder Kebijakan',
                'description' => 'Buat analisis peta relasi kekuasaan (*power mapping*) dari pihak-pihak berkepentingan dalam advokasi isu yang Anda pilih.',
            ],
            [
                'focus_area' => FocusArea::Politisi,
                'order' => 3,
                'title' => 'Penyusunan Naskah Policy Brief / Pernyataan Sikap',
                'description' => 'Susun draf *policy brief* resmi berformat IMM dengan rekomendasi langkah solutif konkret bagi pembuat kebijakan.',
            ],

            // Akademisi
            [
                'focus_area' => FocusArea::Akademisi,
                'order' => 1,
                'title' => 'Pemetaan Literatur Ilmiah (Literature Review)',
                'description' => 'Kumpulkan dan ringkas minimal 5 artikel jurnal bereputasi terkait tema riset bidang studi Anda dalam 3 tahun terakhir.',
            ],
            [
                'focus_area' => FocusArea::Akademisi,
                'order' => 2,
                'title' => 'Penyusunan Rancangan Proposal Penelitian Ringkas',
                'description' => 'Rumuskan latar belakang masalah, pertanyaan penelitian, dan metode riset ilmiah yang akan digunakan.',
            ],
            [
                'focus_area' => FocusArea::Akademisi,
                'order' => 3,
                'title' => 'Draf Abstrak Artikel Ilmiah Siap Publikasi',
                'description' => 'Tuliskan draf abstrak berbahasa Indonesia dan Inggris berstandar publikasi jurnal terakreditasi.',
            ],

            // Pengabdi Persyarikatan
            [
                'focus_area' => FocusArea::PengabdiPersyarikatan,
                'order' => 1,
                'title' => 'Kajian Matan Keyakinan & Cita-Cita Hidup Muhammadiyah (MKCH)',
                'description' => 'Susun resume esai pemahaman ideologi MKCH dan manifestasinya dalam gerakan IMM kontemporer.',
            ],
            [
                'focus_area' => FocusArea::PengabdiPersyarikatan,
                'order' => 2,
                'title' => 'Observasi & Keterlibatan di Pimpinan Ranting / Cabang',
                'description' => 'Ikuti minimal satu kegiatan di tingkat Ranting/Cabang Muhammadiyah setempat dan dokumentasikan laporannya.',
            ],
            [
                'focus_area' => FocusArea::PengabdiPersyarikatan,
                'order' => 3,
                'title' => 'Penyusunan Rencana Program Dakwah Komunitas',
                'description' => 'Rancang satu program pengabdian atau kajian dakwah kultural berbasis komunitas binaan di lingkungan sekitar Anda.',
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::updateOrCreate(
                [
                    'focus_area' => $taskData['focus_area'],
                    'order' => $taskData['order'],
                ],
                [
                    'title' => $taskData['title'],
                    'description' => $taskData['description'],
                ]
            );
        }
    }
}
