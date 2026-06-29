'use client';

import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
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
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { useToast } from '@/hooks/use-toast';
import AdminLayout from '@/layouts/app/app-adminkmz-layout';
import { store } from '@/routes/penugasan';
import type { PenugasanPeer } from '@/types';
import { router } from '@inertiajs/react';
import { Separator } from '@radix-ui/react-separator';
import {
    AlertCircle,
    CheckCircle,
    Crown,
    Edit,
    Search,
    Shield,
    UserCheck,
    UserPlus,
    Users,
    Users2,
} from 'lucide-react';
import { useState } from 'react';

interface initialData {
    evaluators: any;
    outsourcing: PenugasanPeer[];
}

interface SearchableSelectProps {
    items: any[];
    value: string;
    onValueChange: (value: string) => void;
    placeholder: string;
    getLabel: (item: any) => string;
    getSubLabel?: (item: any) => string;
}

function SearchableSelect({
    items,
    value,
    onValueChange,
    placeholder,
    getLabel,
    getSubLabel,
}: SearchableSelectProps) {
    const [searchTerm, setSearchTerm] = useState('');
    const [isOpen, setIsOpen] = useState(false);

    const filteredItems = items.filter((item) =>
        getLabel(item).toLowerCase().includes(searchTerm.toLowerCase()),
    );

    const selectedItem = items.find((item) => item.uuid === value);

    const displayValue =
        searchTerm !== ''
            ? searchTerm
            : selectedItem
              ? getLabel(selectedItem)
              : '';

    const handleSelect = (uuid: string) => {
        onValueChange(uuid);
        setSearchTerm('');
        setIsOpen(false);
    };

    return (
        <div className="relative">
            <Input
                placeholder={
                    selectedItem ? getLabel(selectedItem) : placeholder
                }
                // value={displayValue}
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                onClick={() => setIsOpen(true)}
                className="mb-2"
            />
            {isOpen && (
                <div className="absolute top-12 right-0 left-0 z-50 max-h-48 overflow-y-auto rounded-md border border-gray-200 bg-white shadow-lg">
                    {filteredItems.length > 0 ? (
                        filteredItems.map((item) => (
                            <div
                                key={item.uuid}
                                onClick={() => handleSelect(item.uuid)}
                                className={`cursor-pointer border-b px-4 py-2 last:border-b-0 hover:bg-gray-100 ${
                                    value === item.uuid ? 'bg-blue-50' : ''
                                }`}
                            >
                                <div className="flex flex-col">
                                    <span className="text-sm font-medium">
                                        {getLabel(item)}
                                    </span>
                                    {getSubLabel && (
                                        <span className="text-xs text-gray-500">
                                            {getSubLabel(item)}
                                        </span>
                                    )}
                                </div>
                            </div>
                        ))
                    ) : (
                        <div className="px-4 py-2 text-sm text-gray-500">
                            Tidak ada hasil
                        </div>
                    )}
                </div>
            )}

            {selectedItem && (
                <div className="mt-2 rounded border border-blue-200 bg-blue-50 p-2">
                    <p className="text-sm font-medium text-blue-900">
                        {getLabel(selectedItem)}
                    </p>
                    {getSubLabel && (
                        <p className="text-xs text-blue-700">
                            {getSubLabel(selectedItem)}
                        </p>
                    )}
                </div>
            )}
        </div>
    );
}

