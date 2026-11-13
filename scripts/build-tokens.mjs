#!/usr/bin/env node
/**
 * Reads tokens/tokens.json, checks contrast rules, then writes:
 *   prototype/src/tokens.css   (Tailwind v4 @theme)
 *   theme/byahe/theme.json     (theme.base.json + generated settings)
 */
import { readFileSync, writeFileSync, mkdirSync } from 'node:fs';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { checkRules } from './lib/contrast.mjs';
import { toTailwindTheme, toThemeJsonSettings } from './lib/tokens.mjs';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const read = (p) => JSON.parse(readFileSync(resolve(root, p), 'utf8'));

const isObject = (v) => v && typeof v === 'object' && !Array.isArray(v);
function deepMerge(base, extra) {
  const out = { ...base };
  for (const [k, v] of Object.entries(extra)) {
    out[k] = isObject(v) && isObject(base[k]) ? deepMerge(base[k], v) : v;
  }
  return out;
}

const tokens = read('tokens/tokens.json');

const contrast = checkRules(tokens);
if (!contrast.pass) {
  for (const r of contrast.results.filter((x) => !x.pass)) {
    console.error(`Contrast fail: ${r.fg} on ${r.bg} = ${r.ratio}:1 (needs ${r.min}:1)`);
  }
  process.exit(1);
}

const write = (p, content) => {
  const abs = resolve(root, p);
  mkdirSync(dirname(abs), { recursive: true });
  writeFileSync(abs, content);
  console.log(`wrote ${p}`);
};

write('prototype/src/tokens.css', toTailwindTheme(tokens));

const base = read('theme/byahe/theme.base.json');
const themeJson = deepMerge(base, { settings: toThemeJsonSettings(tokens) });
write('theme/byahe/theme.json', `${JSON.stringify(themeJson, null, '\t')}\n`);
