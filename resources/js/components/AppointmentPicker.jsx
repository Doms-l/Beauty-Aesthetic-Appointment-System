import React, { useState } from 'react';

export default function AppointmentPicker({
    services = [],
    selectedService = '',
    maxServices = 1,
    levelName = 'Member'
}) {

    // Order the "types of service" are listed in
    const CATEGORY_ORDER = [
        'Promo',
        'Facial Services',
        'Lash and Brows Services',
        'Other Services',
    ];

    const categoryOf = (service) => service?.category || 'Other Services';

    const rank = (name) => {
        const index = CATEGORY_ORDER.indexOf(name);
        return index === -1 ? CATEGORY_ORDER.length : index;
    };

    const categories = [];

    services.forEach((service) => {
        const name = categoryOf(service);

        if (!categories.includes(name)) {
            categories.push(name);
        }
    });

    // "Promo" is always offered, even when no promo service exists yet
    if (!categories.includes('Promo')) {
        categories.push('Promo');
    }

    categories.sort((a, b) => rank(a) - rank(b));

    // The first service keeps the old field name (service_id). Any added
    // service is sent as extra_service_ids[] and shares the same visit.
    const findService = (id) =>
        services.find((service) => String(service.id) === String(id));

    const [rows, setRows] = useState(() => {
        const preselected = findService(selectedService);

        return [{
            category: preselected ? categoryOf(preselected) : '',
            serviceId: preselected ? String(preselected.id) : '',
        }];
    });

    const updateRow = (index, changes) => {
        setRows((current) => current.map(
            (row, i) => (i === index ? { ...row, ...changes } : row)
        ));
    };

    const isPackage = (service) => Number(service?.sessions) > 1;
    const isPromo = (service) => categoryOf(service) === 'Promo';

    const firstService = findService(rows[0].serviceId);

    // a package or a promo must be booked on its own
    const mustBeAlone = !!firstService && (isPackage(firstService) || isPromo(firstService));

    const canAdd = maxServices > 1
        && rows.length < maxServices
        && !!firstService
        && !mustBeAlone
        && rows.every((row) => row.serviceId);

    const addRow = () => setRows((current) => [...current, { category: '', serviceId: '' }]);

    const removeRow = (index) =>
        setRows((current) => current.filter((_, i) => i !== index));

    // services that may still be picked in an added row
    const optionsFor = (rowIndex, category) => {
        const taken = rows
            .filter((_, i) => i !== rowIndex)
            .map((row) => String(row.serviceId));

        return services.filter((service) =>
            categoryOf(service) === category
            && !taken.includes(String(service.id))
            && (rowIndex === 0 || (!isPackage(service) && !isPromo(service)))
        );
    };

    // added rows cannot use the Promo type
    const categoriesFor = (rowIndex) =>
        rowIndex === 0 ? categories : categories.filter((name) => name !== 'Promo');

    const chosen = rows.map((row) => findService(row.serviceId)).filter(Boolean);

    const priceOf = (service) => Number(service?.price || 0);

    const totalPrice = chosen.reduce((sum, service) => sum + priceOf(service), 0);

    const totalMinutes = chosen.reduce(
        (sum, service) => sum + Number(service?.duration_minutes || 0), 0
    );

    const formatMinutes = (minutes) => {
        const hours = Math.floor(minutes / 60);
        const rest = minutes % 60;

        if (!hours) return `${rest} min`;
        return rest ? `${hours} hr ${rest} min` : `${hours} hr`;
    };

    const peso = (value) =>
        '₱' + Number(value).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

    const [date, setDate] = useState('');

    const [time, setTime] = useState('');

    const today = new Date()
        .toISOString()
        .split('T')[0];

    const selectedServiceData = firstService;
    const serviceId = rows[0].serviceId;

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


            {/* SERVICE DROPDOWNS (one row per service in this visit) */}

            {rows.map((row, index) => (

                <div
                    key={index}
                    className={index === 0 ? '' : 'extra-service-row'}
                    style={index === 0 ? undefined : {
                        border: '1px solid var(--line)',
                        borderRadius: '18px',
                        padding: '14px',
                        margin: '14px 0',
                        background: 'var(--surface-soft)',
                    }}
                >

                    {maxServices > 1 && (
                        <div style={{
                            display: 'flex',
                            justifyContent: 'space-between',
                            alignItems: 'center',
                            fontWeight: 800,
                            marginBottom: '8px',
                        }}>
                            <span>Service {index + 1}</span>

                            {index > 0 && (
                                <button
                                    type="button"
                                    onClick={() => removeRow(index)}
                                    title="Remove this service"
                                    aria-label="Remove this service"
                                    style={{
                                        border: 0,
                                        background: 'transparent',
                                        color: '#d64b6b',
                                        fontSize: '22px',
                                        cursor: 'pointer',
                                    }}
                                >
                                    &times;
                                </button>
                            )}
                        </div>
                    )}

                    <div
                        className="service-dropdown-wrapper"
                        style={{ marginBottom: '14px' }}
                    >

                        <label htmlFor={index === 0 ? 'service_category' : `extra_category_${index}`}>
                            Type of service
                        </label>

                        <select
                            id={index === 0 ? 'service_category' : `extra_category_${index}`}
                            value={row.category}
                            onChange={(event) => {
                                updateRow(index, { category: event.target.value, serviceId: '' });
                            }}
                            required
                        >

                            <option value="">
                                -- Select a type of service --
                            </option>

                            {categoriesFor(index).map((name) => (

                                <option key={name} value={name}>
                                    {name}
                                </option>

                            ))}

                        </select>

                    </div>


                    <div className="service-dropdown-wrapper">

                        <label htmlFor={index === 0 ? 'service_id' : `extra_service_${index}`}>
                            Service
                        </label>

                        <select
                            id={index === 0 ? 'service_id' : `extra_service_${index}`}
                            name={index === 0 ? 'service_id' : 'extra_service_ids[]'}
                            value={row.serviceId}
                            onChange={(event) => {
                                updateRow(index, { serviceId: event.target.value });
                            }}
                            disabled={!row.category}
                            required
                        >

                            <option value="">
                                {row.category
                                    ? (optionsFor(index, row.category).length
                                        ? '-- Select a service --'
                                        : '-- No services available right now --')
                                    : '-- Choose a type of service first --'}
                            </option>

                            {optionsFor(index, row.category).map((service) => (

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

                </div>

            ))}


            {/* ADD ANOTHER SERVICE */}

            {maxServices > 1 && (

                <div style={{ margin: '4px 0 16px' }}>

                    <button
                        type="button"
                        onClick={addRow}
                        disabled={!canAdd}
                        style={{
                            width: '100%',
                            padding: '12px',
                            border: '2px dashed var(--mauve)',
                            borderRadius: '16px',
                            background: 'transparent',
                            color: 'var(--heading)',
                            font: 'inherit',
                            fontWeight: 800,
                            cursor: canAdd ? 'pointer' : 'not-allowed',
                            opacity: canAdd ? 1 : 0.5,
                        }}
                    >
                        ＋ Add another service
                    </button>

                    <p style={{ fontSize: '13px', margin: '6px 0 0', color: 'var(--mauve)' }}>
                        {mustBeAlone
                            ? 'A package or promo service must be booked on its own.'
                            : `As a ${levelName} you can book up to ${maxServices} services in one visit.`}
                    </p>

                </div>

            )}


            {/* SELECTED SERVICE INFORMATION */}

            {chosen.length > 1 && (

                <div className="selected-service-info" style={{ display: 'block' }}>

                    <strong>Your services</strong>

                    <ul style={{ listStyle: 'none', margin: '8px 0', padding: 0 }}>
                        {chosen.map((service) => (
                            <li
                                key={service.id}
                                style={{ display: 'flex', justifyContent: 'space-between', padding: '4px 0' }}
                            >
                                <span>{service.name}</span>
                                <strong>{peso(priceOf(service))}</strong>
                            </li>
                        ))}
                    </ul>

                    <div style={{ display: 'flex', justifyContent: 'space-between', fontWeight: 800 }}>
                        <span>Total</span>
                        <span>{peso(totalPrice)}</span>
                    </div>

                    {totalMinutes > 0 && (
                        <div style={{ marginTop: '4px', fontSize: '14px' }}>
                            ⏱ About {formatMinutes(totalMinutes)} in total
                        </div>
                    )}

                </div>

            )}

            {chosen.length <= 1 && selectedServiceData && (

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
                        {chosen.map((service) => service.name).join(' + ')}
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