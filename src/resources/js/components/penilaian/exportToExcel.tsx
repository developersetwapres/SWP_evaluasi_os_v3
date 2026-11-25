import * as XLSX from 'xlsx';
import type { RekapResult } from '@/types';

export default function exportToExcel(evaluationResults: RekapResult[]) {
    const formattedData = (evaluationResults ?? []).map((item) => {
        const atasan = item.evaluatorScores?.find(
            (e) => e.type === 'atasan',
        );
        const penerima = item.evaluatorScores?.find(
            (e) => e.type === 'penerima_layanan1',
        );
        const teman = item.evaluatorScores?.find(
            (e) => e.type === 'penerima_layanan2',
        );

        return {
            'Nama Outsourcing': item.name,
            'Unit Kerja Outsourcing': item.biro,
            'Jabatan Outsourcing': item.jabatan,
            'Evaluator Atasan (40%)': atasan?.evaluatorName ?? '',
            'Nilai Atasan': atasan?.averageScore ?? 0,
            'Evaluator Penerima Layanan 1(30%)': penerima?.evaluatorName ?? '',
            'Nilai Penerima': penerima?.averageScore ?? 0,
            'Evaluator Penerima Layanan 2(30%)': teman?.evaluatorName ?? '',
            'Nilai Teman': teman?.averageScore ?? 0,
            'Nilai Akhir': item.finalTotalScore,
        };
    });

    const worksheet = XLSX.utils.json_to_sheet(formattedData);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Rekap Hasil');

    return XLSX.writeFile(workbook, 'hasil-penilaian.xlsx');
}
