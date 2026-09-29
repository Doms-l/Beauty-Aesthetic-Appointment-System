import React, { useMemo, useState } from "react";

export default function AppointmentPicker({ services, selectedService }) {

    const [serviceId, setServiceId] = useState(
        selectedService ? String(selectedService) : ""
    );

    const [date, setDate] = useState("");
    const [time, setTime] = useState("");

    const selected = useMemo(() => {
        return services.find(
            service => String(service.id) === String(serviceId)
        );
    }, [services, serviceId]);

    // Get today's date in YYYY-MM-DD format
    const today = new Date().toISOString().split("T")[0];

    return (
        <div className="react-booking-box">

            {/* Heading */}
            <div className="react-heading">

                <span>01</span>

                <div>
                    <h3>Select a service</h3>

                    <p>
                        Choose the beauty or aesthetic service you want.
                    </p>
                </div>

            </div>

            {/* Services */}
            <div className="service-select-grid">

                {services.map(service => (

                    <button
                        type="button"
                        key={service.id}
                        className={
                            "service-choice " +
                            (
                                String(service.id) === String(serviceId)
                                    ? "selected"
                                    : ""
                            )
                        }
                        onClick={() => setServiceId(String(service.id))}
                    >

                        <strong>
                            {service.name}
                        </strong>

                        <span>
                            {service.price_display || `₱${Number(service.price).toLocaleString()}`}
                        </span>

                    </button>

                ))}

            </div>

            {/* Hidden service ID */}
            <input
                type="hidden"
                name="service_id"
                value={serviceId}
                required
            />

            {/* Date and time */}
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

                {/* Date */}
                <label>

                    Appointment Date

                    <input
                        type="date"
                        name="appointment_date"
                        value={date}
                        min={today}
                        onChange={(event) => {
                            setDate(event.target.value);
                        }}
                        required
                    />

                </label>

                {/* Time */}
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

            {/* Summary */}
            {selected && date && time && (

                <div className="booking-summary">

                    <div>
                        <strong>
                            {selected.name}
                        </strong>

                        <br />

                        <span>
                            {date} at {time}
                        </span>
                    </div>

                    <strong>
                        {selected.price_display ||
                            `₱${Number(selected.price).toLocaleString()}`}
                    </strong>

                </div>

            )}

        </div>
    );
}