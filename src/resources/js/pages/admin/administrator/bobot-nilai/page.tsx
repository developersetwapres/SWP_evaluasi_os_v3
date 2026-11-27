import CrudManager from '@/components/administrator/crud-manager';
import type {
    CrudColumn,
    CrudDialogState,
    CrudField,
    CrudOption,
} from '@/components/administrator/crud-manager';
import { dashboard } from '@/routes';
import { home } from '@/routes/administrator';
import { destroy, index, store, update } from '@/routes/bobotSkor';
import type { BreadcrumbItem } from '@/types/navigation';
import type { PaginatedData } from '@/types/pagination';

type BobotSkorRecord = {
    id: number;
    siklus_id: number | null;
    title: string;
    kode_bobot: string;
    bobot: string | number;

    siklus?: { id: number; title: string } | null;
};

type BobotNilaiPageProps = {
    bobotSkors: PaginatedData<BobotSkorRecord>;
    filters: { search?: string | null };
    formOptions: Record<string, CrudOption[]>;
    initialDialog?: CrudDialogState<BobotSkorRecord>;
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard() },
    { title: 'Administrator', href: home() },
    { title: 'Bobot Nilai', href: index() },
];

const columns: CrudColumn<BobotSkorRecord>[] = [
    // { key: 'kode_bobot', label: 'Kode Bobot' },
    { key: 'title', label: 'Nama Bobot' },
    {
        key: 'siklus_id',
        label: 'Siklus',
        render: (bobotSkor) => bobotSkor.siklus?.title ?? '-',
    },
    {
        key: 'bobot',
        label: 'Bobot',
        render: (bobotSkor) => `${Number(bobotSkor.bobot)}%`,
    },
];

const fields: CrudField<BobotSkorRecord>[] = [
    {
        name: 'siklus_id',
        label: 'Siklus',
        type: 'select',
        optionsKey: 'sikluses',
        getValue: (bobotSkor) => bobotSkor.siklus_id ?? '',
    },
    {
        name: 'title',
        label: 'Nama Bobot',
        required: true,
        placeholder: 'Masukkan nama bobot',
    },
    {
        name: 'kode_bobot',
        label: 'Kode Bobot',
        required: true,
        placeholder: 'Contoh: atasan',
    },
    {
        name: 'bobot',
        label: 'Bobot (%)',
        type: 'number',
        required: true,
        placeholder: 'Contoh: 40',
        getValue: (bobotSkor) => bobotSkor.bobot,
    },
];

export default function BobotNilaiPage({
    bobotSkors,
    filters = {},
    formOptions = {},
    initialDialog = null,
}: BobotNilaiPageProps) {
    return (
        <CrudManager
            title="Bobot Nilai"
            description="Kelola bobot penilaian beserta siklus semester yang terkait."
            entityLabel="Bobot Nilai"
            breadcrumbs={breadcrumbs}
            itemsKey="bobotSkors"
            items={bobotSkors}
            filters={filters}
            columns={columns}
            fields={fields}
            formOptions={formOptions}
            initialDialog={initialDialog}
            emptyMessage="Belum ada data bobot nilai. Tambahkan bobot baru untuk memulai."
            urls={{
                index: index.url(),
                store: store.url(),
                update: (bobotSkor) => update(bobotSkor.id).url,
                destroy: (bobotSkor) => destroy(bobotSkor.id).url,
            }}
        />
    );
}
