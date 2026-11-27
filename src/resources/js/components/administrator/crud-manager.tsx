import { Head, router } from '@inertiajs/react';
import { LoaderCircle, Pencil, Plus, Search, Trash2 } from 'lucide-react';
import { useEffect, useState } from 'react';
import type { FormEvent, ReactNode } from 'react';
import { toast } from 'sonner';
import InputError from '@/components/input-error';
import { ServerPagination } from '@/components/penilaian/server-pagination';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Textarea } from '@/components/ui/textarea';
import AppLayoutAdministrator from '@/layouts/app-layout-administrator';
import type { BreadcrumbItem } from '@/types/navigation';
import type { PaginatedData } from '@/types/pagination';

export type CrudRecord = { id: number };

type CrudPayloadValue =
    | string
    | number
    | boolean
    | null
    | CrudPayloadValue[]
    | { [key: string]: CrudPayloadValue };

type CrudPayload = Record<string, CrudPayloadValue>;

export type CrudOption = {
    value: string | number;
    label: string;
};

export type CrudField<T extends CrudRecord> = {
    name: string;
    label: string;
    type?: 'text' | 'textarea' | 'number' | 'select';
    placeholder?: string;
    required?: boolean;
    optionsKey?: string;
    defaultValue?: string;
    getValue?: (item: T) => string | number;
    parse?: (value: string) => unknown;
};

export type CrudColumn<T extends CrudRecord> = {
    key: string;
    label: string;
    className?: string;
    render?: (item: T) => ReactNode;
};

export type CrudDialogState<T extends CrudRecord> = {
    mode: 'create' | 'edit';
    item?: T | null;
} | null;

type CrudManagerProps<T extends CrudRecord> = {
    title: string;
    description: string;
    entityLabel: string;
    breadcrumbs: BreadcrumbItem[];
    itemsKey: string;
    items: PaginatedData<T>;
    filters: { search?: string | null };
    columns: CrudColumn<T>[];
    fields: CrudField<T>[];
    formOptions: Record<string, CrudOption[]>;
    urls: {
        index: string;
        store: string;
        update: (item: T) => string;
        destroy: (item: T) => string;
    };
    initialDialog?: CrudDialogState<T>;
    emptyMessage?: string;
};

function fieldValue<T extends CrudRecord>(
    item: T | null | undefined,
    field: CrudField<T>,
): string {
    if (!item) {
        return '';
    }

    if (field.getValue) {
        return String(field.getValue(item));
    }

    const raw = (item as unknown as Record<string, unknown>)[field.name];

    return raw === null || raw === undefined ? '' : String(raw);
}

