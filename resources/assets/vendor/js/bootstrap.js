import * as bootstrap from 'bootstrap';
import Toastify from 'toastify-js';

try {
  window.bootstrap = bootstrap;
  window.Toastify = Toastify;
} catch (e) {}

export { bootstrap, Toastify };
