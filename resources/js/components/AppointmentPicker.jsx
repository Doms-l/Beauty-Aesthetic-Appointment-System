import React, { useState } from 'react';

export default function AppointmentPicker({
    services = [],
    selectedService = ''
}) {

    // Selected service
    const [serviceId, setServiceId] = useState(
        selectedService
            ? String(selectedService)
            : ''
    );

    // Selected appointment date
    const [date, setDate] = useState('');

    // Selected appointment time
    const [time, setTime] = useState('');

    // Get today's date
    const today = new Date()
        .toISOString()
        .split('T')[0];

    return (
        <div className="react-booking-box">

            {/* =====================================================
                STEP 1 - SERVICE
            ====================================================== */}

            <div className="react-heading">

                <span>01</span>

                <div>
                    <h3>Select a service</h3>

                    <p>
                        Choose the beauty or aesthetic service
                        you want.
                    </p>
                </div>

            </div>


            {/* Service choices */}

            <div className="service-select-grid">

                {services.map((service) => (

                    <button
                        type="button"
                        key={service.id}
                        className={
                            String(service.id) === String(serviceId)
                                ? 'service-choice selected'
                                : 'service-choice'
                        }
                        onClick={() =>
                            setServiceId(String(service.id))
                        }
                    >

                        <strong>
                            {service.name}
                        </strong>

                        <span>
                            {service.price_display ||
                                `₱${Number(service.price).toLocaleString()}`}
                        </span>

                    </button>

                ))}

            </div>


            {/* Hidden service ID sent to Laravel */}

            <input
                type="hidden"
                name="service_id"
                value={serviceId}
            />


            {/* =====================================================
                STEP 2 - DATE AND TIME
            ====================================================== */}

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

                {/* Appointment Date */}

                <label>

                    Appointment Date

                    <input
                        type="date"
                        name="appointment_date"
                        value={date}
                        min={today}
                        onChange={(e) =>
                            setDate(e.target.value)
                        }
                    />

                </label>


                {/* Appointment Time */}

                <label>

                    Preferred Time

                    <input
                        type="time"
                        name="appointment_time"
                        value={time}
                        onChange={(e) =>
                            setTime(e.target.value)
                        }
                    />

                </label>

            </div>


            {/* =====================================================
                APPOINTMENT SUMMARY
            ====================================================== */}

            {serviceId && date && time && (

                <div className="booking-summary">

                    <div>

                        <strong>
                            Appointment Selected
                        </strong>

                        <br />

                        <span>
                            {date} at {time}
                        </span>

                    </div>

                </div>

            )}

        </div>
    );
}