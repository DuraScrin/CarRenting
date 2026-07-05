<footer class="site-footer">
    <div class="container footer-inner">
        <p>&copy; <?php echo date('Y'); ?> GreenDrive Car Rental. All rights reserved.</p>
    </div>
</footer>
<script>
    (function () {
        function truncate(value, max) {
            if (typeof value !== 'string') {
                return '';
            }
            return value.length > max ? value.slice(0, max) : value;
        }

        function sendEvent(payload) {
            var body = JSON.stringify(payload);

            if (navigator.sendBeacon) {
                var blob = new Blob([body], { type: 'application/json' });
                navigator.sendBeacon('/api/events', blob);
                return;
            }

            fetch('/api/events', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: body,
                keepalive: true
            }).catch(function () {
                return null;
            });
        }

        document.addEventListener('click', function (event) {
            var element = event.target instanceof Element ? event.target.closest('[data-track-event],a,button') : null;
            if (!element) {
                return;
            }

            var eventName = element.getAttribute('data-track-event') || 'button_click';
            var targetType = element.getAttribute('data-track-type') || element.tagName.toLowerCase();
            var targetIdentifier =
                element.getAttribute('data-track-id') ||
                element.getAttribute('href') ||
                element.getAttribute('id') ||
                element.getAttribute('name') ||
                'unknown';

            sendEvent({
                event_name: eventName,
                page_path: window.location.pathname + window.location.search,
                target_type: truncate(targetType, 50),
                target_identifier: truncate(targetIdentifier, 100),
                metadata: {
                    source: 'ui_click'
                }
            });
        }, { capture: true });

        var bookingForm = document.querySelector('.booking-request-form');
        if (bookingForm) {
            bookingForm.addEventListener('submit', function () {
                sendEvent({
                    event_name: 'booking_submit_attempt',
                    page_path: window.location.pathname + window.location.search,
                    target_type: 'form',
                    target_identifier: 'booking-request-form',
                    metadata: {
                        source: 'booking_submit'
                    }
                });
            });
        }
    })();
</script>