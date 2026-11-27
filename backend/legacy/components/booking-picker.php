<div class="native-booking" data-booking-picker data-endpoint="api/bookings.php">
  <form method="post" action="api/bookings.php" id="native-booking-form">
    <fieldset>
      <legend>Choose a 15-minute appointment</legend>
      <div class="native-booking__grid">
        <label>Appointment date
          <input name="date" type="date" required disabled>
        </label>
        <label>Available time
          <select name="start" required disabled><option value="">Choose a date first</option></select>
        </label>
      </div>
      <p data-schedule-timezone class="native-booking__hint"></p>

      <label>Service interest
        <select name="interest">
          <?php foreach (phelon_discovery_options() as $option => $label): ?>
            <option value="<?= phelon_service_escape($option) ?>"<?= $option === $discoverySelection['option'] ? ' selected' : '' ?>><?= phelon_service_escape($label) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <div class="native-booking__grid">
        <label>Full name <input name="name" autocomplete="name" maxlength="100" required></label>
        <label>Email <input name="email" type="email" autocomplete="email" maxlength="254" required></label>
        <label>Phone <input name="phone" type="tel" autocomplete="tel" maxlength="32" required></label>
        <label>State or territory
          <input name="state" autocomplete="address-level1" pattern="[A-Za-z]{2}" maxlength="2" placeholder="e.g. NY" required>
        </label>
      </div>
      <label>Your time zone
        <select name="timezone" required>
          <option value="UTC">UTC</option>
          <?php foreach (DateTimeZone::listIdentifiers() as $zone): ?>
            <?php if ($zone !== 'UTC'): ?><option value="<?= phelon_service_escape($zone) ?>"><?= phelon_service_escape($zone) ?></option><?php endif; ?>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Main area of concern
        <textarea name="concern" rows="3" maxlength="300" aria-describedby="booking-privacy-note" required></textarea>
      </label>
      <p id="booking-privacy-note" class="native-booking__hint">Keep this general. Please don’t include case details, documents, or sensitive identifiers.</p>
      <button type="submit" disabled>Confirm appointment</button>
    </fieldset>
  </form>
  <p data-booking-status class="native-booking__status" role="status" tabindex="-1" hidden></p>
  <p class="native-booking__hint">Need help scheduling? <a href="Contact.php">Contact our team</a>.</p>
  <noscript><p>JavaScript is required to check available times.</p></noscript>
</div>
