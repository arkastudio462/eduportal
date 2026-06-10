import './bootstrap';

import Alpine from 'alpinejs';

import Swal from 'sweetalert2';
window.Swal = Swal;

import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);
window.Chart = Chart;

import './utils/admin-nav';
import './utils/password-toggle';
import { isWebAuthnSupported, registerBiometric, authenticateBiometric } from './utils/webauthn';
window.WebAuthn = { isWebAuthnSupported, registerBiometric, authenticateBiometric };

window.Alpine = Alpine;

Alpine.start();
