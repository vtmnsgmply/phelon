(() => {
  const picker = document.querySelector('[data-booking-picker]');
  if (!picker) return;
  const form = picker.querySelector('form');
  const { date, start, timezone } = form.elements;
  const fieldset = form.querySelector('fieldset');
  const submit = form.querySelector('button[type="submit"]');
  const info = picker.querySelector('[data-schedule-timezone]');
  const status = picker.querySelector('[data-booking-status]');
  const endpoint = new URL(picker.dataset.endpoint, window.location.href);
  let token = '';
  let slots = [];
  let generation = 0;
  let busy = false;

  const detected = Intl.DateTimeFormat().resolvedOptions().timeZone;
  if (Array.from(timezone.options).some(option => option.value === detected)) timezone.value = detected;
  const message = (text, focus = false) => {
    status.textContent = text;
    status.hidden = !text;
    if (focus) status.focus();
  };
  const format = instant => new Intl.DateTimeFormat(undefined, {
    timeZone: timezone.value, weekday: 'short', month: 'short', day: 'numeric',
    hour: 'numeric', minute: '2-digit', timeZoneName: 'short',
  }).format(new Date(instant));

  function renderSlots() {
    const chosen = start.value;
    start.replaceChildren(new Option(slots.length ? 'Select a time' : 'No available times', ''));
    slots.forEach(instant => start.add(new Option(format(instant), instant)));
    if (slots.includes(chosen)) start.value = chosen;
    start.disabled = !slots.length || busy;
    submit.disabled = !start.value || busy;
  }

  async function loadSlots() {
    const request = ++generation;
    const url = new URL(endpoint);
    if (date.value) url.searchParams.set('date', date.value);
    slots = [];
    start.replaceChildren(new Option('Checking times…', ''));
    start.disabled = submit.disabled = true;
    message('');
    try {
      const response = await fetch(url, { cache: 'no-store', credentials: 'same-origin' });
      const result = await response.json();
      if (request !== generation) return;
      if (!response.ok) throw new Error(result.error);
      token = result.csrfToken;
      date.min = result.minDate;
      date.max = result.maxDate;
      date.disabled = false;
      if (!date.value) date.value = result.minDate;
      slots = result.slots;
      info.textContent = 'Dates follow the calendar time zone (' + result.timezone
        + '). Times are shown in your selected time zone.';
      renderSlots();
      if (!slots.length) message('No appointments are available on that date. Try another date.');
    } catch (error) {
      if (request === generation) {
        renderSlots();
        message(error.message === 'Failed to fetch' ? 'Could not check availability. Please try again.' : error.message);
      }
    }
  }

  date.addEventListener('change', loadSlots);
  timezone.addEventListener('change', renderSlots);
  start.addEventListener('change', () => { submit.disabled = !start.value || busy; });
  form.addEventListener('submit', async event => {
    event.preventDefault();
    if (busy || !start.value) return;
    const request = Object.fromEntries(new FormData(form));
    busy = true;
    fieldset.disabled = true;
    message('Reserving your time…');
    let conflict = false;
    try {
      const response = await fetch(endpoint, {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': token },
        body: JSON.stringify(request),
      });
      const result = await response.json();
      if (!response.ok) {
        conflict = response.status === 409;
        throw new Error(result.error);
      }
      form.hidden = true;
      message('Appointment confirmed for ' + format(result.startsAt) + '. Your reference is ' + result.reference + '.', true);
      form.reset();
    } catch (error) {
      if (conflict) await loadSlots();
      message(error.message === 'Failed to fetch' ? 'Could not confirm the appointment. Please try again.' : error.message, true);
    } finally {
      busy = false;
      fieldset.disabled = false;
      if (!form.hidden) renderSlots();
    }
  });
  loadSlots();
})();
