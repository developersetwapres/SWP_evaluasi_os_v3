import { Jabatan } from './jabatan';

export type Outsourcing = {
    uuid: string;
    name: string;
    image: string;
    nrp_os: string;
    jabatan_id: number;
    jabatan: Jabatan;
    unit_kerja: string;
    status: string;
    [key: string]: unknown;
};
