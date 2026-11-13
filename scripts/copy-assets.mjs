#!/usr/bin/env node
/**
 * Copies build-time assets the WordPress theme and plugin need but the repo
 * does not commit: self-hosted fonts, stock photos and Lucide icons.
 */
import { copyFileSync, mkdirSync, readFileSync, readdirSync } from 'node:fs';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

/** Lucide icons the plugin's `byahe/icon` block may render (plugins/byahe-core/inc/icons.json). */
export const ICONS = JSON.parse(
  readFileSync(new URL('../plugins/byahe-core/inc/icons.json', import.meta.url), 'utf8'),
);

const FONTS = [
  ['prototype/src/fonts/cubao-free-variable.woff2', 'cubao-free-variable.woff2'],
  ['node_modules/@fontsource-variable/inter/files/inter-latin-wght-normal.woff2', 'inter-latin-wght-normal.woff2'],
  ['node_modules/@fontsource-variable/space-grotesk/files/space-grotesk-latin-wght-normal.woff2', 'space-grotesk-latin-wght-normal.woff2'],
];

function copy(root, from, to) {
  const target = join(root, to);
  mkdirSync(dirname(target), { recursive: true });
  copyFileSync(join(root, from), target);
}

export async function copyAssets({ root }) {
  const fonts = FONTS.map(([from, name]) => {
    copy(root, from, `theme/byahe/assets/fonts/${name}`);
    return name;
  });

  const images = readdirSync(join(root, 'prototype/public/images')).filter((f) => f.endsWith('.webp'));
  for (const img of images) copy(root, `prototype/public/images/${img}`, `theme/byahe/assets/images/${img}`);
  copy(root, 'scripts/photos.json', 'theme/byahe/assets/images/photos.json');

  const icons = ICONS.map((name) => {
    copy(root, `node_modules/lucide-static/icons/${name}.svg`, `plugins/byahe-core/assets/icons/${name}.svg`);
    return name;
  });

  return { fonts, images, icons };
}

if (process.argv[1] && resolve(process.argv[1]) === fileURLToPath(import.meta.url)) {
  const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
  const out = await copyAssets({ root });
  console.log(`copied ${out.fonts.length} fonts, ${out.images.length} images, ${out.icons.length} icons`);
}
