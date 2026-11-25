export type PenilaianStatus = 'completed' | 'draft' | 'incomplete';

export type EvaluatorType =
    | 'atasan'
    | 'penerima_layanan1'
    | 'penerima_layanan2';

export type EvaluatorSlot = {
    name: string | null;
    uuid: string | null;
    jabatan: string | null;
};

export type AssignmentOutsourcing = {
    uuid: string;
    image: string;
    name: string;
    nama_jabatan: string | null;
    biro: string | null;
    evaluators: Record<EvaluatorType, EvaluatorSlot>;
};

export type AssignmentEvaluator = {
    uuid: string;
    name: string;
    image: string;
    jabatan: string | null;
    kode_biro: string | null;
    biro: { kode_biro: string | null; nama_biro: string | null };
};

export type AssignmentPageProps = {
    outsourcing: AssignmentOutsourcing[];
    evaluators: AssignmentEvaluator[];
    message?: string;
};

export type EvaluatorScore = {
    type: EvaluatorType;
    uuidPenugasan: string;
    evaluatorName: string | null;
    averageScore: number;
    bobot: number;
    weightedScore: number;
    status: PenilaianStatus;
    notes: string | null;
    aspects: Array<{
        id: number;
        title: string;
        averageScore: number;
        weightedScore: number;
        bobot: number;
        indicators: Array<{
            indicatorId: number;
            indicatorTitle: string | null;
            value: number | null;
        }>;
    }>;
};

export type RekapResult = {
    id: number;
    name: string;
    uuid: string;
    image: string;
    biro: string | null;
    jabatan: string | null;
    status: 'completed' | 'progress';
    finalTotalScore: number;
    evaluatorScores: EvaluatorScore[];
};

export type RekapHasilPageProps = {
    evaluationResults: RekapResult[];
    filters: { search: string; kodeBiro: string | null };
    units: Array<{ kodeBiro: string; namaBiro: string | null }>;
};

export type RankingRow = {
    nama: string;
    atasan: number;
    penerima_layanan: number;
    teman: number;
    total: number;
    ranking: number;
};

export type RankingPageProps = {
    outsourcingData: Array<{ jabatan: string; ranking: RankingRow[] }>;
    allJabatan: Array<{ id: number; nama_jabatan: string }>;
    selectedJabatanId: string;
};

export type StatusOutsourcingRow = {
    outsourcing_name: string;
    outsourcing_image: string;
    outsourcing_jabatan: string | null;
    evaluatorsAtasan: StatusEvaluatorSlot;
    evaluatorsTemanSetingkat: StatusEvaluatorSlot;
    evaluatorsPenerimaLayanan: StatusEvaluatorSlot;
};

export type StatusEvaluatorSlot = {
    name: string | null;
    image: string | null;
    uuid: string | null;
    status: PenilaianStatus | null;
};

export type StatusEvaluatorRow = {
    outsourcing_name: string | null;
    outsourcing_image: string | null;
    outsourcing_jabatan: string | null;
    tipe_penilai: EvaluatorType;
    status: PenilaianStatus;
    evaluator_name: string | null;
    evaluator_image: string | null;
    evaluator_uuid: string | null;
};

export type StatusPenilaianPageProps = {
    byOutsourcings: StatusOutsourcingRow[];
    byEvaluators: StatusEvaluatorRow[];
};

export type FeedbackPenugasan = {
    nama: string | null;
    image: string | null;
    tipe_penilai: EvaluatorType;
    catatan: string | null;
    area_pengembangan: string | null;
    kekuatan_teramati: string | null;
};

export type FeedbackOutsourcing = {
    name: string;
    image: string;
    penugasan: FeedbackPenugasan[];
};

export type FeedbackGroup = {
    jabatan: string;
    jabatan_id: number;
    evaluators: FeedbackOutsourcing[];
};

export type SaranPerbaikanPageProps = {
    Outsourcings: FeedbackGroup[];
    allJabatan: Array<{ id: number; nama_jabatan: string }>;
    selectedJabatanId: string;
};

export type UserManagementRow = {
    id: string;
    name: string;
    image: string;
    nip: string | null;
    email: string | null;
    role: string[] | null;
    is_active?: boolean;
    status?: 'active' | 'inactive';
    type: 'outsourcing' | 'pegawai';
    biro: string | null;
    kode_biro: string | null;
    jabatan: string | null;
    id_jabatan: number | string | null;
    jumlahDinilai: number;
};