export default function PeerAssignment({
    outsourcing,
    evaluators,
}: initialData) {
    const [searchTerm, setSearchTerm] = useState('');
    const [isDialogOpen, setIsDialogOpen] = useState(false);
    const [selectedEmployee, setSelectedEmployee] = useState<{
        uuid: string;
        name: string;
        biro: string;
        jabatan: string;
        evaluators: any;
    } | null>(null);

    const [selectedEvaluators, setSelectedEvaluators] = useState({
        atasan: '',
        penerima_layanan: '',
        teman_setingkat: '',
    });

    const { toast } = useToast();

    const filteredSearchEmployees = outsourcing?.filter(
        (emp: any) =>
            emp.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            emp?.biro?.toLowerCase().includes(searchTerm.toLowerCase()),
    );

    const handleAssignEvaluators = () => {
        if (
            selectedEmployee &&
            (selectedEvaluators.atasan ||
                selectedEvaluators.penerima_layanan ||
                selectedEvaluators.teman_setingkat)
        ) {
            router.post(store.url(selectedEmployee.uuid), selectedEvaluators, {
                onSuccess: () => {
                    setIsDialogOpen(false);
                    setSelectedEmployee(null);
                    setSelectedEvaluators({
                        atasan: '',
                        penerima_layanan: '',
                        teman_setingkat: '',
                    });

                    // Show success message
                    toast({
                        title: 'Penugasan Berhasil',
                        description: `Berhasil menugaskan ................ untuk menilai ................`,
                    });
                },
                onError: (err) => {
                    console.log(err);
                },
            });
        }
    };

    function hitungEvaluator(employees: PenugasanPeer[]) {
        let sudah = 0;
        let belum = 0;

        for (const emp of employees) {
            const ev = emp.evaluators || {};
            if (
                ev.atasan?.name &&
                ev.penerima_layanan?.name &&
                ev.teman_setingkat?.name
            ) {
                sudah++;
            } else {
                belum++;
            }
        }

        return { sudah, belum };
    }

    const { sudah, belum } = hitungEvaluator(outsourcing);

    return (
        <AdminLayout>
            <div className="space-y-6">
                {/* Header Card */}
                <Card className="bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
                    <CardHeader>
                        <CardTitle className="text-2xl">
                            Penugasan Evaluator
                        </CardTitle>
                        <CardDescription className="text-indigo-100">
                            Tetapkan rekan kerja sebagai evaluator peer untuk
                            setiap karyawan yang dievaluasi.
                        </CardDescription>
                    </CardHeader>
                </Card>

                {/* Peer Assignment Management */}
                <Card>
                    <CardHeader>
                        <div className="flex items-center justify-between gap-4">
                            <div className="relative w-full sm:w-80">
                                <Search className="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 transform text-gray-400" />
                                <Input
                                    placeholder="Cari pegawai, unit, atau perusahaan..."
                                    value={searchTerm}
                                    onChange={(e) =>
                                        setSearchTerm(e.target.value)
                                    }
                                    className="pl-10"
                                />
                            </div>

                            <div className="flex items-center gap-4">
                                <div className="text-center">
                                    <div className="text-2xl font-bold text-green-600">
                                        {sudah}
                                    </div>
                                    <div className="text-sm text-gray-500">
                                        Sudah Ditugaskan
                                    </div>
                                </div>
                                <div className="text-center">
                                    <div className="text-2xl font-bold text-orange-600">
                                        {belum}
                                    </div>
                                    <div className="text-sm text-gray-500">
                                        Belum Ditugaskan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardHeader>

                    <CardContent>
                        {/* Employee Cards */}
                        <div className="space-y-4">
                            <div className="grid gap-3 lg:grid-cols-2">
                                {filteredSearchEmployees?.map(
                                    (penugasan: PenugasanPeer, index) => {
                                        const assignedCount = Object.values(
                                            penugasan.evaluators || {},
                                        ).filter((e: any) => e?.name).length;

                                        const completionPercentage =
                                            (assignedCount / 3) * 100;

                                        return (
                                            <Card
                                                key={penugasan.uuid || index}
                                                className="gap-0 border-l-4 border-l-indigo-500 pt-0.5 pb-0"
                                            >
                                                <CardContent className="p-6">
                                                    <div className="flex items-start justify-between">
                                                        <div className="flex-1">
                                                            <div className="mb-3 flex items-center space-x-3">
                                                                <Avatar className="h-12 w-12">
                                                                    <AvatarImage
                                                                        src={
                                                                            `/storage/${penugasan.image || '/placeholder.svg'}` ||
                                                                            '/placeholder.svg'
                                                                        }
                                                                        alt={
                                                                            penugasan.name
                                                                        }
                                                                    />
                                                                    <AvatarFallback>
                                                                        {penugasan.name
                                                                            .split(
                                                                                ' ',
                                                                            )
                                                                            .map(
                                                                                (
                                                                                    n: any,
                                                                                ) =>
                                                                                    n[0],
                                                                            )
                                                                            .join(
                                                                                '',
                                                                            )
                                                                            .substring(
                                                                                0,
                                                                                2,
                                                                            )
                                                                            .toUpperCase()}
                                                                    </AvatarFallback>
                                                                </Avatar>
                                                                <div>
                                                                    <h3 className="text-lg font-semibold text-gray-900">
                                                                        {
                                                                            penugasan.name
                                                                        }
                                                                    </h3>
                                                                    <p className="mb-1.5 text-sm text-gray-600">
                                                                        {
                                                                            penugasan.biro
                                                                        }
                                                                    </p>
                                                                    <Badge className="bg-blue-100 text-xs text-blue-800">
                                                                        {
                                                                            penugasan.nama_jabatan
                                                                        }
                                                                    </Badge>
                                                                </div>

                                                                {/* Kanan: Progress */}
                                                                <div className="ml-auto text-right">
                                                                    <div className="text-sm font-medium text-gray-700">
                                                                        Progress:{' '}
                                                                        {
                                                                            assignedCount
                                                                        }
                                                                        /3
                                                                    </div>
                                                                    <div className="mt-1 flex h-2 w-24 rounded-full bg-gray-200">
                                                                        <div
                                                                            className={`h-2 rounded-full ${
                                                                                completionPercentage ===
                                                                                100
                                                                                    ? 'bg-green-500'
                                                                                    : completionPercentage >
                                                                                        0
                                                                                      ? 'bg-yellow-500'
                                                                                      : 'bg-red-500'
                                                                            }`}
                                                                            style={{
                                                                                width: `${completionPercentage}%`,
                                                                            }}
                                                                        ></div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {/* Evaluator Assignment Status */}
                                                            <div className="mt-5 rounded-lg bg-gray-50 p-4">
                                                                <div className="mb-3 flex items-center justify-between">
                                                                    <h4 className="font-medium text-gray-900">
                                                                        Status
                                                                        Penugasan
                                                                        Evaluator
                                                                    </h4>
                                                                    <Button
                                                                        onClick={() => {
                                                                            setSelectedEmployee(
                                                                                {
                                                                                    uuid: penugasan.uuid,
                                                                                    name: penugasan.name,
                                                                                    biro: penugasan.biro,
                                                                                    evaluators:
                                                                                        penugasan.evaluators,
                                                                                    jabatan:
                                                                                        penugasan.nama_jabatan,
                                                                                },
                                                                            );
                                                                            setSelectedEvaluators(
                                                                                {
                                                                                    atasan:
                                                                                        penugasan
                                                                                            ?.evaluators
                                                                                            ?.atasan
                                                                                            ?.uuid ||
                                                                                        '',
                                                                                    penerima_layanan:
                                                                                        penugasan
                                                                                            ?.evaluators
                                                                                            ?.penerima_layanan
                                                                                            ?.uuid ||
                                                                                        '',
                                                                                    teman_setingkat:
                                                                                        penugasan
                                                                                            ?.evaluators
                                                                                            ?.teman_setingkat
                                                                                            ?.uuid ||
                                                                                        '',
                                                                                },
                                                                            );
                                                                            setIsDialogOpen(
                                                                                true,
                                                                            );
                                                                        }}
                                                                        className="flex items-center space-x-2"
                                                                    >
                                                                        {assignedCount >
                                                                        0 ? (
                                                                            <Edit className="h-4 w-4" />
                                                                        ) : (
                                                                            <UserPlus className="h-4 w-4" />
                                                                        )}
                                                                        <span>
                                                                            {assignedCount >
                                                                            0
                                                                                ? 'Kelola'
                                                                                : 'Tugaskan'}
                                                                        </span>
                                                                    </Button>
                                                                </div>

                                                                {/* Atasan */}
                                                                <div className="mb-2.5 flex items-center space-x-2 rounded border p-2">
                                                                    <Crown className="h-4 w-4 text-amber-600" />
                                                                    <div className="flex-1">
                                                                        <div className="text-xs font-medium text-gray-700">
                                                                            Atasan
                                                                        </div>
                                                                        {penugasan
                                                                            ?.evaluators
                                                                            ?.atasan
                                                                            ?.name ? (
                                                                            <div className="flex items-center space-x-1">
                                                                                <CheckCircle className="h-3 w-3 text-green-600" />
                                                                                <span className="text-xs font-medium text-green-700">
                                                                                    {
                                                                                        penugasan
                                                                                            .evaluators
                                                                                            .atasan
                                                                                            .name
                                                                                    }{' '}
                                                                                    -{' '}
                                                                                    {
                                                                                        penugasan
                                                                                            .evaluators
                                                                                            .atasan
                                                                                            ?.jabatan
                                                                                    }
                                                                                </span>
                                                                            </div>
                                                                        ) : (
                                                                            <div className="flex items-center space-x-1">
                                                                                <AlertCircle className="h-3 w-3 text-orange-600" />
                                                                                <span className="text-xs text-orange-700">
                                                                                    Belum
                                                                                    ditugaskan
                                                                                </span>
                                                                            </div>
                                                                        )}
                                                                    </div>
                                                                </div>

                                                                {/* Penerima Layanan */}
                                                                <div className="mb-2.5 flex items-center space-x-2 rounded border p-2">
                                                                    <Shield className="h-4 w-4 text-blue-600" />
                                                                    <div className="flex-1">
                                                                        <div className="text-xs font-medium text-gray-700">
                                                                            Penerima
                                                                            Layanan
                                                                        </div>
                                                                        {penugasan
                                                                            ?.evaluators
                                                                            ?.penerima_layanan
                                                                            ?.name ? (
                                                                            <div className="flex items-center space-x-1">
                                                                                <CheckCircle className="h-3 w-3 text-green-600" />
                                                                                <span className="text-xs font-medium text-green-700">
                                                                                    {
                                                                                        penugasan
                                                                                            .evaluators
                                                                                            .penerima_layanan
                                                                                            .name
                                                                                    }{' '}
                                                                                    -{' '}
                                                                                    {
                                                                                        penugasan
                                                                                            .evaluators
                                                                                            .penerima_layanan
                                                                                            ?.jabatan
                                                                                    }
                                                                                </span>
                                                                            </div>
                                                                        ) : (
                                                                            <div className="flex items-center space-x-1">
                                                                                <AlertCircle className="h-3 w-3 text-orange-600" />
                                                                                <span className="text-xs text-orange-700">
                                                                                    Belum
                                                                                    ditugaskan
                                                                                </span>
                                                                            </div>
                                                                        )}
                                                                    </div>
                                                                </div>

                                                                {/* Teman_setingkat */}
                                                                <div className="mb-2.5 flex items-center space-x-2 rounded border p-2">
                                                                    <Users2 className="h-4 w-4 text-green-600" />
                                                                    <div className="flex-1">
                                                                        <div className="text-xs font-medium text-gray-700">
                                                                            Teman_setingkat
                                                                            Setingkat
                                                                        </div>
                                                                        {penugasan
                                                                            ?.evaluators
                                                                            ?.teman_setingkat
                                                                            ?.name ? (
                                                                            <div className="flex items-center space-x-1">
                                                                                <CheckCircle className="h-3 w-3 text-green-600" />
                                                                                <span className="text-xs font-medium text-green-700">
                                                                                    {
                                                                                        penugasan
                                                                                            .evaluators
                                                                                            .teman_setingkat
                                                                                            .name
                                                                                    }{' '}
                                                                                    -{' '}
                                                                                    {
                                                                                        penugasan
                                                                                            .evaluators
                                                                                            .teman_setingkat
                                                                                            ?.jabatan
                                                                                    }
                                                                                </span>
                                                                            </div>
                                                                        ) : (
                                                                            <div className="flex items-center space-x-1">
                                                                                <AlertCircle className="h-3 w-3 text-orange-600" />
                                                                                <span className="text-xs text-orange-700">
                                                                                    Belum
                                                                                    ditugaskan
                                                                                </span>
                                                                            </div>
                                                                        )}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </CardContent>
                                            </Card>
                                        );
                                    },
                                )}
                            </div>
                        </div>

                        {filteredSearchEmployees?.length === 0 && (
                            <Card className="py-12 text-center">
                                <CardContent>
                                    <Users className="mx-auto mb-4 h-12 w-12 text-gray-400" />
                                    <h3 className="mb-2 text-lg font-medium text-gray-900">
                                        Tidak ada pegawai ditemukan
                                    </h3>
                                    <p className="text-gray-500">
                                        Coba ubah kata kunci pencarian Anda
                                    </p>
                                </CardContent>
                            </Card>
                        )}
                    </CardContent>
                </Card>

                <Dialog open={isDialogOpen} onOpenChange={setIsDialogOpen}>
                    <DialogContent className="max-h-[80vh] overflow-y-auto sm:max-w-[600px]">
                        <DialogHeader>
                            <DialogTitle className="flex items-center space-x-2">
                                <UserCheck className="h-5 w-5" />
                                <span>Atur Evaluator Komprehensif</span>
                            </DialogTitle>
                        </DialogHeader>

                        <div className="space-y-6 pb-4">
                            {selectedEmployee && (
                                <>
                                    <div className="mt-3 mb-8 flex items-center space-x-3 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 p-4 text-white">
                                        <div className="rounded-full bg-indigo-100 p-2">
                                            <Users className="h-5 w-5 text-blue-600" />
                                        </div>
                                        <div>
                                            <h3 className="text-lg font-semibold text-white">
                                                {selectedEmployee.name}
                                            </h3>
                                            <p className="mb-1.5 text-sm text-gray-50">
                                                {selectedEmployee.biro}
                                            </p>
                                            <Badge className="bg-blue-200 text-xs text-blue-800">
                                                {selectedEmployee.jabatan}
                                            </Badge>
                                        </div>
                                    </div>
                                    {/* Evaluator Selection */}
                                    <div className="space-y-6">
                                        {/* Atasan */}
                                        <div className="space-y-3 pe-6">
                                            <div className="mb-1 flex items-center space-x-2">
                                                <Crown className="h-5 w-5 text-amber-600" />
                                                <label className="text-sm font-medium text-gray-900">
                                                    1. Evaluator Atasan
                                                </label>
                                            </div>

                                            <div className="ml-7">
                                                <SearchableSelect
                                                    items={evaluators || []}
                                                    value={
                                                        selectedEvaluators.atasan
                                                    }
                                                    onValueChange={(value) =>
                                                        setSelectedEvaluators(
                                                            (prev) => ({
                                                                ...prev,
                                                                atasan: value,
                                                            }),
                                                        )
                                                    }
                                                    placeholder="Cari dan pilih atasan sebagai evaluator..."
                                                    getLabel={(e) => e.name}
                                                    getSubLabel={(e) =>
                                                        `${e.jabatan} • ${e.biro?.nama_biro || ''}`
                                                    }
                                                />
                                            </div>
                                        </div>

                                        <Separator />

                                        {/* Penerima Layanan */}
                                        <div className="space-y-3 pe-6">
                                            <div className="mb-1 flex items-center space-x-2">
                                                <Shield className="h-5 w-5 text-blue-600" />
                                                <label className="text-sm font-medium text-gray-900">
                                                    2. Evaluator Penerima
                                                    Layanan
                                                </label>
                                            </div>

                                            <div className="ml-7">
                                                <SearchableSelect
                                                    items={evaluators || []}
                                                    value={
                                                        selectedEvaluators.penerima_layanan
                                                    }
                                                    onValueChange={(value) => {
                                                        setSelectedEvaluators(
                                                            (prev) => ({
                                                                ...prev,
                                                                penerima_layanan:
                                                                    value,
                                                            }),
                                                        );
                                                    }}
                                                    placeholder="Cari dan pilih penerima layanan sebagai evaluator..."
                                                    getLabel={(e) => e.name}
                                                    getSubLabel={(e) =>
                                                        `${e.jabatan} • ${e.biro?.nama_biro || ''}`
                                                    }
                                                />
                                            </div>
                                        </div>

                                        <Separator />

                                        {/* Teman Setingkat */}
                                        <div className="space-y-3 pe-6">
                                            <div className="mb-1 flex items-center space-x-2">
                                                <Users2 className="h-5 w-5 text-green-600" />
                                                <label className="text-sm font-medium text-gray-900">
                                                    3. Evaluator Teman Setingkat
                                                </label>
                                            </div>

                                            <div className="ml-7">
                                                <SearchableSelect
                                                    items={outsourcing || []}
                                                    value={
                                                        selectedEvaluators.teman_setingkat
                                                    }
                                                    onValueChange={(value) => {
                                                        setSelectedEvaluators(
                                                            (prev) => ({
                                                                ...prev,
                                                                teman_setingkat:
                                                                    value,
                                                            }),
                                                        );
                                                    }}
                                                    placeholder="Cari dan pilih teman setingkat sebagai evaluator..."
                                                    getLabel={(e) => e.name}
                                                    getSubLabel={(e) =>
                                                        `${e?.nama_jabatan || ''} • ${e?.biro || ''}`
                                                    }
                                                />
                                            </div>

                                            {outsourcing.length === 0 && (
                                                <div className="mt-2 ml-7 rounded-md border border-yellow-200 bg-yellow-50 p-3">
                                                    <p className="text-sm text-yellow-800">
                                                        Tidak ada
                                                        teman_setingkat
                                                        setingkat lain di unit
                                                        yang sama untuk
                                                        ditugaskan sebagai
                                                        evaluator.
                                                    </p>
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                </>
                            )}
                        </div>

                        <DialogFooter>
                            <Button
                                variant="outline"
                                onClick={() => {
                                    setIsDialogOpen(false);
                                }}
                            >
                                Batal
                            </Button>
                            <Button
                                onClick={handleAssignEvaluators}
                                disabled={
                                    Object.values(selectedEvaluators).filter(
                                        Boolean,
                                    ).length < 2
                                }
                            >
                                Simpan Penugasan
                            </Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </div>
        </AdminLayout>
    );
}
