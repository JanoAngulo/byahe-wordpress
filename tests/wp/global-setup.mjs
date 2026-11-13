import { request } from '@playwright/test';

/** Re-seed the demo pages so a reused dev server serves current patterns. */
export default async function globalSetup(config) {
  const baseURL = config.projects[0].use.baseURL;
  const ctx = await request.newContext({ baseURL });
  const res = await ctx.post('/?rest_route=/byahe-test/v1/seed');
  if (!res.ok()) throw new Error(`re-seed failed: ${res.status()} ${await res.text()}`);
  await ctx.dispose();
}
