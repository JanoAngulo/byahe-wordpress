import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import yaml from 'js-yaml';

const wf = yaml.load(readFileSync(new URL('../../.github/workflows/release.yml', import.meta.url), 'utf8'));
const jobs = Object.values(wf.jobs);
const steps = (job) => job.steps ?? [];
const runs = (job) => steps(job).map((s) => s.run ?? '').join('\n');
const writer = jobs.filter((j) => j.permissions?.contents === 'write');
const builder = jobs.find((j) => /npm ci/.test(runs(j)));

test('the workflow defaults to a read-only token', () => {
  assert.deepEqual(wf.permissions, { contents: 'read' });
});

test('only one job can write, and it never installs or runs project code', () => {
  assert.equal(writer.length, 1);
  assert.doesNotMatch(runs(writer[0]), /\b(npm|npx|composer|node)\b/);
  assert.ok(steps(writer[0]).every((s) => !/setup-node|setup-php|checkout/.test(s.uses ?? '')));
});

test('the build job keeps no token in .git/config and has no write access', () => {
  assert.ok(builder, 'a job runs npm ci');
  assert.notEqual(builder.permissions?.contents, 'write');
  const checkout = steps(builder).find((s) => /actions\/checkout/.test(s.uses ?? ''));
  assert.equal(checkout.with?.['persist-credentials'], false);
});

test('unit tests run before the zips are packaged', () => {
  const order = steps(builder).map((s) => s.run ?? '');
  const unit = order.findIndex((r) => /npm run test:unit/.test(r));
  const pkg = order.findIndex((r) => /npm run package:wp/.test(r));
  assert.ok(unit >= 0 && pkg > unit);
});

test('the publish job waits for the build and uses its artifact', () => {
  const needs = [writer[0].needs].flat();
  assert.ok(needs.length === 1 && wf.jobs[needs[0]] === builder);
  assert.ok(steps(writer[0]).some((s) => /actions\/download-artifact/.test(s.uses ?? '')));
});

test('a newer push queues behind a running release instead of cancelling its upload', () => {
  assert.equal(wf.concurrency['cancel-in-progress'], false);
});

test('every action is pinned to a full commit SHA', () => {
  const uses = jobs.flatMap(steps).map((s) => s.uses).filter(Boolean);
  assert.ok(uses.length >= 5);
  for (const u of uses) assert.match(u, /@[0-9a-f]{40}$/, u);
});
