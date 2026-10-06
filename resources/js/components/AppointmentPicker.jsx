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

    const selectedServiceData = services.find(
        (service) => String(service.id) === String(serviceId)
    );

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


            {/* SERVICE DROPDOWN */}

            <div className="service-dropdown-wrapper">

                <label htmlFor="service_id">
                    Service
                </label>

                <select
                    id="service_id"
                    name="service_id"
                    value={serviceId}
                    onChange={(event) => {
                        setServiceId(event.target.value);
                    }}
                    required
                >

                    <option value="">
                        -- Select a service --
                    </option>

                    {services.map((service) => (

                        <option
                            key={service.id}
                            value={service.id}
                        >
                            {service.name}
                            {service.price_display
                                ? ` — ${service.price_display}`
                                : ` — ₱${Number(service.price).toLocaleString()}`
                            }
                        </option>

                    ))}

                </select>

            </div>


            {/* SELECTED SERVICE INFORMATION */}

            {selectedServiceData && (

                <div className="selected-service-info">

                    <strong>
                        Selected Service
                    </strong>

                    <span>
                        {selectedServiceData.name}
                    </span>

                    <span>
                        {selectedServiceData.price_display
                            ? selectedServiceData.price_display
                            : `₱${Number(selectedServiceData.price).toLocaleString()}`
                        }
                    </span>

                </div>

            )}


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
                        {selectedServiceData?.name}
                    </span>

                    <br />

                    <span>
                        {date} at {time}
                    </span>

                </div>

            )}

        </div>
    );
}