const MESSAGES = {
  yearly: 'Showing yearly prices. Two months libre on every paid plan.',
  monthly: 'Showing monthly prices.',
};

export function initPricingToggle(root = document) {
  const toggle = root.querySelector('[data-billing-toggle]');
  if (!toggle) return;
  const status = root.getElementById('billing-status');

  const apply = (yearly) => {
    const period = yearly ? 'yearly' : 'monthly';
    toggle.setAttribute('aria-checked', String(yearly));
    root.querySelectorAll('.price[data-monthly]').forEach((el) => {
      el.textContent = el.dataset[period];
    });
    root.querySelectorAll('.price-period[data-monthly]').forEach((el) => {
      el.textContent = el.dataset[period];
    });
    if (status) status.textContent = MESSAGES[period];
  };

  toggle.addEventListener('click', () => apply(toggle.getAttribute('aria-checked') !== 'true'));
}

initPricingToggle();
