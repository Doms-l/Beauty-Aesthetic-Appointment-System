import React from 'react';
import { createRoot } from 'react-dom/client';

import AppointmentPicker from './components/AppointmentPicker';

/**
 * React entry point.
 *
 * Blade provides the page structure and service data.
 * React handles the interactive appointment selection.
 */

const appointmentRoot =
    document.getElementById('appointment-picker');

if (appointmentRoot) {

    // Get services from the Blade data attribute
    const services = JSON.parse(
        appointmentRoot.dataset.services || '[]'
    );

    // Get the service selected from the Services page
    const selectedService =
        appointmentRoot.dataset.selectedService || '';

    // Render React inside the appointment picker
    createRoot(appointmentRoot).render(
        <AppointmentPicker
            services={services}
            selectedService={selectedService}
        />
    );
}