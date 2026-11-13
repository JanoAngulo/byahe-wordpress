/**
 * Tiny Vite plugin: replaces `<!-- @include path/to/file.html -->` with that
 * partial's contents. Paths resolve against `root`. Nested includes work up to
 * MAX_DEPTH levels.
 */
import { readFileSync, existsSync, readdirSync, statSync } from 'node:fs';
import { resolve, join } from 'node:path';

const INCLUDE = /<!--\s*@include\s+([^\s]+)\s*-->/g;
const MAX_DEPTH = 5;

export function resolveIncludes(html, root, depth = 0) {
  return html.replace(INCLUDE, (_, path) => {
    if (depth >= MAX_DEPTH) {
      throw new Error(`Include depth ${MAX_DEPTH} exceeded at ${path}`);
    }
    const file = resolve(root, path);
    if (!existsSync(file)) {
      throw new Error(`Partial not found: ${path}`);
    }
    return resolveIncludes(readFileSync(file, 'utf8'), root, depth + 1);
  });
}

function listFiles(dir) {
  if (!existsSync(dir)) return [];
  return readdirSync(dir).flatMap((name) => {
    const full = join(dir, name);
    return statSync(full).isDirectory() ? listFiles(full) : [full];
  });
}

export function includePartials({ root }) {
  return {
    name: 'byahe-include-partials',
    configureServer(server) {
      server.watcher.add(root);
      server.watcher.on('change', (file) => {
        if (resolve(file).startsWith(resolve(root))) {
          server.ws.send({ type: 'full-reload' });
        }
      });
    },
    buildStart() {
      for (const file of listFiles(root)) this.addWatchFile(file);
    },
    transformIndexHtml: {
      order: 'pre',
      handler: (html) => resolveIncludes(html, root),
    },
  };
}
