export const getScoreColor = (score: number) => {
    if (score >= 91) return 'text-green-600';
    if (score >= 81) return 'text-blue-600';
    if (score >= 71) return 'text-yellow-600';
    if (score >= 61) return 'text-orange-600';
    return 'text-red-600';
};

export const getScoreLabel = (score: number) => {
    if (score >= 91) return 'Sangat Baik';
    if (score >= 81) return 'Baik';
    if (score >= 71) return 'Butuh Perbaikan';
    if (score >= 61) return 'Kurang';
    return 'Sangat Kurang';
};

export const getScoreBadgeColor = (score: number) => {
    if (score >= 91) return 'bg-green-100 text-green-800 border-green-200';
    if (score >= 81) return 'bg-blue-100 text-blue-800 border-blue-200';
    if (score >= 71) return 'bg-yellow-100 text-yellow-800 border-yellow-200';
    if (score >= 61) return 'bg-orange-100 text-orange-800 border-orange-200';
    return 'bg-red-100 text-red-800 border-red-200';
};
