<?php

namespace Database\Seeders;

use App\Models\Behavioral;
use App\Models\BobotSkor;
use App\Models\Indikator;
use App\Models\KelompokJabatan;
use App\Models\Pilar;
use App\Models\Siklus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BehavioralBelumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                "id" => 2,
                "title" => "WORK BEHAVIOR",
                "slug" => "",
                'bobot' => null,
                "indikators" => [
                    [
                        "id" => 64,
                        "title" => "Disiplin dan Kepatuhan Kerja",
                        "deskripsi" => "Konsisten menjalankan tugas sesuai jadwal, area kerja, SOP, dan ketentuan yang berlaku.",
                        "kelompok_jabatan" => "Environment Support",
                        "behaviorals" => [
                            [
                                "id" => 253,
                                "skor" => 4,
                                "deskripsi" => "Menjalankan tugas sesuai jadwal, area kerja, SOP, dan ketentuan yang berlaku tanpa ditemukan area yang terlewat, pekerjaan yang tidak dilaksanakan, atau pelanggaran terhadap prosedur kerja."
                            ],
                            [
                                "id" => 254,
                                "skor" => 3,
                                "deskripsi" => "Menjalankan tugas sesuai jadwal, area kerja, SOP, dan ketentuan yang berlaku, namun masih ditemukan ketidaksesuaian yang tidak memengaruhi hasil pekerjaan secara keseluruhan (misalnya keterlambatan pengisian checklist, penggunaan perlengkapan yang belum sesuai prosedur, atau dokumentasi pekerjaan yang belum lengkap)."
                            ],
                            [
                                "id" => 255,
                                "skor" => 2,
                                "deskripsi" => "Ditemukan ketidaksesuaian terhadap SOP, jadwal, atau area kerja yang menyebabkan sebagian pekerjaan tidak terlaksana sesuai standar yang ditetapkan (misalnya terdapat area yang tidak dibersihkan sesuai jadwal, pekerjaan pemeliharaan yang tidak dilakukan, atau prosedur kerja yang diabaikan sehingga memengaruhi kualitas hasil kerja)."
                            ],
                            [
                                "id" => 256,
                                "skor" => 1,
                                "deskripsi" => "Tidak menjalankan SOP, jadwal, atau area kerja yang menjadi tanggung jawabnya sehingga pekerjaan tidak terlaksana sesuai standar yang ditetapkan."
                            ],
                        ]
                    ],
                    [
                        "id" => 68,
                        "title" => "Kepekaan terhadap Kondisi Lingkungan",
                        "deskripsi" => "Kemampuan mengenali kondisi lingkungan yang memerlukan perhatian sebelum berkembang menjadi gangguan terhadap area kerja.",
                        "kelompok_jabatan" => "Environment Support",
                        "behaviorals" => [
                            [
                                "id" => 257,
                                "skor" => 4,
                                "deskripsi" => "Mengenali seluruh kondisi lingkungan yang memerlukan perhatian dan menyampaikan temuan tersebut kepada pihak yang berwenang atau mencatatnya sesuai prosedur sebelum berkembang menjadi gangguan (misalnya menemukan saluran yang mulai tersumbat, pohon yang berpotensi tumbang, atau lantai yang licin)."
                            ],
                            [
                                "id" => 258,
                                "skor" => 3,
                                "deskripsi" => "Mengenali kondisi lingkungan yang memerlukan perhatian sehingga kondisi tersebut dapat segera ditindaklanjuti (misalnya menemukan area yang mulai kotor, tanaman yang mulai layu, atau fasilitas yang mulai rusak)."
                            ],
                            [
                                "id" => 259,
                                "skor" => 2,
                                "deskripsi" => "Hanya mengenali sebagian kondisi yang memerlukan perhatian sehingga masih terdapat kondisi yang baru diketahui setelah muncul keluhan atau mengganggu pekerjaan (misalnya saluran yang mulai tersumbat atau area yang memerlukan pembersihan tidak teridentifikasi lebih awal)."
                            ],
                            [
                                "id" => 260,
                                "skor" => 1,
                                "deskripsi" => "Tidak mengenali kondisi lingkungan yang memerlukan perhatian sehingga gangguan, keluhan, atau risiko baru diketahui setelah berdampak pada pengguna atau operasional."
                            ],
                        ]
                    ],
                    [
                        "id" => 72,
                        "title" => "Responsivitas dan Tindak Lanjut",
                        "deskripsi" => "Kemampuan menindaklanjuti kebutuhan pekerjaan atau permasalahan yang telah diketahui sesuai prosedur dan kewenangan yang dimiliki.",
                        "kelompok_jabatan" => "Environment Support",
                        "behaviorals" => [
                            [
                                "id" => 261,
                                "skor" => 4,
                                "deskripsi" => "Melakukan seluruh tindak lanjut yang menjadi kewenangannya dan memastikan setiap permasalahan yang ditemukan telah ditangani atau dilaporkan kepada pihak yang berwenang sesuai prosedur."
                            ],
                            [
                                "id" => 262,
                                "skor" => 3,
                                "deskripsi" => "Melakukan tindak lanjut terhadap permasalahan yang menjadi tanggung jawabnya sesuai prosedur, namun masih terdapat tindak lanjut yang memerlukan penyempurnaan administratif misalnya dokumentasi pelaporan belum lengkap)."
                            ],
                            [
                                "id" => 263,
                                "skor" => 2,
                                "deskripsi" => "Hanya menindaklanjuti sebagian permasalahan sehingga masih terdapat kondisi yang belum ditangani atau belum dilaporkan kepada pihak terkait (misalnya kerusakan fasilitas tidak dilaporkan atau area yang memerlukan tindakan belum ditangani)."
                            ],
                            [
                                "id" => 264,
                                "skor" => 1,
                                "deskripsi" => "Tidak melakukan tindak lanjut terhadap permasalahan yang menjadi tanggung jawabnya sehingga permasalahan tetap terjadi atau berkembang menjadi gangguan yang lebih besar."
                            ],
                        ]
                    ],
                    [
                        "id" => 76,
                        "title" => "Ketelitian dalam Pelaksanaan Tugas",
                        "deskripsi" => "Kemampuan memperhatikan detail pekerjaan untuk memastikan seluruh area, fasilitas, dan perlengkapan ditangani sesuai standar yang ditetapkan.",
                        "kelompok_jabatan" => "Environment Support",
                        "behaviorals" => [
                            [
                                "id" => 265,
                                "skor" => 4,
                                "deskripsi" => "Memastikan seluruh area, fasilitas, dan perlengkapan yang menjadi tanggung jawabnya telah diperiksa dan ditangani sesuai standar yang ditetapkan tanpa ada bagian penting yang terlewat."
                            ],

                            [
                                "id" => 266,
                                "skor" => 3,
                                "deskripsi" => "Menangani area, fasilitas, dan perlengkapan sesuai standar yang ditetapkan, namun masih ditemukan detail administratif atau pendukung yang belum lengkap (misalnya penempatan perlengkapan belum sesuai lokasi yang ditentukan atau pencatatan pekerjaan belum lengkap)."
                            ],
                            [
                                "id" => 267,
                                "skor" => 2,
                                "deskripsi" => "Masih terdapat bagian pekerjaan yang terlewat atau tidak sesuai standar sehingga memerlukan perbaikan (misalnya area tertentu belum dibersihkan, tanaman yang memerlukan perawatan tidak tertangani, atau perlengkapan kerja tertinggal di area kerja)."
                            ],
                            [
                                "id" => 268,
                                "skor" => 1,
                                "deskripsi" => "Mengabaikan detail penting dalam pekerjaan sehingga area, fasilitas, atau perlengkapan tidak ditangani sesuai standar yang ditetapkan."
                            ],
                        ]
                    ],
                ]
            ],
        ];

        foreach ($data as $pilarData) {

            foreach ($pilarData['indikators'] as $indikatorData) {

                $kelompokJabatan = KelompokJabatan::firstOrCreate(
                    [
                        'nama_kelompok' => $indikatorData['kelompok_jabatan'],
                    ]
                );

                $indikator = Indikator::create(
                    [
                        'pilar_id' => 2,
                        'kelompok_jabatan_id' => $kelompokJabatan->id,
                        'title' => $indikatorData['title'],
                        'defenisi' => $indikatorData['deskripsi'],
                        'example' => json_encode([]),
                    ]
                );

                foreach ($indikatorData['behaviorals'] as $behavioralData) {
                    Behavioral::create([
                        'indikator_id' => $indikator->id,
                        'behavioral' => $behavioralData['deskripsi'],
                        'skor' => $behavioralData['skor'],
                    ]);
                }
            }
        }
    }
}
