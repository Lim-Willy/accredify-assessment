import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;
document.addEventListener('DOMContentLoaded', (event) => {
  const token = document.head.querySelector('meta[name="csrf-token"]');
  if (token) {
      window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
  } else {
      console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
  }
});

// Optional: Refresh CSRF token after login/logout
window.refreshCSRFToken = () => {
  window.axios.get('/sanctum/csrf-cookie').then(response => {
      const token = document.head.querySelector('meta[name="csrf-token"]');
      if (token) {
          window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
      }
  });
};
