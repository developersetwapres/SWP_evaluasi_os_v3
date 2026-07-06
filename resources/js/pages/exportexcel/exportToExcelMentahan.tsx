import * as XLSX from 'xlsx';

type ExportRowData = Record<string, string | number>;

type ExportData = {
    kelompokJabatanId?: number | null;
    kelompokJabatan?: string;
    columns?: string[];
    rows?: ExportRowData[];
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
];

function buildOrderedRows(
    rows: ExportRowData[],
    columns: string[],
): ExportRowData[] {
    return rows.map((row) =>
        Object.fromEntries(
            columns.map((column) => [
                column,
                row[column] ?? (BASE_EXPORT_COLUMNS.includes(column) ? '' : 0),
            ]),
        ),
    );
}

export default function exportToExcelMentahan({ evaluationResults }: any) {
    const sheetMap = new Map<
        string,
        { sheetName: string; columns: string[]; rows: ExportRowData[] }
    >();

    console.log(evaluationResults);
    return;

    for (const item of evaluationResults ?? []) {
        const exportData = item?.exportData as ExportData | undefined;

        if (!exportData?.rows?.length || !exportData.columns?.length) {
            continue;
        }

        const sheetKey = String(
            exportData.kelompokJabatanId ??
                exportData.kelompokJabatan ??
                'lainnya',
        );

        if (!sheetMap.has(sheetKey)) {
            sheetMap.set(sheetKey, {
                sheetName: exportData.kelompokJabatan ?? 'Tanpa Kelompok',
                columns: exportData.columns,
                rows: [],
            });
        }

        sheetMap.get(sheetKey)?.rows.push(...exportData.rows);
    }

    const workbook = XLSX.utils.book_new();
    const usedSheetNames = new Set<string>();

    for (const sheet of sheetMap.values()) {
        const orderedRows = buildOrderedRows(sheet.rows, sheet.columns);
        const worksheet = XLSX.utils.json_to_sheet(orderedRows, {
            header: sheet.columns,
        });

        XLSX.utils.book_append_sheet(
            workbook,
            worksheet,
            sanitizeSheetName(sheet.sheetName, usedSheetNames),
        );
    }

    if (sheetMap.size === 0) {
        const worksheet = XLSX.utils.json_to_sheet([]);
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Data Kosong');
    }

    return XLSX.writeFile(workbook, 'hasil-penilaian-by-row.xlsx');
}
