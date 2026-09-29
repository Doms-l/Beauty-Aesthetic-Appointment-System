import React, { useState } from 'react';

export default function AppointmentPicker({
    services = [],
    selectedService = ''
}) {

    const [serviceId, setServiceId] = useState(
        selectedService ? String(selectedService) : ''
    );

    const [date, setDate] = useState('');

    const [time, setTime] = useState('');

    const today = new Date()
        .toISOString()
        .split('T')[0];

    return (
        <div className="react-booking-box">

            {/* ================================
                STEP 1: SELECT SERVICE
            ================================= */}

            <div className="react-heading">

                <span>01</span>

                <div>
                    <h3>Select a service</h3>

                    <p>
                        Choose the beauty or aesthetic service you want.
                    </p>
                </div>

            </div>


            <div className="service-select-grid">

                {services.map((service) => (

                    <button
                        key={service.id}
                        type="button"
                        className={
                            String(service.id) === String(serviceId)
                                ? 'service-choice selected'
                                : 'service-choice'
                        }
                        onClick={() => {
                            setServiceId(String(service.id));
                        }}
                    >

                        <strong>
                            {service.name}
                        </strong>

                        <span>
                            {service.price_display
                                ? service.price_display
                                : `₱${Number(service.price).toLocaleString()}`
                            }
                        </span>

                    </button>

                ))}

            </div>


            {/* Laravel receives this value */}

            <input
                type="hidden"
                name="service_id"
                value={serviceId}
            />


            {/* ================================
                STEP 2: DATE AND TIME
            ================================= */}

            <div className="react-heading">

                <span>02</span>

                <div>

                    <h3>Choose date and time</h3>

                    <p>
                        Select your preferred appointment schedule.
                    </p>

                </div>

            </div>


            <div className="booking-fields">

                <label>

                    Appointment Date

                    <input
                        type="date"
                        name="appointment_date"
                        min={today}
                        value={date}
                        onChange={(event) => {
                            setDate(event.target.value);
                        }}
                        required
                    />

                </label>


                <label>

                    Preferred Time

                    <input
                        type="time"
                        name="appointment_time"
                        value={time}
                        onChange={(event) => {
                            setTime(event.target.value);
                        }}
                        required
                    />

                </label>

            </div>


            {/* ================================
                SUMMARY
            ================================= */}

            {serviceId && date && time && (

                <div className="booking-summary">

                    <strong>
                        Appointment Selected
                    </strong>

                    <br />

                    <span>
                        {date} at {time}
                    </span>

                </div>

            )}

        </div>
    );
}