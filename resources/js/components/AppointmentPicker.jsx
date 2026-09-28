import React, { useMemo, useState } from 'react';

/**
 * Interactive appointment selector.
 * It updates the hidden form fields used by the Laravel controller.
 */
export default function AppointmentPicker({ services }) {
    const [serviceId, setServiceId] = useState('');
    const [date, setDate] = useState('');
    const [time, setTime] = useState('');

    const selectedService = useMemo(
        () => services.find((service) => String(service.id) === String(serviceId)),
        [services, serviceId]
    );

    const minDate = new Date().toISOString().split('T')[0];

    return (
        <div className="react-booking-box">
            <div className="react-heading">
                <span>01</span>
                <div>
                    <h3>Choose your service</h3>
                    <p>Select an available M. Cares service.</p>
                </div>
            </div>

            <div className="service-select-grid">
                {services.map((service) => (
                    <button
                        key={service.id}
                        type="button"
                        className={`service-choice ${String(service.id) === String(serviceId) ? 'selected' : ''}`}
                        onClick={() => setServiceId(service.id)}
                    >
                        <strong>{service.name}</strong>
                        <span>₱{Number(service.price).toLocaleString()}</span>
                        <small>{service.duration_minutes} minutes</small>
                    </button>
                ))}
            </div>

            <input type="hidden" name="service_id" value={serviceId} />

            <div className="booking-fields">
                <label>
                    Appointment date
                    <input
                        type="date"
                        name="appointment_date"
                        min={minDate}
                        value={date}
                        onChange={(event) => setDate(event.target.value)}
                        required
                    />
                </label>

                <label>
                    Preferred time
                    <input
                        type="time"
                        name="appointment_time"
                        value={time}
                        onChange={(event) => setTime(event.target.value)}
                        required
                    />
                </label>
            </div>

            {selectedService && (
                <div className="booking-summary">
                    <strong>{selectedService.name}</strong>
                    <span>₱{Number(selectedService.price).toLocaleString()} · {selectedService.duration_minutes} minutes</span>
                </div>
            )}
        </div>
    );
}
