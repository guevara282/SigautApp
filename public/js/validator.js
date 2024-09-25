document.querySelector('form').addEventListener('submit', function(event) {
    const questionTextarea = document.getElementById('question');
    const affirmationTextarea = document.getElementById('affirmation');
    const justificationTextarea = document.getElementById('justification');
    const evidenceTextarea = document.getElementById('evidence');

    // Validar que la pregunta no esté vacía
    if (!questionTextarea.value.trim()) {
      alert('Por favor, ingrese una pregunta.');
      event.preventDefault();
      questionTextarea.focus();
      return;
    }

    // Solo validar los textareas si están visibles
    const additionalFields = document.getElementById('additionalFields');
    if (additionalFields.style.display !== 'none') {
      let valid = true; // Variable para controlar la validez

      // Validar afirmación
      if (!affirmationTextarea.value.trim()) {
        alert('Por favor, ingrese una afirmación.');
        affirmationTextarea.focus();
        valid = false;
      }

      // Validar justificación
      if (!justificationTextarea.value.trim()) {
        alert('Por favor, ingrese una justificación.');
        justificationTextarea.focus();
        valid = false;
      }

      // Validar evidencia
      if (!evidenceTextarea.value.trim()) {
        alert('Por favor, ingrese una evidencia.');
        evidenceTextarea.focus();
        valid = false;
      }

      // Si algún campo es inválido, prevenir el envío
      if (!valid) {
        event.preventDefault();
      }
    }
  });