import CrudManager from '@/components/administrator/crud-manager';
import type {
    CrudColumn,
    CrudDialogState,
    CrudField,
    CrudOption,
} from '@/components/administrator/crud-manager';
import { dashboard } from '@/routes';
import { home } from '@/routes/administrator';
import { destroy, index, store, update } from '@/routes/indikator';
import type { BreadcrumbItem } from '@/types/navigation';
import type { PaginatedData } from '@/types/pagination';

type IndikatorRecord = {
    id: number;
    uuid?: string;
    pilar_id: number;
    kelompok_jabatan_id: number;
    title: string;
    defenisi: string;
    example: unknown[];
    pilar?: { id: number; title: string } | null;
    kelompok_jabatan?: { id: number; nama_kelompok: string } | null;
};

type IndikatorPageProps = {
    indikators: PaginatedData<IndikatorRecord>;
    filters: { search?: string | null };
    formOptions: Record<string, CrudOption[]>;
    initialDialog?: CrudDialogState<IndikatorRecord>;
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard() },
    { title: 'Administrator', href: home() },
    { title: 'Indikator', href: index() },
];

const columns: CrudColumn<IndikatorRecord>[] = [
    { key: 'title', label: 'Nama Indikator' },
    {
        key: 'pilar_id',
        label: 'Pilar',
        render: (indikator) => indikator.pilar?.title ?? '-',
    },
    {
        key: 'kelompok_jabatan_id',
        label: 'Kelompok Jabatan',
        render: (indikator) => indikator.kelompok_jabatan?.nama_kelompok ?? '-',
    },
];

const fields: CrudField<IndikatorRecord>[] = [
    {
        name: 'pilar_id',
        label: 'Pilar',
        type: 'select',
        optionsKey: 'pilars',
        required: true,
        getValue: (indikator) => indikator.pilar_id,
    },
    {
        name: 'kelompok_jabatan_id',
        label: 'Kelompok Jabatan',
        type: 'select',
        optionsKey: 'kelompokJabatans',
        required: true,
        getValue: (indikator) => indikator.kelompok_jabatan_id,
    },
    {
        name: 'title',
        label: 'Nama Indikator',
        required: true,
        placeholder: 'Masukkan nama indikator',
    },
    {
        name: 'defenisi',
        label: 'Definisi',
        type: 'textarea',
        required: true,
        placeholder: 'Masukkan definisi indikator',
    },
    // {
    //     name: 'example',
    //     label: 'Contoh (JSON array)',
    //     type: 'textarea',
    //     defaultValue: '[]',
    //     required: true,
    //     placeholder: '["Contoh penerapan indikator"]',
    //     getValue: (indikator) => JSON.stringify(indikator.example ?? []),
    //     parse: (value) => JSON.parse(value) as unknown,
    // },
];

export default function IndikatorPage({
    indikators,
    filters = {},
    formOptions = {},
    initialDialog = null,
}: IndikatorPageProps) {
    return (
        <CrudManager
            title="Indikator"
            description="Kelola indikator penilaian, definisi, contoh, serta relasi pilar dan kelompok jabatannya."
            entityLabel="Indikator"
            breadcrumbs={breadcrumbs}
            itemsKey="indikators"
            items={indikators}
            filters={filters}
            columns={columns}
            fields={fields}
            formOptions={formOptions}
            initialDialog={initialDialog}
            emptyMessage="Belum ada data indikator. Tambahkan indikator baru untuk memulai."
            urls={{
                index: index.url(),
                store: store.url(),
                update: (indikator) => update(String(indikator.uuid ?? '')).url,
                destroy: (indikator) =>
                    destroy(String(indikator.uuid ?? '')).url,
            }}
        />
    );
}
