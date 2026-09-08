export type PenugasanPeer = {
    uuid: string;
    name: string;
    image: string;
    nrp_os: string;
    jabatan_id: number;
    nama_jabatan: string;
    unit_kerja: string;
    status: string;
    biro?: string;
    evaluators?: {
        atasan?: {
            uuid?: string;
            name: string | null;
            jabatan: string | null;
        };
        penerima_layanan1?: {
            uuid?: string;
            name: string | null;
            jabatan: string | null;
        };
        penerima_layanan2?: {
            uuid?: string;
            name: string | null;
            jabatan: string | null;
        };
    };
};
