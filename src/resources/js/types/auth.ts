export type User = {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    two_factor_enabled?: boolean;
    created_at: string;
    updated_at: string;
    nip?: string;
    nip_sso?: string;
    role?: string[];
    userable_type?: string;
    userable_id?: number | string | null;
    userable?: OutsourcingUserable | EmployeeUserable | null;
};

export type OutsourcingUserable = {
    id: number;
    uuid: string;
    name: string;
    image: string;
    nip?: string | null;
    jabatan?: { id: number; nama_jabatan: string } | null;
    biro?: { kode_biro: string; nama_biro: string } | null;
};

export type EmployeeUserable = {
    id: number;
    uuid: string;
    name: string;
    image: string;
    nip?: string | null;
    jabatan?: string | null;
    biro?: { kode_biro: string; nama_biro: string } | null;
};

export type Auth = {
    user: User;
};

/* @chisel-passkeys */
export type Passkey = {
    id: number;
    name: string;
    authenticator: string | null;
    created_at_diff: string;
    last_used_at_diff: string | null;
};
/* @end-chisel-passkeys */

export type TwoFactorSetupData = {
    svg: string;
    url: string;
};

export type TwoFactorSecretKey = {
    secretKey: string;
};
