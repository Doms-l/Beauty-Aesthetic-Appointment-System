import React from 'react';
import { createRoot } from 'react-dom/client';
import AppointmentPicker from './components/AppointmentPicker';

/**
 * React entry point.
 * Blade remains responsible for the page layout, while React handles
 * interactive components that benefit from client-side state.
 */

const appointmentRoot = document.getElementById('appointment-picker');

if (appointmentRoot) {

    const services = JSON.parse(
        appointmentRoot.dataset.services || '[]'
    );

    const selectedService =
        appointmentRoot.dataset.selectedService || '';

    createRoot(appointmentRoot).render(
        <AppointmentPicker
            services={services}
            selectedService={selectedService}
        />
    );
}