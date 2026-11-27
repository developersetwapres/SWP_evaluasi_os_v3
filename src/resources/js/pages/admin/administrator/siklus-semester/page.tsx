import CrudManager from '@/components/administrator/crud-manager';
import type {
    CrudColumn,
    CrudDialogState,
    CrudField,
    CrudOption,
} from '@/components/administrator/crud-manager';
import { dashboard } from '@/routes';
import { home } from '@/routes/administrator';
import { index, store, update } from '@/routes/siklus';
import type { BreadcrumbItem } from '@/types/navigation';
import type { PaginatedData } from '@/types/pagination';

type SiklusRecord = {
    id: number;
    uuid?: string;
    title: string;
    tanggal_mulai: string;
    tanggal_selesai: string;
    is_active: boolean;
};

type SiklusPageProps = {
    sikluses: PaginatedData<SiklusRecord>;
    filters: { search?: string | null };
    formOptions: Record<string, CrudOption[]>;
    initialDialog?: CrudDialogState<SiklusRecord>;
};

const dateOnly = (value: string): string => String(value).slice(0, 10);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard() },
    { title: 'Administrator', href: home() },
    { title: 'Siklus Semester', href: index() },
];

const columns: CrudColumn<SiklusRecord>[] = [
    { key: 'title', label: 'Judul Siklus' },
    {
        key: 'tanggal_mulai',
        label: 'Tanggal Mulai',
        render: (siklus) => dateOnly(siklus.tanggal_mulai),
    },
    {
        key: 'tanggal_selesai',
        label: 'Tanggal Selesai',
        render: (siklus) => dateOnly(siklus.tanggal_selesai),
    },
    { key: 'is_active', label: 'Status' },
];

const fields: CrudField<SiklusRecord>[] = [
    {
        name: 'title',
        label: 'Judul Siklus',
        required: true,
        placeholder: 'Contoh: Semester I tahun 2026',
    },
    {
        name: 'tanggal_mulai',
        label: 'Tanggal Mulai',
        type: 'date',
        required: true,
        getValue: (siklus) => dateOnly(siklus.tanggal_mulai),
    },
    {
        name: 'tanggal_selesai',
        label: 'Tanggal Selesai',
        type: 'date',
        required: true,
        getValue: (siklus) => dateOnly(siklus.tanggal_selesai),
    },
    {
        name: 'is_active',
        label: 'Status',
        type: 'switch',
        getValue: (siklus) => (siklus.is_active ? 'true' : 'false'),
    },
];

export default function SiklusSemesterPage({
    sikluses,
    filters = {},
    formOptions = {},
    initialDialog = null,
}: SiklusPageProps) {
    return (
        <CrudManager
            title="Siklus Semester"
            description="Kelola siklus semester. Hanya satu siklus yang dapat berstatus aktif pada satu waktu."
            entityLabel="Siklus Semester"
            breadcrumbs={breadcrumbs}
            itemsKey="sikluses"
            items={sikluses}
            filters={filters}
            columns={columns}
            fields={fields}
            formOptions={formOptions}
            initialDialog={initialDialog}
            emptyMessage="Belum ada data siklus semester. Tambahkan siklus baru untuk memulai."
            canDelete={false}
            toggle={{ key: 'is_active' }}
            urls={{
                index: index.url(),
                store: store.url(),
                update: (siklus) => update(String(siklus.uuid ?? '')).url,
            }}
        />
    );
}
