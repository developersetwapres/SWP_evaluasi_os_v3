export type PenugasanPeer = {
    uuid: string;
    name: string;
    image: string;
    nama_jabatan: string;
    biro: string | null;
    evaluators: {
        atasan: {
            uuid: string | null;
            name: string | null;
            jabatan: string | null;
        };
        penerima_layanan1: {
            uuid: string | null;
            name: string | null;
            jabatan: string | null;
        };
        penerima_layanan2: {
            uuid: string | null;
            name: string | null;
            jabatan: string | null;
        };
    };
};
