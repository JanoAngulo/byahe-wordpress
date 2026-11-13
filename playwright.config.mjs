import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
  testDir: 'tests/e2e',
  fullyParallel: true,
  reporter: [['list']],
  use: {
    baseURL: 'http://localhost:4173',
  },
  projects: [
    { name: 'desktop', use: { ...devices['Desktop Chrome'], viewport: { width: 1280, height: 800 } } },
    { name: 'mobile', use: { ...devices['Desktop Chrome'], viewport: { width: 360, height: 800 }, hasTouch: true } },
  ],
  webServer: {
    command: 'npm run build:proto && npm run preview:proto',
    url: 'http://localhost:4173/components.html',
    reuseExistingServer: !process.env.CI,
    timeout: 120_000,
  },
});
