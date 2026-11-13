import { expect } from '@playwright/test';
import { ADMIN_STATE } from './global-setup.mjs';

/** A browser context logged in as the Playground admin (session from global setup). */
export async function adminContext(browser, baseURL, options = {}) {
  return browser.newContext({ baseURL, storageState: ADMIN_STATE, ...options });
}

/** REST nonce for the logged-in user of this page's context. */
export async function restNonce(page) {
  const nonce = (await (await page.request.get('/wp-admin/admin-ajax.php?action=rest-nonce')).text()).trim();
  expect(nonce, 'REST nonce').toMatch(/^[a-f0-9]{10}$/);
  return nonce;
}

/** Creates a page through REST as the logged-in user; returns { id, link }. */
export async function createPage(page, nonce, { title, content, status = 'publish' }) {
  const res = await page.request.post('/?rest_route=/wp/v2/pages', {
    headers: { 'X-WP-Nonce': nonce },
    data: { title, content, status },
  });
  expect(res.status(), await res.text()).toBe(201);
  const json = await res.json();
  return { id: json.id, link: json.link };
}

export async function deletePage(page, nonce, id) {
  await page.request.delete(`/?rest_route=/wp/v2/pages/${id}&force=true`, { headers: { 'X-WP-Nonce': nonce } });
}

/**
 * Creates a published page from block markup, runs `fn` with the page open
 * on the front end (logged out), then deletes it.
 */
export async function withBlockPage(browser, baseURL, viewport, content, fn) {
  const admin = await adminContext(browser, baseURL);
  const adminPage = await admin.newPage();
  const nonce = await restNonce(adminPage);
  const { id, link } = await createPage(adminPage, nonce, { title: `Block test ${Date.now()}`, content });
  const visitor = await browser.newContext({ baseURL, viewport });
  const page = await visitor.newPage();
  try {
    await page.goto(link);
    await fn(page);
  } finally {
    await deletePage(adminPage, nonce, id);
    await visitor.close();
    await admin.close();
  }
}
