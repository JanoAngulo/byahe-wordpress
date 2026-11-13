import { request } from '@playwright/test';
import { mkdirSync } from 'node:fs';
import { dirname } from 'node:path';

export const ADMIN_STATE = 'test-results/.auth/admin.json';

/**
 * Runs once before the WP suite:
 * - re-seeds the demo pages so a reused dev server serves current patterns;
 * - logs in once and saves the cookies. Logging in from parallel workers
 *   races on the user's session_tokens meta and drops sessions.
 */
export default async function globalSetup(config) {
  const baseURL = config.projects[0].use.baseURL;
  const ctx = await request.newContext({ baseURL });

  const seed = await ctx.post('/?rest_route=/byahe-test/v1/seed');
  if (!seed.ok()) throw new Error(`re-seed failed: ${seed.status()} ${await seed.text()}`);

  await ctx.get('/wp-login.php');
  const login = await ctx.post('/wp-login.php', {
    form: { log: 'admin', pwd: 'password', 'wp-submit': 'Log In', testcookie: '1', redirect_to: '/wp-admin/' },
    maxRedirects: 0,
  });
  if (![302, 303].includes(login.status())) throw new Error(`login failed: ${login.status()}`);

  mkdirSync(dirname(ADMIN_STATE), { recursive: true });
  await ctx.storageState({ path: ADMIN_STATE });
  await ctx.dispose();
}
