export function formatAmount(amount) {
    return `${amount / 100n}.${String(amount % 100n).padStart(2, '0')}`;
}

export function roundRatio(numerator, denominator) {
    return denominator > 0n ? (numerator * 2n + denominator) / (denominator * 2n) : 0n;
}
