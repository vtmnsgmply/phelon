(() => {
  const portal = document.querySelector('[data-portal]');
  if (!portal) return;
  const form = portal.querySelector('form');
  const submit = form.querySelector('button');
  const signedOut = portal.querySelector('[data-signed-out]');
  const signedIn = portal.querySelector('[data-signed-in]');
  const signOut = portal.querySelector('[data-sign-out]');
  const status = portal.querySelector('[data-login-status]');
  let token = '';

  function show(message) {
    status.hidden = !message;
    status.textContent = message;
  }

  async function session(method = 'GET', data = undefined) {
    const headers = { Accept: 'application/json' };
    if (method !== 'GET') headers['X-CSRF-Token'] = token;
    if (data !== undefined) headers['Content-Type'] = 'application/json';
    const response = await fetch(portal.dataset.endpoint, {
      method, headers, credentials: 'same-origin', cache: 'no-store',
      body: data === undefined ? undefined : JSON.stringify(data),
    });
    const result = await response.json();
    if (!response.ok) throw new Error(result.error);
    token = result.csrfToken;
    portal.querySelector('[data-user-name]').textContent = result.user ? result.user.displayName : '';
    signedOut.hidden = Boolean(result.user);
    signedIn.hidden = !result.user;
  }

  form.addEventListener('submit', async event => {
    event.preventDefault();
    if (submit.disabled) return;
    submit.disabled = true;
    show('');
    const data = Object.fromEntries(new FormData(form));
    form.elements.password.value = '';
    try {
      await session('POST', data);
      signedIn.querySelector('h2').focus();
    } catch (error) {
      show(error.message);
      form.elements.password.focus();
    } finally {
      submit.disabled = false;
    }
  });

  signOut.addEventListener('click', async () => {
    signOut.disabled = true;
    show('');
    try {
      await session('DELETE');
      form.elements.email.focus();
    } catch (error) {
      show(error.message);
    } finally {
      signOut.disabled = false;
    }
  });
  session().then(() => { submit.disabled = false; }).catch(() => show('Unable to reach the sign-in service. Reload to try again.'));
})();
