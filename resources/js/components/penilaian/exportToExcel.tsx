import * as XLSX from 'xlsx';

export default function exportToExcel(evaluationResults: any[]) {
    const formattedData = evaluationResults.map((item: any) => {
        const atasan = item.evaluatorScores.find(
            (e: any) => e.type === 'atasan',
        );
        const penerima = item.evaluatorScores.find(
            (e: any) => e.type === 'penerima_layanan',
        );
        const teman = item.evaluatorScores.find(
            (e: any) => e.type === 'teman_setingkat',
        );

        return {
            'NRP Outsourcing': item.nip,
            'Nama Outsourcing': item.name,
            'Unit Kerja Outsourcing': item.biro,
            'Jabatan Outsourcing': item.jabatan,
            'Evaluator Atasan (50%)': atasan?.evaluatorName ?? '',
            'Nilai Atasan': atasan?.averageScore ?? 0,
            'Evaluator Penerima Layanan (30%)': penerima?.evaluatorName ?? '',
            'Nilai Penerima': penerima?.averageScore ?? 0,
            'Evaluator Teman (20%)': teman?.evaluatorName ?? '',
            'Nilai Teman': teman?.averageScore ?? 0,
            'Nilai Akhir': item.finalTotalScore,
        };
    });
    // 2. Buat worksheet dari data
    const worksheet = XLSX.utils.json_to_sheet(formattedData);

    // 3. Buat workbook
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

    // 4. Simpan sebagai file xlsx
    return XLSX.writeFile(workbook, 'hasil-penilaian.xlsx');
}