export type UserManagementPageProps = {
    initialUsers: UserManagementRow[];
    totalOutsourcing: number;
    outsourcingAktif: number;
    outsourcingNonAktif: number;
    totalPegawai: number;
    pegawaiMenilai: number;
    pegawaiTidakMenilai: number;
    jabatans: Array<{ id: number; nama_jabatan: string }>;
    biros: Array<{ kode_biro: string; nama_biro: string }>;
};

export type NilaiAkhirProps = {
    name: string;
    uuid: string;
    image: string;
    jabatan: string | null;
    unit_kerja: string | null;
    status: 'completed' | 'draft';
    finalTotalScore: number;
    pilarScores: Array<{
        id: number;
        title: string;
        averageScore: number;
        weightedScore: number;
        bobot: number;
    }>;
    evaluatorScores: EvaluatorScore[];
};

export type RekapNilaiProps = {
    peraspek: {
        uuid: string;
        aspects: Array<{
            id: number;
            aspectTitle: string;
            evaluators: Array<{
                evaluatorName: string | null;
                evaluatorType: EvaluatorType;
                averageScore: number;
                weightedScore: number;
                bobot: number;
            }>;
            total: number;
        }>;
    } | null;
};

export type CatatanEvaluatorProps = {
    penugasans: Array<{
        tipe_penilai: EvaluatorType;
        catatan: string | null;
        area_pengembangan: string | null;
        kekuatan_teramati: string | null;
        evaluators: { userable: PersonSummary | null } | null;
    }>;
    uuidOs: string;
};

export type NilaiPerkriteriaProps = {
    rekapPerAspek: {
        finalTotalScore: number;
        aspects: Array<{ title: string; nilai: number; bobot: number }>;
    } | null;
    evaluationData: EvaluationPillar[];
    uuidOs: string;
};

export type PersonSummary = {
    id?: number;
    name: string;
    image?: string;
    uuid?: string;
    jabatan?: string | { nama_jabatan?: string | null } | null;
    biro?: { nama_biro?: string | null } | null;
};

export type BehavioralOption = { value: number; description: string };

export type EvaluationIndicator = {
    id: number;
    title: string;
    defenisi: string | null;
    behavioral: Array<{ skor: number; behavioral: string }>;
    penilaian: { nilai: number } | null;
};

export type EvaluationPillar = {
    id: number;
    title: string;
    bobot_skor: { bobot: number } | null;
    bobotSkor?: { bobot: number } | null;
    indikator: EvaluationIndicator[];
    kriteria?: EvaluationIndicator[];
};

export type EvaluationFormProps = {
    outsourcing: PersonSummary & { id: number };
    evaluator: PersonSummary;
    evaluationData: EvaluationPillar[];
    uuidPenugasanPeer: string;
    tipePenilai: EvaluatorType;
    overallNotes: string | null;
    developmentArea: string | null;
    observedStrengths: string | null;
};

export type ViewScoreProps = {
    outsourcing: PersonSummary & { id: number };
    evaluator: PersonSummary;
    evaluationData: EvaluationPillar[];
    overallNotes: string | null;
    tipePenilai: EvaluatorType;
    developmentArea: string | null;
    observedStrengths: string | null;
};

export type EvaluatorAssignment = {
    id: number;
    uuid: string;
    status: PenilaianStatus;
    tipe_penilai: EvaluatorType;
    outsourcings: {
        uuid: string;
        image: string;
        name: string;
        jabatan: string | null;
    };
};

export type SemesterEmployee = {
    id: number;
    uuid: string;
    outsourcings: {
        name: string;
        jabatan: string | null;
        image: string;
    };
    status: PenilaianStatus;
    tipe_penilai: EvaluatorType;
    score: number;
};

export type SemesterHistory = {
    id: string;
    name: string;
    period: string;
    status: 'active' | 'completed';
    employees: SemesterEmployee[];
};

export type ResultHistory = {
    id: string;
    name: string;
    period: string;
    status: 'active' | 'completed';
    evaluators: EvaluatorScore[];
};

export type EvaluatorPageProps = {
    penugasanPeer: EvaluatorAssignment[];
    semesterHistory: SemesterHistory[];
    resultScore: (EvaluatorScore[] & { finalTotalScore?: number }) | null;
    resultScoreHistory: ResultHistory[] | null;
    typeUser: 'outsourcing' | 'pegawai';
    siklusAktif: string;
};
