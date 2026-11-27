import CrudManager from '@/components/administrator/crud-manager';
import type {
    CrudColumn,
    CrudDialogState,
    CrudField,
    CrudOption,
} from '@/components/administrator/crud-manager';
import { dashboard } from '@/routes';
import { home } from '@/routes/administrator';
import { destroy, index, store, update } from '@/routes/behavioral';
import type { BreadcrumbItem } from '@/types/navigation';
import type { PaginatedData } from '@/types/pagination';

type BehavioralRecord = {
    id: number;
    uuid?: string;
    indikator_id: number;
    behavioral: string;
    skor: number;
    indikator?: { id: number; title: string } | null;
};

type BehavioralPageProps = {
    behaviorals: PaginatedData<BehavioralRecord>;
    filters: { search?: string | null };
    formOptions: Record<string, CrudOption[]>;
    initialDialog?: CrudDialogState<BehavioralRecord>;
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard() },
    { title: 'Administrator', href: home() },
    { title: 'Behavioral', href: index() },
];

const columns: CrudColumn<BehavioralRecord>[] = [
    {
        key: 'indikator_id',
        label: 'Indikator',
        render: (behavioral) => behavioral.indikator?.title ?? '-',
    },
    { key: 'behavioral', label: 'Perilaku' },
    {
        key: 'skor',
        label: 'Skor',
        render: (behavioral) => ` ${behavioral.skor}`,
    },
];

const fields: CrudField<BehavioralRecord>[] = [
    {
        name: 'indikator_id',
        label: 'Indikator',
        type: 'select',
        optionsKey: 'indikators',
        required: true,
        getValue: (behavioral) => behavioral.indikator_id,
    },
    {
        name: 'behavioral',
        label: 'Perilaku',
        type: 'textarea',
        required: true,
        placeholder: 'Masukkan deskripsi perilaku',
    },
    {
        name: 'skor',
        label: 'Skor',
        type: 'number',
        required: true,
        defaultValue: '1',
        placeholder: '1 - 4',
        getValue: (behavioral) => behavioral.skor,
    },
];

export default function BehavioralPage({
    behaviorals,
    filters = {},
    formOptions = {},
    initialDialog = null,
}: BehavioralPageProps) {
    return (
        <CrudManager
            title="Behavioral"
            description="Kelola daftar perilaku dan skor penilaian untuk setiap indikator."
            entityLabel="Behavioral"
            breadcrumbs={breadcrumbs}
            itemsKey="behaviorals"
            items={behaviorals}
            filters={filters}
            columns={columns}
            fields={fields}
            formOptions={formOptions}
            initialDialog={initialDialog}
            emptyMessage="Belum ada data behavioral. Tambahkan behavioral baru untuk memulai."
            urls={{
                index: index.url(),
                store: store.url(),
                update: (behavioral) =>
                    update(String(behavioral.uuid ?? '')).url,
                destroy: (behavioral) =>
                    destroy(String(behavioral.uuid ?? '')).url,
            }}
        />
    );
}
