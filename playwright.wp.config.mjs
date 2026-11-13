import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
  testDir: 'tests/wp',
  fullyParallel: true,
  workers: 2,
  reporter: [['list']],
  timeout: 60_000,
  use: {
    baseURL: 'http://127.0.0.1:9410',
  },
  projects: [
    { name: 'desktop', use: { ...devices['Desktop Chrome'], viewport: { width: 1280, height: 800 } } },
    { name: 'mobile', use: { ...devices['Desktop Chrome'], viewport: { width: 360, height: 800 }, hasTouch: true } },
  ],
  webServer: {
    command: 'npm run build:wp && npm run serve:wp',
    url: 'http://127.0.0.1:9410/',
    timeout: 480_000,
    reuseExistingServer: !process.env.CI,
  },
});
