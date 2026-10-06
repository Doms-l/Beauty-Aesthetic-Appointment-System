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
    }


    // ---------------------------------------------------------
    // CHATBOT ANSWERS
    // ---------------------------------------------------------

    function getBotResponse(message) {

        const question =
            message
                .toLowerCase()
                .trim();


        // SERVICES

        if (
            question.includes('service') ||
            question.includes('treatment') ||
            question.includes('facial') ||
            question.includes('lash') ||
            question.includes('aesthetic')
        ) {

            return (
                'M. Cares Beauty Services offers ' +
                'beauty and aesthetic services including ' +
                'facial treatments, aesthetic services, ' +
                'lash services, and other beauty treatments. ' +
                'Please visit our Services page to see the ' +
                'available services and prices.'
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
                'You can book an appointment through the ' +
                'Book Now option on the website. ' +
                'Choose your service, select the available ' +
                'date and time, and complete the appointment form.'
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
                'M. Cares Beauty Services provides ' +
                'client-friendly amenities such as free Wi-Fi ' +
                'and free drinking water. Other available ' +
                'refreshments and amenities may be shown ' +
                'during the appointment process.'
            );
        }


        // CLINIC HOURS

        if (
            question.includes('hour') ||
            question.includes('open') ||
            question.includes('close') ||
            question.includes('schedule')
        ) {

            return (
                'For the most accurate clinic hours, ' +
                'please check the clinic information provided ' +
                'on the website or contact M. Cares Beauty Services directly.'
            );
        }


        // PRICE

        if (
            question.includes('price') ||
            question.includes('cost') ||
            question.includes('how much') ||
            question.includes('fee')
        ) {

            return (
                'Service prices are available on the ' +
                'Services page. You can open the Services ' +
                'section to view the available treatments and prices.'
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
            question.includes('where') ||
            question.includes('location') ||
            question.includes('address')
        ) {

            return (
                'For the clinic location and contact information, ' +
                'please check the information provided on the ' +
                'M. Cares Beauty Services website.'
            );
        }


        // GREETING

        if (
            question === 'hi' ||
            question === 'hello' ||
            question === 'hey' ||
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
            question.includes('thanks')
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
            'cancellations, or your profile.'
        );
    }


    // ---------------------------------------------------------
    // SEND MESSAGE
    // ---------------------------------------------------------

    function sendMessage(message) {

        const cleanMessage =
            message.trim();


        if (!cleanMessage) {
            return;
        }


        // User message

        addMessage(
            cleanMessage,
            'user'
        );


        // Clear input

        chatInput.value = '';


        // Small delay for natural chatbot response

        setTimeout(() => {

            const response =
                getBotResponse(
                    cleanMessage
                );

            addMessage(
                response,
                'bot'
            );

        }, 400);
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