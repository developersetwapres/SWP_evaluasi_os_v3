<?php

namespace Database\Seeders;

use App\Models\Behavioral;
use App\Models\BobotSkor;
use App\Models\Indikator;
use App\Models\KelompokJabatan;
use App\Models\Pilar;
use App\Models\Siklus;
use Illuminate\Database\Seeder;

class BehavioralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                "id" => 1,
                "title" => "Attitude & Service",
                "slug" => "attitude-service",
                'bobot' => 20,
                "indikators" => [
                    [
                        "id" => 8,
                        "title" => "Orientasi Pelayanan Administrasi Definisi",
                        "deskripsi" => "Menunjukkan sikap yang memahami kebutuhan pengguna layanan administrasi, memberikandukungan secara responsif, serta membangun hubungan kerja yang profesional dalam setiapproses administrasi.",
                        "kelompok_jabatan" => "BARS_Administrative Support",
                        "behaviorals" => [
                            [
                                "id" => 29,
                                "skor" => 4,
                                "deskripsi" => "Secara konsisten menunjukkan sikap membantu, responsif, dan profesional dalam memberikan layanan administrasi. (Misalnya mendengarkan kebutuhan pengguna sampai tuntas, menjelaskan prosedur administrasi dengan bahasa yang mudah dipahami, memberikan alternatif penyelesaian yang masih sesuai kewenangan, serta memastikan pengguna memahami langkah administrasi berikutnya.)"
                            ],
                            [
                                "id" => 30,
                                "skor" => 3,
                                "deskripsi" => "Menunjukkan sikap membantu dan profesional dalam memberikan layanan administrasi sesuai kebutuhan pekerjaan. (Misalnya memberikan informasi yang diminta dengan jelas, menjawab pertanyaan pengguna secara sopan, atau membantu pengguna melengkapi persyaratan administrasi.)"
                            ],
                            [
                                "id" => 31,
                                "skor" => 2,
                                "deskripsi" => "Memberikan layanan administrasi sesuai permintaan, namun komunikasi atau respons yang diberikan masih terbatas sehingga pengguna sering memerlukan klarifikasi tambahan. (Misalnya hanya menjawab pertanyaan yang diajukan tanpa memberikan informasi pendukung yang relevan, atau memberikan penjelasan yang masih kurang lengkap.)"
                            ],
                            [
                                "id" => 32,
                                "skor" => 1,
                                "deskripsi" => "Tidak menunjukkan sikap yang mendukung kebutuhan pengguna dalam proses administrasi. (Misalnyamengabaikan pertanyaan pengguna, memberikan respons yang tidak profesional, atau enggan membantu menjelaskan prosedur administrasi yang menjadi tanggung jawabnya.)"
                            ]
                        ]
                    ],
                    [
                        "id" => 9,
                        "title" => "Integritas dan Kerahasiaan Informasi Definisi",
                        "deskripsi" => "Menunjukkan sikap jujur, bertanggung jawab, menjaga kerahasiaan data dan dokumen, sertamenggunakan informasi administrasi sesuai kewenangan yang dimiliki.",
                        "kelompok_jabatan" => "BARS_Administrative Support",
                        "behaviorals" => [
                            [
                                "id" => 33,
                                "skor" => 4,
                                "deskripsi" => "Secara konsisten menjaga kerahasiaan informasi, menggunakan data dan dokumen sesuai kewenangan, serta menunjukkan kejujuran dan tanggung jawab dalam setiap pekerjaan administrasi."
                            ],
                            [
                                "id" => 34,
                                "skor" => 3,
                                "deskripsi" => "Menunjukkan sikap jujur dan bertanggung jawab dalam penggunaan informasi serta menjaga kerahasiaan data sesuai ketentuan organisasi."
                            ],
                            [
                                "id" => 35,
                                "skor" => 2,
                                "deskripsi" => "Masih memerlukan pengingat untuk menjaga kerahasiaan informasi atau menggunakan data sesuai kewenangan."
                            ],
                            [
                                "id" => 36,
                                "skor" => 1,
                                "deskripsi" => "Menunjukkan perilaku yang tidak menjaga integritas atau kerahasiaan informasi sehingga berpotensi menimbulkan risiko bagi organisasi."
                            ]
                        ]
                    ],
                    [
                        "id" => 18,
                        "title" => "Orientasi Pelayanan Definisi",
                        "deskripsi" => "kan sikap yang mengutamakan kenyamanan, kebutuhan, dan pengalaman positifpengguna layanan melalui komunikasi yang sopan, membantu, menghargai, dan memberikanperhatian selama pelayanan berlangsung.",
                        "kelompok_jabatan" => "BARS_Hospitality & Service Support",
                        "behaviorals" => [
                            [
                                "id" => 69,
                                "skor" => 4,
                                "deskripsi" => "Menunjukkan sikap yang sopan, ramah, menghargai, dan membantu pengguna layanan,serta secara aktif menciptakan kenyamanan pengguna (misalnya menyambut tamusebelum diminta, menawarkan bantuan ketika melihat pengguna mengalami kesulitan,memberikan informasi tambahan yang diperlukan, atau memastikan pengguna mengetahuitahapan pelayanan yang sedang berlangsung)."
                            ],
                            [
                                "id" => 70,
                                "skor" => 3,
                                "deskripsi" => "Menunjukkan sikap yang sopan, ramah, menghargai, dan membantu pengguna sesuaikebutuhan selama proses pelayanan (misalnya menjawab pertanyaan dengan bahasa yangmudah dipahami, membantu ketika diminta, serta menjaga komunikasi yang santun selamapelayanan berlangsung)."
                            ],
                            [
                                "id" => 71,
                                "skor" => 2,
                                "deskripsi" => "Tetap menunjukkan sikap yang sopan, namun belum memberikan perhatian yang memadaiterhadap kenyamanan pengguna (misalnya memberikan jawaban secara singkat tanpamemastikan pengguna memahami informasi, kurang menjaga kontak dengan penggunayang sedang menunggu, atau hanya memberikan bantuan setelah diminta berulang kali)."
                            ],
                            [
                                "id" => 72,
                                "skor" => 1,
                                "deskripsi" => "Menunjukkan perilaku yang tidak menghargai pengguna sehingga menimbulkanketidaknyamanan atau keluhan (misalnya mengabaikan pengguna yang membutuhkanbantuan, berbicara dengan nada yang tidak sopan, menunjukkan ekspresi tidakmenyenangkan, atau memperdebatkan keluhan pengguna)."
                            ]
                        ]
                    ],
                    [
                        "id" => 19,
                        "title" => "Etika dan Tanggung Jawab Kerja Definisi",
                        "deskripsi" => "Menunjukkan sikap yang menjaga kepercayaan organisasi melalui perilaku yang jujur,menghormati pengguna layanan, menjaga kerahasiaan informasi, menjaga aset organisasi, sertamematuhi etika pelayanan.",
                        "kelompok_jabatan" => "BARS_Hospitality & Service Support",
                        "behaviorals" => [
                            [
                                "id" => 73,
                                "skor" => 4,
                                "deskripsi" => "Menunjukkan sikap yang menjaga kepercayaan organisasi dengan menghormati penggunalayanan, menjaga kerahasiaan informasi, menggunakan aset sesuai peruntukannya, sertamenjaga penampilan dan etika pelayanan tanpa ditemukan pelanggaran."
                            ],
                            [
                                "id" => 74,
                                "skor" => 3,
                                "deskripsi" => "Menunjukkan sikap yang menjaga etika pelayanan dan tanggung jawab kerja, namun masihterdapat ketidaksesuaian administratif atau pendukung yang tidak menimbulkan risikoterhadap organisasi (misalnya belum merapikan perlengkapan pelayanan setelah selesaidigunakan, belum memperbarui pencatatan peminjaman perlengkapan, atau belummenyesuaikan atribut kerja sebelum memulai tugas)."
                            ],
                            [
                                "id" => 75,
                                "skor" => 2,
                                "deskripsi" => "Masih ditemukan perilaku yang kurang mencerminkan tanggung jawab terhadap etikapelayanan atau aset organisasi sehingga memerlukan pembinaan (misalnya membicarakanagenda internal kepada pihak yang tidak berkepentingan, menggunakan perlengkapanpelayanan untuk kepentingan pribadi tanpa izin, atau tidak menjaga standar grooming yangtelah ditetapkan)."
                            ],
                            [
                                "id" => 76,
                                "skor" => 1,
                                "deskripsi" => "Menunjukkan perilaku yang melanggar etika kerja atau menyalahgunakan kepercayaanorganisasi sehingga berpotensi merugikan organisasi atau menurunkan kepercayaanpengguna layanan (misalnya membocorkan informasi yang bersifat rahasia, bersikap tidakhormat kepada tamu, atau menyalahgunakan aset organisasi)."
                            ]
                        ]
                    ],
                    [
                        "id" => 28,
                        "title" => "Orientasi Pelayanan",
                        "deskripsi" => "Menunjukkan sikap yang mengutamakan kenyamanan, keamanan, serta kebutuhanpengguna layanan melalui komunikasi yang sopan, menghargai pengguna, danmemberikan pelayanan yang profesional selama perjalanan berlangsung.",
                        "kelompok_jabatan" => "Transportation Support",
                        "behaviorals" => [
                            [
                                "id" => 109,
                                "skor" => 4,
                                "deskripsi" => "Menunjukkan sikap yang sopan, menghargai, dan membantu pengguna layanan, serta secara aktifmenciptakan rasa aman dan nyaman selama pelayanan (misalnya menyambut pengguna dengansopan, membantu membawa barang bawaan ketika diperlukan, menginformasikan perubahan ruteatau waktu tempuh secara proaktif, serta memastikan pengguna turun di lokasi yang aman)."
                            ],
                            [
                                "id" => 110,
                                "skor" => 3,
                                "deskripsi" => "Menunjukkan sikap yang sopan, menghargai, dan membantu pengguna sesuai kebutuhan selamapelayanan (misalnya menjawab pertanyaan dengan santun, membantu ketika diminta, serta menjagakomunikasi yang baik selama perjalanan)."
                            ],
                            [
                                "id" => 111,
                                "skor" => 2,
                                "deskripsi" => "Tetap menunjukkan sikap yang sopan, namun belum memberikan perhatian yang memadai terhadapkenyamanan pengguna (misalnya memberikan informasi perjalanan hanya setelah penggunabertanya, kurang membantu pengguna yang membawa barang, atau kurang memperhatikankenyamanan pengguna selama perjalanan)."
                            ],
                            [
                                "id" => 112,
                                "skor" => 1,
                                "deskripsi" => "Menunjukkan perilaku yang tidak menghargai pengguna sehingga menimbulkan ketidaknyamananatau keluhan (misalnya berbicara dengan nada yang tidak sopan, mengabaikan pertanyaan pengguna,merokok di dalam kendaraan, atau menggunakan telepon untuk kepentingan pribadi hinggamengganggu pelayanan)."
                            ]
                        ]
                    ],
                    [
                        "id" => 29,
                        "title" => "Etika dan Tanggung Jawab Kerja",
                        "deskripsi" => "Menunjukkan sikap yang menjaga kepercayaan organisasi melalui perilaku yang jujur,menjaga kerahasiaan informasi, menggunakan kendaraan dan fasilitas kerja sesuaiperuntukannya, serta bertanggung jawab terhadap aset organisasi.",
                        "kelompok_jabatan" => "Transportation Support",
                        "behaviorals" => [
                            [
                                "id" => 113,
                                "skor" => 4,
                                "deskripsi" => "Menunjukkan sikap yang menjaga kepercayaan organisasi dengan menghormati privasi pengguna,menjaga kerahasiaan informasi perjalanan, menggunakan kendaraan sesuai penugasan, sertamerawat aset organisasi tanpa ditemukan pelanggaran (misalnya tidak membicarakan agendapimpinan kepada pihak lain, tidak menggunakan kendaraan dinas untuk kepentingan pribadi, sertamenjaga kebersihan dan kondisi kendaraan setelah digunakan)."
                            ],
                            [
                                "id" => 114,
                                "skor" => 3,
                                "deskripsi" => "Menunjukkan sikap yang bertanggung jawab terhadap pengguna dan aset organisasi, namun masihterdapat kekurangan pada aspek pendukung yang tidak menimbulkan risiko terhadap pelayanan(misalnya belum segera merapikan kendaraan setelah selesai digunakan, belum melengkapipencatatan penggunaan kendaraan, atau atribut kerja belum sepenuhnya sesuai standar sebelumbertugas)."
                            ],
                            [
                                "id" => 115,
                                "skor" => 2,
                                "deskripsi" => "Masih ditemukan perilaku yang kurang mencerminkan tanggung jawab terhadap pengguna atauaset organisasi sehingga memerlukan pembinaan (misalnya meninggalkan kendaraan dalamkondisi kurang rapi, menggunakan kendaraan untuk keperluan di luar penugasan tanpa izin, ataumembicarakan informasi perjalanan kepada pihak yang tidak berkepentingan)."
                            ],
                            [
                                "id" => 116,
                                "skor" => 1,
                                "deskripsi" => "Menunjukkan perilaku yang melanggar etika kerja atau menyalahgunakan kepercayaan organisasisehingga berpotensi merugikan organisasi atau menurunkan kepercayaan pengguna(misalnya menyalahgunakan kendaraan dinas untuk kepentingan pribadi, membocorkan agendaatau informasi perjalanan yang bersifat rahasia, atau memperlakukan pengguna layanan dengantidak hormat)."
                            ]
                        ]
                    ],
                    [
                        "id" => 32,
                        "title" => "Orientasi Pelayanan",
                        "deskripsi" => "Menunjukkan sikap yang mengutamakan kenyamanan pengguna melalui pelayanan yang sopan,ramah, membantu, dan menghargai setiap pengguna lingkungan kerja.",
                        "kelompok_jabatan" => "Environment Support",
                        "behaviorals" => [
                            [
                                "id" => 125,
                                "skor" => 4,
                                "deskripsi" => "Menunjukkan sikap yang sopan, ramah, menghargai, dan membantu pengguna area,serta berinisiatif memberikan bantuan untuk menjaga kenyamanan pengguna tanpa harusdiminta (misalnya membantu membuka akses bagi pengguna yang membawa barang,membersihkan area yang akan segera digunakan, memberikan informasi yang diketahui dengansopan, atau mengarahkan pengguna ke lokasi yang dituju)."
                            ],
                            [
                                "id" => 126,
                                "skor" => 3,
                                "deskripsi" => "Menunjukkan sikap yang sopan, ramah, menghargai, dan membantu pengguna sesuai kebutuhansaat berinteraksi atau ketika diminta bantuan (misalnya menjawab pertanyaan dengan sopan,membantu pengguna yang meminta bantuan, atau memberikan informasi sesuai yang diketahui)."
                            ],
                            [
                                "id" => 127,
                                "skor" => 2,
                                "deskripsi" => "Menunjukkan sikap yang sopan, namun masih terdapat perilaku yang mengurangi kenyamananpengguna (misalnya tidak menyapa saat berinteraksi, memberikan jawaban yang kurang jelas, ataumenunjukkan ekspresi yang kurang ramah ketika diminta bantuan)."
                            ],
                            [
                                "id" => 128,
                                "skor" => 1,
                                "deskripsi" => "Menunjukkan perilaku yang tidak sopan, tidak menghargai pengguna, atau menimbulkan keluhanterkait pelayanan yang diberikan (misalnya berbicara dengan nada yang tidak pantas,mengabaikan pengguna yang meminta bantuan, atau berdebat dengan pengguna)."
                            ]
                        ]
                    ],
                    [
                        "id" => 33,
                        "title" => "Etika dan Tanggung Jawab Kerja",
                        "deskripsi" => "Menunjukkan sikap bertanggung jawab dalam melaksanakan pekerjaan, menjaga etika kerja,menggunakan fasilitas sesuai peruntukannya, serta menjaga kerahasiaan informasi yang diperolehselama bekerja.",
                        "kelompok_jabatan" => "Environment Support",
                        "behaviorals" => [
                            [
                                "id" => 129,
                                "skor" => 4,
                                "deskripsi" => "Menunjukkan sikap bertanggung jawab terhadap pekerjaan, menjaga etika kerja,menggunakan fasilitas sesuai peruntukannya, serta menjaga kerahasiaan informasi tanpaditemukan pelanggaran (misalnya tidak menggunakan fasilitas untuk kepentingan pribadi,menjaga informasi kegiatan kedinasan, dan mengembalikan perlengkapan kerja sesuaiketentuan)."
                            ],
                            [
                                "id" => 130,
                                "skor" => 3,
                                "deskripsi" => "Menunjukkan sikap bertanggung jawab, menjaga etika kerja, serta menggunakan fasilitassesuai ketentuan, namun masih ditemukan ketidaksesuaian yang bersifat administratif dantidak berdampak terhadap pekerjaan (misalnya perlengkapan kerja belum dikembalikan ketempat penyimpanan setelah selesai digunakan atau pencatatan penggunaan fasilitas belumlengkap)."
                            ],
                            [
                                "id" => 131,
                                "skor" => 2,
                                "deskripsi" => "Masih ditemukan perilaku yang kurang mencerminkan tanggung jawab atau kepatuhanterhadap ketentuan kerja sehingga memerlukan pembinaan (misalnya menggunakanfasilitas kerja tidak sesuai peruntukannya, tidak menjaga perlengkapan kerja dengan baik,atau menyampaikan informasi internal kepada pihak yang tidak berkepentingan)."
                            ],
                            [
                                "id" => 132,
                                "skor" => 1,
                                "deskripsi" => "Menunjukkan perilaku yang bertentangan dengan etika kerja atau menyalahgunakankepercayaan yang diberikan sehingga berdampak terhadap organisasi (misalnyamenyalahgunakan fasilitas untuk kepentingan pribadi, membocorkan informasi yangbersifat rahasia, atau melakukan tindakan yang melanggar etika kerja)."
                            ]
                        ]
                    ],
                    [
                        "id" => 42,
                        "title" => "Orientasi Pelayanan",
                        "deskripsi" => "Menunjukkan sikap yang mengutamakan kebutuhan pengguna melalui pelayanan yang sopan,komunikatif, membantu, serta mampu menjelaskan informasi teknis dengan cara yang mudahdipahami.",
                        "kelompok_jabatan" => "Technical Support",
                        "behaviorals" => [
                            [
                                "id" => 165,
                                "skor" => 4,
                                "deskripsi" => "Menunjukkan sikap yang sopan, komunikatif, menghargai, dan membantu pengguna,serta berinisiatif memberikan informasi atau bantuan tanpa harus menunggudiminta (misalnya menjelaskan penyebab gangguan dan langkah penanganannya denganbahasa yang mudah dipahami, memberikan alternatif solusi sementara, atau memastikanpengguna memahami cara menggunakan sistem/perangkat setelah pekerjaan selesai)."
                            ],
                            [
                                "id" => 166,
                                "skor" => 3,
                                "deskripsi" => "Menunjukkan sikap yang sopan, komunikatif, menghargai, dan membantu pengguna sesuaikebutuhan saat berinteraksi (misalnya menjawab pertanyaan pengguna dengan bahasa yangmudah dipahami, memberikan penjelasan ketika diminta, atau membantu penggunamenyelesaikan kesulitan yang dihadapi)."
                            ],
                            [
                                "id" => 167,
                                "skor" => 2,
                                "deskripsi" => "Menunjukkan sikap yang sopan, namun masih terdapat perilaku yang mengurangi kualitas pelayanan (misalnya memberikan penjelasan yang sulit dipahami oleh pengguna non- teknis, kurang memberikan informasi mengenai perkembangan penanganan gangguan, atau menunjukkan ekspresi yang kurang ramah saat menerima pertanyaan pengguna)."
                            ],
                            [
                                "id" => 168,
                                "skor" => 1,
                                "deskripsi" => "Menunjukkan perilaku yang tidak menghargai pengguna atau menimbulkan keluhanterhadap pelayanan (misalnya berbicara dengan nada yang tidak pantas, mengabaikanpertanyaan pengguna, menyalahkan pengguna atas permasalahan yang terjadi, atau menolakmemberikan penjelasan yang diperlukan)."
                            ]
                        ]
                    ],
                    [
                        "id" => 43,
                        "title" => "Etika dan Tanggung Jawab Kerja",
                        "deskripsi" => "Menunjukkan sikap yang menjaga kepercayaan organisasi melalui kepatuhan terhadap etika kerja,menjaga kerahasiaan informasi, menggunakan akses dan fasilitas sesuai kewenangan, sertabertanggung jawab terhadap aset organisasi.",
                        "kelompok_jabatan" => "Technical Support",
                        "behaviorals" => [
                            [
                                "id" => 169,
                                "skor" => 4,
                                "deskripsi" => "Menunjukkan sikap yang menjaga kepercayaan organisasi dengan menggunakan data,sistem, hak akses, serta fasilitas kerja sesuai kewenangan tanpa ditemukan pelanggaran(misalnya menjaga kerahasiaan informasi, tidak menggunakan akun milik orang lain, tidakmenginstal perangkat lunak tanpa persetujuan, dan menggunakan peralatan kerja sesuaiperuntukannya)."
                            ],
                            [
                                "id" => 170,
                                "skor" => 3,
                                "deskripsi" => "Menunjukkan sikap yang menjaga etika kerja dan menggunakan data, sistem, maupunfasilitas sesuai ketentuan, namun masih ditemukan ketidaksesuaian administratif yang tidakmenimbulkan risiko terhadap organisasi (misalnya belum mengunci komputer saatmeninggalkan meja kerja dalam waktu singkat, belum memperbarui pencatatanpeminjaman peralatan, atau belum mengembalikan peralatan ke tempat penyimpanansetelah selesai digunakan)."
                            ],
                            [
                                "id" => 171,
                                "skor" => 2,
                                "deskripsi" => "Masih ditemukan perilaku yang kurang mencerminkan tanggung jawab terhadap keamanan informasi atau aset organisasi sehingga memerlukan pembinaan (misalnya membagikan akun kepada rekan kerja, menyimpan data kerja pada media yang tidak ditetapkan organisasi, atau menggunakan peralatan kerja untuk kepentingan pribadi tanpa izin)."
                            ],
                            [
                                "id" => 172,
                                "skor" => 1,
                                "deskripsi" => "Menunjukkan perilaku yang melanggar etika kerja atau menyalahgunakan kepercayaan organisasi sehingga berpotensi menimbulkan risiko terhadap keamanan informasi, aset,atau reputasi organisasi (misalnya membocorkan informasi yang bersifat rahasia,menyalahgunakan hak akses, atau menggunakan sistem untuk kepentingan yang tidaksesuai dengan tugasnya)."
                            ]
                        ]
                    ],
                    [
                        "id" => 52,
                        "title" => "Orientasi Pelayanan",
                        "deskripsi" => "Menunjukkan sikap yang memahami kebutuhan pengguna layanan, memberikan dukungankomunikasi secara profesional, serta berupaya menghasilkan dokumentasi dan materi komunikasiyang mendukung kebutuhan organisasi.",
                        "kelompok_jabatan" => "Media & Communication Support",
                        "behaviorals" => [
                            [
                                "id" => 205,
                                "skor" => 4,
                                "deskripsi" => "Menunjukkan sikap yang profesional, sopan, dan berorientasi pada kebutuhan pengguna, sertasecara aktif membantu memastikan kebutuhan dokumentasi atau publikasi terpenuhi(misalnya menawarkan alternatif pengambilan gambar ketika lokasi kurang mendukung, membantupengguna memilih foto yang paling sesuai untuk publikasi, menjelaskan alasan pemilihan visualatau narasi, atau menyesuaikan prioritas pekerjaan agar mendukung kebutuhan kegiatan organisasi)."
                            ],
                            [
                                "id" => 206,
                                "skor" => 3,
                                "deskripsi" => "Menunjukkan sikap yang profesional, sopan, dan membantu pengguna sesuai kebutuhan selamaproses peliputan maupun publikasi (misalnya memberikan informasi yang diminta dengan jelas,menyesuaikan permintaan pengguna selama masih sesuai ketentuan, serta menjaga komunikasiyang baik dengan narasumber dan unit kerja)."
                            ],
                            [
                                "id" => 207,
                                "skor" => 2,
                                "deskripsi" => "Menunjukkan sikap yang tetap sopan, namun belum memberikan perhatian yang memadai terhadapkebutuhan pengguna (misalnya hanya mengerjakan sesuai permintaan tanpa menggali kebutuhantambahan, kurang memberikan penjelasan ketika pengguna memerlukan informasi, atau kurangresponsif terhadap perubahan kebutuhan dokumentasi)."
                            ],
                            [
                                "id" => 208,
                                "skor" => 1,
                                "deskripsi" => "Menunjukkan sikap yang tidak mendukung kebutuhan pengguna sehingga menghambat prosespeliputan atau menimbulkan keluhan (misalnya mengabaikan permintaan yang masih menjadibagian dari penugasan, berkomunikasi dengan nada yang tidak profesional, atau menolakberkoordinasi tanpa alasan yang dapat dipertanggungjawabkan)."
                            ]
                        ]
                    ],
                    [
                        "id" => 53,
                        "title" => "Etika dan Tanggung Jawab Kerja",
                        "deskripsi" => "Menunjukkan sikap yang menjaga kepercayaan organisasi melalui perilaku yang jujur, menjagakerahasiaan informasi dan materi dokumentasi, menghormati hak penggunaan materikomunikasi, serta bertanggung jawab terhadap hasil kerja dan peralatan yang digunakan.",
                        "kelompok_jabatan" => "Media & Communication Support",
                        "behaviorals" => [
                            [
                                "id" => 209,
                                "skor" => 4,
                                "deskripsi" => "Menunjukkan sikap yang menjaga kepercayaan organisasi dengan memegang teguh etikaprofesi, menjaga kerahasiaan informasi, menggunakan materi komunikasi sesuaiketentuan, serta merawat seluruh peralatan kerja tanpa ditemukan pelanggaran(misalnya tidak membagikan foto atau video internal kepada pihak yang tidak berwenang,mempublikasikan materi hanya setelah memperoleh persetujuan, mencantumkan sumberinformasi dengan benar, serta menjaga kamera, drone, dan perangkat kerja dalam kondisibaik)."
                            ],
                            [
                                "id" => 210,
                                "skor" => 3,
                                "deskripsi" => "Menunjukkan sikap yang bertanggung jawab terhadap informasi, materi komunikasi, danperalatan kerja, namun masih terdapat kekurangan pada aspek pendukung yang tidakmenimbulkan risiko terhadap organisasi (misalnya arsip peralatan belum segera diperbarui,perlengkapan belum langsung dirapikan setelah digunakan, atau dokumentasi administrasipeminjaman alat belum dilengkapi)."
                            ],
                            [
                                "id" => 211,
                                "skor" => 2,
                                "deskripsi" => "Masih ditemukan perilaku yang kurang mencerminkan tanggung jawab terhadap informasi,materi komunikasi, atau aset organisasi sehingga memerlukan pembinaan(misalnya menyimpan file kerja pada media pribadi tanpa izin, lalai menjaga peralatansehingga memerlukan perbaikan ringan, atau menggunakan materi dokumentasi tanpamemastikan status persetujuan publikasinya)."
                            ],
                            [
                                "id" => 212,
                                "skor" => 1,
                                "deskripsi" => "Menunjukkan perilaku yang melanggar etika profesi atau menyalahgunakan kepercayaanorganisasi sehingga berpotensi merugikan organisasi atau menurunkan kredibilitasnya(misalnya membocorkan dokumentasi yang bersifat rahasia, mempublikasikan materitanpa persetujuan, memanipulasi informasi dalam berita, atau menyalahgunakan peralatanorganisasi untuk kepentingan pribadi tanpa izin)."
                            ]
                        ]
                    ],
                    [
                        "id" => 62,
                        "title" => "Orientasi Pelayanan",
                        "deskripsi" => "Menunjukkan komitmen untuk memahami kebutuhan pengguna layanan, membangun hubungankerja yang profesional, serta memastikan tim memberikan layanan sesuai standar organisasi.",
                        "kelompok_jabatan" => "Supervisory Support",
                        "behaviorals" => [
                            [
                                "id" => 245,
                                "skor" => 4,
                                "deskripsi" => "Secara konsisten menunjukkan kepedulian terhadap kebutuhan pengguna, mendengarkanmasukan, mengomunikasikan kebutuhan tersebut kepada tim, serta memastikan seluruhanggota memahami prioritas layanan. (Misalnya melakukan konfirmasi kebutuhansebelum kegiatan dimulai, menyampaikan perubahan kebutuhan kepada seluruh anggotatim, meminta umpan balik setelah layanan selesai, atau mengingatkan tim mengenai standarlayanan yang diharapkan pengguna.)"
                            ],
                            [
                                "id" => 246,
                                "skor" => 3,
                                "deskripsi" => "Menunjukkan kepedulian terhadap kebutuhan pengguna dan menyampaikan informasiyang diperlukan kepada tim selama pelaksanaan pekerjaan. (Misalnya menginformasikanperubahan kebutuhan kepada petugas, merespons masukan pengguna, atau menjelaskanprioritas pekerjaan kepada tim.)"
                            ],
                            [
                                "id" => 247,
                                "skor" => 2,
                                "deskripsi" => "Menanggapi kebutuhan pengguna setelah menerima permintaan atau keluhan tanpa secaraaktif memastikan seluruh anggota tim memahami perubahan kebutuhan.(Misalnya menyampaikan perubahan hanya kepada sebagian petugas atau baru membahaskebutuhan pengguna setelah muncul kendala di lapangan.)"
                            ],
                            [
                                "id" => 248,
                                "skor" => 1,
                                "deskripsi" => "Tidak menunjukkan kepedulian terhadap kebutuhan pengguna atau tidak menyampaikanperubahan kebutuhan kepada tim. (Misalnya mengabaikan masukan pengguna, tidakmeneruskan perubahan kebutuhan kepada petugas, atau tetap menggunakan pengaturanlama meskipun kebutuhan layanan telah berubah.)"
                            ]
                        ]
                    ],
                    [
                        "id" => 63,
                        "title" => "Integritas dan Objektivitas dalam Pengawasan",
                        "deskripsi" => "Menunjukkan sikap jujur, adil, konsisten, dan objektif dalam melakukan pengawasan,pembinaan, penilaian, serta penyampaian informasi kepada anggota tim maupun pihak terkait.",
                        "kelompok_jabatan" => "Supervisory Support",
                        "behaviorals" => [
                            [
                                "id" => 249,
                                "skor" => 4,
                                "deskripsi" => "Secara konsisten menunjukkan sikap jujur, adil, dan objektif dalam setiap pengawasan,pembinaan, maupun penilaian terhadap anggota tim. (Misalnya memberikan umpan balikberdasarkan hasil observasi, menerapkan aturan yang sama kepada seluruh anggota tim,menyampaikan fakta apa adanya dalam laporan, serta menjaga kerahasiaan informasipersonel.)"
                            ],
                            [
                                "id" => 250,
                                "skor" => 3,
                                "deskripsi" => "Menunjukkan sikap jujur dan objektif dalam pelaksanaan pengawasan sertamemperlakukan anggota tim sesuai ketentuan yang berlaku. (Misalnya memberikanpenilaian berdasarkan hasil kerja, menyampaikan temuan sesuai kondisi lapangan, ataumenindaklanjuti pelanggaran sesuai prosedur.)"
                            ],
                            [
                                "id" => 251,
                                "skor" => 2,
                                "deskripsi" => "Masih ditemukan sikap yang kurang konsisten dalam pengawasan atau pembinaansehingga memerlukan penguatan. (Misalnya memberikan perlakuan yang berbeda kepadaanggota tim pada kondisi yang sama, menyampaikan umpan balik tanpa didukung faktayang cukup, atau menunda tindak lanjut terhadap pelanggaran tanpa alasan yang jelas.)"
                            ],
                            [
                                "id" => 252,
                                "skor" => 1,
                                "deskripsi" => "Menunjukkan sikap yang tidak objektif atau tidak menjaga integritas dalam pengawasansehingga menurunkan kepercayaan anggota tim maupun pengguna layanan.(Misalnya memberikan penilaian berdasarkan kedekatan pribadi, menyembunyikantemuan yang seharusnya dilaporkan, membocorkan informasi personel yang bersifatrahasia, atau menerapkan aturan yang berbeda untuk kasus yang sama.)"
                            ]
                        ]
                    ]
                ]
            ],
            [
                "id" => 2,
                "title" => "Work Behavior",
                "slug" => "work-behavior",
                'bobot' => 40,
                "indikators" => [
                    [
                        "id" => 4,
                        "title" => "Pengelolaan Prioritas Administrasi Definisi",
                        "deskripsi" => "Kemampuan mengidentifikasi tingkat urgensi pekerjaan, menentukan prioritas penyelesaian,serta mengatur urutan pekerjaan sesuai kebutuhan organisasi.",
                        "kelompok_jabatan" => "BARS_Administrative Support",
                        "behaviorals" => [
                            [
                                "id" => 13,
                                "skor" => 4,
                                "deskripsi" => "Secara konsisten mengidentifikasi tingkat urgensi pekerjaan, menyusun urutan prioritas, serta menyesuaikan kembali prioritas ketika terjadi perubahan kebutuhan. (Misalnya menyusun daftar prioritas harian, segera menyesuaikan urutan pekerjaan ketika terdapat permintaan mendadak dari pimpinan, atau membedakan pekerjaan yang harus diselesaikan segera dengan yang dapat dijadwalkan kemudian.)"
                            ],
                            [
                                "id" => 14,
                                "skor" => 3,
                                "deskripsi" => "Menentukan prioritas pekerjaan sesuai kebutuhan utama serta melakukan penyesuaian ketika terdapat perubahan yang telah dikomunikasikan. (Misalnya menyusun urutan pekerjaan berdasarkan tenggat waktu atau mengubah urutan pekerjaan setelah menerima arahan baru.)"
                            ],
                            [
                                "id" => 15,
                                "skor" => 2,
                                "deskripsi" => "Menentukan prioritas pekerjaan sesuai kebutuhan utama serta melakukan penyesuaian ketika terdapat perubahan yang telah dikomunikasikan. (Misalnya menyusun urutan pekerjaan berdasarkan tenggat waktu atau mengubah urutan pekerjaan setelah menerima arahan baru.)"
                            ],
                            [
                                "id" => 16,
                                "skor" => 1,
                                "deskripsi" => "Tidak mampu menentukan prioritas pekerjaan secara jelas sehingga pekerjaan dikerjakan tanpa urutan yang sesuai. (Misalnya menyelesaikan pekerjaan berdasarkan urutan masuk tanpa mempertimbangkan urgensi, sering berpindah-pindah pekerjaan tanpa alasan yang jelas, atau menunggu arahan untuk setiap perubahan prioritas.)"
                            ]
                        ]
                    ],
                    [
                        "id" => 5,
                        "title" => "Stabilitas Kinerja dalam Tekanan Definisi",
                        "deskripsi" => "Kemampuan mempertahankan fokus, ketenangan, dan ritme kerja ketika menghadapi volumepekerjaan tinggi, perubahan prioritas, maupun tenggat waktu yang ketat.",
                        "kelompok_jabatan" => "BARS_Administrative Support",
                        "behaviorals" => [
                            [
                                "id" => 17,
                                "skor" => 4,
                                "deskripsi" => "Tetap bekerja secara tenang, fokus, dan terstruktur meskipun menghadapi volume pekerjaan tinggi, perubahan prioritas, atau tenggat waktu yang ketat. (Misalnya tetap menyelesaikan pekerjaan satu per satu sesuai prioritas, tetap mengikuti tahapan kerja yang biasa dilakukan saat menerima banyak revisi, atau tetap mampu menjelaskan status pekerjaan secara jelas ketika diminta secara mendadak.)"
                            ],
                            [
                                "id" => 18,
                                "skor" => 3,
                                "deskripsi" => "Tetap menjaga fokus dan ritme kerja pada sebagian besar kondisi kerja yang padat, meskipun sesekali memerlukan waktu untuk menyesuaikan diri terhadap perubahan. (Misalnya tetap menyelesaikan pekerjaan sesuai urutan setelah menerima perubahan prioritas atau kembali fokus setelah memperoleh klarifikasi tambahan.)"
                            ],
                            [
                                "id" => 19,
                                "skor" => 2,
                                "deskripsi" => "Mulai kehilangan fokus atau ritme kerja ketika menghadapi tekanan sehingga sering memerlukan arahan atau pengingat untuk kembali pada prioritas pekerjaan. (Misalnya mudah berpindah-pindah pekerjaan tanpa menyelesaikan yang sedang dikerjakan, tampak bingung ketika beberapa permintaan datang bersamaan, atau sering meminta konfirmasi ulang untuk pekerjaan yang telah dijelaskan.)"
                            ],
                            [
                                "id" => 20,
                                "skor" => 1,
                                "deskripsi" => "Tidak mampu mempertahankan fokus dan ritme kerja ketika menghadapi tekanan sehingga pelaksanaan pekerjaan menjadi tidak terarah. (Misalnya menghentikan pekerjaan yang sedang berjalan tanpa alasan yang jelas, panik ketika menerima beberapa permintaan sekaligus, atau tidak mampu menjelaskan pekerjaan mana yang sedang diprioritaskan.)"
                            ]
                        ]
                    ],
                    [
                        "id" => 6,
                        "title" => "Disiplin dalam Proses Administrasi Definisi",
                        "deskripsi" => "Kemampuan menjalankan setiap tahapan administrasi secara tertib, konsisten, dan sesuaiprosedur yang berlaku.",
                        "kelompok_jabatan" => "BARS_Administrative Support",
                        "behaviorals" => [
                            [
                                "id" => 21,
                                "skor" => 4,
                                "deskripsi" => "Secara konsisten menjalankan setiap tahapan administrasi sesuai prosedur tanpa mengabaikan langkah yang telah ditetapkan. (Misalnya selalu menggunakan format resmi organisasi, mengikuti alur persetujuan sebelum dokumen diterbitkan, menerapkan penamaan file sesuai standar, serta menyimpan dokumen pada lokasi dan klasifikasi yang telah ditentukan.)"
                            ],
                            [
                                "id" => 22,
                                "skor" => 3,
                                "deskripsi" => "Menjalankan tahapan administrasi sesuai prosedur pada pekerjaan utama, namun masih terdapat langkah pendukung yang sesekali perlu diingatkan. (Misalnya telah menggunakan format resmi tetapi sesekali lupa memperbarui kode dokumen atau belum langsung menyimpan dokumen pada folder yang sesuai.)"
                            ],
                            [
                                "id" => 23,
                                "skor" => 2,
                                "deskripsi" => "Menjalankan sebagian tahapan administrasi, namun masih sering melewati langkah tertentu sehingga memerlukan pengingat atau koreksi. (Misalnya melewati proses pengecekan administrasi, tidak menggunakan format terbaru, atau menyimpan dokumen pada lokasi yang tidak sesuai sebelum diperbaiki.)"
                            ],
                            [
                                "id" => 24,
                                "skor" => 1,
                                "deskripsi" => "Tidak menjalankan tahapan administrasi sesuai prosedur sehingga pola kerja menjadi tidak konsisten. (Misalnyamenggunakan format yang tidak sesuai, melewati alur persetujuan yang diwajibkan, atau mengabaikan sistem klasifikasi dokumen yang telah ditetapkan.)"
                            ]
                        ]
                    ],
                    [
                        "id" => 7,
                        "title" => "Koordinasi Administratif Definisi",
                        "deskripsi" => "Kemampuan membangun komunikasi dan koordinasi yang efektif dengan pihak terkait untukmemperoleh, mengklarifikasi, menyampaikan, dan menindaklanjuti informasi administrasi.",
                        "kelompok_jabatan" => "BARS_Administrative Support",
                        "behaviorals" => [
                            [
                                "id" => 25,
                                "skor" => 4,
                                "deskripsi" => "Secara proaktif melakukan komunikasi, klarifikasi, dan tindak lanjut dengan seluruh pihak yang terkait untuk memastikan informasi administrasi dipahami dan diteruskan sesuai kebutuhan. (Misalnya menghubungi unit terkait ketika data belum lengkap, melakukan konfirmasi ulang terhadap informasi yang belum jelas, menyampaikan perubahan kepada seluruh pihak yang berkepentingan, atau melakukan tindak lanjut tanpa menunggu pengingat.)"
                            ],
                            [
                                "id" => 26,
                                "skor" => 3,
                                "deskripsi" => "Melakukan komunikasi dan koordinasi sesuai kebutuhan pekerjaan serta menindaklanjuti informasi setelah menerima permintaan atau arahan. (Misalnya meminta klarifikasi ketika terdapat data yang belum lengkap atau menyampaikan perubahan kepada pihak terkait setelah memperoleh informasi.)"
                            ],
                            [
                                "id" => 27,
                                "skor" => 2,
                                "deskripsi" => "Melakukan koordinasi hanya pada sebagian kebutuhan pekerjaan sehingga masih memerlukan arahan atau pengingat untuk memperoleh maupun menyampaikan informasi yang diperlukan. (Misalnya menunggu diminta sebelum melakukan tindak lanjut atau hanya menghubungi sebagian pihak yang terkait.)"
                            ],
                            [
                                "id" => 28,
                                "skor" => 1,
                                "deskripsi" => "Tidak melakukan komunikasi atau koordinasi yang diperlukan sehingga informasi administrasi tidak tersampaikan atau tidak diklarifikasi sebagaimana mestinya. (Misalnya tidak melakukan konfirmasi terhadap data yang belum jelas, tidak menyampaikan perubahan kepada pihak terkait, atau tidak melakukan tindak lanjut terhadap permintaan administrasi.)"
                            ]
                        ]
                    ],
                    [
                        "id" => 14,
                        "title" => "Inisiatif dalam Menyiapkan Layanan Definisi",
                        "deskripsi" => "Kemampuan mengambil tindakan untuk menyiapkan kebutuhan layanan tanpa menungguinstruksi ketika melihat adanya kebutuhan atau potensi gangguan terhadap kelancaran pelayanan.",
                        "kelompok_jabatan" => "BARS_Hospitality & Service Support",
                        "behaviorals" => [
                            [
                                "id" => 53,
                                "skor" => 4,
                                "deskripsi" => "Mengambil tindakan secara mandiri untuk menyiapkan kebutuhan layanan sebelum diminta sehingga kebutuhan pengguna telah tersedia ketika layanan diberikan (misalnya menambah kursi karena jumlah peserta bertambah, menyiapkan konsumsi tambahan karena acara diperpanjang, atau menyiapkan badge tamu sebelum tamu datang)."
                            ],
                            [
                                "id" => 54,
                                "skor" => 3,
                                "deskripsi" => "Mengambil tindakan setelah terdapat indikasi yang jelas bahwa kebutuhan layanan berubah, sehingga layanan tetap dapat diberikan tanpa mengganggu kelancaran kegiatan (misalnya menambah kursi setelah menerima informasi penambahan peserta atau menyesuaikan layout setelah menerima perubahan susunan acara)."
                            ],
                            [
                                "id" => 55,
                                "skor" => 2,
                                "deskripsi" => "Menunggu instruksi untuk menyiapkan kebutuhan layanan yang sebenarnya sudah dapat dikenali dari kondisi di lapangan (misalnya baru menambah kursi setelah diminta, baru menyiapkan konsumsi setelah pengguna menyampaikan kekurangan, atau baru membuka meja pelayanan setelah antrean panjang terbentuk)."
                            ],
                            [
                                "id" => 56,
                                "skor" => 1,
                                "deskripsi" => "Tidak mengambil tindakan meskipun kebutuhan layanan telah terlihat sehingga mengakibatkan pelayanan terganggu atau pengguna harus meminta secara langsung."
                            ]
                        ]
                    ],
                    [
                        "id" => 15,
                        "title" => "Kepatuhan terhadap Standar Layanan Definisi",
                        "deskripsi" => "Kemampuan melaksanakan pelayanan sesuai standar pelayanan, protokol, tata cara pelayanan,dan ketentuan kerja yang berlaku.",
                        "kelompok_jabatan" => "BARS_Hospitality & Service Support",
                        "behaviorals" => [
                            [
                                "id" => 57,
                                "skor" => 4,
                                "deskripsi" => "Melaksanakan seluruh tahapan pelayanan sesuai standar layanan, protokol, dan ketentuankerja tanpa ditemukan penyimpangan terhadap prosedur yang berlaku."
                            ],
                            [
                                "id" => 58,
                                "skor" => 3,
                                "deskripsi" => "Melaksanakan seluruh tahapan utama pelayanan sesuai standar, namun masih terdapatketidaksesuaian administratif atau pendukung yang tidak memengaruhi kualitas pelayanan(misalnya checklist pelayanan belum dilengkapi, penempatan label internal belumdiperbarui, atau pencatatan penggunaan perlengkapan belum dilakukan)."
                            ],
                            [
                                "id" => 59,
                                "skor" => 2,
                                "deskripsi" => "Tidak melaksanakan sebagian prosedur pelayanan yang bersifat wajib sehingga berpotensimemengaruhi kualitas atau ketertiban pelayanan (misalnya tidak mengikuti prosedurregistrasi tamu, tidak menggunakan atribut pelayanan sesuai ketentuan, atau tidakmengikuti urutan penyajian yang telah ditetapkan)."
                            ],
                            [
                                "id" => 60,
                                "skor" => 1,
                                "deskripsi" => "Mengabaikan standar pelayanan atau protokol kerja sehingga menimbulkan gangguanterhadap pelayanan atau mencederai citra organisasi."
                            ]
                        ]
                    ],
                    [
                        "id" => 16,
                        "title" => "Ketelitian dalam Pelaksanaan Layanan Definisi",
                        "deskripsi" => "Kemampuan memeriksa setiap detail layanan untuk memastikan seluruh kebutuhan telah sesuaisebelum layanan diberikan kepada pengguna.",
                        "kelompok_jabatan" => "BARS_Hospitality & Service Support",
                        "behaviorals" => [
                            [
                                "id" => 61,
                                "skor" => 4,
                                "deskripsi" => "Memeriksa seluruh elemen layanan sebelum pelayanan diberikan sehingga tidak ditemukankesalahan atau kekurangan pada saat layanan berlangsung (misalnya jumlah kursi sesuai,konsumsi lengkap, identitas tamu benar, layout sesuai, dan perlengkapan rapat telahdiperiksa)."
                            ],
                            [
                                "id" => 62,
                                "skor" => 3,
                                "deskripsi" => "Memeriksa seluruh elemen utama layanan, namun masih terdapat detail pendukung yangbelum sesuai dan tidak memengaruhi kelancaran pelayanan (misalnya posisi alat tulisbelum rapi, susunan brosur belum seragam, atau perlengkapan pendukung belumditempatkan sesuai standar estetika)."
                            ],
                            [
                                "id" => 63,
                                "skor" => 2,
                                "deskripsi" => "Tidak memeriksa sebagian elemen utama layanan sehingga ditemukan kesalahan ataukekurangan yang harus diperbaiki ketika pelayanan berlangsung (misalnya jumlah kursikurang, konsumsi tidak sesuai, identitas tamu keliru, atau perlengkapan rapat belumlengkap)."
                            ],
                            [
                                "id" => 64,
                                "skor" => 1,
                                "deskripsi" => "Tidak melakukan pemeriksaan terhadap kebutuhan layanan sehingga terjadi kesalahanyang mengganggu pelayanan atau menimbulkan keluhan pengguna."
                            ]
                        ]
                    ],
                    [
                        "id" => 17,
                        "title" => "Koordinasi dalam Pelaksanaan Layanan Definisi",
                        "deskripsi" => "Kemampuan membangun koordinasi dengan pihak terkait untuk memastikan seluruh kebutuhanpelayanan terpenuhi sesuai peran dan tanggung jawab masing-masing.",
                        "kelompok_jabatan" => "BARS_Hospitality & Service Support",
                        "behaviorals" => [
                            [
                                "id" => 65,
                                "skor" => 4,
                                "deskripsi" => "Melakukan koordinasi secara lengkap dengan seluruh pihak yang terkait sehingga setiapkebutuhan pelayanan dipahami dan dipenuhi sesuai peran masing-masing(misalnya mengoordinasikan perubahan jadwal dengan sekretariat, perubahan layoutdengan tata tempat, kebutuhan perangkat dengan teknisi, dan alur tamu dengan petugaskeamanan)."
                            ],
                            [
                                "id" => 66,
                                "skor" => 3,
                                "deskripsi" => "Melakukan koordinasi dengan pihak yang terkait sehingga pelayanan dapat berjalan denganlancar, namun masih terdapat informasi pendukung yang belum tersampaikan secaralengkap dan tidak memengaruhi pelaksanaan pelayanan (misalnya informasi mengenaiperubahan konsumsi atau perlengkapan tambahan disampaikan setelah koordinasi utamaselesai)."
                            ],
                            [
                                "id" => 67,
                                "skor" => 2,
                                "deskripsi" => "Koordinasi belum dilakukan kepada seluruh pihak yang terkait sehingga terjadiketidaksesuaian dalam pelaksanaan pelayanan (misalnya perubahan jumlah peserta tidakdisampaikan kepada petugas tata tempat, perubahan jadwal tidak diinformasikan kepadapenyedia konsumsi, atau kebutuhan perangkat tidak dikomunikasikan kepada teknisi)."
                            ],
                            [
                                "id" => 68,
                                "skor" => 1,
                                "deskripsi" => "Tidak melakukan koordinasi dengan pihak yang terkait sehingga pelayanan terganggu ataukebutuhan pengguna tidak dapat dipenuhi."
                            ]
                        ]
                    ],
                    [
                        "id" => 24,
                        "title" => "Inisiatif dalam Mendukung Layanan Transportasi",
                        "deskripsi" => "Kemampuan mengambil tindakan secara mandiri untuk menjaga kelancaran layanantransportasi tanpa menunggu instruksi ketika melihat adanya kebutuhan atau potensigangguan terhadap perjalanan.",
                        "kelompok_jabatan" => "Transportation Support",
                        "behaviorals" => [
                            [
                                "id" => 93,
                                "skor" => 4,
                                "deskripsi" => "Mengambil tindakan sebelum diminta sehingga potensi gangguan terhadap pelayanan dapat dicegah(misalnya menyiapkan kendaraan pengganti ketika mengetahui kendaraan pimpinan mengalamigangguan, memilih rute alternatif sebelum memasuki titik kemacetan, atau mengisi BBM kendaraandistribusi sebelum penugasan dimulai sehingga pengiriman tetap berjalan sesuai jadwal)."
                            ],
                            [
                                "id" => 94,
                                "skor" => 3,
                                "deskripsi" => "Mengambil tindakan setelah memperoleh informasi yang jelas mengenai perubahan kondisi sehinggapelayanan tetap dapat berjalan tanpa mengganggu pengguna (misalnya mengganti rute setelahmenerima informasi kemacetan dari petugas, menyiapkan kendaraan setelah menerima perubahanjadwal kegiatan, atau menyesuaikan urutan pengiriman setelah menerima perubahan lokasi tujuan)."
                            ],
                            [
                                "id" => 95,
                                "skor" => 2,
                                "deskripsi" => "Menunggu instruksi untuk melakukan tindakan yang sebenarnya sudah dapat dikenali dari kondisi dilapangan (misalnya baru mengisi BBM setelah diminta, baru mencari rute alternatif setelah penggunamengeluhkan kemacetan, atau baru menyiapkan kendaraan cadangan setelah pengguna menunggu)."
                            ],
                            [
                                "id" => 96,
                                "skor" => 1,
                                "deskripsi" => "Tidak mengambil tindakan meskipun kebutuhan atau potensi gangguan telah terlihat sehinggapelayanan terganggu (misalnya tetap menggunakan kendaraan yang diketahui bermasalah, tidakmencari alternatif ketika jalan ditutup, atau membiarkan kendaraan kehabisan BBM saat penugasanberlangsung)."
                            ]
                        ]
                    ],
                    [
                        "id" => 25,
                        "title" => "Kepatuhan terhadap Standar Operasional Transportasi",
                        "deskripsi" => "Kemampuan melaksanakan pelayanan transportasi sesuai prosedur",
                        "kelompok_jabatan" => "Transportation Support",
                        "behaviorals" => [
                            [
                                "id" => 97,
                                "skor" => 4,
                                "deskripsi" => "Melaksanakan seluruh tahapan pelayanan sesuai prosedur operasional, ketentuankeselamatan, dan peraturan lalu lintas tanpa ditemukan penyimpangan(misalnya melakukan pemeriksaan kendaraan sebelum digunakan, menggunakan sabukpengaman, mematuhi batas kecepatan, serta melaksanakan serah terima kendaraan sesuaiprosedur)."
                            ],
                            [
                                "id" => 98,
                                "skor" => 3,
                                "deskripsi" => "Melaksanakan seluruh prosedur utama, namun masih terdapat langkah pendukung yangbelum dilakukan dan tidak memengaruhi keselamatan maupun pelayanan(misalnya checklist kendaraan belum ditandatangani, pencatatan kilometer belumdiperbarui, atau bukti pengisian BBM belum dilampirkan)."
                            ],
                            [
                                "id" => 99,
                                "skor" => 2,
                                "deskripsi" => "Tidak melaksanakan salah satu prosedur wajib sehingga meningkatkan kemungkinanterjadinya gangguan pelayanan (misalnya tidak melakukan pemeriksaan kendaraansebelum digunakan, tidak menggunakan sabuk pengaman, atau tidak mengikuti prosedurserah terima kendaraan)."
                            ],
                            [
                                "id" => 100,
                                "skor" => 1,
                                "deskripsi" => "Mengabaikan prosedur operasional atau ketentuan keselamatan sehingga menyebabkangangguan pelayanan, membahayakan pengguna, atau merugikan organisasi(misalnya menerobos lampu merah, mengemudi melebihi batas kecepatan, ataumenggunakan kendaraan yang dinyatakan tidak layak jalan)."
                            ]
                        ]
                    ],
                    [
                        "id" => 26,
                        "title" => "Ketelitian dalam Pelaksanaan Layanan Transportasi",
                        "deskripsi" => "Kemampuan memeriksa seluruh elemen perjalanan untuk memastikan layanantransportasi dilaksanakan sesuai kebutuhan pengguna.",
                        "kelompok_jabatan" => "Transportation Support",
                        "behaviorals" => [
                            [
                                "id" => 101,
                                "skor" => 4,
                                "deskripsi" => "Memeriksa seluruh elemen perjalanan sebelum keberangkatan sehingga tidak ditemukankesalahan selama pelayanan (misalnya memastikan lokasi penjemputan pimpinan benar,dokumen yang akan dikirim sesuai daftar distribusi, tujuan perjalanan sesuai surat tugas,dan dokumen kendaraan telah dibawa)."
                            ],
                            [
                                "id" => 102,
                                "skor" => 3,
                                "deskripsi" => "Memeriksa seluruh elemen utama perjalanan, namun masih terdapat detail pendukung yangbelum sesuai dan tidak memengaruhi pelayanan (misalnya urutan pemberhentian belumdiperbarui pada catatan perjalanan atau dokumen pendukung belum disusun sesuai urutankunjungan)."
                            ],
                            [
                                "id" => 103,
                                "skor" => 2,
                                "deskripsi" => "Tidak memeriksa salah satu elemen utama sehingga terjadi kesalahan yang harus diperbaikisaat pelayanan berlangsung (misalnya lokasi penjemputan keliru, dokumen yang harusdikirim tertinggal, tujuan perjalanan salah sebelum dikoreksi, atau STNK kendaraan belumdibawa)."
                            ],
                            [
                                "id" => 104,
                                "skor" => 1,
                                "deskripsi" => "Tidak melakukan pemeriksaan sehingga terjadi kesalahan yang menghambat pelayananatau menimbulkan keluhan pengguna (misalnya pengguna tidak terjemput di lokasi yangbenar, dokumen dikirim ke unit yang salah, atau kendaraan tidak dapat melanjutkanperjalanan karena dokumen wajib tidak tersedia)."
                            ]
                        ]
                    ],
                    [
                        "id" => 27,
                        "title" => "Koordinasi dalam Pelaksanaan Layanan Transportasi",
                        "deskripsi" => "Kemampuan membangun koordinasi dengan pihak terkait untuk memastikan pelayanantransportasi berjalan lancar sesuai kebutuhan.",
                        "kelompok_jabatan" => "Transportation Support",
                        "behaviorals" => [
                            [
                                "id" => 105,
                                "skor" => 4,
                                "deskripsi" => "Melakukan koordinasi secara lengkap dengan seluruh pihak terkait sehingga seluruhkebutuhan perjalanan dipahami dan dipenuhi sesuai peran masing-masing(misalnya mengonfirmasi perubahan jadwal dengan sekretariat, mengoordinasikankendaraan pengganti dengan pool kendaraan, menginformasikan perubahan titikpenjemputan kepada pengguna, atau berkoordinasi dengan keamanan terkait akses masuklokasi)."
                            ],
                            [
                                "id" => 106,
                                "skor" => 3,
                                "deskripsi" => "Melakukan koordinasi dengan pihak terkait sehingga pelayanan tetap berjalan lancar,namun masih terdapat informasi pendukung yang terlambat disampaikan dan tidakmemengaruhi pelayanan (misalnya informasi rute alternatif disampaikan setelahperjalanan dimulai atau informasi lokasi parkir diberikan sesaat sebelum tiba di tujuan)."
                            ],
                            [
                                "id" => 107,
                                "skor" => 2,
                                "deskripsi" => "Tidak mengoordinasikan salah satu informasi penting sehingga terjadi ketidaksesuaiandalam pelaksanaan pelayanan (misalnya perubahan jadwal tidak dikonfirmasi kepadapengguna, kendaraan pengganti tidak dikoordinasikan dengan pool, atau perubahan lokasipengiriman tidak disampaikan kepada penerima)."
                            ],
                            [
                                "id" => 108,
                                "skor" => 1,
                                "deskripsi" => "Tidak melakukan koordinasi dengan pihak terkait sehingga pelayanan terganggu ataukebutuhan pengguna tidak dapat dipenuhi (misalnya pengguna menunggu karenaperubahan jadwal tidak diinformasikan, kendaraan tidak tersedia karena tidak berkoordinasidengan pool, atau dokumen gagal diterima karena penerima tidak diberi informasi)."
                            ]
                        ]
                    ],
                    [
                        "id" => 38,
                        "title" => "Kepatuhan terhadap Prosedur Teknis",
                        "deskripsi" => "Kemampuan melaksanakan pekerjaan sesuai prosedur teknis, standar operasional, ketentuankeselamatan kerja, serta kebijakan keamanan yang berlaku.",
                        "kelompok_jabatan" => "Technical Support",
                        "behaviorals" => [
                            [
                                "id" => 149,
                                "skor" => 4,
                                "deskripsi" => "Melaksanakan seluruh tahapan pekerjaan sesuai prosedur teknis tanpa ditemukan tahapanyang diabaikan maupun pelanggaran terhadap SOP, standar keselamatan kerja, ataukebijakan keamanan."
                            ],
                            [
                                "id" => 150,
                                "skor" => 3,
                                "deskripsi" => "Melaksanakan seluruh tahapan utama pekerjaan sesuai prosedur, namun masih terdapatketidaksesuaian administratif yang tidak memengaruhi kualitas maupun keamananpekerjaan (misalnya checklist pekerjaan belum dilengkapi, changelog aplikasi belumdiperbarui, dokumentasi konfigurasi belum diperbarui, atau pelabelan perangkat belumdilakukan)."
                            ],
                            [
                                "id" => 151,
                                "skor" => 2,
                                "deskripsi" => "Tidak melaksanakan sebagian prosedur teknis yang bersifat wajib sehingga meningkatkanrisiko terhadap kualitas atau keamanan pekerjaan (misalnya tidak melakukan backupsebelum deployment/perubahan sistem, tidak melakukan testing setelah konfigurasi atauperbaikan, atau tidak menggunakan APD pada pekerjaan yang mewajibkannya)."
                            ],
                            [
                                "id" => 152,
                                "skor" => 1,
                                "deskripsi" => "Mengabaikan prosedur teknis, standar keselamatan kerja, atau kebijakan keamanansehingga pekerjaan berisiko menyebabkan gangguan layanan, kerusakan sistem/fasilitas,atau kecelakaan kerja."
                            ]
                        ]
                    ],
                    [
                        "id" => 39,
                        "title" => "Kepekaan terhadap Potensi Gangguan Teknis",
                        "deskripsi" => "Kemampuan mengenali gejala awal atau kondisi yang berpotensi menimbulkan gangguan padasistem, aplikasi, perangkat, jaringan, maupun fasilitas teknis sebelum berdampak terhadapoperasional.",
                        "kelompok_jabatan" => "Technical Support",
                        "behaviorals" => [
                            [
                                "id" => 153,
                                "skor" => 4,
                                "deskripsi" => "Mengidentifikasi seluruh kondisi yang berpotensi menimbulkan gangguan danmenyampaikan temuan sesuai prosedur sebelum berdampak pada layanan(misalnya menemukan peningkatan log error aplikasi, kapasitas storage hampir penuh,koneksi jaringan mulai tidak stabil, atau suara mesin/AC yang tidak normal)."
                            ],
                            [
                                "id" => 154,
                                "skor" => 3,
                                "deskripsi" => "Mengidentifikasi kondisi yang memerlukan perhatian sehingga dapat segera ditindaklanjutisebelum berkembang menjadi gangguan operasional (misalnya aplikasi mulai melambat,penggunaan memori server meningkat, kualitas jaringan menurun, atau performa AC mulaiberkurang)."
                            ],
                            [
                                "id" => 155,
                                "skor" => 2,
                                "deskripsi" => "Hanya mengidentifikasi sebagian kondisi yang memerlukan perhatian sehingga masihterdapat potensi gangguan yang baru diketahui setelah mulai memengaruhi layanan(misalnya bug baru diketahui setelah pengguna melapor, server mencapai kapasitasmaksimum, jaringan terputus, atau kerusakan pompa baru diketahui setelah menggangguoperasional)."
                            ],
                            [
                                "id" => 156,
                                "skor" => 1,
                                "deskripsi" => "Tidak mengenali kondisi yang memerlukan perhatian sehingga gangguan baru diketahuisetelah menghambat operasional atau menyebabkan kerusakan yang lebih besar."
                            ]
                        ]
                    ],
                    [
                        "id" => 40,
                        "title" => "Analisis dan Penyelesaian Permasalahan Teknis",
                        "deskripsi" => "Kemampuan menganalisis penyebab permasalahan teknis, menentukan solusi berdasarkan akarpenyebab, serta melakukan eskalasi apabila permasalahan berada di luar kewenangannya.",
                        "kelompok_jabatan" => "Technical Support",
                        "behaviorals" => [
                            [
                                "id" => 157,
                                "skor" => 4,
                                "deskripsi" => "Melakukan diagnosis secara sistematis, mengidentifikasi akar penyebab, menentukansolusi berdasarkan hasil analisis, serta melakukan eskalasi apabila diperlukan sesuaikewenangan (misalnya menganalisis log aplikasi sebelum memperbaiki bug, melakukantroubleshooting jaringan sebelum mengganti perangkat, atau melakukan pemeriksaankomponen sebelum mengganti unit mesin)."
                            ],
                            [
                                "id" => 158,
                                "skor" => 3,
                                "deskripsi" => "Melakukan diagnosis dan menentukan solusi berdasarkan penyebab utama permasalahan,namun masih terdapat analisis pendukung atau dokumentasi troubleshooting yang belumlengkap (misalnya akar penyebab telah ditemukan tetapi analisis log, hasil pengujian, ataudokumentasi troubleshooting belum dilengkapi)."
                            ],
                            [
                                "id" => 159,
                                "skor" => 2,
                                "deskripsi" => "Analisis belum mengidentifikasi seluruh penyebab permasalahan sehingga solusi hanyamengatasi sebagian masalah (misalnya hanya melakukan restart aplikasi/server tanpamenelusuri penyebab error, mengganti perangkat tanpa memastikan sumber kerusakan,atau memperbaiki gejala pada mesin tanpa mengidentifikasi komponen penyebabnya)."
                            ],
                            [
                                "id" => 160,
                                "skor" => 1,
                                "deskripsi" => "Melakukan tindakan tanpa diagnosis yang memadai atau tidak melakukan eskalasi ketikadiperlukan sehingga permasalahan tidak terselesaikan atau kembali berulang."
                            ]
                        ]
                    ],
                    [
                        "id" => 41,
                        "title" => "Ketelitian dalam Pelaksanaan Pekerjaan Teknis",
                        "deskripsi" => "Kemampuan memperhatikan setiap detail pekerjaan untuk memastikan seluruh tahapanpelaksanaan, pengujian, dan pemeriksaan akhir dilakukan secara lengkap.",
                        "kelompok_jabatan" => "Technical Support",
                        "behaviorals" => [
                            [
                                "id" => 161,
                                "skor" => 4,
                                "deskripsi" => "Memastikan seluruh tahapan pekerjaan, pengujian, dan pemeriksaan akhir telah dilakukan sehinggatidak ditemukan langkah penting yang terlewat sebelum pekerjaan dinyatakan selesai(misalnya seluruh fitur aplikasi telah diuji, konfigurasi jaringan telah diverifikasi, serta fungsi ACatau panel listrik telah dipastikan berjalan normal sebelum diserahterimakan)."
                            ],
                            [
                                "id" => 162,
                                "skor" => 3,
                                "deskripsi" => "Melaksanakan seluruh tahapan utama pekerjaan, namun masih terdapat detail pendukung yangbelum lengkap dan tidak memengaruhi hasil pekerjaan (misalnya penamaan file belum sesuaistandar, label kabel belum diperbarui, atau penataan tools belum sesuai lokasi penyimpanan)."
                            ],
                            [
                                "id" => 163,
                                "skor" => 2,
                                "deskripsi" => "Masih terdapat tahapan penting yang terlewat sehingga pekerjaan memerlukan pemeriksaan atauperbaikan ulang (misalnya tidak melakukan user acceptance test (UAT) setelah perubahan aplikasi,tidak melakukan pengujian konektivitas setelah konfigurasi jaringan, atau tidak melakukan ujifungsi setelah perbaikan AC/panel listrik)."
                            ],
                            [
                                "id" => 164,
                                "skor" => 1,
                                "deskripsi" => "Mengabaikan detail penting dalam pelaksanaan pekerjaan sehingga meningkatkan risiko gangguan,kesalahan, atau pekerjaan harus diulang kembali."
                            ]
                        ]
                    ],
                    [
                        "id" => 48,
                        "title" => "Inisiatif dan Antisipasi dalam Pelaksanaan Peliputan dan Produksi Materi",
                        "deskripsi" => "Kemampuan mengenali kebutuhan, mengantisipasi perubahan situasi, serta mengambil tindakansecara mandiri untuk memastikan proses peliputan dan produksi materi komunikasi tetapberjalan lancar tanpa menunggu instruksi.",
                        "kelompok_jabatan" => "Media & Communication Support",
                        "behaviorals" => [
                            [
                                "id" => 189,
                                "skor" => 4,
                                "deskripsi" => "Mengenali kebutuhan dan mengantisipasi perubahan situasi sebelum terjadi sehingga prosespeliputan maupun produksi materi tetap berjalan tanpa gangguan (misalnya berpindah posisisebelum momen penandatanganan berlangsung untuk mendapatkan sudut terbaik, menyiapkanbaterai dan memory card cadangan sebelum kegiatan dimulai, atau menyiapkan informasipendukung sebelum menyusun berita)."
                            ],
                            [
                                "id" => 190,
                                "skor" => 3,
                                "deskripsi" => "Mengambil tindakan setelah memperoleh informasi atau melihat perubahan situasi sehingga prosespeliputan tetap berjalan dengan baik (misalnya berpindah posisi setelah mengetahui perubahansusunan acara, mengganti peralatan setelah mengetahui adanya gangguan teknis, atau menyesuaikanfokus peliputan setelah memperoleh arahan tambahan)."
                            ],
                            [
                                "id" => 191,
                                "skor" => 2,
                                "deskripsi" => "Menunggu arahan untuk melakukan tindakan yang sebenarnya sudah dapat diperkirakan darikondisi di lapangan (misalnya baru menyiapkan baterai setelah kamera mati, baru berpindah posisisetelah momen terhalang, atau baru mencari informasi tambahan setelah editor menemukan datayang belum lengkap)."
                            ],
                            [
                                "id" => 192,
                                "skor" => 1,
                                "deskripsi" => "Tidak mengenali kebutuhan maupun perubahan situasi sehingga proses peliputan atau produksimateri terganggu (misalnya kehilangan momen penting karena tetap berada di posisi yang tidaktepat, kehabisan media penyimpanan saat kegiatan berlangsung, atau tidak menyiapkan alternatifketika cuaca berubah)."
                            ]
                        ]
                    ],
                    [
                        "id" => 49,
                        "title" => "Kepatuhan terhadap Standar Produksi dan Publikasi",
                        "deskripsi" => "Kemampuan melaksanakan peliputan, produksi, penyuntingan, dan publikasi sesuai standarteknis, SOP, etika dokumentasi, etika jurnalistik, serta ketentuan komunikasi organisasi.",
                        "kelompok_jabatan" => "Media & Communication Support",
                        "behaviorals" => [
                            [
                                "id" => 193,
                                "skor" => 4,
                                "deskripsi" => "Melaksanakan seluruh tahapan peliputan, produksi, dan publikasi sesuai standar teknis,prosedur kerja, serta etika yang berlaku tanpa ditemukan penyimpangan(misalnya mengikuti SOP peliputan, mematuhi regulasi pengoperasian drone,menggunakan format visual organisasi, serta tidak mempublikasikan materi sebelummemperoleh persetujuan)."
                            ],
                            [
                                "id" => 194,
                                "skor" => 3,
                                "deskripsi" => "Melaksanakan seluruh prosedur utama, namun masih terdapat langkah pendukung yangbelum dilakukan dan tidak memengaruhi kualitas maupun keamanan publikasi(misalnya metadata file belum dilengkapi, template penamaan file belum sepenuhnyasesuai, atau dokumentasi pendukung belum langsung diarsipkan)."
                            ],
                            [
                                "id" => 195,
                                "skor" => 2,
                                "deskripsi" => "Tidak melaksanakan salah satu prosedur wajib sehingga meningkatkan risiko terhadapkualitas, keamanan, atau kelancaran publikasi (misalnya tidak mengikuti prosedurpersetujuan publikasi, tidak melakukan backup sebelum editing lanjutan, ataumengoperasikan drone tanpa melakukan pengecekan awal sesuai prosedur)."
                            ],
                            [
                                "id" => 196,
                                "skor" => 1,
                                "deskripsi" => "Mengabaikan standar produksi, etika, atau prosedur publikasi sehingga mengakibatkanpelanggaran, kerugian, atau menurunkan kredibilitas organisasi (misalnya menyebarkanmateri sebelum mendapat izin, melanggar regulasi penerbangan drone, ataumempublikasikan informasi yang belum terverifikasi)."
                            ]
                        ]
                    ],
                    [
                        "id" => 50,
                        "title" => "Ketelitian dalam Produksi Materi Komunikasi",
                        "deskripsi" => "Kemampuan memeriksa seluruh aspek teknis maupun substansi hasil kerja untuk memastikanmateri komunikasi bebas dari kesalahan sebelum diserahkan atau dipublikasikan.",
                        "kelompok_jabatan" => "Media & Communication Support",
                        "behaviorals" => [
                            [
                                "id" => 197,
                                "skor" => 4,
                                "deskripsi" => "Memeriksa seluruh aspek teknis dan substansi sebelum materi diserahkan sehingga tidakditemukan kesalahan pada hasil akhir (misalnya nama dan jabatan narasumber benar, faktatelah diverifikasi, foto bebas dari kesalahan teknis, video telah diperiksa kualitas audio-visualnya, serta naskah bebas dari kesalahan ejaan)."
                            ],
                            [
                                "id" => 198,
                                "skor" => 3,
                                "deskripsi" => "Memeriksa seluruh aspek utama, namun masih terdapat kekurangan pada elemenpendukung yang tidak memengaruhi substansi maupun kelayakan publikasi(misalnya metadata belum lengkap, caption alternatif belum disiapkan, atau terdapatkesalahan tanda baca yang tidak mengubah makna)."
                            ],
                            [
                                "id" => 199,
                                "skor" => 2,
                                "deskripsi" => "Tidak memeriksa salah satu aspek utama sehingga ditemukan kesalahan yang memerlukanrevisi sebelum dipublikasikan (misalnya nama pejabat salah, audio tidak terdengar jelas,foto kurang tajam, atau terdapat kesalahan data yang harus diperbaiki)."
                            ],
                            [
                                "id" => 200,
                                "skor" => 1,
                                "deskripsi" => "Tidak melakukan pemeriksaan sehingga materi dipublikasikan dengan kesalahan yangmemengaruhi akurasi informasi, kualitas dokumentasi, atau kredibilitas organisasi(misalnya berita memuat fakta yang tidak benar, foto salah menggambarkan kegiatan, atauvideo dipublikasikan dengan gangguan audio yang signifikan)."
                            ]
                        ]
                    ],
                    [
                        "id" => 51,
                        "title" => "Koordinasi dalam Pelaksanaan Peliputan dan Publikasi",
                        "deskripsi" => "Kemampuan membangun koordinasi dengan pihak terkait untuk memastikan peliputan,produksi, dan publikasi berjalan sesuai kebutuhan organisasi.",
                        "kelompok_jabatan" => "Media & Communication Support",
                        "behaviorals" => [
                            [
                                "id" => 201,
                                "skor" => 4,
                                "deskripsi" => "Melakukan koordinasi secara lengkap dengan seluruh pihak terkait sehingga kebutuhanpeliputan dan publikasi dipahami serta terlaksana sesuai rencana (misalnya mengonfirmasisusunan acara dengan protokol, memastikan kebutuhan publikasi dengan humas,menyelaraskan tenggat dengan editor, dan mengoordinasikan kebutuhan visual denganpengelola media sosial)."
                            ],
                            [
                                "id" => 202,
                                "skor" => 3,
                                "deskripsi" => "Melakukan koordinasi dengan pihak terkait sehingga pekerjaan tetap berjalan lancar,namun masih terdapat informasi pendukung yang terlambat disampaikan dan tidakmemengaruhi hasil akhir (misalnya perubahan urutan dokumentasi baru diinformasikansetelah briefing, atau kebutuhan foto pendukung baru disampaikan setelah peliputandimulai)."
                            ],
                            [
                                "id" => 203,
                                "skor" => 2,
                                "deskripsi" => "Tidak mengoordinasikan salah satu informasi penting sehingga pelaksanaan peliputan ataupublikasi memerlukan penyesuaian (misalnya perubahan lokasi kegiatan tidakdikonfirmasi kepada tim peliputan, kebutuhan visual tidak disampaikan kepada fotografer,atau tenggat publikasi tidak dikomunikasikan kepada editor)."
                            ],
                            [
                                "id" => 204,
                                "skor" => 1,
                                "deskripsi" => "Tidak melakukan koordinasi dengan pihak terkait sehingga peliputan, produksi, ataupublikasi terganggu (misalnya tim kehilangan momen penting karena perubahan agendatidak diinformasikan, materi publikasi tidak dapat diterbitkan sesuai jadwal karenamiskomunikasi, atau dokumentasi tidak sesuai kebutuhan pengguna akibat koordinasi yangtidak dilakukan)."
                            ]
                        ]
                    ],
                    [
                        "id" => 58,
                        "title" => "Pengelolaan Perubahan Operasional",
                        "deskripsi" => "Kemampuan menyesuaikan pembagian tugas, penempatan personel, prioritas pekerjaan, danpengaturan operasional ketika terjadi perubahan kebutuhan, kendala, atau kondisi lapangan.",
                        "kelompok_jabatan" => "Supervisory Support",
                        "behaviorals" => [
                            [
                                "id" => 229,
                                "skor" => 4,
                                "deskripsi" => "Segera menyusun ulang pembagian tugas, penempatan personel, dan prioritas pekerjaansegera setelah mengetahui adanya perubahan kondisi. (Misalnya langsung menetapkanpetugas pengganti ketika ada personel yang berhalangan hadir, mengubah pembagian areakarena adanya kegiatan tambahan, atau menyesuaikan jadwal kerja setelah menerimaperubahan agenda.)"
                            ],
                            [
                                "id" => 230,
                                "skor" => 3,
                                "deskripsi" => "Menyesuaikan pembagian tugas, penempatan personel, atau prioritas pekerjaan setelahmemperoleh informasi mengenai perubahan kondisi. (Misalnya memperbarui jadwal kerjasetelah briefing tambahan, mengubah pembagian area pada awal operasional, ataumemindahkan personel setelah menerima permintaan perubahan layanan.)"
                            ],
                            [
                                "id" => 231,
                                "skor" => 2,
                                "deskripsi" => "Menyesuaikan pembagian tugas atau penempatan personel setelah perubahan kondisi mulaimemengaruhi pelaksanaan pekerjaan. (Misalnya baru menyusun ulang pembagian areasetelah petugas mulai bekerja, baru menetapkan pengganti setelah ada laporan kekuranganpersonel, atau baru mengubah prioritas pekerjaan setelah muncul kendala di lapangan.)"
                            ],
                            [
                                "id" => 232,
                                "skor" => 1,
                                "deskripsi" => "Tidak melakukan penyesuaian terhadap pembagian tugas, penempatan personel, atauprioritas pekerjaan ketika terjadi perubahan kondisi. (Misalnya membiarkan pembagiantugas tetap sama meskipun terdapat personel yang tidak hadir, tidak mengubah prioritaspekerjaan saat terjadi perubahan kebutuhan, atau tidak menetapkan pengganti ketikapetugas berhalangan.)"
                            ]
                        ]
                    ],
                    [
                        "id" => 59,
                        "title" => "Monitoring dan Verifikasi Lapangan",
                        "deskripsi" => "Kemampuan melakukan pemantauan langsung terhadap pelaksanaan pekerjaan sertamemverifikasi kondisi lapangan berdasarkan fakta sebelum menetapkan tindak lanjut.",
                        "kelompok_jabatan" => "Supervisory Support",
                        "behaviorals" => [
                            [
                                "id" => 233,
                                "skor" => 4,
                                "deskripsi" => "Melakukan pemeriksaan langsung terhadap pelaksanaan pekerjaan, membandingkankondisi lapangan dengan laporan yang diterima, serta mengidentifikasi temuan sebelummenentukan tindak lanjut. (Misalnya mengecek langsung kebersihan area, memeriksakesiapan kendaraan, mengamati pelayanan petugas, atau memastikan hasil perbaikanteknisi sesuai kondisi di lapangan.)"
                            ],
                            [
                                "id" => 234,
                                "skor" => 3,
                                "deskripsi" => "Melakukan pemeriksaan langsung terhadap pekerjaan utama serta memverifikasi temuanyang berkaitan dengan kebutuhan operasional. (Misalnya memeriksa area prioritas terlebihdahulu kemudian melanjutkan pemeriksaan pada area pendukung, atau mengecek sebagianhasil pekerjaan sebelum menerima laporan.)"
                            ],
                            [
                                "id" => 235,
                                "skor" => 2,
                                "deskripsi" => "Monitoring dilakukan secara terbatas atau verifikasi hanya berdasarkan sebagian informasiyang tersedia. (Misalnya hanya memeriksa area tertentu, hanya mengecek sebagian hasilpekerjaan, atau hanya memverifikasi laporan pada kondisi tertentu.)"
                            ],
                            [
                                "id" => 236,
                                "skor" => 1,
                                "deskripsi" => "Tidak melakukan pemeriksaan langsung maupun verifikasi terhadap kondisi lapangan.(Misalnya hanya menerima laporan tanpa mengecek kondisi sebenarnya, menutup laporantanpa pemeriksaan, atau menetapkan tindak lanjut berdasarkan asumsi.)"
                            ]
                        ]
                    ],
                    [
                        "id" => 60,
                        "title" => "Pengambilan Keputusan Operasional",
                        "deskripsi" => "Kemampuan menentukan tindakan operasional berdasarkan kondisi lapangan, tingkat urgensi,dan kewenangan yang dimiliki.",
                        "kelompok_jabatan" => "Supervisory Support",
                        "behaviorals" => [
                            [
                                "id" => 237,
                                "skor" => 4,
                                "deskripsi" => "Menetapkan tindakan operasional berdasarkan kondisi lapangan, tingkat urgensi, dankewenangan yang dimiliki. (Misalnya menetapkan penggunaan petugas cadangan,menentukan prioritas pekerjaan saat beberapa permintaan muncul bersamaan, memilihsolusi sementara sebelum perbaikan permanen dilakukan, atau memutuskan eskalasikepada unit terkait.)"
                            ],
                            [
                                "id" => 238,
                                "skor" => 3,
                                "deskripsi" => "Menetapkan tindakan operasional setelah memperoleh informasi yang diperlukan danmempertimbangkan kebutuhan utama pekerjaan. (Misalnya menentukan pembagian ulangpekerjaan setelah menerima laporan kondisi lapangan atau menetapkan perubahan jadwalsetelah memperoleh konfirmasi dari pihak terkait.)"
                            ],
                            [
                                "id" => 239,
                                "skor" => 2,
                                "deskripsi" => "Menetapkan tindakan operasional setelah mempertimbangkan sebagian informasi sehinggakeputusan masih memerlukan penyesuaian lanjutan. (Misalnya menetapkan prioritaspekerjaan tanpa mempertimbangkan seluruh kebutuhan lapangan atau baru melakukaneskalasi setelah menerima informasi tambahan.)"
                            ],
                            [
                                "id" => 240,
                                "skor" => 1,
                                "deskripsi" => "Tidak menetapkan tindakan operasional yang diperlukan atau menetapkan tindakan tanpamempertimbangkan kondisi lapangan maupun kewenangan yang dimiliki.(Misalnya membiarkan tim menentukan sendiri prioritas pekerjaan, tidak menunjukpetugas pengganti, atau tidak melakukan eskalasi terhadap masalah yang berada di luarkewenangannya.)"
                            ]
                        ]
                    ],
                    [
                        "id" => 61,
                        "title" => "Koordinasi dan Pembinaan Tim",
                        "deskripsi" => "Kemampuan membangun komunikasi kerja, memberikan arahan, menyampaikan",
                        "kelompok_jabatan" => "Supervisory Support",
                        "behaviorals" => [
                            [
                                "id" => 241,
                                "skor" => 4,
                                "deskripsi" => "Melakukan briefing, menyampaikan arahan, memberikan umpan balik berdasarkan hasilpengamatan, melakukan pembinaan terhadap anggota tim, serta berkoordinasi denganseluruh pihak yang terkait. (Misalnya menjelaskan perubahan pembagian tugas sebelumoperasional dimulai, memberikan umpan balik setelah inspeksi lapangan, melakukancoaching kepada petugas yang melakukan kesalahan, atau mengoordinasikan perubahankebutuhan dengan pengguna layanan.)"
                            ],
                            [
                                "id" => 242,
                                "skor" => 3,
                                "deskripsi" => "Melakukan arahan kerja, pembinaan, dan koordinasi terhadap kebutuhan utamaoperasional. (Misalnya memberikan briefing sebelum kegiatan, menyampaikan perubahanpenugasan kepada tim, atau berkoordinasi dengan pengguna mengenai kebutuhan layanan.)"
                            ],
                            [
                                "id" => 243,
                                "skor" => 2,
                                "deskripsi" => "Memberikan arahan atau pembinaan hanya pada kondisi tertentu sehingga sebagiananggota tim belum memperoleh informasi atau umpan balik yang diperlukan.(Misalnya hanya memberikan arahan kepada sebagian petugas, pembinaan dilakukansetelah masalah berulang, atau koordinasi dilakukan setelah pekerjaan dimulai.)"
                            ],
                            [
                                "id" => 244,
                                "skor" => 1,
                                "deskripsi" => "Tidak memberikan arahan, pembinaan, maupun koordinasi kepada anggota tim atau pihakterkait. (Misalnya tidak melakukan briefing sebelum operasional, tidak memberikan umpanbalik atas hasil kerja, atau tidak menyampaikan perubahan kebutuhan kepada anggota tim.)"
                            ]
                        ]
                    ]
                ]
            ],
            [
                "id" => 3,
                "title" => "Task Performance",
                "slug" => "task-performance",
                'bobot' => 40,
                "indikators" => [
                    [
                        "id" => 1,
                        "title" => "Akurasi dan Kelengkapan Hasil Administrasi Definisi",
                        "deskripsi" => "Kemampuan menghasilkan dokumen, laporan, data administrasi, maupun keluaran administrasilainnya yang akurat, lengkap, sesuai ketentuan, dan siap digunakan.",
                        "kelompok_jabatan" => "BARS_Administrative Support",
                        "behaviorals" => [
                            [
                                "id" => 1,
                                "skor" => 4,
                                "deskripsi" => "Seluruh hasil administrasi akurat, lengkap, sesuai format, dan siap digunakan tanpa memerlukan perbaikan. (Misalnya surat dinas bebas dari kesalahan nama, nomor, tanggal, dan lampiran; laporan keuangan atau BMN lengkap sesuai ketentuan; notulen rapat memuat seluruh keputusan penting; serta dokumen kepegawaian lengkap dan siap diproses.)"
                            ],
                            [
                                "id" => 2,
                                "skor" => 3,
                                "deskripsi" => "Seluruh hasil administrasi memenuhi ketentuan utama, namun masih terdapat kekurangan pada aspek pendukung yang tidak memengaruhi penggunaan dokumen. (Misalnya format penulisan belum sepenuhnya seragam, terdapat kesalahan tata letak atau ejaan yang tidak mengubah isi dokumen, atau terdapat lampiran pendukung yang dilengkapi setelah dokumen utama selesai.)"
                            ],
                            [
                                "id" => 3,
                                "skor" => 2,
                                "deskripsi" => "Sebagian hasil administrasi belum memenuhi ketentuan sehingga memerlukan perbaikan sebelum dapat digunakan. (Misalnya terdapat kesalahan nama, nomor surat, tanggal, nilai transaksi, atau lampiran yang menyebabkan dokumen harus direvisi sebelum diproses lebih lanjut.)"
                            ],
                            [
                                "id" => 4,
                                "skor" => 1,
                                "deskripsi" => "Hasil administrasi tidak memenuhi ketentuan sehingga tidak dapat digunakan atau harus dibuat ulang. (Misalnya dokumen tidak lengkap, informasi penting tidak sesuai, laporantidak dapat diproses, atau dokumen dikembalikan karena banyak kesalahan administrasi.)"
                            ]
                        ]
                    ],
                    [
                        "id" => 2,
                        "title" => "Ketepatan Waktu Penyelesaian Administrasi Definisi",
                        "deskripsi" => "Kemampuan menyelesaikan seluruh pekerjaan administrasi sesuai batas waktu yang telahditetapkan sehingga dapat digunakan sesuai kebutuhan organisasi.",
                        "kelompok_jabatan" => "BARS_Administrative Support",
                        "behaviorals" => [
                            [
                                "id" => 5,
                                "skor" => 4,
                                "deskripsi" => "Seluruh hasil administrasi tersedia sesuai atau sebelum batas waktu yang ditetapkan sehingga seluruh kebutuhan administrasi dapat dipenuhi tepat waktu. (Misalnya suratditerbitkan sesuai jadwal, laporan selesai sebelum tenggat, dokumen pembayaran siapdiproses sesuai jadwal, atau bahan rapat telah tersedia sebelum rapat dimulai.)"
                            ],
                            [
                                "id" => 6,
                                "skor" => 3,
                                "deskripsi" => "Seluruh hasil administrasi utama tersedia sesuai batas waktu, namun terdapat keterlambatan pada keluaran pendukung yang tidak memengaruhi kebutuhan utama organisasi. (Misalnya laporan utama selesai tepat waktu sementara rekap pendukungdisampaikan kemudian, atau dokumen utama tersedia sesuai jadwal meskipun salinan digital diunggah setelahnya.)"
                            ],
                            [
                                "id" => 7,
                                "skor" => 2,
                                "deskripsi" => "Sebagian hasil administrasi tersedia melewati batas waktu sehingga memengaruhi sebagian proses administrasi. (Misalnya surat diterbitkan setelah jadwal yang ditentukan, laporan terlambat disampaikan, atau dokumen pembayaran belum siap ketika dibutuhkan.)"
                            ],
                            [
                                "id" => 8,
                                "skor" => 1,
                                "deskripsi" => "Sebagian besar hasil administrasi tidak tersedia sesuai batas waktu sehingga menghambat proses administrasi organisasi. (Misalnya dokumen tidak selesai pada saat dibutuhkan, laporan tidak tersedia untuk pengambilan keputusan, atau agenda kegiatan tidak siap sebelum pelaksanaan.)"
                            ]
                        ]
                    ],
                    [
                        "id" => 3,
                        "title" => "Ketersediaan Informasi Administrasi Definisi",
                        "deskripsi" => "Kemampuan memastikan data, dokumen, arsip, dan informasi administrasi selalu lengkap,mutakhir, serta mudah ditemukan ketika dibutuhkan.",
                        "kelompok_jabatan" => "BARS_Administrative Support",
                        "behaviorals" => [
                            [
                                "id" => 9,
                                "skor" => 4,
                                "deskripsi" => "Seluruh data, dokumen, dan arsip administrasi tersedia, mutakhir, lengkap, serta dapat ditemukan dengan cepat ketika dibutuhkan. (Misalnya data pegawai telah diperbarui, arsip surat dapat ditemukan sesuai nomor atau tanggal, dokumen BMN tersedia lengkap, serta dokumen keuangan dan laporan terdokumentasi dengan baik.)"
                            ],
                            [
                                "id" => 10,
                                "skor" => 3,
                                "deskripsi" => "Seluruh informasi administrasi utama tersedia dan dapat digunakan, namun masih terdapat data atau arsip pendukung yang belum sepenuhnya diperbarui tanpa menghambat penggunaan informasi utama. (Misalnyadatabase utama telah mutakhir, sementara dokumen pendukung atau arsip lama masih dalam proses pembaruan.)"
                            ],
                            [
                                "id" => 11,
                                "skor" => 2,
                                "deskripsi" => "Sebagian informasi administrasi belum lengkap, belum mutakhir, atau memerlukan waktu tambahan untuk ditemukan sehingga menghambat sebagian kebutuhan administrasi. (Misalnya data pegawai belum diperbarui, dokumen tertentu sulit ditemukan, atau sebagian arsip belum tersusun sesuai klasifikasi.)"
                            ],
                            [
                                "id" => 12,
                                "skor" => 1,
                                "deskripsi" => "Informasi administrasi tidak tersedia, tidak mutakhir, atau tidak dapat ditemukan ketika dibutuhkan sehingga menghambat proses administrasi organisasi. (Misalnya arsip penting tidak ditemukan, database tidak diperbarui, atau dokumen yang diperlukan tidak tersedia pada saat diminta.)"
                            ]
                        ]
                    ],
                    [
                        "id" => 10,
                        "title" => "Kesiapan Area dan Sarana Layanan Definisi",
                        "deskripsi" => "Kemampuan memastikan area pelayanan, ruang rapat, ruang tunggu, front desk, pantry, maupunsarana pendukung berada dalam kondisi siap digunakan sebelum layanan diberikan.",
                        "kelompok_jabatan" => "BARS_Hospitality & Service Support",
                        "behaviorals" => [
                            [
                                "id" => 37,
                                "skor" => 4,
                                "deskripsi" => "Seluruh area pelayanan dan sarana pendukung berada dalam kondisi siap digunakan sebelum layanan dimulai sehingga tidak diperlukan penyesuaian tambahan saat pelayanan berlangsung."
                            ],
                            [
                                "id" => 38,
                                "skor" => 3,
                                "deskripsi" => "Seluruh area pelayanan dan sarana utama telah siap digunakan, namun masih terdapat kekurangan pada sarana pendukung yang tidak menghambat pelaksanaan layanan (misalnya alat tulis cadangan belum tersedia, papan nama tambahan belum dipasang, atau perlengkapan pendukung non-esensial belum ditata)."
                            ],
                            [
                                "id" => 39,
                                "skor" => 2,
                                "deskripsi" => "Sebagian area pelayanan atau sarana utama belum siap sehingga layanan memerlukan penyesuaian sebelum dapat diberikan (misalnya ruang rapat belum selesai ditata, frontdesk belum siap menerima tamu, pantry belum siap digunakan, atau layout ruangan belum sesuai kebutuhan kegiatan)."
                            ],
                            [
                                "id" => 40,
                                "skor" => 1,
                                "deskripsi" => "Area pelayanan atau sarana utama tidak siap digunakan sehingga pelayanan tidak dapat dilaksanakan sesuai kebutuhan operasional."
                            ]
                        ]
                    ],
                    [
                        "id" => 11,
                        "title" => "Kesiapan Area dan Sarana Layanan Definisi",
                        "deskripsi" => "Kemampuan memastikan area pelayanan, ruang rapat, ruang tunggu, front desk, pantry, maupunsarana pendukung berada dalam kondisi siap digunakan sebelum layanan diberikan.",
                        "kelompok_jabatan" => "BARS_Hospitality & Service Support",
                        "behaviorals" => [
                            [
                                "id" => 41,
                                "skor" => 4,
                                "deskripsi" => "Seluruh kebutuhan pengguna dipenuhi sesuai permintaan, ketentuan, atau kebutuhan kegiatan tanpa ditemukan kekurangan maupun kesalahan layanan."
                            ],
                            [
                                "id" => 42,
                                "skor" => 3,
                                "deskripsi" => "Kebutuhan utama pengguna telah terpenuhi, namun masih terdapat kekurangan pada kebutuhan pendukung yang tidak memengaruhi kelancaran kegiatan (misalnya alat tulis tambahan belum tersedia, pilihan minuman pendamping belum sesuai preferensi, atau perlengkapan pendukung tambahan baru dilengkapi setelah diminta)."
                            ],
                            [
                                "id" => 43,
                                "skor" => 2,
                                "deskripsi" => "Sebagian kebutuhan utama pengguna belum terpenuhi sehingga pelayanan memerlukan penyesuaian selama kegiatan berlangsung (misalnya jumlah kursi tidak sesuai kebutuhan, konsumsi tidak sesuai permintaan, tamu sempat diarahkan ke unit yang kurang tepat sebelum dikoreksi, atau perlengkapan rapat belum lengkap saat kegiatan dimulai)."
                            ],
                            [
                                "id" => 44,
                                "skor" => 1,
                                "deskripsi" => "Kebutuhan utama pengguna tidak terpenuhi sehingga pelayanan tidak dapat mendukung pelaksanaan kegiatan atau mengganggu pengguna layanan."
                            ]
                        ]
                    ],
                    [
                        "id" => 12,
                        "title" => "Kualitas Layanan Definisi",
                        "deskripsi" => "Kemampuan memberikan layanan yang memenuhi standar kebersihan, kerapian, higienitas,estetika, dan kenyamanan sehingga memberikan pengalaman pelayanan yang baik kepadapengguna.",
                        "kelompok_jabatan" => "BARS_Hospitality & Service Support",
                        "behaviorals" => [
                            [
                                "id" => 45,
                                "skor" => 4,
                                "deskripsi" => "Layanan disajikan sesuai standar kebersihan, kerapian, higienitas, estetika, dan kenyamanan tanpa ditemukan kekurangan yang memengaruhi kualitas pelayanan."
                            ],
                            [
                                "id" => 46,
                                "skor" => 3,
                                "deskripsi" => "Layanan telah memenuhi standar utama, namun masih terdapat kekurangan pada aspek pendukung yang tidak memengaruhi kenyamanan pengguna (misalnya penataan dekorasi belum sepenuhnya simetris, lipatan linen kurang rapi, atau posisi perlengkapan pendukung belum seragam)."
                            ],
                            [
                                "id" => 47,
                                "skor" => 2,
                                "deskripsi" => "Layanan belum memenuhi sebagian standar kualitas sehingga mengurangi kenyamanan atau citra pelayanan (misalnya penyajian konsumsi kurang rapi, gelas atau peralatan kurang bersih, area front desk kurang representatif, atau tata letak perlengkapan terlihat kurang teratur)."
                            ],
                            [
                                "id" => 48,
                                "skor" => 1,
                                "deskripsi" => "Layanan tidak memenuhi standar kualitas sehingga menimbulkan keluhan atau mengganggu kenyamanan pengguna layanan."
                            ]
                        ]
                    ],
                    [
                        "id" => 13,
                        "title" => "Ketepatan Administrasi dan Pelaporan Layanan Definisi",
                        "deskripsi" => "Kemampuan menghasilkan administrasi, pencatatan, dan pelaporan layanan secara lengkap,akurat, tepat waktu, dan dapat digunakan sebagai dasar tindak lanjut.",
                        "kelompok_jabatan" => "BARS_Hospitality & Service Support",
                        "behaviorals" => [
                            [
                                "id" => 49,
                                "skor" => 4,
                                "deskripsi" => "Seluruh administrasi dan pelaporan layanan tersedia secara lengkap, akurat, tepat waktu, serta dapat digunakan sebagai dasar pelaksanaan maupun tindak lanjut pekerjaan."
                            ],
                            [
                                "id" => 50,
                                "skor" => 3,
                                "deskripsi" => "Administrasi dan pelaporan layanan tersedia dan dapat digunakan, namun masih terdapat informasi pendukung yang belum lengkap (misalnya waktu penyerahan belum dicantumkan, nomor dokumen belum dilengkapi, atau rekap penggunaan perlengkapan belum diperbarui)."
                            ],
                            [
                                "id" => 51,
                                "skor" => 2,
                                "deskripsi" => "Administrasi atau pelaporan hanya memuat sebagian informasi sehingga menyulitkan proses tindak lanjut (misalnya data tamu tidak lengkap, distribusi dokumen tidak tercatat, laporan stok belum diperbarui, atau serah terima shift tidak terdokumentasi)."
                            ],
                            [
                                "id" => 52,
                                "skor" => 1,
                                "deskripsi" => "Administrasi atau pelaporan tidak tersedia atau tidak dapat digunakan sebagai dasar pelaksanaan maupun tindak lanjut pekerjaan."
                            ]
                        ]
                    ],
                    [
                        "id" => 20,
                        "title" => "Kesiapan Kendaraan Operasional",
                        "deskripsi" => "Kemampuan memastikan kendaraan beserta seluruh persyaratan operasional berada dalam kondisisiap digunakan sehingga pelayanan transportasi dapat dilaksanakan sesuai kebutuhan.",
                        "kelompok_jabatan" => "Transportation Support",
                        "behaviorals" => [
                            [
                                "id" => 77,
                                "skor" => 4,
                                "deskripsi" => "Kendaraan beserta seluruh persyaratan operasional telah siap digunakan sebelum penugasan dimulaisehingga pelayanan dapat langsung dilaksanakan tanpa penyesuaian (misalnya kendaraan pimpinantelah diperiksa sebelum jadwal keberangkatan, kendaraan operasional telah diisi BBM sesuaikebutuhan penugasan, atau kendaraan distribusi telah siap beserta dokumen dan barang yang akandikirim)."
                            ],
                            [
                                "id" => 78,
                                "skor" => 3,
                                "deskripsi" => "Kendaraan dan seluruh persyaratan utama telah siap digunakan, namun masih terdapat kekuranganpada elemen pendukung yang tidak memengaruhi pelaksanaan pelayanan (misalnya air minumbelum tersedia di kendaraan pimpinan, perlengkapan kebersihan kendaraan belum dilengkapi, ataupengisian cairan pembersih kaca belum dilakukan)."
                            ],
                            [
                                "id" => 79,
                                "skor" => 2,
                                "deskripsi" => "Salah satu persyaratan utama operasional belum terpenuhi sehingga kendaraan memerlukanpenyesuaian sebelum dapat digunakan (misalnya BBM belum mencukupi untuk perjalanan, bankurang tekanan angin, dokumen kendaraan belum tersedia, atau kendaraan belum dibersihkansetelah penugasan sebelumnya)."
                            ],
                            [
                                "id" => 80,
                                "skor" => 1,
                                "deskripsi" => "Kendaraan tidak memenuhi persyaratan operasional sehingga tidak dapat digunakan ataumengakibatkan pelayanan transportasi gagal dilaksanakan."
                            ]
                        ]
                    ],
                    [
                        "id" => 21,
                        "title" => "Ketepatan Pemenuhan Layanan Transportasi",
                        "deskripsi" => "Kemampuan memenuhi kebutuhan layanan transportasi sesuai jadwal, tujuan, pengguna, danketentuan penugasan yang telah ditetapkan.",
                        "kelompok_jabatan" => "Transportation Support",
                        "behaviorals" => [
                            [
                                "id" => 81,
                                "skor" => 4,
                                "deskripsi" => "Seluruh kebutuhan perjalanan dipenuhi sesuai jadwal, tujuan, pengguna, dan ketentuanpenugasan tanpa ditemukan kesalahan pelayanan (misalnya pimpinan dijemput sesuaiwaktu dan lokasi, kendaraan operasional tiba sesuai jadwal kegiatan, atau dokumen/barangditerima oleh unit tujuan yang benar)."
                            ],
                            [
                                "id" => 82,
                                "skor" => 3,
                                "deskripsi" => "Seluruh kebutuhan utama perjalanan terpenuhi, namun masih terdapat penyesuaian padaaspek pendukung yang tidak memengaruhi kelancaran pelayanan (misalnya rute diubahkarena kondisi lalu lintas dengan persetujuan pengguna, atau titik penurunan disesuaikankarena kondisi akses lokasi)."
                            ],
                            [
                                "id" => 83,
                                "skor" => 2,
                                "deskripsi" => "Sebagian kebutuhan utama perjalanan tidak terpenuhi sehingga pelayanan memerlukanpenyesuaian selama pelaksanaan (misalnya penjemputan terlambat karena kesalahanpersiapan, tujuan sempat keliru sebelum dikoreksi, atau barang yang dikirim belum lengkapsehingga harus dilakukan pengiriman ulang)."
                            ],
                            [
                                "id" => 84,
                                "skor" => 1,
                                "deskripsi" => "Kebutuhan perjalanan tidak terpenuhi sehingga pelayanan gagal mendukung kegiatanpengguna (misalnyapengguna tidak terlayani sesuai jadwal, perjalanan tidak terlaksana,atau dokumen/barang tidak sampai kepada tujuan yang ditetapkan)."
                            ]
                        ]
                    ],
                    [
                        "id" => 22,
                        "title" => "Keselamatan Operasional Transportasi",
                        "deskripsi" => "Kemampuan melaksanakan pelayanan transportasi tanpa menimbulkan kecelakaan, pelanggaranlalu lintas, maupun kerusakan kendaraan yang disebabkan oleh kelalaian dalam berkendara.",
                        "kelompok_jabatan" => "Transportation Support",
                        "behaviorals" => [
                            [
                                "id" => 85,
                                "skor" => 4,
                                "deskripsi" => "Seluruh perjalanan terlaksana tanpa kecelakaan, pelanggaran lalu lintas, maupun kerusakankendaraan akibat kelalaian pengemudi."
                            ],
                            [
                                "id" => 86,
                                "skor" => 3,
                                "deskripsi" => "Perjalanan terlaksana dengan aman, namun terdapat kejadian minor yang tidakmemengaruhi keselamatan maupun kelangsungan pelayanan (misalnya menerima teguranlisan dari petugas karena kesalahan administratif, atau terjadi goresan ringan padakendaraan akibat kondisi parkir yang tidak menimbulkan kerusakan operasional)."
                            ],
                            [
                                "id" => 87,
                                "skor" => 2,
                                "deskripsi" => "Terjadi pelanggaran atau insiden akibat kelalaian yang memerlukan penanganan danmengganggu sebagian pelayanan (misalnya melanggar rambu lalu lintas, kendaraanmengalami kerusakan karena kesalahan pengoperasian, atau terjadi insiden yangmenyebabkan perjalanan tertunda)."
                            ],
                            [
                                "id" => 88,
                                "skor" => 1,
                                "deskripsi" => "Terjadi kecelakaan atau pelanggaran serius akibat kelalaian sehingga menimbulkankerugian terhadap pengguna, kendaraan, atau organisasi."
                            ]
                        ]
                    ],
                    [
                        "id" => 23,
                        "title" => "Ketepatan Administrasi Transportasi",
                        "deskripsi" => "Kemampuan menghasilkan administrasi penggunaan kendaraan, perjalanan, dan kondisikendaraan secara lengkap, akurat, tepat waktu, serta dapat digunakan sebagai dasar tindak lanjut.",
                        "kelompok_jabatan" => "Transportation Support",
                        "behaviorals" => [
                            [
                                "id" => 89,
                                "skor" => 4,
                                "deskripsi" => "Seluruh administrasi transportasi tersedia secara lengkap, akurat, tepat waktu, dan dapatdigunakan sebagai dasar pelaksanaan maupun tindak lanjut (misalnya logbook, checklistkendaraan, laporan perjalanan, laporan BBM, dan laporan kondisi kendaraan terisilengkap)."
                            ],
                            [
                                "id" => 90,
                                "skor" => 3,
                                "deskripsi" => "Administrasi transportasi telah tersedia dan dapat digunakan, namun masih terdapatinformasi pendukung yang belum lengkap (misalnya waktu kedatangan belum dicatat,kilometer akhir belum diperbarui, atau bukti pengisian BBM belum dilampirkan)."
                            ],
                            [
                                "id" => 91,
                                "skor" => 2,
                                "deskripsi" => "Administrasi hanya memuat sebagian informasi sehingga menyulitkan penelusuran atautindak lanjut (misalnyachecklist kendaraan tidak diisi, laporan kerusakan belum dibuat,atau penggunaan kendaraan tidak tercatat secara lengkap)."
                            ],
                            [
                                "id" => 92,
                                "skor" => 1,
                                "deskripsi" => "Administrasi tidak tersedia atau tidak dapat digunakan sebagai dasar pelaksanaan maupuntindak lanjut pekerjaan."
                            ]
                        ]
                    ],
                    [
                        "id" => 30,
                        "title" => "Kualitas Lingkungan Kerja",
                        "deskripsi" => "Kemampuan menghasilkan lingkungan kerja yang bersih, rapi, higienis, nyaman, danrepresentatif sesuai standar yang ditetapkan.",
                        "kelompok_jabatan" => "Environment Support",
                        "behaviorals" => [
                            [
                                "id" => 117,
                                "skor" => 4,
                                "deskripsi" => "Area bersih, toilet higienis, taman tertata rapi, lingkungan nyaman dan representatif."
                            ],
                            [
                                "id" => 118,
                                "skor" => 3,
                                "deskripsi" => "Area bersih dan nyaman digunakan, namun masih terdapat aspek estetika atau kerapianpendukung yang belum optimal (misalnya taman kurang rapi atau daun kering belumtertangani)."
                            ],
                            [
                                "id" => 119,
                                "skor" => 2,
                                "deskripsi" => "Masih ditemukan area yang kurang bersih, kurang rapi, atau mengurangi kenyamananpengguna."
                            ],
                            [
                                "id" => 120,
                                "skor" => 1,
                                "deskripsi" => "Kondisi lingkungan kerja tidak memenuhi standar sehingga mengganggu kenyamananatau kualitas lingkungan kerja."
                            ]
                        ]
                    ],
                    [
                        "id" => 31,
                        "title" => "Kelayakan dan Kesiapan Area Kerja",
                        "deskripsi" => "Kemampuan menghasilkan kondisi area kerja yang aman, layak, dan siap digunakan untukmendukung aktivitas operasional sehari-hari.",
                        "kelompok_jabatan" => "Environment Support",
                        "behaviorals" => [
                            [
                                "id" => 121,
                                "skor" => 4,
                                "deskripsi" => "Seluruh area yang menjadi tanggung jawab berada dalam kondisi aman, layak, dan siapdigunakan sesuai fungsi yang ditetapkan (misalnya tidak terdapat kran bocor, salurantersumbat, area licin, atau kondisi lain yang mengganggu penggunaan area)."
                            ],
                            [
                                "id" => 122,
                                "skor" => 3,
                                "deskripsi" => "Area yang menjadi tanggung jawab dapat digunakan sesuai fungsi utamanya, namun masihterdapat kondisi pendukung yang belum sepenuhnya sesuai standar dan tidak mengganggupenggunaan area (misalnya terdapat kerusakan ringan atau area yang memerlukanperbaikan minor)."
                            ],
                            [
                                "id" => 123,
                                "skor" => 2,
                                "deskripsi" => "Sebagian area tidak dapat digunakan secara optimal karena terdapat kondisi yangmengurangi fungsi, keamanan, atau kenyamanan area (misalnya saluran kurang berfungsi,area tertentu sulit digunakan, atau terdapat kondisi lingkungan yang memerlukanpenanganan lebih lanjut)."
                            ],
                            [
                                "id" => 124,
                                "skor" => 1,
                                "deskripsi" => "Kondisi area menyebabkan fungsi area tidak dapat digunakan sebagaimana mestinya ataumenimbulkan gangguan terhadap aktivitas pengguna (misalnya fasilitas tidak dapatdigunakan, area tidak aman, atau terdapat kondisi yang menghambat aktivitas operasional)."
                            ]
                        ]
                    ],
                    [
                        "id" => 34,
                        "title" => "Keandalan Layanan Teknis",
                        "deskripsi" => "Kemampuan memastikan sistem, aplikasi, perangkat, jaringan, maupun fasilitas teknis yangmenjadi tanggung jawabnya berada dalam kondisi berfungsi dengan baik, stabil, aman, dan siapdigunakan untuk mendukung operasional organisasi.",
                        "kelompok_jabatan" => "Technical Support",
                        "behaviorals" => [
                            [
                                "id" => 133,
                                "skor" => 4,
                                "deskripsi" => "Seluruh sistem, aplikasi, perangkat, jaringan, atau fasilitas teknis yang menjadi tanggungjawabnya berada dalam kondisi berfungsi, stabil, aman, dan siap digunakan sesuai standarlayanan yang ditetapkan."
                            ],
                            [
                                "id" => 134,
                                "skor" => 3,
                                "deskripsi" => "Sistem, aplikasi, perangkat, jaringan, atau fasilitas teknis dapat digunakan sesuai fungsiutamanya, namun masih terdapat gangguan minor yang tidak mengganggu operasional(misalnya waktu akses aplikasi sedikit lebih lambat, printer memerlukan reset sebelumdigunakan, atau AC memerlukan penyesuaian suhu)."
                            ],
                            [
                                "id" => 135,
                                "skor" => 2,
                                "deskripsi" => "Sebagian sistem, aplikasi, perangkat, jaringan, atau fasilitas teknis belum dapat digunakansesuai fungsi yang ditetapkan sehingga mengganggu sebagian aktivitas operasional(misalnya fitur aplikasi tidak dapat digunakan, jaringan pada area tertentu tidak berfungsi,printer tidak dapat digunakan, atau AC tidak berfungsi pada sebagian ruangan)."
                            ],
                            [
                                "id" => 136,
                                "skor" => 1,
                                "deskripsi" => "Sistem, aplikasi, perangkat, jaringan, atau fasilitas teknis tidak dapat digunakan sesuaifungsi yang ditetapkan sehingga menghambat operasional atau kegiatan kedinasan."
                            ]
                        ]
                    ],
                    [
                        "id" => 35,
                        "title" => "Pemenuhan Target Layanan Teknis",
                        "deskripsi" => "Kemampuan menyelesaikan layanan teknis sesuai target waktu, jadwal, atau standar layananyang telah ditetapkan untuk mendukung kelancaran operasional.",
                        "kelompok_jabatan" => "Technical Support",
                        "behaviorals" => [
                            [
                                "id" => 137,
                                "skor" => 4,
                                "deskripsi" => "Seluruh layanan teknis yang menjadi tanggung jawabnya diselesaikan sesuai target waktu,jadwal, atau standar layanan yang ditetapkan."
                            ],
                            [
                                "id" => 138,
                                "skor" => 3,
                                "deskripsi" => "Layanan teknis utama diselesaikan sesuai target, namun masih terdapat pekerjaanpendukung yang mengalami penyesuaian waktu tanpa memengaruhi kelancaranoperasional (misalnya pembaruan dokumentasi dilakukan setelah layanan selesai,sinkronisasi data dijadwalkan pada waktu berikutnya, atau pembaruan inventaris dilakukansetelah pekerjaan selesai)."
                            ],
                            [
                                "id" => 139,
                                "skor" => 2,
                                "deskripsi" => "Sebagian layanan teknis tidak diselesaikan sesuai target waktu sehingga mengganggusebagian aktivitas operasional (misalnya penyelesaian bug melebihi target, deploymenttertunda, preventive maintenance tidak dilaksanakan sesuai jadwal, atau ruang rapat belumsiap digunakan pada waktu yang ditentukan)."
                            ],
                            [
                                "id" => 140,
                                "skor" => 1,
                                "deskripsi" => "Layanan teknis tidak diselesaikan sesuai target waktu sehingga menghambat operasionalatau kegiatan kedinasan."
                            ]
                        ]
                    ],
                    [
                        "id" => 36,
                        "title" => "Kualitas Solusi Teknis",
                        "deskripsi" => "Kemampuan menghasilkan solusi teknis yang tepat sehingga akar penyebab gangguanterselesaikan dan permasalahan yang sama tidak kembali terjadi.",
                        "kelompok_jabatan" => "Technical Support",
                        "behaviorals" => [
                            [
                                "id" => 141,
                                "skor" => 4,
                                "deskripsi" => "Solusi yang diberikan menyelesaikan akar penyebab permasalahan sehingga gangguanyang sama tidak kembali terjadi dan tidak menimbulkan gangguan baru pada sistem,perangkat, aplikasi, maupun fasilitas lainnya."
                            ],
                            [
                                "id" => 142,
                                "skor" => 3,
                                "deskripsi" => "Solusi yang diberikan menyelesaikan permasalahan utama, namun masih terdapat tindaklanjut yang telah teridentifikasi untuk menyempurnakan solusi permanen (misalnyapenggantian komponen menunggu pengadaan, optimasi sistem dijadwalkan pada periodeberikutnya, atau penyempurnaan fitur dilakukan pada versi berikutnya)."
                            ],
                            [
                                "id" => 143,
                                "skor" => 2,
                                "deskripsi" => "Solusi yang diberikan hanya mengatasi sebagian penyebab permasalahan sehingga masihterdapat potensi gangguan yang sama terjadi kembali (misalnya hanya melakukan restartsistem tanpa memperbaiki penyebab error, memperbaiki kebocoran sementara tanpamengganti komponen yang rusak, atau memperbaiki bug tanpa mengatasi penyebab utamapada program)."
                            ],
                            [
                                "id" => 144,
                                "skor" => 1,
                                "deskripsi" => "Solusi yang diberikan tidak menyelesaikan penyebab permasalahan sehingga gangguantetap terjadi atau kembali muncul akibat penyebab yang sama."
                            ]
                        ]
                    ],
                    [
                        "id" => 37,
                        "title" => "Ketepatan Dokumentasi Teknis",
                        "deskripsi" => "Kemampuan menghasilkan dokumentasi pekerjaan teknis yang lengkap, akurat, mutakhir, dandapat digunakan sebagai acuan pelaksanaan, pemeliharaan, maupun tindak lanjut pekerjaanberikutnya.",
                        "kelompok_jabatan" => "Technical Support",
                        "behaviorals" => [
                            [
                                "id" => 145,
                                "skor" => 4,
                                "deskripsi" => "Seluruh dokumentasi pekerjaan teknis tersedia secara lengkap, akurat, mutakhir, dan dapatdigunakan sebagai acuan pelaksanaan, pemeliharaan, maupun tindak lanjut pekerjaan."
                            ],
                            [
                                "id" => 146,
                                "skor" => 3,
                                "deskripsi" => "Dokumentasi pekerjaan teknis tersedia dan dapat digunakan, namun masih terdapatinformasi pendukung yang belum lengkap (misalnya perubahan konfigurasi belumdiperbarui, changelog belum dilengkapi, nomor seri perangkat belum dicantumkan, ataupenggunaan spare part belum terdokumentasi)."
                            ],
                            [
                                "id" => 147,
                                "skor" => 2,
                                "deskripsi" => "Dokumentasi pekerjaan teknis hanya memuat sebagian informasi sehingga menyulitkanproses tindak lanjut atau penelusuran riwayat pekerjaan (misalnya tindakan perbaikan,perubahan konfigurasi, perubahan source code, atau penggunaan komponen tidakterdokumentasi secara lengkap)."
                            ],
                            [
                                "id" => 148,
                                "skor" => 1,
                                "deskripsi" => "Dokumentasi pekerjaan teknis tidak tersedia atau tidak dapat digunakan sebagai acuanpelaksanaan maupun tindak lanjut pekerjaan."
                            ]
                        ]
                    ],
                    [
                        "id" => 44,
                        "title" => "Kelengkapan dan Ketepatan Hasil Dokumentasi dan Materi Komunikasi",
                        "deskripsi" => "Kemampuan menghasilkan dokumentasi maupun materi komunikasi yang mencakup seluruhmomen penting, sesuai substansi kegiatan, kebutuhan pengguna, serta tujuan publikasi yang telahditetapkan.",
                        "kelompok_jabatan" => "Media & Communication Support",
                        "behaviorals" => [
                            [
                                "id" => 173,
                                "skor" => 4,
                                "deskripsi" => "Seluruh momen penting dan informasi utama terdokumentasi secara lengkap serta sesuaidengan substansi kegiatan sehingga materi siap digunakan tanpa memerlukan pengambilanulang atau perbaikan substansi (misalnya seluruh rangkaian kunjungan pimpinanterdokumentasi lengkap, video memuat seluruh tahapan utama kegiatan, atau beritamemuat fakta, kutipan, dan konteks kegiatan secara utuh)."
                            ],
                            [
                                "id" => 174,
                                "skor" => 3,
                                "deskripsi" => "Seluruh momen dan informasi utama telah terdokumentasi, namun masih terdapat elemenpendukung yang belum tercakup dan tidak memengaruhi kebutuhan publikasi(misalnya dokumentasi suasana pendukung belum lengkap, variasi angle terbatas, ataukutipan tambahan belum dimasukkan karena tidak memengaruhi substansi berita)."
                            ],
                            [
                                "id" => 175,
                                "skor" => 2,
                                "deskripsi" => "Sebagian momen penting atau informasi utama tidak terdokumentasi sehingga materimemerlukan penyesuaian atau tidak sepenuhnya memenuhi kebutuhan pengguna(misalnya momen penyerahan penghargaan tidak terekam, kutipan utama narasumber tidaktercatat, atau dokumentasi udara yang dibutuhkan tidak tersedia)."
                            ],
                            [
                                "id" => 176,
                                "skor" => 1,
                                "deskripsi" => "Dokumentasi atau materi komunikasi tidak mampu menggambarkan substansi kegiatansehingga tidak dapat digunakan sebagai materi publikasi atau dokumentasi resmi(misalnya sebagian besar momen utama tidak terdokumentasi, isi berita tidak sesuai faktakegiatan, atau materi yang dihasilkan tidak sesuai tujuan penugasan)."
                            ]
                        ]
                    ],
                    [
                        "id" => 45,
                        "title" => "Kualitas Teknis Hasil Kerja",
                        "deskripsi" => "Kemampuan menghasilkan foto, video, maupun materi komunikasi yang memenuhi standarkualitas teknis sehingga layak digunakan untuk kebutuhan dokumentasi dan publikasi organisasi.",
                        "kelompok_jabatan" => "Media & Communication Support",
                        "behaviorals" => [
                            [
                                "id" => 177,
                                "skor" => 4,
                                "deskripsi" => "Hasil kerja memenuhi seluruh standar kualitas teknis dan siap digunakan tanpa memerlukanperbaikan (misalnya foto tajam dengan komposisi dan pencahayaan sesuai, video stabildengan audio jernih, atau berita tersusun sistematis dengan bahasa yang benar dan bebasdari kesalahan ejaan)."
                            ],
                            [
                                "id" => 178,
                                "skor" => 3,
                                "deskripsi" => "Hasil kerja memenuhi standar kualitas utama, namun masih terdapat kekurangan padaaspek pendukung yang tidak memengaruhi kelayakan publikasi (misalnya variasikomposisi kurang beragam, transisi video belum sepenuhnya halus, atau masih terdapatkesalahan tanda baca yang tidak mengubah makna tulisan)."
                            ],
                            [
                                "id" => 179,
                                "skor" => 2,
                                "deskripsi" => "Hasil kerja belum memenuhi sebagian standar kualitas teknis sehingga memerlukanperbaikan sebelum dapat dipublikasikan (misalnya foto kurang tajam, video goyang atauaudio kurang jelas, atau berita memerlukan revisi struktur kalimat dan penyuntinganbahasa)."
                            ],
                            [
                                "id" => 180,
                                "skor" => 1,
                                "deskripsi" => "Hasil kerja tidak memenuhi standar kualitas teknis sehingga tidak dapat digunakan sebagaimateri dokumentasi maupun publikasi (misalnya foto blur, video tidak layak ditayangkankarena gangguan visual atau audio, atau berita mengandung banyak kesalahan yangmengubah makna informasi)."
                            ]
                        ]
                    ],
                    [
                        "id" => 46,
                        "title" => "Ketepatan Waktu Penyelesaian Output",
                        "deskripsi" => "Kemampuan menyelesaikan dan menyerahkan hasil dokumentasi maupun materi komunikasisesuai tenggat waktu yang telah ditetapkan sehingga dapat segera dimanfaatkan oleh pengguna.",
                        "kelompok_jabatan" => "Media & Communication Support",
                        "behaviorals" => [
                            [
                                "id" => 181,
                                "skor" => 4,
                                "deskripsi" => "Seluruh hasil kerja selesai dan diserahkan sesuai tenggat waktu sehingga dapat langsungdigunakan untuk kebutuhan dokumentasi maupun publikasi (misalnya foto diserahkansegera setelah kegiatan, video selesai sesuai jadwal publikasi, atau berita terbit sesuai waktuyang telah ditentukan)."
                            ],
                            [
                                "id" => 182,
                                "skor" => 3,
                                "deskripsi" => "Seluruh hasil kerja selesai sesuai tenggat utama, namun masih terdapat keterlambatan padamateri pendukung yang tidak memengaruhi kebutuhan publikasi (misalnya thumbnailvideo atau foto pendukung diserahkan setelah materi utama dipublikasikan, atau arsip versiresolusi tinggi dikirim setelah kebutuhan utama terpenuhi)."
                            ],
                            [
                                "id" => 183,
                                "skor" => 2,
                                "deskripsi" => "Sebagian hasil kerja terlambat diserahkan sehingga proses publikasi atau dokumentasimemerlukan penyesuaian (misalnya video selesai setelah jadwal publikasi, berita terlambatdikirim ke pengelola media, atau foto utama belum tersedia saat dibutuhkan)."
                            ],
                            [
                                "id" => 184,
                                "skor" => 1,
                                "deskripsi" => "Hasil kerja tidak selesai sesuai tenggat sehingga kebutuhan dokumentasi atau publikasitidak dapat dipenuhi (misalnya berita tidak dapat diterbitkan sesuai jadwal, dokumentasikegiatan tidak tersedia saat diminta, atau video tidak selesai untuk agenda publikasi yangtelah ditetapkan)."
                            ]
                        ]
                    ],
                    [
                        "id" => 47,
                        "title" => "Ketertiban Pengelolaan Arsip dan Media",
                        "deskripsi" => "Kemampuan mengelola file dokumentasi dan materi komunikasi secara lengkap, sistematis,aman, serta mudah ditelusuri kembali ketika dibutuhkan.",
                        "kelompok_jabatan" => "Media & Communication Support",
                        "behaviorals" => [
                            [
                                "id" => 185,
                                "skor" => 4,
                                "deskripsi" => "Seluruh file dokumentasi dan materi komunikasi tersimpan secara lengkap, terstruktur,aman, serta mudah ditemukan kembali tanpa memerlukan penelusuran tambahan(misalnya foto, video, berita, dan rekaman drone memiliki penamaan yang konsisten,tersimpan pada folder yang sesuai, serta telah dilakukan backup)."
                            ],
                            [
                                "id" => 186,
                                "skor" => 3,
                                "deskripsi" => "Seluruh file utama telah tersimpan dengan baik, namun masih terdapat kekurangan padapengelolaan arsip pendukung yang tidak menghambat pencarian kembali(misalnya metadata belum lengkap, folder pendukung belum dirapikan, atau backup keduabelum dilakukan)."
                            ],
                            [
                                "id" => 187,
                                "skor" => 2,
                                "deskripsi" => "Sebagian file belum dikelola secara sistematis sehingga memerlukan waktu tambahanuntuk pencarian atau pengamanan data (misalnya penamaan file tidak konsisten, folderbercampur, atau sebagian file belum dibackup)."
                            ],
                            [
                                "id" => 188,
                                "skor" => 1,
                                "deskripsi" => "Arsip tidak dikelola dengan baik sehingga file sulit ditemukan, hilang, rusak, atau tidakdapat digunakan ketika dibutuhkan (misalnya file RAW terhapus, dokumentasi tidakmemiliki backup, atau materi kegiatan tersimpan pada lokasi yang tidak dapat ditelusuri)."
                            ]
                        ]
                    ],
                    [
                        "id" => 54,
                        "title" => "Ketersediaan dan Kesiapan Tim Layanan",
                        "deskripsi" => "Kemampuan memastikan tenaga outsourcing tersedia, siap bertugas, dan ditempatkan sesuaikebutuhan sehingga seluruh layanan memiliki personel yang memadai.",
                        "kelompok_jabatan" => "Supervisory Support",
                        "behaviorals" => [
                            [
                                "id" => 213,
                                "skor" => 4,
                                "deskripsi" => "Seluruh layanan memiliki personel yang tersedia dan siap bertugas sesuai kebutuhanoperasional sepanjang periode penilaian. (Misalnya seluruh kendaraan memilikipengemudi sesuai jadwal, seluruh ruang rapat memperoleh layanan pramusaji sesuaikebutuhan kegiatan, atau seluruh permintaan layanan teknis ditangani oleh personel yangtersedia.)"
                            ],
                            [
                                "id" => 214,
                                "skor" => 3,
                                "deskripsi" => "Seluruh layanan utama memiliki personel yang tersedia dan siap bertugas, namun masihterdapat penyesuaian pada layanan pendukung tanpa mengurangi cakupan layanan.(Misalnya petugas menangani lebih dari satu area pendukung atau dilakukan penyesuaianpenempatan personel pada kegiatan dengan prioritas lebih rendah.)"
                            ],
                            [
                                "id" => 215,
                                "skor" => 2,
                                "deskripsi" => "Sebagian layanan belum memiliki personel yang tersedia atau siap bertugas sehinggacakupan layanan berkurang pada sebagian area atau waktu tertentu. (Misalnya salah satuarea pelayanan belum memiliki petugas pada awal operasional, kendaraan belum memilikipengemudi sesuai jadwal, atau permintaan layanan teknis menunggu ketersediaanpersonel.)"
                            ],
                            [
                                "id" => 216,
                                "skor" => 1,
                                "deskripsi" => "Sebagian layanan belum memiliki personel yang tersedia atau siap bertugas sehinggacakupan layanan berkurang pada sebagian area atau waktu tertentu. (Misalnya salah satuarea pelayanan belum memiliki petugas pada awal operasional, kendaraan belum memilikipengemudi sesuai jadwal, atau permintaan layanan teknis menunggu ketersediaanpersonel.)"
                            ]
                        ]
                    ],
                    [
                        "id" => 55,
                        "title" => "Kualitas Layanan Tim",
                        "deskripsi" => "Kemampuan memastikan hasil kerja tenaga outsourcing memenuhi standar layanan yang telahditetapkan sesuai jenis pekerjaannya.",
                        "kelompok_jabatan" => "Supervisory Support",
                        "behaviorals" => [
                            [
                                "id" => 217,
                                "skor" => 4,
                                "deskripsi" => "Seluruh hasil kerja tim memenuhi standar layanan yang ditetapkan tanpa ditemukanketidaksesuaian. (Misalnyaseluruh area memenuhi standar kebersihan, seluruh ruang rapatsiap digunakan, seluruh kendaraan siap operasional, pelayanan resepsionis sesuai standar,dan hasil pekerjaan teknisi memenuhi spesifikasi yang ditetapkan.)"
                            ],
                            [
                                "id" => 218,
                                "skor" => 3,
                                "deskripsi" => "Hasil kerja utama memenuhi standar layanan, namun masih terdapat ketidaksesuaian padaaspek pendukung yang tidak memengaruhi kualitas layanan secara keseluruhan.(Misalnya area utama telah memenuhi standar kebersihan meskipun area pendukung masihmemerlukan perapihan, atau ruang rapat siap digunakan meskipun penataan perlengkapanpendukung belum sepenuhnya selesai.)"
                            ],
                            [
                                "id" => 219,
                                "skor" => 2,
                                "deskripsi" => "Sebagian hasil kerja belum memenuhi standar layanan sehingga masih diperlukanperbaikan pada sebagian pekerjaan. (Misalnya sebagian area belum memenuhi standarkebersihan, sebagian kendaraan belum siap digunakan, atau sebagian pekerjaan teknismemerlukan perbaikan ulang.)"
                            ],
                            [
                                "id" => 220,
                                "skor" => 1,
                                "deskripsi" => "Sebagian besar hasil kerja tidak memenuhi standar layanan sehingga kebutuhan penggunatidak dapat dipenuhi. (Misalnya ruang rapat tidak siap digunakan, kendaraan tidak siapoperasional, atau gangguan teknis masih terjadi setelah pekerjaan dinyatakan selesai.)"
                            ]
                        ]
                    ],
                    [
                        "id" => 56,
                        "title" => "Penyelesaian Keluhan dan Permasalahan",
                        "deskripsi" => "Kemampuan memastikan setiap keluhan maupun permasalahan operasional terselesaikansehingga kondisi layanan kembali memenuhi standar.",
                        "kelompok_jabatan" => "Supervisory Support",
                        "behaviorals" => [
                            [
                                "id" => 221,
                                "skor" => 4,
                                "deskripsi" => "Seluruh keluhan dan permasalahan operasional terselesaikan sehingga kondisi layanankembali memenuhi standar. (Misalnya area kembali bersih sesuai standar, kendaraankembali siap digunakan, gangguan teknis telah berfungsi normal, atau pelayanan kembaliberjalan sesuai ketentuan.)"
                            ],
                            [
                                "id" => 222,
                                "skor" => 3,
                                "deskripsi" => "Seluruh permasalahan utama telah terselesaikan sehingga layanan kembali berjalan, namunmasih terdapat penyempurnaan pada aspek pendukung. (Misalnya fungsi utama peralatantelah pulih meskipun perbaikan estetika dijadwalkan kemudian, atau layanan telah kembalinormal sementara dokumentasi penyelesaian masih dilengkapi.)"
                            ],
                            [
                                "id" => 223,
                                "skor" => 2,
                                "deskripsi" => "Sebagian keluhan atau permasalahan telah terselesaikan, namun masih terdapat kondisiyang belum memenuhi standar layanan. (Misalnya gangguan teknis masih muncul padakondisi tertentu, sebagian area belum kembali memenuhi standar kebersihan, atau keluhanserupa masih terjadi.)"
                            ],
                            [
                                "id" => 224,
                                "skor" => 1,
                                "deskripsi" => "Keluhan atau permasalahan operasional tidak terselesaikan sehingga mengganggupelayanan atau menimbulkan keluhan lanjutan (misalnya area tetap tidak siap digunakan,layanan terhenti karena kekurangan personel, atau gangguan teknis tidak ditindaklanjuti)."
                            ]
                        ]
                    ],
                    [
                        "id" => 57,
                        "title" => "Keberlangsungan Layanan Operasional",
                        "deskripsi" => "Kemampuan memastikan seluruh layanan tetap tersedia sesuai kebutuhan meskipun terjadiperubahan kondisi operasional.",
                        "kelompok_jabatan" => "Supervisory Support",
                        "behaviorals" => [
                            [
                                "id" => 225,
                                "skor" => 4,
                                "deskripsi" => "Seluruh layanan tetap tersedia sesuai kebutuhan operasional meskipun terjadi perubahankondisi. (Misalnyaseluruh kegiatan tetap memperoleh layanan sesuai kebutuhan, seluruharea tetap terlayani, dan seluruh permintaan layanan operasional tetap terpenuhi.)"
                            ],
                            [
                                "id" => 226,
                                "skor" => 3,
                                "deskripsi" => "Seluruh layanan utama tetap tersedia sesuai kebutuhan, namun terdapat penyesuaian padalayanan pendukung tanpa mengurangi layanan utama. (Misalnya layanan pada areaprioritas tetap terpenuhi sementara layanan pendukung dijadwalkan ulang, ataupenyesuaian urutan pelayanan dilakukan tanpa mengurangi kebutuhan utama pengguna.)"
                            ],
                            [
                                "id" => 227,
                                "skor" => 2,
                                "deskripsi" => "Sebagian layanan masih tersedia, namun terdapat layanan yang mengalami keterlambatanatau pengurangan cakupan akibat perubahan kondisi operasional. (Misalnya sebagian areaterlambat memperoleh layanan, sebagian kegiatan mengalami penyesuaian waktu layanan,atau sebagian permintaan layanan belum dapat dipenuhi pada waktu yang dibutuhkan.)"
                            ],
                            [
                                "id" => 228,
                                "skor" => 1,
                                "deskripsi" => "Sebagian layanan tidak dapat dipertahankan sehingga kebutuhan operasional tidakterpenuhi. (Misalnya kegiatan tidak memperoleh dukungan layanan, area tertentu tidakterlayani, atau operasional terhenti pada sebagian layanan.)"
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $siklusId = Siklus::where('is_active', 1)->first()->id;

        foreach ($data as $pilarData) {

            $bobot = BobotSkor::create([
                'siklus_id' => $siklusId,
                'title' => $pilarData['title'],
                'kode_bobot' => $pilarData['slug'],
                'bobot' => $pilarData['bobot'],
            ]);

            $pilar = Pilar::create([
                'title' => $pilarData['title'],
                'bobot_skor_id' => $bobot->id,
            ]);

            foreach ($pilarData['indikators'] as $indikatorData) {

                $kelompokJabatan = KelompokJabatan::firstOrCreate([
                    'nama_kelompok' => $indikatorData['kelompok_jabatan'],
                ]);

                $indikator = Indikator::create([
                    'pilar_id' => $pilar->id,
                    'kelompok_jabatan_id' =>  $kelompokJabatan->id,
                    'title' => $indikatorData['title'],
                    'defenisi' => $indikatorData['deskripsi'],
                    'example' => [], // bisa kamu isi nanti
                ]);

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
