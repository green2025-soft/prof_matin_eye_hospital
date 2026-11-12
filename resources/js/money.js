// Parse decimal input exactly; each form decides how to handle an invalid value.
export function parseAmount(value, maxWholeDigits = 10) {
    const match = String(value ?? '').trim().match(/^(\d+)(?:\.(\d{1,2}))?$/);
    if (!match || match[1].length > maxWholeDigits) return null;

    return BigInt(match[1]) * 100n + BigInt((match[2] ?? '').padEnd(2, '0'));
}

// Format non-negative integer cents without floating-point conversion.
export function formatAmount(amount) {
    return `${amount / 100n}.${String(amount % 100n).padStart(2, '0')}`;
}

// Round a non-negative ratio to the nearest integer, including half-up ties.
export function roundRatio(numerator, denominator) {
    return denominator > 0n ? (numerator * 2n + denominator) / (denominator * 2n) : 0n;
}
