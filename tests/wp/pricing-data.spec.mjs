import { test, expect } from '@playwright/test';

test.beforeEach(({}, info) => test.skip(info.project.name !== 'desktop', 'server data is the same at every width'));

const field = async (request, query) => {
  const res = await request.get(`/?rest_route=/byahe-test/v1/field&${new URLSearchParams(query)}`);
  expect(res.status()).toBe(200);
  return (await res.json()).value;
};

test('prices format as pesos for each billing period', async ({ request }) => {
  expect(await field(request, { tier: 'negosyo', field: 'price', billing: 'monthly' })).toBe('₱499');
  expect(await field(request, { tier: 'negosyo', field: 'price', billing: 'yearly' })).toBe('₱4,990');
  expect(await field(request, { tier: 'pro', field: 'price', billing: 'yearly' })).toBe('₱14,990');
  expect(await field(request, { tier: 'libre', field: 'price' })).toBe('₱0');
});

test('enterprise shows its label instead of a price', async ({ request }) => {
  expect(await field(request, { tier: 'enterprise', field: 'price' })).toBe("Let's talk");
  expect(await field(request, { tier: 'enterprise', field: 'period' })).toBe('custom pricing');
});

test('names, periods and badges come from the data', async ({ request }) => {
  expect(await field(request, { tier: 'negosyo', field: 'name' })).toBe('Negosyo');
  expect(await field(request, { tier: 'negosyo', field: 'badge' })).toBe('Most popular');
  expect(await field(request, { tier: 'negosyo', field: 'period', billing: 'yearly' })).toBe('per year');
  expect(await field(request, { tier: 'libre', field: 'period' })).toBe('forever');
});

test('unknown tiers and fields return an empty string', async ({ request }) => {
  expect(await field(request, { tier: 'nope', field: 'price' })).toBe('');
  expect(await field(request, { tier: 'pro', field: 'nope' })).toBe('');
});

test('the pricing filter changes the price', async ({ request }) => {
  expect(await field(request, { tier: 'negosyo', field: 'price', byahe_test_price: '1' })).toBe('₱555');
});

test('a monthly price edit also moves the yearly price (ten months, two free)', async ({ request }) => {
  expect(await field(request, { tier: 'negosyo', field: 'price', billing: 'yearly', byahe_test_price: '1' })).toBe('₱5,550');
  expect(await field(request, { tier: 'enterprise', field: 'price', billing: 'yearly' })).toBe("Let's talk");
  expect(await field(request, { tier: 'libre', field: 'price', billing: 'yearly' })).toBe('₱0');
});
