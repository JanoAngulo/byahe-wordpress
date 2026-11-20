#!/usr/bin/env node
/**
 * Writes blueprint.local.json (dev and tests, mounted folders) and
 * blueprint.json (public demo, installs the release zips) from
 * blueprints/steps.json.
 */
import { readFileSync, writeFileSync } from 'node:fs';
import { buildBlueprints } from './lib/blueprints.mjs';

const root = new URL('../', import.meta.url);
const steps = JSON.parse(readFileSync(new URL('blueprints/steps.json', root), 'utf8'));
const { local, public: pub } = buildBlueprints(steps, { repo: 'JanoAngulo/byahe-wordpress', tag: 'demo-latest' });

const write = (file, data) => writeFileSync(new URL(file, root), `${JSON.stringify(data, null, 2)}\n`);
write('blueprint.local.json', local);
write('blueprint.json', pub);
console.log('Wrote blueprint.local.json and blueprint.json');
