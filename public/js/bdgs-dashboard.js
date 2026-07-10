document.addEventListener('DOMContentLoaded', function () {
  var bell = document.querySelector('[data-notifications]');
  if (!bell) return;

  fetch('/dashboard/notifications', { headers: { 'Accept': 'application/json' } })
    .then(function (r) { return r.json(); })
    .then(function (data) {
      if (data.unread > 0) {
        bell.setAttribute('data-unread', String(data.unread));
      }
    })
    .catch(function () {});
});
