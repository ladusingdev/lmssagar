document.addEventListener('DOMContentLoaded', function () {
  // Sidebar toggle (mobile)
  var toggleBtn = document.getElementById('sidebarToggle');
  var sidebar = document.getElementById('appSidebar');
  var overlay = document.getElementById('sidebarOverlay');

  function closeSidebar() {
    sidebar?.classList.remove('show');
    overlay?.classList.add('d-none');
  }

  toggleBtn?.addEventListener('click', function () {
    sidebar.classList.toggle('show');
    overlay.classList.toggle('d-none');
  });

  overlay?.addEventListener('click', closeSidebar);

  // Auto-dismiss alerts
  document.querySelectorAll('.alert[data-auto-dismiss]').forEach(function (alert) {
    setTimeout(function () {
      var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
      bsAlert.close();
    }, 5000);
  });

  // Delete confirmation for forms with data-confirm-delete
  document.querySelectorAll('form[data-confirm-delete]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      var message = form.getAttribute('data-confirm-delete') || 'Apakah Anda yakin ingin menghapus data ini?';
      if (!window.confirm(message)) {
        e.preventDefault();
      }
    });
  });

  // Bootstrap tooltips
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
    new bootstrap.Tooltip(el);
  });

  // File input preview name
  document.querySelectorAll('input[type="file"][data-preview-target]').forEach(function (input) {
    input.addEventListener('change', function () {
      var target = document.querySelector(input.getAttribute('data-preview-target'));
      if (target && input.files.length) {
        target.textContent = input.files[0].name;
      }
    });
  });
});

/**
 * Countdown timer used by quiz/exam attempt pages.
 * Auto-submits the given form when time runs out.
 */
function startCountdown(endTimestampMs, displayElId, formId) {
  var displayEl = document.getElementById(displayElId);
  var form = document.getElementById(formId);
  if (!displayEl) return;

  var timer = setInterval(function () {
    var remaining = endTimestampMs - Date.now();
    if (remaining <= 0) {
      clearInterval(timer);
      displayEl.textContent = '00:00:00';
      if (form) form.submit();
      return;
    }
    var totalSeconds = Math.floor(remaining / 1000);
    var hours = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
    var minutes = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
    var seconds = String(totalSeconds % 60).padStart(2, '0');
    displayEl.textContent = hours + ':' + minutes + ':' + seconds;

    if (remaining <= 60000) {
      displayEl.classList.add('text-danger', 'fw-bold');
    }
  }, 1000);
}
