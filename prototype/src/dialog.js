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

  // Clicks on the dialog's own padding also target the dialog, so only close
  // when the pointer is outside its box (the backdrop).
  dialog.addEventListener('click', (event) => {
    if (event.target !== dialog) return;
    const box = dialog.getBoundingClientRect();
    const inside = event.clientX >= box.left && event.clientX <= box.right
      && event.clientY >= box.top && event.clientY <= box.bottom;
    if (!inside) dialog.close();
  });

  dialog.addEventListener('close', () => {
    opener?.focus();
    opener = null;
  });
}

initDemoDialog();
