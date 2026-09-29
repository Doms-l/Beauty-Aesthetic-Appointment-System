import React from 'react';
import { createRoot } from 'react-dom/client';
import AppointmentPicker from './components/AppointmentPicker';

console.log('M. Cares React app loaded');

const appointmentRoot = document.getElementById('appointment-picker');

if (appointmentRoot) {

    console.log('Appointment picker found');

    const services = JSON.parse(
        appointmentRoot.dataset.services || '[]'
    );

    const selectedService =
        appointmentRoot.dataset.selectedService || '';

    console.log('Services:', services);

    createRoot(appointmentRoot).render(
        <AppointmentPicker
            services={services}
            selectedService={selectedService}
        />
    );
}