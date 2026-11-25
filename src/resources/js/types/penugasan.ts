export type PenugasanPeer = {
    uuid: string;
    name: string;
    image: string;
    nama_jabatan: string;
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
