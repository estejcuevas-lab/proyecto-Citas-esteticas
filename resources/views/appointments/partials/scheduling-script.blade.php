<script>
    const businessSelect = document.getElementById('business_id');
    const serviceSelect = document.getElementById('service_id');
    const startTimeInput = document.getElementById('start_time');
    const scheduleBox = document.getElementById('business-schedule');
    const summaryBox = document.getElementById('appointment-summary');
    const serviceOptions = Array.from(serviceSelect.querySelectorAll('option[data-business-id]'));
    const schedules = @json($schedules);

    function updateServices() {
        const selectedBusinessId = businessSelect.value;
        const currentServiceId = serviceSelect.value;

        serviceOptions.forEach((option) => {
            const visible = !selectedBusinessId || option.dataset.businessId === selectedBusinessId;
            option.hidden = !visible;
            option.disabled = !visible;
        });

        const selectedOption = serviceSelect.selectedOptions[0];

        if (!selectedOption || selectedOption.hidden) {
            serviceSelect.value = '';
        } else if (currentServiceId) {
            serviceSelect.value = currentServiceId;
        }
    }

    function updateSchedule() {
        const selectedBusinessId = businessSelect.value;
        const hours = schedules[selectedBusinessId] || [];

        if (!selectedBusinessId) {
            scheduleBox.textContent = 'Selecciona un negocio para ver sus horarios disponibles.';
            return;
        }

        if (!hours.length) {
            scheduleBox.textContent = 'Este negocio todavia no tiene horarios configurados.';
            return;
        }

        scheduleBox.innerHTML = hours.map((hour) => `${hour.day}: ${hour.is_active ? `${hour.opens_at} - ${hour.closes_at}` : 'No disponible'}`).join('<br>');
    }

    function updateSummary() {
        const selectedOption = serviceSelect.selectedOptions[0];
        const startTime = startTimeInput.value;

        if (!selectedOption || !selectedOption.dataset.duration || !startTime) {
            summaryBox.textContent = 'La hora de finalizacion se calcula segun la duracion del servicio.';
            return;
        }

        const [hours, minutes] = startTime.split(':').map(Number);
        const duration = Number(selectedOption.dataset.duration);
        const price = Number(selectedOption.dataset.price || 0);
        const advanceAmount = (price * 0.5).toFixed(2);
        const date = new Date();

        date.setHours(hours, minutes + duration, 0, 0);

        const endHours = String(date.getHours()).padStart(2, '0');
        const endMinutes = String(date.getMinutes()).padStart(2, '0');

        summaryBox.textContent = `Duracion: ${duration} min. Precio: $${price.toFixed(2)}. Adelanto: $${advanceAmount}. Fin estimado: ${endHours}:${endMinutes}.`;
    }

    businessSelect.addEventListener('change', () => {
        updateServices();
        updateSchedule();
        updateSummary();
    });

    serviceSelect.addEventListener('change', updateSummary);
    startTimeInput.addEventListener('input', updateSummary);

    updateServices();
    updateSchedule();
    updateSummary();
</script>
