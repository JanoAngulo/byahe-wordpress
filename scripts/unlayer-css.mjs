#!/usr/bin/env node
/**
 * Removes CSS cascade layers from the WordPress stylesheet.
 *
 * Tailwind and the shared components.css emit `@layer` blocks. WordPress core
 * and global styles are unlayered, and unlayered rules always beat layered
 * ones, so in WordPress every layered rule would lose regardless of
 * specificity. Unwrapping keeps the source order (theme, base, components,
 * utilities) and lets specificity decide, as in an unlayered stylesheet.
 */
import { readFileSync, writeFileSync } from 'node:fs';
import { resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import postcss from 'postcss';

export async function unlayer(css) {
  const root = postcss.parse(css);
  root.walkAtRules('layer', (rule) => {
    if (rule.nodes) rule.replaceWith(rule.nodes);
    else rule.remove();
  });
  return root.toString();
}

if (process.argv[1] && resolve(process.argv[1]) === fileURLToPath(import.meta.url)) {
  const file = process.argv[2];
  writeFileSync(file, await unlayer(readFileSync(file, 'utf8')));
}
