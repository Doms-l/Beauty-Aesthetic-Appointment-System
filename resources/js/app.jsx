import React from 'react';
import { createRoot } from 'react-dom/client';
import AppointmentPicker from './components/AppointmentPicker';


// =========================================================
// APPOINTMENT PICKER
// =========================================================

const appointmentRoot = document.getElementById('appointment-picker');

if (appointmentRoot) {

    console.log('M. Cares React app loaded');

    const services = JSON.parse(
        appointmentRoot.dataset.services || '[]'
    );

    const selectedService =
        appointmentRoot.dataset.selectedService || '';

    console.log('Appointment picker found');

    console.log('Services:', services);

    createRoot(appointmentRoot).render(
        <AppointmentPicker
            services={services}
            selectedService={selectedService}
        />
    );
}


// =========================================================
// M. CARES CHATBOT
// =========================================================

document.addEventListener('DOMContentLoaded', () => {

    const chatToggle =
        document.getElementById('mcares-chat-toggle');

    const chatWindow =
        document.getElementById('mcares-chat-window');

    const chatClose =
        document.getElementById('mcares-chat-close');

    const chatForm =
        document.getElementById('mcares-chat-form');

    const chatInput =
        document.getElementById('mcares-chat-input');

    const chatMessages =
        document.getElementById('mcares-chat-messages');

    const quickQuestions =
        document.querySelectorAll(
            '.mcares-chat-quick button'
        );


    // ---------------------------------------------------------
    // CHECK CHATBOT ELEMENTS
    // ---------------------------------------------------------

    if (
        !chatToggle ||
        !chatWindow ||
        !chatClose ||
        !chatForm ||
        !chatInput ||
        !chatMessages
    ) {
        return;
    }


    // ---------------------------------------------------------
    // OPEN CHAT
    // ---------------------------------------------------------

    function openChat() {

        chatWindow.classList.add('is-open');

        chatWindow.setAttribute(
            'aria-hidden',
            'false'
        );

        chatToggle.setAttribute(
            'aria-expanded',
            'true'
        );

        setTimeout(() => {
            chatInput.focus();
        }, 250);
    }


    // ---------------------------------------------------------
    // CLOSE CHAT
    // ---------------------------------------------------------

    function closeChat() {

        chatWindow.classList.remove('is-open');

        chatWindow.setAttribute(
            'aria-hidden',
            'true'
        );

        chatToggle.setAttribute(
            'aria-expanded',
            'false'
        );
    }


    // ---------------------------------------------------------
    // TOGGLE CHAT
    // ---------------------------------------------------------

    chatToggle.addEventListener(
        'click',
        () => {

            if (
                chatWindow.classList.contains(
                    'is-open'
                )
            ) {

                closeChat();

            } else {

                openChat();

            }

        }
    );


    // ---------------------------------------------------------
    // CLOSE BUTTON
    // ---------------------------------------------------------

    chatClose.addEventListener(
        'click',
        closeChat
    );


    // ---------------------------------------------------------
    // ADD MESSAGE
    // ---------------------------------------------------------

    function addMessage(
        message,
        sender = 'bot'
    ) {

        const messageWrapper =
            document.createElement('div');

        messageWrapper.className =
            `mcares-chat-message ${sender}`;


        const bubble =
            document.createElement('div');

        bubble.className =
            'mcares-chat-bubble';


        // lets the bot show lists with line breaks
        if (sender === 'bot') {
            bubble.style.whiteSpace = 'pre-line';
        }


        /*
         * We use textContent instead of innerHTML
         * for user messages to prevent HTML injection.
         *
         * Bot responses below are also plain text.
         */

        bubble.textContent = message;


        messageWrapper.appendChild(
            bubble
        );

        chatMessages.appendChild(
            messageWrapper
        );


        // Scroll to newest message

        chatMessages.scrollTop =
            chatMessages.scrollHeight;

        return messageWrapper;
    }


    // ---------------------------------------------------------
    // PRICE LIST
    //
    // name  = what the bot says
    // price = the price text shown to the client
    // keys  = words a client might type (lowercase). The full
    //         service name is always included automatically.
    //
    // To change a price, just edit the price text below.
    // ---------------------------------------------------------

    const PRICE_LIST = [

        // ----- FACIAL SERVICES -----
        { name: 'Basic Facial', price: '₱350', keys: ['facial', 'facials'] },
        { name: 'Microdermabrasion / Diamond Peel', price: '₱499', keys: ['microdermabrasion', 'diamond peel', 'facial', 'facials'] },
        { name: 'Acne Treatment', price: '₱799', keys: ['acne', 'facial', 'facials'] },
        { name: 'Hydra Facial', price: '₱999', keys: ['hydra', 'hydrafacial', 'facial', 'facials'] },
        { name: 'Anti-Aging Facial', price: '₱699', keys: ['anti aging', 'antiaging', 'anti ageing', 'facial', 'facials'] },
        { name: 'Melasma Treatment', price: '₱599', keys: ['melasma', 'facial', 'facials'] },
        { name: 'Pico Carbon Laser Treatment', price: '₱1,999', keys: ['pico', 'carbon', 'carbon laser', 'facial', 'facials'] },
        { name: 'Oxygen Facial', price: '₱1,999', keys: ['oxygen', 'oxygeneo', 'facial', 'facials'] },
        { name: 'Korean BB Glow + BB Blush', price: '₱1,499', keys: ['bb glow', 'bb blush', 'korean', 'facial', 'facials'] },
        { name: 'Free Stemcell Facial', price: '₱3,999 / 4 sessions', keys: ['stemcell', 'stem cell', 'facial', 'facials'] },

        // ----- LASH & BROWS SERVICES -----
        { name: 'Lash Extension', price: '₱199–₱350', keys: ['lash extension', 'lash extensions', 'eyelash extension', 'eyelash extensions', 'lash', 'lashes', 'eyelash', 'eyelashes'] },
        { name: 'Lashlift', price: '₱350', keys: ['lash lift', 'lashlift', 'lash', 'lashes', 'eyelash', 'eyelashes'] },
        { name: 'Brow Tint', price: '₱150', keys: ['brow', 'brows', 'eyebrow', 'eyebrows'] },
        { name: 'Brow Lamination w/ Tint', price: '₱350', keys: ['brow lamination', 'lamination', 'brow', 'brows', 'eyebrow', 'eyebrows'] },
        { name: 'Microblading', price: '₱2,500', keys: ['microblading', 'brow', 'brows', 'eyebrow', 'eyebrows'] },
        {
            name: 'Micro Brows Retouch',
            price: '₱1,500',
            keys: ['micro brows', 'microbrows', 'retouch', 'recolor', 'brow', 'brows', 'eyebrow', 'eyebrows'],
            // PROMO NOTE - delete this line when the promo ends
            note: 'It is on promo right now: ₱999 with FREE lashes! 🎀',
        },
        { name: 'Lip Blush', price: '₱2,500', keys: ['lip blush', 'lip'] },
        { name: 'Lip Tattoo', price: '₱3,500', keys: ['lip tattoo', 'lip', 'tattoo'] },
        { name: 'Microshading', price: '₱3,000', keys: ['microshading', 'shading', 'brow', 'brows', 'eyebrow', 'eyebrows'] },
        { name: 'Ombre Shading', price: '₱3,500', keys: ['ombre', 'shading', 'brow', 'brows', 'eyebrow', 'eyebrows'] },
        { name: 'Eyeliner Tattoo', price: '₱1,999', keys: ['eyeliner', 'tattoo'] },

        // ----- OTHER SERVICES -----
        { name: 'UA Waxing', price: '₱250', keys: ['ua wax', 'ua waxing', 'underarm wax', 'underarm waxing', 'armpit wax', 'wax', 'waxing'] },
        { name: 'Leg Waxing', price: '₱500', keys: ['leg wax', 'leg waxing', 'wax', 'waxing'] },
        { name: 'Upper Lip Wax', price: '₱250', keys: ['upper lip', 'wax', 'waxing'] },
        { name: 'UA IPL Laser Hair Removal', price: '₱500', keys: ['ua ipl', 'underarm ipl', 'ipl', 'laser hair removal', 'hair removal'] },
        { name: 'Body IPL Laser Hair Removal', price: '₱999', keys: ['body ipl', 'ipl', 'laser hair removal', 'hair removal'] },
        { name: 'UA Whitening', price: '₱350', keys: ['ua whitening', 'underarm whitening', 'whitening'] },
        { name: 'RF Face', price: '₱350', keys: ['rf face', 'rf'] },
        { name: 'RF Body', price: '₱550', keys: ['rf body', 'rf'] },
        { name: 'HIFU Face', price: '₱999', keys: ['hifu'] },
        { name: 'Gel Polish', price: '₱350', keys: ['gel polish', 'gel', 'polish', 'manicure', 'nail', 'nails'] },
        { name: 'Nail Extension', price: '₱550', keys: ['nail extension', 'nail extensions', 'nail', 'nails'] },
        { name: 'Toe Nail Extension', price: '₱650', keys: ['toe nail extension', 'toe nail', 'toe', 'nail', 'nails'] },
        { name: 'Toe Gel Polish', price: '₱499', keys: ['toe gel polish', 'toe gel', 'toe', 'nail', 'nails'] },
        { name: 'Barbie Arms', price: '₱2,499', keys: ['barbie arms', 'barbie arm', 'barbie'] },
        { name: 'Face Botox', price: '₱7,999', keys: ['botox', 'face botox'] },
        { name: 'Warts Removal', price: '₱599', keys: ['warts', 'wart', 'warts removal'] },
        { name: 'Milia Removal', price: '₱799', keys: ['milia'] },
        { name: 'Syringoma Removal', price: '₱899', keys: ['syringoma'] },
        { name: 'Skin Tag Removal', price: '₱699', keys: ['skin tag', 'skin tags'] },
        { name: 'Tattoo Removal', price: '₱500–₱2,000', keys: ['tattoo removal', 'tattoo'] },
        { name: 'Scar Camouflage', price: '₱999', keys: ['scar', 'scars', 'camouflage'] },
        { name: 'Glutadrip', price: '₱3,500', keys: ['glutadrip', 'gluta', 'glutathione', 'drip'] },
        { name: 'Thermage', price: '₱1,999', keys: ['thermage'] },
        { name: 'Melano Out Melasma Meso', price: '₱4,999', keys: ['melano out', 'melano', 'meso', 'melasma'] },
        { name: 'Vitamin A (Acne Breakouts)', price: '₱4,999', keys: ['vitamin a', 'acne breakouts', 'breakouts', 'breakout', 'acne'] },
        { name: 'Hair Treatments', price: '₱300–₱600', keys: ['hair treatment', 'hair treatments', 'hair'] },
        { name: 'Rebond', price: '₱999', keys: ['rebond', 'rebonding'] },
        { name: 'Footspa', price: '₱300', keys: ['footspa', 'foot spa', 'foot'] },

    ];


    // words that mean "how much?"
    const PRICE_WORDS = [
        'price', 'prices', 'pricing', 'cost', 'costs',
        'how much', 'fee', 'fees', 'rate', 'rates',
        'magkano', 'presyo', 'charge', 'tagpila', 'precio', 'cuanto cuesta',
    ];

    // words that mean "I want to book"
    const BOOKING_WORDS = [
        'book', 'booking', 'appointment', 'schedule', 'reserve',
        'magbook', 'mag book', 'magpabook', 'magpa book', 'pabook',
        'iskedyul', 'reservar', 'cita',
    ];

    // words that mean "where is the clinic?" (English, Tagalog,
    // Cebuano/Waray, Spanish and a few others)
    const LOCATION_WORDS = [
        'where', 'location', 'located', 'address', 'directions', 'direction',
        'saan', 'nasaan', 'lokasyon', 'asa', 'diin', 'hain',
        'donde', 'ubicacion', 'direccion', 'adresse', 'standort',
    ];

    // same idea for languages that do not use a-z letters
    const LOCATION_WORDS_OTHER = [
        '哪里', '哪裡', '在哪', '地址', '位置',
        'どこ', '場所', '住所',
        '어디', '위치', '주소',
        'أين', 'عنوان',
        'где', 'адрес',
    ];

    const CLINIC_ADDRESS =
        'Brgy Bito Abuyog Leyte, Front of BV Closa Central School Back Gate';

    // contact details (keep in sync with config/chatbot.php)
    const CLINIC_PHONE = '09155168312';
    const CLINIC_FACEBOOK = 'https://www.facebook.com/macaylaanjeaneath.raejell';

    // words that mean "phone number / contact us"
    const CONTACT_WORDS = [
        'phone', 'number', 'contact', 'call', 'cellphone', 'mobile',
        'telephone', 'hotline', 'facebook', 'messenger', 'fb',
        'telepono', 'numero', 'tawag', 'tumawag', 'kontak', 'contacto',
        'telefono', 'llamar', 'telefon', 'telephone',
    ];

    const CONTACT_WORDS_OTHER = [
        '电话', '電話', '联系', '聯絡', '聯繫', '手机', '手機',
        '電話番号', 'でんわ', '連絡',
        '전화', '연락', '번호',
        'هاتف', 'رقم', 'اتصال',
        'телефон', 'номер', 'связь',
    ];


    // lowercase, remove symbols, pad with spaces so whole words match
    function normalizeText(text) {

        return ' ' +
            text
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]+/g, ' ')
                .trim() +
            ' ';
    }


    function mentionsAny(question, words) {

        const text = normalizeText(question);

        return words.some(function (word) {

            return text.includes(normalizeText(word));
        });
    }


    // finds the service(s) the client is asking about
    // (the longest / most specific match wins)
    function findServices(question) {

        const text = normalizeText(question);

        let best = 0;

        const scored = [];

        PRICE_LIST.forEach(function (service) {

            let longest = 0;

            [service.name].concat(service.keys).forEach(function (key) {

                const normalized = normalizeText(key);

                if (text.includes(normalized)) {

                    longest = Math.max(
                        longest,
                        normalized.trim().length
                    );
                }
            });

            if (longest > 0) {

                scored.push({ service: service, length: longest });

                best = Math.max(best, longest);
            }
        });

        return scored
            .filter(function (item) {
                return item.length === best;
            })
            .map(function (item) {
                return item.service;
            });
    }


    function formatPrices(services) {

        // ONE service
        if (services.length === 1) {

            const service = services[0];

            let reply =
                service.name + ' is ' + service.price + '.';

            if (service.note) {
                reply += ' ' + service.note;
            }

            reply +=
                ' You can book it anytime through Book Now ' +
                'on our website.';

            return reply;
        }

        // SEVERAL services
        return (
            'Here are the prices I found:\n' +
            services
                .map(function (service) {
                    return '• ' + service.name + ' – ' + service.price;
                })
                .join('\n') +
            '\n\nAsk me about one service to get its exact price.'
        );
    }


    const PRICE_SUMMARY =
        'Our prices depend on the service:\n' +
        '• Facial Services: from ₱350\n' +
        '• Lash & Brows Services: from ₱150\n' +
        '• Other Services: from ₱250\n\n' +
        'Ask me about a specific service, for example ' +
        '"How much is Gel Polish?", or open the Services page ' +
        'to see the full list.';


    // ---------------------------------------------------------
    // CHATBOT ANSWERS
    // ---------------------------------------------------------

    function getBotResponse(message) {

        const question =
            message
                .toLowerCase()
                .trim();


        // PRICES (asking about a specific service, e.g. "gel polish")

        const wantsPrice =
            mentionsAny(question, PRICE_WORDS);

        const wantsBooking =
            mentionsAny(question, BOOKING_WORDS);

        const wordCount =
            question.split(/\s+/).length;

        const matchedServices =
            findServices(question);

        if (
            matchedServices.length > 0 &&
            (wantsPrice || (wordCount <= 4 && !wantsBooking))
        ) {

            return formatPrices(matchedServices);
        }

        if (wantsPrice) {

            return PRICE_SUMMARY;
        }


        // PHONE / CONTACT

        if (
            mentionsAny(question, CONTACT_WORDS) ||
            CONTACT_WORDS_OTHER.some(function (word) {
                return question.includes(word);
            })
        ) {

            return (
                'You can reach M. Cares Beauty Services here:\n' +
                '📞 ' + CLINIC_PHONE + '\n' +
                '💬 Facebook: ' + CLINIC_FACEBOOK
            );
        }


        // SERVICES

        if (
            question.includes('service') ||
            question.includes('treatment') ||
            question.includes('facial') ||
            question.includes('lash') ||
            question.includes('aesthetic')
        ) {

            return (
                'We offer facial treatments like Hydra Facial, ' +
                'Korean BB Glow, Acne and Melasma Treatment; ' +
                'lash and brow services like Lash Extension, ' +
                'Lashlift, Microblading and Brow Lamination; ' +
                'and other treatments like Face Botox, Glutadrip, ' +
                'IPL Hair Removal, Gel Polish and Footspa. ' +
                'Ask me about any service to get its price, ' +
                'like "How much is Gel Polish?", or visit our ' +
                'Services page to see the full list.'
            );
        }


        // APPOINTMENT

        if (
            question.includes('appointment') ||
            question.includes('book') ||
            question.includes('booking') ||
            question.includes('schedule')
        ) {

            return (
                'Log in or register, then click Book Now. ' +
                'Choose your service, pick an available date and ' +
                'time, and send your appointment request. ' +
                'Our team will confirm it, and you can check its ' +
                'status anytime under Appointments.'
            );
        }


        // AMENITIES

        if (
            question.includes('amenit') ||
            question.includes('wifi') ||
            question.includes('wi-fi') ||
            question.includes('water') ||
            question.includes('coffee')
        ) {

            return (
                'We provide free Wi-Fi and free drinking water ' +
                'for all our clients while you wait or enjoy ' +
                'your treatment.'
            );
        }


        // CLINIC HOURS

        if (
            question.includes('hour') ||
            question.includes('open') ||
            question.includes('close')
        ) {

            return (
                'We are open every day, Monday to Sunday, ' +
                'from 8:00 AM to 8:00 PM. ' +
                'Booking online ahead is recommended.'
            );
        }


        // STAFF

        if (
            question.includes('staff') ||
            question.includes('aesthetician') ||
            question.includes('therapist') ||
            question.includes('who will')
        ) {

            return (
                'When booking an appointment, you can choose ' +
                'your preferred staff member if that staff member ' +
                'is available for the selected service and schedule.'
            );
        }


        // CANCEL

        if (
            question.includes('cancel') ||
            question.includes('cancellation')
        ) {

            return (
                'If you need to cancel your appointment, ' +
                'open your Appointments page and use the ' +
                'available cancellation option.'
            );
        }


        // PROFILE

        if (
            question.includes('profile') ||
            question.includes('account') ||
            question.includes('picture') ||
            question.includes('photo')
        ) {

            return (
                'You can manage your account information ' +
                'through your Profile page. Profile features ' +
                'may include your personal information and profile picture.'
            );
        }


        // LOCATION

        if (
            mentionsAny(question, LOCATION_WORDS) ||
            LOCATION_WORDS_OTHER.some(function (word) {
                return question.includes(word);
            })
        ) {

            return (
                'You can find us at:\n' +
                '📍 ' + CLINIC_ADDRESS + '\n\n' +
                'Call or message us:\n' +
                '📞 ' + CLINIC_PHONE
            );
        }


        // GREETING

        if (
            question === 'hi' ||
            question === 'hello' ||
            question === 'hey' ||
            question === 'hola' ||
            question === 'kumusta' ||
            question === 'kamusta' ||
            question.includes('maayong buntag') ||
            question.includes('maayong hapon') ||
            question.includes('maayong gabii') ||
            question.includes('good morning') ||
            question.includes('good afternoon') ||
            question.includes('good evening')
        ) {

            return (
                'Hello! 👋 Welcome to M. Cares Beauty Services. ' +
                'How can I help you today?'
            );
        }


        // THANK YOU

        if (
            question.includes('thank') ||
            question.includes('thanks') ||
            question.includes('salamat') ||
            question.includes('gracias')
        ) {

            return (
                'You are very welcome! 💗 ' +
                'We are happy to help. Let us know if you have ' +
                'another question about M. Cares Beauty Services.'
            );
        }


        // DEFAULT RESPONSE

        return (
            'I’m sorry, I don’t have an answer for that yet. ' +
            'You can ask me about our services, prices, ' +
            'appointments, staff, amenities, clinic hours, ' +
            'our location, cancellations, or your profile.'
        );
    }


    // ---------------------------------------------------------
    // ASK THE SERVER (ANSWERS IN ANY LANGUAGE)
    //
    // The server sends the question to the AI assistant, which
    // answers in the client's language. If the server cannot
    // answer (no API key, no internet, too many requests), the
    // built-in answers above are used instead.
    // ---------------------------------------------------------

    const chatEndpoint =
        chatForm.dataset.endpoint || '/chatbot';

    const csrfToken =
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content') || '';

    // last messages, so the assistant understands follow-up questions
    const chatHistory = [];

    let isSending = false;


    async function askServer(message) {

        const response = await fetch(
            chatEndpoint,
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    message: message,
                    history: chatHistory.slice(-10),
                }),
            }
        );

        if (!response.ok) {
            throw new Error('Chatbot server unavailable');
        }

        const data = await response.json();

        if (!data.reply) {
            throw new Error('Empty chatbot reply');
        }

        return data.reply;
    }


    // ---------------------------------------------------------
    // SEND MESSAGE
    // ---------------------------------------------------------

    async function sendMessage(message) {

        const cleanMessage =
            message.trim();


        if (!cleanMessage || isSending) {
            return;
        }

        isSending = true;


        // User message

        addMessage(
            cleanMessage,
            'user'
        );


        // Clear input

        chatInput.value = '';


        // "typing" bubble while we wait

        const typing = addMessage('…', 'bot');

        let response;

        try {

            response = await askServer(cleanMessage);

        } catch (error) {

            response = getBotResponse(cleanMessage);
        }

        typing.remove();

        addMessage(
            response,
            'bot'
        );

        chatHistory.push(
            { role: 'user', content: cleanMessage },
            { role: 'assistant', content: response }
        );

        isSending = false;
    }


    // ---------------------------------------------------------
    // FORM SUBMISSION
    // ---------------------------------------------------------

    chatForm.addEventListener(
        'submit',
        (event) => {

            event.preventDefault();

            sendMessage(
                chatInput.value
            );

        }
    );


    // ---------------------------------------------------------
    // QUICK QUESTIONS
    // ---------------------------------------------------------

    quickQuestions.forEach(
        (button) => {

            button.addEventListener(
                'click',
                () => {

                    const question =
                        button.dataset.question || '';

                    sendMessage(
                        question
                    );

                }
            );

        }
    );


    // ---------------------------------------------------------
    // ENTER KEY
    // ---------------------------------------------------------

    chatInput.addEventListener(
        'keydown',
        (event) => {

            if (
                event.key === 'Enter' &&
                !event.shiftKey
            ) {

                event.preventDefault();

                chatForm.requestSubmit();

            }

        }
    );


    // ---------------------------------------------------------
    // ESCAPE KEY
    // ---------------------------------------------------------

    document.addEventListener(
        'keydown',
        (event) => {

            if (
                event.key === 'Escape' &&
                chatWindow.classList.contains(
                    'is-open'
                )
            ) {

                closeChat();

            }

        }
    );

});