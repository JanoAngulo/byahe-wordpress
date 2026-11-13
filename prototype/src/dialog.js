export function initDemoDialog(root = document) {
  const dialog = root.getElementById('demo-dialog');
  if (!dialog) return;
  let opener = null;

  root.querySelectorAll('[data-open-demo-dialog]').forEach((trigger) => {
    trigger.addEventListener('click', (event) => {
      event.preventDefault();
      opener = trigger;
      dialog.showModal();
    });
  });

  dialog.querySelectorAll('[data-close-dialog]').forEach((button) => {
    button.addEventListener('click', () => dialog.close());
  });

  dialog.addEventListener('click', (event) => {
    if (event.target === dialog) dialog.close();
  });

  dialog.addEventListener('close', () => {
    opener?.focus();
    opener = null;
  });
}

initDemoDialog();
