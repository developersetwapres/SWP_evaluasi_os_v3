import CrudManager from '@/components/administrator/crud-manager';
import type {
    CrudColumn,
    CrudDialogState,
    CrudField,
    CrudOption,
} from '@/components/administrator/crud-manager';
import { dashboard } from '@/routes';
import { destroy, index, store, update } from '@/routes/jabatan';
import type { BreadcrumbItem } from '@/types/navigation';
import type { PaginatedData } from '@/types/pagination';

type JabatanRecord = {
    id: number;
    kelompok_jabatan_id: number;
    nama_jabatan: string;
    kode_jabatan: string;
    kelompok_jabatan?: { id: number; nama_kelompok: string } | null;
};

type JabatanPageProps = {
    jabatans: PaginatedData<JabatanRecord>;
    filters: { search?: string | null };
    formOptions: Record<string, CrudOption[]>;
    initialDialog?: CrudDialogState<JabatanRecord>;
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard() },
    { title: 'Administrator', href: '#' },
    { title: 'Jabatan Outsourcing', href: index() },
];

const columns: CrudColumn<JabatanRecord>[] = [
    { key: 'nama_jabatan', label: 'Nama Jabatan' },
    { key: 'kode_jabatan', label: 'Kode Jabatan' },
    {
        key: 'kelompok_jabatan_id',
        label: 'Kelompok Jabatan',
        render: (jabatan) => jabatan.kelompok_jabatan?.nama_kelompok ?? '-',
    },
];

const fields: CrudField<JabatanRecord>[] = [
    {
        name: 'kelompok_jabatan_id',
        label: 'Kelompok Jabatan',
        type: 'select',
        optionsKey: 'kelompokJabatans',
        required: true,
        getValue: (jabatan) => jabatan.kelompok_jabatan_id,
    },
    {
        name: 'nama_jabatan',
        label: 'Nama Jabatan',
        required: true,
        placeholder: 'Masukkan nama jabatan',
    },
    {
        name: 'kode_jabatan',
        label: 'Kode Jabatan',
        required: true,
        placeholder: 'Masukkan kode jabatan',
    },
];

export default function JabatanPage({
    jabatans,
    filters = {},
    formOptions = {},
    initialDialog = null,
}: JabatanPageProps) {
    return (
        <CrudManager
            title="Jabatan Outsourcing"
            description="Kelola master jabatan outsourcing beserta kode dan kelompok jabatannya."
            entityLabel="Jabatan"
            breadcrumbs={breadcrumbs}
            itemsKey="jabatans"
            items={jabatans}
            filters={filters}
            columns={columns}
            fields={fields}
            formOptions={formOptions}
            initialDialog={initialDialog}
            emptyMessage="Belum ada data jabatan. Tambahkan jabatan baru untuk memulai."
            urls={{
                index: index.url(),
                store: store.url(),
                update: (jabatan) => update(jabatan.id).url,
                destroy: (jabatan) => destroy(jabatan.id).url,
            }}
        />
    );
}