export default function CrudManager<T extends CrudRecord>({
    title,
    description,
    entityLabel,
    breadcrumbs,
    itemsKey,
    items,
    filters = {},
    columns,
    fields,
    formOptions,
    urls,
    initialDialog = null,
    emptyMessage = 'Belum ada data yang ditemukan.',
}: CrudManagerProps<T>) {
    const [searchTerm, setSearchTerm] = useState(filters.search ?? '');
    const [dialog, setDialog] = useState<CrudDialogState<T>>(
        () => initialDialog,
    );
    const [form, setForm] = useState<Record<string, string>>({});
    const [errors, setErrors] = useState<Record<string, string>>({});
    const [saving, setSaving] = useState(false);
    const [deleteTarget, setDeleteTarget] = useState<T | null>(null);
    const [deletingId, setDeletingId] = useState<number | null>(null);

    useEffect(() => {
        // Keep the dialog in sync with the state provided by a page visit.
        // eslint-disable-next-line react-hooks/set-state-in-effect
        setDialog(initialDialog);
    }, [initialDialog]);

    const buildForm = (item?: T | null): Record<string, string> =>
        Object.fromEntries(
            fields.map((field) => [
                field.name,
                item ? fieldValue(item, field) : (field.defaultValue ?? ''),
            ]),
        );

    const closeDialog = () => {
        if (saving) {
            return;
        }

        setDialog(null);
        setErrors({});
    };

    const openCreate = () => {
        setForm(buildForm());
        setErrors({});
        setDialog({ mode: 'create' });
    };

    const openEdit = (item: T) => {
        setForm(buildForm(item));
        setErrors({});
        setDialog({ mode: 'edit', item });
    };

    const visitItems = (search: string, page = 1) => {
        router.get(
            urls.index,
            {
                page,
                ...(search.trim() ? { search: search.trim() } : {}),
            },
            {
                only: [itemsKey, 'filters', 'initialDialog'],
                preserveScroll: true,
                preserveState: true,
            },
        );
    };

    const submitSearch = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        visitItems(searchTerm);
    };

    const submitForm = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        if (!dialog || saving) {
            return;
        }

        const payload: CrudPayload = {};

        for (const field of fields) {
            const raw = form[field.name] ?? '';

            if (field.parse) {
                try {
                    payload[field.name] = field.parse(raw) as CrudPayloadValue;
                } catch {
                    setErrors({
                        [field.name]: `${field.label} tidak berupa format yang valid.`,
                    });
                    toast.error(
                        `${field.label} tidak berupa format yang valid.`,
                    );

                    return;
                }

                continue;
            }

            payload[field.name] = field.type === 'number' ? Number(raw) : raw;
        }

        const isEdit = dialog.mode === 'edit' && dialog.item !== null;
        const target = dialog.item;

        setSaving(true);

        const options = {
            only: [itemsKey, 'filters', 'initialDialog'],
            preserveScroll: true,
            onSuccess: () => {
                setDialog(null);
                setErrors({});
                toast.success(
                    `${entityLabel} berhasil ${isEdit ? 'diperbarui' : 'dibuat'}.`,
                );
            },
            onError: (validationErrors: Record<string, string>) => {
                setErrors(validationErrors);
                toast.error(
                    Object.values(validationErrors)[0] ??
                        `${entityLabel} gagal disimpan. Silakan periksa kembali data yang diisi.`,
                );
            },
            onHttpException: (response: { status: number }) => {
                toast.error(
                    `${entityLabel} gagal disimpan (HTTP ${response.status}).`,
                );

                return true;
            },
            onNetworkError: () => {
                toast.error(`Koneksi gagal. ${entityLabel} belum disimpan.`);
            },
            onFinish: () => setSaving(false),
        };

        if (isEdit && target) {
            router.put(urls.update(target), payload, options);
        } else {
            router.post(urls.store, payload, options);
        }
    };

    const confirmDelete = () => {
        if (!deleteTarget || deletingId !== null) {
            return;
        }

        setDeletingId(deleteTarget.id);

        router.delete(urls.destroy(deleteTarget), {
            only: [itemsKey, 'filters', 'initialDialog'],
            preserveScroll: true,
            onSuccess: () => {
                setDeleteTarget(null);
                toast.success(`${entityLabel} berhasil dihapus.`);
            },
            onError: () => {
                toast.error(`${entityLabel} gagal dihapus. Silakan coba lagi.`);
            },
            onHttpException: (response: { status: number }) => {
                toast.error(
                    `${entityLabel} gagal dihapus (HTTP ${response.status}).`,
                );

                return true;
            },
            onNetworkError: () => {
                toast.error(`Koneksi gagal. ${entityLabel} belum dihapus.`);
            },
            onFinish: () => setDeletingId(null),
        });
    };

    const isBusy = saving || deletingId !== null;

    return (
        <AppLayoutAdministrator breadcrumbs={breadcrumbs}>
            <Head title={title} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl bg-linear-to-br from-white to-blue-100 p-4">
                <Card>
                    <CardHeader>
                        <div className="flex flex-col gap-4">
                            <div>
                                <CardTitle>{title}</CardTitle>
                                <CardDescription>{description}</CardDescription>
                            </div>
                            <form
                                className="flex flex-col gap-3 md:flex-row md:items-center md:justify-between"
                                onSubmit={submitSearch}
                            >
                                <div className="flex w-full max-w-lg items-center gap-2">
                                    <Input
                                        placeholder={`Cari ${entityLabel.toLowerCase()}...`}
                                        value={searchTerm}
                                        onChange={(event) =>
                                            setSearchTerm(event.target.value)
                                        }
                                        className="min-w-0 flex-1 bg-white"
                                        disabled={isBusy || dialog !== null}
                                    />
                                    <Button
                                        type="submit"
                                        className="gap-2"
                                        disabled={isBusy || dialog !== null}
                                    >
                                        <Search className="h-4 w-4" />
                                        Cari
                                    </Button>
                                </div>
                                <Button
                                    type="button"
                                    className="gap-2"
                                    onClick={openCreate}
                                    disabled={isBusy || dialog !== null}
                                >
                                    <Plus className="h-4 w-4" />
                                    Tambah {entityLabel}
                                </Button>
                            </form>
                        </div>
                    </CardHeader>

                    <CardContent>
                        <div className="rounded-md border">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>No</TableHead>
                                        {columns.map((column) => (
                                            <TableHead
                                                key={column.key}
                                                className={column.className}
                                            >
                                                {column.label}
                                            </TableHead>
                                        ))}
                                        <TableHead className="text-right">
                                            Aksi
                                        </TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {items.data.length === 0 ? (
                                        <TableRow>
                                            <TableCell
                                                colSpan={columns.length + 2}
                                                className="py-8 text-center text-gray-500"
                                            >
                                                {emptyMessage}
                                            </TableCell>
                                        </TableRow>
                                    ) : (
                                        items.data.map((item, index) => (
                                            <TableRow key={item.id}>
                                                <TableCell className="py-1.5">
                                                    {(items.from ?? 1) + index}
                                                </TableCell>
                                                {columns.map((column) => (
                                                    <TableCell
                                                        key={column.key}
                                                        className={
                                                            column.className +
                                                            ' py-1.5'
                                                        }
                                                    >
                                                        {column.render
                                                            ? column.render(
                                                                  item,
                                                              )
                                                            : String(
                                                                  (
                                                                      item as unknown as Record<
                                                                          string,
                                                                          unknown
                                                                      >
                                                                  )[
                                                                      column.key
                                                                  ] ?? '-',
                                                              )}
                                                    </TableCell>
                                                ))}
                                                <TableCell className="py-1.5">
                                                    <div className="flex items-center justify-end gap-1">
                                                        <Button
                                                            type="button"
                                                            variant="ghost"
                                                            size="icon"
                                                            title="Edit data"
                                                            aria-label={`Edit ${entityLabel}`}
                                                            disabled={isBusy}
                                                            onClick={() =>
                                                                openEdit(item)
                                                            }
                                                        >
                                                            <Pencil className="h-4 w-4" />
                                                        </Button>
                                                        <Button
                                                            type="button"
                                                            variant="ghost"
                                                            size="icon"
                                                            title="Hapus data"
                                                            aria-label={`Hapus ${entityLabel}`}
                                                            className="text-red-600 hover:text-red-700"
                                                            disabled={isBusy}
                                                            onClick={() =>
                                                                setDeleteTarget(
                                                                    item,
                                                                )
                                                            }
                                                        >
                                                            <Trash2 className="h-4 w-4" />
                                                        </Button>
                                                    </div>
                                                </TableCell>
                                            </TableRow>
                                        ))
                                    )}
                                </TableBody>
                            </Table>
                        </div>

                        <ServerPagination
                            meta={items}
                            onPageChange={(page) =>
                                visitItems(filters.search ?? '', page)
                            }
                            disabled={isBusy || dialog !== null}
                        />
                    </CardContent>
                </Card>
            </div>

            <Dialog
                open={dialog !== null}
                onOpenChange={(open) => {
                    if (!open) {
                        closeDialog();
                    }
                }}
            >
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>
                            {dialog?.mode === 'edit'
                                ? `Edit ${entityLabel}`
                                : `Tambah ${entityLabel}`}
                        </DialogTitle>
                        <DialogDescription>
                            Isi data berikut dengan benar, lalu simpan
                            perubahan.
                        </DialogDescription>
                    </DialogHeader>
                    <form className="space-y-4" onSubmit={submitForm}>
                        {fields.map((field) => (
                            <div className="space-y-2" key={field.name}>
                                <Label htmlFor={`crud-${field.name}`}>
                                    {field.label}
                                    {field.required ? ' *' : ''}
                                </Label>
                                {field.type === 'textarea' ? (
                                    <Textarea
                                        id={`crud-${field.name}`}
                                        value={form[field.name] ?? ''}
                                        placeholder={field.placeholder}
                                        onChange={(event) =>
                                            setForm((current) => ({
                                                ...current,
                                                [field.name]:
                                                    event.target.value,
                                            }))
                                        }
                                        disabled={saving}
                                    />
                                ) : field.type === 'select' ? (
                                    <Select
                                        value={form[field.name] || undefined}
                                        disabled={saving}
                                        onValueChange={(value) =>
                                            setForm((current) => ({
                                                ...current,
                                                [field.name]: value,
                                            }))
                                        }
                                    >
                                        <SelectTrigger
                                            id={`crud-${field.name}`}
                                            className="w-full bg-white"
                                        >
                                            <SelectValue
                                                placeholder={
                                                    field.placeholder ??
                                                    `Pilih ${field.label.toLowerCase()}`
                                                }
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {(
                                                formOptions[
                                                    field.optionsKey ?? ''
                                                ] ?? []
                                            ).map((option) => (
                                                <SelectItem
                                                    key={String(option.value)}
                                                    value={String(option.value)}
                                                >
                                                    {option.label}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                ) : (
                                    <Input
                                        id={`crud-${field.name}`}
                                        type={
                                            field.type === 'number'
                                                ? 'number'
                                                : 'text'
                                        }
                                        value={form[field.name] ?? ''}
                                        placeholder={field.placeholder}
                                        onChange={(event) =>
                                            setForm((current) => ({
                                                ...current,
                                                [field.name]:
                                                    event.target.value,
                                            }))
                                        }
                                        disabled={saving}
                                    />
                                )}
                                <InputError message={errors[field.name]} />
                            </div>
                        ))}
                        <DialogFooter>
                            <Button
                                type="button"
                                variant="outline"
                                onClick={closeDialog}
                                disabled={saving}
                            >
                                Batal
                            </Button>
                            <Button type="submit" disabled={saving}>
                                {saving ? (
                                    <LoaderCircle className="h-4 w-4 animate-spin" />
                                ) : null}
                                {saving ? 'Menyimpan...' : 'Simpan'}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>

            <AlertDialog
                open={deleteTarget !== null}
                onOpenChange={(open) => {
                    if (!open && deletingId === null) {
                        setDeleteTarget(null);
                    }
                }}
            >
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>
                            Hapus {entityLabel} ini?
                        </AlertDialogTitle>
                        <AlertDialogDescription>
                            Data {entityLabel.toLowerCase()} yang dipilih akan
                            dihapus permanen. Tindakan ini tidak dapat
                            dibatalkan.
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel disabled={deletingId !== null}>
                            Batal
                        </AlertDialogCancel>
                        <AlertDialogAction
                            className="gap-2"
                            disabled={deletingId !== null}
                            onClick={(event) => {
                                event.preventDefault();
                                confirmDelete();
                            }}
                        >
                            {deletingId !== null ? (
                                <LoaderCircle className="h-4 w-4 animate-spin" />
                            ) : (
                                <Trash2 className="h-4 w-4" />
                            )}
                            Hapus permanen
                        </AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </AppLayoutAdministrator>
    );
}
