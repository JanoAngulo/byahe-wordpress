/**
 * WCAG 2.x contrast helpers.
 */

function channel(value) {
  const c = value / 255;
  return c <= 0.03928 ? c / 12.92 : ((c + 0.055) / 1.055) ** 2.4;
}

function luminance(hex) {
  const clean = hex.replace('#', '');
  const full = clean.length === 3 ? [...clean].map((c) => c + c).join('') : clean;
  const [r, g, b] = [0, 2, 4].map((i) => parseInt(full.slice(i, i + 2), 16));
  return 0.2126 * channel(r) + 0.7152 * channel(g) + 0.0722 * channel(b);
}

export function contrastRatio(fgHex, bgHex) {
  const [hi, lo] = [luminance(fgHex), luminance(bgHex)].sort((a, b) => b - a);
  return (hi + 0.05) / (lo + 0.05);
}

export function checkRules(tokens) {
  const results = tokens.contrastRules.map(({ fg, bg, min }) => {
    const ratio = contrastRatio(tokens.color[fg], tokens.color[bg]);
    return { fg, bg, ratio: Math.round(ratio * 100) / 100, min, pass: ratio >= min };
  });
  return { pass: results.every((r) => r.pass), results };
}
