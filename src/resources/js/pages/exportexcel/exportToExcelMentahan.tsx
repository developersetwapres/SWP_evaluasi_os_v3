'use client';

import { useEffect, useRef } from 'react';
import * as XLSX from 'xlsx';

type ExportRowData = Record<string, string | number>;

type PenugasanNilai = {
    indikator: string;
    pilar: string;
    nilai: number | null;
};

type PenugasanExport = {
    id: number;
    outsourcing: string;
    jabatan_os: string;
    evaluator: string;
    jabatan_evaluator: string;
    tipe_penilai: string;
    nilai: PenugasanNilai[];
};

type KelompokJabatanExport = {
    kelompokJabatan: string;
    penugasan: PenugasanExport[];
};

function sanitizeSheetName(name: string, usedNames: Set<string>): string {
    const sanitized =
        name
            .replace(/[\\/*?:[\]]/g, '')
            .trim()
            .slice(0, 31) || 'Sheet';

    let candidate = sanitized;
    let counter = 1;

    while (usedNames.has(candidate)) {
        const suffix = ` (${counter})`;
        candidate = `${sanitized.slice(0, 31 - suffix.length)}${suffix}`;
        counter += 1;
    }

    usedNames.add(candidate);

    return candidate;
}

const BASE_EXPORT_COLUMNS = [
    'Nama Outsourcing',
    'Jabatan Outsourcing',
    'Kelompok Jabatan Outsourcing',
    'Evaluator',
    'Jabatan Evaluator',
    'Tipe Penilai',
];

function buildColumns(penugasans: PenugasanExport[]): string[] {
    const indicatorHeaders: string[] = [];
    const seen = new Set<string>();

    for (const penugasan of penugasans) {
        for (const nilai of penugasan.nilai ?? []) {
            const header = `${nilai.pilar} - ${nilai.indikator}`;

            if (!seen.has(header)) {
                seen.add(header);
                indicatorHeaders.push(header);
            }
        }
    }

    return [...BASE_EXPORT_COLUMNS, ...indicatorHeaders];
}

function buildRows(
    penugasans: PenugasanExport[],
    columns: string[],
    kelompokJabatan: string,
): ExportRowData[] {
    return penugasans.map((penugasan) => {
        const row: ExportRowData = {
            'Nama Outsourcing': penugasan.outsourcing,
            'Jabatan Outsourcing': penugasan.jabatan_os,
            'Kelompok Jabatan Outsourcing': kelompokJabatan,
            Evaluator: penugasan.evaluator,
            'Jabatan Evaluator': penugasan.jabatan_evaluator,
            'Tipe Penilai': penugasan.tipe_penilai,
        };

        for (const nilai of penugasan.nilai ?? []) {
            const header = `${nilai.pilar} - ${nilai.indikator}`;
            row[header] = nilai.nilai ?? 0;
        }

        return Object.fromEntries(
            columns.map((column) => [column, row[column] ?? 0]),
        );
    });
}

export default function exportToExcelMentahan({
    evaluationResults,
}: {
    evaluationResults: KelompokJabatanExport[];
}) {
    const downloaded = useRef(false);

    useEffect(() => {
        if (downloaded.current) {
            return;
        }

        downloaded.current = true;

        const workbook = XLSX.utils.book_new();
        const usedSheetNames = new Set<string>();

        for (const kelompok of evaluationResults ?? []) {
            const sheetName = kelompok.kelompokJabatan ?? 'Tanpa Kelompok';
            const columns = buildColumns(kelompok.penugasan ?? []);
            const rows = buildRows(
                kelompok.penugasan ?? [],
                columns,
                sheetName,
            );
            const worksheet = XLSX.utils.json_to_sheet(rows, {
                header: columns,
            });

            XLSX.utils.book_append_sheet(
                workbook,
                worksheet,
                sanitizeSheetName(sheetName, usedSheetNames),
            );
        }

        if ((evaluationResults ?? []).length === 0) {
            const worksheet = XLSX.utils.json_to_sheet([]);

            XLSX.utils.book_append_sheet(workbook, worksheet, 'Data Kosong');
        }

        XLSX.writeFile(workbook, 'hasil-penilaian-by-row.xlsx');

        setTimeout(() => {
            window.history.back();
        }, 300);
    }, [evaluationResults]);

    return null;
}
