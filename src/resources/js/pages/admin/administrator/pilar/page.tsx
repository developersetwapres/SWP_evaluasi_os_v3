import CrudManager from '@/components/administrator/crud-manager';
import type {
    CrudColumn,
    CrudDialogState,
    CrudField,
    CrudOption,
} from '@/components/administrator/crud-manager';
import { dashboard } from '@/routes';
import { destroy, index, store, update } from '@/routes/pilar';
import type { BreadcrumbItem } from '@/types/navigation';
import type { PaginatedData } from '@/types/pagination';

type PilarRecord = {
    id: number;
    uuid?: string;
    title: string;
    bobot_skor_id: number;
    bobot_skor?: { id: number; title: string } | null;
};

type PilarPageProps = {
    pilars: PaginatedData<PilarRecord>;
    filters: { search?: string | null };
    formOptions: Record<string, CrudOption[]>;
    initialDialog?: CrudDialogState<PilarRecord>;
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard() },
    { title: 'Administrator', href: '#' },
    { title: 'Pilar', href: index() },
];

const columns: CrudColumn<PilarRecord>[] = [
    { key: 'title', label: 'Nama Pilar' },
    {
        key: 'bobot_skor_id',
        label: 'Bobot Skor',
        render: (pilar) => pilar.bobot_skor?.title ?? '-',
    },
];

const fields: CrudField<PilarRecord>[] = [
    {
        name: 'title',
        label: 'Nama Pilar',
        required: true,
        placeholder: 'Masukkan nama pilar',
    },
    {
        name: 'bobot_skor_id',
        label: 'Bobot Skor',
        type: 'select',
        optionsKey: 'bobotSkors',
        required: true,
        getValue: (pilar) => pilar.bobot_skor_id,
    },
];

export default function PilarPage({
    pilars,
    filters = {},
    formOptions = {},
    initialDialog = null,
}: PilarPageProps) {
    return (
        <CrudManager
            title="Pilar"
            description="Kelola data pilar penilaian beserta bobot skor yang terkait."
            entityLabel="Pilar"
            breadcrumbs={breadcrumbs}
            itemsKey="pilars"
            items={pilars}
            filters={filters}
            columns={columns}
            fields={fields}
            formOptions={formOptions}
            initialDialog={initialDialog}
            emptyMessage="Belum ada data pilar. Tambahkan pilar baru untuk memulai."
            urls={{
                index: index.url(),
                store: store.url(),
                update: (pilar) => update(String(pilar.uuid ?? '')).url,
                destroy: (pilar) => destroy(String(pilar.uuid ?? '')).url,
            }}
        />
    );
}
