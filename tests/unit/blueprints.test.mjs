import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { buildBlueprints } from '../../scripts/lib/blueprints.mjs';

const root = new URL('../../', import.meta.url);
const steps = JSON.parse(readFileSync(new URL('blueprints/steps.json', root), 'utf8'));
const opts = { repo: 'JanoAngulo/byahe-wordpress', tag: 'demo-latest' };
const PROXY = 'https://wordpress-playground-cors-proxy.net/?';
const RELEASE = 'https://github.com/JanoAngulo/byahe-wordpress/releases/download/demo-latest/';
const SEED = (s) => s.step === 'wp-cli' && s.command === 'wp byahe seed';

test('public blueprint installs the theme and plugin from the release, through the CORS proxy', () => {
  const { public: pub } = buildBlueprints(steps, opts);
  assert.equal(pub.steps[0].step, 'installTheme');
  assert.equal(pub.steps[0].themeData.resource, 'url');
  assert.equal(pub.steps[0].themeData.url, `${PROXY}${RELEASE}byahe-theme.zip`);
  assert.deepEqual(pub.steps[0].options, { activate: true });
  assert.equal(pub.steps[1].step, 'installPlugin');
  assert.equal(pub.steps[1].pluginData.url, `${PROXY}${RELEASE}byahe-core.zip`);
  assert.deepEqual(pub.steps[1].options, { activate: true });
});

test('public blueprint logs in, lands on Home and pins PHP and WordPress versions', () => {
  const { public: pub } = buildBlueprints(steps, opts);
  assert.equal(pub.login, true);
  assert.equal(pub.landingPage, '/');
  assert.deepEqual(pub.preferredVersions, { php: '8.2', wp: 'latest' });
});

test('public blueprint drops the separate activate steps and local-only steps', () => {
  const { public: pub } = buildBlueprints(steps, opts);
  const names = pub.steps.map((s) => s.step);
  assert.ok(!names.includes('activateTheme'));
  assert.ok(!names.includes('activatePlugin'));
  assert.ok(!names.includes('defineWpConfigConsts'), 'development mode stays local');
  assert.ok(pub.steps.every((s) => !('only' in s)));
});

test('local blueprint has no install steps and no login, and activates the mounted folders', () => {
  const { local } = buildBlueprints(steps, opts);
  assert.ok(local.steps.every((s) => !s.step.startsWith('install')));
  assert.ok(!('login' in local));
  const names = local.steps.map((s) => s.step);
  assert.ok(names.includes('activateTheme'));
  assert.ok(names.includes('activatePlugin'));
  assert.ok(names.includes('defineWpConfigConsts'));
  assert.ok(local.steps.every((s) => !('only' in s)));
});

test('the seed step appears exactly once in each blueprint, after activation', () => {
  for (const [name, bp] of Object.entries(buildBlueprints(steps, opts))) {
    assert.equal(bp.steps.filter(SEED).length, 1, name);
    assert.equal(bp.steps.at(-1).command, 'wp byahe seed', name);
  }
});

test('both blueprints are plain JSON with the Playground schema', () => {
  for (const [name, bp] of Object.entries(buildBlueprints(steps, opts))) {
    assert.equal(bp.$schema, 'https://playground.wordpress.net/blueprint-schema.json', name);
    assert.deepEqual(JSON.parse(JSON.stringify(bp)), bp, name);
  }
});

test('does not change the step list it is given', () => {
  const copy = structuredClone(steps);
  buildBlueprints(steps, opts);
  assert.deepEqual(steps, copy);
});

test('committed blueprint files match the generator output', () => {
  const { local, public: pub } = buildBlueprints(steps, opts);
  assert.equal(readFileSync(new URL('blueprint.local.json', root), 'utf8'), `${JSON.stringify(local, null, 2)}\n`);
  assert.equal(readFileSync(new URL('blueprint.json', root), 'utf8'), `${JSON.stringify(pub, null, 2)}\n`);
});
