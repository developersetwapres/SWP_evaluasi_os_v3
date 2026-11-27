import CrudManager from '@/components/administrator/crud-manager';
import type {
    CrudColumn,
    CrudDialogState,
    CrudField,
    CrudOption,
} from '@/components/administrator/crud-manager';
import { dashboard } from '@/routes';
import { destroy, index, store, update } from '@/routes/kelompokJabatan';
import type { BreadcrumbItem } from '@/types/navigation';
import type { PaginatedData } from '@/types/pagination';

type KelompokJabatanRecord = {
    id: number;
    uuid?: string;
    nama_kelompok: string;
    jabatan_count?: number;
    indikator_count?: number;
};

type KelompokJabatanPageProps = {
    kelompokJabatans: PaginatedData<KelompokJabatanRecord>;
    filters: { search?: string | null };
    formOptions: Record<string, CrudOption[]>;
    initialDialog?: CrudDialogState<KelompokJabatanRecord>;
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard() },
    { title: 'Administrator', href: '#' },
    { title: 'Kelompok Jabatan Os', href: index() },
];

const columns: CrudColumn<KelompokJabatanRecord>[] = [
    { key: 'nama_kelompok', label: 'Nama Kelompok' },
    {
        key: 'jabatan_count',
        label: 'Jumlah Jabatan',
        render: (kelompok) => String(kelompok.jabatan_count ?? 0),
    },
    {
        key: 'indikator_count',
        label: 'Jumlah Indikator',
        render: (kelompok) => String(kelompok.indikator_count ?? 0),
    },
];

const fields: CrudField<KelompokJabatanRecord>[] = [
    {
        name: 'nama_kelompok',
        label: 'Nama Kelompok',
        required: true,
        placeholder: 'Masukkan nama kelompok jabatan',
    },
];

export default function KelompokJabatanPage({
    kelompokJabatans,
    filters = {},
    formOptions = {},
    initialDialog = null,
}: KelompokJabatanPageProps) {
    return (
        <CrudManager
            title="Kelompok Jabatan Os"
            description="Kelola kelompok jabatan outsourcing yang digunakan sebagai pengelompokan indikator dan jabatan."
            entityLabel="Kelompok Jabatan"
            breadcrumbs={breadcrumbs}
            itemsKey="kelompokJabatans"
            items={kelompokJabatans}
            filters={filters}
            columns={columns}
            fields={fields}
            formOptions={formOptions}
            initialDialog={initialDialog}
            emptyMessage="Belum ada data kelompok jabatan. Tambahkan kelompok baru untuk memulai."
            urls={{
                index: index.url(),
                store: store.url(),
                update: (kelompok) =>
                    update(String(kelompok.uuid ?? '')).url,
                destroy: (kelompok) =>
                    destroy(String(kelompok.uuid ?? '')).url,
            }}
        />
    );
}
